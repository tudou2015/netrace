<?php
/**
 * The action module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id: zh-cn.php 2224 2011-10-25 07:41:54Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
$lang->action = new stdClass();

$lang->action->common   = '系统日志';
$lang->action->browse   = '浏览日志';
$lang->action->undelete = '还原';

$lang->action->id         = '编号';
$lang->action->org        = '单位';
$lang->action->objectType = '对象类型';
$lang->action->objectID   = '对象ID';
$lang->action->objectName = '对象名称';
$lang->action->actor      = '操作者';
$lang->action->action     = '动作';
$lang->action->date       = '日期';
$lang->action->ip         = 'IP';
$lang->action->comment    = '内容';
$lang->action->trashTips  = '提示：为了保证系统的完整性，禅道系统的删除都是标记删除。';


$lang->action->actionMap = array
(
'login'  =>'登录',
'logout' =>'退出',
'create' =>'新建',
'update' =>'编辑',
'delete' =>'删除',
'audit' => '审核'
);

$lang->action->objectTypeMap = array
(
'user'=>'用户',
'org'=>'单位',
'race'=>'活动',
'player'=>'选手',
'content'=>'内容'
);