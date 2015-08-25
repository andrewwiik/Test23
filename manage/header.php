<?php
/**
 * This file is part of WEIPDCRM.
 * 
 * WEIPDCRM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * WEIPDCRM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with WEIPDCRM.  If not, see <http://www.gnu.org/licenses/>.
 */

/* DCRM Manage Header */

if (!defined("DCRM")) {
    header('HTTP/1.1 403 Forbidden');
    exit('HTTP/1.1 403 Forbidden');
}

$localetype = 'manage';
define('MANAGE_ROOT', dirname(__FILE__).'/');
define('ABSPATH', dirname(MANAGE_ROOT).'/');
require_once ABSPATH.'system/common.inc.php';

$packages = array(
    array(
        'name'  => __('Manage Packages'),
        'id'    => 'center',
        'type'  => 'subtitle'
    ),
    array(
        'name'  => __('Manage UDID'),
        'id'    => 'udid',
        'type'  => 'subtitle'
    )
    );
$repository = array(
    array(
        'name'  => __('Manage Sections'),
        'id'    => 'sections',
        'type'  => 'subtitle'
    ),
    array(
        'name'  => __('Manage Repository'),
        'id'    => 'release',
        'type'  => 'subtitle'
    )
    );
$system = array(
    array(
        'name'  => __('Running Status'),
        'id'    => 'stats',
        'type'  => 'subtitle'
    ),
    array(
        'name'  => __('About'),
        'id'    => 'about',
        'type'  => 'subtitle'
    )
);
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Twiik's | Repo Manager </title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="css/plugins/dropzone/basic.css" rel="stylesheet">
    <link href="css/plugins/dropzone/dropzone.css" rel="stylesheet">
    <link href="css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="css/plugins/summernote/summernote-bs3.css" rel="stylesheet">
    <link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="css/plugins/tour/bootstrap-tour.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="js/plugins/iCheck/icheck.min.js"></script>
    <script src="js/plugins/footable/footable.all.min.js"></script>
    <script src="js/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="js/plugins/toastr/toastr.min.js"></script>
    <script src="js/plugins/dropzone/dropzone.js"></script>
    <script src="js/plugins/summernote/summernote.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/tour/bootstrap-tour.min.js"></script>



    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>
<?php
if ( isset($activeid) && ( 'manage' == $activeid || 'sections' == $activeid || 'center' == $activeid) ) 
    echo('  <link rel="stylesheet" type="text/css" href="css/corepage.css">');
if ( isset($activeid) && ( 'view' == $activeid || 'edit' == $activeid || 'center' == $activeid) ) {
?>
    <script type="text/javascript">
        var hide_text = '<?php _e('Are you sure you want to hide this software package?'); ?>';
        var show_text = '<?php _e('Are you sure you want to display this software package?\nIf there are more than one version, the new version of the Cydia will show downgrade.'); ?>';
    </script>
    <script src="javascript/backend/mbar.js" type="text/javascript"></script>
<?php } ?>

</head>

<body>

    <div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                <?php
                $user = DB::fetch_first("SELECT `First`, `Last`, `Image` FROM `".DCRM_CON_PREFIX."Users`");
                ?>

                    <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="<?php echo($user['Image']); ?>" />
                             </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo($user['First']); ?> <?php echo($user['Last']); ?></strong>
                             </span> <span class="text-muted text-xs block">Developer <b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                             <li><a href="build.php" class=""><?php _e('Rebuild the list');?></a></li>
                    <li><a href="settings.php" class="<?php if ( isset($activeid) && 'settings' == $activeid ) echo ' disabled'; ?>"><?php _ex('Preferences', 'Header');?></a></li>
                            <li class="divider"></li>
                            <li><a href="login.php?action=logout" class=""><?php _e('Logout');?></a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        AW
                    </div>
                </li>
                <li>
                    <a href="index.html"><i class="fa fa-archive"></i> <span class="nav-label">Packages</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                       <?php
foreach ($packages as $value) {
    switch ( $value['type'] ) {
        case 'subtitle':
            if( ( isset($activeid) && $value['id'] == $activeid ) || ( isset($highactiveid) && $value['id'] == $highactiveid ) ){
                echo "\t\t\t\t\t\t\t<li class=\"active\">";
            } else {
                echo "\t\t\t\t\t\t\t<li>";
            }
            echo '<a href="' . $value['id'] . '.php">' . $value['name'] . "</a></li>\n";
    }
}
?>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-database"></i> <span class="nav-label">Repository</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <?php
foreach ($repository as $value) {
    switch ( $value['type'] ) {
        case 'subtitle':
            if( ( isset($activeid) && $value['id'] == $activeid ) || ( isset($highactiveid) && $value['id'] == $highactiveid ) ){
                echo "\t\t\t\t\t\t\t<li class=\"active\">";
            } else {
                echo "\t\t\t\t\t\t\t<li>";
            }
            echo '<a href="' . $value['id'] . '.php">' . $value['name'] . "</a></li>\n";
    }
}
?>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">System</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <?php
foreach ($system as $value) {
    switch ( $value['type'] ) {
        case 'subtitle':
            if( ( isset($activeid) && $value['id'] == $activeid ) || ( isset($highactiveid) && $value['id'] == $highactiveid ) ){
                echo "\t\t\t\t\t\t\t<li class=\"active\">";
            } else {
                echo "\t\t\t\t\t\t\t<li>";
            }
            echo '<a href="' . $value['id'] . '.php">' . $value['name'] . "</a></li>\n";
    }
}
?>
                    </ul>
                </li>
            </ul>

        </div>
    </nav>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="Search..." class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
        <ul class="nav navbar-top-links navbar-right">
         <li>
                    <a href="settings.php">
                        <i id="step1" class="fa fa-gear"></i>
                    </a>
                </li>
                <li>
                    <a onclick="javascript:rebuildList()">
                        <i id="step2" class="fa fa-refresh"></i>
                    </a>
                </li>
                <li>
                    <a href="login.php?action=logout">
                        <i id="step3" class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>
        </nav>
        </div>
        <script type="text/javascript">
    function rebuildList () {
$.ajax({
                                type: "GET",
                                url: "build.php",
                                success: function(msg){    toastr.success("Package List was Rebuilt Successfully");
                                  toastr.options = {
                                     "closeButton": true,
                                     "debug": false,
                                     "progressBar": true,
                                     "positionClass": "toast-top-right",
                                     "onclick": null,
                                     "showDuration": "400",
                                     "hideDuration": "400",
                                     "timeOut": "7000",
                                     "extendedTimeOut": "1000",
                                     "showEasing": "swing",
                                     "hideEasing": "linear",
                                     "showMethod": "fadeIn",
                                     "hideMethod": "fadeOut"
                                }}});
    }
</script>

