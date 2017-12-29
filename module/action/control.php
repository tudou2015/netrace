<?php
class action extends control
{
  /**
     * Browse codes.
     * 
     * @param  int    $orgID 
     * @access public
     * @return void
     */
    public function browse()
    {	   
     		//a($_POST);
    	
        /* Load pager. */
        $pager = pager::init();

        $objectType = $this->post->objectType;
        $objectID = $this->post->objectID;
				
				$search = array();
				
				if(isset($this->lang->action->objectTypeMap[$objectType])) $search[]="objectType='$objectType'";
				if(is_numeric($objectID)) $search[]="objectID='$objectID'";
        
        $search[] = "orgcode like '{$this->session->user->orgcode}%'";
        
        $query = implode(' and ',$search);

        $this->view->actions       = $this->action->getAll($query, $pager);        
				$this->view->pager       = $pager;
				$this->view->userMap     = $this->user->getMap('id', 'name', true);
				$this->view->actionMap   = $this->lang->action->actionMap;
				$this->view->objectTypeMap   = $this->lang->action->objectTypeMap;
				
        $this->display();
    }
    
    public function detail($actionID)
    {
	    	$action=$this->action->getById($actionID);
  	  	if(!$action)
    		{
    			die(js::error($this->lang->error->idInvalid));
    		}
				
				if(!$this->org->isSub($this->session->user->org, $action->org))
				{
					die(js::error($this->lang->error->param));
				}
				
       	$this->view->action  = $action;

        $this->display();
    }        
    
		public function recover()
		{
			die();
			$this->action->recover();
		}
		    
    public function test()
    {    	
        /* Load pager. */
        $pager = pager::init();

        $objectType = $this->post->objectType;
        $objectID = $this->post->objectID;
				
				$search = array();
				
				if(isset($this->lang->action->objectTypeMap[$objectType])) $search[]="objectType='$objectType'";
				if(is_numeric($objectID)) $search[]="objectID='$objectID'";
        
        $query = implode(' and ',$search);

        $this->view->actions       = $this->action->getAll($query, $pager);        
				$this->view->pager       = $pager;
				$this->view->userMap     = $this->user->getMap('id', 'name', true);
				$this->view->actionMap   = $this->lang->action->actionMap;
				$this->view->objectTypeMap   = $this->lang->action->objectTypeMap;
    		$this->view->url				 = helper::createLink('action','test');
    		
    		$this->sdisplay();//smartyÄ£°å¼¼Êõ
    }
}
