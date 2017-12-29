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
$lang->score = new stdClass();
$lang->score->playername         = '选手姓名';
$lang->score->worksname         = '作品名称';
$lang->score->workstype         = '作品类型';
$lang->score->org        = '所属单位';
$lang->score->race        = '所属活动';
$lang->score->sex        = '性别';
$lang->score->age        = '年龄';
$lang->score->face        = '照片';
$lang->score->number        = '学号';
$lang->score->idcode        = '身份证号';
$lang->score->phone        = '联系电话';
$lang->score->sdeclare        = '参赛宣言';
$lang->score->audit        = '审核标识';
$lang->score->vote        = '得票数';
$lang->score->path        = '试听';
$lang->score->score        = '选手总分';



$lang->score->error = new stdClass();
$lang->score->error->nameInvalid           = '活动名称无效';
$lang->score->error->noRightCreate            = '无权修改';
$lang->score->error->typeInvalid            = '无此类型';
$lang->score->error->idInvalid            = 'ID不存在';
$lang->score->error->noRightDelete            = '无权删除';
$lang->score->error->orgInvalid                 ='活动开始时间错误';
$lang->score->error->rend                 ='活动结束时间错误';
$lang->score->error->vstart                 ='投票开始时间错误';
$lang->score->error->vend                 ='投票结束时间错误';
$lang->score->error->rstartGTrend       ='活动开始时间不得晚于结束时间 ';
$lang->score->error->vstartGTvend       ='投票开始时间不得晚于结束时间 ';
//race活动类型
$lang->score->typeMap = array
(
 1=>'文字', 
 2=>'图片',
 3=>'音频', 
 4=>'视频'
);

$lang->score->auditMap = array
(
 0=>'未审核', 
 1=>'已审核'
 );
 
 //性别定义，变量名为：sex
$lang->score->sexMap=array
(
0=>'男',
1=>'女'
);
