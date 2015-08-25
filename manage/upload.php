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

/* DCRM Upload Page */

session_start();
define("DCRM",true);
$activeid = 'upload';
$localetype = 'manage';
define('MANAGE_ROOT', dirname(__FILE__).'/');
define('ABSPATH', dirname(MANAGE_ROOT).'/');
require_once ABSPATH.'system/common.inc.php';

function upload($file, $path = '../upload/', $name = '') {
	if ($file["size"] <= 0) {
		return __('File size incorrect!');
	}
	if (pathinfo($_FILES['deb']['name'], PATHINFO_EXTENSION) != "deb") {
		return __('File type incorrect!');
	}
	if ($file["error"] > 0) {
		return sprintf(__('Upload failed, Error Code: %s.'), $file["error"]);
	}
	if (file_exists($path . $file["name"])) {
		return sprintf(__('%s already exists.'), $file["name"]);
	}
	$name = ($name == '') ? $file["name"] : $name;
	move_uploaded_file($file["tmp_name"], $path . $name);
	return sprintf(__('Uploaded successfully: %s.'), $path . $name);
}

if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
	if (isset($_GET['action']) && $_GET['action'] == "upload" && !empty($_FILES)) {
		echo upload($_FILES["deb"]);
		exit();
	}

	require_once("header.php");
	if (!isset($_GET['action']) && !isset($_GET['mode'])) {
?>
				<form class="form-horizontal dropzone" id="deb" method="POST" enctype="multipart/form-data" action="upload.php?action=upload">
				</form>
			</div>
		</div>
	</div>
	</div>
	<script type="text/javascript" src="plugins/ajaxfileupload/ajaxfileupload.min.js"></script>
	<script type="text/javascript">
		function ajaxFileUpload() {
			fakepath = document.getElementById("deb").value;
			if (fakepath != "") {
				if (/\.[^\.]+$/.exec(fakepath) == ".deb") {
					$("#tips").html("<?php _e('Please wait for uploading...'); ?>");
					$.ajaxFileUpload(
						{
							url: "upload.php?action=upload",
							secureuri: false,
							fileElementId: 'myAwesomeDropzone',
							dataType: 'text',
							success: function(data) {
								$("#tips").html(data);
							}
						}
					);
					return true;
				} else {
					$("#tips").html("<?php _e('Invalid file type!'); ?>");
					return false;
				}
			} else {
				$("#tips").html("<?php _e('Please select a package!');?>");
				return false;
			}
		}
	</script>
	<script>
	// "myAwesomeDropzone" is the camelized version of the HTML element's ID
Dropzone.options.deb = {
  paramName: "deb", // The name that will be used to transfer the file
  maxFilesize: 2, // MB
  accept: function(file, done) {
    if (file.name == "justinbieber.jpg") {
      done("Naha, you don't.");
    }
    else { done(); }
  }
};
</script>
<?php
	}
?>
</body>
</html>
<?php
} else {
	header("Location: login.php");
	exit();
}
?>