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

/* DCRM Section Manage */

session_start();
define("DCRM",true);
$localetype = 'manage';
define('MANAGE_ROOT', dirname(__FILE__).'/');
define('ABSPATH', dirname(MANAGE_ROOT).'/');
require_once ABSPATH.'system/common.inc.php';
class_loader('tar');
class_loader('CorePage');
$activeid = 'sections';

if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
	require_once("header.php");

	if (!isset($_GET['action'])) {
?>
<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2><?php _e('Manage Sections'); ?></h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a href="index.html">Packages</a>
                        </li>
                        <li class="active">
                            <strong><?php _e('Manage Sections'); ?></strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div style="display: inline-block; float: right;" class="title-action">
                        <button id="step5" type="button" onclick="createIconPackage(event);" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;<span class="bold"><?php _e('Create Icon Package'); ?></span></button>
                    </div>
                    <div style="display: inline-block; float: right; margin-right: 1em;" class="title-action">
                        <button id="step5" type="button" onclick="addSection(event);" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;<span class="bold"><?php _e('Add Section'); ?></span></button>
                    </div>
                </div>
            </div>
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php _e('Sections List'); ?></h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="sections.php?action=create"><?php _e('Create Icon Package'); ?></a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <?php
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
		if ($page <= 0 OR $page >= 6) {
			$page = 1;
		}
		$page_a = $page * 10 - 10;
		if ($page == 1) {
			$page_b = $page;
		} else {
			$page_b = $page - 1;
		}
		$list_query = DB::query("SELECT * FROM `".DCRM_CON_PREFIX."Sections` ORDER BY `ID` DESC LIMIT ".(string)$page_a.",50");
		if ($list_query == FALSE) {
			goto endlabel;
		} else {
?>
					<table id="section-table" class="footable table" data-page-size="9000" data-filter="#top-search"><thead><tr>
					<th><?php _e('Name'); ?></th>
					<th><?php _e('Icon'); ?></th>
					<th><?php _e('Last Change'); ?></th>
					<th style="text-align: center; width: 4em;"><?php _e('Edit'); ?></th>
					<th style="text-align: center; width: 4em"><?php _e('Delete'); ?></th>
					</tr></thead><tbody>
<?php
			while ($list = mysql_fetch_assoc($list_query)) {
?>
					<tr id="sec-<?php echo $list['ID']; ?>">
					<td><a title="<?php _e('Click to view packages in this section.'); ?>" href="center.php?action=search&amp;contents=<?php echo(urlencode($list['Name'])); ?>&amp;type=7"><?php echo(htmlspecialchars($list['Name'])); ?></a></td>
<?php
				if ($list['Icon'] != "") {
?>
					<td><a href="<?php echo(base64_decode(DCRM_REPOURL)); ?>/icon/<?php echo($list['Icon']); ?>"><?php echo($list['Icon']); ?></a></td>

<?php
				} else {
?>
					<td><?php _e('No Icon'); ?></td>
<?php
				}
?>
					<td><?php echo($list['TimeStamp']); ?></td>
					<td  style="text-align: center;"><a data-action="sections.php?action=edit&amp;id=<?php echo($list['ID']); ?>" onclick="javascript:editSection(event)" class="fa fa-edit" style="color:#1ab394; text-align: center;"></a></td>
					<td  style="text-align: center;"><a data-name="<?php echo(htmlspecialchars($list['Name'])); ?>" data-id="<?php echo $list['ID']; ?>" data-action="sections.php?action=delete_confirmation&amp;id=<?php echo($list['ID']); ?>&amp;name=<?php echo($list['Name']); ?>" onclick="javascript:deleteSection(event)" class="fa fa-trash" style="text-align: center; color:#ed5565;"></a></td>
					</tr>
<?php
			}
?>
					</tbody></table>
                    </div>
    
                </div>
		
<?php
			$q_info = DB::query("SELECT count(*) FROM `".DCRM_CON_PREFIX."Sections`");
			$info = DB::fetch_row($q_info);
			$totalnum = (int)$info[0];
			$params = array('total_rows'=>$totalnum, 'method'=>'html', 'parameter' =>'sections.php?page=%page', 'now_page'  =>$page, 'list_rows' =>10);
			$page = new Core_Lib_Page($params);
			echo '<div class="page">' . $page->show(2) . '</div>';
		}
	} elseif (!empty($_GET['action']) AND ($_GET['action'] == "add" || $_GET['action'] == "edit")) {
		// 获取编辑信息
		if($_GET['action'] == "edit"){
			if (isset($_GET['id']) && is_numeric($_GET['id'])) {
				$request_id = (int)$_GET['id'];
				if ($request_id < 1) {
					_e('Illegal request!');
					goto endlabel;
				}
			} else {
				_e('Illegal request!');
				goto endlabel;
			}
			$edit_info = DB::fetch_first("SELECT * FROM `".DCRM_CON_PREFIX."Sections` WHERE `ID` = '" . $request_id . "'");
			if (null == $edit_info) {
				_e('Illegal request!');
				goto endlabel;
			}
		}
?>
						<h2><?php _e('Manage Sections'); ?></h2>
						<br />
						<h3 class="navbar"><span><a href="sections.php"><?php _e('Sections List'); ?></a></span>　<span><?php $_GET['action'] == "edit" ? _e('Edit Section') : _e('Add Section'); ?></span>　<span><a href="sections.php?action=create"><?php _e('Create Icon Package'); ?></a></span></h3>
						<br />
						<div id="sectionForm">
						<form id="actualSectionForm" class="form-horizontal" method="POST" enctype="multipart/form-data" action="sections.php?action=add_now" >
						<div class="col-lg-12">
						<div class="col-lg-12">
						<div class="form-group">
							<label><?php _e('Section Name'); ?></label>
								<input class="form-control input-xlarge" name="contents" required="required"  value="<?php if (!empty($edit_info['Name'])) echo $edit_info['Name']; ?>"/>
						</div>
						</div>
						<div class="col-lg-12">
						<div class="form-group">
							<label><?php _e('Section Icon'); ?></label>
								<input type="file" class="dropzone form-control" id="iconUpload" name="icon" accept="image/x-png" />
								<p class="help-block">
<?php
		if($_GET['action'] == "edit"){
			if ($edit_info['Icon'] != "") {
				printf(__('The current icon is %s.'), "<a href='".base64_decode(DCRM_REPOURL)."/icon/{$edit_info['Icon']}'>{$edit_info['Icon']}</a>");
				echo('<br/>');
				printf(__('You can click <a href="%s">Here</a> to delete current icon.'), "sections.php?action=delete_icon&amp;id={$edit_info['ID']}&amp;name={$edit_info['Name']}");
				echo("<input type='hidden' name='exist_icon' value='{$edit_info['Icon']}' />");
			} else {
				_e('There are currently no icon.');
			}
			echo("<input type='hidden' name='id' value='{$edit_info['ID']}' />");
		}
?>
								</p>
							</div>
						</div>						
						</form>
						</div>
<?php 
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "add_now" AND !empty($_POST['contents'])) {
		$new_name = DB::real_escape_string($_POST['contents']);
		$num = DB::result_first("SELECT count(*) FROM `".DCRM_CON_PREFIX."Sections`");
		if ($num <= 50) {
			if (pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION) == "png") {
				if (file_exists("../icon/" . $_FILES['icon']['name'])) {
					unlink("../icon/" . $_FILES['icon']['name']);
				}
				$move = rename($_FILES['icon']['tmp_name'],"../icon/" . $_FILES['icon']['name']);
				if (!$move) {
					$alert = __('Upload failed, please check the file permissions.');
					goto endlabel;
				} else {
					if(isset($_POST['id'])){
						$n_query = DB::query("UPDATE `".DCRM_CON_PREFIX."Sections` SET `Name` = '{$new_name}', `Icon` = '{$_FILES['icon']['name']}' WHERE `ID` = ".$_POST['id']);
					} else {
						$n_query = DB::query("INSERT INTO `".DCRM_CON_PREFIX."Sections`(`Name`, `Icon`) VALUES('" . $new_name . "', '" . $_FILES['icon']['name'] . "')");
					}
				}
			} else {
				if(isset($_POST['id'])){
					if(isset($_POST['exist_icon'])){
						$n_query = DB::query("UPDATE `".DCRM_CON_PREFIX."Sections` SET `Name` = '{$new_name}', `Icon` = '{$_POST['exist_icon']}' WHERE `ID` = ".$_POST['id']);
					} else {
						$n_query = DB::query("UPDATE `".DCRM_CON_PREFIX."Sections` SET `Name` = '{$new_name}' WHERE `ID` = ".$_POST['id']);
					}
				} else {
					$n_query = DB::query("INSERT INTO `".DCRM_CON_PREFIX."Sections`(`Name`) VALUES('" . $new_name . "')");
				}
			}
		} else {
			$alert = __('You can add a maximum of 50 sections!');
			goto endlabel;
		}
		if (!$n_query) {
			goto endlabel;
		} else {
			header("Location: sections.php");
			exit();
		}
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "create") {
		if (defined("AUTOFILL_SEO") && defined("AUTOFILL_PRE")) {
			$alert = sprintf(__('Are you sure want to create the %s icon package?'), AUTOFILL_SEO) . '<br /><a href="sections.php?action=createnow">'.__('Create Now').'</a>';
			$alert_tag = 'alert-success';
		} else {
			$alert = __('You have not filled in SEO and autofill information, unable to use this function!');
		}
		goto endlabel;
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "createnow") {
		$new_name = DB::real_escape_string($_POST['contents']);
		$num = DB::result_first("SELECT count(*) FROM `".DCRM_CON_PREFIX."Sections` WHERE `Icon` != ''");
		if ($num < 1) {
			$alert = __('Cannot find any existing icon of section, please add an icon first, then create icon package.');
			goto endlabel;
		}
		if (file_exists(CONF_PATH.'empty_icon.deb')) {
			$r_id = randstr(40);
			if (!is_dir("../tmp/")) {
				$result = mkdir("../tmp/");
			}
			if (!is_dir("../tmp/" . $r_id)) {
				$result = mkdir("../tmp/" . $r_id);
				if (!$result) {
					$alert = __('Cannot create temporary directory, please check the file permissions!');
					goto endlabel;
				}
			}
			$deb_path = "../tmp/" . $r_id . "/icon_" . time() . ".deb";
			$result = copy(CONF_PATH.'empty_icon.deb', $deb_path);
			if (!$result) {
				$alert = __('Icon package template copy failed, please check the file permissions!');
				goto endlabel;
			}
			$raw_data = new phpAr($deb_path);
			$new_tar = new Tar();
			$new_path = "../tmp/" . $r_id . "/data.tar.gz";
			$icon_query = DB::fetch_all("SELECT * FROM `".DCRM_CON_PREFIX."Sections`");
			mkdir("../tmp/" . $r_id . "/Applications");
			mkdir("../tmp/" . $r_id . "/Applications/Cydia.app");
			mkdir("../tmp/" . $r_id . "/Applications/Cydia.app/Sections");
			foreach($icon_query as $icon_assoc){
				if ($icon_assoc['Icon'] != "") {
					// Compatible with earlier Cydia version and special situations
					$new_filename = $new_filenames[] = str_replace(" ", "_", $icon_assoc['Name']) . '.png';
					if(substr($new_filename, 0, 1) == '[' && substr($new_filename, -5, -4) == ']')
						$new_filenames[] = substr($new_filename, 1, -5) . '.png';
					$new_filepath = "../tmp/" . $r_id . "/Applications/Cydia.app/Sections/" . $new_filename;
					copy("../icon/" . $icon_assoc['Icon'], $new_filepath);
					chmod($new_filepath, 0755);
					foreach($new_filenames as $filename){
						$new_tar -> add_file("/Applications/Cydia.app/Sections/" . $filename, 0755, file_get_contents($new_filepath));
					}
				}
			}
			$new_tar -> save($new_path);
			$result = $raw_data -> replace("data.tar.gz", $new_path);

			if (!$result) {
				$alert = __('Icon package template rewriting failed!');
				goto endlabel;
			} else {
				$control_path = "../tmp/" . $r_id . "/control.tar.gz";
				$control_tar = new Tar();
				$f_Package = "Package: ".(defined("AUTOFILL_PRE") ? AUTOFILL_PRE : '')."sourceicon\nArchitecture: iphoneos-arm\nName: Source Icon\nVersion: 0.1-1\nAuthor: ".(defined("AUTOFILL_SEO")?AUTOFILL_SEO.(defined("AUTOFILL_EMAIL")?' <'.AUTOFILL_EMAIL.'>':''):'DCRM <i.82@me.com>')."\nSponsor: ".(defined("AUTOFILL_MASTER")?AUTOFILL_MASTER.(defined("AUTOFILL_EMAIL")?' <'.AUTOFILL_EMAIL.'>':''):'i_82 <http://82flex.com>')."\nMaintainer: ".(defined("AUTOFILL_MASTER")?AUTOFILL_MASTER.(defined("AUTOFILL_EMAIL")?' <'.AUTOFILL_EMAIL.'>':''):'i_82 <http://82flex.com>')."\nSection: Repositories\nDescription: Custom Empty Source Icon Package\n";
				$control_tar -> add_file("control", "", $f_Package);
				$control_tar -> save($control_path);
				$result = $raw_data -> replace("control.tar.gz", $control_path);
				if (!$result) {
					$alert = __('Icon package template rewriting failed!');
					goto endlabel;
				} else {
					$result = rename($deb_path, "../upload/" . "Icons-Package" . ".deb");
					if (!$result) {
						$alert = __('Icon package template repositioning failed!');
						goto endlabel;
					}
					header("Location: manage.php");
					exit();
				}
			}
		} else {
			$alert = __('Icon package template missing, please upload DCRM Pro again!');
			goto endlabel;
		}
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "delete_confirmation" AND !empty($_GET['id']) AND !empty($_GET['name'])) {
?>
						<h3 class="alert"><?php printf(__('Are you sure delete: %s ?'), htmlspecialchars($_GET['name'])); ?></h3>
						<a class="btn btn-warning" href="sections.php?action=delete&amp;id=<?php echo($_GET['id']); ?>"><?php _e('Confirm'); ?></a>　
						<a class="btn btn-success" href="sections.php"><?php _e('Cancel'); ?></a>
<?php
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "delete" AND !empty($_GET['id'])) {
		$delete_id = (int)$_GET['id'];
		DB::delete(DCRM_CON_PREFIX.'Sections', array('ID' => $delete_id));
		header("Location: sections.php");
		exit();
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "delete_icon" AND !empty($_GET['id'])){
		if(isset($_GET['delete_now'])){
			$delete_id = (int)$_GET['id'];
			$n_query = DB::query("UPDATE `".DCRM_CON_PREFIX."Sections` SET `Icon` = '' WHERE `ID` = {$delete_id}");
			header("Location: sections.php?action=edit&id={$_GET['id']}");
			exit();
		}
		if(!empty($_GET['name'])){
?>
						<h3 class="alert"><?php printf(__('Are you sure delete the section icon for %s ?'), htmlspecialchars($_GET['name'])); ?></h3>
						<a class="btn btn-warning" href="sections.php?action=delete_icon&amp;id=<?php echo($_GET['id']); ?>&amp;delete_now=true"><?php _e('Confirm'); ?></a>　
						<a class="btn btn-success" href="sections.php"><?php _e('Cancel'); ?></a>
<?php
		}
	}
	endlabel:
	if (isset($alert))
		echo '<h3 class="alert '.(isset($alert_tag) ? $alert_tag : 'alert-error').'">'.$alert.'<br /><a href="sections.php">'.__('Back').'</a></h3>';
?>
			</div>
		</div>
	</div>
	</div>
	</div>
	<div class="modal inmodal" id="addSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" style="width: 63%;">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-list-alt modal-icon"></i>
                                            <h4 class="modal-title">Add Section</h4>
                                            <small class="font-bold">Add a Section using the Form Below</small>
                                        </div>
                                        <div id="addSectionModalBody" class="modal-body" style="padding: 30px 0px 0px 0px;">
                                        <div>
                                        </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button  type="submit" form="actualSectionForm" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
    <div class="modal inmodal" id="editSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" style="width: 63%;">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-list-alt modal-icon"></i>
                                            <h4 class="modal-title">Edit Section</h4>
                                            <small class="font-bold">Edit the Section using the Form Below</small>
                                        </div>
                                        <div id="editSectionModalBody" class="modal-body" style="padding: 30px 0px 0px 0px;">
                                        <div>
                                        </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button  type="button" onclick="addSectionSubmit()" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
</body>
<script>
        $(document).ready(function() {
  Dropzone.options.myAwesomeDropzone = { // The camelized version of the ID of the form element
    // The configuration we've talked about above
    url: '#',
    previewsContainer: ".dropzone-previews",
    uploadMultiple: true,
    parallelUploads: 100,
    maxFiles: 100
  }
            $('.footable').footable();

        });
    </script>
<script type="text/javascript">
function addSection(event) {
$.ajax({
   url: "sections.php?action=add",
   success: function(data){
     var data2 = $(data).find('#sectionForm').html();
     $( '#addSectionModalBody' ).html(data2);
     $('#addSectionModal').modal('show');
     var action = $( '#actualSectionForm' ).attr( 'action' )
     Dropzone.options.iconUpload = { // The camelized version of the ID of the form element
    // The configuration we've talked about above
    url: action,
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 100,
    maxFiles: 100,

    // The setting up of the dropzone
    init: function() {
      var myDropzone = this;
      console.log(myDropzone); // This doesn't get logged when I check. <-------

      // First change the button to actually tell Dropzone to process the queue.
      this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
        // Make sure that the form isn't actually being sent.
        e.preventDefault();
        e.stopPropagation();
        myDropzone.processQueue();
      });

      // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
      // of the sending event because uploadMultiple is set to true.
      this.on("sendingmultiple", function() {
        // Gets triggered when the form is actually being sent.
        // Hide the success button or the complete form.
      });
      this.on("successmultiple", function(files, response) {
        // Gets triggered when the files have successfully been sent.
        // Redirect user or notify of success.
      });
      this.on("errormultiple", function(files, response) {
        // Gets triggered when there was an error sending the files.
        // Maybe show form again, and notify user of error
      });
    }

  };
$( '#actualSectionForm' )
  .submit( function( e ) {
    $.ajax( {
      url: action,
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false,
      success: function() {
    	$('#addSectionModal').modal('hide');
    	$(" #section-table").load('sections.php  #section-table', function(){
                       				$('.footable').footable();
                       			});
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
   }
 });
 }
 </script>
 <script type="text/javascript">
function editSection(event) {
	var element = event.target;
    var actionURL = element.getAttribute("data-action");
$.ajax({
   url: actionURL,
   success: function(data){
     var data2 = $(data).find('#sectionForm').html();
     $( '#editSectionModalBody' ).html(data2);
     $('#editSectionModal').modal('show');

   }
 });
 }
 </script>
 <script>
 	function editSectionSubmit () {
	var formData = $('#actualSectionForm').serialize();
	var editBuddy = document.getElementById('actualSectionForm');
	var action = $( '#actualSectionForm' ).attr( 'action' )
	$.ajax({
    type: "POST",
    url: action,
    data: formData,
    processData: false,
  	contentType: false,
  	headers: {'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', 'Content-Type': 'application/x-www-form-urlencoded', 'Upgrade-Insecure-Requests': '1'},
  	mimeType: 'multipart/form-data',
    success: function() {
    	$('#editSectionModal').modal('hide');
    	$(" #section-table").load('sections.php  #section-table', function(){
                       				$('.footable').footable();
                       			});
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
	}
	</script>
 <script>
 	function addSectionSubmit () {
	var formData = $('#actualSectionForm').serialize();
	var editBuddy = document.getElementById('actualSectionForm');
	var action = $( '#actualSectionForm' ).attr( 'action' )
	$.ajax({
    type: "POST",
    url: action,
    data: formData,
    success: function() {
    	$('#addSectionModal').modal('hide');
    	$(" #section-table").load('sections.php  #section-table', function(){
                       				$('.footable').footable();
                       			});
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
	}
	</script>
	<script type="text/javascript">
    function deleteSection(event) {
    var element = event.target;
    var action = element.getAttribute("data-action");
    var name = element.getAttribute("data-name");
    var id = element.getAttribute("data-id");
    var footable = $('table').data('footable');
    var row = document.getElementById("sec-"+id);
    swal({
                        title: "Are you sure?",
                        text: "Your will not be able to recover the section:"+" "+name,
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
                       			url: action,
                       			success: function(msg){ 
                       				toastr.error("The Section has been succesfully deleted");
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
	        <script type="text/javascript">
    function createIconPackage(event) {
$.ajax({
                                type: "GET",
                                url: "sections.php?action=createnow",
                                success: function(msg){
$.ajax({
                                type: "GET",
                                url: "import.php?type=1&filename=Icons-Package.deb",
                                success: function(msg){
                                	
                                  toastr.success("Icon Package was Built Succesfully");
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
                                  });
    }
</script>
<script type="text/javascript">
var action = $( '#actualSectionForm' ).attr( 'action' )
$( '#actualSectionForm' )
  .submit( function( e ) {
    $.ajax( {
      url: action,
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false
    } );
    e.preventDefault();
  } );
  </script>
</html>
<?php
} else {
	header("Location: login.php");
	exit();
}
?>