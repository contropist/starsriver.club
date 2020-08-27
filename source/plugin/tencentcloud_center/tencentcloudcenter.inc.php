<?php

/*
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
require_once DISCUZ_ROOT . 'source/plugin/tencentcloud_center/lib/tencentcloud_helper.class.php';

$static_path = TencentCloudHelper::staticUrl();

/**
 * 判断是否为插件开启或关闭请求
 */
if (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['tcplugin'])&&isset($_GET['tcoperation']) && $_GET['formhash'] === FORMHASH) {
    $tencentPluginId = $_GET['tcplugin'];
    $operation = $_GET['tcoperation'];
    $conflictplugins = '';
    $plugin = C::t('common_plugin')->fetch($tencentPluginId);
    if (!$plugin) {
        cpmsg('plugin_not_found', '', 'error');
    }
    $dir = substr($plugin['directory'], 0, -1);
    $modules = dunserialize($plugin['modules']);
    $file = DISCUZ_ROOT . './source/plugin/' . $dir . '/discuz_plugin_' . $dir . ($modules['extra']['installtype'] ? '_' . $modules['extra']['installtype'] : '') . '.xml';
    if (!file_exists($file)) {
        $pluginarray[$operation . 'file'] = $modules['extra'][$operation . 'file'];
        $pluginarray['plugin']['version'] = $plugin['version'];
    } else {
        $importtxt = @implode('', file($file));
        $pluginarray = getimportdata('Discuz! Plugin');
    }
    if (!empty($pluginarray[$operation . 'file']) && preg_match('/^[\w\.]+$/', $pluginarray[$operation . 'file'])) {
        $filename = DISCUZ_ROOT . './source/plugin/' . $dir . '/' . $pluginarray[$operation . 'file'];
        if (file_exists($filename)) {
            @include $filename;
        }
    }

    if ($operation == 'enable') {

        require_once libfile('cache/setting', 'function');
        list(, , $hookscript) = get_cachedata_setting_plugin($plugin['identifier']);
        $exists = array();
        foreach ($hookscript as $script => $modules) {
            foreach ($modules as $module => $data) {
                foreach (array('funcs' => '', 'outputfuncs' => '_output', 'messagefuncs' => '_message') as $functype => $funcname) {
                    foreach ($data[$functype] as $k => $funcs) {
                        $pluginids = array();
                        foreach ($funcs as $func) {
                            $pluginids[$func[0]] = $func[0];
                        }
                        if (in_array($plugin['identifier'], $pluginids) && count($pluginids) > 1) {
                            unset($pluginids[$plugin['identifier']]);
                            foreach ($pluginids as $pluginid) {
                                $exists[$pluginid][$k . $funcname] = $k . $funcname;
                            }
                        }
                    }
                }
            }
        }
        if ($exists) {
            $plugins = array();
            foreach (C::t('common_plugin')->fetch_all_by_identifier(array_keys($exists)) as $plugin) {
                $plugins[] = '<b>' . $plugin['name'] . '</b>:' .
                    '&nbsp;<a href="javascript:;" onclick="display(\'conflict_' . $plugin['identifier'] . '\')">' . cplang('plugins_conflict_view') . '</a>' .
                    '&nbsp;<a href="' . cloudaddons_pluginlogo_url($plugin['identifier']) . '" target="_blank">' . cplang('plugins_conflict_info') . '</a>' .
                    '<span id="conflict_' . $plugin['identifier'] . '" style="display:none"><br />' . implode(',', $exists[$plugin['identifier']]) . '</span>';
            }
            $conflictplugins = '<div align="left" style="margin: auto 100px; border: 1px solid #DEEEFA;padding: 4px;line-height: 25px;">' . implode('<br />', $plugins) . '</div>';
        }
    }
    $available = $operation == 'enable' ? 1 : 0;
    C::t('common_plugin')->update($tencentPluginId, array('available' => $available));
    updatecache(array('plugin', 'setting', 'styles'));
    cleartemplatecache();
    updatemenu('plugin');
    $landurl = 'action=plugins&operation=config&do=' . $_GET['do'] . '&identifier=tencentcloud_center&pmod=tencentcloudcenter';
    if ($operation == 'enable') {
        if (!$conflictplugins) {
            cpmsg('plugins_enable_succeed', $landurl . (!empty($_GET['system']) ? '&system=1' : ''), 'succeed');
        } else {
            cpmsg('plugins_conflict', $landurl . (!empty($_GET['system']) ? '&system=1' : ''), 'succeed', array('plugins' => $conflictplugins));
        }
    } else {
        cpmsg('plugins_disable_succeed', $landurl . (!empty($_GET['system']) ? '&system=1' : ''), 'succeed');
    }
    cpmsg('plugins_' . $operation . '_succeed', $landurl . (!empty($_GET['system']) ? '&system=1' : ''), 'succeed');

}
$currentluginId=$_GET['do'];
$pluginsInfo = TencentCloudHelper::tencent_discuz_plugincenter_queryPluginInfo();
include template('tencentcloud_center:tencentcloudcenter');


