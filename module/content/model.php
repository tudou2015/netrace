<?php
class contentModel extends model
{

    
	
		private function getData()
		{    	  
    	  $type = $this->post->type;
    	  if(!isset($this->lang->content->typeMap[$type]))
				{
     	    throw new Exception($this->lang->content->error->typeInvalid);    	  	
				}

    	  $title = $this->post->title;
        if(!validater::checkString($title, 1, 255)) 
				{
     	    throw new Exception($this->lang->content->error->nameInvalid);    	  	
				}														
				
	     $body = base64_encode($this->post->body);
	     	     	
    	  $data = new stdClass();		
    	 	//$data->race       = $race;
				$data->type 			= $type;     
    	  $data->title 			= $title;  		
				$data->body   		= $body;
				return $data;
			}
		

    public function create($raceID)
    { 
   	
    	  if($this->session->user->type <> 1 || $this->session->user->org <> 1)
    	  {
     	    throw new Exception($this->lang->error->noRightCreate);    	  	
    	  }

				$race = $this->race->getByID($raceID);
				if(!$race)
				{
					throw new Exception($this->lang->content->error->raceInvalid);
				}				
    	 
    	 	$data = $this->getData(); 
    	 	$data->race = $raceID;	
    	  $data->ptime 	= curDate();
		
							
        $this->dao->insert(TABLE_CONTENT)->data($data)
            ->exec();
            
        $contentID = $this->dao->lastInsertID();
        
        $this->action->create('content', $contentID, 'create', $data); 
             
        
        
    }

    public function getByID($contentID)
    {
    	 if(!is_numeric($contentID)) return false;
        return $this->dao->select('*')->from(TABLE_CONTENT)
        		->where('id')->eq($contentID)
        		->andWhere('deleted')->eq(0)
            ->fetch();
    }
    
     public function deletex($contentID)
    {		    	    		    		    			
     		$content = $this->getByID($contentID);
	    	if(!$content)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}
    		
    	  if($this->session->user->type <> 1 || $this->session->user->org <> 1)
    	  {
    			throw new Exception($this->lang->error->noRightDelete);
    	  }

    		parent::delete(TABLE_CONTENT, $contentID);            // model.class.php中    		    	
    }
     public function publish($contentID)
    {		
    		$content = $this->getByID($contentID);
	    	if(!$content)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}
    		
    	  if($this->session->user->type <> 1 || $this->session->user->org <> 1)
    	  {
    			throw new Exception($this->lang->error->noRight);
    	  }
    
       //生成本次要修改的数据（字段）
       $data = new stdClass();
       $data->publish 	= 1;       
       //对比要修改的字段值和原记录中的字段值，返回修改日志(原值多少，新值多少）,相同的字段将从data中删除
       $log = getLog($content, $data);//调用参数，原记录在前，新数据在后
			 if(!$log) return;//如果两者相同,没有任何改动，返回的日志是空，说明不需要update，直接返回
			
				
				//否则data只包含需要update的字段，则执行udpate
        $this->dao->update(TABLE_CONTENT)->data($data)
            ->where('id')->eq($contentID)
            ->exec();    
            
    
         $this->action->create('content', $contentID, 'update', $log); 
        
    }
    
     public function cancelPublish($contentID)
    {		
    		$content = $this->getByID($contentID);
	    	if(!$content)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}
    		
    	  if($this->session->user->type <> 1 || $this->session->user->org <> 1)
    	  {
    			throw new Exception($this->lang->error->noRightUpdate);
    	  }
    
       //生成本次要修改的数据（字段）
       $data = new stdClass();
       $data->publish 	= 0;  
       $data->topx 	= 0;      
       //对比要修改的字段值和原记录中的字段值，返回修改日志(原值多少，新值多少）,相同的字段将从data中删除
       $log = getLog($content, $data);//调用参数，原记录在前，新数据在后
			 if(!$log) return;//如果两者相同,没有任何改动，返回的日志是空，说明不需要update，直接返回
			
				
				//否则data只包含需要update的字段，则执行udpate
        $this->dao->update(TABLE_CONTENT)->data($data)
            ->where('id')->eq($contentID)
            ->exec();    
            
    
         $this->action->create('content', $contentID, 'update', $log); 
        
    }
    
    
     public function topx($contentID)
    {		
    		$content = $this->getByID($contentID);
	    	if(!$content)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}
    		
    	  if($this->session->user->type <> 1 || $this->session->user->org <> 1)
    	  {
    			throw new Exception($this->lang->error->noRightEdit);
    	  }
    
       //生成本次要修改的数据（字段）
       $data = new stdClass();
        
       //清除原置顶的标识
       $this->dao->update(TABLE_CONTENT)->set('topx')->eq(0)
        ->where('id')->ne($contentID)  //不等于 
	      ->andWhere('topx')->eq(1)
	      ->andWhere('publish')->eq(1)  //等于
            ->exec();   
            
       $data->topx 	= 1;       
       //对比要修改的字段值和原记录中的字段值，返回修改日志(原值多少，新值多少）,相同的字段将从data中删除
       $log = getLog($content, $data);//调用参数，原记录在前，新数据在后
			 if(!$log) return;//如果两者相同,没有任何改动，返回的日志是空，说明不需要update，直接返回
			
				
				//否则data只包含需要update的字段，则执行udpate
        $this->dao->update(TABLE_CONTENT)->data($data)
            ->where('id')->eq($contentID)
            ->andWhere('publish')->eq(1)  //等于
            ->exec();    
            
    
         $this->action->create('content', $contentID, 'update', $log); 
        
    }
    
     public function cancelTop($contentID)
    {		
    		$content = $this->getByID($contentID);
	    	if(!$content)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}
    		
    	  if($this->session->user->type <> 1 || $this->session->user->org <> 1)
    	  {
    			throw new Exception($this->lang->error->noRightEdit);
    	  }
    
       //生成本次要修改的数据（字段）
       $data = new stdClass();   
       $data->topx 	= 0;       
       //对比要修改的字段值和原记录中的字段值，返回修改日志(原值多少，新值多少）,相同的字段将从data中删除
       $log = getLog($content, $data);//调用参数，原记录在前，新数据在后
			 if(!$log) return;//如果两者相同,没有任何改动，返回的日志是空，说明不需要update，直接返回
			
				
				//否则data只包含需要update的字段，则执行udpate
        $this->dao->update(TABLE_CONTENT)->data($data)
            ->where('id')->eq($contentID)
            ->exec();    
            
    
         $this->action->create('content', $contentID, 'update', $log); 
        
    }
    
    
    
    public function update($contentID)
    {		
    		$content = $this->getByID($contentID);
	    	if(!$content)// id无效    		
  	  	{
     	    throw new Exception($this->lang->error->idInvalid);    	  	
    		}    	    				

    	  if($this->session->user->type <> 1 || $this->session->user->org <> 1)
    	  {
     	    throw new Exception($this->lang->error->noRightEdit);    	  	
    	  }

				$data = $this->getData();
				
				$log = getLog($content, $data);//数据有没有变更
				if(!$log) return;//没有变动
				
        $this->dao->update(TABLE_CONTENT)->data($data)
            ->where('id')->eq($contentID)
            ->exec();    
            
    
         $this->action->create('content', $contentID, 'create', $data); 
        
    }

    public function getAll($query = '', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_CONTENT)        
            ->where('deleted')->eq(0)
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy('id desc')
            ->beginIF($pager)->page($pager)->fi()
            ->fetchAll('id');
    }

		
		public function upload($raceID)
		{
    	/*
    这里执行上传操作，传到/www/upload/$raceID/content/下
    	*/
    	$race = $this->race->getByID($raceID);
    	if(!$race)
    	{
				throw new Exception('no race');				
    	}
    		        
 			if(!isset($_FILES['filedata']))
			{
				//没传文件报错
				throw new Exception('no file');				
			}
			
			$f = $_FILES['filedata'];
			if($f['error']) 
			{
				throw new Exception('error');			
			}
						
  		$a = explode('.',$f['name']);	
  	  if(count($a)<2
  	  //||$a[1]<>'jpg'
  	  ) 
  		{
  			throw new Exception('no extension');
  			
			}
			
			if($f['size']>$this->config->contentSize)
			{
				throw new Exception('too big');
				
			}
			
			
        //generator saved file name  
        $pathFix = $this->pathFix;
        $path = $this->basePath . 'www'. $pathFix . 'upload'. $pathFix. $raceID .$pathFix. 'content'. $pathFix ;              
				createFolder($path);

				$src = $f['tmp_name'];

        $name = date("YmdHis").'_'.rand().'.'.$a[1]; //$a[1]是扩展名
        $dst = $path . $name;
				//	echo $dst;
			
			
				copy($src, $dst);  
				
				$url = '/upload/'.$raceID.'/content/'.$name;
				
				return $url;
    	
		}
		
		public function acnt($contentID)
		{
			$content = $this->getByID($contentID);
			if(!$content)
			{
				throw new Exception($this->lang->error->param);
			}
			
			$this->dao->update(TABLE_CONTENT)
				->set('acnt=acnt+1')
				->where('id')->eq($contentID)
				->exec();
		}
}
 