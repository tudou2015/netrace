<?php
/**
 * The control file of content module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     org
 * @version     $Id: control.php 2108 2011-09-22 00:31:23Z wwccss $
 * @link        http://www.zentao.net
 */
class content extends control
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
    		if($this->session->user->org <> 1) die($this->lang->error->noRightOp);  
      	//a($_POST);
    	
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init();

        $race = $this->post->race;
        $title = $this->post->title;
				
				$search = array();
				 
				
    		$raceMap = $this->view->raceMap  = $this->race->getMap();

				if(isset($raceMap[$race])) $search[]="race=$race";
				
				//验证title是否有效
				if(validater::checkString($title,1,50)) $search[]="title like '%$title%'";
        
        $query = implode(' and ',$search);
    		
    		if(isset($raceMap[$race]))
    		{
	        $this->view->contents = $this->content->getAll($query, $pager); 
				}
				else
				{
	        $this->view->contents = array(); 
				}
				$this->view->pager    = $pager;
				
        $this->display();
    }

  public function preview($contentID)
    {	  
        $content = $this->content->getByID($contentID); 
				
        echo $this->fetch('guest','show',array('raceID'=>$content->race,'contentID'=>$contentID));

        //$this->display();
    }
    
    /**
     * Create a org.
     * 
     * @param  int    $deptID 
     * @access public
     * @return void
     */
    public function create($raceID = 0)
    {
    	if(!$raceID)
    	{
    		die(js::error('请先选择活动!'));
    	}
    	
        if(!empty($_POST))
        {
        	try
        	{
            $this->content->create($raceID);
          }
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());                      	
	  	    }
    		  $this->ajaxReturn('200','','','','closeCurrent');
        }
				else
				{
          //生成上传文件的url
      		$this->view->raceMap  = $this->race->getMap();

					$this->view->uploadUrl = helper::createLink('content','upload',array('raceID'=>$raceID));
					
					$this->view->raceID = $raceID;
					
					$this->display();
      	}
    }

   
    public function edit($contentID)
    {
        if(!empty($_POST))
        {
        	try
        	{
            $this->content->update($contentID);
          }
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());                      	
	  	    }
    		  $this->ajaxReturn('200','','','','closeCurrent');
        }
        else
        {
        	$this->view->raceMap  = $this->race->getMap();
        	    		
        	$content = $this->view->content     = $this->content->getByID($contentID);
          //生成上传文件的url
					$this->view->uploadUrl = helper::createLink('content','upload',array('raceID'=>$content->race));
				
        	$this->display();
      	}
    }


   
    public function delete($contentID)
    {
    	
    			try
    			{
         		$this->content->deletex($contentID);            
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());                      	
	  	    }
    		  $this->ajaxReturn('200');
    }
    
    //置顶
     public function top($contentID)
    {
    	
    			try
    			{
         		$this->content->topx($contentID);            
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());                      	
	  	    }
    		  $this->ajaxReturn('200');
    }
    
		 //置顶
     public function cancelTop($contentID)
    {
    	
    			try
    			{
         		$this->content->cancelTop($contentID);            
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());                      	
	  	    }
    		  $this->ajaxReturn('200');
    }
    
    //发布
         public function publish($contentID)
    {
    	
    			try
    			{
         		$this->content->publish($contentID);            
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());                      	
	  	    }
    		  $this->ajaxReturn('200');
    }
    //取消发布
         public function cancelPublish($contentID)
    {
    	
    			try
    			{
         		$this->content->cancelPublish($contentID);            
       		}
          catch(Exception $ex)
          {
		       	$this->ajaxReturn('300',$ex->getMessage());                      	
	  	    }
    		  $this->ajaxReturn('200');
    }
    
    
    //处理上传文件
    public function upload($raceID)
    {
    	try
    	{    		
				$url = $this->content->upload($raceID);
			}
			catch(Exception $ex)
			{
  	  	$data =new stdClass();
    		$data->err = $ex->getMessage();//是否出错
    		$data->msg = $ex->getMessage();//文件上传后的url路径
    		echo json_encode($data);		
    		die();
			}
		
  	  	$data =new stdClass();
    		$data->err = 0;//是否出错
    		$data->msg = $url;//文件上传后的url路径
    		echo json_encode($data);		
    }
 }
