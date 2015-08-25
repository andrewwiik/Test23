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

/* DCRM Debian List */

session_start();
define("DCRM",true);
$activeid = 'center';
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
	require_once("header.php");
	class_loader('CorePage');

	if (!isset($_GET['action'])) {
		if (isset($_GET['search']))
		if (isset($_GET['upload'])) {
		echo upload($_FILES["deb"]);
		exit();
	}
	{
?>
<link href="css/plugins/footable/footable.core.css" rel="stylesheet">
<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Manage Packages</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a href="index.html">Packages</a>
                        </li>
                        <li class="active">
                            <strong>Manage Packages</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                        <button id="step5" type="button" onclick="javascript:$('#uploadModal').modal('show');" class="btn btn-primary"><i class="fa fa-upload"></i>&nbsp;&nbsp;<span class="bold">Upload Package</span></button>
                    </div>
                </div>
            </div>
            <div class="wrapper wrapper-content animated fadeInRight">
                <div id="packagesbox" class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Packages</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                             <?php
{
?>
                            <li<?php if ( isset($activeid) && 'view' == $activeid ) echo ' class="a
                            ctive"'; ?>><a href="javascript:opt(1)"><?php _e('View Details'); ?></a></li>
                            <li<?php if ( isset($activeid) && 'edit' == $activeid && isset($_GET['action']) && ($_GET['action'] == 'advance' || $_GET['action'] == 'advance_set') ) echo ' class="active"'?>><a href="javascript:opt(3)"><?php _e('Advance Editing'); ?></a></li>
                            <li id="sli"></li>
                    </ul>
                </div>
<?php
}
?>
                            </ul>
                        </div>
                    <div class="ibox-content" style="display: block;">

                        <?php
			if (isset($_GET['page'])) {
				$page = $_GET['page'];
			} elseif (isset($_SESSION['page'])) {
				$page = $_SESSION['page'];
			} else {
				$page = 1;
			}
			if ($page <= 0 OR $page >= 100) {
				$page = 1;
			}
			unset($_SESSION['contents']);
			unset($_SESSION['type']);
			$_SESSION['page'] = $page;
			$row_start = $page * 20 - 20;
			$lists = DB::fetch_all("SELECT `ID`, `Package`, `Name`, `Version`, `DownloadTimes`, `Stat`, `Size`, `Section` FROM `".DCRM_CON_PREFIX."Packages` ORDER BY `Stat` DESC, `ID` DESC, `Version` DESC, `Name` DESC LIMIT " . (string)$row_start. ",20");
?>
								<div id="packages-table" class="table-responsive">
								<table class="footable table" data-page-size="9000" data-filter=#top-search><thead><tr>
									<th style="width:5%;" data-sort-ignore="true"></th>
									<th id="packagestablename"><?php _e('Name'); ?></th>
									<th id="packagestableversion" style="width:12.5%;"><?php _e('Version'); ?></th>
									<th id="packagestablesize" data-type="numeric" style="width:12.5%;"><?php _e('Size'); ?></th>
									<th  id="packagestabledownloads"data-type="numeric" style="width:10%;"><?php _e('Downloads'); ?></th>
									<th id="packagestablevisibility" data-type="numeric" style="width:10%; text-align:center;" data-sort-ignore="true"><?php _e('Visibility'); ?></th>
									<th id="packagestabledelete" style="width:5%; text-align:center;" data-sort-ignore="true"><?php _e('Delete'); ?></th>
									<th id="packagestableedit" style="width:5%; text-align:center;" data-sort-ignore="true" >Edit</th>
								</tr></thead><tbody>
<?php
			foreach ($lists as $list) {
?>
								<tr id="pkg-<?php echo $list['ID']; ?>">
									<td><input type="checkbox" class="i-checks" data-toggle="checkbox" name="package" value="<?php echo($list['ID']); ?>" onclick="javascript:show(<?php echo $list['Stat']; ?>);" /></td>
<?php
				if (empty($list['Name']))
					$list['Name'] = AUTOFILL_NONAME;
				$color = array(-1 => 'gray', 1 => '#08C', 2 => 'green', 3 => 'yellow');
?>
									<td><a style="color: <?php echo($color[$list['Stat']]); ?>;"href = "view.php?id=<?php echo($list['ID']); ?>"><?php echo htmlspecialchars($list['Name']); ?></a></td>
									<td><?php echo(htmlspecialchars($list['Version'])); ?></td>
									<td><?php echo(sizeext($list['Size'])); ?></td>
									<td><?php echo($list['DownloadTimes']); ?></td>
									<td style="text-align: center;"><a id="visibility" data-package-visibility="<?php echo($list['Stat']); ?>" data-package-id="<?php echo $list['ID']; ?>" onclick="togglePackageVisibility(event);" class="visibility fa" style="color:#1ab394;"></a></td>
									<td style="text-align: center;"><a data-package-bundle="<?php echo($list['Package']); ?>"data-package-name="<?php echo($list['Name']); ?>" data-package-id="<?php echo $list['ID']; ?>" onclick="deletePackage(event);" class="fa fa-trash" style="text-align: center; color:#ed5565;"></a></td>
									<td style="text-align: center;"><a data-package-id="<?php echo $list['ID']; ?>" onclick="editPackage(event);" class="fa fa-edit" style="color:#1ab394;"></a></td>
								</tr>
<?php
			}
?>
								</tbody></table>
                    </div>
                </div>
            </div>
                <div class="modal inmodal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" style="width: 63%;">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-archive modal-icon"></i>
                                            <h4 class="modal-title">Edit Package</h4>
                                            <small class="font-bold">Edit the package using the form below</small>
                                        </div>
                                        <div id="yay21"class="modal-body" style="padding: 30px 0px 0px 0px;">
                                        <div>
                                        </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button  type="button" onclick="editSubmit()" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal inmodal" id="uploadModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-upload modal-icon"></i>
                                            <h4 class="modal-title">Upload Package</h4>
                                            <small class="font-bold">Drop your .deb in the box below and it will automatically be uplaoded</small>
                                        </div>
                                        <div class="modal-body">
                                         <form class="form-horizontal dropzone" id="deb" method="POST" enctype="multipart/form-data" action="upload.php?action=upload">
                                 			
										</form>   
                                        </div>
                  
                                    </div>
                                </div>
                            </div>
<?php
			$packages_count = DB::result_first("SELECT count(*) FROM `".DCRM_CON_PREFIX."Packages`");
			$params = array('total_rows' => (int)$packages_count, 'method' => 'html', 'parameter' => 'center.php?page=%page', 'now_page'  => $page, 'list_rows' => 30);
			$page = new Core_Lib_Page($params);
			echo('<div class="page">' . $page->show(2) . '</div>');
		}
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "search" AND !empty($_GET['contents']) AND !empty($_GET['type'])) {
		unset($_SESSION['page']);
		$_SESSION['contents'] = $_GET['contents'];
		$_SESSION['type'] = $_GET['type'];
		if (isset($_GET['page'])) {
			$page = (int)$_GET['page'];
		} else {
			$page = 1;
		}
		if ($page <= 0 OR $page >= 100) {
			$page = 1;
		}
		$row_start = $page * 10 - 10;
?>
				<h2><?php _e('Manage Packages'); ?></h2>
				<br />
				<h3 class="navbar"><?php $contents = isset($_GET['udid']) ? __('Protection Packages') : $_GET['contents']; printf(__('Search Packages: %s'), $contents); ?></h3>
<?php
		$search_type = (int)$_GET['type'];
		$query_type = array('', 'Package', 'Name', 'Author', 'Description', 'Maintainer', 'Sponsor', 'Section', 'Tag');

		if(isset($query_type[$search_type])){
			$r_value = DB::real_escape_string(str_replace('*', '%', str_replace('?', '_', $_GET['contents'])));
			$lists = DB::fetch_all("SELECT `ID`, `Package`, `Name`, `Version`, `DownloadTimes`, `Stat`, `Size` FROM `".DCRM_CON_PREFIX."Packages` WHERE `" . $query_type[$search_type] . "` LIKE '%" . $r_value . "%' ORDER BY `Stat` DESC, `ID` DESC LIMIT ".(string)$row_start.",20");
?>
								<table class="footable table" data-page-size="20"><thead><tr>
									<th data-sort-ignore="true" style="width:13px;"></th>
									<th><?php _e('Name'); ?></th>
									<th style="width:20%;"><?php _e('Version'); ?></th>
									<th data-type="numeric" style="width:20%;"><?php _e('Size'); ?></th>
									<th data-type="numeric" style="width:10%;"><?php _e('Downloads'); ?></th>
									<th data-sort-ignore="true" style="width:5%; text-align:center;"><?php _e('Delete'); ?></th>
									<th data-sort-ignore="true" style="width:5%;">Edit</th>
<?php 		if(isset($_GET['udid'])) { ?><th style="width:5%;"><?php _e('Binding'); ?></th><?php } ?>
								</tr></thead><tbody>
<?php
			if(isset($_GET['udid'])) {
				$udid_query = DB::result_first("SELECT `Packages` FROM `".DCRM_CON_PREFIX."UDID` WHERE `UDID` = '" . $_GET['udid'] . "'");
				$packages_udid = TrimArray(explode(',', $udid_query));
			}
			foreach ($lists as $list) {
?>
								<tr>
									<td><label class="checkbox"><input class="i-checks" type="checkbox" data-toggle="checkbox" name="package" value="<?php echo($list['ID']); ?>" onclick="javascript:show(<?php echo $list['Stat']; ?>);" /></label></td>
<?php
				if (empty($list['Name']))
					$list['Name'] = AUTOFILL_NONAME;
				$color = array(-1 => 'gray', 1 => '#08C', 2 => 'green', 3 => 'yellow');
?>
									<td><a style="color: <?php echo($color[$list['Stat']]); ?>;" href = "view.php?id=<?php echo($list['ID']); ?>"><?php echo htmlspecialchars($list['Name']); ?></a></td>
									<td><?php echo(htmlspecialchars($list['Version'])); ?></ul></td>
									<td><?php echo(sizeext($list['Size'])); ?></td>
									<td><?php echo($list['DownloadTimes']); ?></td>
									<td style="text-align: center;"><a href="center.php?action=delete_confirm&name=<?php echo($list['Package']); ?>&id=<?php echo $list['ID']; ?>" class="fa fa-trash" style="text-align: center; color:#ed5565;"></a></td>
									<td style="text-align: center;"><a href="center.php?action=search&contents=<?php echo($list['Package']); ?>&type=1" class="fa fa-history" style="color:#1ab394;"></a></td>
<?php
				if(isset($_GET['udid'])) {
					if(in_array($list['Package'], $packages_udid, true)) {
?>
									<td style="text-align: center;"><a href="udid.php?action=binding&contents=<?php echo($list['Package']); ?>&amp;udid=<?php echo $_GET['udid']; ?>&amp;delete=true" class="fa fa-trash" style="text-align: center; color:#ed5565;" title="<?php _e('Delete'); ?>"></a></td>
<?php				} else { ?>
									<td><a href="udid.php?action=binding&contents=<?php echo($list['Package']); ?>&amp;udid=<?php echo $_GET['udid']; ?>" class="fa fa-history" style="color:#1ab394;" title="<?php _e('Binding'); ?>">※</a></td>
<?php
					}
				}
?>
								</tr>
<?php
			}
?>
								</tbody></table>
<?php
			$packages_count = DB::result_first("SELECT count(*) FROM `".DCRM_CON_PREFIX."Packages` WHERE `" . $query_type[$search_type] . "` LIKE '%" . $r_value . "%'");
			$params = array('total_rows' => (int)$packages_count, 'method' => 'html', 'parameter' => 'center.php?action=search&contents='.$_GET['contents'].'&type='.$_GET['type'].'&page=%page', 'now_page'  => $page, 'list_rows' => 30);
			$page = new Core_Lib_Page($params);
			echo('<div class="page">' . $page->show(2) . '</div>');
		}
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "delete_confirm" AND !empty($_GET['name']) AND !empty($_GET['id'])) {
?>
						<h3 class="alert"><?php printf(__('Are you sure you want to delete: %s?'), htmlspecialchars($_GET['name']));?><br /><?php _e('This operation is irreversible, all related data will be delete!'); ?></h3>
						<a class="btn btn-danger" href="center.php?action=delete&id=<?php echo($_GET['id']); ?>"><?php _e('Delete'); ?></a>　
						<a class="btn btn-warning" href="center.php?action=submit&id=<?php echo($_GET['id']); ?>"><?php _e('Hide'); ?></a>　
<?php
		echo('<a class="btn btn-success" href="center.php?');
		if (!empty($_SESSION['page'])) {
			echo("page=" . $_SESSION['page']);
		} elseif (!empty($_SESSION['contents']) AND !empty($_SESSION['type'])) {
			echo("action=search&contents=" . $_SESSION['contents'] . "&type=" . $_SESSION['type']);
		}
		echo('">'.__('Cancel').'</a>');
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "delete" AND !empty($_GET['id'])) {
		$delete_id = (int)$_GET['id'];
		$f_filename = DB::result_first("SELECT `Filename` FROM `".DCRM_CON_PREFIX."Packages` WHERE `ID` = '" . $delete_id . "'");

		unlink($f_filename);
		DB::delete(DCRM_CON_PREFIX.'Packages', array('ID' => $delete_id));
		DB::delete(DCRM_CON_PREFIX.'ScreenShots', array('PID' => $delete_id));
		DB::delete(DCRM_CON_PREFIX.'Reports', array('PID' => $delete_id));
		if (!empty($_SESSION['page'])) {
			header("Location: center.php?page=" . $_SESSION['page']);
			exit();
		} elseif (!empty($_SESSION['contents']) AND !empty($_SESSION['type'])) {
			header("Location: center.php?action=search&contents=" . $_SESSION['contents'] . "&type=" . $_SESSION['type']);
			exit();
		} else {
			header("Location: center.php");
			exit();
		}
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "submit" AND !empty($_GET['id'])) {
		$submit_id = (int)$_GET['id'];
		$s_info = DB::fetch_first("SELECT `Package`, `Stat` FROM `".DCRM_CON_PREFIX."Packages` WHERE `ID` = '" . $submit_id . "'");
		if ((int)$s_info['Stat'] != 1) {
			//$s_query = DB::query("UPDATE `".DCRM_CON_PREFIX."Packages` SET `Stat` = '-1' WHERE `Package` = '" . $s_info['Package'] . "'");
			DB::update(DCRM_CON_PREFIX.'Packages', array('Stat' => '1'), array('ID' => $submit_id));
		} else {
			DB::update(DCRM_CON_PREFIX.'Packages', array('Stat' => '-1'), array('ID' => $submit_id));
		}
		if (!empty($_SESSION['page'])) {
			header("Location: center.php?page=" . $_SESSION['page']);
			exit();
		} elseif (!empty($_SESSION['contents']) AND !empty($_SESSION['type'])) {
			header("Location: center.php?action=search&contents=" . $_SESSION['contents'] . "&type=" . $_SESSION['type']);
			exit();
		} else {
			header("Location: center.php");
			exit();
		}
	}
?>
			</div>
	</div>
	    
	    <script>
        $(document).ready(function() {

            $('.footable').footable();

        });

    </script>
<script type="text/javascript">
    function deletePackage(event) {
    var element = event.target;
    var name = element.getAttribute("data-package-name");
    var id = element.getAttribute("data-package-id");
    var footable = $('table').data('footable');
    var row = document.getElementById("pkg-"+id);
    swal({
                        title: "Are you sure?",
                        text: "Your will not be able to recover the package:"+" "+name,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel plx!",
                        closeOnConfirm: true,
                        closeOnCancel: false },
                    function (isConfirm) {
                        if (isConfirm) {
                        	 $.ajax({
                       			type: "GET",
                       			url: "center.php",
                       			data: "action=delete&id="+id,
                       			success: function(msg){ 
                       				toastr.error(name + " " + " has been succesfully deleted", "Package Deleted");
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
								footable.removeRow(row);
							}
                     		})
                       } else {
                            swal("Cancelled", "Your package is safe :)", "error");
                        }
                    });
}
    </script>
    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
            $('.i-checks').each(function () {
        $onclick = $(this).attr("onclick");
        $iCheckName = $(this).attr("name");
        $buttonTrigger = 'input[name="' + $iCheckName + '"';
        var $this = $(this);
        if ($onclick != undefined) {
            if ($onclick.length > 0) {
                $($buttonTrigger).on('ifChecked', function (event) {
                    $(this).trigger("click");
                });
            }
        }
    });

        });
    </script>
    <script type="text/javascript">
    function pageLoad () {
    var hiddenPackages = $('.visibility[data-package-visibility="-1"]');
	var visiblePackages = $('.visibility[data-package-visibility="1"]');
	var undecidedPackages = $('.visibility[data-package-visibility="2"]');

	$(hiddenPackages).addClass('fa-eye-slash');
	$(hiddenPackages).css("color","grey");
	$(visiblePackages).addClass('fa-eye');
	$(visiblePackages).css("color","#1ab394");
	$(undecidedPackages).addClass('fa-eye-slash');
	$(undecidedPackages).css("color","grey");
	 $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
            $('.i-checks').each(function () {
        $onclick = $(this).attr("onclick");
        $iCheckName = $(this).attr("name");
        $buttonTrigger = 'input[name="' + $iCheckName + '"';
        var $this = $(this);
        if ($onclick != undefined) {
            if ($onclick.length > 0) {
                $($buttonTrigger).on('ifChecked', function (event) {
                    $(this).trigger("click");
                });
            }
        }
    });
$('.footable').footable();
    }
    </script>
    <script type="text/javascript">
$(document ).ready(function() {
pageLoad();
});

    </script>
	<script type="text/javascript">
	function show(stat) {
		sli = document.getElementById('sli');
		
		if (stat == 1) {
			sli.innerHTML = '<a href="javascript:opt(4)"><?php _e('Hide Package'); ?></a>';
		} else {
			sli.innerHTML = '<a href="javascript:opt(5)"><?php _e('Display Package'); ?></a>';
		}
		document.getElementById('mbar').style.display = "";
	}
	</script>
	<script type="text/javascript" src="plugins/ajaxfileupload/ajaxfileupload.min.js"></script>
	<script type="text/javascript">
		function ajaxFileUpload() {
			fakepath = document.getElementById("deb").value;
			if (fakepath != "") {
				if (/\.[^\.]+$/.exec(fakepath) == ".deb") {
					$("#tips").html("<?php _e('Please wait for uploading...'); ?>");
					$.ajaxFileUpload(
						{
							url: "center.php?action=upload",
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
	<script type="text/javascript">
function togglePackageVisibility(event) {
    var element = event.target;
    var id = element.getAttribute("data-package-id");
    $.ajax({
                       			type: "GET",
                       			url: "center.php",
                       			data: "action=submit&id="+id,
                       			success: function(msg){ 
                       				$(" #packages-table").load('center.php  #packages-table', function(){
                       				pageLoad();
                       			});
                       				}});
}

	</script>
	<script>
	// "myAwesomeDropzone" is the camelized version of the HTML element's ID
Dropzone.options.deb = {
  init: function() {
  	var deb = this;
  	$('#uploadModal').on('hidden.bs.modal', function () {
    deb.removeAllFiles();
})
    this.on("success", function(file) {
    	var filename = file.name
    	$.ajax({
                       			type: "GET",
                       			url: "import.php",
                       			data: "type=1&filename="+filename,
                       			success: function(msg){    	toastr.success("Package Uploaded Successfully");
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
								}}});
$(" #packages-table").load('center.php  #packages-table', function(){
                       				pageLoad();
                       			});
});
  },
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
<script type="text/javascript">
function editPackage(event) {
    var element = event.target;
    var id = element.getAttribute("data-package-id");
$.ajax({
   url: "edit.php?id="+id+"#editForm",
   success: function(data){
     var data2 = $(data).find('#editForm').html();
     $( '#yay21' ).html(data2);
     $('.summernote').summernote();
     $('#editModal').modal('show');
     var editBuddy = document.getElementById('actualEditForm');
     editBuddy.setAttribute('data-edit-id', id);
   }
 });
 }
 </script>
 	<script>
 	function editSubmit () {
 	$('.summernote').each( function() {
    $(this).val($(this).code());
});
	var formData = $('#actualEditForm').serialize();
	var editBuddy = document.getElementById('actualEditForm');
	var id = $('#actualEditForm').attr('data-edit-id');
	$.ajax({
    type: "POST",
    url: "edit.php?action=set&id="+id,
    data: formData,
    processData: false,
  	contentType: false,
  	headers: {'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', 'Content-Type': 'application/x-www-form-urlencoded', 'Upgrade-Insecure-Requests': '1'},
  	mimeType: 'multipart/form-data',
    success: function() {
    	$('#editModal').modal('hide');
    	$(" #packages-table").load('center.php  #packages-table', function(){
                       				pageLoad();
                       			});
      toastr.success("Package was Updated");
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
	var tour = new Tour({
  steps: [
  {
	orphan: true,
    title: "Welcome",
    content: "Take a Quick Tour through WEIPDCRM 2.0"
  },
  {
    element: "#step1",
    title: "Settings",
    content: "This Button will take you to your Repo Settings",
    placement: "left"
  },
  {
    element: "#step2",
    placement: "left",
    title: "Rebuild Package List",
    content: "This button will rebuild the file that's tell's your repo what packages to dispaly to the end user"
  },
   {
    element: "#step3",
    placement: "left",
    title: "Log out",
    content: "This button will log you out of your current session"
  },
  {
    element: "#step5",
    placement: "left",
    title: "Upload a Package",
    reflex: true,
    content: "Clicking this button will show the upload package interface where you can drop .deb files"
  },
  {
    element: "#packagesbox",
    placement: "top",
    title: "Packages Table",
    content: "This table contains a list of all the packages in your repo whether they are visible to the public or not"
  },
    {
    element: "#packagestablename",
    placement: "top",
    title: "Package Name",
    content: "The data in this column will tell you the packages name"
  },
  {
    element: "#packagestableversion",
    placement: "top",
    title: "Package Version",
    content: "The data in this column will tell you the packages version number"
  },
    {
    element: "#packagestablesize",
    placement: "top",
    title: "Package Size",
    content: "The data in this column will tell you the package's deb file size"
  },
      {
    element: "#packagestabledownloads",
    placement: "top",
    title: "Number of Downloads",
    content: "The data in this column will tell you how many times this version of the package has been downloaded"
  },
        {
    element: "#packagestablevisibility",
    placement: "top",
    title: "Package Visibility",
    content: "The data in this column will tell you whether the package is visible to the public, a green eye means the public can see it and a grey eye with a slash means it's currently hidden from the public"
  },
        {
    element: "#packagestabledelete",
    placement: "top",
    title: "Package Deletion",
    content: "Pressing this button will delete the package permanently in the corresponding row"
  },
          {
    element: "#packagestableedit",
    placement: "top",
    title: "Edit Package",
    content: "Pressing this button will open a Modal allowing you to edit the details of the package"
  }
]});
packagestablesize

// Initialize the tour
tour.init();
</script>
</body>
</html>
<?php
} else {
	header("Location: login.php");
	exit();
}
?>