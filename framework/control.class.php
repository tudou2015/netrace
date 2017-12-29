<?php
/**
 * The control class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * The base class of control.
 * 
 * @package framework
 */
abstract class control
{
    /**
     * The name of current module.
     * 
     * @var string
     * @access protected
     */
    protected $moduleName;

		protected $methodName;
		
    /**
     * The vars assigned to the view page.
     * 
     * @var object
     * @access public
     */
    protected $view; 

    /**
     * The construct function.
     *
     * 1. global the global vars, refer them by the class member such as $this->app.
     * 2. set the pathes of current module, and load it's mode class.
     * 3. auto assign the $lang and $config to the view.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        /* Global the globals, and refer them to the class member. */
				$this->view       = new stdClass();
				
				$this->moduleName = get_class($this);
				
        /* Load the model file auto. */
        router::getInstance()->loadModel($this->moduleName);
    }

    //-------------------- Model related methods --------------------//


    /* Set the method name. 
     * 
     * @param   string  $methodName    The method name, if empty, get it from $app.
     * @access  private
     * @return  void
     */
    public function setMethodName($methodName)
    {
    		if(!$methodName) $this->error('methodName is empty');
        $this->methodName = $methodName;
    }

    //-------------------- View related methods --------------------//
    
    /**
     * Set the view file, thus can use fetch other module's page.
     * 
     * @param  string   $moduleName    module name
     * @param  string   $methodName    method name
     * @access private
     * @return string  the view file
     */
    private function getViewFile($moduleName, $methodName)
    {
        $modulePath  = $this->app->getModulePath($moduleName);

        /* The main view file, extension view file and hook file. */
        $viewFile = $modulePath . 'view' . $this->app->pathFix . $methodName . '.' . $this->app->config->default->view . '.php';

        if(!is_file($viewFile)) $this->app->error("the view file $viewFile not found");
        
        return $viewFile;
    }


    /**
     * Parse default html format.
     *
     * @param string $moduleName    module name
     * @param string $methodName    method name
     * @access private
     * @return void
     */
    private function parse($moduleName, $methodName)
    {
        /* Set the view file. */
        $viewFile = $this->getViewFile($moduleName, $methodName);

        /* Change the dir to the view file to keep the relative pathes work. */
        $currentPWD = getcwd();
        chdir(dirname($viewFile));

				$this->view->app = $this->app;
				$this->view->config = $this->app->config;
				$this->view->lang = $this->app->lang;			
        extract((array)$this->view);
        
        $this->view = null;//出错时，阻止dump的递归
        
        ob_start();
        include $viewFile;
        $output = ob_get_contents();
        ob_end_clean();

        /* At the end, chang the dir to the previous. */
        chdir($currentPWD);
        
        return $output;
    }

    /**
     * Get the output of one module's one method as a string, thus in one module's method, can fetch other module's content.
     * 
     * If the module name is empty, then use the current module and method. If set, use the user defined module and method.
     *
     * @param   string  $moduleName    module name.
     * @param   string  $methodName    method name.
     * @param   array   $params        params.
     * @access  public
     * @return  string  the parsed html.
     */
    protected function fetch($moduleName, $methodName, $params = array())
    {
        if($moduleName == $this->moduleName)
        {
            return $this->parse($moduleName, $methodName);
        }

        /* Set the pathes and files to included. */
        $modulePath        = $this->app->getModulePath($moduleName);
        $moduleControlFile = $modulePath . 'control.php';
        $file2Included     = $moduleControlFile;

        /* Load the control file. */
        if(!is_file($file2Included)) $this->app->error("The control file $file2Included not found");
        $currentPWD = getcwd();
        chdir(dirname($file2Included));
        helper::import($file2Included);
        
        /* Set the name of the class to be called. */
        $className = $moduleName;
        if(!class_exists($className)) $this->app->error(" The class $className not found");

        /* Parse the params, create the $module control object. */
        if(!is_array($params)) parse_str($params, $params);
        $module = new $className();
				$module->setMethodName($methodName);
				
        /* Call the method and use ob function to get the output. */
        ob_start();
        call_user_func_array(array($module, $methodName), $params);
        $output = ob_get_contents();
        ob_end_clean();

        /* Return the content. */
        chdir($currentPWD);
        return $output;
    }

    /**
     * Print the content of the view. 
     * 
     * @param   string  $moduleName    module name
     * @param   string  $methodName    method name
     * @access  public
     * @return  void
     */
    protected function display($moduleName = '', $methodName = '')
    {
    		if(!$moduleName && !$methodName)
    		{
    			$moduleName = $this->moduleName;
    			$methodName = $this->methodName;
    		}
    		else 
    		{
    			$this->app->error('display,param error');
    		}
    	
    		
        echo $this->parse($moduleName, $methodName);        
    }

		/*
		* 使用smarty模板技术
		*/
    protected function sdisplay($moduleName = '', $methodName = '')
    {
    		if(!$moduleName && !$methodName)
    		{
    			$moduleName = $this->moduleName;
    			$methodName = $this->methodName;
    		}
    		else 
    		{
    			$this->app->error('sdisplay,param error');
    		}
    		    	
    		$smarty = new smarty();//加载 smarty
    	    	
    		$smarty->setTemplateDir('..\\'.$moduleName.'\\view');//设置模板路径
    		$smarty->setCompileDir($this->app->cacheRoot);//设置编译缓存路径
    		//$smarty->php_handling = Smarty::PHP_ALLOW;
				
				$this->view->app = $this->app;
				$this->view->config = $this->app->config;
				$this->view->lang = $this->app->lang;			

				foreach($this->view  as $key => $value) //导入变量
				{
					$smarty->assign($key, $value);
				}

				$this->view = null;			
				
				$smarty->display($methodName.'.tpl');
    }
    
  
		protected function ajaxReturn($statusCode, $message = '', $navTabId = '', $rel = '', $callbackType = '', $forwardUrl = '')
		{
			$ret = array();
			$ret['statusCode'] = $statusCode;
			$ret['message'] = $message;
			$ret['navTabId'] = $navTabId;
			$ret['rel'] = $rel;
			$ret['callbackType'] = $callbackType;
			$ret['forwardUrl'] = $forwardUrl;
			
			//return $ret; 			
			die(json_encode($ret));
		}		
		
		/*
		* __get拦截器
		*/
		public function __get($key)
		{
			//私有属性
			if(isset($this->$key)) return $this->$key;
			
			$app = router::getInstance();
			
			//返回app
			if($key == 'app') return $app;
			
			if($key == 'dao') 
			{				
				//control不运行直接访问数据
				//router::dbh已经不存在了
			  $app->error("control::__get('$key')");
			}
			
			//app的属性存在，返回
			if($app->exists($key)) return $app->$key;

			//自动加载模块
			if($app->loadModel($key)) return $app->$key;
			
			$app->error("control::__get('$key')");
		}
}
