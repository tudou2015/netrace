<?php
/**
 * The model file of student module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     student
 * @version     $Id: model.php 1939 2011-06-28 15:14:53Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class voteModel extends model
{

    /**
     * Get votes of a date.
     * 
     * @param  array    
     * @access public
     * @return array
     */
    public function getByDate($query = '')
    {
        $rows = $this->dao->select('*')->from(TABLE_VOTE)
            ->beginIF($query)->where($query)->fi()
            ->orderBy('date desc')
            ->fetchAll('id');
            
        $result = array();
        foreach($rows as $row)
        {
        	$result[]=$row;
        }
        
        return $result;
    }
    
    /**
     * Get players of a date.
     * 
     * @param  array    
     * @access public
     * @return array
     */
      public function getByStu($query = '')
    {
        $rows = $this->dao->select('*')->from(TABLE_PLAYER)
            ->where('deleted')->eq(0)
            ->beginIF($query)->andwhere($query)->fi()
            ->orderBy('vote desc')
            ->fetchAll('id');
            
        $result = array();
        
        foreach($rows as $row)
        {
        	$result[]=$row;
        }
        
        return $result;
    }
    

      public function getByTeach($query = '')
    {
        $rows = $this->dao->select('teacher,sum(vote) as vote')->from(TABLE_PLAYER)
            ->where('deleted')->eq(0)
            ->beginIF($query)->andwhere($query)->fi()
            ->groupBy('teacher')
            ->orderBy('vote desc')
            ->fetchAll();
            
        $result = array();
        
        foreach($rows as $row)
        {
        	if(!$row->teacher) continue;
        	
        	if(!isset($result[$row->teacher])) $result[$row->teacher]=0;
        	
        	$result[$row->teacher]+=$row->vote;
        }
        
        return $result;
    }
 
}
