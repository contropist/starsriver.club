<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: function_attachment.php 28348 2012-02-28 06:16:29Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function attachtype($type, $returnval = 'html') {

	static $attachicons = array(
			0 => 'common.svg',

			'img' => 'image.svg',   
			'audio' => 'music.svg', 
			'video' => 'video.svg', 
			'flash' => 'flash.svg',
			'real' => 'real.svg',
			'pdf' => 'pdf.svg',

			'txt' => 'txt.svg',    
			'word' => 'word.svg',
			'excel' => 'excel.svg',
			'ppt' => 'ppt.svg',

			'zip' => 'zip.svg',
			'rar' => 'zip.svg',
			'lzh' => 'zip.svg',
			'arj' => 'zip.svg',

			'html' => 'html.svg',
			'xml' => 'code.svg',
			'css' => 'css.svg',
			'php' => 'code.svg',
			'js' => 'code.svg',
			'asp' => 'code.svg',
			'exe' => 'exe.svg',
			'cmd' => 'cmd.svg',
			'log' => 'log.svg',
			'bt' => 'bt.svg',
			'url' => 'url.svg'
		);

	if(is_numeric($type)) {
		$typeid = $type;
	} else {
		if(preg_match("/image|^(jpg|gif|png|bmp|ico|svg)\t/", $type)){
			$typeid = 'img';
		} elseif (preg_match("/^(wav|mid|mp3|m3u|wma|vqf|m4a|ogg|weba|aac|flac)\t/", $type)){
			$typeid = 'audio';
		} elseif (preg_match("/^(asf|asx|mpg|mpeg|avi|wmv|mp4|mkv|m2ts|rmvb|mov|m4v|3gp|ogv|webm)\t/", $type)){
			$typeid = 'video';
		} elseif (preg_match("/flash|^(swf|fla|flv|swi)\t/", $type)){
			$typeid = 'flash';
		} elseif (preg_match("/^(ra|rm|rv)\t/", $type)){
			$typeid = 'real';
		} elseif (preg_match("/pdf|^pdf\t/", $type)){
			$typeid = 'pdf';
		} elseif (preg_match("/^(txt|rtf|wri|chm|md)\t/", $type)){
			$typeid = 'txt';
		} elseif (preg_match("/^(doc|docx|dot|docm|wps)\t/", $type)){
			$typeid = 'word';
		} elseif (preg_match("/^(ppt|pptx|pptm|xps)\t/", $type)){
			$typeid = 'ppt';
		} elseif (preg_match("/^(xlsx|xlsm|xls|xlsb)\t/", $type)){
			$typeid = 'excel';
		} elseif (preg_match("/^(zip|tar|gz|7z)\t/", $type)){
			$typeid = 'zip';
		} elseif (preg_match("/^rar\t/", $type)){
			$typeid = 'rar';
		} elseif (preg_match("/^(lzh|lha|)\t/", $type)){
			$typeid = 'lzh';
		} elseif (preg_match("/^(arj|arc|cab)\t/", $type)){
			$typeid = 'arj';
		} elseif (preg_match("/^html\t/", $type)){
			$typeid = 'html';
		} elseif (preg_match("/^css\t/", $type)){
			$typeid = 'css';
		} elseif (preg_match("/^(html|htm|xml|yml|config)\t/", $type)){
			$typeid = 'xml';
		} elseif (preg_match("/^(php|class)\t/", $type)){
			$typeid = 'php';
		} elseif (preg_match("/^(js|jsp|json)\t/", $type)){
			$typeid = 'js';
		} elseif (preg_match("/^(pl|cgi|asp)\t/", $type)){
			$typeid = 'asp';
		} elseif (preg_match("/^(exe)\t/", $type)){
			$typeid = 'exe';
		} elseif (preg_match("/^(com|bat|dll)\t/", $type)){
			$typeid = 'cmd';
		} elseif (preg_match("/^(log)\t/", $type)){
			$typeid = 'log';
		} elseif (preg_match("/bittorrent|^torrent\t/", $type)){
			$typeid = 'bt';
		} elseif (preg_match("/^url\t/", $type)){
			$typeid = 'url';
		} else{
			$typeid = 0;
		}
	}
	if($returnval == 'html') {
		return '<img class="fileicon" src="'.IMGURL.'/common/filetype/'.$attachicons[$typeid].'" />';
	} elseif($returnval == 'id') {
		return $typeid;
	}
}

function parseattach($attachpids, $attachtags, &$postlist, $skipaids = []) {
	global $_G;
	if(!$attachpids) {
		return;
	}
	$attachpids = is_array($attachpids) ? $attachpids : array($attachpids);
	$attachexists = FALSE;
	$skipattachcode = $aids = $payaids = $findattach = [];
	foreach(C::t('forum_attachment_n')->fetch_all_by_id('tid:'.$_G['tid'], 'pid', $attachpids) as $attach) {
		$attachexists = TRUE;
		if($skipaids && in_array($attach['aid'], $skipaids)) {
			$skipattachcode[$attach[pid]][] = "/\[attach\]$attach[aid]\[\/attach\]/i";
			continue;
		}
		$attached = 0;
		$extension = strtolower(fileext($attach['filename']));
		$attach['ext'] = $extension;
        $attach['imgalt'] = $attach['isimage'] ? strip_tags(str_replace('"', '', $attach['description'] ? $attach['description'] : $attach['filename'])) : '';
		$attach['attachicon'] = attachtype($extension."\t".$attach['filetype']);
		$attach['attachsize'] = sizecount($attach['filesize']);
		if($attach['isimage'] && !$_G['setting']['attachimgpost']) {
			$attach['isimage'] = 0;
		}
		$attach['attachimg'] = $attach['isimage'] && (!$attach['readperm'] || $_G['group']['readaccess'] >= $attach['readperm']) ? 1 : 0;
		if($attach['attachimg']) {
			$GLOBALS['aimgs'][$attach['pid']][] = $attach['aid'];
		}
		if($attach['price']) {
			if($_G['setting']['maxchargespan'] && TIMESTAMP - $attach['dateline'] >= $_G['setting']['maxchargespan'] * 3600) {
				C::t('forum_attachment_n')->update('tid:'.$_G['tid'], $attach['aid'], array('price' => 0));
				$attach['price'] = 0;
			} elseif(!$_G['forum_attachmentdown'] && $_G['uid'] != $attach['uid']) {
				$payaids[$attach['aid']] = $attach['pid'];
			}
		}
		$attach['payed'] = $_G['forum_attachmentdown'] || $_G['uid'] == $attach['uid'] ? 1 : 0;
		$attach['url'] = ($attach['remote'] ? $_G['setting']['ftp']['attachurl'] : $_G['setting']['attachurl']).'forum/';
		$attach['dbdateline'] = $attach['dateline'];
		$attach['dateline'] = dgmdate($attach['dateline'], 'u');
		$hideattachs = $_G['adminid'] != 1 && $_G['setting']['bannedmessages'] & 1 && (($postlist[$attach['pid']]['authorid'] && !$postlist[$attach['pid']]['username'])
				|| ($postlist[$attach['pid']]['groupid'] == 4 || $postlist[$attach['pid']]['groupid'] == 5) || $postlist[$attach['pid']]['status'] == -1 || $postlist[$attach['pid']]['memberstatus'])
				|| $_G['adminid'] != 1 && $postlist[$attach['pid']]['status'] & 1 || $postlist[$attach['pid']]['first'] && $_G['forum_threadpay'];
		if(!$hideattachs) {
			if(defined('IN_MOBILE_API')) {
				$attach['aidencode'] = packaids($attach);
			}
			$postlist[$attach['pid']]['attachments'][$attach['aid']] = $attach;
		}
		if(!defined('IN_MOBILE_API') && !empty($attachtags[$attach['pid']]) && is_array($attachtags[$attach['pid']]) && in_array($attach['aid'], $attachtags[$attach['pid']])) {
			$findattach[$attach['pid']][$attach['aid']] = "/\[attach\]$attach[aid]\[\/attach\]/i";
			$attached = 1;
		}

		if(!$attached) {
			if($attach['isimage']) {
				if(!$hideattachs) {
					$postlist[$attach['pid']]['imagelist'][] = $attach['aid'];
					$postlist[$attach['pid']]['imagelistcount']++;
				}
				if($postlist[$attach['pid']]['first']) {
					$GLOBALS['firstimgs'][] = $attach['aid'];
				}
			} else {
				if(!$hideattachs && (!$_G['forum_skipaidlist'] || !in_array($attach['aid'], $_G['forum_skipaidlist']))) {
					$postlist[$attach['pid']]['attachlist'][] = $attach['aid'];
				}
			}
		}
		$aids[] = $attach['aid'];
	}
	if($aids) {
		$attachs = C::t('forum_attachment')->fetch_all($aids);
		foreach($attachs as $aid => $attach) {
			if($postlist[$attach['pid']]) {
				$postlist[$attach['pid']]['attachments'][$attach['aid']]['downloads'] = $attach['downloads'];
			}
		}
	}
	if($payaids) {
		foreach(C::t('common_credit_log')->fetch_all_by_uid_operation_relatedid($_G['uid'], 'BAC', array_keys($payaids)) as $creditlog) {
			$postlist[$payaids[$creditlog['relatedid']]]['attachments'][$creditlog['relatedid']]['payed'] = 1;
		}
	}
	if(!empty($skipattachcode)) {
		foreach($skipattachcode as $pid => $findskipattach) {
			foreach($findskipattach as $findskip) {
				$postlist[$pid]['message'] = preg_replace($findskip, '', $postlist[$pid]['message']);
			}
		}
	}

	if($attachexists) {
		foreach($attachtags as $pid => $aids) {
			if($findattach[$pid]) {
				foreach($findattach[$pid] as $aid => $find) {
					$postlist[$pid]['message'] = preg_replace($find, attachinpost($postlist[$pid]['attachments'][$aid], $postlist[$pid]), $postlist[$pid]['message'], 1);
					$postlist[$pid]['message'] = preg_replace($find, '', $postlist[$pid]['message']);
				}
			}
		}
	} else {
		loadcache('posttableids');
		$posttableids = $_G['cache']['posttableids'] ? $_G['cache']['posttableids'] : array('0');
		foreach($posttableids as $id) {
			C::t('forum_post')->update($id, $attachpids, array('attachment' => '0'), true);
		}
	}
}

function attachwidth($width) {
	global $_G;
	if($_G['setting']['imagemaxwidth'] && $width) {
		return 'class="zoom" onclick="zoom(this, this.src, 0, 0, '.($_G['setting']['showexif'] ? 1 : 0).')" width="'.($width > $_G['setting']['imagemaxwidth'] ? $_G['setting']['imagemaxwidth'] : $width).'"';
	} else {
		return 'thumbImg="1"';
	}
}

function packaids($attach) {
	global $_G;
	return aidencode($attach['aid'], 0, $_G['tid']);
}

function showattach($post, $type = 0) {
	$type = !$type ? 'attachlist' : 'imagelist';
	$return = '';
	if(!empty($post[$type]) && is_array($post[$type])) {
		foreach($post[$type] as $aid) {
			if(!empty($post['attachments'][$aid])) {
				$return .= $type($post['attachments'][$aid], $post['first']);
			}
		}
	}
	return $return;
}

function getattachexif($aid, $path = '') {
	global $_G;
	$return = $filename = '';
	if(!$path) {
		if($attach = C::t('forum_attachment_n')->fetch('aid:'.$aid, $aid, array(1, -1))) {
			if($attach['remote']) {
				$filename = $_G['setting']['ftp']['attachurl'].'forum/'.$attach['attachment'];
			} else {
				$filename = $_G['setting']['attachdir'].'forum/'.$attach['attachment'];
			}
		}
	} else {
		$filename = $path;
	}
	if($filename) {
		require_once libfile('function/exif');
		$exif = getexif($filename);
		$keys = array(
		    exif_lang('Model'),
		    exif_lang('ShutterSpeedValue'),
		    exif_lang('ApertureValue'),
		    exif_lang('FocalLength'),
		    exif_lang('ExposureTime'),
		    exif_lang('DateTimeOriginal'),
		    exif_lang('ISOSpeedRatings'),
		);
		foreach($exif as $key => $value) {
			if(in_array($key, $keys)) {
				$return .= "$key : $value<br>";
			}
		}
	}
	return $return;
}

?>