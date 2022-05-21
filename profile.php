<?php
/**
 * @var User $logged_in_user
 */

require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("profile");
$languageFiles = getLanguageFiles();
require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("profile"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("change_password"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="change-password" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="currentPasswordInput"><?= __("current_password"); ?></label>
                                <input type="password" name="currentPassword" class="form-control" minlength="8"
                                       id="currentPasswordInput" placeholder="<?= __("current_password"); ?>"
                                       required="required">
                            </div>
                            <div class="form-group">
                                <label for="newPasswordInput"><?= __("new_password"); ?></label>
                                <input type="password" name="newPassword" class="form-control" minlength="8"
                                       id="newPasswordInput" placeholder="<?= __("password"); ?>" required="required">
                            </div>
                            <div class="form-group" id="confirmPasswordBox">
                                <label for="confirmPasswordInput"><?= __("confirm_password"); ?></label>
                                <input type="password" name="confirmPassword" class="form-control"
                                       id="confirmPasswordInput" placeholder="<?= __("confirm_password"); ?>"
                                       required="required">
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" id="changePasswordButton" name="changePassword"
                                    class="btn btn-primary"><i
                                        class="fa fa-edit"></i>&nbsp;<?= __("change_password"); ?>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("settings"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="settings" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nameInput"><?= __("name"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="glyphicon glyphicon-user"></i>
                                    </div>
                                    <input type="text" name="name" class="form-control" id="nameInput"
                                           value="<?= $logged_in_user->getName(); ?>" placeholder="<?= __("name"); ?>"
                                           required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="delayInput">
                                    <?= __("delay_setting"); ?>
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                       title="<?= __('tooltip_delay'); ?>"></i>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="text" name="delay" class="form-control"
                                           id="delayInput"
                                           value="<?= $logged_in_user->getDelay(); ?>" placeholder="<?= __("delay"); ?>"
                                           required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="timeZoneInput"><?= __("timezone") ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-globe"></i>
                                    </div>
                                    <select class="form-control select2" id="timeZoneInput" name="timezone"
                                            style="width: 100%;" required="required">
                                        <?php
                                        $timezones = generate_timezone_list();
                                        foreach ($timezones as $timezone => $timezone_value) {
                                            echo "<option value='$timezone' ";
                                            if ($logged_in_user->getTimeZone() == $timezone) {
                                                echo "selected='selected'";
                                            }
                                            echo ">{$timezone_value}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php if (count($languageFiles) > 1) { ?>
                                <div class="form-group">
                                    <label for="languageInput"><?= __("language") ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-language"></i>
                                        </div>
                                        <select name="language" id="languageInput"
                                                class="form-control select2"
                                                style="width: 100%">
                                            <?php
                                            foreach ($languageFiles as $languageFile) {
                                                createOption(ucfirst($languageFile), $languageFile, $languageFile === $logged_in_user->getLanguage());
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="reportDeliveryInput">
                                    <input type="checkbox" name="reportDelivery" value="1"
                                           id="reportDeliveryInput" <?php if ($logged_in_user->getReportDelivery()) echo "checked='checked'" ?>>
                                    <?= __("report_delivery_setting"); ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="autoRetryInput">
                                    <input type="checkbox" name="autoRetry" value="1"
                                           id="autoRetryInput" <?php if ($logged_in_user->getAutoRetry()) echo "checked='checked'" ?>>
                                    <?= __("auto_retry_setting"); ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="smsToEmailInput">
                                    <input type="checkbox" name="smsToEmail" value="1"
                                           id="smsToEmailInput" <?php if ($logged_in_user->getSmsToEmail()) echo "checked='checked'" ?>>
                                    <?= __("send_received_messages_to_email"); ?>
                                </label>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" id="saveSettingsButton" name="saveSettings" class="btn btn-primary"><i
                                        class="fa fa-save"></i>&nbsp;<?= __("save"); ?>
                            </button>
                        </div>
                    </form>
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
<?php require_once __DIR__ . "/includes/common-js.php" ?>
<script src="components/he/he.js"></script>
<script type="text/javascript">

    $(function () {
        const changePasswordForm = $("#change-password");
        const settingsForm = $("#settings");
        const changePasswordButton = $('#changePasswordButton');
        const saveSettingsButton = $('#saveSettingsButton');
        const languageInput = $('#languageInput');

        changePasswordForm.validate({
            rules: {
                newPassword: "required",
                confirmPassword: {
                    equalTo: "#newPasswordInput"
                }
            },
            submitHandler: function (form) {
                let postData = changePasswordForm.serialize();
                let url = "ajax/change-password.php";
                changePasswordButton.prop('disabled', true);
                ajaxRequest(url, postData).then(result => {
                    toastr.success(result);
                }).catch(reason => {
                    toastr.error(reason);
                }).finally(() => {
                    form.reset();
                    changePasswordButton.prop('disabled', false);
                });
                return false;
            }
        });

        settingsForm.submit(function (event) {
            event.preventDefault();
            let postData = settingsForm.serialize();
            let url = "ajax/save-user-settings.php";
            saveSettingsButton.prop('disabled', true);
            ajaxRequest(url, postData).then(result => {
                toastr.success(result);
                if (languageInput.val() !== "<?= $logged_in_user->getLanguage() ?>") {
                    setTimeout(() => {
                        document.location.href = `profile.php?language=${languageInput.val()}`;
                    }, 1000);
                } else {
                    let name = he.encode($('#nameInput').val());
                    <?php if (isset($_COOKIE["DEVICE_ID"])) { ?>
                    Android.changeName(name);
                    <?php } else { ?>
                    $('.user-name').html(name);
                    <?php } ?>
                }
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                saveSettingsButton.prop('disabled', false);
            });
        });
    });

</script>
</body>
</html>
