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
$lang->judge = new stdClass();
$lang->judge->playername         = '选手姓名';
$lang->judge->worksname         = '作品名称';
$lang->judge->workstype         = '作品类型';
$lang->judge->org        = '所属单位';
$lang->judge->race        = '所属活动';
$lang->judge->sex        = '性别';
$lang->judge->age        = '年龄';
$lang->judge->face        = '照片';
$lang->judge->number        = '学号';
$lang->judge->idcode        = '身份证号';
$lang->judge->phone        = '联系电话';
$lang->judge->sdeclare        = '参赛宣言';
$lang->judge->audit        = '审核标识';
$lang->judge->vote        = '得票数';
$lang->judge->path        = '试听';
$lang->judge->score        = '分数';
$lang->judge->judge        = '评委';
$lang->judge->xh           = '序号';

$lang->judge->error = new stdClass();
$lang->judge->error->nameInvalid           = '活动名称无效';

$lang->judge->error->noRightScore     ='非本项目评委，无权评分';
//race活动类型
$lang->judge->typeMap = array
(
 1=>'文字', 
 2=>'图片',
 3=>'音频', 
 4=>'视频'
);
