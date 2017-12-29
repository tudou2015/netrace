<?php
/**
 * The org module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     org
 * @version     $Id: zh-cn.php 2174 2011-10-16 06:16:13Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
$lang->content = new stdClass();

$lang->content->id           = '编号';
$lang->content->race         = '活动';
$lang->content->title        = '标题';
$lang->content->body         = '内容';
$lang->content->ptime        = '发布时间';
$lang->content->acnt         = '访问次数';
$lang->content->type         = '类型';
$lang->content->publish      = '发布标识';
$lang->content->topx          = '是否置顶';

$lang->content->error = new stdClass();
$lang->content->error->raceInvalid = '活动无效!';
$lang->content->error->typeInvalid = '类型无效!';

$lang->content->typeMap = array
(
1=>'新闻',
2=>'公告'
);

$lang->content->publishMap = array
(
0=>'',
1=>'已发布'
);

$lang->content->topMap = array
(
0=>' ',
1=>'是'
);