<?php
class judge extends control
{
		
		public function browse()
		{
			
				$raceID = $this->post->race;
				
		
				$raceMap = $this->view->raceMap      = $this->race->getMap();//����race��getMap()�����еı���
								
				if(isset($raceMap[$raceID]))
				{				
					$race = $this->race->getByID($raceID);			
					if(!$race->judger 
						|| !in_array($this->session->user->id,explode(',',$race->judger))
						) 
					{
							echo js::alert($this->lang->judge->error->noRightScore);
							
	  	  		 $this->view->players = array();
  	  			 $this->view->works = array();							
					}
					else
					{
						$search = array();
						$search[] =  'race='.$raceID;
						$search[] =  'state=1';
						
						 
		        $query = implode(' and ',$search);
						
		      		
		        
		        $this->view->players    = $this->player->getAll($query, null);			
		       
		    		$this->view->works = $this->player->getWorksByPlayer($raceID);//��player��model�ļ���
		    		
		    		$this->view->scores = $this->player->getScoresByPlayer($raceID, $this->session->user->id);//��player��model�ļ���
		
		    		//		���ϼ���view�в�������browseҳ��ʹ�ã�������score()��view����������;
	  			}	
    	}
    	else
    	{
    		 $this->view->players = array();
    		 $this->view->works = array();
    	}

        $this->display();        

			
		}
		 			
		public function score($playerID)
    {
    		if(!empty($_POST))
        {
    			try
    			{
         		$this->player->score($playerID);//player�е�score����
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		 $this->ajaxReturn('200','','','','closeCurrent');
    		}
			else
			{
								
				$player = $this->player->getByID($playerID);
				if(!$player)
				{
					throw new Exception($this->lang->error->param);//$this->lang->error->paramλ��common�ļ���lang��
				}
				
				$works = $this->player->getWorksByPlayer($player->race, $player->id);
				$this->view->work = isset($works[$player->id])?$works[$player->id]:null;
       	$this->view->score     = $this->player->getScoreByPlayer($this->session->user->id, $playerID);     	   
    	  $this->view->playerID = $playerID;
    	  
    		$s1 = $this->server->http_accept;			
				$s2 = $this->server->http_user_agent;
				if(stripos($s1, "wap") !== false || stripos($s2, "android")  !== false || stripos($s2, "iphone") !== false)
				{
					$this->view->mobile = 1;
				}
			
    	  //���ϼ���view�еĲ�������score.html.phpҳ�棬���ڵ�����ʾ
				$this->display();
      }
    }
 
    
     
}
