<?php
/**
 * The control file of org module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     org
 * @version     $Id: control.php 2108 2011-09-22 00:31:23Z wwccss $
 * @link        http://www.zentao.net
 */
class org extends control
{
    /**
     * Browse orgs.
     * 
     * @param  int    $orgID 
     * @access public
     * @return void
     */
    public function browse()
    {	  
      	//a($_POST);
    	
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init();

        $code = $this->post->code;
				
				$search = array();
				
				if(is_numeric($code)) $search[]="code='$code'";
        
        $query = implode(' and ',$search);

    	
        $this->view->orgs       = $this->org->getAll($query, $pager); 
				$this->view->pager      = $pager;
				
        $this->display();
    }

    /**
     * Create a org.
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
            $this->org->create();
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

    /**
     * Edit a org.
     * 
     * @param  int $orgID
     * @access public
     * @return void
     */
    public function edit($orgID)
    {
        if(!empty($_POST))
        {
        	try
        	{
            $this->org->update($orgID);
          }
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());                      	
	  	    }
    		  $this->ajaxReturn('200','','','','closeCurrent');
        }
        else
        {
        	$this->view->org     = $this->org->getByID($orgID);
				
        	$this->display();
      	}
    }

    /**
     * Delete a org.
     * 
     * @param  int    $orgID 
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($orgID)
    {
    	
    			try
    			{
         		$this->org->deletex($orgID);            
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());                      	
	  	    }
    		  $this->ajaxReturn('200');
    }
    
		//启用/禁用
    public function enable($orgID)
    {
    			try
    			{
         		$this->org->enable($orgID);            
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());                      	
	  	    }
    		  $this->ajaxReturn('200');
    }    
    
    
    /*
    显示地图
    */
    public function map($orgID = 0)
    {
    	$org     = $this->org->getByID($orgID);

    	if(!$org
    	|| strlen($org->code) <> 7
    	//|| !$org->enable
    	|| !$org->map
    	)
    		die();
    	
    	
    	$this->view->org = $org;
    	$map = $this->view->map = explode('#', $org->map);
    	
    	if(count($map)<>2) die();//格式不符
    	
    	$this->display();    	
    }
    
    /*
    选择单位时的帮助
    */    
		public function help()
		{
			$this->view->orgs = $this->org->getAll();
			$this->display();
		}    
 }
