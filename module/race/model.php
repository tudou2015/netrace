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
class raceModel extends model
{

    
     /**
     * Get users of a org.
     * 
     * @param  array   $orgIDSet 
     * @access public
     * @return array
     */
    public function getAll($query = '', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_RACE)
            ->where('deleted')->eq(0)
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF($pager)->page($pager)->fi()            
            ->fetchAll('id');
    }
    
    /**
     * Get user info by ID.
     * 
     * @param  int|string    $userID 
     * @access public
     * @return object|bool
     */
    public function getByID($raceID)
    {
    	 if(!is_numeric($raceID)) return false;
        return $this->dao->select('*')->from(TABLE_RACE)
        		->where('id')->eq($raceID)
        		->andWhere('deleted')->eq(0)
            ->fetch();
    }
	
    /**
     * Create a user.
     * 
     * @access public
     * @return insertID
     */
     
    private function getData()
		{
    	$name = $this->post->name;			
    	$rstart = $this->post->rstart;
    	$rend = $this->post->rend;
    	$vstart = $this->post->vstart;
    	$vend = $this->post->vend;
    	$type = $this->post->type;
    	$ext = $this->post->ext;
    	$size = $this->post->size;
    	
    	if(!validater::checkString($name, 1, 255)) 
			{
				throw new Exception($this->lang->race->error->nameInvalid);
			}
    	if(!validater::checkDate($rstart))
    	{
    		throw new Exception($this->lang->race->error->rstart);    		
    	}
    	if(!validater::checkDate($rend))
    	{
    		throw new Exception($this->lang->race->error->rend);    		
    	}
    	if(!validater::checkDate($vstart))
    	{
    		throw new Exception($this->lang->race->error->vstart);    		
    	}
    	
    	if(!validater::checkDate($vend))
    	{
    		throw new Exception($this->lang->race->error->vend);    		
    	}
    	
    	if($rstart > $rend)
    	{
    		throw new Exception($this->lang->race->error->rstartGTrend);    		
    	}
    	if($vstart > $vend)
    	{
    		throw new Exception($this->lang->race->error->vstartGTvend);    		
    	}
    	
    	
			if(!isset($this->lang->typeMap[$type]))
			{
				throw new Exception($this->lang->error->typeInvalid);
			}

      $data = new stdClass();
	   	$data->name = $name;
	   	$data->rstart = $rstart;
    	$data->rend = $rend;
    	$data->vstart = $vstart;
    	$data->vend = $vend;
    	$data->type = $type;
    	$data->ext = $ext;
    	$data->size = $size;
    	return $data;
			}
     
    public function create()
	{
    	if($this->session->user->type <> 1||$this->session->user->org <> 1)
    	{
    	  throw new Exception($this->lang->error->noRightCreate);    	  	
    	}
		
		 $data = $this->getData();
     $this->dao->insert(TABLE_RACE)->data($data)
          ->exec();
            
     $raceID = $this->dao->lastInsertID();
        
     $this->action->create('race', $raceID, 'create', $data);     		
	}
	
    /**
     * Update a blog.
     * 
     * @access public
     * @return void
     */
   
   public function update($raceID)
    {		
    	if($this->session->user->type <> 1||$this->session->user->org <> 1)
    	{
    	  throw new Exception($this->lang->race->error->noRightUpdate);    	  	
    	}
    	$race = $this->getByID($raceID);
	    if(!$race)// id无效    		
  	  {
    		throw new Exception($this->lang->error->idInvalid);
    	}
    	
    	$data = $this->getData();
			$log = getLog($race, $data);//比较修改了哪些内容，并将修改处记录日志。一般新建记录，删除记录不需要getLog
			if(!$log) return;
			
      $this->dao->update(TABLE_RACE)->data($data)
            ->where('id')->eq($raceID)
            ->exec();    
            
      $this->action->create('race', $raceID, 'update', $log);    

			//更新缓存      
     // $this->loadModel('common')->getSystem(true);        	
    }	
   
    
   /**
     * Delete a user.
     * 
     * @access public
     * @return void
     */
    public function deletex($raceID)
    {		    	 
     		$race = $this->getByID($raceID);
	    	if(!$race)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}

    	  if($this->session->user->type <> 1||$this->session->user->org <>1)
    	  {
    	    throw new Exception($this->lang->error->noRightDelete);
    	  }
    	 	
    	 	$row = $this->dao->select('count(*) as num')->from(TABLE_PLAYER)
    	  ->where('deleted')->eq(0)
    	  ->andWhere('race')->eq($raceID)
    	  ->fetch();
    	  
    	  if($row->num)
    	  {
    			throw new Exception($this->lang->error->relate);
    	  }  		
    		parent::delete(TABLE_RACE, $raceID);            // model.class.php中    		    					
    }
    
   
    /**
     * Get all user.
     * 
     * @access public
     * @return array
     */
    public function getMap($key = 'id', $value = 'name', $all = false)
    {
        return $this->dao->select('*')->from(TABLE_RACE)
        		->beginIF(!$all)->where('deleted')->eq(0)->fi()
            ->fetchPairs($key,$value);
    }


	 public function setJudge($raceID)
	 {
    	if($this->session->user->type <> 1||$this->session->user->org <> 1)
    	{
    	  throw new Exception($this->lang->error->noRightOp);    	  	
    	}

      
				$race = $this->race->getByID($raceID);
				if(!$race) 
				{
					throw new Exception($this->lang->error->idInvalid);
				}
				 
    	
				 $userID = $this->post->userID;
				 $check = $this->post->check;
				 
				 $user = $this->user->getByID($userID);
				 if(!$user)
				 {
				 		throw new Exception($this->lang->error->param);
				}
				
        if($check <> "true" && $check <> "false")
				{
     	    throw new Exception($this->lang->error->param);    	  	
				}
    	  if($check == "true") $deleted = 0;else $deleted = 1;

				 				 
				 
				 $judgerMap = explode(',',trim($race->judger));
				 				 
				 
				 $judgerMap = array_flip($judgerMap);
				 
				 if(isset($judgerMap[''])) unset($judgerMap['']);
				 
				 if(!$deleted)
				 {
				 		$judgerMap[$userID] = count($judgerMap);				 		
				 }
				 else				 
				 {
				 	unset($judgerMap[$userID]);
			  	}			  	
			  	
			  	$judger = implode(',',array_flip($judgerMap));
			  	
			  	$data = new stdClass();
			  	$data->judger = $judger;

					$log = getLog($race, $data);//比较修改了哪些内容，并将修改处记录日志。一般新建记录，删除记录不需要getLog
					if(!$log) return;
						  	
			  	
     			 $this->dao->update(TABLE_RACE)->data($data)
            ->where('id')->eq($raceID)
            ->exec();    
            
     		 $this->action->create('race', $raceID, 'update', $log);   

	}
	
	//增加访问计数
	public function incAcnt($raceID)
	{
		$this->dao->update(TABLE_RACE)
			->set('acnt=acnt+1')
			->where('id')->eq($raceID)
			->exec();
	}
}

