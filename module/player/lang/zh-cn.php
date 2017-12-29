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
$lang->player = new stdClass();
$lang->player->name         = '姓名';
$lang->player->org          = '所属单位';
$lang->player->race         = '所属活动';
$lang->player->sex          = '性别';
$lang->player->age          = '年龄';
$lang->player->face         = '照片';
$lang->player->number       = '学号';
$lang->player->idcode       = '身份证号';
$lang->player->phone        = '联系电话';
$lang->player->sdeclare     = '参赛宣言';
$lang->player->audit        = '审核标识';
$lang->player->vote         = '得票数';
$lang->player->state        = '状态';
$lang->player->teacher      = '指导教师';
$lang->player->wname        = '作品名称';
$lang->player->wfile        = '参赛作品';
$lang->player->work         = '参赛作品';


$lang->player->error = new stdClass();
$lang->player->error->nameInvalid          = '请输入姓名！';
$lang->player->error->teacherInvalid       = '填写教师姓名不合法！';
$lang->player->error->noRightCreate        = '无权修改！';
$lang->player->error->typeInvalid          = '无此类型！';
$lang->player->error->idInvalid            = 'ID不存在！';
$lang->player->error->noRightDelete        = '无权删除！';
$lang->player->error->raceInvalid          = '活动无效！';
$lang->player->error->raceNotStart         = '活动未开始！';
$lang->player->error->raceIsEnd            = '活动已结束！';
$lang->player->error->idcodeInvalid        = '请输入身份证号！';
$lang->player->error->sexInvalid           = '请选择性别！';
$lang->player->error->tooYoung             = '输入参赛者年龄太小！';
$lang->player->error->tooOld               = '输入参赛者年龄太大！';
$lang->player->error->ageInvalid           = '请输入年龄！';
$lang->player->error->numberInvalid        = '请输入学号！';
$lang->player->error->orgInvalid           = '请选择单位！';
$lang->player->error->Telinvalid           = '请输入联系方式！';
$lang->player->error->sex_idcode_not_consistent = '性别和身份证不符！';
$lang->player->error->age_idcode_not_consistent = '年龄和身份证不符！';
$lang->player->error->sdeclareInvalid      = '请输入参赛宣言！';
$lang->player->error->auditInvalid         = '审核无效！';
$lang->player->error->noRightScoreAll      ='无权执行该合成操作！';
$lang->player->error->noCancel             = '选手已为初赛阶段！';
$lang->player->error->noState              = '选手已经晋级！';
$lang->player->error->noRightCancel        = '无权执行取消晋级操作！';
$lang->player->error->noRightState         = '无权执行晋级操作！';
$lang->player->error->notAudit             = '选手未通过审核，无法执行晋级操作！';
$lang->player->error->auditInvalid         = '该选手已通过审核！';
$lang->player->error->notYetUpload         = '未上传！';
$lang->player->error->uploadError          = '上传出错！';
$lang->player->error->uploadFormat         = '必须是%s格式文件！';
$lang->player->error->uploadToBig          = '文件太大！';
$lang->player->error->uploadFailed         = '上传失败！';
$lang->player->error->wname                = '请输入作品名称！';
$lang->player->error->reged                = '您已经报名!';
$lang->player->error->ip                   = '一个IP一天只能投一票！';
$lang->player->error->voteOK               = '投票成功！';
$lang->player->error->voteNotYetStart      = '投票还未开始!';
$lang->player->error->voteAlreadyEnd       = '投票已经结束!';
$lang->player->error->voteNotStart         = '投票还未开始，不能执行晋级操作!';
$lang->player->error->voteNotEnd           = '投票还未结束，不能执行晋级操作!';
//race活动类型
$lang->player->stateMap = array
(
 0=>'初赛', 
 1=>'复赛'
);

$lang->player->auditMap = array
(
 0=>'未审核', 
 1=>'已审核'
 );
 
 //性别定义，变量名为：sex
$lang->player->sexMap=array
(
0=>'男',
1=>'女'
);


$lang->player->errorMap = array
(
0=>'文件上传成功',
1=>'超过了文件大小php.ini中即系统设定的大小',
2=>'超过了文件大小MAX_FILE_SIZE 选项指定的值',
3=>'文件只有部分被上传',
4=>'没有文件被上传',
5=>'上传文件大小为0'
);