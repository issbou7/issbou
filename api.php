<?php
/**
 * @var User $logged_in_user
 */
require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("api");

require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("api"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("api_test"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="generateApiLinkForm" method="post">
                        <div class="box-body">
                            <div id="ajaxResult"></div>
                            <div class="form-group">
                                <label for="apiInput"><?= __("api_key"); ?></label>
                                <input readonly type="text" name="api" class="form-control" id="apiInput"
                                       value="<?php echo $logged_in_user->getAPIKey(); ?>" required="required">
                            </div>
                            <div class="form-group">
                                <label for="mobileNumberInput"><?= __("mobile_numbers"); ?></label>
                                <input type="text" class="form-control" name="mobileNumber" id="mobileNumberInput"
                                       placeholder="<?= __("mobile_numbers_placeholder"); ?>"
                                       required="required">
                            </div>
                            <div class="form-group">
                                <label for="deviceInput"><?= __("device"); ?></label>
                                <select class="form-control select2" name="device" id="deviceInput" style="width: 100%;"
                                        required="required">
                                    <?php
                                    /** @var User $logged_in_user */
                                    $selectedDevice = $logged_in_user->getPrimaryDeviceID();
                                    $logged_in_user->generateDevicesList($selectedDevice);
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="simInput"><?= __("sim"); ?></label>
                                <select class="form-control select2" name="sim" id="simInput" style="width: 100%;">
                                    <option value="">Default</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="typeInput"><?= __("type"); ?></label>
                                <select class="form-control select2 type-input" id="typeInput" name="type"
                                        data-target="#file-input" style="width: 100%;">
                                    <option value="sms">SMS</option>
                                    <option value="mms">MMS</option>
                                </select>
                            </div>
                            <div class="form-group" id="file-input" hidden>
                                <label for="attachmentsInput">
                                    <?= __("attachments_links"); ?>
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                       title="<?= __('tooltip_attachments_links'); ?>"></i>
                                </label>
                                <input type="text" class="form-control" name="attachments" id="attachmentsInput"
                                       placeholder="https://example.com/example.png,https://example.com/example.jpg">
                            </div>
                            <div class="form-group">
                                <label for="messageInput"><?= __("message"); ?></label>
                                <textarea class="form-control" id="messageInput" name="message" rows="4"
                                          placeholder="<?= __("message"); ?>"
                                          required="required"></textarea>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" id="generateApiButton" name="generate"
                                    class="btn btn-primary"><i class="fa fa-link"></i>&nbsp;<?= __("generate_link"); ?>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("add_webhook"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="webHookURLInput"><?= __("webhook_url"); ?></label>
                            <input type="url" name="webHookURL" class="form-control" id="webHookURLInput"
                                   value="<?= $logged_in_user->getWebHook() ?>" required="required">
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="button" id="addWebHook"
                                class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;<?= __("save"); ?></button>&nbsp;

                        <button type="button" id="removeWebHook"
                                class="btn btn-danger"><i class="fa fa-remove"></i>&nbsp;<?= __("remove"); ?>
                        </button>
                    </div>
                </div>
                <!-- /.box -->

                <div class="box box-primary collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("webhook_example"); ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <h4><?= __("webhook_instruction"); ?></h4>
                        <pre class="prettyprint">
define(&#x22;API_KEY&#x22;, &#x22;<?= $logged_in_user->getApiKey() ?>&#x22;);

<?= htmlentities('try {
    if (isset($_SERVER["HTTP_X_SG_SIGNATURE"])) {
        $hash = base64_encode(hash_hmac(\'sha256\', $_POST["messages"], API_KEY, true));
        if ($hash === $_SERVER["HTTP_X_SG_SIGNATURE"]) {
            $messages = json_decode($_POST["messages"], true);

            /**
             * For example :-
             * $messages = [
             *                 0 => [
             *                          "ID" => "1",
             *                          "number" => "+911234567890",
             *                          "message" => "This is a test message.",
             *                          "deviceID" => "1",
             *                          "simSlot" => "0",
             *                          "userID" => "1",
             *                          "status" => "Received",
             *                          "sentDate" => "2018-10-20T00:00:00+02:00",
             *                          "deliveredDate" => "2018-10-20T00:00:00+02:00"
             *                          "groupID" => null
             *                      ]
             *             ]
             *
             * senDate represents the date and time when the message was received on the device.
             * deliveredDate represents the date and time when the message was received by the server.
             */

            foreach ($messages as $message) {
                if(strtolower($message["message"]) === "hi") {
                    // Reply to message using API or execute some commands. Possibilities are limitless.
                }
            }
        } else {
            http_response_code(401);
            error_log("Signature don\'t match!");
        }
    } else {
        http_response_code(400);
        error_log("Signature not found!");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
}') ?></pre>
                    </div>
                </div>

                <div class="box box-primary collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("php_integration"); ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <h4><?= __("php_integration_instruction"); ?></h4>
                        <pre class="prettyprint">
define(&#x22;SERVER&#x22;, &#x22;<?= getServerURL() ?>&#x22;);
define(&#x22;API_KEY&#x22;, &#x22;<?= $logged_in_user->getApiKey() ?>&#x22;);

<?=
htmlentities('define("USE_SPECIFIED", 0);
define("USE_ALL_DEVICES", 1);
define("USE_ALL_SIMS", 2);

/**
 * @param string     $number      The mobile number where you want to send message.
 * @param string     $message     The message you want to send.
 * @param int|string $device      The ID of a device you want to use to send this message.
 * @param int        $schedule    Set it to timestamp when you want to send this message.
 * @param bool       $isMMS       Set it to true if you want to send MMS message instead of SMS.
 * @param string     $attachments Comma separated list of image links you want to attach to the message. Only works for MMS messages.
 *
 * @return array     Returns The array containing information about the message.
 * @throws Exception If there is an error while sending a message.
 */
function sendSingleMessage($number, $message, $device = 0, $schedule = null, $isMMS = false, $attachments = null)
{
    $url = SERVER . "/services/send.php";
    $postData = array(
        \'number\' => $number,
        \'message\' => $message,
        \'schedule\' => $schedule,
        \'key\' => API_KEY,
        \'devices\' => $device,
        \'type\' => $isMMS ? "mms" : "sms",
        \'attachments\' => $attachments
    );
    return sendRequest($url, $postData)["messages"][0];
}

/**
 * @param array  $messages        The array containing numbers and messages.
 * @param int    $option          Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
 *                                Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
 *                                Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.
 * @param array  $devices         The array of ID of devices you want to use to send these messages.
 * @param int    $schedule        Set it to timestamp when you want to send these messages.
 * @param bool   $useRandomDevice Set it to true if you want to send messages using only one random device from selected devices.
 *
 * @return array     Returns The array containing messages.
 *                   For example :-
 *                   [
 *                      0 => [
 *                              "ID" => "1",
 *                              "number" => "+911234567890",
 *                              "message" => "This is a test message.",
 *                              "deviceID" => "1",
 *                              "simSlot" => "0",
 *                              "userID" => "1",
 *                              "status" => "Pending",
 *                              "type" => "sms",
 *                              "attachments" => null,
 *                              "sentDate" => "2018-10-20T00:00:00+02:00",
 *                              "deliveredDate" => null
 *                              "groupID" => ")V5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871"
 *                           ]
 *                   ]
 * @throws Exception If there is an error while sending messages.
 */
function sendMessages($messages, $option = USE_SPECIFIED, $devices = [], $schedule = null, $useRandomDevice = false)
{
    $url = SERVER . "/services/send.php";
    $postData = [
        \'messages\' => json_encode($messages),
        \'schedule\' => $schedule,
        \'key\' => API_KEY,
        \'devices\' => json_encode($devices),
        \'option\' => $option,
        \'useRandomDevice\' => $useRandomDevice
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int    $listID      The ID of the contacts list where you want to send this message.
 * @param string $message     The message you want to send.
 * @param int    $option      Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
 *                            Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
 *                            Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.
 * @param array  $devices     The array of ID of devices you want to use to send the message.
 * @param int    $schedule    Set it to timestamp when you want to send this message.
 * @param bool   $isMMS       Set it to true if you want to send MMS message instead of SMS.
 * @param string $attachments Comma separated list of image links you want to attach to the message. Only works for MMS messages.
 *
 * @return array     Returns The array containing messages.
 * @throws Exception If there is an error while sending messages.
 */
function sendMessageToContactsList($listID, $message, $option = USE_SPECIFIED, $devices = [], $schedule = null, $isMMS = false, $attachments = null)
{
    $url = SERVER . "/services/send.php";
    $postData = [
        \'listID\' => $listID,
        \'message\' => $message,
        \'schedule\' => $schedule,
        \'key\' => API_KEY,
        \'devices\' => json_encode($devices),
        \'option\' => $option,
        \'type\' => $isMMS ? "mms" : "sms",
        \'attachments\' => $attachments
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int $id The ID of a message you want to retrieve.
 *
 * @return array     The array containing a message.
 * @throws Exception If there is an error while getting a message.
 */
function getMessageByID($id)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        \'key\' => API_KEY,
        \'id\' => $id
    ];
    return sendRequest($url, $postData)["messages"][0];
}

/**
 * @param string $groupID The group ID of messages you want to retrieve.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while getting messages.
 */
function getMessagesByGroupID($groupID)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        \'key\' => API_KEY,
        \'groupId\' => $groupID
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param string $status         The status of messages you want to retrieve.
 * @param int    $startTimestamp Search for messages sent or received after this time.
 * @param int    $endTimestamp   Search for messages sent or received before this time.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while getting messages.
 */
function getMessagesByStatus($status, $startTimestamp = null, $endTimestamp = null)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        \'key\' => API_KEY,
        \'status\' => $status,
        \'startTimestamp\' => $startTimestamp,
        \'endTimestamp\' => $endTimestamp
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int $id The ID of a message you want to resend.
 *
 * @return array     The array containing a message.
 * @throws Exception If there is an error while resending a message.
 */
function resendMessageByID($id)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        \'key\' => API_KEY,
        \'id\' => $id
    ];
    return sendRequest($url, $postData)["messages"][0];
}

/**
 * @param string $groupID The group ID of messages you want to resend.
 * @param string $status  The status of messages you want to resend.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while resending messages.
 */
function resendMessagesByGroupID($groupID, $status = null)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        \'key\' => API_KEY,
        \'groupId\' => $groupID,
        \'status\' => $status
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param string $status         The status of messages you want to retrieve.
 * @param int    $startTimestamp Resend messages sent or received after this time.
 * @param int    $endTimestamp   Resend messages sent or received before this time.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while resending messages.
 */
function resendMessagesByStatus($status, $startTimestamp = null, $endTimestamp = null)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        \'key\' => API_KEY,
        \'status\' => $status,
        \'startTimestamp\' => $startTimestamp,
        \'endTimestamp\' => $endTimestamp
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int    $listID      The ID of the contacts list where you want to add this contact.
 * @param string $number      The mobile number of the contact.
 * @param string $name        The name of the contact.
 * @param bool   $resubscribe Set it to true if you want to resubscribe this contact if it already exists.
 *
 * @return array     The array containing a newly added contact.
 * @throws Exception If there is an error while adding a new contact.
 */
function addContact($listID, $number, $name = null, $resubscribe = false)
{
    $url = SERVER . "/services/manage-contacts.php";
    $postData = [
        \'key\' => API_KEY,
        \'listID\' => $listID,
        \'number\' => $number,
        \'name\' => $name,
        \'resubscribe\' => $resubscribe
    ];
    return sendRequest($url, $postData)["contact"];
}

/**
 * @param int    $listID The ID of the contacts list from which you want to unsubscribe this contact.
 * @param string $number The mobile number of the contact.
 *
 * @return array     The array containing the unsubscribed contact.
 * @throws Exception If there is an error while setting subscription to false.
 */
function unsubscribeContact($listID, $number)
{
    $url = SERVER . "/services/manage-contacts.php";
    $postData = [
        \'key\' => API_KEY,
        \'listID\' => $listID,
        \'number\' => $number,
        \'unsubscribe\' => true
    ];
    return sendRequest($url, $postData)["contact"];
}

/**
 * @return string    The amount of message credits left.
 * @throws Exception If there is an error while getting message credits.
 */
function getBalance()
{
    $url = SERVER . "/services/send.php";
    $postData = [
        \'key\' => API_KEY
    ];
    $credits = sendRequest($url, $postData)["credits"];
    return is_null($credits) ? "Unlimited" : $credits;
}

function sendRequest($url, $postData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }
    curl_close($ch);
    if ($httpCode == 200) {
        $json = json_decode($response, true);
        if ($json == false) {
            if (empty($response)) {
                throw new Exception("Missing data in request. Please provide all the required information to send messages.");
            } else {
                throw new Exception($response);
            }
        } else {
            if ($json["success"]) {
                return $json["data"];
            } else {
                throw new Exception($json["error"]["message"]);
            }
        }
    } else {
        throw new Exception("HTTP Error Code : {$httpCode}");
    }
}');
?></pre>
                        <h4><?= __("send_single_message"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Send a message using the primary device.
    $msg = sendSingleMessage("+911234567890", "This is a test of single message.");

    // Send a message using the Device ID 1.
    $msg = sendSingleMessage("+911234567890", "This is a test of single message.", 1);
    
    // Send a MMS message with image using the Device ID 1.
    $attachments = "https://rbsoft.org/images/footer-logo.png,https://rbsoft.org/downloads/sms-gateway/images/section/create-chat-bot.png";
    $msg = sendSingleMessage("+911234567890", "This is a test of single message.", 1, null, true, $attachments);
	
    // Send a message using the SIM in slot 1 of Device ID 1 (Represented as "1|0").
    // SIM slot is an index so the index of the first SIM is 0 and the index of the second SIM is 1.
    // In this example, 1 represents Device ID and 0 represents SIM slot index.
    $msg = sendSingleMessage("+911234567890", "This is a test of single message.", "1|0");

    // Send scheduled message using the primary device.
    $msg = sendSingleMessage("+911234567890", "This is a test of schedule feature.", null, strtotime("+2 minutes"));
    print_r($msg);

    echo "Successfully sent a message.";
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("send_bulk_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('$messages = array();

for ($i = 1; $i <= 12; $i++) {
    array_push($messages,
        [
            "number" => "+911234567890",
            "message" => "This is a test #{$i} of PHP version. Testing bulk message functionality."
        ]);
}

try {
    // Send messages using the primary device.
    sendMessages($messages);

    // Send messages using default SIM of all available devices. Messages will be split between all devices.
    sendMessages($messages, USE_ALL_DEVICES);
	
    // Send messages using all SIMs of all available devices. Messages will be split between all SIMs.
    sendMessages($messages, USE_ALL_SIMS);

    // Send messages using only specified devices. Messages will be split between devices or SIMs you specified.
    // If you send 12 messages using this code then 4 messages will be sent by Device ID 1, other 4 by SIM in slot 1 of 
    // Device ID 2 (Represendted as "2|0") and remaining 4 by SIM in slot 2 of Device ID 2 (Represendted as "2|1").
    sendMessages($messages, USE_SPECIFIED, [1, "2|0", "2|1"]);
    
    // Send messages on schedule using the primary device.
    sendMessages($messages, null, null, strtotime("+2 minutes"));
    
    // Send a message to contacts in contacts list with ID of 1.
    sendMessageToContactsList(1, "Test", USE_SPECIFIED, 1);
    
    // Send a message on schedule to contacts in contacts list with ID of 1.
    sendMessageToContactsList(1, "Test", null, null, strtotime("+2 minutes"));
    
    // Array of image links to attach to MMS message;
    $attachments = [
        "https://rbsoft.org/images/footer-logo.png",
        "https://rbsoft.org/downloads/sms-gateway/images/section/create-chat-bot.png"
    ];
    $attachments = implode(\',\', $attachments);
    
    $mmsMessages = [];
    for ($i = 1; $i <= 12; $i++) {
        array_push($mmsMessages,
            [
                "number" => "+911234567890",
                "message" => "This is a test #{$i} of PHP version. Testing bulk MMS message functionality.",
                "type" => "mms",
                "attachments" => $attachments
            ]);
    }
    // Send MMS messages using all SIMs of all available devices. Messages will be split between all SIMs.
    $msgs = sendMessages($mmsMessages, USE_ALL_SIMS);
    
    print_r($msgs);

    echo "Successfully sent bulk messages.";
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("get_balance"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    $credits = getBalance();
    echo "Message Credits Remaining: {$credits}";
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("get_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Get a message using the ID.
    $msg = getMessageByID(1);
    print_r($msg);

    // Get messages using the Group ID.
    $msgs = getMessagesByGroupID(\')V5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871\');
    print_r($msgs);
    
    // Get messages received in last 24 hours.
    $msgs = getMessagesByStatus("Received", time() - 86400);
    print_r($msgs);
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("resend_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Resend a message using the ID.
    $msg = resendMessageByID(1);
    print_r($msg);

    // Get messages using the Group ID and Status.
    $msgs = resendMessagesByGroupID(\'LV5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871\', \'Failed\');
    print_r($msgs);
    
    // Resend pending messages in last 24 hours.
    $msgs = resendMessagesByStatus("Pending", time() - 86400);
    print_r($msgs);
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("manage_contacts"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Add a new contact to contacts list 1 or resubscribe the contact if it already exists.
    $contact = addContact(1, "+911234567890", "Test", true);
    print_r($contact);
    
    // Unsubscribe a contact using the mobile number.
    $contact = unsubscribeContact(1, "+911234567890");
    print_r($contact);
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div class="box box-primary collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("c#_integration"); ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <h4><?= __("c#_integration_instruction"); ?></h4>
                        <pre class="prettyprint"><?=
                            htmlentities('using System;
using System.Collections.Generic;
using System.IO;
using System.Net;
using System.Text;
using System.Web;
using Gateway_Sample_Application.Properties;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;

namespace SMS
{
    static class API
    {
        private static readonly string Server = "' . getServerURL() . '";
        private static readonly string Key = "' . $logged_in_user->getApiKey() . '";

        public enum Option
        {
            USE_SPECIFIED = 0,
            USE_ALL_DEVICES = 1,
            USE_ALL_SIMS = 2
        }

        /// <summary>
        /// Send single message to specific mobile number.
        /// </summary>
        /// <param name="number">The mobile number where you want to send message.</param>
        /// <param name="message">The message you want to send.</param>
        /// <param name="device">The ID of a device you want to use to send this message.</param>
        /// <param name="schedule">Set it to timestamp when you want to send this message.</param>
        /// <param name="isMMS">Set it to true if you want to send MMS message instead of SMS.</param>
        /// <param name="attachments">Comma separated list of image links you want to attach to the message. Only works for MMS messages.</param>
        /// <exception>If there is an error while sending a message.</exception>
        /// <returns>The dictionary containing information about the message.</returns>
        public static Dictionary<string, object> SendSingleMessage(string number, string message, string device = "0",
            long? schedule = null, bool isMMS = false, string attachments = null)
        {
            var values = new Dictionary<string, object>
            {
                {"number", number},
                {"message", message},
                {"schedule", schedule},
                {"key", Key},
                {"devices", device},
                {"type", isMMS ? "mms" : "sms"},
                {"attachments", attachments}
            };

            return GetMessages(GetResponse($"{Server}/services/send.php", values)["messages"])[0];
        }

        /// <summary>
        /// Send multiple messages to different mobile numbers.
        /// </summary>
        /// <param name="messages">The array containing numbers and messages.</param>
        /// <param name="option">Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
        /// Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
        /// Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.</param>
        /// <param name="devices">The array of ID of devices you want to use to send these messages.</param>
        /// <param name="schedule">Set it to timestamp when you want to send this message.</param>
        /// <param name="useRandomDevice">Set it to true if you want to send messages using only one random device from selected devices.</param>
        /// <exception>If there is an error while sending messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] SendMessages(List<Dictionary<string, string>> messages,
            Option option = Option.USE_SPECIFIED, string[] devices = null, long? schedule = null,
            bool useRandomDevice = false)
        {
            var values = new Dictionary<string, object>
            {
                {"messages", JsonConvert.SerializeObject(messages)},
                {"schedule", schedule},
                {"key", Key},
                {"devices", devices},
                {"option", (int) option},
                {"useRandomDevice", useRandomDevice}
            };

            return GetMessages(GetResponse($"{Server}/services/send.php", values)["messages"]);
        }

        /// <summary>
        /// Send a message to contacts in specified contacts list.
        /// </summary>
        /// <param name="listID">The ID of the contacts list where you want to send this message.</param>
        /// <param name="message">The message you want to send.</param>
        /// <param name="option">Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
        /// Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
        /// Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.</param>
        /// <param name="devices">The array of ID of devices you want to use to send these messages.</param>
        /// <param name="schedule">Set it to timestamp when you want to send this message.</param>
        /// <param name="isMMS">Set it to true if you want to send MMS message instead of SMS.</param>
        /// <param name="attachments">Comma separated list of image links you want to attach to the message. Only works for MMS messages.</param>
        /// <exception>If there is an error while sending messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] SendMessageToContactsList(int listID, string message,
            Option option = Option.USE_SPECIFIED, string[] devices = null, long? schedule = null, bool isMMS = false,
            string attachments = null)
        {
            var values = new Dictionary<string, object>
            {
                {"listID", listID},
                {"message", message},
                {"schedule", schedule},
                {"key", Key},
                {"devices", devices},
                {"option", (int) option},
                {"type", isMMS ? "mms" : "sms"},
                {"attachments", attachments}
            };

            return GetMessages(GetResponse($"{Server}/services/send.php", values)["messages"]);
        }

        /// <summary>
        /// Get a message using the ID.
        /// </summary>
        /// <param name="id">The ID of a message you want to retrieve.</param>
        /// <exception>If there is an error while getting a message.</exception>
        /// <returns>The dictionary containing information about the message.</returns>
        public static Dictionary<string, object> GetMessageByID(int id)
        {
            var values = new Dictionary<string, object>
            {
                {"key", Key},
                {"id", id}
            };

            return GetMessages(GetResponse($"{Server}/services/read-messages.php", values)["messages"])[0];
        }

        /// <summary>
        /// Get messages using the Group ID.
        /// </summary>
        /// <param name="groupID">The group ID of messages you want to retrieve.</param>
        /// <exception>If there is an error while getting messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] GetMessagesByGroupID(string groupID)
        {
            var values = new Dictionary<string, object>
            {
                {"key", Key},
                {"groupId", groupID}
            };

            return GetMessages(GetResponse($"{Server}/services/read-messages.php", values)["messages"]);
        }

        /// <summary>
        /// Get messages using the status.
        /// </summary>
        /// <param name="status">The status of messages you want to retrieve.</param>
        /// <param name="startTimestamp">Search for messages sent or received after this time.</param>
        /// <param name="endTimestamp">Search for messages sent or received before this time.</param>
        /// <exception>If there is an error while getting messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] GetMessagesByStatus(string status, long? startTimestamp = null,
            long? endTimestamp = null)
        {
            var values = new Dictionary<string, object>
            {
                {"key", Key},
                {"status", status},
                {"startTimestamp", startTimestamp},
                {"endTimestamp", endTimestamp}
            };

            return GetMessages(GetResponse($"{Server}/services/read-messages.php", values)["messages"]);
        }

        /// <summary>
        /// Resend a message using the ID.
        /// </summary>
        /// <param name="id">The ID of a message you want to resend.</param>
        /// <exception>If there is an error while resending a message.</exception>
        /// <returns>The dictionary containing information about the message.</returns>
        public static Dictionary<string, object> ResendMessageByID(int id)
        {
            var values = new Dictionary<string, object>
            {
                {"key", Key},
                {"id", id}
            };

            return GetMessages(GetResponse($"{Server}/services/resend.php", values)["messages"])[0];
        }

        /// <summary>
        /// Resend messages using the Group ID.
        /// </summary>
        /// <param name="groupID">The group ID of messages you want to resend.</param>
        /// <param name="status">The status of messages you want to resend.</param>
        /// <exception>If there is an error while resending messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] ResendMessagesByGroupID(string groupID, string status = null)
        {
            var values = new Dictionary<string, object>
            {
                {"key", Key},
                {"groupId", groupID},
                {"status", status}
            };

            return GetMessages(GetResponse($"{Server}/services/resend.php", values)["messages"]);
        }

        /// <summary>
        /// Resend messages using the status.
        /// </summary>
        /// <param name="status">The status of messages you want to resend.</param>
        /// <param name="startTimestamp">Resend messages sent or received after this time.</param>
        /// <param name="endTimestamp">Resend messages sent or received before this time.</param>
        /// <exception>If there is an error while resending messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] ResendMessagesByStatus(string status, long? startTimestamp = null,
            long? endTimestamp = null)
        {
            var values = new Dictionary<string, object>
            {
                {"key", Key},
                {"status", status},
                {"startTimestamp", startTimestamp},
                {"endTimestamp", endTimestamp}
            };

            return GetMessages(GetResponse($"{Server}/services/resend.php", values)["messages"]);
        }

        /// <summary>
        /// Add a new contact to contacts list.
        /// </summary>
        /// <param name="listID">The ID of the contacts list where you want to add this contact.</param>
        /// <param name="number">The mobile number of the contact.</param>
        /// <param name="name">The name of the contact.</param>
        /// <param name="resubscribe">Set it to true if you want to resubscribe this contact if it already exists.</param>
        /// <returns>A dictionary containing details about a newly added contact.</returns>
        public static Dictionary<string, object> AddContact(int listID, string number, string name = null,
            bool resubscribe = false)
        {
            var values = new Dictionary<string, object>
            {
                {"key", Key},
                {"listID", listID},
                {"number", number},
                {"name", name},
                {"resubscribe", resubscribe ? \'1\' : \'0\'},
            };
            JObject jObject = (JObject) GetResponse($"{Server}/services/manage-contacts.php", values)["contact"];
            return jObject.ToObject<Dictionary<string, object>>();
        }

        /// <summary>
        /// Unsubscribe a contact from the contacts list.
        /// </summary>
        /// <param name="listID">The ID of the contacts list from which you want to unsubscribe this contact.</param>
        /// <param name="number">The mobile number of the contact.</param>
        /// <returns>A dictionary containing details about the unsubscribed contact.</returns>
        public static Dictionary<string, object> UnsubscribeContact(int listID, string number)
        {
            var values = new Dictionary<string, object>
            {
                {"key", Key},
                {"listID", listID},
                {"number", number},
                {"unsubscribe", \'1\'}
            };
            JObject jObject = (JObject) GetResponse($"{Server}/services/manage-contacts.php", values)["contact"];
            return jObject.ToObject<Dictionary<string, object>>();
        }

        /// <summary>
        /// Get remaining message credits.
        /// </summary>
        /// <exception>If there is an error while getting message credits.</exception>
        /// <returns>The amount of message credits left.</returns>
        public static string GetBalance()
        {
            var values = new Dictionary<string, object>
            {
                {"key", Key}
            };
            JToken credits = GetResponse($"{Server}/services/send.php", values)["credits"];
            if (credits.Type != JTokenType.Null)
            {
                return credits.ToString();
            }

            return "Unlimited";
        }

        private static Dictionary<string, object>[] GetMessages(JToken messagesJToken)
        {
            JArray jArray = (JArray) messagesJToken;
            var messages = new Dictionary<string, object>[jArray.Count];
            for (var index = 0; index < jArray.Count; index++)
            {
                messages[index] = jArray[index].ToObject<Dictionary<string, object>>();
            }

            return messages;
        }

        private static JToken GetResponse(string url, Dictionary<string, object> postData)
        {
            var request = (HttpWebRequest) WebRequest.Create(url);
            var dataString = CreateDataString(postData);
            var data = Encoding.UTF8.GetBytes(dataString);

            request.Method = "POST";
            request.ContentType = "application/x-www-form-urlencoded";
            request.ContentLength = data.Length;
            ServicePointManager.Expect100Continue = true;
            ServicePointManager.SecurityProtocol = SecurityProtocolType.Tls12;
            using (var stream = request.GetRequestStream())
            {
                stream.Write(data, 0, data.Length);
            }

            var response = (HttpWebResponse) request.GetResponse();

            if (response.StatusCode == HttpStatusCode.OK)
            {
                using (StreamReader streamReader = new StreamReader(response.GetResponseStream()))
                {
                    var jsonResponse = streamReader.ReadToEnd();
                    try
                    {
                        JObject jObject = JObject.Parse(jsonResponse);
                        if ((bool) jObject["success"])
                        {
                            return jObject["data"];
                        }

                        throw new Exception(jObject["error"]["message"].ToString());
                    }
                    catch (JsonReaderException)
                    {
                        if (string.IsNullOrEmpty(jsonResponse))
                        {
                            throw new InvalidDataException(
                                "Missing data in request. Please provide all the required information to send messages.");
                        }

                        throw new Exception(jsonResponse);
                    }
                }
            }

            throw new WebException($"HTTP Error : {(int) response.StatusCode} {response.StatusCode}");
        }

        private static string CreateDataString(Dictionary<string, object> data)
        {
            StringBuilder dataString = new StringBuilder();
            bool first = true;
            foreach (var obj in data)
            {
                if (obj.Value != null)
                {
                    if (first)
                    {
                        first = false;
                    }
                    else
                    {
                        dataString.Append("&");
                    }

                    dataString.Append(HttpUtility.UrlEncode(obj.Key));
                    dataString.Append("=");
                    dataString.Append(obj.Value is string[]
                        ? HttpUtility.UrlEncode(JsonConvert.SerializeObject(obj.Value))
                        : HttpUtility.UrlEncode(obj.Value.ToString()));
                }
            }

            return dataString.ToString();
        }
    }
}');
                            ?></pre>
                        <h4><?= __("send_single_message"); ?></h4>
                        <pre class="prettyprint"><?=
                            htmlentities('try
{
    // Send a message using the primary device.
    SMS.API.SendSingleMessage("+911234567890", "This is a test of single message.");

    // Send a message using the Device ID 1.
    Dictionary<string, object> message = SMS.API.SendSingleMessage("+911234567890", "This is a test of single message.", "1");
    
    // Send a MMS message using the Device ID 1.
    string attachments = "https://rbsoft.org/images/footer-logo.png,https://rbsoft.org/downloads/sms-gateway/images/section/create-chat-bot.png";
    Dictionary<string, object> message = SMS.API.SendSingleMessage("+911234567890", "This is a test of single message.", "1", null, true, attachments);
	
    // Send a message using the SIM in slot 1 of Device ID 1 (Represented as "1|0").
    // SIM slot is an index so the index of the first SIM is 0 and the index of the second SIM is 1.
    // In this example, 1 represents Device ID and 0 represents SIM slot index.
    Dictionary<string, object> message = SMS.API.SendSingleMessage("+911234567890", "This is a test of single message.", "1|0");

    // Send scheduled message using the primary device.
    long timestamp = (long) DateTime.UtcNow.AddMinutes(2).Subtract(new DateTime(1970, 1, 1)).TotalSeconds;
    Dictionary<string, object> message = SendSingleMessage(textBoxNumber.Text, textBoxMessage.Text, null, timestamp);
    
    MessageBox.Show("Successfully sent a message.");
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
                            ?></pre>
                        <h4><?= __("send_bulk_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('List<Dictionary<string, string>> messages = new List<Dictionary<string, string>>();
for (int i = 1; i <= 12; i++)
{
    var message = new Dictionary<string, string>
    {
        { "number", "+911234567890" },
        { "message", "This is a test #{$i} of C# version. Testing bulk message functionality." }
    };
    messages.Add(message);
}

try
{
    // Send messages using the primary device.
    SMS.API.SendMessages(messages);

    // Send messages using default SIM of all available devices. Messages will be split between all devices.
    SMS.API.SendMessages(messages, SMS.API.Option.USE_ALL_DEVICES);
	
    // Send messages using all SIMs of all available devices. Messages will be split between all SIMs.
    SMS.API.SendMessages(messages, SMS.API.Option.USE_ALL_SIMS);

    // Send messages using only specified devices. Messages will be split between devices or SIMs you specified.
    // If you send 12 messages using this code then 4 messages will be sent by Device ID 1, other 4 by SIM in slot 1 of 
    // Device ID 2 (Represendted as "2|0") and remaining 4 by SIM in slot 2 of Device ID 2 (Represendted as "2|1").
    SMS.API.SendMessages(messages, SMS.API.Option.USE_SPECIFIED, new [] {"1", "2|0", "2|1"});
    
    // Send messages on schedule using the primary device.
    long timestamp = (long) DateTime.UtcNow.AddMinutes(2).Subtract(new DateTime(1970, 1, 1)).TotalSeconds;
    Dictionary<string, object>[] messages = SMS.API.SendMessages(messages, Option.USE_SPECIFIED, null, timestamp);
    
    // Send a message to contacts in contacts list with ID of 1.
    Dictionary<string, object>[] messages = SMS.API.SendMessageToContactsList(1, "Test", SMS.API.Option.USE_SPECIFIED, new [] {"1"});

    // Send a message on schedule to contacts in contacts list with ID of 1.
    Dictionary<string, object>[] messages = SMS.API.SendMessageToContactsList(1, "Test #1", Option.USE_SPECIFIED, null, timestamp);
    
    string attachments = "https://rbsoft.org/images/footer-logo.png,https://rbsoft.org/downloads/sms-gateway/images/section/create-chat-bot.png";
    List<Dictionary<string, string>> mmsMessages = new List<Dictionary<string, string>>();
    for (int i = 1; i <= 12; i++)
    {
        var message = new Dictionary<string, string>
        {
            { "number", "+911234567890" },
            { "message", "This is a test #{$i} of C# version. Testing bulk MMS message functionality." },
            { "type", "mms" },
            { "attachments", attachments }
        };
        mmsMessages.Add(message);
    }
    
    // Send messages using all SIMs of all available devices. Messages will be split between all SIMs.
    SMS.API.SendMessages(messages, SMS.API.Option.USE_ALL_SIMS);
    
    MessageBox.Show("Success");
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
?></pre>
                        <h4><?= __("get_balance"); ?></h4>
                        <pre class="prettyprint"><?=
                            htmlentities('try
{
    string credits = SMS.API.GetBalance();
    MessageBox.Show($"Message Credits Remaining: {credits}");
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
                            ?></pre>
                        <h4><?= __("get_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try 
{
    // Get a message using the ID.
    Dictionary<string, object> message = SMS.API.GetMessageByID(1);

    // Get messages using the Group ID.
    Dictionary<string, object>[] messages = SMS.API.GetMessagesByGroupID(")V5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871");
    
    // Get messages received in last 24 hours.
    long timestamp = (long) DateTime.UtcNow.AddHours(-24).Subtract(new DateTime(1970, 1, 1)).TotalSeconds;
    messages = GetMessagesByStatus("Received", timestamp);
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
?></pre>
                        <h4><?= __("resend_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try 
{
    // Resend a message using the ID.
    Dictionary<string, object> message = SMS.API.ResendMessageByID(1);

    // Resend messages using the Group ID and Status.
    Dictionary<string, object>[] messages = SMS.API.ResendMessagesByGroupID("LV5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871", "Failed");
    
    // Resend pending messages in last 24 hours.
    long timestamp = (long) DateTime.UtcNow.AddHours(-24).Subtract(new DateTime(1970, 1, 1)).TotalSeconds;
    messages = ResendMessagesByStatus("Pending", timestamp);
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
?></pre>
                        <h4><?= __("manage_contacts"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Add a new contact to contacts list 1 or resubscribe the contact if it already exists.
    Dictionary<string, object> contact = SMS.API.AddContact(1, "+911234567890", "Test C#", true);
    
    // Unsubscribe a contact using the mobile number.
    Dictionary<string, object> contact = UnsubscribeContact(1, "+911234567890");
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
?></pre>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once __DIR__ . "/includes/footer.php"; ?>
<?php require_once __DIR__ . '/includes/user-sims.php'; ?>
<script>
    $(function () {
        const webHookURLInput = $('#webHookURLInput');
        const removeWebHookButton = $('#removeWebHook');
        const generateApiLinkForm = $('#generateApiLinkForm');
        const generateApiButton = $('#generateApiButton');

        <?php if (empty($logged_in_user->getWebHook())) { ?>
        removeWebHookButton.hide();
        <?php } ?>

        $('.select2').select2();

        $('.type-input').change(function () {
            let fileInput = $($(this).data('target'));
            if ($(this).val() === "sms") {
                fileInput.prop("hidden", true);
            } else {
                fileInput.prop("hidden", false);
            }
        });

        $('#addWebHook').click(function (event) {
            event.preventDefault();
            $(this).prop('disabled', true);
            ajaxRequest("ajax/add-webhook.php", `webHookURL=${webHookURLInput.val()}`).then(result => {
                toastr.success(result);
                removeWebHookButton.show();
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                $(this).prop('disabled', false);
            });
        });

        removeWebHookButton.click(function (event) {
            event.preventDefault();
            $(this).prop('disabled', true);
            ajaxRequest("ajax/remove-webhook.php").then(result => {
                toastr.success(result);
                webHookURLInput.val("");
                removeWebHookButton.hide();
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                $(this).prop('disabled', false);
            });
        });

        generateApiLinkForm.submit(function (event) {
            event.preventDefault();
            generateApiButton.prop('disabled', true);
            ajaxRequest("ajax/generate-api-link.php", $(this).serialize()).then(result => {
                $('#ajaxResult').html(
                    `<div class="alert alert-success alert-dismissible" id="alertSuccess">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            &times;
                        </button>
                        <h4><i class="icon fa fa-check"></i>&nbsp;<?= __("success_dialog_title"); ?></h4>
                        <a href="${result}" target="_blank">${result}</a>
                    </div>`
                );
            }).catch(reason => {
                $('#ajaxResult').html(
                    `<div class="alert alert-danger alert-dismissible" id="alertDanger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            &times;
                        </button>
                        <h4><i class="icon fa fa-ban"></i>&nbsp;<?= __("error_dialog_title"); ?></h4>
                        ${reason}
                    </div>`
                );
            }).finally(() => {
                generateApiButton.prop('disabled', false);
            });
        });
    });
</script>
</body>
</html>