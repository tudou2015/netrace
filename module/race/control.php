<?php
class race extends control
{
	
		public function browse()
		{
				$pager = pager::init();

        $name = $this->post->name;
    		$search = array();
				if(validater::checkString($name,1,50)) $search[]="name like'%$name%'";
			  $query = implode(' and ',$search);
        $this->view->races = $this->race->getAll($query, $pager);
				$this->view->pager = $pager;
				$this->view->userMap = $this->user->getMap();
				
				$this->display();
		}

    
    public function create()
    {
        if(!empty($_POST))
        {
        	try
        	{
            $this->race->create();
          }
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200','','','','closeCurrent');
        }
				else
				{
        	$this->display();
        }
    }

   public function edit($raceID)
	{
		    if(!empty($_POST))
        {
        	try
        	{
            $this->race->update($raceID);
          }
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200','','','','closeCurrent');
        }
				else
				{
       	 $this->view->race     = $this->race->getByID($raceID);

        	$this->display();
        }
	}
	
    
   
    public function delete($raceID)
    {
    			try
    			{
         		$this->race->deletex($raceID);
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200');
    }
    
     public function judger($raceID)
    {	 
	
				$race = $this->race->getByID($raceID);
				if(!$race) 
				{
					js::error($this->lang->error->idInvalid);
					die();
				}
				
				//a($_POST);
        $pager = pager::init();

        $type = $this->post->type;
        $name = $this->post->name;

				$search = array();
				
				$this->app->loadLang('user');
				
				if(isset($this->lang->user->typeMap[$type])) $search[]="type=$type";
				if(validater::checkString($name,1,16)) $search[]="name like'%$name%'";
				
				$search[] = "orgcode like '{$this->session->user->orgcode}%'";
				
        $query = implode(' and ',$search);

        $this->view->users = $this->user->getAll($query, $pager);
        $this->view->orgMap = $this->org->getMap($this->session->user->orgcode);
        
				$this->view->pager = $pager;
				$this->view->raceID = $raceID;
				
				$this->view->judgerMap = explode(',', $race->judger);
				
				$this->display();

    }   
    
    public function setJudge($raceID)         
    {
    	try
    	{    		
				$this->race->setJudge($raceID);
			}
			catch(Exception $ex)
			{
				echo $ex->getMessage();
			}
    }
    

}
