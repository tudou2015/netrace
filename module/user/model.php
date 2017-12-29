<?php
/**
 * The model file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: model.php 1939 2011-06-28 15:14:53Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class userModel extends model
{

    /**
     * Get user info by ID.
     * 
     * @param  int|string    $userID 
     * @access public
     * @return object|bool
     */
    public function getByID($userID)
    {
    	 if(!is_numeric($userID)) return false;
        return $this->dao->select('*')->from(TABLE_USER)
        		->where('id')->eq($userID)
        		->andWhere('deleted')->eq(0)
            ->fetch();
    }
	
    /**
     * Create a user.
     * 
     * @access public
     * @return insertID
     */
    public function create()
    {   
    	  if($this->session->user->type <> 1)
    	  {
    	    throw new Exception($this->lang->error->noRightCreate);    	  	
    	  }

    	  $org = $this->post->org;
				if(!$this->org->isSub($this->session->user->org, $org))
				{
					throw new Exception($this->lang->user->error->orgInvalid);
				}
								
    	  $type = $this->post->type;
				if(!isset($this->lang->user->typeMap[$type]))
				{
					throw new Exception($this->lang->user->error->typeInvalid);
				}
				
				$name = $this->post->name;
        if(!validater::checkString($name, 1, 16)) 
				{
					throw new Exception($this->lang->user->error->nameInvalid);
				}
				
				$password1 = $this->post->password1;
				$password2 = $this->post->password2;
        if($password1 != $password2) 
        {
        	throw new Exception($this->lang->user->error->passwordNotSame);
        }
        
        if(!validater::checkString($password1, $this->config->pwdMinLen, $this->config->pwdMaxLen)) 
				{
					throw new Exception($this->lang->user->error->passwordInvalid);
				}
				
				$rows = $this->dao->select()->from(TABLE_USER)
					->where('deleted')->eq(0)
					->andWhere('name')->eq($name)
					->fetchAll();
				if(count($rows))
				{
					throw new Exception($this->lang->user->error->nameDup);
				}
									
				$data = new stdClass();
	   		$data->org = $org;
	   		$data->orgcode = $this->org->getByID($org)->code;
	   		$data->type = $type;
	   		$data->name = $name;
				$rand = mt_rand();
	   		$data->password = md5($password1 . $rand);
				$data->rand = $rand;
												
        $this->dao->insert(TABLE_USER)->data($data)
            ->exec();
            
        $userID = $this->dao->lastInsertID();
        
        $this->action->create('user', $userID, 'create', $data);         
    }

    /**
     * Update a user.
     * 
     * @access public
     * @return void
     */
    public function password($userID)
    {		
    		$user = $this->getByID($userID);
	    	if(!$user)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}    	    				

				$ok = false;				
    		if($userID == $this->session->user->id) $ok = true;//自己修改自己    		
    		if($this->session->user->type == 1 && $this->org->isSub($this->session->user->org, $user->org)) $ok = true;//管理员修改
    		if(!$ok)
    		{
    	    throw new Exception($this->lang->error->noRightEdit);
    		}
    		
    		$data = new stdClass();
    		
    		$oldpassword = $this->post->oldpassword;
    		$newpassword1 = $this->post->newpassword1;
    		$newpassword2 = $this->post->newpassword2;

				//自己改自己的密码需要原密码
   			if($userID == $this->session->user->id)
  			{
      		if(!validater::checkString($oldpassword, $this->config->pwdMinLen, $this->config->pwdMaxLen)) 
					{
						throw new Exception($this->lang->user->error->oldPasswordInvalid);
					}

					$oldPassword = md5($oldpassword . $user->rand);
					if($oldPassword != $user->password)
					{
						throw new Exception($this->lang->user->error->oldPasswordIncorrect);
					}	    			
				}
			
      	if($newpassword1 != $newpassword2) 
	    	{
  	    	throw new Exception($this->lang->user->error->newPasswordNotSame);
    		}
      
      	if(!validater::checkString($newpassword1, $this->config->pwdMinLen, $this->config->pwdMaxLen)) 
				{
					throw new Exception($this->lang->user->error->newPasswordInvalid);
				}

   			$data->password = md5($newpassword1 . $user->rand);
								   										   						   		
				$log = getLog($user, $data);
				if(!$log) return;
				
        $this->dao->update(TABLE_USER)->data($data)
            ->where('id')->eq($userID)
            ->exec();    
            
        $this->action->create('user', $userID, 'update', $log);             
    }

   /**
     * Delete a user.
     * 
     * @access public
     * @return void
     */
    public function deletex($userID)
    {		    	 
     		$user = $this->getByID($userID);
	    	if(!$user)// id无效    		
  	  	{
    			throw new Exception($this->lang->error->idInvalid);
    		}

    	  if($this->session->user->type <> 1 || !$this->org->isSub($this->session->user->org, $user->org))
    	  {
    	    throw new Exception($this->lang->error->noRightDelete);
    	  }
    	      	   		    		    			
    		if($userID == $this->session->user->id)
    		{
    			throw new Exception($this->lang->user->error->denySelfDelete);
    		}
    		    		
    		parent::delete(TABLE_USER, $userID);            // model.class.php中    		    					
    }
    
    /**
     * Identify a user.
     * 
     * @param   string $name     the user name
     * @param   string $password    the user password or auth hash
     * @access  public
     * @return  object
     */
    public function identify($name, $password)
    {
        if(!validater::checkString($name,1,16) or !validater::checkString($password,1,16)) return false;
  
        /* Get the user first. If $password length is 32, don't add the password condition.  */
        $user = $this->dao->select('*')->from(TABLE_USER)
            ->where('name')->eq($name)
            ->andWhere('deleted')->eq(0)
            ->fetch();
				
        if($user)
        {
            $hash = md5($password . $user->rand);
            $user = $user->password == $hash ? $user : '';
        }
				
        if($user)
        {
            $ip   = $this->server->remote_addr;
            $last = date(DT_DATETIME1, $this->server->request_time);
            $this->dao->update(TABLE_USER)->set('visit = visit + 1')->set('ip')->eq($ip)->set('last')->eq($last)->where('name')->eq($name)->exec();
        }
        
  			$this->action->create('user', 
  				$user ? $user->id : 0, 
  				'login', 
  				$name); 
        
        return $user;
    }


    /* 
    /**
     * Judge a user is logon or not.
     * 
     * @access public
     * @return bool
     */
    public function isLogon()
    {
        return ($this->session->user);
    }


    /**
     * Get all user.
     * 
     * @access public
     * @return array
     */
    public function getMap($key = 'id', $value = 'name', $all = false)
    {
        return $this->dao->select('*')->from(TABLE_USER)
        		->beginIF(!$all)->where('deleted')->eq(0)->fi()
            ->fetchPairs($key,$value);
    }


    /**
     * Get users of a org.
     * 
     * @param  array   $orgIDSet 
     * @access public
     * @return array
     */
    public function getAll($query = '', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy('orgcode,type,id')
            ->beginIF($pager)->page($pager)->fi()            
            ->fetchAll('id');
    }

    /**
     * create check code
     * 
     * @access public
     * @return void
     */
     
    public function createCode()
    {
    	$num = rand(1000,9999);
    	
   		//4位验证码也可以用rand(1000,9999)直接生成
		  //将生成的验证码写入session，备验证页面使用
	    $this->session->set('code',$num);
	    
      //创建图片，定义颜色值
	    Header("Content-type: image/PNG");
    	srand((double)microtime()*1000000);
    	$im = imagecreate(60,20);
    	$black = ImageColorAllocate($im, 0,0,0);
    	$gray = ImageColorAllocate($im, 200,200,200);
    	imagefill($im,0,0,$gray);

    	//随机绘制两条虚线，起干扰作用
    	$style = array($black, $black, $black, $black, $black, $gray, $gray, $gray, $gray, $gray);
    	imagesetstyle($im, $style);
    	$y1=rand(0,20);
    	$y2=rand(0,20);
    	$y3=rand(0,20);
    	$y4=rand(0,20);
    	imageline($im, 0, $y1, 60, $y3, IMG_COLOR_STYLED);
    	imageline($im, 0, $y2, 60, $y4, IMG_COLOR_STYLED);

    	//在画布上随机生成大量黑点，起干扰作用;
    	for($i=0;$i<30;$i++)
    	{
   			imagesetpixel($im, rand(0,60), rand(0,20), $black);
    	}
    	//将四个数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
    	$strx=rand(3,8);
    	for($i=0;$i<4;$i++){
    		$strpos=rand(1,6);
    		imagestring($im,5,$strx,$strpos, substr($num,$i,1), $black);
    		$strx+=rand(8,12);
    	}
    	ImagePNG($im);
    	ImageDestroy($im);    
    }
    
    public function createCode2()
    {      	  	
			$chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPRSTUVWXYZ23456789';
			$code = '';
			
			$len = strlen($chars);
			
			for ($i = 0; $i < 4; $i++)
			{
				$code .= substr($chars, mt_rand(0, $len - 1), 1);
			}    	
			$this->session->set('code', $code);
									
			$width = 300;
			$height = 100;				
	   	$left = 0;
			$top = $height / 3;
			$len = strlen($code);
			$font = 'verdana.ttf';
			
			putenv('GDFONTPATH=' . realpath('.'));

	    $im = imagecreate($width, $height);
	    
	    imagecolorallocate($im, 0 , 0, 0);
	    
	 		$black = imagecolorallocate($im, 0, 0, 0);
	 
		 	$gray = ImageColorAllocate($im, 245,245,245); 
	 	
	    imagefill($im, 0, 0, $gray);
	   	
	    $space = $width/$len;
	    $angle = 0;
	    
			for($i = 0; $i < 3; $i++)
			{
		    for ($j = 0; $j < $len; $j++) 
		    { 
					$angle += rand(0, 10) > 5? rand(5, 10): -rand(5, 10);
					$float_top = rand(10,40);
					$float_left = rand(10,40);
					$x=$left + $space * $j + $float_left;
					$y=$top + $float_top;
					imagettftext($im, 30, $angle, $x, $y, $black, $font, substr($code, $j, 1));
			  }
			}
			
			Header('Content-type:image/png');
	    ImagePNG($im);
	    imagedestroy($im);			
    }
    
}
