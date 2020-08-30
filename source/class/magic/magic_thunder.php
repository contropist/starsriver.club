<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: magic_thunder.php 27087 2012-01-05 01:49:09Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class magic_thunder {

	var $version = '1.0';
	var $name = 'thunder_name';
	var $description = 'thunder_desc';
	var $price = '20';
	var $weight = '20';
	var $useevent = 0;
	var $targetgroupperm = false;
	var $copyright = '<a href="http://www.Discuz.com" target="_blank">Discuz Inc.</a>';
	var $magic = [];
	var $parameters = [];

	function getsetting(&$magic) {}

	function setsetting(&$magicnew, &$parameters) {}

	function usesubmit() {
		global $_G;

		$uid = $_G['uid'];
		$_G['uid'] = 0;
		
		include_once libfile('function/feed');
        feed_add([
            'icon'           => 'thunder',
            'title_template' => 'magic_thunder',
            'title_data'     => [
                'uid'      => $uid,
                'username' => $_G['username'],
            ],
            'body_template'  => 'magic_thunder',
            'body_data'      => [
                'uid'         => $uid,
                'username'    => $_G['username'],
                'user_avatar' => avatar($uid, 'middle', true),
            ],
        ]);
		
		$_G['uid'] = $uid;
		usemagic($this->magic['magicid'], $this->magic['num']);
		updatemagiclog($this->magic['magicid'], '2', '1', '0', '0', 'uid', $_G['uid']);
		showmessage('magics_thunder_message', 'home.php?mod=space&do=home&view=all', array('magicname'=>$_G['setting']['magics']['thunder']), array('alert' => 'right', 'showdialog' => 1, 'locationtime' => true));
	}

	function show() {
		magicshowtips(lang('magic/thunder', 'thunder_info'));
	}

}

?>