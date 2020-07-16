<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: lang_email.php 35030 2014-10-23 07:43:23Z laoguozhang $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$lang = [
	'hello' => '你好',
	'moderate_member_invalidate' => '否决',
	'moderate_member_delete' => '删除',
	'moderate_member_validate' => '通过',


	'get_passwd_subject' =>	'重置密码',
    'get_passwd_message' =>	'<tr><td class="starsriver_mail_content_titl"><span>你好，{username}.</span></td></tr>
                             <tr><td class="starsriver_mail_content_para_warning"><span><strong>重要！</strong>如果你没有提交密码重置的请求或不是 {bbname} 的注册用户，请立即忽略并删除这封邮件。</span></td></tr>
                             <tr><td class="starsriver_mail_content_para"><span>你收到这封邮件，是由于这个邮箱地址在 {bbname} 被登记为用户邮箱，且该用户请求使用 Email 密码重置功能所致。</span></td></tr>
                             <tr><td class="starsriver_mail_content_para_method"><span>点击或复制下面的链接到浏览器中重置你的密码：<br />
                                 <a href="{siteurl}member.php?mod=getpasswd&amp;uid={uid}&amp;id={idstring}&amp;sign={sign}" target="_blank">{siteurl}member.php?mod=getpasswd&amp;uid={uid}&amp;id={idstring}&amp;sign={sign}</a>
                             </span></td></tr>
                             <tr><td class="starsriver_mail_content_para"><span>在上面的链接所打开的页面中输入新的密码后提交，你即可使用新的密码登录网站了。你可以在用户控制面板中随时修改你的密码。</span></td></tr>',


	'email_verify_subject' => 'Email 地址验证',
	'email_verify_message' => '<tr><td class="starsriver_mail_content_titl"><span>你好，{username}.</span></td></tr>
                               <tr><td class="starsriver_mail_content_para"><span>你收到这封邮件，是由于在 {bbname} 进行了新用户注册，或用户修改 Email 使用了这个邮箱地址。如果你并没有访问过 {bbname}，或没有进行上述操作，则可能是其他用户误填了您的邮箱，请忽略这封邮件。你不需要退订或进行其他进一步的操作。</span></td></tr>
                               <tr><td class="starsriver_mail_content_para"><span><strong>帐号激活说明：</strong>如果你是 {bbname} 的新用户，或在修改你的注册 Email 时使用了本地址，我们需要对你的地址有效性进行验证以避免垃圾邮件或地址被滥用。</span></td></tr>
                               <tr><td class="starsriver_mail_content_para_method"><span>点击或复制下面的链接到浏览器中即可激活你的帐号：<br />
                                    <a href="{url}" target="_blank">{url}</a>
                               </span></td></tr>',

	'email_register_subject' =>	'论坛注册地址',
	'email_register_message' =>	'<tr><td class="starsriver_mail_content_titl"><span>你好，感谢你在我们的网站注册.</span></td></tr>
                                 <tr><td class="starsriver_mail_content_para"><span>你收到这封邮件，是由于在 {bbname} 获取了新用户注册地址使用了这个邮箱地址。如果你并没有访问过 {bbname}，或没有进行上述操作，请忽略这封邮件。你不需要退订或进行其他进一步的操作。</span></td></tr>
                                 <tr><td class="starsriver_mail_content_para"><span><strong>新用户注册说明：</strong>如果你是 {bbname} 的新用户，或在修改你的注册 Email 时使用了本地址，我们需要对你的地址有效性进行验证以避免垃圾邮件或地址被滥用。</span></td></tr>
                                 <tr><td class="starsriver_mail_content_para_method"><span>点击或复制下面的链接到浏览器中即可进行用户注册，以下链接有效期为3天。过期可以重新请求发送一封新的邮件验证：<br />
                                    <a href="{url}" target="_blank">{url}</a>
                                 </span></td></tr>',


	'add_member_subject' =>	'你被添加成为会员',
	'add_member_message' => '<tr><td class="starsriver_mail_content_titl"><span>你好，{newusername}.</span></td></tr>
                             <tr><td class="starsriver_mail_content_para"><span>这封信是由 {bbname} 发送的。我是 {adminusername} ，{bbname} 的管理者之一。你收到这封邮件，是由于你刚刚被添加成为 {bbname} 的会员，当前 Email 即是我们为你注册的邮箱地址。</span></td></tr>
                             <tr><td class="starsriver_mail_content_para_warning"><span>如果你对 {bbname} 不感兴趣或无意成为会员，请忽略这封邮件。</span></td></tr>
                             <tr><td class="starsriver_mail_content_para_method"><span>
                                从现在起你可以使用以下账户随时登录 {bbname}：<br /><br />
                                &nbsp;&nbsp;&nbsp;[网址]：{siteurl}<br />
                                &nbsp;&nbsp;&nbsp;[用户]：{newusername}<br />
                                &nbsp;&nbsp;&nbsp;[密码]：{newpassword}<br />
                             </span></td></tr>',



	'birthday_subject' => '祝你生日快乐',
	'birthday_message' => '<tr><td class="starsriver_mail_content_titl"><span>祝你生日快乐，{username}.</span></td></tr>
                           <tr><td class="starsriver_mail_content_para"><span>这封信是由 {bbname} 发送的。你收到这封邮件，是由于这个邮箱地址在 {bbname} 被登记为用户邮箱，并且按照你填写的信息，今天是你的生日。很高兴能在此时为你献上一份生日祝福，我谨代表{bbname}管理团队，衷心祝福你生日快乐。</span></td></tr>>
                           <tr><td class="starsriver_mail_content_para_method"><span> 如果你并非 {bbname} 的会员，或今天并非你的生日，可能是有人误用了你的邮址，或错误的填写了生日信息。本邮件不会多次重复发送，请忽略这封邮件。</span></td></tr>',

	'email_to_friend_subject' => '{$_G[member][username]} 推荐给你: $thread[subject]',
	'email_to_friend_message' => '<tr><td class="starsriver_mail_content_titl"><span>你好，</span></td></tr>
                                  <tr><td class="starsriver_mail_content_para"><span>这封信是由 {$_G[setting][bbname]} 的 {$_G[member][username]} 发送的。你收到这封邮件，是由于在 {$_G[member][username]} 通过 {$_G[setting][bbname]} 的“推荐给朋友”功能推荐了如下的内容给你。如果你对此不感兴趣，请忽略这封邮件。你不需要退订或进行其他进一步的操作。</span></td></tr>
                                  <tr><td class="starsriver_mail_content_para_method"><span>
                                        <strong>信件原文：</strong><br />
                                        ----------------------------------------------------------------------<br />
                                        $message
                                        ----------------------------------------------------------------------<br />
                                  </span></td></tr>
                                  <tr><td class="starsriver_mail_content_para_warning"><span>请注意这封信仅仅是由用户使用 “推荐给朋友”发送的，不是网站官方邮件，网站管理团队不会对这类邮件负责。</span></td></tr>',

	'email_to_invite_subject' => '获得邀请码',
	'email_to_invite_message' => '<tr><td class="starsriver_mail_content_titl"><span>你好，$sendtoname.</span></td></tr>
                                  <tr><td class="starsriver_mail_content_para"><span>这封信是由 {$_G[setting][bbname]} 的 {$_G[member][username]} 发送的。你收到这封邮件，是由于 {$_G[member][username]} 通过 {bbname} 的“发送邀请码给朋友”功能推荐了如下的内容给你。如果你对此不感兴趣，请忽略这封邮件。你不需要退订或进行其他进一步的操作。</span></td></tr>
                                  <tr><td class="starsriver_mail_content_para"><span><strong>帐号激活说明：</strong>如果你是 {bbname} 的新用户，或在修改你的注册 Email 时使用了本地址，我们需要对你的地址有效性进行验证以避免垃圾邮件或地址被滥用。</span></td></tr>
                                  <tr><td class="starsriver_mail_content_para_method"><span>
                                        <strong>信件原文：</strong><br />
                                        ----------------------------------------------------------------------<br />
                                        $message
                                        ----------------------------------------------------------------------<br />
                                  </span></td></tr>
                                  <tr><td class="starsriver_mail_content_para_warning"><span>请注意这封信仅仅是由用户使用 “发送邀请码给朋友”发送的，不是网站官方邮件，网站管理团队不会对这类邮件负责。</span></td></tr>',


	'moderate_member_subject' => '用户审核结果通知',
	'moderate_member_message' => '<tr><td class="starsriver_mail_content_titl"><span>你好，{username}.</span></td></tr>
                                  <tr><td class="starsriver_mail_content_para"><span>你收到这封邮件，是由于这个邮箱地址在 {bbname} 被新用户注册时所使用，且管理员设置了对新用户需要进行人工审核，本邮件将通知你提交申请的审核结果。</span></td></tr>
                                  <tr><td class="starsriver_mail_content_para_method"><span>
                                        <strong>注册信息与审核结果：</strong><br />
                                        ----------------------------------------<br />
                                        &nbsp;&nbsp;用户名: {username}<br />
                                        &nbsp;&nbsp;注册时间: {regdate}<br />
                                        &nbsp;&nbsp;提交时间: {submitdate}<br />
                                        &nbsp;&nbsp;提交次数: {submittimes}<br />
                                        &nbsp;&nbsp;注册理由: {message}<br />
                                        ----------------------------------------<br />
                                        &nbsp;&nbsp;审核结果: {modresult}<br />
                                        &nbsp;&nbsp;审核时间: {moddate}<br />
                                        &nbsp;&nbsp;审核管理员: {adminusername}<br />
                                        &nbsp;&nbsp;管理员留言: {remark}<br />
                                  </span></td></tr>
                                  <tr><td class="starsriver_mail_content_para_method"><span>
                                        <strong>审核结果说明：</strong><br />
                                        <ul>
                                            <li><strong>通过: </strong>你的注册已通过审核，你已成为 {bbname} 的正式用户。</li>
                                            <li><strong>否决: </strong>你的注册信息不完整，或未满足我们对新用户的某些要求，你可以根据管理员留言，<a href="home.php?mod=spacecp&ac=profile" target="_blank">完善你的注册信息</a>，然后再次提交。</li>
                                            <li><strong>删除：</strong>你的注册由于与我们的要求偏差较大，或本站的新注册人数已超过预期，申请已被否决。你的帐号已从数据库中删除，将无法再使用其登录或提交再次审核，请你谅解。</li>
                                        </ul>
                                  </span></td></tr>',

	'adv_expiration_subject' =>	'广告将于 {day} 天后到期',
	'adv_expiration_message' =>	'<tr><td class="starsriver_mail_content_titl"><span>你好,</span></td></tr>
                                 <tr><td class="starsriver_mail_content_para"><span>你站点的以下广告将于 {day} 天后到期，请及时处理：{advs}</span></td></tr>',

	'invite_payment_email_message' => '<tr><td class="starsriver_mail_content_para"><span>欢迎你光临{bbname}（{siteurl}），你的订单{orderid}已经支付完成，订单已确认有效。以下是你获得的邀请码：</span></td></tr>
                                       <tr><td class="starsriver_mail_content_para_method"><span>{codetext}</span></td></tr>',
];

?>