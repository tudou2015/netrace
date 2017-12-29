<?php
/**
 * The user module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: zh-cn.php 2174 2011-10-16 06:16:13Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */

$lang->user = new stdClass();

$lang->user->common        = '用户管理';
$lang->user->browse        = '浏览用户';
$lang->user->create        = "添加用户";

$lang->user->login         = '登录';
$lang->user->edit          = '修改用户';

$lang->user->type         = '用户类型';
$lang->user->org          = '单位';
$lang->user->name         = '用户名';
$lang->user->code         = '验证码';
$lang->user->password     = '密码';
$lang->user->password1    = '密码';
$lang->user->password2    = '请重复密码';
$lang->user->oldPassword  = '原密码';
$lang->user->newPassword1 = '新密码';
$lang->user->newPassword2 = '请重复新密码';
$lang->user->visit        = '访问次数';
$lang->user->ip           = '最后IP';
$lang->user->last         = '最后登录';
$lang->user->org          = '单位';
$lang->user->set					= '设置';

$lang->user->detail       = '查看';
$lang->user->enable       = '启用';

$lang->user->error = new stdClass();
$lang->user->error->orgInvalid           = '单位无效';
$lang->user->error->typeInvalid          = '用户类型无效';
$lang->user->error->nameInvalid          = '用户名无效';

$lang->user->error->passwordNotSame      = "两次密码不等";
$lang->user->error->passwordTooShort     = "密码应该符合规则，长度至少为5位";
$lang->user->error->passwordInvalid      = '密码无效';

$lang->user->error->oldPasswordInvalid   = '原密码无效';
$lang->user->error->oldPasswordIncorrect = '原密码不正确';
$lang->user->error->newPasswordInvalid   = '新密码无效';
$lang->user->error->newPasswordNotSame   = '两次新密码不等';

$lang->user->error->nameDup            	 = '该用户名已经存在';

$lang->user->error->denySelfDelete       = "不允许自己删除自己";
$lang->user->error->denySelfEnable       = "不允许禁用自己";
$lang->user->error->loginFailed          = "登录失败，请检查您的用户名或密码是否填写正确。";
$lang->user->error->codeMismatch         = "验证码不正确。";

$lang->user->error->rightInvalid         = '权限无效';
$lang->user->error->paramInvalid         = "参数无效";

$lang->user->typeMap =array
(
1=>'管理员',
2=>'老师',
3=>'学生'
);


$lang->user->support = '<p>技术支持:</p>';