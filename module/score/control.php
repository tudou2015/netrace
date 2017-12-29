<?php
class score extends control
{
		
		public function browse()
		{
								
			
				$raceID = $this->post->race;
				
		
				$raceMap = $this->view->raceMap      = $this->race->getMap();//����race��getMap()�����еı���
				
				if(isset($raceMap[$raceID]))
				{	
					$search = array();
					$search[] =  'race='.$raceID;
					$search[] =  'state=1';
					
					 
	        $query = implode(' and ',$search);
					
	      		
	        
	        $this->view->players    = $this->player->getAll($query, 'score desc,org');			
	        
					
	    		$this->view->works = $this->player->getWorksByPlayer($raceID);//��player��model�ļ���    		   
	    		
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
