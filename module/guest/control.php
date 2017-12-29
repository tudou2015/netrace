<?php

class guest extends control {
		
		//显示竞赛自己的前台页面
		private function render($moduleName, $methodName, $raceID)
		{
				$viewFile = $this->app->getModulePath($moduleName) . 'view' . $this->pathFix . $raceID . $this->pathFix . $methodName . '.' . $this->app->config->default->view . '.php';
				//a($viewFile);
				if(!file_exists($viewFile))
				{
					echo $this->fetch($moduleName, 'error');
					die();
				}
				
        $currentPWD = getcwd();
        chdir(dirname($viewFile));

				$this->view->app = $this->app;
				$this->view->config = $this->app->config;
				$this->view->lang = $this->app->lang;			

    		$this->view->raceID = $raceID;
    		$this->view->race = $this->race->getByID($raceID);
				$this->race->incAcnt($raceID);
				
        extract((array)$this->view);
        
        $this->view = null;//出错时，阻止dump的递归
        
        ob_start();
        include $viewFile;
        $output = ob_get_contents();
        ob_end_clean();

        /* At the end, chang the dir to the previous. */
        chdir($currentPWD);
        
        echo $output;			
		}
	
    public function index($raceID = 0)
    {
    	$race = $this->race->getByID($raceID);
    	if($race)
    	{
    		$this->view->contents = $this->content->getAll("race=$raceID and type=1 and publish=1");
    		$this->view->new_players = $this->player->getAll("race=$raceID and audit=1",'id desc');
    		$this->view->hot_players = $this->player->getAll("race=$raceID and audit=1",'vote desc');
    		$this->view->works = $this->player->getWorksByPlayer($raceID);
    		$this->render('guest','index',$raceID);    		
    	}
    	else
    	{
  	  	$this->view->raceMap = $this->race->getMap();			  	  	
    		$this->display();    	    
	    }
    }
        
    public function show($raceID, $contentID)
    {
    	$race = $this->race->getByID($raceID);
    	$content = $this->content->getByID($contentID);

    	if(!$race || !$content || $raceID <> $content->race || !($this->get->flag || $content->publish))//允许未发布预览
    	{
    		throw new Exception($this->lang->error->param);
    	}
    	
    	//增加访问计数
    	$this->content->acnt($contentID);
    	
    	$this->view->content = $content;
    	$this->render('guest', 'show', $raceID);
    }
    
    public function reg($raceID)
    {
    	$race = $this->race->getByID($raceID);
    	if(!$race)
    	{
    		throw new Exception($this->lang->error->param);
    	}
    	    	
    	if(count($_POST))
    	{
    		try
    		{
    			$this->player->create(false);
    		}
    		catch(Exception $ex)
    		{
					  die(js::error($ex->getMessage()));
					  
    		}
    		echo js::alert('注册成功，请耐心等待审核!');
    		echo js::locate(helper::createLink('guest','index',array('raceID'=>$raceID)),'parent');
    		die();
    	}
    	
    	$this->view->raceMap = $this->race->getMap();
    	$this->view->orgMap = $this->org->getMap3();
    	$player = $this->player;//加载符号,view里面用
    	
      $this->render('guest', 'reg', $raceID);
      
    }
    
    //$type = 1 新加入 
    //$type = 2 最热
    public function browse($raceID, $type, $numPerPage)
    {
    	$race = $this->race->getByID($raceID);
    	if(!$race)
    	{
    		throw new Exception($this->lang->error->param);
    	}

			if($type < 1 && $type > 2) $type = 1;			
    	switch($type)
    	{
    		case 1:
    			$order = 'id desc';
    			break;
    		case 2:
    			$order = 'vote desc';
    			break;
    	}
    	
    	if(!is_numeric($numPerPage))
    	{
    		throw new Exception($this->lang->error->param);
    	}
    	
    	$query = '';
    	$name = $this->post->name;
    	if($name)
    	{
    		if(is_numeric($name) && $name < PHP_INT_MAX) $query = " and id='$name'";
    		else
    			if(validater::checkString($name,1,255)) $query = " and name like '%$name%'";    		
    	}
    	
    	$pager = pager::init();

    	$this->view->players = $this->player->getAll("race=$raceID and audit=1".$query, $order, $pager);
   		$this->view->works = $this->player->getWorksByPlayer($raceID);
    	$this->view->type = $type;
    	$this->view->numPerPage = $numPerPage;
    	$this->view->pager = $pager;        	
      $this->render('guest', 'browse', $raceID);
    }
   
    //更多赛事
    public function more($raceID)
    {
    	$race = $this->race->getByID($raceID);
    	if(!$race)
    	{
    		throw new Exception($this->lang->error->param);
    	}
    	
    	$this->view->contents = $this->content->getAll("race=$raceID and publish=1 and type=1");
      $this->render('guest', 'more', $raceID);
    } 
   
    //承诺书
    public function promise($raceID)
    {
    	$race = $this->race->getByID($raceID);
    	if(!$race)
    	{
    		throw new Exception($this->lang->error->param);
    	}
    	
    	$this->render('guest', 'promise', $raceID);
    }
    
    //详细信息
    public function detail($raceID, $playerID)
    {
    	$race = $this->race->getByID($raceID);
    	$player = $this->player->getByID($playerID);
    	if(!$race || !$player || $raceID <> $player->race)
    	{
    		throw new Exception($this->lang->error->param);
    	}
    	//$this->view->race = $race;
    	$this->view->player = $player;   		
    	$this->view->org = $this->org->getByID($player->org);
   		$works = $this->player->getWorksByPlayer($raceID, $playerID);			
			$this->view->work = isset($works[$playerID])?$works[$playerID]:null;
						
			$s1 = $this->server->http_accept;			
			$s2 = $this->server->http_user_agent;
			if(stripos($s1, "wap") !== false || stripos($s2, "android")  !== false || stripos($s2, "iphone") !== false)
			{
				$this->view->mobile = 1;
			}
			
    	$now = date('Y-m-d');
    	if($race->vstart <= $now && $now <= $race->vend) 
    	{
    		$this->view->permitVote = 1;//允许投票
    	}
			
    	$this->render('guest', 'detail', $raceID);
    }    
    
    public function vote($raceID, $playerID, $code)
    {
    	$ret = new stdClass();
    	$ret->e = 0;//是否错误
			
			$try = $this->session->try;
			if(!$try) $try = 0;
			$try ++;

			//两次登录之间的时间间隔需要限制
			$time = $this->session->time;
			if(!$time)
			{
				$time = time();
				$diff = $this->config->minTime;
			}
			else
			{
				$diff = time() - $time;
				$time = time();
			}
			$this->session->set('time',$time);
			$this->session->set('try',$try);

			if(!$this->session->code //check code未生成,漏洞
				|| $try > $this->config->maxTry //重试次数超过限制
				|| strtolower($code) <> strtolower($this->session->code)
				|| $diff < $this->config->minTime//抵御蛮力攻击
				)			
    	{
    		$ret->e = 1;//出错
    		$ret->m = $this->lang->guest->error->code;
	    	die(json_encode($ret));
    	}
    	
      $this->session->remove('try');
      $this->session->remove('code');
      $this->session->remove('time');
      $this->session->remove('ctime');
    	
    	$race = $this->race->getByID($raceID);
    	$player = $this->player->getByID($playerID);
    	if(!$race || !$player || $raceID <> $player->race)
    	{
    		die($this->lang->error->param);
    	}
    	
    	try
    	{
    		$this->player->vote($playerID);
    	}
    	catch(Exception $ex)
    	{
    		$ret->e = 1;//出错
    		$ret->m = $ex->getMessage();
    	}
    	
    	if(!$ret->e)
    	{
    		$ret->m = $this->lang->player->error->voteOK;    	
    		$ret->v = $this->player->getByID($playerID)->vote;//当前投票数
    	}
    	die(json_encode($ret));
    }    
}