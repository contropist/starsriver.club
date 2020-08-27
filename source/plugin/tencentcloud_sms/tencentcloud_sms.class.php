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
if (!defined('IN_DISCUZ')){
    exit('Access Denied');
}
defined('TENCENT_DISCUZX_SMS_DIR')||define( 'TENCENT_DISCUZX_SMS_DIR', __DIR__.DIRECTORY_SEPARATOR);
defined('TENCENT_DISCUZX_SMS_PLUGIN_NAME')||define( 'TENCENT_DISCUZX_SMS_PLUGIN_NAME', 'tencentcloud_sms');
defined('TENCENT_DISCUZX_USER_BIND_TABLE')||define( 'TENCENT_DISCUZX_USER_BIND_TABLE', 'tencent_discuzx_sms_user_bind');
defined('TENCENT_DISCUZX_SMS_SENT_TABLE')||define( 'TENCENT_DISCUZX_SMS_SENT_TABLE', 'tencent_discuzx_sms_sent_records');
if (!is_file(TENCENT_DISCUZX_SMS_DIR.'vendor/autoload.php')) {
    exit(lang('plugin/tencentcloud_sms','require_sdk'));
}
require_once 'vendor/autoload.php';

use TencentDiscuzSMS\SMSActions;
use TencentDiscuzSMS\SMSOptions;
class plugin_tencentcloud_sms
{
    public static $G;
    public static $pluginOptions;
    public function __construct()
    {
        global $_G;
        self::$G = $_G;
        self::$pluginOptions = unserialize($_G['setting'][TENCENT_DISCUZX_SMS_PLUGIN_NAME]);
    }

    public function common()
    {
        global $_G;
        if (empty(self::$G['uid'])) {
            return;
        }
        $_G['user_phone'] = self::$G['user_phone'] = SMSActions::getPhoneByUid(self::$G['uid']);
        if (empty(self::$G['user_phone']) && $_GET['mod'] == 'post') {
            //发帖前验证是否绑定了手机号
            if (self::$pluginOptions['postNeedPhone'] === SMSOptions::POST_NEED_PHONE
                && $_GET['action'] == 'newthread') {
                showmessage('tencentcloud_sms:need_bind_phone');
            }
            //回帖前验证是否绑定了手机号
            if (self::$pluginOptions['commentNeedPhone'] === SMSOptions::COMMENT_NEED_PHONE
                && $_GET['action'] == 'reply') {
                showmessage('tencentcloud_sms:need_bind_phone');
            }
        }
    }

    //登录区域
    public function global_login_extra()
    {
        include template('tencentcloud_sms:phone_functions_btn');
        return $phone_functions_btn;

    }

    // 已登录用户导航栏区域
    public function global_usernav_extra3()
    {
        //用户已绑定手机号不显示
        if (self::$G['user_phone']) {
            return;
        }
        if (!self::$G['uid']){
            return;
        }
        return '<a href="home.php?ac=plugin&mod=spacecp&id=tencentcloud_sms:bind_phone"><span style="color: red">'.lang('plugin/tencentcloud_sms','unbind').'</span></a>';
    }

}

class plugin_tencentcloud_sms_forum extends plugin_tencentcloud_sms
{
    public function viewthread_fastpost_btn_extra()
    {
        if (self::$pluginOptions['commentNeedPhone'] === SMSOptions::COMMENT_NEED_PHONE
            && empty(self::$G['user_phone'])) {
            include template('tencentcloud_sms:need_bind_phone');
            return $need_bind_phone;
        }
    }

    public function forumdisplay_postbutton_top()
    {
        if (self::$pluginOptions['postNeedPhone'] === SMSOptions::POST_NEED_PHONE
            && empty(self::$G['user_phone'])) {
            include template('tencentcloud_sms:need_bind_phone');
            return $need_bind_phone;
        }
    }

    public function forumdisplay_postbutton_bottom()
    {
        if (self::$pluginOptions['postNeedPhone'] === SMSOptions::POST_NEED_PHONE
            && empty(self::$G['user_phone'])) {
            include template('tencentcloud_sms:need_bind_phone');
            return $need_bind_phone;
        }
    }

    public function forumdisplay_fastpost_btn_extra()
    {
        if (self::$pluginOptions['postNeedPhone'] === SMSOptions::POST_NEED_PHONE
            && empty(self::$G['user_phone'])) {
            include template('tencentcloud_sms:need_bind_phone');
            return $need_bind_phone;
        }
    }

}
