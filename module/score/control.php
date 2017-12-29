<?php
class score extends control
{
		
		public function browse()
		{
								
			
				$raceID = $this->post->race;
				
		
				$raceMap = $this->view->raceMap      = $this->race->getMap();//需检查race中getMap()函数中的表名
				
				if(isset($raceMap[$raceID]))
				{	
					$search = array();
					$search[] =  'race='.$raceID;
					$search[] =  'state=1';
					
					 
	        $query = implode(' and ',$search);
					
	      		
	        
	        $this->view->players    = $this->player->getAll($query, 'score desc,org');			
	        
					
	    		$this->view->works = $this->player->getWorksByPlayer($raceID);//在player下model文件中    		   
	    		
	    		$this->view->orgMap = $this->org->getMap();
	    			    		
    	}
    	else
    	{
    		 $this->view->players = array();
    		 $this->view->works = array();
    	}

        $this->display();        

			
		}

    
		 			
		 public function scoreAll($raceID)
    {
    		
    			try
    			{
         		$this->player->scoreAll($raceID);
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		 $this->ajaxReturn('200');
    		
		
    }
 
     
}
