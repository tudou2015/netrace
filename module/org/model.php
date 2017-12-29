<?php
class orgModel extends model
{

    /**
     * Get org info by ID.
     * 
     * @param  int|string    $orgID 
     * @access public
     * @return object|bool
     */
    public function getByID($orgID)
    {
    	 if(!is_numeric($orgID)) return false;
        return $this->dao->select('*')->from(TABLE_ORG)
        		->where('id')->eq($orgID)
        		->andWhere('deleted')->eq(0)
            ->fetch();
    }
	
		private function getData()
		{
    	  $code = $this->post->code;
        if(!is_numeric($code) || strlen($code) > 50) 
				{
     	    throw new Exception($this->lang->org->error->codeInvalid);    	  	
				}

    	  $name = $this->post->name;
        if(!validater::checkString($name, 1, 50)) 
				{
     	    throw new Exception($this->lang->org->error->nameInvalid);    	  	
				}
				
											
    	  $address = $this->post->address;
        if(!validater::checkString($address, 0, 50)) 
				{
     	    throw new Exception($this->lang->org->error->addressInvalid);    	  	
				}


    	  $phone = $this->post->phone;
        if(!validater::checkString($phone, 0, 50)) 
				{
     	    throw new Exception($this->lang->org->error->phoneInvalid);    	  	
				}


    	  $teacher = $this->post->teacher;
        if(!validater::checkString($teacher, 0, 50)) 
				{
     	    throw new Exception($this->lang->org->error->teacherInvalid);    	  	
				}

    	  $data = new stdClass();		
    	  $data->code 			= $code;
	   		$data->name 			= $name;
	   		$data->address 		= $address;
				$data->phone 			= $phone;
				$data->teacher 		= $teacher;
				return $data;
			}
		
    /**
     * Create a org.
     * 
     * @access public
     * @return insertID
     */
    public function create()
    { 
   	
    	  if($this->session->user->type <> 1 || $this->session->user->org <> 1)
    	  {
     	    throw new Exception($this->lang->error->noRightCreate);    	  	
    	  }
    	 
    	 	$data = $this->getData(); 		
							
        $this->dao->insert(TABLE_ORG)->data($data)
            ->exec();
        
        $orgID = $this->dao->lastInsertID();
        
        $this->action->create('org', $orgID, 'create', $data); 
             
        
        
    }

    /**
     * Update a org.
     * 
     * @access public
     * @return void
     */
    public function update($orgID)
    {		
    		$org = $this->getByID($orgID);
	    	if(!$org)// id无效    		
  	  	{
     	    throw new Exception($this->lang->error->idInvalid);    	  	
    		}    	    				

    	  if($this->session->user->type <> 1 || $this->session->user->org <> 1)
    	  {
     	    throw new Exception($this->lang->error->noRightEdit);    	  	
    	  }

				$data = $this->getData();
				
				$log = getLog($org, $data);//数据有没有变更
				if(!$log) return;//没有变动
				
        $this->dao->update(TABLE_ORG)->data($data)
            ->where('id')->eq($orgID)
            ->exec();    
            
    
         $this->action->create('org', $orgID, 'update', $log); 
        
    }


   /**
     * Delete a org.
     * 
     * @access public
     * @return void
     */
    public function deletex($orgID)
    {		    	    		    		    			
     		$org = $this->getByID($orgID);
	    	if(!$org)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}
    		
    	  if($this->session->user->type <> 1 || $this->session->user->org <> 1)
    	  {
    			throw new Exception($this->lang->error->noRightDelete);
    	  }

/*
				//关联下级单位
    	  $row = $this->dao->select('count(*) as num')->from(TABLE_ORG)
    	  ->where('deleted')->eq(0)
    	  ->andWhere('code')->ne($org->code)
    	  ->andWhere("code like '{$org->code}%'")
    	  ->fetch();
    	  
    	  if($row->num)
    	  {
    			throw new Exception($this->lang->error->relate);
    	  }

				//关联用户
    	  $row = $this->dao->select('count(*) as num')->from(TABLE_USER)
    	  ->where('deleted')->eq(0)
    	  ->andWhere('org')->eq($orgID)
    	  ->fetch();
    	  
    	  if($row->num)
    	  {
    			throw new Exception($this->lang->error->relate);
    	  }
				
				//关联专业分配
    	  $row = $this->dao->select('count(*) as num')->from(TABLE_ORGPROF)
    	  ->where('deleted')->eq(0)
    	  ->andWhere('org')->eq($orgID)
    	  ->fetch();
    	  
    	  if($row->num)
    	  {
    			throw new Exception($this->lang->error->relate);
    	  }
    */	  
    		parent::delete(TABLE_ORG, $orgID);            // model.class.php中    		    	
    }
    
     /**
     * Get all org.
     * 
     * @access public
     * @return array
     */
    public function getMap($code = '', $prefix = '')
    { 
    	
			if(!$prefix)
			{
        return $this->dao->select('*')->from(TABLE_ORG)
        		->where('deleted')->eq(0)
        		->beginIF($code)->andWhere('code')->like($code.'%')->fi()
            ->fetchPairs('id', 'name');
			}
			else
			{
        $rows = $this->dao->select('*')->from(TABLE_ORG)
        		->where('deleted')->eq(0)
        		->beginIF($code)->andWhere('code')->like($code.'%')->fi()
            ->fetchAll();

        $ret = array();
        
        foreach($rows as $row)
        {
        	$ret[$row->id] = str_repeat($prefix, strlen($row->code)-3) . $row->name;
        }
        
        return $ret;
      }
    }


     /**
     * 返回教学点
     * 
     * @access public
     * @return array
     $orgcode 操作员所在单位的orgcode，用于选择下级单位
     */
    public function getMap3($orgcode = '')
    {
        return $this->dao->select('id,name')->from(TABLE_ORG)
        		->where('deleted')->eq(0)
        		->beginIF($orgcode)->andWhere('code')->like("$orgcode%")->fi()
        		->andWhere('len(code)=7')
        	//	->andWhere('enable')->eq(1)//启用
        		->orderBy('code')
            ->fetchPairs('id','name');
    }

    /**
     * Get orgs of a org.
     * 
     * @param  array   $orgIDSet 
     * @access public
     * @return array
     */
    public function getAll($query = '', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_ORG)        
            ->where('deleted')->eq(0)
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy('code')
            ->beginIF($pager)->page($pager)->fi()
            ->fetchAll('id');
    }

		/*
		* 判断$myID的单位，是否是$yourID的上级单位，或同一个单位
		*/
		public function isSub($myID, $yourID)
		{			
			$yourOrg = $this->getByID($yourID);

			if(!$yourOrg) return false;			
			
			if($myID == $yourID) return true;//同一单位

			$myOrg = $this->getByID($myID);					
			
			if(!$myOrg) return false;
			
			$myCode = $myOrg->code;//340
			$yourCode = $yourOrg->code;//34000
			
			$myLen = strlen($myCode);//3
			$yourLen = strlen($yourCode);//6
			if($myLen >= $yourLen) return false;//长度长或等于，肯定不是上级单位
			
			$subCode = substr($yourCode,0,$myLen);
			
			if(strcmp($myCode,$subCode) == 0)  return true;
			
			return false;
		}
		
		
		//启用/禁用
    public function enable($orgID)
    {		    	 
     		$org = $this->getByID($orgID);
	    	if(!$org)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}

    		if($orgID == $this->app->user->org)
    		{
    			throw new Exception($this->lang->org->error->denySelfEnable);
    		}

    	  if($this->app->user->type <> 1 || $this->app->user->org <> 1)
    	  {
    	    throw new Exception($this->lang->error->noRightOp);
    	  }

				$enable = $org->enable;
				if($enable) $enable = 0;else $enable = 1;

				$data = new stdClass();
				$data->enable = $enable;
								
 				$log = getLog($org, $data);

	     $this->dao->update(TABLE_ORG)->data($data)        		
            ->where('id')->eq($orgID)
            ->exec();    
            
        $this->loadModel('action')->create('org', $orgID, 'update', $log);         		    						
    }		
}
 