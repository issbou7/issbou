<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/vendor/autoload.php";

date_default_timezone_set(TIMEZONE);
set_time_limit(50);

$db = MysqliDb::getInstance();
$now = time();
$messageGroups = $db->rawQuery("SELECT DISTINCT groupID, userID, deviceID, expiryDate, count(ID) as messages_count FROM Message WHERE schedule <= {$now} AND status='Scheduled' GROUP BY groupID, userID, deviceID, expiryDate");
Message::where("schedule", $now, '<=')
    ->where("status", "Scheduled")
    ->update_all(['status' => 'Pending', 'sentDate' => date("Y-m-d H:i:s")]);
foreach ($messageGroups as $group) {
    try {
        $groups = [];
        $device = User::getDevice($group["deviceID"], $group["userID"]);
        $groups[$device->getID()] = ["device" => $device, "data" => ["groupId" => $group["groupID"], "delay" => $device->getUser()->getDelay(), "reportDelivery" => $device->getUser()->getReportDelivery()]];
        Device::processRequests($groups);
    } catch (Exception $e) {
        Message::where("groupID", $group["groupID"])
            ->update_all(['status' => 'Failed']);
        if (empty($group["expiryDate"]) || new DateTime($group["expiryDate"]) >= new DateTime()) {
            $user = new User();
            $user->setID($group["userID"]);
            if ($user->read()) {
                if (!is_null($user->getCredits())) {
                    $user->setCredits($user->getCredits() + $group["messages_count"]);
                    $user->save();
                }
            }
        }
        error_log($e->getMessage());
    }
}

$lastRetry = Setting::get("last_retry_timestamp") ? (int)Setting::get("last_retry_timestamp") : 0;
$retryTimeInterval = Setting::get("retry_time_interval") ? (int)Setting::get("retry_time_interval") : 900;
if ($now >= $lastRetry + $retryTimeInterval) {
    $currentTime = date("Y-m-d H:i:s", $now);
    $data = Message::where("Status", "Failed")->where("sentDate >= DATE_SUB('{$currentTime}', INTERVAL {$retryTimeInterval} SECOND)")->read_all();
    $messages = [];
    foreach ($data as $message) {
        if (array_key_exists($message->getUserID(), $messages)) {
            array_push($messages[$message->getUserID()], $message);
        } else {
            $messages[$message->getUserID()] = [$message];
        }
    }

    foreach ($messages as $userID => $userMessages) {
        $user = new User();
        $user->setID($userID);
        $user->read();
        if ($user->getAutoRetry()) {
            Message::resend($userMessages, $user, true);
        }
    }
    Setting::apply([
        "last_retry_timestamp" => $now
    ]);
}

$jobs = Job::read_all();
foreach ($jobs as $job) {
    $job->execute();
    $job->delete();
}
