<?php
/**
 * DCRM UDID Manage Page
 * Copyright (c) 2015 Hintay <hintay@me.com>
 *
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

session_start();
define("DCRM",true);
$activeid = 'udid';

if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
	require_once("header.php");
	class_loader('CorePage');

	// 生成表格
	function show_table($list_query){
		$level_option = get_option('udid_level');
?>
<style>
.udid-packages {
    max-width: 11em;
    min-width: 11em;
    width: 11em;
    white-space: nowrap;
  	overflow: hidden;
  	text-overflow: ellipsis;
}
.udid-comments {
    max-width: 10em;
    min-width: 10em;
    width: 10em;
    white-space: nowrap;
  	overflow: hidden;
  	text-overflow: ellipsis;
}
.udid-name {
    min-width: 5em;
    width: 5em;	
}
.udid-create-time {
	min-width: 11em;
    width: 11em;
}
</style>
<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>Manage UDID</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a href="index.html">Packages</a>
                        </li>
                        <li class="active">
                            <strong><?php _e('Manage UDID'); ?></strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                        <button id="step5" type="button" onclick="addUDID(event);" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;&nbsp;<span class="bold"><?php _e('Add UDID'); ?></span></button>
                    </div>
                </div>
            </div>
            <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php _e('All UDIDs'); ?></h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="udid.php?action=level"><?php _e('Manage Level'); ?></a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <link href="css/plugins/footable/footable.core.css" rel="stylesheet">
                        <table id="udid-table" class="footable table" data-page-size="9000" data-filter="#top-search"><thead><tr>
									<th><?php _e('UDID'); ?></th>
									<th style="text-align: center;"><?php _e('Level'); ?></ul></th>
									<th data-sort-ignore="true"><?php _e('Comment'); ?></th>
									<th style="width:7%;" data-sort-ignore="true" ><?php _e('Packages'); ?></th>
									<th><?php _e('Downloads'); ?></th>
									<th><?php _e('Last IP'); ?></th>
									<th><?php _e('Create Time'); ?></th>
									<th style="text-align: center;" data-sort-ignore="true"><?php _e('Delete'); ?></th>
									<th style="text-align: center;" data-sort-ignore="true"><?php _e('Edit'); ?></th>
								</tr></thead><tbody>
<?php
		foreach($list_query as $list) {
?>
								<tr id="udid-<?php echo $list['ID']; ?>">
									<td class="udid-name"><a href = "udid.php?action=edit&amp;id=<?php echo $list['ID']; ?>"><?php echo htmlspecialchars($list['UDID']); ?></a></td>
									<td style="text-align: center;"><?php echo $list['Level']; echo isset($level_option[$list['Level']]) ? $level_option[$list['Level']] : '' ?></td>
									<td class="udid-comments"><?php echo htmlspecialchars($list['Comment']); ?></td>
									<td class="udid-packages"><?php echo htmlspecialchars($list['Packages']); ?></td>
									<td><?php echo $list['Downloads']; ?></td>
									<td><?php echo long2ip($list['IP']); ?></td>
									<td class="udid-create-time"><?php echo htmlspecialchars($list['CreateStamp']); ?></ul></td>
									<td style="text-align: center;"><a data-udid-id="<?php echo $list['ID']; ?>" data-udid-name="<?php echo htmlspecialchars($list['UDID']); ?>" onclick="deleteUDID(event);" class="fa fa-trash" style="text-align: center; color:#ed5565;"></a></td>
									<td style="text-align: center;"><a data-udid-id="<?php echo $list['ID']; ?>" onclick="editUDID(event);" class="fa fa-edit" style="color:#1ab394;"></a></td>
<?php
			if(isset($_GET['package'])) {
				$package_udid = TrimArray(explode(',', $list['Packages']));
				if(in_array($_GET['package'], $package_udid, true)) {
?>
									<td><a href="udid.php?action=binding&amp;contents=<?php echo $_GET['package']; ?>&amp;udid=<?php echo $list['UDID']; ?>&amp;delete=true" class="close" title="<?php _e('Delete'); ?>">&times;</a></td>
<?php			} else { ?>
									
<?php
				}
			} else {
?>
		
<?php 		} ?>
								</tr>
<?php
		}
		if (count($list_query, false) < 10) {
			$page_c = $page;
		} else {
			$page_c = $page + 1;
		}
?>
								</tbody></table>
                    </div>
                </div>
<?php
		return $page_c;
	}

	if (!isset($_GET['action'])) {
		if (isset($_GET['search'])) {
		// 搜索提交页
?>
				<h2><?php _e('Manage UDID'); ?></h2>
					<br />
					<form class="form-horizontal" method="GET" action="udid.php" >
					<div class="group-control">
						<label><?php _e('Search Content'); ?></label>
						<div class="controls">
							<input class="form-control"  type="hidden" name="action" value="search" />
							<input class="form-control"  class="input-xlarge" name="contents" required="required" />
						</div>
					</div>
					<br />
					<div class="group-control">
						<label><?php _e('Search Type'); ?></label>
						<div class="controls">
							<select name="type" >
							<option value="1" selected="selected"><?php _e('UDID'); ?></option>
							<option value="2"><?php _e('Level'); ?></option>
							<option value="3"><?php _e('Package'); ?></option>
							<option value="4"><?php _e('Last IP'); ?></option>
							<option value="5"><?php _e('Comment'); ?></option>
							</select>
						</div>
					</div>
					<br />
					<div class="form-actions">
						<div class="controls">
							<button type="submit" class="btn btn-success"><?php _e('Search'); ?></button>
						</div>
					</div>
					</form>
				<br />
<?php
		} else {
		// 显示全部
?>
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
			$page_a = $page * 10 - 10;
			if ($page == 1) {
				$page_b = $page;
			} else {
				$page_b = $page - 1;
			}
			$list_query = DB::fetch_all("SELECT * FROM `".DCRM_CON_PREFIX."UDID` ORDER BY `ID` DESC, `CreateStamp` DESC, `TimeStamp` DESC LIMIT " . (string)$page_a. ",10");

			$page_c = show_table($list_query);

			$q_info = DB::query("SELECT count(*) FROM `".DCRM_CON_PREFIX."UDID`");
			$info = db_mysql::fetch_row($q_info);
			$totalnum = (int)$info[0];
			$params = array('total_rows'=>$totalnum, 'method'=>'html', 'parameter' =>'udid.php?page=%page', 'now_page'  =>$page, 'list_rows' =>10);
			$page = new Core_Lib_Page($params);
			echo '<div class="page">' . $page->show(2) . '</div>';
		}
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "search" AND !empty($_GET['contents']) AND !empty($_GET['type'])) {
	// 搜索内容显示
		unset($_SESSION['page']);
		$_SESSION['contents'] = $_GET['contents'];
		$_SESSION['type'] = $_GET['type'];
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
		if ($page <= 0 OR $page >= 100) {
			$page = 1;
		}
		$page_a = $page * 10 - 10;
		if ($page == 1) {
			$page_b = $page;
		} else {
			$page_b = $page - 1;
		}
?>
				<h2><?php _e('Manage UDID'); ?></h2>
				<br />
				<h3 class="navbar"><?php printf(__('Search UDID: %s'), $_GET['contents']); ?></h3>
<?php
		$search_type = (int)$_GET['type'];
		switch ($search_type) {
			case 1:
				$t = 'UDID';
				break;
			case 2:
				$t = 'Level';
				break;
			case 3:
				$t = 'Package';
				break;
			case 4:
				$t = 'IP';
				break;
			case 5:
				$t = 'Comment';
				break;
			default:
				goto endlabel;
		}
		$r_value = DB::real_escape_string(str_replace('*', '%', str_replace('?', '_', $_GET['contents'])));
		$list_query = DB::fetch_all("SELECT * FROM `".DCRM_CON_PREFIX."UDID` WHERE `" . $t . "` LIKE '%" . $r_value . "%' ORDER BY `ID` DESC LIMIT ".(string)$page_a.",10");

		$page_c = show_table($list_query);

		$q_info = DB::query("SELECT count(*) FROM `".DCRM_CON_PREFIX."UDID` WHERE `" . $t . "` LIKE '%" . $r_value . "%'");
		$info = DB::fetch_row($q_info);
		$totalnum = (int)$info[0];
		$params = array('total_rows'=>$totalnum, 'method'=>'html', 'parameter' =>'center.php?action=search&contents='.$_GET['contents'].'&type='.$_GET['type'].'&page=%page', 'now_page'  =>$page, 'list_rows' =>10);
		$page = new Core_Lib_Page($params);
		echo '<div class="page">' . $page->show(2) . '</div>';
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "edit") {
	// 添加与编辑UDID
		if(!isset($_GET['add'])){
		// 获取编辑信息
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
			$edit_info = DB::fetch_first("SELECT * FROM `".DCRM_CON_PREFIX."UDID` WHERE `ID` = '" . $request_id . "'");
			if (null == $edit_info) {
				_e('Illegal request!');
				goto endlabel;
			}
		}
?>
						<h2><?php _e('Manage UDID'); ?></h2>
						<br />
						<h3 class="navbar"><span><a href="udid.php"><?php _e('All UDID'); ?></a></span>　<span><?php if(!isset($_GET['add'])){ ?><a href="udid.php?action=edit&amp;add=true"><?php } _e('Add UDID'); if(!isset($_GET['add'])){ ?></a><?php } ?></span>　<span><a href="udid.php?search=yes"><?php _e('Search UDID'); ?></a>　<span><a href="udid.php?action=level"><?php _e('Manage Level'); ?></a></span></h3>
						<br />
						<div id="udidform">
						<form id="actualUDIDForm" style="display: inline-block;" name="udidform" class="form-horizontal" method="POST" enctype="multipart/form-data" action="udid.php?action=<?php echo isset($_GET['add']) ? 'add_now' : 'set&id='.$request_id;?>">
							<div class="col-lg-6">
            					<div class="col-lg-12">
							<div class="form-group">
								<label><?php _e('UDID'); ?></label>
									<input class="form-control"  type="text" style="width: 400px;" required="required" name="UDID" id="udid" minlength="40" maxlength="40" data-validation-regex-regex="[a-zA-Z0-9]*" data-validation-minlength-message="<?php _e('UDID must be 40 characters!'); ?>" data-validation-maxlength-message="<?php _e('UDID must be 40 characters!'); ?>" data-validation-regex-message="<?php _e('UDID must be number or letter!!'); ?>" value="<?php if (!empty($edit_info['UDID'])) echo $edit_info['UDID']; ?>" />
							</div>
							</div>
							</div>
							<div class="col-lg-6">
            					<div class="col-lg-12">
							<div class="form-group">
								<label><?php _e('Level'); ?></label>
									<input class="form-control"  type="number" style="width: 400px;" required="required" name="Level" value="<?php if (!empty($edit_info['Level'])) echo $edit_info['Level']; else echo '0'; ?>"/>
							</div>
							</div>
							</div>
							<div class="col-lg-12">
							<div class="col-lg-12">
							<div class="form-group">
								<label><?php _e('Comment'); ?></label>
									<textarea name="Comment" class="form-control" rows="1" type="text"><?php if (!empty($edit_info['Comment'])) echo htmlspecialchars($edit_info['Comment']); ?></textarea>
							</div>
							</div>
							<div class="col-lg-12">
							<div class="form-group">
								<label><?php _e('Packages'); ?></label>
									<textarea name="Packages"  class="form-control" rows="1" type="text"><?php if (!empty($edit_info['Packages'])) echo htmlspecialchars($edit_info['Packages']); ?></textarea>
									<span class="help-block m-b-none"><?php _e('If you are entering multiple packages, Separate them by commas.'); ?></span>
							</div>
							</div>
							</div>
						</form>
						</div>
<script src="./plugins/jqBootstrapValidation/jqBootstrapValidation.min.js"></script>
<script language="javascript">
$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
</script>
<?php
	} elseif (!empty($_GET['action']) && ($_GET['action'] == "add_now" || ($_GET['action'] == "set" && !empty($_GET['id'])))) {
		$_POST['UDID'] = strtolower($_POST['UDID']);
		if($_GET['action'] == "set"){
			$new_id = (int)$_GET['id'];
		} elseif($_GET['action'] == "add_now") {
			$new_id = DB::insert(DCRM_CON_PREFIX.'UDID', array('UDID' => $_POST['UDID']));
			$_POST['CreateStamp'] = date('Y-m-d H:i:s');
			unset($_POST['UDID']);
		} else {
			goto endlabel;
		}
		DB::update(DCRM_CON_PREFIX.'UDID', $_POST, array('ID' => $new_id));
		header("Location: udid.php");
		exit();
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "delete_confirm" AND !empty($_GET['name']) AND !empty($_GET['id'])) {
	// 删除确认
?>
						<h3 class="alert"><?php printf(__('Are you sure you want to delete: %s?'), htmlspecialchars($_GET['name']));?><br /><?php _e('This operation is irreversible, all related data will be delete!'); ?></h3>
						<a class="btn btn-danger" href="udid.php?action=delete&amp;id=<?php echo $_GET['id']; ?>"><?php _e('Confirm'); ?></a>
<?php
		echo '<a class="btn btn-success" href="udid.php?';
		if (!empty($_SESSION['page'])) {
			echo "page=" . $_SESSION['page'];
		} elseif (!empty($_SESSION['contents']) AND !empty($_SESSION['type'])) {
			echo "action=search&contents=" . $_SESSION['contents'] . "&type=" . $_SESSION['type'];
		}
		echo '">'.__('Cancel').'</a>';
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "delete" AND !empty($_GET['id'])) {
	// 删除
		$delete_id = (int)$_GET['id'];
		DB::delete(DCRM_CON_PREFIX.'UDID', array('ID' => $delete_id));
		if (!empty($_SESSION['page'])) {
			header("Location: udid.php?page=" . $_SESSION['page']);
			exit();
		} elseif (!empty($_SESSION['contents']) AND !empty($_SESSION['type'])) {
			header("Location: udid.php?action=search&contents=" . $_SESSION['contents'] . "&type=" . $_SESSION['type']);
			exit();
		} else {
			header("Location: udid.php");
			exit();
		}
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "level") {
	// 等级管理
	$level_option = get_option('udid_level');
	//$lowest_option = get_option('udid_level_lowest');
?>
						<h2><?php _e('Manage UDID'); ?></h2>
						<br />
						<h3 class="navbar"><span><a href="udid.php"><?php _e('All UDID'); ?></a></span>　<span><a href="udid.php?action=edit&amp;add=true"><?php _e('Add UDID'); ?></a></span>　<span><a href="udid.php?search=yes"><?php _e('Search UDID'); ?></a>　<span><?php _e('Manage Level'); ?></span></h3>
						<form class="form-horizontal" method="POST" enctype="multipart/form-data" action="udid.php?action=level_set">
							<!--<div class="group-control">
								<label><?php _e('Lowest Protection Level'); ?></label>
								<div class="controls">
									<input class="form-control"  type="text" style="width: 400px;" required="required" name="lowest" value="<?php //if (!empty($lowest_option)) echo $lowest_option; ?>"/>
								</div>
							</div>
							<br />-->
							<table class="table" id="level_table"><thead><tr>
								<th style="width:15%;"><?php _e('Level'); ?></ul></th>
								<th><?php _e('Comment'); ?></ul></th>
							</tr></thead><tbody>
							<?php if(!empty($level_option)) { foreach($level_option as $key => $value) { ?>
								<tr><td><?php echo $key; ?></ul></td>
								<td><input class="form-control"  type="text" style="width: 400px;" name="level[<?php echo $key; ?>]" value="<?php if (!empty($value)) echo $value; ?>"/></ul></td></tr>
							<?php } } ?>
							</tbody></table>
							<div class="form-actions">
								<div class="controls">
									<button type="submit" class="btn btn-success"><?php _e('Save'); ?></button>
									<a class="btn btn-success" onclick="javascript:AddRow();"><?php _e('Add Row'); ?></a>
									<a class="btn btn-success" onclick="javascript:DelRow();"><?php _e('Delete Row'); ?></a>
								</div>
							</div>
						</form>
<script language="javascript" type="text/javascript">
	function AddRow() {
		var i = level_table.rows.length;
		var Cod = i-1;
		var newTr = level_table.insertRow();
		var newTd0 = newTr.insertCell();
		var newTd1 = newTr.insertCell();
		newTd0.innerHTML = ''+Cod+'</ul>';
		if(Cod == 0){
			newTd1.innerHTML = '<input class="form-control"  type="text" style="width: 400px;" name="level['+Cod+']" value="<?php _e('Guest'); ?>"/></ul>';
		} else {
			newTd1.innerHTML = '<input class="form-control"  type="text" style="width: 400px;" name="level['+Cod+']"/></ul>';
		}
	}
	function DelRow() {
		var i = level_table.rows.length;
		level_table.deleteRow(i-1);
	}
</script>
<?php
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "level_set") {
		update_option('udid_level', $_POST['level']);
		//update_option('udid_level_lowest', $_POST['lowest']);
		header("Location: udid.php?action=level");
		exit();
	} elseif (!empty($_GET['action']) AND $_GET['action'] == "binding") {
		$_SESSION['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
		if(!isset($_GET['delete'])){
?>
						<h2><?php _e('Manage UDID'); ?></h2>
						<br />
						<h3><?php _e('Binding UDID'); ?></h3>
						<h3 class="alert alert-info"><?php printf(__('Are you sure you want to binding \'%1$s\' to UDID \'%2$s\'?'), htmlspecialchars($_GET['contents']), htmlspecialchars($_GET['udid']));?></h3>
						<a class="btn btn-success" href="udid.php?action=binding_now&amp;udid=<?php echo $_GET['udid']; ?>&amp;package=<?php echo $_GET['contents']; ?>"><?php _e('Confirm'); ?></a>
						<a class="btn btn-success" href="javascript:history.go(-1)"><?php _e('Cancel'); ?></a>
<?php
		} else {
?>
						<h2><?php _e('Manage UDID'); ?></h2>
						<br />
						<h3><?php _e('Unbinding UDID'); ?></h3>
						<h3 class="alert"><?php printf(__('Are you sure you want to unbinding \'%1$s\' from UDID \'%2$s\'?'), htmlspecialchars($_GET['contents']), htmlspecialchars($_GET['udid'])); ?><br /><?php _e('You will not be able to undo this operation!'); ?></h3>
						<a class="btn btn-danger" href="udid.php?action=unbinding&amp;udid=<?php echo $_GET['udid']; ?>&amp;package=<?php echo $_GET['contents']; ?>"><?php _e('Confirm'); ?></a>　
						<a class="btn btn-success" href="javascript:history.go(-1)"><?php _e('Cancel'); ?></a>
<?php
		}
	} elseif (!empty($_GET['action']) AND ($_GET['action'] == "binding_now" OR $_GET['action'] == 'unbinding')) {
		if($_GET['action'] == 'unbinding') $switch = false;
		else $switch = true;
		$original_packages = DB::result_first("SELECT `Packages` FROM `".DCRM_CON_PREFIX."UDID` WHERE `UDID` = '" . $_GET['udid'] . "'");
		$packages = string_handle($original_packages, $switch, $_GET['package'], ',');
		DB::update(DCRM_CON_PREFIX.'UDID', array('Packages' => $packages), array('UDID' => $_GET['udid']));
		if($_SESSION['HTTP_REFERER'] == $_SERVER['HTTP_REFERER'])
			header("Location: udid.php");
		else
			header("Location: ".$_SESSION['HTTP_REFERER']);
		exit();
	}
	endlabel:
?>
			</div>
		</div>
	</div>
</div>
<div class="modal inmodal" id="addUDIDModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" style="width: 63%;">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-mobile-phone modal-icon"></i>
                                            <h4 class="modal-title">Add UDID</h4>
                                            <small class="font-bold">Add a UDID using the form below</small>
                                        </div>
                                        <div id="addUDIDModalBody" class="modal-body" style="padding: 30px 0px 0px 0px;">
                                        <div>
                                        </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button  type="button" onclick="addUDIDSubmit()" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal inmodal" id="editUDIDModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" style="width: 63%;">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i style="width:100%; text-align:center;" class="fa fa-edit modal-icon"></i>
                                            <h4 class="modal-title">Edit UDID</h4>
                                            <small class="font-bold">Edit the UDID using the form below</small>
                                        </div>
                                        <div id="editUDIDModalBody" class="modal-body" style="padding: 30px 0px 0px 0px;">
                                        <div>
                                        </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button  type="button" onclick="editUDIDSubmit()" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
<script>
        $(document).ready(function() {

            $('.footable').footable();

        });

    </script>
    <script type="text/javascript">
    function deleteUDID(event) {
    var element = event.target;
    var name = element.getAttribute("data-udid-name");
    var id = element.getAttribute("data-udid-id");
    var footable = $('table').data('footable');
    var row = document.getElementById("udid-"+id);
    swal({
                        title: "Are you sure?",
                        text: "Your will not be able to recover the UDID:"+" "+name,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!",
                        closeOnConfirm: true,
                        closeOnCancel: false },
                    function (isConfirm) {
                        if (isConfirm) {
                        	 $.ajax({
                       			type: "GET",
                       			url: "udid.php",
                       			data: "action=delete&id="+id,
                       			success: function(msg){ 
                       				toastr.error(name + " " + " was deleted", "UDID Deleted");
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
                            swal("Cancelled", "The UDID is safe :)", "error");
                        }
                    });
}
    </script>
    <script>
 	function addUDIDSubmit () {
	var formData = $('#actualUDIDForm').serialize();
	var editBuddy = document.getElementById('actualUDIDForm');
	var action = $( '#actualUDIDForm' ).attr( 'action' )
	$.ajax({
    type: "POST",
    url: action,
    data: formData,
    processData: false,
  	contentType: false,
  	headers: {'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', 'Content-Type': 'application/x-www-form-urlencoded', 'Upgrade-Insecure-Requests': '1'},
  	mimeType: 'multipart/form-data',
    success: function() {
    	$('#addUDIDModal').modal('hide');
    	$(" #udid-table").load('udid.php  #udid-table', function(){
                       				$('.footable').footable();
                       			});
      toastr.success("UDID was Added");
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
function editUDID(event) {
	 var element = event.target;
     var id = element.getAttribute("data-udid-id");
$.ajax({
   url: "udid.php?action=edit&id="+id,
   success: function(data){
     var data2 = $(data).find('#udidform').html();
     $( '#editUDIDModalBody' ).html(data2);
     $('#editUDIDModal').modal('show');
     var editBuddy = document.getElementById('actualUDIDForm');
     editBuddy.setAttribute('data-edit-UDID-id', id);
   }
 });
 }
 </script>
 <script>
 	function editUDIDSubmit () {
	var formData = $('#actualUDIDForm').serialize();
	var action = $( '#actualUDIDForm' ).attr( 'action' );
	var id = $('#actualUDIDForm').attr('data-edit-UDID-id');
	$.ajax({
    type: "POST",
    url: "udid.php?action=set&id="+id,
    data: formData,
    processData: false,
  	contentType: false,
  	headers: {'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', 'Content-Type': 'application/x-www-form-urlencoded', 'Upgrade-Insecure-Requests': '1'},
  	mimeType: 'multipart/form-data',
    success: function() {
    	$('#editUDIDModal').modal('hide');
    	$(" #udid-table").load('udid.php  #udid-table', function(){
                       				$('.footable').footable();
                       			});
      toastr.success("UDID was Edited");
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
function addUDID(event) {
$.ajax({
   url: "udid.php?action=edit&add=true",
   success: function(data){
     var data2 = $(data).find('#udidform').html();
     $( '#addUDIDModalBody' ).html(data2);
     $('#addUDIDModal').modal('show');
   }
 });
 }
 </script>
</body>
</html>
<?php
} else {
	header("Location: login.php");
	exit();
}
?>