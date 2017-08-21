<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
$default_tpl = <<<EOF
<div style="background-color:#fbfbfb;">
    <table width="760" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:760px;border-spacing:0;padding:0;margin:0 auto;" class="ke-zeroborder">
        <tbody>
            <tr>
                <td colspan="2" bgcolor="#3EA9F5" style="height:8px;">
                </td>
            </tr>
            <tr>
                <td height="62" style="padding-left:15px;">
                    <a href="http://www.corethink.cn" target="_blank" title="CoreThink"> <img src="http://www.corethink.cn/Uploads/2015-08-03/55bee01014b4d.png" border="0" width="125" height="33" /> </a> 
                </td>
                <td align="right" style="padding-right:15px;">
                    <a style="font-size:14px;font-weight:700;text-decoration:none;" href="http://www.corethink.cn" target="_blank" title="首页">首页</a> 
                    <a style="font-size:14px;font-weight:700;text-decoration:none;" href="http://www.corethink.cn/cms/cate/21.html" target="_blank" title="产品">产品</a> 
                    <a style="font-size:14px;font-weight:700;text-decoration:none;" href="http://www.corethink.cn/appstore.html" target="_blank" title="云商店">云商店</a> 
                    <a style="font-size:14px;font-weight:700;text-decoration:none;" href="http://www.corethink.cn/manual.html" target="_blank" title="文档">文档</a> 
                    <a style="font-size:14px;font-weight:700;text-decoration:none;" href="http://www.corethink.cn/cms/category/detail/id/11.html" target="_blank" title="联系我们">联系我们</a>
                    <a style="font-size:14px;font-weight:700;text-decoration:none;" href="http://www.corethink.cn/forum.html" target="_blank" title="常见问题">常见问题</a> 
                </td>
            </tr>
            <tr>
                <td bgcolor="#FFFFFF" colspan="2">
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td style="padding:15px;">
                                    [MAILBODY]
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br />
                </td>
            </tr>
        </tbody>
    </table>
    <table width="760" border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:760px;border-spacing:0;padding:0;margin:0 auto;" class="ke-zeroborder">
        <tbody>
            <tr>
                <td valign="top" style="line-height:24px;padding:24px;" bgcolor="#4f4f4f">
                    <p style="font-size:12px;color:#fff;margin:0;">
                        为了您能够正常收到来自Wiera的最新信息和会员邮件，请将<a style="text-decoration:underline;color:#6cb4ff;cursor:pointer;">noreply@corethink.cn</a>添加为您的联系人。
                    </p>
                    <p style="font-size:14px;color:#fff;margin:20px 0 0 0;">
                        若您有任何疑问，请及时联系我们的客服。
                    </p>
                    <p style="font-size:12px;color:#fff;margin:0;">
                        客 服 QQ：<strong style="color:#ff789c;font-family:Tahoma;">598821125</strong> 
                    </p>
                    <p style="font-size:12px;color:#fff;margin:0;padding-bottom:20px;">
                        客服热线：<strong style="color:#ff789c;font-family:Tahoma;">150-0517-3785</strong> (周一至周六，9:00 - 18:00)
                    </p>
                    <p style="font-size:12px;color:#fff;margin:0;">
                        更多最新讯息请关注官方微信： <span style="color:#2a85bf;font-weight:700;font-family:Tahoma;">CoreThink</span> 
                    </p>
                </td>
                <td style="padding:24px 20px 22px 0;" bgcolor="#4f4f4f">
                    <img src="http://www.corethink.cn/Uploads/2016-01-14/569797565addc.jpg" width="150" height="150" border="0" /> 
                </td>
                <td width="17" style="font-size:0;">
                </td>
            </tr>
            <tr style="height:50px;" bgcolor="#fbfbfb">
                <td colspan="3">
                </td>
            </tr>
        </tbody>
    </table>
</div>
EOF;

return array(
    'status'=>array(
        'title'=>'是否开启邮件:',
        'type'=>'radio',
        'options'=>array(
            '1'=>'开启',
            '0'=>'关闭',
        ),
        'value'=>'1',
    ),
    'group'=>array(
        'type'=>'group',
        'options'=>array(
            'server'=>array(
                'title'=>'发信设置',
                'options'=>array(
                    'MAIL_SMTP_TYPE'=>array(
                        'title'=>'邮件发信类型：',
                        'type'=>'select',
                        'options'=>array(
                            'mail'     => 'PHP自带函数发送',
                            'smtp'     => 'SMTP服务器发送',
                            'sendmail' => 'sendmail程序发送',
                            'qmail'    => 'qmail程序发送',
                        ),
                        'value'=>'mail',
                        'tip'=>'邮件发信类型',
                    ),
                    'MAIL_SMTP_SECURE'=>array(
                        'title'=>'安全协议类型：',
                        'type'=>'select',
                        'options'=>array(
                            '0'   => ' 不使用 ',
                            'ssl' => 'SSL',
                            'tls' => 'TLS',
                        ),
                        'value'=>'0',
                        'tip'=>'安全协议类型',
                    ),
                    'MAIL_SMTP_PORT'=>array(
                        'title'=>'SMTP服务器端口：',
                        'type'=>'text',
                        'value'=>'25',
                        'tip'=>'普通端口一般为25，SSL端口为465，TLS端口为587',
                    ),
                    'MAIL_SMTP_HOST'=>array(
                        'title'=>'SMTP服务器地址：',
                        'type'=>'text',
                        'value'=>'smtp.qq.com',
                        'tip'=>'邮箱服务器名称[如：smtp.qq.com]',
                    ),
                    'MAIL_SMTP_USER'=>array(
                        'title'=>'SMTP服务器用户名：',
                        'type'=>'text',
                        'value'=>'',
                        'tip'=>'SMTP服务器用户名',
                    ),
                    'MAIL_SMTP_PASS'=>array(
                        'title'=>'SMTP服务器密码：',
                        'type'=>'password',
                        'value'=>'',
                        'tip'=>'SMTP服务器密码',
                    ),
                ),
             ),
            'template'=>array(
                'title'=>'发信模版',
                'options'=>array(
                    'default'=>array(
                        'title'=>'默认发信模版：',
                        'type'=>'kindeditor',
                        'value'=>$default_tpl,
                        'tip'=>'默认发信模版',
                    )
                )
            )
        )
    )
);
