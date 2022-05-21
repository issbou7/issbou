<?php
/**
 * @var User $logged_in_user
 * @var Device $currentDevice
 */

require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("devices");

require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("devices"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <?php if (Device::where("userID", $_SESSION["userID"])->where("enabled", true)->count() > 0) { ?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= __("device_settings"); ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" id="update-device" method="post">
                            <div class="box-body">
                                <?php if (isset($_COOKIE["DEVICE_ID"])) { ?>

                                    <div class="form-group">
                                        <label for="deviceNameInput"><?= __("device_name"); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-mobile"></i>
                                            </div>
                                            <input type="text" name="name" class="form-control" maxlength="25"
                                                   value="<?= htmlentities($currentDevice->getName(), ENT_QUOTES); ?>"
                                                   id="deviceNameInput"
                                                   placeholder="<?= __("device_name"); ?>">
                                        </div>
                                    </div>

                                <?php } ?>
                                <div class="form-group">
                                    <label for="deviceInput"><?= __("primary_device"); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-android"></i>
                                        </div>
                                        <select class="form-control select2" name="device" id="deviceInput"
                                                style="width: 100%;">
                                            <?php
                                            $logged_in_user->generateDevicesList($logged_in_user->getPrimaryDeviceID());
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" id="updateDeviceButton" class="btn btn-primary"><i
                                            class="fa fa-save"></i>&nbsp;<?= __("save"); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->
                <?php } ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="pull-left">
                            <?php if ($_SESSION["isAdmin"]) { ?>
                                <select title="User" class="form-control select2" id="user">
                                    <?php
                                    $users = User::read_all();
                                    foreach ($users as $user) {
                                        $name = htmlentities($user->getName(), ENT_QUOTES);
                                        createOption("{$name} ({$user->getEmail()})", $user->getID(), $user->getID() == $_SESSION["userID"]);
                                    }
                                    ?>
                                </select>
                            <?php } else { ?>
                                <h3 class="box-title"><?= __("devices"); ?></h3>
                            <?php } ?>
                        </div>
                        <div class="pull-right">
                            <button type="button" id="remove-selected" class="btn btn-danger pull-right"><i
                                        class="icon fa fa-remove"></i><span
                                        class="hidden-xs hidden-sm">&nbsp;<?= __("remove"); ?></span></button>
                            <button type="button" id="add-device" style="margin-right: 4px"
                                    class="btn btn-primary pull-right" data-toggle="modal"
                                    data-target="#modal-add-device">
                                <i class="icon fa fa-plus"></i><span
                                        class="hidden-xs hidden-sm">&nbsp;<?= __("add_device"); ?></span></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="devices" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" id="check-all">
                                    </label>
                                </th>
                                <th><?= __("name"); ?></th>
                                <th><?= __("device_model"); ?></th>
                                <th><?= __("android_version"); ?></th>
                                <th><?= __("app_version"); ?></th>
                                <th><?= __("total_messages"); ?></th>
                                <th><?= __("device_status"); ?></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->

        <?php require_once __DIR__ . "/includes/add-device.php"; ?>

        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?= __("edit_device") ?></h4>
                    </div>
                    <form id="edit-device">
                        <div class="modal-body">
                            <input type="hidden" name="deviceID" id="deviceIDEditInput" class="form-control"
                                   required="required">
                            <div class="form-group">
                                <label for="deviceNameEditInput"><?= __("device_name"); ?>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-mobile"></i>
                                    </div>
                                    <input type="text" name="name" class="form-control"
                                           id="deviceNameEditInput">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                                        class="fa fa-remove"></i>&nbsp;<?= __("close") ?>
                            </button>
                            <button type="submit" id="editDeviceButton" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;<?= __("save_changes") ?>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once __DIR__ . "/includes/footer.php"; ?>
<?php require_once __DIR__ . "/includes/common-js.php"; ?>

<script type="text/javascript">
    function editDevice(name, id) {
        $('#deviceIDEditInput').val(id);
        if (name !== null) {
            $('#deviceNameEditInput').val(name);
        } else {
            $('#deviceNameEditInput').val("");
        }
        $('#modal-default').modal('show');
    }

    $(function () {
        const checkAllInput = $('#check-all');
        const updateDeviceForm = $('#update-device');
        const updateDeviceButton = $('#updateDeviceButton');
        const editDeviceButton = $('#editDeviceButton');

        checkAllInput.click(function () {
            if ($(this).is(':checked')) {
                $('.remove-devices').prop('checked', true);
            } else {
                $('.remove-devices').prop('checked', false);
            }
        });
        <?php if ($_SESSION["isAdmin"]) { ?>

        $("#user").change(function () {
            table.ajax.url("ajax/get-devices.php?user=" + $(this).val()).load();
            $("#qr-code").attr("src", `qr-code.php?user=${$(this).val()}`);
        });
        <?php } ?>

        const table = $('#devices').DataTable({
            <?php if (isset($dataTablesLanguage)) { ?>
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/<?=$dataTablesLanguage?>.json"
            },
            <?php } ?>
            autoWidth: false,
            pagingType: "simple",
            responsive: true,
            columnDefs: [
                {
                    orderable: false,
                    targets: 0
                }
            ],
            ajax: "ajax/get-devices.php"
        });

        $('#edit-device').submit(function (e) {
            e.preventDefault();
            let postData = $('#edit-device').serialize();
            let url = "ajax/edit-device.php";
            editDeviceButton.prop('disabled', true);
            const options = {positionClass: "toast-top-center", closeButton: true};
            ajaxRequest(url, postData).then(result => {
                toastr.success(result.message, null, options);
                table.ajax.reload();
                let updatedDeviceId = $('#deviceIDEditInput').val();
                <?php if (isset($_COOKIE["DEVICE_ID"])) { ?>
                if (updatedDeviceId == <?=$_COOKIE["DEVICE_ID"]?>) {
                    Android.changeDeviceName($('#deviceNameEditInput').val());
                }
                <?php } ?>
                let optionElement = $(`#deviceInput option[value="${updatedDeviceId}"]`);
                if (optionElement) {
                    optionElement.html(result.data.name);
                    $('#deviceInput').select2();
                }
                checkAllInput.prop('checked', false);
                $('#modal-default').modal('hide');
            }).catch(reason => {
                toastr.error(reason, null, options);
            }).finally(() => {
                editDeviceButton.prop('disabled', false);
            });
        });

        $("#remove-selected").click(function () {
            let postData = table.$('input').serialize();
            if (postData) {
                let result = confirm("<?=__("remove_devices_confirmation");?>");
                if (result) {
                    let url = "ajax/remove-devices.php";
                    $(this).prop('disabled', true);
                    ajaxRequest(url, postData).then(result => {
                        toastr.success(result);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }).catch(reason => {
                        toastr.error("<?=__("error_unable_to_remove_devices");?>" + ` ${reason}`);
                    }).finally(() => {
                        $(this).prop('disabled', false);
                    });
                }
            }
        });

        updateDeviceForm.submit(function (event) {
            event.preventDefault();
            updateDeviceButton.prop('disabled', true);
            ajaxRequest('ajax/update-device.php', updateDeviceForm.serialize()).then(result => {
                <?php if (isset($_COOKIE["DEVICE_ID"])) { ?>
                Android.changeDeviceName($('#deviceNameInput').val());
                <?php } ?>
                toastr.success(result);
                table.ajax.reload();
                checkAllInput.prop('checked', false);
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                updateDeviceButton.prop('disabled', false);
            });
        });
    });
</script>
</body>
</html>