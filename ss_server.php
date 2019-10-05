<?php

function check_ss_installed()
{
    return file_exists("/usr/bin/ss-server");
}

function check_ss_service()
{
    exec("systemctl is-active shadowsocks-libev --quiet", $output, $exitcode);
    return (int) $exitcode;
}

function install_ss()
{
    file_put_contents("/etc/yum.repos.d/librehat-shadowsocks-epel-7.repo", fopen("https://copr.fedorainfracloud.org/coprs/librehat/shadowsocks/repo/epel-7/librehat-shadowsocks-epel-7.repo", 'r'));
    $install_out = shell_exec("yum -y install shadowsocks-libev");
    shell_exec("systemctl enable shadowsocks-libev --now");
    return $install_out;
}
function uninstall_ss()
{
    if (check_ss_installed() == true) {
        unlink("/etc/yum.repos.d/librehat-shadowsocks-epel-7.repo");
        return shell_exec("yum -y remove shadowsocks-libev");
    } else
        return "Shadowsocks is not installed.";
}

function start_ss()
{
    if (check_ss_installed() == true)
        return shell_exec("systemctl enable shadowsocks-libev --now");
    else
        return "Shadowsocks is not installed. Please install it first.";
}

function restart_ss()
{
    if (check_ss_installed() == true)
        return shell_exec("systemctl restart shadowsocks-libev");
    else
        return "Shadowsocks is not installed. Please install it first.";
}
function stop_ss()
{
    if (check_ss_installed() == true)
        return shell_exec("systemctl disable shadowsocks-libev --now");
    else
        return "Shadowsocks is not installed. Please install it first.";
}
function status_ss()
{
    if (check_ss_installed() == true)
        return shell_exec("systemctl status shadowsocks-libev");
    else
        return "Shadowsocks is not installed. Please install it first.";
}
function generate_ss_config()
{
    $ss_config = array();
    $ss_config["server"] = $_POST['ss_server'];
    $ss_config["server_port"] = (int) ($_POST['ss_server_port']);
    $ss_config["local_port"] = (int) ($_POST['ss_local_port']);
    $ss_config["password"] = $_POST['ss_password'];
    $ss_config["timeout"] = (int) ($_POST['ss_timeout']);
    $ss_config["method"] = $_POST['ss_method'];
    $fp = fopen('/etc/shadowsocks-libev/config.json', 'w');
    fwrite($fp, json_encode($ss_config));
    fclose($fp);
    shell_exec("systemctl restart shadowsocks-libev");
    return "Configuration is Updated";
}

$message = "";
if (isset($_POST['ifpost'])) {
    switch ($_POST['action']) {
        case 'install':
            $message = install_ss();
            break;
        case 'uninstall':
            $message = uninstall_ss();
            break;
        case 'start':
            $message = start_ss();
            break;
        case 'restart':
            $message = restart_ss();
            break;
        case 'stop':
            $message = stop_ss();
            break;
        case 'status':
            $message = status_ss();
            break;
        case 'config':
            $message = generate_ss_config();
            break;
        default:
            echo "Nothing";
    }
};

if (check_ss_installed())
    $ss_config_file = json_decode(file_get_contents("/etc/shadowsocks-libev/config.json"), true);

$isinstalled = '<span class="label label-success mr6 mb6"><i class="icomoon-icon-checkmark-circle-2" style="color:#fff"></i>Installed</span>';
$isnotinstalled = '<span class="label label-danger mr6 mb6"><i class="icomoon-icon-cancel-circle-2" style="color:#fff"></i>Not Installed</span>';
$isactive = '<span class="label label-success mr6 mb6"><i class="icomoon-icon-checkmark-circle-2" style="color:#fff"></i>Active</span>';
$isinactive = '<span class="label label-danger mr6 mb6"><i class="icomoon-icon-cancel-circle-2" style="color:#fff"></i>Inactive</span>';
?>


    <h3>Shadowsocks Server</h3>

    <?php
    echo "<p>Shadowsocks Server is currently ";
    if (check_ss_installed())
        if (check_ss_service() == 0)
            echo $isinstalled . " and service status is " . $isactive . "</p>";
        else
            echo $isinstalled . " and service status is " . $isinactive . "</p>";
    else
        echo $isnotinstalled . " and service status is " . $isinactive . "</p>";

    if ($message != "")
        echo "<p><pre>" . $message . "</pre></p>";
    ?>
    <div class="col-lg-8">
        <table style="width:100%;">
            <tbody>
                <tr>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="ifpost" size="0" value="yes">
                            <input type="hidden" name="action" size="0" value="install">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-block"><i class="icomoon-icon-arrow-down"></i>Install</button>
                            </div>
                        </form>
                    </td>

                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="ifpost" size="0" value="yes">
                            <input type="hidden" name="action" size="0" value="start">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="icomoon-icon-play-3"></i>Start
                                </button>
                            </div>
                        </form>
                    </td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="ifpost" size="0" value="yes">
                            <input type="hidden" name="action" size="0" value="stop">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="icomoon-icon-stop-2"></i>Stop
                                </button>
                            </div>
                        </form>
                    </td>

                </tr>
                <tr>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="ifpost" size="0" value="yes">
                            <input type="hidden" name="action" size="0" value="restart">
                            <div class="form-group">
                                <button type="submit" class="btn btn-warning btn-block">
                                    <i class="cut-icon-reload"></i>Restart
                                </button>
                            </div>
                        </form>
                    </td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="ifpost" size="0" value="yes">
                            <input type="hidden" name="action" size="0" value="status">
                            <div class="form-group">
                                <button type="submit" class="btn btn-default btn-block">
                                    <i class="icomoon-icon-info-2"></i>Status
                                </button>
                            </div>
                        </form>
                    </td>
                    <td>
                        <form action="" method="post" onsubmit="return confirm('Are you sure you want to uninstall Shadowsocks?');">
                            <input type="hidden" name="ifpost" size="0" value="yes">
                            <input type="hidden" name="action" size="0" value="uninstall">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger btn-block"><i class="icomoon-icon-remove"></i>Uninstall</button>
                            </div>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php if (check_ss_installed()) { ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default toggle">
                    <div class="panel-heading">
                        <h4 class="panel-title">Shadowsocks Settings</h4>
                        <div class="panel-controls panel-controls-right"><a href="#" class="toggle panel-minimize"><i class="icomoon-icon-plus"></i></a></div>
                    </div>
                    <div class="panel-body pt0 pb0" style="display: block;">
                        <form class="form-horizontal group-border stripped" action="" method="post">
                            <input type="hidden" name="ifpost" size="0" value="yes">
                            <input type="hidden" name="action" size="0" value="config">

                            <div class="form-group">
                                <label class="col-lg-2 col-md-3 control-label" for="">Server:</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" class="form-control input-large" value="<?php echo $ss_config_file['server']; ?>" name="ss_server" maxlength="50">
                                    <span class="help-block">The address your server listens to, use 0.0.0.0 for everyone.</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 col-md-3 control-label" for="">Server Port:</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="number" class="form-control input-small" value="<?php echo $ss_config_file['server_port']; ?>" name="ss_server_port" maxlength="5">
                                    <span class="help-block">Server port on which server listens to, default is 8388. <b>Don't forget to open this port in CWP firewall.</b></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 col-md-3 control-label" for="">Local Port:</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="number" class="form-control input-small" value="<?php echo $ss_config_file['local_port']; ?>" name="ss_local_port" maxlength="5">
                                    <span class="help-block">Local port on which client listens to, default 1080.</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 col-md-3 control-label" for="">Password:</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" class="form-control input-large" value="<?php echo $ss_config_file['password']; ?>" name="ss_password" maxlength="50">
                                    <span class="help-block">Password used for encryption.</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 col-md-3 control-label" for="">Timeout:</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="number" class="form-control input-small" value="<?php echo $ss_config_file['timeout']; ?>" name="ss_timeout" maxlength="5">
                                    <span class="help-block">Timeout in seconds.</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 col-md-3 control-label" for="">Method:</label>
                                <div class="col-lg-10 col-md-9">
                                    <input type="text" class="form-control input-large" value="<?php echo $ss_config_file['method']; ?>" name="ss_method" maxlength="50">
                                    <span class="help-block">Encryption Method for encrypting traffic. Recommended is <b>aes-256-cfb</b>. See <a href="https://shadowsocks.org/en/config/quick-guide.html">Encryption</a></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-1 col-lg-8">
                                    <button type="submit" class="btn btn-primary btn-block mb10">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
