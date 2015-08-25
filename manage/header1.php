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
		'name'  => __('Upload Packages'),
		'id'    => 'upload',
		'type'  => 'subtitle'
	),
	array(
		'name'  => __('Import Packages'),
		'id'    => 'manage',
		'type'  => 'subtitle'
	),
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>DCRM - <?php _e('Repository Manager');?></title>
	<meta name="viewport" content="width=600px, minimal-ui">
	<link href="../bootstrap3/css/bootstrap.css" rel="stylesheet" />
    <link href="../assets/css/gsdk.css" rel="stylesheet"/>
    
    <link href="../assets/css/demo.css" rel="stylesheet" /> 
        
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>  
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
    <link href="../assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
<?php if(is_rtl()){ ?>	<link rel="stylesheet" type="text/css" href="css/bootstrap-rtl.min.css"><?php echo "\n"; } ?>
<?php if(file_exists(ROOT.'css/font/'.($local_css = substr($locale, 0, 2)).'.css') || file_exists(ROOT.'css/font/' . ($local_css = $locale) . '.css')): ?>	<link rel="stylesheet" type="text/css" href="../css/font/<?php echo $local_css; ?>.css"><?php echo("\n"); endif; ?>
<script src="../assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="../assets/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>

	<script src="../bootstrap3/js/bootstrap.js" type="text/javascript"></script>
	
	<!--  Plugins -->
	<script src="../assets/js/gsdk-checkbox.js"></script>
	<script src="../assets/js/gsdk-morphing.js"></script>
	<script src="../assets/js/gsdk-radio.js"></script>
	<script src="../assets/js/gsdk-bootstrapswitch.js"></script>
	<script src="../assets/js/bootstrap-select.js"></script>
	<script src="../assets/js/bootstrap-datepicker.js"></script>
	<script src="../assets/js/chartist.min.js"></script>
    <script src="../assets/js/jquery.tagsinput.js"></script>
    <script src="../assets/js/dropzone.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
	
	<script src="../assets/js/get-shit-done.js"></script>
	<script type="text/javascript">
        $().ready(function(){
            $(window).on('scroll', gsdk.checkScrollForTransparentNavbar);
        });       
    </script>
	<script type="text/javascript" src="./javascript/pace.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		var loaded = true;
		var top = $("#navbar").offset().top;
		function Add_Data() {
			var scrolla=$(window).scrollTop();
			var cha=parseInt(top)-parseInt(scrolla)-10;
			if(loaded && cha<=0) {                
				$("#navbar").addClass("sticky");
				loaded=false;
			}
			if(!loaded && cha>0) {
				$("#navbar").removeClass("sticky");
				loaded=true;
			}
		}
		$(window).scroll(Add_Data);
	});
	</script>
<?php
if ( isset($activeid) && ( 'manage' == $activeid || 'sections' == $activeid || 'center' == $activeid) ) 
	echo('	<link rel="stylesheet" type="text/css" href="css/corepage.css">');
if ( isset($activeid) && ( 'view' == $activeid || 'edit' == $activeid || 'center' == $activeid) ) {
?>
	<script type="text/javascript">
		var hide_text = '<?php _e('Are you sure you want to hide this software package?'); ?>';
		var show_text = '<?php _e('Are you sure you want to display this software package?\nIf there are more than one version, the new version of the Cydia will show downgrade.'); ?>';
	</script>
	<script src="javascript/backend/mbar.js" type="text/javascript"></script>
<?php } ?>
</head>
<body class="manage">
 <div id="navbar">
    
        <nav class="navbar navbar-default" role="navigation">
    
          <div class="container-fluid">
    
            <div class="navbar-header">
    
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
    
                <span class="sr-only">Toggle navigation</span>
    
                <span class="icon-bar"></span>
    
                <span class="icon-bar"></span>
    
                <span class="icon-bar"></span>
    
              </button>
    
              <a class="navbar-brand" href="#">Repo Manager</a>
    
            </div>
    
    
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    
              <ul class="nav navbar-nav">
    
                <li class="dropdown">
    
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Packages<b class="caret"></b></a>
    
                  <ul class="dropdown-menu">
    
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
                <li class="dropdown">
    
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Repository<b class="caret"></b></a>
    
                  <ul class="dropdown-menu">
    
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
                <li class="dropdown">
    
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">System<b class="caret"></b></a>
    
                  <ul class="dropdown-menu">
    
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
                <ul class="nav navbar-nav navbar-right">
    <li><a href="build.php" class=""><?php _e('Rebuild the list');?></a></li>
					<li class="pull-right"><a href="settings.php" class="<?php if ( isset($activeid) && 'settings' == $activeid ) echo ' disabled'; ?>"><?php _ex('Preferences', 'Header');?></a></li>
					<li class="pull-right"><a href="login.php?action=logout" class=""><?php _e('Logout');?></a></li>
              </ul>
    <?php
{
?>
				<div class="well sidebar-nav" id="mbar" <?php if ( isset($activeid) && 'center' == $activeid ) echo ''; ?>>
					<ul class="nav nav-list">
						<li class="nav-header">OPERATIONS</li>
							<li<?php if ( isset($activeid) && 'view' == $activeid ) echo ' class="active"'; ?>><a href="javascript:opt(1)"><?php _e('View Details'); ?></a></li>
							<li<?php if ( isset($activeid) && 'edit' == $activeid && !isset($_GET['action']) ) echo ' class="active"'?>><a href="javascript:opt(2)"><?php _e('General Editing'); ?></a></li>
							<li<?php if ( isset($activeid) && 'edit' == $activeid && isset($_GET['action']) && ($_GET['action'] == 'advance' || $_GET['action'] == 'advance_set') ) echo ' class="active"'?>><a href="javascript:opt(3)"><?php _e('Advance Editing'); ?></a></li>
							<li id="sli"></li>
					</ul>
				</div>
<?php
}
?>
            </div><!-- /.navbar-collapse -->
    
          </div><!-- /.container-fluid -->
    
        </nav>
    
    </div>
	<div class="container">
		<div class="row">
<?php
{
?>
				<div class="" id="mbar" <?php if ( isset($activeid) && 'center' == $activeid ) echo ''; ?>>
					<ul class="nav nav-list">
						<li class="nav-header">OPERATIONS</li>
							<li<?php if ( isset($activeid) && 'view' == $activeid ) echo ' class="active"'; ?>><a href="javascript:opt(1)"><?php _e('View Details'); ?></a></li>
							<li<?php if ( isset($activeid) && 'edit' == $activeid && !isset($_GET['action']) ) echo ' class="active"'?>><a href="javascript:opt(2)"><?php _e('General Editing'); ?></a></li>
							<li<?php if ( isset($activeid) && 'edit' == $activeid && isset($_GET['action']) && ($_GET['action'] == 'advance' || $_GET['action'] == 'advance_set') ) echo ' class="active"'?>><a href="javascript:opt(3)"><?php _e('Advance Editing'); ?></a></li>
							<li id="sli"></li>
					</ul>
				</div>
<?php
}
?>
			</div>
			<div class="content" id="content">
				<div class="wrap">
