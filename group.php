<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: group.php 31307 2012-08-10 02:10:56Z zhengqingpeng $
 */

define('APPTYPEID', 3);
define('CURSCRIPT', 'group');


require './source/class/class_core.php';

$cachelist = [
    'grouptype',
    'groupindex',
    'nesttemplatenamegroup'
];

$modarray = [
    'index',
    'my',
    'attentiongroup'
];

$mod = !in_array($_G['mod'], $modarray) ? 'index' : $_G['mod'];

define('CURMODULE', $mod);

C::app()->cachelist = $cachelist;
C::app()->init();
runhooks();

$_G['disabledwidthauto'] = 0;

$navtitle = str_replace('{bbname}', $_G['setting']['bbname'], $_G['setting']['seotitle']['group']);

require DISCUZ_ROOT.'./source/module/group/group_'.$mod.'.php';