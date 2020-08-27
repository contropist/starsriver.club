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
const AVAIBLE_PLUGIN_ACTIVE = '1';

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$dataArray=['tencentcloud_captcha', 'tencentcloud_cos', 'tencentcloud_ims', 'tencentcloud_sms', 'tencentcloud_tms', 'tencentcloud_vod'];

for ($i=0; $i<count($dataArray); $i++) {
    $pluginInfo=C::t('common_plugin')->fetch_by_identifier($dataArray[$i]);
    if ($pluginInfo['available'] == AVAIBLE_PLUGIN_ACTIVE) {
        $landurl = 'action=plugins';
        cpmsg('插件中心关闭失败，需要关闭腾讯云其它插件', $landurl . (!empty($_GET['system']) ? '&system=1' : ''), 'error');
        break;
    }
}

