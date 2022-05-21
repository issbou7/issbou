<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/vendor/autoload.php";

try {
    if (empty($_REQUEST["token"])) {
        $message = __("error_empty_token");
    } else {
        $contact = new Contact();
        $contact->setToken($_REQUEST["token"]);
        if ($contact->read()) {
            if ($contact->getSubscribed()) {
                $contact->setSubscribed(false);
                $contact->save();
                $message = __("success_unsubscribed");
            } else {
                $message = __("error_already_unsubscribed");
            }
        } else {
            $message = __("error_invalid_token");
        }
    }
} catch (Exception $e) {
    $message = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unsubscribe</title>
</head>

<body>
<h1><?= $message; ?></h1>
</body>

</html>
