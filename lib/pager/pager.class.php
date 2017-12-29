<?php
/**
 * The pager class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
/**
 * Pager class.
 * 
 * @package framework
 */
class pager
{
    /**
     * The default counts of per page.
     *
     * @public int
     */
    const DEFAULT_REC_PRE_PAGE = 20;

    /**
     * The total counts.
     * 
     * @var int
     * @access public
     */
    private $recTotal;

    /**
     * Record count per page.
     * 
     * @var int
     * @access public
     */
    private $recPerPage;


    /**
     * Page count.
     * 
     * @var string
     * @access public
     */
    private $pageTotal;

    /**
     * Current page id.
     * 
     * @var string
     * @access public
     */
    private $pageID;


    /**
     * The params.
     *
     * @private array
     */
    private $params;

		private $recPerPageMap;
		
    /**
     * The construct function.
     * 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->recPerPageMap = router::getInstance()->config->recPerPageMap;        
        
        $this->setRecTotal();
        $this->setRecPerPage();
        $this->setPageTotal();
        $this->setPageID();
    }

    /**
     * The factory function.
     * 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return object
     */
    static public function init()
    {
        return new pager();
    }

    /**
     * Set the recTotal property.
     * 
     * @param  int    $recTotal 
     * @access public
     * @return void
     */
    public function setRecTotal($recTotal = 0)
    {
        $this->recTotal = (int)$recTotal;
    }

    /**
     * Set the recTotal property.
     * 
     * @param  int    $recPerPage 
     * @access public
     * @return void
     */
    public function setRecPerPage()
    {
        /* Set the cookie name. */
        $recPerPage = router::getInstance()->request->numPerPage;
        reset($this->recPerPageMap);
        list($key,$value)=each($this->recPerPageMap);
        $this->recPerPage = ($recPerPage > 0) ? $recPerPage : $value;
    }

    /**
     * Set the pageTotal property.
     * 
     * @access public
     * @return void
     */
    public function setPageTotal()
    {
        $this->pageTotal = ceil($this->recTotal / $this->recPerPage);
    }

    /**
     * Set the page id.
     * 
     * @param  int $pageID 
     * @access public
     * @return void
     */
    public function setPageID()
    {
    		$pageID = router::getInstance()->request->pageNum;
    		    	
        if($pageID > 0 and $pageID <= $this->pageTotal)
        {
            $this->pageID = $pageID;
        }
        else
        {
            $this->pageID = 1;
        }
    }

    /**
     * Get recTotal, recPerpage, pageID from the request params, and add them to params.
     * 
     * @access private
     * @return void
     */
    private function setParams()
    {
        $this->params = router::getInstance()->getParams();
        foreach($this->params as $key => $value)
        {
            if(strtolower($key) == 'rectotal')   $this->params[$key] = $this->recTotal;
            if(strtolower($key) == 'recperpage') $this->params[$key] = $this->recPerPage;
            if(strtolower($key) == 'pageID')     $this->params[$key] = $this->pageID;
        }
    }

    /**
     * Create the limit string.
     * 
     * @access public
     * @return string
     */
    public function limit()
    {
        $limit = null;
        //if($this->pageTotal > 1) $limit = ' limit ' . ($this->pageID - 1) * $this->recPerPage . ", $this->recPerPage";
        //通过row_number处理
        //if($this->pageTotal > 1) $limit = array( ($this->pageID - 1) * $this->recPerPage + 1 , $this->recPerPage);
        //通过array_slice处理
        if($this->pageTotal > 1) $limit = array( ($this->pageID - 1) * $this->recPerPage, $this->pageID * $this->recPerPage);
        return $limit;
    }
       
       
    public function __get($key)
    {
    	if(isset($this->$key)) return $this->$key;
    	
    	router::getInstance()->error("pager::__get('$key')");
    }
}
