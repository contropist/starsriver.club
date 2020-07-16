<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_nesttemplate.php 29236 2012-03-30 05:34:47Z chenmengshu $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
		exit('Access Denied');
}

cpheader();
$operation = in_array($operation, array('edit', 'perm')) ? $operation : 'list';

shownav('portal', 'nesttemplate');

if($operation == 'list') {
	$searchctrl = '<span style="float: right; padding-right: 40px;">'
					.'<a onclick="$(\'tb_search\').style.display=\'\';$(\'a_search_show\').style.display=\'none\';$(\'a_search_hide\').style.display=\'\';" id="a_search_show" style="display:none">'.cplang('show_search').'</a>'
					.'<a onclick="$(\'tb_search\').style.display=\'none\';$(\'a_search_show\').style.display=\'\';$(\'a_search_hide\').style.display=\'none\';" id="a_search_hide">'.cplang('hide_search').'</a>'
					.'</span>';
	showsubmenu('nesttemplate',  array(
		array('list', 'nesttemplate', 1),
	), $searchctrl);

	$intkeys = array('uid', 'closed');
	$strkeys = array();
	$randkeys = array();
	$likekeys = array('targettplname', 'primaltplname', 'username', 'name');
	$results = getwheres($intkeys, $strkeys, $randkeys, $likekeys);
	foreach($likekeys as $k) {
		$_GET[$k] = dhtmlspecialchars($_GET[$k]);
	}
	$wherearr = $results['wherearr'];
	$mpurl = ADMINSCRIPT.'?action=nesttemplate';
	$mpurl .= '&'.implode('&', $results['urls']);
	$wherearr[] = " primaltplname NOT LIKE 'portal/list%' ";
	$wherearr[] = " primaltplname NOT LIKE 'portal/portal_topic_content%' ";

	if($_GET['permname']) {
		$tpls = '';
		$member = C::t('common_member')->fetch_by_username($_GET['permname']);
		if($member && $member['adminid'] != 1) {
			$tpls = array_keys(C::t('common_template_permission')->fetch_all_by_uid($member['uid']));
			if(($tpls = dimplode($tpls))) {
				$wherearr[] = 'targettplname IN ('.$tpls.')';
			} else {
				cpmsg_error($_GET['permname'].cplang('nesttemplate_the_username_has_not_template'));
			}
		}
		$mpurl .= '&permname='.$_GET['permname'];
	}

	$wheresql = empty($wherearr)?'':implode(' AND ', $wherearr);

	$orders = getorders(array('dateline','targettplname'), 'dateline');
	$ordersql = $orders['sql'];
	if($orders['urls']) $mpurl .= '&'.implode('&', $orders['urls']);
	$orderby = array($_GET['orderby']=>' selected');
	$ordersc = array($_GET['ordersc']=>' selected');

	$perpage = empty($_GET['perpage'])?0:intval($_GET['perpage']);
	if(!in_array($perpage, array(10,20,50,100))) $perpage = 20;
	$perpages = array($perpage=>' selected');

	$searchlang = array();
	$keys = array('search', 'likesupport', 'resultsort', 'defaultsort', 'orderdesc', 'orderasc', 'perpage_10', 'perpage_20', 'perpage_50', 'perpage_100',
	'nesttemplate_name', 'nesttemplate_dateline', 'nesttemplate_targettplname', 'nesttemplate_primaltplname', 'nesttemplate_uid', 'nesttemplate_username',
	'nolimit', 'no', 'yes', 'nesttemplate_permname', 'nesttemplate_permname_tips');
	foreach ($keys as $key) {
		$searchlang[$key] = cplang($key);
	}

	$adminscript = ADMINSCRIPT;
	echo <<<SEARCH
	<form method="get" autocomplete="off" action="$adminscript" id="tb_search">
		<div style="margin-top:8px;">
			<table>
				<tr>
					<th>$searchlang[nesttemplate_name]*</th><td><input type="text" class="txt" name="name" value="$_GET[name]"></td>
					<th>$searchlang[nesttemplate_targettplname]*</th><td><input type="text" class="txt" name="targettplname" value="$_GET[targettplname]"></td>
					<th>$searchlang[nesttemplate_primaltplname]*</th><td><input type="text" class="txt" name="primaltplname" value="$_GET[primaltplname]"> *$searchlang[likesupport]</td>
				</tr>
				<tr>
					<th>$searchlang[nesttemplate_uid]</th><td><input type="text" class="txt" name="uid" value="$_GET[uid]"></td>
					<th>$searchlang[nesttemplate_username]*</th><td><input type="text" class="txt" name="username" value="$_GET[username]" colspan=2></td>
				</tr>
				<tr>
					<th>$searchlang[resultsort]</th>
					<td colspan="3">
						<select name="orderby">
						<option value="">$searchlang[defaultsort]</option>
						<option value="dateline"$orderby[dateline]>$searchlang[nesttemplate_dateline]</option>
						<option value="targettplname"$orderby[targettplname]>$searchlang[nesttemplate_targettplname]</option>
						</select>
						<select name="ordersc">
						<option value="desc"$ordersc[desc]>$searchlang[orderdesc]</option>
						<option value="asc"$ordersc[asc]>$searchlang[orderasc]</option>
						</select>
						<select name="perpage">
						<option value="10"$perpages[10]>$searchlang[perpage_10]</option>
						<option value="20"$perpages[20]>$searchlang[perpage_20]</option>
						<option value="50"$perpages[50]>$searchlang[perpage_50]</option>
						<option value="100"$perpages[100]>$searchlang[perpage_100]</option>
						</select>
						<input type="hidden" name="action" value="nesttemplate">
					</td>
					<th>$searchlang[nesttemplate_permname]</th>
					<td><input type="text" class="txt" name="permname" value="$_GET[permname]"> $searchlang[nesttemplate_permname_tips]
						<input type="submit" name="searchsubmit" value="$searchlang[search]" class="btn"></td>
				</tr>
			</table>
		</div>
	</form>
SEARCH;

	$start = ($page-1)*$perpage;

	$mpurl .= '&perpage='.$perpage;
	$perpages = array($perpage => ' selected');

	showformheader('nesttemplate');
	showtableheader('nesttemplate_list');
	showsubtitle(array('nesttemplate_name', 'nesttemplate_targettplname', 'nesttemplate_primaltplname', 'username', 'nesttemplate_dateline', 'operation'));

	$multipage = '';
	if(($count = C::t('common_nest_data')->count_by_where($wheresql))) {
		loadcache('nesttemplatename');
		require_once libfile('function/block');
		foreach(C::t(common_nest_data)->fetch_all_by_where($wheresql, $ordersql, $start, $perpage) as $value) {
			$value['name'] = $_G['cache']['nesttemplatename'][$value['targettplname']];
			$value['dateline'] = $value['dateline'] ? dgmdate($value['dateline']) : '';
			$nesturl = block_getnesturl($value['targettplname']);
			$nesttitle = cplang($nesturl['flag'] ? 'nesttemplate_share' : 'nesttemplate_alone');
			showtablerow('', array('class=""', 'class=""', 'class="td28"'), array(
					"<a href=\"$nesturl[url]\" title=\"$nesttitle\" target=\"_blank\">$value[name]</a>",
					'<span title="'.cplang('nesttemplate_path').'./data/nest/'.$value['targettplname'].'.htm">'.$value['targettplname'].'</span>',
					'<span title="'.cplang('nesttemplate_path').$_G['style']['tpldir'].'/'.$value['primaltplname'].'.htm">'.$value['primaltplname'].'</span>',
					"<a href=\"home.php?mod=space&uid=$value[uid]&do=profile\" target=\"_blank\">$value[username]</a>",
					$value[dateline],
					'<a href="'.ADMINSCRIPT.'?action=nesttemplate&operation=edit&targettplname='.$value['targettplname'].'&tpldirectory='.$value['tpldirectory'].'">'.cplang('edit').'</a> '.
					'<a href="'.ADMINSCRIPT.'?action=nesttemplate&operation=perm&targettplname='.$value['targettplname'].'&tpldirectory='.$value['tpldirectory'].'">'.cplang('nesttemplate_perm').'</a>',
				));
		}
		$multipage = multi($count, $perpage, $page, $mpurl);
	}

	showsubmit('', '', '', '', $multipage);
	showtablefooter();
	showformfooter();
} elseif($operation == 'edit') {
	loadcache('nesttemplatename');
	$targettplname = $_GET['targettplname'];
	$tpldirectory = $_GET['tpldirectory'];
	$nestdata = C::t('common_nest_data')->fetch($targettplname, $tpldirectory);
	if(empty($nestdata)) { cpmsg_error('nesttemplate_targettplname_error', dreferer());}
	if(!submitcheck('editsubmit')) {
		if(empty($nestdata['name'])) $nestdata['name'] = $_G['cache']['nesttemplatename'][$nestdata['targettplname']];
		shownav('portal', 'nesttemplate', $nestdata['name']);
		showsubmenu(cplang('nesttemplate_edit').' - '.$nestdata['name'], array(
					array('list', 'nesttemplate', 0),
					array('edit', 'nesttemplate&operation=edit&targettplname='.$_GET['targettplname']."&tpldirectory=$tpldirectory", 1)
				));

		showformheader("nesttemplate&operation=edit&targettplname=$targettplname&tpldirectory=$tpldirectory");
		showtableheader();
		showtitle('edit');

		showsetting('nesttemplate_name', 'name', $nestdata['name'],'text');
		showsetting('nesttemplate_targettplname', '', '',cplang('nesttemplate_path').'./data/nest/'.$nestdata['targettplname'].'.htm');
		showsetting('nesttemplate_primaltplname', '', '',cplang('nesttemplate_path').$_G['style']['tpldir'].'/'.$nestdata['primaltplname'].'.htm');
		showsetting('nesttemplate_username', '', '',$nestdata['username']);
		showsetting('nesttemplate_dateline', '', '',$nestdata['dateline'] ? dgmdate($nestdata['dateline']) : '');

		showsubmit('editsubmit');
		showtablefooter();
		showformfooter();

	} else {

		$editnestdata = array('name'=>$_GET['name']);
		C::t('common_nest_data')->update($targettplname, $tpldirectory, $editnestdata);

		include_once libfile('function/cache');
		updatecache('nesttemplatename');

		cpmsg('nesttemplate_edit_succeed', 'action=nesttemplate', 'succeed');
	}
} elseif($operation=='perm') {
	loadcache('nesttemplatename');
	$targettplname = $_GET['targettplname'];
	$tpldirectory = $_GET['tpldirectory'];
	$nestdata = C::t('common_nest_data')->fetch($targettplname, $tpldirectory);
	if(empty($nestdata)) { cpmsg_error('nesttemplate_targettplname_error', dreferer());}
	if(!submitcheck('permsubmit')) {
		shownav('portal', 'nesttemplate', 'nesttemplate_perm');
		showsubmenu(cplang('nesttemplate_perm_edit').' - '.($nestdata['name'] ? cplang($nestdata['name']) : $_G['cache']['nesttemplatename'][$nestdata['targettplname']]));
		showtips('nesttemplate_perm_tips');
		showformheader("nesttemplate&operation=perm&targettplname=$targettplname&tpldirectory=$tpldirectory");
		showtableheader('', 'fixpadding');
		showsubtitle(array('', 'username',
		'<input class="checkbox" type="checkbox" name="chkallmanage" onclick="checkAll(\'prefix\', this.form, \'allowmanage\', \'chkallmanage\')" id="chkallmanage" /><label for="chkallmanage">'.cplang('block_perm_manage').'</label>',
		'<input class="checkbox" type="checkbox" name="chkallrecommend" onclick="checkAll(\'prefix\', this.form, \'allowrecommend\', \'chkallrecommend\')" id="chkallrecommend" /><label for="chkallrecommend">'.cplang('block_perm_recommend').'</label>',
		'<input class="checkbox" type="checkbox" name="chkallneedverify" onclick="checkAll(\'prefix\', this.form, \'needverify\', \'chkallneedverify\')" id="chkallneedverify" /><label for="chkallneedverify">'.cplang('block_perm_needverify').'</label>',
		'block_perm_inherited'
		));

		$allpermission = C::t('common_template_permission')->fetch_all_by_targettplname($targettplname);
		$allusername = C::t('common_member')->fetch_all_username_by_uid(array_keys($allpermission));
		$line = '&minus;';
		foreach($allpermission as $uid => $value) {
			if(!empty($value['inheritedtplname'])) {
				showtablerow('', array('class="td25"'), array(
					"",
					"$allusername[$uid]",
					$value['allowmanage'] ? '&radic;' : $line,
					$value['allowrecommend'] ? '&radic;' : $line,
					$value['needverify'] ? '&radic;' : $line,
					'<a href="'.ADMINSCRIPT.'?action=nesttemplate&operation=perm&targettplname='.$value['inheritedtplname'].'">'.$_G['cache']['nesttemplatename'][$value['inheritedtplname']].'</a>',
				));
			} else {
				showtablerow('', array('class="td25"'), array(
					"<input type=\"checkbox\" name=\"delete[$value[uid]]\" value=\"$value[uid]\" />
					<input type=\"hidden\" name=\"perm[$value[uid]][allowmanage]\" value=\"$value[allowmanage]\" />
					<input type=\"hidden\" name=\"perm[$value[uid]][allowrecommend]\" value=\"$value[allowrecommend]\" />
					<input type=\"hidden\" name=\"perm[$value[uid]][needverify]\" value=\"$value[needverify]\" />",
					"$allusername[$uid]",
					"<input type=\"checkbox\" name=\"allowmanage[$value[uid]]\" value=\"1\" ".($value['allowmanage'] ? 'checked' : '').' />',
					"<input type=\"checkbox\" name=\"allowrecommend[$value[uid]]\" value=\"1\" ".($value['allowrecommend'] ? 'checked' : '').' />',
					"<input type=\"checkbox\" name=\"needverify[$value[uid]]\" value=\"1\" ".($value['needverify'] ? 'checked' : '').' />',
					$line,
				));
			}
		}

		showtablerow('', array('class="td25"'), array(
			cplang('add_new'),
			'<input type="text" class="txt" name="newuser" value="" size="20" />',
			'<input type="checkbox" class="checkbox" name="newallowmanage" value="1" />',
			'<input type="checkbox" class="checkbox" name="newallowrecommend" value="1" />',
			'<input type="checkbox" class="checkbox" name="newneedverify" value="1" />',
			'',
		));

		showsubmit('permsubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	} else {

		$users = array();
		if(!empty($_GET['newuser'])) {
			$uid = C::t('common_member')->fetch_uid_by_username($_GET['newuser']);
			if($uid) {
				$user = array();
				$user['uid'] = $uid;
				$user['allowmanage'] = $_GET['newallowmanage'] ? 1 : 0;
				$user['allowrecommend'] = $_GET['newallowrecommend'] ? 1 : 0;
				$user['needverify'] = $_GET['newneedverify'] ? 1 : 0;
				$users[] = $user;
			} else {
				cpmsg_error($_GET['newuser'].cplang('block_has_no_allowauthorizedblock'), dreferer());
			}
		}
		if(is_array($_GET['perm'])) {
			foreach($_GET['perm'] as $uid => $value) {
				if(empty($_GET['delete']) || !in_array($uid, $_GET['delete'])) {
					$user = array();
					$user['allowmanage'] = $_GET['allowmanage'][$uid] ? 1 : 0;
					$user['allowrecommend'] = $_GET['allowrecommend'][$uid] ? 1 : 0;
					$user['needverify'] = $_GET['needverify'][$uid] ? 1 : 0;
					if($value['allowmanage'] != $user['allowmanage'] || $value['allowrecommend'] != $user['allowrecommend']	|| $value['needverify'] != $user['needverify'] ) {
						$user['uid'] = intval($uid);
						$users[] = $user;
					}
				}
			}
		}
		if(!empty($users) || $_GET['delete']) {
			require_once libfile('class/blockpermission');
			$tplpermsission = & template_permission::instance();
			if($_GET['delete']) {
				$tplpermsission->delete_users($targettplname ,$_GET['delete']);
			}

			if(!empty($users)) {
				$tplpermsission->add_users($targettplname, $users);
			}
		}
		cpmsg('nesttemplate_perm_update_succeed', "action=nesttemplate&operation=perm&targettplname=$targettplname&tpldirectory=$tpldirectory", 'succeed');
	}

}

?>