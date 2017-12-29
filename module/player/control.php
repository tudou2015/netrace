<?php
class player extends control
{
		

		public function browse()
		{
						
			  $pager = pager::init();
									
				$race = $this->post->race;
				$org = $this->post->org;
        $name = $this->post->name;    	
    		$idcode = $this->post->idcode;
				$audit = $this->post->audit;
				$number = $this->post->number;
				
				$search = array();
				
			
        $raceMap = $this->view->raceMap      = $this->race->getMap();
        $orgMap = $this->view->orgMap      = $this->org->getMap3($this->session->user->orgcode);

        if(isset($raceMap[$race])) $search[]="race='$race'";
        if(isset($orgMap[$org])) $search[]="org='$org'";
				if(validater::checkString($name,1,255)) $search[]="name like '%$name%'";
				if(validater::checkIDCode($idcode) || is_numeric($idcode)) $search[]="idcode like '$idcode%'";
        if(validater::checkString($number,1,255) || is_numeric($number)) $search[]="number like '$number%'";
        if(isset($this->lang->player->auditMap[$audit])) $search[]="audit='$audit'";
        
        
        $search[] = "orgcode like '{$this->session->user->orgcode}%'";
			 
        $query = implode(' and ',$search);
				
				if($race)
				{
        	$this->view->players     = $this->player->getAll($query, 'id desc', $pager);				
			   	$this->view->works = $this->player->getWorksByPlayer($race);			
      	}
      	else
      	{
      		$this->view->players = array();
      	}
				$this->view->pager       = $pager;

        $this->display();        

		}

    /**
     * Create a user.
     *
     * @param  int    $deptID
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
        	try
        	{
            $this->player->create(true);
          }
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200','','','','closeCurrent');
        }
				else
				{
					$this->view->orgMap      = $this->org->getMap3($this->session->user->orgcode);//调用race表中的字段信息
    	    $this->view->raceMap      = $this->race->getMap();//需检查race中getMap()函数中的表名
        	$this->display();//调用create.html.php
        }
    }

   public function edit($playerID)
	{
		    if(!empty($_POST))
        {
        	try
        	{
            $this->player->update($playerID);
          }
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200','','','','closeCurrent');
        }
				else
				{
								
	       	 $player = $this->view->player     = $this->player->getByID($playerID);
	       	 
	       	 if(!$player) die(js::error($this->lang->error->param));
	       	 
	         $this->view->orgMap      = $this->org->getMap3($this->session->user->orgcode);//调用race表中的字段信息
	    	   $this->view->raceMap      = $this->race->getMap();//需检查race中getMap()函数中的表名
		   		$works = $this->player->getWorksByPlayer($player->race, $playerID);			
					$this->view->work = isset($works[$playerID])?$works[$playerID]:null;
					
	        $this->display();
       }
        
	}
	
    
    /**
     * Delete a user.
     *
     * @param  int    $userID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($playerID)
    {
    			try
    			{
         		$this->player->deletex($playerID);
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200');
    }
    
    /**
     * audit a player.
     *
     * @param  int    $playerID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function audit($playerID)
    {
    			try
    			{
         		$this->player->auditx($playerID);
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200');
    }
     /**
     * promotion a player.
     *
     * @param  int    $playerID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function state($playerID)
    {
    			try
    			{
         		$this->player->statex($playerID);
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200');
    }
     /**
     * Cancel the promotion a player.
     *
     * @param  int    $playerID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function cancelstate($playerID)
    {
    			try
    			{
         		$this->player->cancelstatex($playerID);
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200');
    }
     
}
