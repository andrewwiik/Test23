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

/* DCRM APT Information Settings */

session_start();
define("DCRM",true);
$activeid = 'release';

if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
	require_once("header.php");

	if (!isset($_GET['action'])) {
		if (file_exists(CONF_PATH.'release.save')) {
			$release_file = file(CONF_PATH.'release.save');
			$release = array();
			foreach ($release_file as $line) {
				if(preg_match("#^Origin|Label|Version|Codename|Description#", $line)) {
					$release[trim(preg_replace("#^(.+):\\s*(.+)#","$1", $line))] = trim(preg_replace("#^(.+):\\s*(.+)#","$2", $line));
				}
			}
		}
?>
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-4">
    <h2><?php _e('Repository Settings'); ?></h2>
    <ol class="breadcrumb">
      <li>
        <a href="index.html">Home</a>
      </li>
      <li>
        <a href="index.html">Repository</a>
      </li>
      <li class="active">
        <strong><?php _e('Repository Settings'); ?></strong>
      </li>
    </ol>
  </div>
  <div class="col-sm-8">
  <div class="title-action">
                        <button id="step5" type="submit" form="repoSettingsForm" class="btn btn-primary"><span class="bold"><?php _e('Save Settings'); ?></span></button>
                    </div>
                </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5><?php _e('Repository Settings'); ?></h5>
        <div class="ibox-tools">
          <a class="collapse-link">
            <i class="fa fa-chevron-up"></i>
          </a>
        </div>
      </div>
      <div class="ibox-content">
        <form id="repoSettingsForm" style="overflow:auto;" class="form-horizontal" method="POST" enctype="multipart/form-data" action="release.php?action=set">
        <div class="col-lg-12" style="overflow:auto;">
            <div class="form-group">
            <label>
                <?php _e('Origin'); ?>
              </label>
              <input class="form-control" type="text" required="required" name="origin" value="<?php if (!empty($release['Origin'])) {echo htmlspecialchars($release['Origin']);} ?>" />
              <p class="help-block">
                <?php _e( 'This name will be displayed in the sources interface of Cydia.'); ?>
              </p>
            </div>
            <div class="form-group">
                        <label>
                <?php _e('Repo Label'); ?>
              </label>
              <input class="form-control" type="text" required="required" name="label" value="<?php if (!empty($release['Label'])) {echo htmlspecialchars($release['Label']);} ?>" />
              <p class="help-block">
                <?php _e( 'This name will be displayed at the top of the package list interface.'); ?>
              </p>
            </div>
            <div class="form-group">
            <label>
                <?php _e('Codename'); ?>
              </label>
              <input class="form-control" type="text" name="codename" value="<?php if (!empty($release['Codename'])) {echo htmlspecialchars($release['Codename']);} ?>" />
            </div>
            <div class="form-group">
              <label>
                <?php _e( 'Description'); ?>
              </label>

              <textarea class="form-control" type="text" rows="3" required="required" name="description">
<?php if (!empty($release[ 'Description'])) {echo $release[ 'Description'];} ?>
              </textarea>

            </div>
            <div class="form-group">
              <label class="control-label">
                <?php _e( 'Version'); ?>
              </label>

              <input class="form-control" type="text" name="version" value="<?php if (!empty($release['Version'])) {echo htmlspecialchars($release['Version']);} ?>" />
            </div>
            <div style="margin-bottom: 0px;" class="form-group">
              <label>
                <?php _e( 'Repository Icon'); ?>
              </label>
              <input class="form-control" type="file" name="icon" accept="image/x-png" />
              <p class="help-block">Allowed Upload format is PNG
                </p>
            </div>
          </div>
        </form>
      </div> 
    </div>
				
<?php
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "set") {
		$release_text = "Origin: ".stripslashes($_POST['origin']);
		$release_text .= "\nLabel: ".$_POST['label'];
		$release_text .= "\nSuite: stable";
		$release_text .= "\nVersion: ".$_POST['version'];
		$release_text .= "\nCodename: ".$_POST['codename'];
		$release_text .= "\nArchitectures: iphoneos-arm";
		$release_text .= "\nComponents: main";
		$release_text .= "\nDescription: ".str_replace("\n","<br />",$_POST['description']);
		$release_text .= "\n";
		$release_handle = fopen(CONF_PATH.'release.save',"w");
		fputs($release_handle,stripslashes($release_text));
		fclose($release_handle);
		if (pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION) == "png") {
			if (file_exists("../CydiaIcon.png")) {
				$result_1 = unlink("../CydiaIcon.png");
			}
			$result_2 = rename($_FILES['icon']['tmp_name'], "../CydiaIcon.png");
			if (!$result_1 OR !$result_2) {
				echo '<h3 class="alert alert-error">'.__('Upload failed, please check the file permissions.').'<br /><a href="release.php">'.__('Back').'</a></h3>';
			} else {
				echo '<h3 class="alert alert-success">'.__('Upload icon completed.').'<br />'.__('Repository settings save complete, rebuild the list to apply the changes.').'<br /><a href="release.php">'.__('Back').'</a></h3>';
			}
		} else {
			echo '<h3 class="alert alert-success">'.__('Repository settings save complete, rebuild the list to apply the changes.').'<br /><a href="release.php">'.__('Back').'</a></h3>';
		}
	}
?>
	</div>
 <script>
  function saveRepoSettings(event) {
  var formData = $('#repoSettingsForm').serialize();
  var editBuddy = document.getElementById('repoSettingsForm');
  var action = $( '#repoSettingsForm' ).attr( 'action' )
  $.ajax({
    type: "POST",
    url: action,
    data: formData,
    headers: {'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', 'Content-Type': 'application/x-www-form-urlencoded', 'Upgrade-Insecure-Requests': '1'},
    mimeType: 'multipart/form-data',
    success: function() {
      toastr.success("Repo Settings were Updated");
                  toastr.options = {
                   "closeButton": true,
                   "debug": false,
                   "progressBar": true,
                   "positionClass": "toast-top-right",
                   "onclick": null,
                   "showDuration": "400",
                   "hideDuration": "1000",
                   "timeOut": "7000",
                   "extendedTimeOut": "1000",
                   "showEasing": "swing",
                   "hideEasing": "linear",
                   "showMethod": "fadeIn",
                   "hideMethod": "fadeOut"
                }
              }
   });
  }
  </script>
  <script type="text/javascript">
var action = $( '#repoSettingsForm' ).attr( 'action' )
  $( '#repoSettingsForm' )
  .submit( function( e ) {
    $.ajax( {
      url: action,
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false,
      success: function() {
      toastr.success("Section was Added");
                  toastr.options = {
                   "closeButton": true,
                   "debug": false,
                   "progressBar": true,
                   "positionClass": "toast-top-right",
                   "onclick": null,
                   "showDuration": "400",
                   "hideDuration": "1000",
                   "timeOut": "7000",
                   "extendedTimeOut": "1000",
                   "showEasing": "swing",
                   "hideEasing": "linear",
                   "showMethod": "fadeIn",
                   "hideMethod": "fadeOut"
                }
              }
   });
    e.preventDefault();
  } );
  </script>

</body>
</html>
<?php
} else {
	header("Location: login.php");
	exit();
}
?>