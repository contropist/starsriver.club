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
    $newPwd = $dzxSMS->filterPostParam('newPwd');
    $confirmNewPwd = $dzxSMS->filterPostParam('confirmNewPwd');
    $secCode = $dzxSMS->filterPostParam('secCode');
    $secCodeHash = $dzxSMS->filterGetParam('seccodehash');
    //验证码
    if (!check_seccode($secCode, $secCodeHash)) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_INVALID_VERIFY_CODE);
    }
    //手机号验证
    if (!$dzxSMS::isPhoneNumber($phone)) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_INVALID_PHONE);
    }
    //判断两次密码是否相等
    if(empty($confirmNewPwd) ||$newPwd !== $confirmNewPwd) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_TWO_PWD_UNEQUAL);
    }
    //新密码强度不符合
    if (strlen($newPwd) < $_G['setting']['pwlength']){
        $dzxSMS->jsonReturn($dzxSMS::CODE_NEW_PWD_TOO_SHORT);
    }
    $DBVerifyCode = $dzxSMS->getVerifyCodeByPhone($phone);
    //短信验证码比对
    if ( empty($DBVerifyCode) || $DBVerifyCode['verify_code'] !== $verifyCode) {
        $dzxSMS->jsonReturn($dzxSMS::CODE_INVALID_VERIFY_CODE);
    }

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
    //重置密码操作
    loaducenter();
    uc_user_edit(addslashes($user['username']), $newPwd, $confirmNewPwd, addslashes($user['email']), 1, 0);
    if (isset($user['_inarchive'])) {
        C::t('common_member_archive')->move_to_master($user['uid']);
    }
    //验证码状态变为已使用
    $dzxSMS->loseCodeEfficacy($DBVerifyCode['id']);
    $password = md5(uniqid('tencentcloud',true));
    //这里的密码是用来做cookie验证的
    C::t('common_member')->update($user['uid'], array('password' => $password));
    //跳转首页
    $dzxSMS->jsonReturn($dzxSMS::CODE_SUCCESS,['location'=>'index.php']);
} catch (\Exception $exception) {
    $dzxSMS->jsonReturn($dzxSMS::CODE_EXCEPTION,array(),$exception->getMessage().'  请联系管理员解决');
}
