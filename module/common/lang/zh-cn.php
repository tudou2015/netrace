<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: zh-cn.php 2246 2011-10-27 08:54:27Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
//$lang = new stdClass();

/* 时间格式设置。*/
define('DT_DATETIME1',  'Y-m-d H:i:s');
define('DT_DATETIME2',  'y-m-d H:i');
define('DT_MONTHTIME1', 'n/d H:i');
define('DT_MONTHTIME2', 'n月d日 H:i');
define('DT_DATE1',     'Y-m-d');
define('DT_DATE2',     'Ymd');
define('DT_DATE3',     'Y年m月d日');
define('DT_DATE4',     'n月j日');
define('DT_TIME1',     'H:i:s');
define('DT_TIME2',     'H:i');

$lang->check        = '√';

$lang->reload   = '刷新';
$lang->editPass = '修改密码';

$lang->save     = '保存';
$lang->cancel   = '取消';

$lang->select   = '请选择';

$lang->add      = '新建';
$lang->edit     = '修改';
$lang->delete   = '删除';
$lang->search		= '检索';
$lang->detail		= '详情';

$lang->perPage  = '每页';
$lang->row      = '条';
$lang->count		= '共';

$lang->confrimDelete = '确认删除吗？';

/* 错误提示信息。*/
$lang->error = new stdClass();
$lang->error->noRightCreate = '无权创建';
$lang->error->noRightEdit   = '无权编辑';
$lang->error->noRightDelete = '无权删除';
$lang->error->noRightOp     = '无权操作';
$lang->error->idInvalid     = 'id无效';
$lang->confirmDelete        = "您确认删除吗？";
$lang->error->param         = '参数无效';
$lang->error->relate        = '关联数据不允许删除';
$lang->error->fatal					= '发生严重错误!';
$lang->error->typeInvalid   = '选择作品类型不存在！';

$lang->checkAll      = '全选';

$lang->id ='编号';

//race活动类型
$lang->typeMap = array
(
 1=>'文字', 
 2=>'图片',
 3=>'音频', 
 4=>'视频'
);

$lang->title      = '电大学生竞赛类活动平台';

$lang->copyright  = 'Copyright &copy; 2015 安徽广播电视大学';