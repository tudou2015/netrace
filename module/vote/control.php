<?php
class vote extends control
{

    /*
		按日期统计
		*/
    public function statByDate()
    {	
				$search = array();
				
			
				$race = $this->post->race;
        $start = $this->post->start;
        $end = $this->post->end;
      
		
		    $raceMap =  $this->view->raceMap      = $this->race->getMap();
				if(isset($raceMap[$race])) $search[]="race='$race'";
				if(validater::checkDate($start)) $search[]="date >= '$start'";
				if(validater::checkDate($end)) $search[]="date <= '$end'";
        
        //查询vote表，没有orgcode字段
        //因为按日期查，也不需要
        //$search[] = "orgcode like '{$this->session->user->orgcode}%'";
        
        $query = implode(' and ',$search);
				
				if(isset($raceMap[$race]))
				{
					$votes = $this->vote->getByDate($query);				
		  	}
				else
				{
					$votes = array();
				}		  
		
				$this->view->votes = $votes;
				
        $this->display();
    }
    
		/*
		按学生统计
		*/
    public function statByStu()
    {	
				$search = array();
				
			
				$race = $this->post->race;
        $name = $this->post->name;
        $idcode = $this->post->idcode;
      
		
		    $raceMap =  $this->view->raceMap      = $this->race->getMap();
				if(isset($raceMap[$race])) $search[]="race='$race'";
			
				if(validater::checkString($name,1,255)) $search[]="name like '%$name%'";
				if(validater::checkIDCode($idcode) || is_numeric($idcode)) $search[]="idcode like '$idcode%'";
				
				$search[] = "orgcode like '{$this->session->user->orgcode}%'";
        
        
        $query = implode(' and ',$search);
				
				if(isset($raceMap[$race]))
				{	
					$votes = $this->vote->getByStu($query);				
				}
				else
				{
					$votes = array();
				}
		 
		 		$this->view->votes = $votes;
		 		
		 		$this->view->orgMap = $this->org->getMap();
		 		
        $this->display();
    }
  

		/*
		按指导教师统计
		*/
    public function statByTeach()
    {	
				$search = array();
							
				$race = $this->post->race;
      		
		    $raceMap =  $this->view->raceMap      = $this->race->getMap();
				if(isset($raceMap[$race])) $search[]="race='$race'";
				
				$search[] = "orgcode like '{$this->session->user->orgcode}%'";
			        
        $query = implode(' and ',$search);
				
				if(isset($raceMap[$race]))
				{	
					$votes = $this->vote->getByTeach($query);				
				}
				else
				{
					$votes = array();
				}
		 
		 		//a($votes);
		 		$this->view->votes = $votes;
		 		
        $this->display();
    }    
 }
