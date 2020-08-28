<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_main.php 36284 2016-12-12 00:47:50Z nemohou $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

global $_G;

require './source/admincp/admincp_menu.php';

lang('admincp_menu');

$extra = cpurl('url');
$extra = $extra && getgpc('action') ? $extra : 'action=index';
$charset = CHARSET;
$title = cplang('admincp_title');
$header_welcome = cplang('header_welcome');
$header_logout = cplang('header_logout');
$header_bbs = cplang('header_bbs');
if(isfounder()) {
	cplang('founder_admin');
} else {
	if($GLOBALS['admincp']->adminsession['cpgroupid']) {
		$cpgroup = C::t('common_admincp_group')->fetch($GLOBALS['admincp']->adminsession['cpgroupid']);
		$cpadmingroup = $cpgroup['cpgroupname'];
	} else {
		cplang('founder_master');
	}
}
$basescript = ADMINSCRIPT;

echo <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
        <title>$title</title>
        <link rel="stylesheet" href="{$_G['config']['output']['fonturl']}/Feather.css">
        <link rel="stylesheet" href="static/image/admincp/src/css/admincp.css?{$_G['style']['verhash']}" type="text/css" media="all" />

        <script src="{$_G[setting][jspath]}common.js?{$_G['style']['verhash']}"></script>
        <script src="{$_G[setting][jspath]}common_protos.js?{$_G['style']['verhash']}"></script>
        <script src="{$_G[setting][jspath]}common_compos.js?{$_G['style']['verhash']}"></script>
        <script src="{$_G[setting][jspath]}common_action.js?{$_G['style']['verhash']}"></script>

    </head>
    <body>
        <div id="append_parent"></div>
        <div id="frametable">
            <div class="nav">
                <a class="ft-x-circle logout" id="frameuinfo" href="$basescript?action=logout" target="_top"></a>
                <ul id="topmenu">
EOT;
                    foreach($topmenu as $k => $v) {
                        if($k == 'cloud') {
                            continue;
                        }
                        if($v === '') {
                            $v = @array_keys($menu[$k]);
                            $v = $menu[$k][$v[0]][1];
                        }
                        showheader($k, $v);
                    }
                    $uc_api_url = '';
                    if($isfounder) {
                        loaducenter();
                        $uc_api_url = UC_API;
                        echo '<li><a id="header_uc" hidefocus="true" href="'.UC_API.'/ucterminal.php?m=frame" onclick="uc_login=1;toggleMenu(\'uc\', \'\');doane(event);">'.cplang('header_uc').'</a></li>';
                        $topmenu['uc'] = '';
                    }
                    $headers = "'".implode("','", array_keys($topmenu))."'";
                    echo
<<<EOT
                </ul>
                <div class="currentloca">
                    <div class="subnav" id="admincpnav"></div>
                    <div class="sitemap">
                        <form name="search" method="post" autocomplete="off" action="$basescript?action=search" target="main" style="float: left;">
                            <input type="hidden" name="searchsubmit" value="yes"/>
                            <a class="map-icon ft-map-pin" id="cpmap" onclick="showMap();return false;"></a>
                            <i class="map-key">
                                <input type="text" name="keywords"/>
                                <button class="map-btn ft-search" type="submit" ></button>
                            </i>
                        </form>
                        <span id="add2custom" style="display: none"></span>
                    </div>
                </div>
            </div>
            <div class="nav-vertical" id="leftmenu">
                <div class='logo'>SR.ADMIN</div>
EOT;
                foreach ($menu as $k => $v) {showmenu($k, $v);}
                unset($menu);
                $plugindefaultkey = $isfounder ? 1 : 0;
                echo
<<<EOT
            </div>
            <div class="mask">
                <iframe class='feature' src="$basescript?$extra" id="main" name="main" frameborder="0"></iframe>
            </div>
        </div>
        
        <div id="scrolllink" style="display: none"></div>
        <div id="cpmap_menu" class="custom" style="display: none">
            <div class="cmain" id="cmain"></div>
            <div class="cfixbd"></div>
        </div>
        <div class="script" hide>
            <script>
                var cookiepre = '{$_G['config']['cookie']['cookiepre']}', 
                    cookiedomain = '{$_G['config']['cookie']['cookiedomain']}', 
                    cookiepath = '{$_G['config']['cookie']['cookiepath']}',
                    headers = [$headers], 
                    admincpfilename = '$basescript', 
                    menukey = '',
                    uclink = '$uc_api_url/ucterminal.php?m=frame',
                    plugindefaultkey = $plugindefaultkey,
                    lextra = '$extra',
                    cplangs = {
                        admincp_maptitle: '$lang[admincp_maptitle]',
                        nav_newwin : '$lang[nav_newwin]'
                    };
            </script>
            <script src="static/image/admincp/src/js/admincp_common.js"></script>
        </div>
    </body>
</html>
EOT;

?>