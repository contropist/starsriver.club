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
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
//不是ajax请求直接退出
if( !isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    exit('Access Denied');
}
require_once libfile('function/member');
use TencentDiscuzSMS\SMSActions;
try {
    $dzxSMS = new SMSActions();
    global $_G;
    $phone = $dzxSMS->filterPostParam('phone');
    $type = intval($dzxSMS->filterPostParam('type'));
    $verifyCode = $dzxSMS->filterPostParam('verifyCode');
    $secCode = $dzxSMS->filterPostParam('secCode');
    $secCodeHash = $dzxSMS->filterGetParam('seccodehash');
    $cookieExpire = $dzxSMS->filterGetParam('cookieExpire',0);
    //验证码
    if (!check_seccode($secCode, $secCodeHash)) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_INVALID_VERIFY_CODE);
    }
    //手机号验证
    if (!$dzxSMS::isPhoneNumber($phone)) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_INVALID_PHONE);
    }
    $DBVerifyCode = $dzxSMS->getVerifyCodeByPhone($phone);
    //短信验证码比对
    if ( empty($DBVerifyCode) || $DBVerifyCode['verify_code'] !== $verifyCode) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_INVALID_VERIFY_CODE);
    }
    //验证码状态变为已使用
    $dzxSMS->loseCodeEfficacy($DBVerifyCode['id']);
    //查找uid
    $uid = $dzxSMS::getUidByPhone($phone);
    if ($uid === 0) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_PHONE_UNBIND);
    }
    //获取用户
    $user = getuserbyuid($uid, 1);
    if (empty($user['uid'])) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_PHONE_UNBIND);
    }
    if (isset($user['_inarchive'])) {
        C::t('common_member_archive')->move_to_master($user['uid']);
    }
    //设置cookie
    setloginstatus($user, $cookieExpire);
    checkfollowfeed();
    //更新用户最新登录信息
    if ($_G['member']['lastip'] && $_G['member']['lastvisit']) {
        dsetcookie('lip', $_G['member']['lastip'] . ',' . $_G['member']['lastvisit']);
    }
    C::t('common_member_status')->update($uid, array('lastip' => $_G['clientip'], 'port' => $_G['remoteport'], 'lastvisit' => time(), 'lastactivity' => time()));
    //登录跳转
    $dzxSMS->jsonReturn($dzxSMS::CODE_SUCCESS,['location'=>dreferer()]);
} catch (\Exception $exception) {
    $dzxSMS->jsonReturn($dzxSMS::CODE_EXCEPTION,array(),$exception->getMessage().'  请联系管理员解决');
}
