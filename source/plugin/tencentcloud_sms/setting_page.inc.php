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
defined('TENCENT_DISCUZX_SMS_DIR')||define( 'TENCENT_DISCUZX_SMS_DIR', __DIR__.DIRECTORY_SEPARATOR);
if (!is_file(TENCENT_DISCUZX_SMS_DIR.'vendor/autoload.php')) {
    exit('缺少依赖文件，请确保安装了腾讯云sdk');
}
require_once 'vendor/autoload.php';
use TencentDiscuzSMS\SMSActions;
use TencentDiscuzSMS\SMSOptions;

try {
    //不是post请求直接返回html页面
    if( $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $options = SMSActions::getSMSOptionsObject();
        $customKey = $options->getCustomKey();
        $secretId = $options->getSecretID();
        $secretKey = $options->getSecretKey();
        $codeExpired = $options->getCodeExpired();
        $commentNeedPhone = $options->getCommentNeedPhone();
        $postNeedPhone = $options->getPostNeedPhone();
        $sign = $options->getSign();
        $SDKAppID = $options->getSDKAppID();
        $templateId = $options->getTemplateID();
        $hasExpireTime = $options->getHasExpiredTime();
        $actionUrl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tencentcloud_sms&pmod=setting_page';
        include template('tencentcloud_sms:setting_page');
        exit;
    }
    $dzxSMS = new SMSActions();
    $options = SMSActions::getSMSOptionsObject();
    $options->setCustomKey(intval($dzxSMS->filterPostParam('customKey',SMSOptions::GLOBAL_KEY)));
    $options->setSecretID($dzxSMS->filterPostParam('secretId'));
    $options->setSecretKey($dzxSMS->filterPostParam('secretKey'));
    $options->setSign($dzxSMS->filterPostParam('sign'));
    $options->setTemplateID($dzxSMS->filterPostParam('templateId'));
    $options->setSDKAppID($dzxSMS->filterPostParam('SDKAppID'));
    $options->setPostNeedPhone($dzxSMS->filterPostParam('postNeedPhone',SMSOptions::POST_NEED_PHONE));
    $options->setCommentNeedPhone($dzxSMS->filterPostParam('replyNeedPhone',SMSOptions::COMMENT_NEED_PHONE));
    $options->setCodeExpired($dzxSMS->filterPostParam('codeExpired',SMSOptions::DEFAULT_EXPIRED));
    $options->setHasExpiredTime($dzxSMS->filterPostParam('hasExpiredTime',SMSOptions::NOT_EXPIRED_TIME));

    C::t('common_setting')->update_batch(array("tencentcloud_sms" => $options->toArray()));
    updatecache('setting');
    SMSActions::uploadDzxStatisticsData('save_config');
    $url = 'action=plugins&operation=config&do='.$pluginid.'&identifier=tencentcloud_sms&pmod=setting_page';
    cpmsg('plugins_edit_succeed', $url, 'succeed');
}catch (\Exception $exception) {
    cpmsg($exception->getMessage(), '', 'error');
}
