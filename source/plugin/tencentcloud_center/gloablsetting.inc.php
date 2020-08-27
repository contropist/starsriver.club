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
const SITE_REPORT_FLAG = '1';
const SITE_SECRE_KEY_FLAG = '1';
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
global $_G;
require_once DISCUZ_ROOT.'source/plugin/tencentcloud_center/lib/tencentcloud_helper.class.php';

//判断是否为插件中心保存设置请求
if(($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['tencentcloudglobal']) && $_POST['formhash'] === FORMHASH){
    //获取到请求数据
    $data = $_POST['tencentcloudglobal'];
    $save_data = array();
    $save_data['secretId'] = TencentCloudHelper::filterParam( $data['secretId']);
    $save_data['secretKey'] = TencentCloudHelper::filterParam( $data['secretKey']);
    $save_data['site_report_on'] = TencentCloudHelper::filterParam( $data['site_report_on']);
    $save_data['site_sec_on'] = TencentCloudHelper::filterParam( $data['site_sec_on']);
    $site_id = TencentCloudHelper::getDiscuzSiteID();
    $save_data['site_id'] = $site_id;
    //保存到数据库中
    C::t('common_setting')->update_batch(array("tencentcloud_center" => $save_data));
    //更新缓存信息
    updatecache('setting');

    $site_url = TencentCloudHelper::siteUrl();
    $site_app = TencentCloudHelper::getDiscuzSiteApp();
    $static_data = array(
        'action' => 'save_common_config',
        'data' => array(
                'site_id'  => $site_id,
                'site_url' => $site_url,
                'site_app' => $site_app
        )
    );

    if ($data['site_sec_on'] == SITE_SECRE_KEY_FLAG && isset($data['secretId']) && isset($data['secretKey'])) {
        $static_data['data']['uin'] = TencentCloudHelper::getUserUinBySecret($data['secretId'],$data['secretKey']);
    }

    TencentCloudHelper::sendUserExperienceInfo($static_data);

    //组装返回URL
    $landurl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=tencentcloud_center&pmod=gloablsetting';
    cpmsg('plugins_edit_succeed', $landurl, 'succeed');
}
$static_path  = TencentCloudHelper::staticUrl();
$config = TencentCloudHelper::config();
include template('tencentcloud_center:gloablesetting');


