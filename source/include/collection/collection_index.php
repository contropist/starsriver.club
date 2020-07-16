<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: collection_index.php 33200 2013-05-06 12:27:49Z laoguozhang $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$navtitle = lang('core', 'title_collection');
$searchtitle = !empty($_GET['kw']) ? dhtmlspecialchars($_POST['kw']) : '';
$oplist = array('all', 'my');
if(!in_array($op, $oplist)) {
	$op = '';
}

$cpp = 43;
$start = ($page-1)*$cpp;
$orderbyarr = array('follownum', 'threadnum', 'commentnum', 'dateline');
$orderby = (in_array($_GET['order'], $orderbyarr)) ? $_GET['order'] : 'dateline';

if($op == 'all') {
    $count = C::t('forum_collection')->count();
	$collectiondata = processCollectionData(C::t('forum_collection')->fetch_all('', $orderby, 'DESC', $start, $cpp, $searchtitle), [], $orderby);
	$multipage = multi($count, $cpp, $page, 'forum.php?mod=collection&op='.$op.'&order='.$orderby.($searchtitle ? '&kw='.$searchtitle : ''));

	include template('forum/collection_all');
} elseif ($op == 'my') {
	$mycollection = C::t('forum_collection')->fetch_all_by_uid($_G['uid']);
	$myctid = array_keys($mycollection);
	$teamworker = C::t('forum_collectionteamworker')->fetch_all_by_uid($_G['uid']);
	$twctid = array_keys($teamworker);
	$follow = C::t('forum_collectionfollow')->fetch_all_by_uid($_G['uid']);
	if(empty($follow)) {
		$follow = [];
	}
	$followctid = array_keys($follow);

	if(!$myctid) {
		$myctid = [];
	}
	if(!$twctid) {
		$twctid = [];
	}
	if(!$followctid) {
		$followctid = [];
	}

	$ctidlist = array_merge($myctid, $twctid, $followctid);

	if(count($ctidlist) > 0) {
		$tfcollection = $mycollection + $teamworker + $follow;
		$collectiondata = C::t('forum_collection')->fetch_all($ctidlist, $orderby, 'DESC', '', '', $searchtitle);
		$collectiondata = processCollectionData($collectiondata, $tfcollection);
	}

	include template('forum/collection_mycollection');
} else {
	if(!$tid) {
		$collectiondata = [];
		loadcache('collection');

		if(TIMESTAMP - $_G['cache']['collection']['dateline'] > 300) {
			$collection = getHotCollection(500, false);
			$collectioncache = array('dateline' => TIMESTAMP, 'data' => $collection);
			savecache('collection', $collectioncache);
		} else {
			$collection = &$_G['cache']['collection']['data'];
		}
		$count = count($collection);
		for($i = $start; $i < $start+$cpp; $i++) {
			if(!$collection[$i]) {
				continue;
			}
			$collectiondata[] = $collection[$i];
		}
		unset($collection);
		$collectiondata = processCollectionData($collectiondata);
	} else {
		$tidrelate = C::t('forum_collectionrelated')->fetch($tid);
		$ctids = explode("\t", $tidrelate['collection'], -1);
		$count = count($ctids);
		$collectiondata = C::t('forum_collection')->fetch_all($ctids, 'follownum', 'DESC', $start, $cpp, $searchtitle);
		$collectiondata = processCollectionData($collectiondata);
	}

	$multipage = multi($count, $cpp, $page, 'forum.php?mod=collection'.($tid ? '&tid='.$tid : '').($searchtitle ? '&kw='.$searchtitle : ''));

	include template('forum/collection_index');
}


?>