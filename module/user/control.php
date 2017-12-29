<?php
class user extends control
{
		public function index()
		{
			$this->view->org = $this->org->getByID($this->session->user->org);
			$this->display();
		}

		public function browse()
		{
				//a($_POST);
        $pager = pager::init();

        $type = $this->post->type;
        $name = $this->post->name;

				$search = array();

				if(isset($this->lang->user->typeMap[$type])) $search[]="type=$type";
				if(validater::checkString($name,1,16)) $search[]="name like'%$name%'";//检查字符串长度以及是否存在非法字符
				
				$search[] = "orgcode like '{$this->session->user->orgcode}%'";
				
        $query = implode(' and ',$search);

        $this->view->users = $this->user->getAll($query, $pager);
        $this->view->orgMap = $this->org->getMap($this->session->user->orgcode);
        
				$this->view->pager = $pager;

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
            $this->user->create();
          }
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200','','','','closeCurrent');
        }
				else
				{
					$this->view->orgMap = $this->org->getMap('','-');
        	$this->display();
        }
    }

    /**
     * Edit a user.
     *
     * @param  int $userID
     * @access public
     * @return void
     */
    public function password($userID)
    {
        if(!empty($_POST))
        {
        	try
        	{
            $this->user->password($userID);
          }
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200','','','','closeCurrent');
        }
				else
				{
       	 $this->view->user     = $this->user->getByID($userID);

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
    public function delete($userID)
    {
    			try
    			{
         		$this->user->deletex($userID);
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());
	  	    }
    		  $this->ajaxReturn('200');
    }
    
    /**
     * User login, identify him and authorize him.
     *
     * @access public
     * @return void
     */
    public function login()
    {
        if($this->user->isLogon())
        {
            //$this->locate($this->createLink('user', 'index'));// m=index&f=index
            session_unset();
        }

        /* Passed account and password by post or get. */
        if(!empty($_POST))
        {
            $name  = $this->post->name;
            $password = $this->post->password;
						$code = $this->post->code;

						$try = $this->session->try;
						if(!$try) $try = 0;
						$try ++;

						//$failed = $this->session->failed;
						//if(!$failed) $failed = 0;

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

						//echo "$time,$diff";


						//rand(1000,9999)
						if(!$this->session->code //check code未生成,漏洞
							|| $try > $this->config->maxTry //重试次数超过限制
//							|| $failed > $this->config->maxFailed
							|| strtolower($code) <> strtolower($this->session->code)
							|| $diff < $this->config->minTime//抵御蛮力攻击
							) die(js::error($this->lang->user->error->codeMismatch));

            $user = $this->user->identify($name, $password);

						$this->session->set('try',$try);

            if($user)
            {
                /* Authorize him and save to session. */
                session_regenerate_id(true);//重新生成session,抵抗固定会话攻击

                $this->session->remove('try');
                $this->session->remove('code');
                $this->session->remove('time');
                $this->session->remove('ctime');

                //session溢出攻击
                $token = rand() . ':' . $this->server->remote_addr . ':' . $this->server->remote_port . ':' .time() . ':' . $user->rand;
//                echo $token;
                $token = crc32($token);
                $this->session->set('token', $token);
                setcookie('token', $token);

                $this->session->set('user', $user);
                die(js::locate(helper::createLink('user', 'index'), 'parent'));
            }
            else
            {
            	  //$failed ++;
								//$this->session->set('failed', $failed);
                die(js::error($this->lang->user->error->loginFailed));
            }
        }
        else
        {
            $this->display();
        }
    }


    /**
     * Logout.
     *
     * @access public
     * @return void
     */
    public function logout()
    {
    		$user = $this->session->user;
    		if($user)
    		{
        	$this->action->create('user', $this->session->user->id, 'logout');
        	session_destroy();
				}
        helper::locate(helper::createLink('user', 'login'));
    }

    /**
     * create check code image.
     *
     * @access public
     * @return void
     */
    public function code($type = 0)
    {
			//code的生成要保护
			$ctime = $this->session->ctime;
			if(!$ctime)
			{
				$ctime = time();
				$diff = $this->config->minTime;
			}
			else
			{
				$diff = time() - $ctime;
				$ctime = time();
			}
			$this->session->set('ctime',$ctime);

			if($diff < $this->config->minTime)	die();

    	$this->session->set('try',0);//重置try

    	if(!$type)
    	{
    		$this->user->createCode();
    	}
    	else
    	{
    		//动态验证码
    		$this->user->createCode2();
    	}
    			
    }
    
    /**
     * Deny page.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $refererBeforeDeny    the referer of the denied page.
     * @access public
     * @return void
     */
    public function deny($module, $method)
    {
	    	die(js::alert($this->lang->error->noRightOp));
    }
}
