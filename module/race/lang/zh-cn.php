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
$lang->race = new stdClass();
$lang->race->name         = '活动名称';
$lang->race->rstart        = '活动开始时间';
$lang->race->rend        = '活动结束时间';
$lang->race->vstart        = '投票开始时间';
$lang->race->vend        = '投票结束时间';
$lang->race->acnt        = '访问量';
$lang->race->judger      = '项目评委';
$lang->race->type         = '作品类型';
$lang->race->ext         = '作品扩展名';
$lang->race->size         = '作品大小';

$lang->race->error = new stdClass();
$lang->race->error->nameInvalid           = '活动名称无效';
$lang->race->error->noRightCreate            = '无权新增活动项目';
$lang->race->error->noRightUpdate            = '无权修改活动项目';
$lang->race->error->typeInvalid            = '无此类型';

$lang->race->error->rstart                 ='活动开始时间错误';
$lang->race->error->rend                 ='活动结束时间错误';
$lang->race->error->vstart                 ='投票开始时间错误';
$lang->race->error->vend                 ='投票结束时间错误';
$lang->race->error->rstartGTrend       ='活动开始时间不得晚于结束时间 ';
$lang->race->error->vstartGTvend       ='投票开始时间不得晚于结束时间 ';
