<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/includes/set-language.php";

date_default_timezone_set(TIMEZONE);

try {
    MysqliDb::getInstance()->startTransaction();
    $subscriptions = Subscription::where("Subscription.status", "ACTIVE")->read_all();
    foreach ($subscriptions as $subscription) {
        if ($subscription->getExpiryDate() < new DateTime()) {
            if ($subscription->getPlan()->getTotalCycles() == 0 || $subscription->getCyclesCompleted() < $subscription->getPlan()->getTotalCycles()) {
                $subscription->setCyclesCompleted($subscription->getCyclesCompleted() + 1);
                $expiryDate = date("Y-m-d H:i:s", $subscription->getExpiryDate()->getTimestamp() + $subscription->getPlan()->getFrequencyInSeconds());
                $subscription->setExpiryDate($expiryDate);
                $subscription->save();
                $subscription->renew($expiryDate);
            } else {
                $subscription->setStatus("EXPIRED");
                $subscription->save();
            }
        }
    }
    MysqliDb::getInstance()->commit();
} catch (Exception $e) {
    error_log($e->getMessage());
}
