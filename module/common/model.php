<?php
/**
 * The model file of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class commonModel extends model
{
    /**
     * Start the session.
     * 
     * @access public
     * @return void
     */
    public function startSession()
    {
    		$sessionVar = $this->config->sessionVar;  
        session_name($sessionVar);
        //if(isset($this->get->$sessionVar)) session_id($this->get->$sessionVar);
        session_start();        
    }

    /**
     * Set the header info.
     * 
     * @access public
     * @return void
     */
    public function sendHeader()
    {
        header("Content-Type: text/html; charset={$this->config->encoding}");
        header("Cache-control: private");
    }

    /**
     * Check the user has permission to access this method, if not, locate to the login page or deny page.
     * 
     * @access public
     * @return void
     */
    public function checkPriv()
    {
        $module = $this->app->moduleName;
        $method = $this->app->methodName;
        if($this->isOpenMethod($module, $method)) return true;

				//检查令牌是否有效
        if($this->cookie->token == $this->session->token && $this->session->user)
        {        	
            if(!$this->hasPriv($module, $method)) 
            {            	
	            session_unset();
  	         	helper::locate(helper::createLink($this->config->login->module, $this->config->login->method));
            }
        }
        else
        {
            session_unset();
           	helper::locate(helper::createLink($this->config->login->module, $this->config->login->method));
        }
    }

    /**
     * Check the user has permisson of one method of one module.
     * 
     * @param  string $module 
     * @param  string $method 
     * @static
     * @access public
     * @return bool
     */
    public function hasPriv($module, $method)
    {     	
				return true;
    }
    
    /**
     * Juage a method of one module is open or not?
     * 
     * @param  string $module 
     * @param  string $method 
     * @access public
     * @return bool
     */
    public function isOpenMethod($module, $method)
    {
        if($module == 'user' and strpos('|login|code|logout|deny|sso|', "|$method|") !== false) return true;
				if($module == 'guest') return true;
				
        return false;
    }

    /**
     * Deny access.
     * 
     * @access public
     * @return void
     */
    public function deny($module, $method)
    {
	    	die();
    }

}
