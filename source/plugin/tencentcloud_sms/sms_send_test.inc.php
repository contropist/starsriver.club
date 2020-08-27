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
    exit(lang('plugin/tencentcloud_sms','require_sdk'));
}
require_once 'vendor/autoload.php';
use TencentDiscuzSMS\SMSActions;

try {
    if (submitcheck('sms_send_test')) {
        $dzxSMS = new SMSActions();
        global $_G;
        $phone = $dzxSMS->filterPostParam('phone');
        if (!$dzxSMS::isPhoneNumber($phone)) {
            cpmsg('tencentcloud_sms:phone_error', '', 'error');
        }
        $_G['setting']['tencentcloud_sms_setting'];
        $dzxSMS->sendVerifyCodeSMS($phone,$dzxSMS::TYPE_TEST);
        cpmsg('tencentcloud_sms:send_success', "action=plugins&operation=config&do={$pluginid}&identifier=tencentcloud_sms&pmod=sms_send_test&subaction=send", 'succeed');
        return;
    }
    $lang = lang('plugin/tencentcloud_sms');

    $tips = '<ol>
                <li>'.$lang['use'].'<a href='.ADMINSCRIPT.'"?action=plugins&operation=config&do='.$pluginid.'">'.$lang['setting'].'</a>'.$lang['test_send'].'</li>
                <li>'.$lang['record'].'</li>
            </ol>';
    showtips($tips);
    showformheader("plugins&operation=config&identifier=tencentcloud_sms&pmod=sms_send_test&do={$pluginid}");
    showtableheader($lang['send']);
    showsetting($lang['phone'], 'phone', '', 'text', 0, 0);
    showsubmit('sms_send_test', $lang['send']);
    showtablefooter();
    showformfooter();
    echo '<div style="text-align: center;flex: 0 0 auto;margin-top: 3rem;">
            <a href="https://openapp.qq.com/docs/DiscuzX/sms.html" target="_blank">'.$lang['docs_center'].'</a> | <a href="https://github.com/Tencent-Cloud-Plugins/tencentcloud-wordpress-plugin-sms" target="_blank">GitHub</a> | <a
                    href="https://support.qq.com/product/164613" target="_blank">'.$lang['support'].'</a>
        </div>';
}catch (\Exception $exception) {
    cpmsg($exception->getMessage(), '', 'error');
    return;
}

