<?php
$escape = static function ($value): string {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
};

$toolInfo = is_array($data['tools_info'] ?? null)
    ? $data['tools_info']
    : [];

$controllerInfo =
    is_array($data['controller_info'] ?? null)
        ? $data['controller_info']
        : [];

$databaseError = trim(
    (string)($data['database_error'] ?? '')
);

$qrCode = (string)($data['qrcode'] ?? '');
$qrCodeUrl = (string)($data['qrcode_url'] ?? '');
?>

<div class="container-ms">
    <div class="w3-text-white w3-center">
        <table>
            <tr id="header">
                <td width="100%">
                    <h3>
                        <?php echo $text['tool']; ?>
                    </h3>
                </td>

                <td>
                    <button
                        class="w3-btn w3-round-large"
                        style="height:50px;padding:0"
                        onclick="window.location.href='./?url=Dashboards'"
                    >
                        <img
                            src="../public/img/btn_home.png"
                            alt="Home"
                        >
                    </button>
                </td>
            </tr>
        </table>
    </div>

    <div class="main-content">
        <div class="center-content">
            <div
                class="container"
                style="
                    padding:10px;
                    border-radius:5px;
                    box-shadow:0 3px 8px 0 rgba(0,0,0,.2);
                "
            >
                <div id="Tool_Setting">
                    <?php if ($databaseError !== ''): ?>
                        <div
                            style="
                                margin:0 0 12px;
                                padding:10px 12px;
                                color:#842029;
                                background:#f8d7da;
                                border:1px solid #f5c2c7;
                                border-radius:5px;
                                word-break:break-word;
                            "
                        >
                            <strong>Tool DB Error:</strong>
                            <?php echo $escape($databaseError); ?>

                            <div style="margin-top:5px">
                                Debug:
                                <code>
                                    ?url=Tools/db_debug
                                </code>
                            </div>
                        </div>
                    <?php endif; ?>

                    <h3 style="margin:5px 3px 10px">
                        <b>
                            <?php echo $text['tool_info']; ?>
                        </b>
                    </h3>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo  $text['total_counts']; ?>:
                        </div>
                        <div class="col t1">
                            <?php
                            echo $escape(
                                $toolInfo['tool_maintain_counts']
                                ?? ''
                            );
                            ?>
                        </div>
                        &nbsp;
                    </div>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo $text['maintain_counts']; ?>:
                        </div>
                        <div class="col t1">
                            <?php
                            echo $escape(
                                $toolInfo['tool_total_counts']
                                ?? ''
                            );
                            ?>
                        </div>
                    </div>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo $text['Torque']; ?>:
                        </div>
                        <div class="col t1">
                            <?php
                            echo $escape(
                                $toolInfo['tool_mintorque']
                                ?? ''
                            );
                            ?>
                            /
                            <?php
                            echo $escape(
                                $toolInfo['tool_maxtorque']
                                ?? ''
                            );
                            ?>
                        </div>
                    </div>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo $text['rpm']; ?>:
                        </div>
                        <div class="col t1">
                            <?php
                            echo $escape(
                                $toolInfo['tool_maxrpm']
                                ?? ''
                            );
                            ?>
                            /
                            <?php
                            echo $escape(
                                $toolInfo['tool_minrpm']
                                ?? ''
                            );
                            ?>
                        </div>
                    </div>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo $text['calibration_value']; ?>:
                        </div>
                        <div class="col t1">
                            <?php
                            echo $escape(
                                $toolInfo['tool_calibration']
                                ?? ''
                            );
                            ?>
                        </div>
                    </div>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo $text['calibration_time']; ?>:
                        </div>
                        <div class="col t1">
                            <?php
                            echo $escape(
                                $toolInfo['tool_calib_time']
                                ?? ''
                            );
                            ?>
                        </div>
                    </div>

                    <hr>

                    <h3 style="margin:5px 3px 10px">
                        <b>
                            <?php echo $text['controller_info']; ?>
                        </b>
                    </h3>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo $text['controller_sn']; ?>:
                        </div>
                        <div class="col t1">
                            <?php
                            echo $escape(
                                $controllerInfo['device_sn']
                                ?? ''
                            );
                            ?>
                        </div>
                    </div>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo $text['controller_version']; ?>:
                        </div>
                        <div class="col t1">
                            <?php
                            echo $escape(
                                $controllerInfo['device_version']
                                ?? ''
                            );
                            ?>
                        </div>
                    </div>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo $text['mcb_version']; ?>:
                        </div>
                        <div class="col t1">
                            <?php
                            echo $escape(
                                $controllerInfo[
                                    'device_mcbswversion'
                                ]
                                ?? ''
                            );
                            ?>
                        </div>
                    </div>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo $text['image_version']; ?>:
                        </div>
                        <div class="col t1">
                            <?php
                            echo $escape(
                                $controllerInfo['image_version']
                                ?? ''
                            );
                            ?>
                        </div>
                    </div>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo $text['network_ip']; ?>:
                        </div>
                        <div class="col t1">
                            <?php echo $escape($data['IP'] ?? ''); ?>
                        </div>
                    </div>

                    <div class="row border-bottom">
                        <div class="col-4 t1">
                            <?php echo $text['Mac']; ?>:
                        </div>
                        <div class="col t1">
                            <?php echo $escape($data['MAC'] ?? ''); ?>
                        </div>
                    </div>

                    <hr>

                    <div
                        class="border-bottom"
                        style="
                            display: flex;
                            width: 100%;
                            min-height: 170px;
                            justify-content: center;
                            align-items: center;
                            text-align: center;
                        "
                    >
                        <?php if ($qrCode !== ''): ?>
                            <a
                                href="<?php echo $escape($qrCodeUrl); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                style="
                                    display: flex;
                                    width: 100%;
                                    height: 170px;
                                    justify-content: center;
                                    align-items: center;
                                    text-decoration: none;
                                "
                            >
                                <img
                                    src="<?php echo $escape($qrCode); ?>"
                                    alt="QR Code"
                                    style="
                                        display: block;
                                        width: 150px;
                                        height: 150px;
                                        margin: 0 auto;
                                        object-fit: contain;
                                        cursor: pointer;
                                    "
                                >
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
