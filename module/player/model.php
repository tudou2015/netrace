<?php
/**
 * The model file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: model.php 1939 2011-06-28 15:14:53Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class playerModel extends model
{

    
     /**
     * Get users of a org.
     * 
     * @param  array   $orgIDSet 
     * @access public
     * @return array
     */
    public function getAll($query = '', $order = '', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_PLAYER)
            ->where('deleted')->eq(0)
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($order)->orderBy($order)->fi()            
            ->beginIF($pager)->page($pager)->fi()
            ->fetchAll('id');//检索所有符合条件记录,fetch()检索单条记录
    }
    
    /**
     * Get user info by ID.
     * 
     * @param  int|string    $userID 
     * @access public
     * @return object|bool
     */
    public function getByID($playerID)
    {
    	 if(!is_numeric($playerID)) return false;
        return $this->dao->select('*')->from(TABLE_PLAYER)
        		->where('id')->eq($playerID)
        		->andWhere('deleted')->eq(0)
            ->fetch();
    }
    
     /**
     * Get works info by playerID.
     * 通过参赛项目获取作品列表
     */
   public function getWorksByPlayer($raceID, $playerID = 0)
    {
        $rows = $this->dao->select('*')->from(TABLE_WORKS)
            ->where('race')->eq($raceID)
            ->beginIF($playerID)->andWhere('player')->eq($playerID)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
            
        $result = array();
        //将获取的记录记在一张二维数组里。即一个比赛一个组
        foreach($rows as $row)
        {
        	$result[$row->player] = $row;
        }
     //   a($result);
        return $result;
    }
	 /**
     * Get score info by ID.
     * 
     * @param  int|string    $userID 
     * @access public
     * @return object|bool
     */
    public function getScore($judgeID, $playerID)
    {
    	 if(!is_numeric($playerID)) return false;
        return $this->dao->select('*')->from(TABLE_SCORE)
        		->where('judge')->eq($judgeID)
        		->andwhere('player')->eq($playerID)
        		->andWhere('deleted')->eq(0)
            ->fetch();
    }
    

	
   public function getScoresByPlayer($raceID, $judgeID)
    {
        $rows = $this->dao->select('*')->from(TABLE_SCORE)
            ->where('race')->eq($raceID)
            ->andWhere('judge')->eq($judgeID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
            
        $result = array();
        //将获取的记录记在一张二维数组里。即一个比赛一个组
        foreach($rows as $row)
        {
        	$result[$row->player]=$row;
        }
     //   a($result);
        return $result;
    }	
    
    
   public function getScoreByPlayer($judgeID, $playerID)
    {
        return $this->dao->select('*')->from(TABLE_SCORE)
            ->where('judge')->eq($judgeID)
            ->andWhere('player')->eq($playerID)
            ->andWhere('deleted')->eq(0)
            ->fetch();
    }	
    
    
		private static function getSex($code)
		{				
    		return (substr($code, 16, 1) % 2) ^ 1;//第17位代表性别，奇数为男，偶数为女
		}
		
		private static function getAge($code)
		{
    		$by  = substr($code, 6, 4);
    		$bm = substr($code, 10, 2);
    		$bd   = substr($code, 12, 2);
		
				$cm = date('n');
				$cd = date('j');
				$age = date('Y')- $by - 1;
				
				if ($cm>$bm || ($cm=$bm && $cd>$bd)) $age++;
			
				return $age;
		}
		    
    private function getData($checkOrg,$update = false)
		{
	    	
	    		$race = $this->post->race;
	    	  $org = $this->post->org;
	    	  $number = $this->post->number;
		    	$name = $this->post->name;
					$sex = $this->post->sex;
	    		$idcode = $this->post->idcode;
					$phone = $this->post->phone;	
					$teacher = $this->post->teacher;
					$sdeclare = $this->post->sdeclare;
	    	
	    	//按录入顺序检查字段
	    	if(!$update)
	    	{
					$row_race = $this->race->getByID($race);
					if(!$row_race)
					{
						throw new Exception($this->lang->player->error->raceInvalid);
					}	    	
					
					$now = date('Y-m-d');
					if($now < $row_race->rstart)
					{
						throw new Exception($this->lang->player->error->raceNotStart);
					}
					
					if($row_race->rend < $now)
					{
						throw new Exception($this->lang->player->error->raceIsEnd);
					}		
			
				}
				
				$orgInf = $this->org->getByID($org);
				if(!$orgInf)
				{
						throw new Exception($this->lang->player->error->orgInvalid);
				}
					
				$orgcode = $orgInf->code;
					
				if($checkOrg && !$this->org->isSub($this->session->user->org, $org))
				{
					throw new Exception($this->lang->player->error->orgInvalid);
				}
				if(!is_numeric($number))
	    	{
	    				throw new Exception($this->lang->player->error->numberInvalid);
	    	}
				
	       if(!validater::checkString($name, 1, 255)) 
				{
					throw new Exception($this->lang->player->error->nameInvalid);
				}

				if(!isset($this->lang->player->sexMap[$sex]))
				{
					throw new Exception($this->lang->player->error->sexInvalid);
				}
				
	    	if(strlen($idcode) <> 18 || !validater::checkIDCode($idcode))
	    	{
	    		throw new Exception($this->lang->player->error->idcodeInvalid);
	    	}
	    		    				
	    	if($sex <> self::getSex($idcode))
	    	{
	    			throw new Exception($this->lang->player->error->sex_idcode_not_consistent);
	    	}
	    	
	    	$age = self::getAge($idcode);	    	

	    	if($age < 10)
	    	{
	    		throw new Exception($this->lang->player->error->tooYoung);
	    	}
	    	if($age > 80)
	    	{
	    		throw new Exception($this->lang->player->error->tooOld);
	    	}
	    		    	
	    	if(!validater::checkTelePhone($phone))//位于lib->filter.class.php
	    	{
	    		throw new Exception($this->lang->player->error->Telinvalid);
	    	}				
							
        if(!validater::checkString($teacher,0,255)) 
				{
					throw new Exception($this->lang->player->error->teacherInvalid);
				}

				if(!validater::checkString($sdeclare,1,255))		
				{
						throw new Exception($this->lang->player->error->sdeclareInvalid);
				}        
												
				$data = new stdClass();
	    	if(!$update) 
	    	{
	    		$data->race = $race;			   
			  }
			  $data->org = $org;
			  $data->orgcode = $orgcode;
		   	$data->number = $number;
		   	$data->name = $name;
				$data->sex = $sex;
				$data->age = $age;
	    	$data->idcode = $idcode;
	    	$data->phone = $phone;	
		    $data->teacher = $teacher;	   	     	  
				$data->sdeclare = $sdeclare;

				return $data;
			}
    
      public function score($playerID)
	    {		    	 
	     		$scoreValue = $this->post->score;	
	     		if(!is_numeric($scoreValue) || $scoreValue < 0 || $scoreValue > 100)
	     		{
	     			throw new Exception($this->lang->error->param);
	     		}
	     		
	     		$player = $this->getByID($playerID);
		    	if(!$player || $player->state <> 1)// id无效    		
	  	  	{
	    			throw new Exception($this->lang->error->idInvalid);
	    		}
	
	    	/*
	    	  if($this->session->user->type <> 1)
	    	  {
	    	    throw new Exception($this->lang->player->error->noRightDelete);
	    	  }
	    	  */
	    	$raceID = $player->race;
	    	$race = $this->race->getByID($raceID);
			  
				$judgerMap = explode(',',$race->judger);
				
			//	a($judgerMap);
			//	die();
				$check = in_array($this->session->user->id,$judgerMap);
	
	    
	    	if(!$check)
	    	{
	    	  throw new Exception($this->lang->judge->error->noRightScore);
	    	}

	    
	      $score = $this->getScore($this->session->user->id, $playerID);
				if(!$score)
				{	
					$data = new stdClass();
					$data->race = $player->race;
					$data->judge = $this->session->user->id;					
					$data->player = $playerID;
					$data->score =  $scoreValue;   
					$this->dao->insert(TABLE_SCORE)->data($data)->exec();
					
					$scoreID = $this->dao->lastInsertID();
					
		 	    $this->action->create('score', $scoreID, 'create', $data);   
	    	}
	    	else
	    	{
					$data = new stdClass();
					$data->score 		= $scoreValue;

					$log = getLog($score, $data);//数据有没有变更
					if(!$log) return;//没有变动
	
		   		$this->dao->update(TABLE_SCORE)
	  	  			->data($data)
	    				->where('id')->eq($score->id)
	    				->exec();	    		

		 	    $this->action->create('score', $score->id, 'update', $log);   
	    	}
	    	
	    }
   
      public function scoreAll($raceID)
	    {		    	 
		   
		     if($this->session->user->type <> 1 || $this->session->user->org <> 1)
    	   {
    	    throw new Exception($this->lang->player->error->noRightScoreAll);
    	   } 
			 
			 $race = $this->race->getByID($raceID);
			 if(!$race)
			 {
			   throw new Exception($this->lang->error->param);	
			}
			 
			  $scores = $this->dao->select('player,sum(score) as score')->from(TABLE_SCORE)
					->where('deleted')->eq(0)
					->andWhere('race')->eq($raceID)
					->groupBy('player')
					->fetchAll('player');
					
		   //a($scores);die();
		    
		   $players = $this->dao->select('*')->from(TABLE_PLAYER)
					->where('deleted')->eq(0)
					->andWhere('race')->eq($raceID)
					->andWhere('state')->eq(1)					
					->fetchAll();

				$max = 0;
				foreach($players as $player)
				{
					if($player->vote > $max) $max = $player->vote;
				}

				if(!$max)
				{
							throw new Exception('最大投票数为0!');
				}
								
				
	    	$this->dao->beginTransaction();
	    	
	    	foreach($players as $player)
	    	{
					$score = round($player->vote / $max * 100 * 0.3) + 
					      (isset($scores[$player->id])?$scores[$player->id]->score:0) * 0.7;										
										
	    		$this->dao->update(TABLE_PLAYER)
	    			->set('score')->eq($score)
	    			->where('id')->eq($player->id)
	    			->exec();
	    	}
	    	
	    	$this->dao->commit();
	    }    
	    
	    
	    /*
	    通用的上传例程
	    */	    
	    private function upload($id, $desc, $exts, $maxSize, $raceID, $playerID, $catagory, $name)
	    {
		 			if(!isset($_FILES[$id]))
					{
						//没传文件报错
						throw new Exception($desc.$this->lang->player->error->notYetUpload);				
					}
					
					$f = $_FILES[$id];
					if($f['error']) 
					{
						a($f);
						throw new Exception($desc.$this->lang->player->error->uploadError);				
					}
								
		  		$a = explode('.',$f['name']);	
		  		if(count($a)<>2 || !in_array(strtolower($a[1]), $exts)) 
		  		{
		  			throw new Exception($desc.sprintf($this->lang->player->error->uploadFormat,implode(',',$exts)));		  			
					}
					$ext = strtolower($a[1]);
					
					if($f['size']>$maxSize)
					{
						throw new Exception($desc.$this->lang->player->error->uploadToBig);
						
					}			
					
					$src = $f['tmp_name'];
					
					$pathFix = $this->pathFix;
					$path = $this->basePath . 'www' . $pathFix . 'upload'. $pathFix . $raceID . $pathFix . $catagory;			
					$dst = $path . $pathFix . $playerID . '-' . date('YmdHis') . '-' . rand() . '.' . $ext;
								
					createFolder($path);
					
					copy($src, $dst);  
					
					//上传成功
					if(!file_exists($dst))
					{
						throw new Exception($name . $this->lang->player->error->uploadFailed);
					}
					
					return $dst;
	}
	    
	    
	  private function uploadFace($raceID, $playerID)
	  {
	  	$player = $this->getByID($playerID);
	  	
	  	//private function upload($id, $desc, $exts, $maxSize, $raceID, $playerID, $catagory, $name)
			$dst = $this->upload('face', $this->lang->player->face, $this->config->faceExts, $this->config->faceSize, $raceID, $playerID, 'face', $this->lang->player->face);    	
			
			$this->dao->update(TABLE_PLAYER)
						->set('face')->eq($dst)
						->where('id')->eq($playerID)
						->exec();
												
			return $dst;
	  }
	  
    /**
     * Create a user.
     * 
     * @access public
     * @return insertID
     */
     
    public function create($checkOrg)
	 {
      $data = $this->getData($checkOrg);
    	if($checkOrg) 
    		$data->audit = 1;    	
    	else
    		$data->audit = 0; //guest默认未审核   	
    	$data->face = '';
    	
    	//检查是否已报名，相同的活动下，身份号一致
    	$row = $this->dao->select('count(*) as num')->from(TABLE_PLAYER)
    		->where('race')->eq($data->race)
    		->andWhere('idcode')->eq($data->idcode)
    		->andWhere('deleted')->eq(0)
    		->fetch();
    	    	
    	if($row->num)
    	{
    		throw new Exception($this->lang->player->error->reged);
    	}	
    	
    	$this->dao->beginTransaction();
    	
      $this->dao->insert(TABLE_PLAYER)->data($data)
           ->exec();
      
      $playerID = $this->dao->lastInsertID();
      
      //uploadFace($raceID, $playerID)      	
     	$data->face = $this->uploadFace($data->race, $playerID);     	
						     	      
      //uploadWork($raceID, $playerID, $wnameID, $wfileID)
      $work = $this->uploadWork($data->race, $playerID, 'wname', 'wfile');
      
      $data->type = $work->type;
      $data->name = $work->name;
      $data->path = $work->path;
      
      $this->action->create('player', $playerID, 'create', $data);     	

      $this->dao->commit();
			      
	 }
	
    /**
     * Update a blog.
     * 
     * @access public
     * @return void
     */
   
    public function update($playerID)
    {	
    	$player = $this->getByID($playerID);
    	if(!$player)// id无效    		
  	  {
    		throw new Exception($this->lang->error->idInvalid);
    	}	
	    if(!$this->org->isSub($this->session->user->org, $player->org))
    	{
    	   throw new Exception($this->lang->error->noRightEdit);
    	}
    	
    	$data = $this->getData(true,true);
    	   							    	   	 	     	
			$log = getLog($player, $data);//比较修改了哪些内容，并将修改处记录日志。一般新建记录，删除记录不需要getLog
			if($log) 
			{			
	      $this->dao->update(TABLE_PLAYER)->data($data)
	            ->where('id')->eq($playerID)
	            ->exec(); 
	            
	      $this->action->create('player', $playerID, 'update', $log);    			
     }
      

			//有照片，再执行
			if(isset($_FILES['face']['error']))
			{
				$error = $_FILES['face']['error'];
				switch($error) 
				{
					case 0://成功
						$data = new stdClass();
						$data->old_face = $player->face;			 
					 	$data->new_face = $this->uploadFace($player->race, $player->id);				 				 	
					 	$this->action->create('player', $playerID, 'update', $data);    						 	
						//unlink($data->old_face);
						break;
					case 1:
					case 2:
					case 3:
					case 5://出错
						throw new Exception($this->lang->player->face . $this->lang->player->errorMap[$error]);
						break;
					case 4://没有上传,忽略
						break;
				}
				
			}      
				
				//开始处理作品上传
				
				$data = new stdClass();

      //检查作品名称
				$wname = $this->post->wname;
				if(!validater::checkString($wname, 1, 255))
				{
					throw new Exception($this->lang->player->error->wname);
				}
				$data->name = $wname;
				
			//检查是否上传新文件，表明需要更新
			if(isset($_FILES['wfile']['error']))
			{
				$error = $_FILES['wfile']['error'];
				switch($error)
				{
					case 0:
						$raceID = $player->race;
						$race = $this->race->getByID($raceID);
						$data->path = $this->upload('wfile', $this->lang->player->work, explode(',', $race->ext), $race->size * 1024 * 1024, $raceID, $playerID, 'work', $this->lang->player->work);
						break;
					case 1:
					case 2:
					case 3:
					case 5:
						throw new Exception($this->lang->player->work.$this->lang->player->errorMap[$error]);
						break;
					case 4://没有上传,忽略
						break;					
				}
			}
			
			$works = $this->getWorksByPlayer($player->race, $player->id);
			$work = $works[$player->id];
			
			$log = getLog($work, $data);
			if(!$log) return;
								
				$this->dao->update(TABLE_WORKS)
						->data($data)
            ->where('id')->eq($work->id)
            ->exec();   												
				      
			 	$this->action->create('player', $playerID, 'update', $log);    							
    }	
   
    
   /**
     * Delete a user.
     * 
     * @access public
     * @return void
     */
    public function deletex($playerID)
    {		    	 
     		$player = $this->getByID($playerID);
	    	if(!$player)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}
	    		
	    	//检查选手是否为登陆管理员本单位或下级单位学员	
		    if(!$this->org->isSub($this->session->user->org, $player->org))
	    	{
	    	   throw new Exception($this->lang->error->noRightDelete);
	    	}
 	 	  		
    		parent::delete(TABLE_PLAYER, $playerID);            // model.class.php中    		    					
    }
    
      /**
     * audit a player.
     * 
     * @access public
     * @return void
     */
    public function auditx($playerID)
    {		    	 
     		$player = $this->getByID($playerID);
	    	if(!$player)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}
    		
    		
		    if(!$this->org->isSub($this->session->user->org, $player->org))
	    	{
	    	   throw new Exception($this->lang->error->noRightOp);
	    	}
    	  
    	  $audit = $player->audit;
    	  if($audit <> 0)
    	  {
    	  	throw new Exception($this->lang->player->error->auditInvalid);
    	  }
	    	     		
	    	$audit = 1; 		    	
				$data = new stdClass();
				$data->audit 	= $audit;
	    	
	
				$log = getLog($player, $data);//数据有没有变更
				if(!$log) return;//没有变动
	
	   		$this->dao->update(TABLE_PLAYER)
	    			->data($data)
	    			->where('id')->eq($playerID)
	    			->exec();
				
	 	    $this->action->create('player', $player->id, 'audit', $log);   		
    	  		    					
    }
   /**
     * promotion a player.
     * 
     * @access public
     * @return void
     */
    public function statex($playerID)
    {		    	 
     		$player = $this->getByID($playerID);
	    	if(!$player)// id无效    		
  	  	{
    			throw new Exception($this->lang->player->error->idInvalid);
    		}
        $race = $this->race->getByID($player->race);
        $now = date('Y-m-d');
				if($now < $race->vstart)
				{
					throw new Exception($this->lang->player->error->voteNotStart);
				}
				if($now < $race->vend)
				{
					throw new Exception($this->lang->player->error->voteNotEnd);
				}
					
    	  if($this->session->user->type <> 1/* || $this->session->user->org <> 1*/)
    	  {
    	    throw new Exception($this->lang->player->error->noRightState);
    	  }
    	  $audit = $player->audit;
    	  if($audit <> 1)
    	  {
    	  	throw new Exception($this->lang->player->error->notAudit);
    	  }
    	  $state = $player->state;
    	 	if($state <> 0)
    	  {
    	    throw new Exception($this->lang->player->error->noState);
    	  }
    		
	    	$state = 1; 		    	
				$data = new stdClass();
				$data->state 		= $state;
	    	
	
				$log = getLog($player, $data);//数据有没有变更
				if(!$log) return;//没有变动
	
	   		$this->dao->update(TABLE_PLAYER)
	    			->data($data)
	    			->where('id')->eq($playerID)
	    			->exec();
				
	 	    $this->action->create('player', $player->id, 'state', $log);   		
    	  		    					
    }
    
     public function cancelstatex($playerID)
    {		    	 
     		$player = $this->getByID($playerID);
	    	if(!$player)// id无效    		
  	  	{
    			throw new Exception($this->lang->player->error->idInvalid);
    		}

    	  if($this->session->user->type <> 1|| $this->session->user->org <> 1)
    	  {
    	    throw new Exception($this->lang->player->error->noRightCancel);
    	  }
    	  $state = $player->state;
    	 	if($state <> 1)
    	  {
    	    throw new Exception($this->lang->player->error->noCancel);
    	  }
      	$state = 0; 		    	
			  $data = new stdClass();
		   	$data->state 		= $state;
    	

			 $log = getLog($player, $data);//数据有没有变更
			 if(!$log) return;//没有变动

   	 	 $this->dao->update(TABLE_PLAYER)
    			->data($data)
    			->where('id')->eq($playerID)
    			->exec();
			
 	     $this->action->create('player', $player->id, 'state', $log);   		
    	  		    					
    }

    /**
     * Get all user.
     * 
     * @access public
     * @return array
     */
    public function getMap($key = 'id', $value = 'name', $all = false)
    {
        return $this->dao->select('*')->from(TABLE_PLAYER)
        		->beginIF(!$all)->where('deleted')->eq(0)->fi()
            ->fetchPairs($key,$value);
    }

	
		/*
		上传作品
		*/
    private function uploadWork($raceID, $playerID, $wnameID, $wfileID)
    {
				$wname = $this->post->$wnameID;
				if(!validater::checkString($wname, 1, 255))
				{
					throw new Exception($this->lang->player->error->wname);
				}
				
				$race = $this->race->getByID($raceID);
				
				//upload($id, $desc, $exts, $maxSize, $raceID, $playerID, $catagory)
				$dst = $this->upload($wfileID, $this->lang->player->work, explode(',', $race->ext), $race->size * 1024 * 1024, $raceID, $playerID, 'work', $this->lang->player->work);
				
				$data = new stdClass();
				$data->race = $raceID;
				$data->player = $playerID;
				$data->type = $race->type;
				$data->name = $wname;
				$data->path = $dst;
				
				$this->dao->insert(TABLE_WORKS)
						->data($data)
						->exec();
												
				return $data;
		}	
		
		public function vote($playerID)
		{
			$player = $this->getByID($playerID);
			if(!$player)
			{
				throw new Exception($this->lang->error->idInvalid);
			}
			
			//检查是否允许投票
			$race = $this->race->getByID($player->race);
			$now = date('Y-m-d');
			if($now < $race->vstart)
			{
					throw new Exception($this->lang->player->error->voteNotYetStart);
			}
			
			if($race->vend < $now)
			{
				throw new Exception($this->lang->player->error->voteAlreadyEnd);
			}
						
			//查询是否投过票
			$ip = $this->server->remote_addr;
			$row = $this->dao->select('*')->from(TABLE_IP)
				->where('ip')->eq($ip)
				->fetch();
			if($row)	
			{
				 if($row->date == date('Y-m-d'))//已投过票
				 {
					throw new Exception($this->lang->player->error->ip);
				 }
				 else
				 {
						$this->dao->update(TABLE_IP)
							->set('date')->eq(date('Y-m-d'))
							->where('id')->eq($row->id)
							->exec();
				 }
			}
			else
			{
				$data = new stdClass();
				$data->ip = $ip;
				$data->date = date('Y-m-d');
				$this->dao->insert(TABLE_IP)
					->data($data)
					->exec();
			}

			//投票
			$this->dao->update(TABLE_PLAYER)
				->set('vote = vote + 1')
				->where('id')->eq($playerID)
				->exec();			

			//按活动投票记录
			$row = $this->dao->select('*')->from(TABLE_VOTE)
				->where('race')->eq($player->race)
				->andWhere('date')->eq(date('Y-m-d'))
				->fetch();
			if($row)
			{
				$this->dao->update(TABLE_VOTE)
					->set('vote = vote + 1')
					->where('id')->eq($row->id)
					->exec();
			}
			else
			{
				$data = new stdClass();
				$data->race = $player->race;
				$data->date = date('Y-m-d');
				$data->vote = 1;
				$this->dao->insert(TABLE_VOTE)
					->data($data)
					->exec();
			}
		}
}

