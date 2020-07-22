<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: portalcp_nest.php 33949 2013-09-05 02:16:25Z laoguozhang $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

global $_G;

$op = in_array($_GET['op'], ['style', 'nest', 'image', 'export', 'import', 'blockclass']) ? $_GET['op'] : '';


if (submitcheck('uploadsubmit')) {
	$topicid = intval($_POST['topicid']);
	if($topicid) {
		$topic = C::t('portal_topic')->fetch($topicid);
		if(empty($topic)) {
			topic_upload_error('nest_topic_noexist');
		}
	}
	topic_checkperm($topic);

	$upload = new discuz_upload();

	$upload->init($_FILES['attach'], 'portal');
	$attach = $upload->attach;

	if(!$upload->error()) {
		$upload->save();
	}
	if($upload->error()) {
		topic_upload_error($attach, $upload->error());
	} else {
		if($attach['isimage']) {
			require_once libfile('class/image');
			$image = new image();
			$attach['thumb'] = $image->Thumb($attach['target'], '', '80', '50');
		}

		if(getglobal('setting/ftp/on')) {
			if(ftpcmd('upload', 'portal/'.$attach['attachment'])) {
				if($attach['thumb']) {
					ftpcmd('upload', 'portal/'.getimgthumbname($attach['attachment']));
				}
				$attach['remote'] = 1;
			} else {
				if(getglobal('setting/ftp/mirror')) {
					@unlink($attach['target']);
					@unlink(getimgthumbname($attach['target']));
					topic_upload_error($attach, 'nest_remote_upload_failed');
				}
			}
		}

		$setarr = array(
			'uid' => $_G['uid'],
			'username' => $_G['username'],
			'filename' => $attach['name'],
			'filepath' => $attach['attachment'],
			'size' => $attach['size'],
			'thumb' => $attach['thumb'],
			'remote' => $attach['remote'],
			'dateline' => $_G['timestamp'],
			'topicid' => $topicid
		);
		$setarr['picid'] = C::t('portal_topic_pic')->insert($setarr, true);

		topic_upload_show($topicid);
	}

} elseif (submitcheck('nestsubmit')) {

	require_once libfile('function/portalcp');

	$tpl_suffix = $_G['config']['output']['tpl_suffix'];

	$tpldirectory = getstr($_POST['tpldirectory'], 80);
	$template = getstr($_POST['template'], 50);
	if(dsign($tpldirectory.$template) !== $_POST['nestsign']) {
		showmessage('nest_sign_invalid');
	}
	$tpldirectory = ($tpldirectory) ? $tpldirectory : $_G['cache']['style_default']['tpldir'];
	$savemod = getstr($_POST['savemod'], 1);
	$recover = getstr($_POST['recover'], 1);
	$optype = getstr($_POST['optype'],10);

	tpl_checkperm($template);

	list($template, $clonefile) = explode(':', $template);
	list($mod,$file) = explode('/', $template);
	$targettplname = $template;

	if ($savemod == '1' && !empty($clonefile)) {
		$targettplname = $template.'_'.$clonefile;
	}

	$istopic = $iscategory = $isarticle = false;
	if($template == 'portal/portal_topic_content') {
		$template = gettopictplname($clonefile);
		$istopic = true;
	} elseif ($template == 'portal/list') {
		$template = getportalcategorytplname($clonefile);
		$iscategory = true;
	} elseif ($template == 'portal/view') {
		$template = getportalarticletplname($clonefile, $template);
		$isarticle = true;
	}

	if(($istopic || $iscategory || $isarticle) && strpos($template, ':') !== false) {
		list($tpldirectory, $template) = explode(':', $template);
	}

	$checktpl = checkprimaltpl($tpldirectory.':'.$template);
	if($checktpl !== true) {
		showmessage($checktpl);
	}

	if($optype == 'cancelnest') {
		@unlink(DISCUZ_ROOT.'./data/nest/'.$tpldirectory.'/'.$targettplname.'_nest_preview'.$tpl_suffix);
		if($targettplname == $template) @unlink(DISCUZ_ROOT.'./data/nest/'.$tpldirectory.'/'.$targettplname.'_'.$clonefile.'_nest_preview'.$tpl_suffix);
		showmessage('do_success');
	}

	if ($recover == '1') {
		$file = './data/nest/'.$tpldirectory.'/'.$targettplname.$tpl_suffix;
		if (is_file($file.'.bak')) {
			copy ($file.'.bak', $file);
		} else {
			showmessage('nest_backup_noexist');
		}
	} else {
		$templatedata = [];
		checksecurity($_POST['spacecss']);
		$templatedata['spacecss'] = preg_replace("/(\<|\>)/is", '', $_POST['spacecss']);
		$style = empty($_POST['style'])?'':preg_replace("/[^0-9a-z]/i", '', $_POST['style']);
		if($style) {
			$cssfile = DISCUZ_ROOT.'./static/topic/'.$style.'/style.css';
			if(!file_exists($cssfile)) {
				showmessage('theme_does_not_exist');
			} else {
				$templatedata['style'] = "static/topic/$style/style.css";
			}
		}

		$layoutdata = getstr($_POST['layoutdata'],0,0,0,0,1);
		require_once libfile('class/xml');
		$templatedata['layoutdata'] = xml2array($layoutdata);
		if (empty($templatedata['layoutdata'])) showmessage('nest_data_format_invalid');

		$r = save_nest_data($tpldirectory, $template, $targettplname, $templatedata, true, $optype);

		include_once libfile('function/cache');
		updatecache('nesttemplatename');

		if ($r && $optype != 'savecache') {
			if (!$iscategory && !$istopic && empty($savemod) && !empty($clonefile)) {
				$delfile = DISCUZ_ROOT.'./data/nest/'.$tpldirectory.'/'.$template.'_'.$clonefile.$tpl_suffix;
				if (file_exists($delfile)) {
					unlink($delfile);
					@unlink($delfile.'.bak');
					C::t('common_template_block')->delete_by_targettplname("{$template}_{$clonefile}", $tpldirectory);
					C::t('common_nest_data')->delete("{$template}_{$clonefile}", $tpldirectory);
					include_once libfile('function/cache');
					updatecache('nesttemplatename');
				}
			}
		}
	}

	$tourl = empty($_POST['gobackurl']) || strpos($_POST['gobackurl'],'op=add') != false || strpos($_POST['gobackurl'],'&nest=yes') != false ?
			str_replace('&nest=yes','',$_SERVER['HTTP_REFERER']) : $_POST['gobackurl'];

	$tourl = preg_replace('/[\?|&]preview=yes/', '', $tourl);

	showmessage('do_success', $tourl,array('rejs'=>$_POST['rejs']));
}
if($op == 'blockclass') {

	loadcache('blockclass');
} elseif($op == 'style') {

	if(!$_G['group']['allowmanagetopic'] && !$_G['group']['allownest'] && !$_G['group']['allowaddtopic']) {
		showmessage('group_nopermission', NULL, array('grouptitle' => $_G['group']['grouptitle']), array('login' => 1));
	}

	$themes = gettheme('topic');

} elseif ($op == 'nest' || $op == 'image') {

	$topicid = intval($_GET['topicid']);
	$topic = C::t('portal_topic')->fetch($topicid);
	topic_checkperm($topic);

	$perpage = 6;
	$page = max(1, intval($_GET['page']));
	$start=  ($page-1) * $perpage;

	$list = [];
	if ($topicid) {
		$count = C::t('portal_topic_pic')->count_by_topicid($topicid);
		if (!empty($count)) {
			foreach(C::t('portal_topic_pic')->fetch_all_by_topicid($topicid, $start, $perpage) as $value) {
				$value['pic'] = pic_get($value['filepath'], 'portal', $value['thumb'], $value['remote']);
				$list[] = $value;
			}
		}
		$multi= multi($count, $perpage, $page, "portal.php?mod=portalcp&ac=nest&op=image&topicid=$topicid");
	}


} elseif ($op == 'delete') {

	$topicid = intval($_GET['topicid']);
	$topic = C::t('portal_topic')->fetch($topicid);
	topic_checkperm($topic);

	$picid = intval($_GET['picid']);

} elseif ($op == 'export') {
	if (submitcheck('exportsubmit')) {
		$tpl = $_POST['tpl'];
		$tpldirectory = $_POST['tpldirectory'];
		$frame = $_POST['frame'];
		$type = $_POST['type'];
		if (!empty($tpl)) {
			tpl_checkperm($tpl);

			list($tpl,$id) = explode(':', $tpl);
			$tplname = $id ? $tpl.'_'.$id : $tpl;
			$nestdata = C::t('common_nest_data')->fetch($tplname, $tpldirectory);
			if(empty($nestdata) && $id) $nestdata = C::t('common_nest_data')->fetch($tpl, $tpldirectory);
			if ($nestdata) {

				$filename = $nestdata['targettplname'];

				$nestcontent = dunserialize($nestdata['nestcontent']);

				if (empty($nestcontent)) showmessage('nest_no_export_data');
				if ($frame) {
					$area = '';
					$filename = $frame;
					$framedata = [];
					foreach ($nestcontent['layoutdata'] as $key => $value) {
						$framedata = getobjbyname($frame, $value);
						if ($framedata) {
							$area = $key;
							getframeblock(array($framedata['type'].'`'.$frame => $framedata['content']));
							break;
						}
					}
				} else {
					foreach ($nestcontent['layoutdata'] as $key => $value) {
						if (!empty($value)) getframeblock($value);
					}
				}

				$nestcontent['blockdata'] = block_export($_G['curtplbid']);

				if ($frame) {
					$nestcontent['spacecss'] = getcssdata($nestcontent['spacecss']);
					$nestcontent['layoutdata'] = [];
					$area = empty($area) ? 'nest1' : $area;
					$nestcontent['layoutdata'][$area][$framedata['type'].'`'.$frame] = $framedata['content'] ? $framedata['content'] : [];
				}

				dheader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
				dheader('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
				dheader('Cache-Control: no-cache, must-revalidate');
				dheader('Pragma: no-cache');
				dheader('Content-Encoding: none');

				if ($type == 'txt') {
					$str = serialize($nestcontent);
					dheader('Content-Length: '.strlen($str));
					dheader('Content-Disposition: attachment; filename='.$filename.'.txt');
					dheader('Content-Type: text/plant');
				} else {
					require_once libfile('class/xml');
					$str = array2xml($nestcontent, true);
					dheader('Content-Length: '.strlen($str));
					dheader('Content-Disposition: attachment; filename='.$filename.'.xml');
					dheader('Content-Type: text/xml');
				}
				echo $str;
				exit();
			} else {
				showmessage('nest_export_no_data','/');
			}
		} else {
			showmessage('nest_export_tpl_invalid','/');
		}
	}
	showmessage('nest_operation_invalid','/');
} elseif ($op == 'import') {

	$tpl = $_POST['tpl'] ? $_POST['tpl'] : $_GET['tpl'];
	tpl_checkperm($tpl);

	if (submitcheck('importsubmit')) {
		$isinner = false;
		$filename = '';
		if($_POST['importfilename']) {
			$filename = DISCUZ_ROOT.'./template/default/portal/nestxml/'.$_POST['importfilename'].'.xml';
			$isinner = true;
		} else {
			$upload = new discuz_upload();

			$upload->init($_FILES['importfile'], 'temp');
			$attach = $upload->attach;

			if(!$upload->error()) {
				$upload->save();
			}
			if($upload->error()) {
				showmessage($upload->error(),'portal.php',array('status'=>$upload->error()));
			} else {
				$filename = $attach['target'];
			}
		}
		if($filename) {
			$arr = import_nest($filename);
			if(!$isinner) {
				@unlink($filename);
			}
			if (!empty($arr)) {
				$search = array('/\<script/i', '/\<\/script\>/i', "/\r/", "/\n/", '/(\[script [^>]*?)(src=)(.*?\[\/script\])/');
				$replace = array('[script', '[/script]', '', '', '$1[src=]$3');
				$arr['css'] = str_replace(array("\r","\n"),array(''),$arr['css']);

				$jsarr = array('status'=>1,'css'=>$arr['css'],'bids'=>implode(',',$arr['mapping']));

				foreach ($arr['html'] as $key => $value) {
					$value = preg_replace($search,$replace,$value);
					$jsarr['html'][$key] = $value;
				}

				showmessage('do_success','portal.php',$jsarr);
			} else {
				showmessage('do_success','portal.php',array('status'=>0));
			}
		}
	}
	$xmlarr = [];
	if ($_GET['type'] == 1) {
		$xmlfilepath = DISCUZ_ROOT.'./template/default/portal/nestxml/';
		if(($dh = @opendir($xmlfilepath))) {
			while(($file = @readdir($dh)) !== false) {
				if(fileext($file) == 'xml') {
					$xmlarr[substr($file, 0, -4)] = getnestxmlname($file, $xmlfilepath);
				}
			}
			closedir($dh);
		}
		arsort($xmlarr);
	}
} else {
	showmessage('undefined_action');
}

include_once template("portal/portalcp_nest");

function topic_upload_error($attach, $msg='') {
	echo '<script>';
	echo 'parent.document.getElementById(\'uploadmsg\').innerHTML = \''.$attach['name'].' '.lang('home/template', 'upload_error').$msg.'\';';
	echo '</script>';
	exit();
}

function topic_upload_show($topicid) {

	echo '<script>';
	echo 'parent.ajaxget("portal.php?mod=portalcp&ac=nest&op=image&topicid='.$topicid.'&", "nestimages");';
	echo 'parent.document.uploadpic.attach.value = \'\';';
	echo 'Util.toggleEle(\'upload\')';
	echo '</script>';
	exit();
}

function tpl_checkperm($tpl) {
	global $_G;

	list($file,$id) = explode(':', $tpl);

	if ($file == 'portal/portal_topic_content') {
		$topicid = max(0,intval($id));
		$topic = C::t('portal_topic')->fetch($topicid);
		topic_checkperm($topic);
	} elseif($file == 'portal/list'){
		$catid = max(0,intval($id));
		$category = $_G['cache']['portalcategory'][$catid];
		category_checkperm($category);
	} else {
		if(!$_G['group']['allownest']) {
			showmessage('nest_nopermission');
		}
	}
}

function category_checkperm($category) {
	global $_G;

	if(empty($category)) {
		showmessage('topic_not_exist');
	}

	if($_G['group']['allownest']) return true;

	if(!$_G['group']['allownest'] && (!$_G['group']['allowaddtopic'] || $_G['uid'] != $category['uid'])) {
		showmessage('topic_edit_nopermission');
	}
}

function topic_checkperm($topic) {
	global $_G;

	if(empty($topic)) {
		showmessage('topic_not_exist');
	}

	if(!$_G['group']['allowmanagetopic'] && (!$_G['group']['allowaddtopic'] || $_G['uid'] != $topic['uid'])) {
		showmessage('topic_edit_nopermission');
	}
}

function gettopictplname($topicid) {
	$topicid = max(0,intval($topicid));
	$topic = C::t('portal_topic')->fetch($topicid);
	return !empty($topic) && !empty($topic['primaltplname']) ? $topic['primaltplname'] : getglobal('cache/style_default/tpldir').':portal/portal_topic_content';
}

function getportalcategorytplname($catid) {
	global $_G;
	$catid = max(0,intval($catid));
	$category = $_G['cache']['portalcategory'][$catid];
	return !empty($category) && !empty($category['primaltplname']) ? $category['primaltplname'] : getglobal('cache/style_default/tpldir').':portal/list';
}

function getportalarticletplname($catid, $primaltplname = ''){
	if(($catid = intval($catid))) {
		if(($category = C::t('portal_category')->fetch($catid))) {
			$primaltplname = $category['articleprimaltplname'];
		}
		if(empty($primaltplname)) {
			$primaltplname = getglobal('cache/style_default/tpldir').':portal/view';
			C::t('portal_category')->update($catid, array('articleprimaltplname' => $primaltplname));
		}
	}
	return $primaltplname;
}

function getnestxmlname($filename, $path) {
	$content = @file_get_contents($path.$filename);
	$name = $filename;
	if($content) {
		preg_match("/\<\!\-\-\[name\](.+?)\[\/name\]\-\-\>/i", trim($content), $mathes);
		if(!empty($mathes[1])) {
			preg_match("/^\{lang (.+?)\}$/", $mathes[1], $langs);
			if(!empty($langs[1])) {
				$name = lang('portalcp', $langs[1]);
			} else {
				$name = dhtmlspecialchars($mathes[1]);
			}
		}
	}
	return $name;
}
?>