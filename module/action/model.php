<?php
/**
 * The model file of action module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id: model.php 2253 2011-10-28 05:09:44Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php
class actionModel extends model
{
    const CAN_UNDELETED = 1;    // The deleted object can be undeleted or not.
    const BE_UNDELETED  = 0;    // The deleted object has been undeleted or not.

  public function __construct()
  {
  	parent::__construct();  	
	}
    /**
     * Create a action.
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @param  string $actionType 
     * @param  string $comment 
     * @param  string $extra        the extra info of this action, according to different modules and actions, can set different extra.
     * @access public
     * @return int
     */
    public function create($objectType, $objectID, $actionType, $comment = '')
    {
    		$action = new stdClass();        
    		
        $action->objectType = $objectType;
        $action->objectID   = $objectID;
        
        $user = $this->session->user;
        if(is_object($user)) 
        {
					$action->actor			= $user->id;
	        $action->org		    = $user->org;
	        $action->orgcode    = $user->orgcode;
      	}
        $action->action     = $actionType;
        $action->date       = helper::now();
        $action->ip         = $this->server->remote_addr;
        
        if(is_object($comment))
        {
        	$data = array();
        	foreach($comment as $key => $value)
        	{
        		$data[]="$key=[$value]";
        	}
        	$comment = implode(',', $data);
        }
        
        $action->comment    = htmlspecialchars($comment);
        //$action->extra      = $extra;

        $this->dao->insert(TABLE_ACTION)->data($action)->exec();
        return $this->dao->lastInsertID();
    }

    
    /**
     * Get all.
     * 
     * @param    array   $pager 
     * @access public
     * @return array
     */
    public function getAll($query = '', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_ACTION)
        		->beginIF($query)->where($query)->fi()
            ->orderBy('id desc')
            ->beginIF($pager)->page($pager)->fi()
            ->fetchAll('id');
    }      
    
    public function getByID($actionID)
    {
    	 if(!is_numeric($actionID)) return false;
        return $this->dao->select('*')->from(TABLE_ACTION)
        		->where('id')->eq($actionID)
            ->fetch();
    }
    
    public function recover()
    {
    	$table = 'org';
    	
    	$rows = $this->dao->select()->from(TABLE_ACTION)
    		->where('objectType')->eq($table)
    		->fetchAll();
    		
    	foreach($rows as $row)
    	{
    		switch($row->action)
    		{
    			case 'create':
    				$comment=$row->comment;
    				$a=explode(',',$comment);
    				$k = array();
    				$v = array();
    				foreach($a as $b)
    				{
    					$c=explode('=',$b);
    					$k[]=$c[0];
    					$v[]=strtr($c[1],'[]','\'\'');
    				}
    				    				
    				echo "insert into zt_{$table} (",implode(',',$k),") values(",implode(',',$v),")";
    				break;
    			case 'update':
    				$comment=strtr($row->comment,'[]','\'\'');
    				echo "update zt_{$table} set {$comment} where id={$row->objectID}";
    				break;
    			case 'delete':
    				echo "update zt_{$table} set deleted=1 where id={$row->objectID}";
    				break;
    		}
    		echo ';<br />';
    	}
    }    
}
