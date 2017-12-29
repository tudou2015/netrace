<?php
/**
 * The router, config and lang class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * The router class.
 * 
 * @package framework
 */
class router
{
    /**
     * The directory seperator.
     * 
     * @var string
     * @access private
     */
    private $pathFix;

    /**
     * The base path of the ZenTaoPMS framework.
     *
     * @var string
     * @access private
     */
    private $basePath;

    /**
     * The root directory of the framwork($this->basePath/framework)
     * 
     * @var string
     * @access private
     */
    private $frameRoot;

    /**
     * The root directory of the core library($this->basePath/lib)
     * 
     * @var string
     * @access private
     */
    private $libRoot;


    /**
     * The root directory of temp.
     * 
     * @var string
     * @access private
     */
    private $tmpRoot;

    /**
     * The root directory of cache.
     * 
     * @var string
     * @access private
     */
    private $cacheRoot;

    /**
     * The root directory of log.
     * 
     * @var string
     * @access private
     */
    private $logRoot;

    /**
     * The root directory of config.
     * 
     * @var string
     * @access private
     */
    private $configRoot;

    /**
     * The root directory of module.
     * 
     * @var string
     * @access private
     */
    private $moduleRoot;


    /**
     * The lang of the client user.
     * 
     * @var string
     * @access private
     */
    private $clientLang;

    /**
     * The theme of the client user.
     * 
     * @var string
     * @access private
     */
    private $clientTheme;


    /**
     * The module name
     * 
     * @var string
     * @access private
     */
    private $moduleName;


    /**
     * The name of the method current visiting.
     * 
     * @var string
     * @access private
     */
    private $methodName;


    /**
     * The URI.
     * 
     * @var string
     * @access private
     */
    private $URI;

    /**
     * The params passed in through url.
     * 
     * @var array
     * @access private
     */
    private $params;

    /**
     * The view type.
     * 
     * @var string
     * @access private
     */
    private $viewType;

    /**
     * The global $config object.
     * 
     * @var object
     * @access public
     */
    private $config;

    /**
     * The global $lang object.
     * 
     * @var object
     * @access public
     */
    private $lang;


    /**
     * The $post object, used to access the $_POST var.
     * 
     * @var ojbect
     * @access public
     */
    private $post;

    /**
     * The $get object, used to access the $_GET var.
     * 
     * @var ojbect
     * @access public
     */
    private $get;

    /**
     * The $session object, used to access the $_SESSION var.
     * 
     * @var ojbect
     * @access public
     */
    private $session;

    /**
     * The $server object, used to access the $_SERVER var.
     * 
     * @var ojbect
     * @access public
     */
    private $server;

    /**
     * The $cookie object, used to access the $_COOKIE var.
     * 
     * @var ojbect
     * @access public
     */
    private $cookie;

		//$_ENV的封装
		private $env;
		
		//data base handle
		private $dbh;
		
		//data access object
		private $dao;
	
    /**
     * The construct function.
     * 
     * Prepare all the paths, classes, super objects and so on.
     * Notice: 
     * 1. You should use the createApp() method to get an instance of the router.
     * 2. If the $appRoot is empty, the framework will comput the appRoot according the $appName
     *
     * @param string $appName   the name of the app 
     * @param string $appRoot   the root path of the app
     * @access protected
     * @return void
     */
    
    //单例对象指针
    private static $instance = null;
    
    private function __construct()
    {   
    	  //设置路径信息 	
        $this->setPathInfo();       
				
				//设置超级变量
        $this->setSuperVars();
				
				//加载/config/config.php
				$this->loadConfig();
				
				//语言
				$this->clientLang = $this->config->default->lang;
				
				//根据配置，预先加载类
				foreach($this->config->loadClass as $className)
				{
        	$this->loadClass($className, true);
				} 
				
				//使用spl自动加载类       
				spl_autoload_register(array('router', 'autoload'));
				
				//设置错误报告级别
				error_reporting($this->config->error_reporting);
    }

		/*
		* router是单例的
		*/
		public static function getInstance()
		{
			if(self::$instance != null) return self::$instance;
			
			self::$instance = new router();
			
			return self::$instance;
		}
		
    /**
     * Set the path Info.
     * 
     * @access protected
     * @return void
     */
    private function setPathInfo()
    {
    		//setPathFix
        $this->pathFix = DIRECTORY_SEPARATOR;

    		//setBasePath
        $this->basePath = dirname(dirname(__FILE__)) . $this->pathFix;				
				
    		//setFrameRoot
        $this->frameRoot = $this->basePath . 'framework' . $this->pathFix;

    		//setLibRoot
        $this->libRoot = $this->basePath . 'lib' . $this->pathFix;
    	
    		//setAppLibRoot
        $this->appLibRoot = $this->basePath . 'lib' . $this->pathFix;
	
	    	//setTmpRoot
	      $this->tmpRoot = $this->basePath . 'tmp' . $this->pathFix;
  			
  			//setCacheRoot
        $this->cacheRoot = $this->tmpRoot . 'cache' . $this->pathFix;
    
    		//setLogRoot
        $this->logRoot = $this->tmpRoot . 'log' . $this->pathFix;
		    
		    //setConfigRoot
        $this->configRoot = $this->basePath . 'config' . $this->pathFix;
		    
		    //setModuleRoot
        $this->moduleRoot = $this->basePath . 'module' . $this->pathFix;
    		
    }

    /**
     * Set the super vars.
     * 
     * @access protected
     * @return void
     */
    private function setSuperVars()
    {
        $this->post    = new super('post');
        $this->get     = new super('get');
        $this->server  = new super('server');
        $this->cookie  = new super('cookie');
        $this->session = new super('session');
				$this->request = new super('request');        
				$this->env     = new super('env');        
    }

   //-------------------- Request related methods. --------------------//

    /**
     * The entrance of parseing request. According to the requestType, call related methods.
     * 
     * @access public
     * @return void
     */
    public function parseRequest()
    {
        if($this->config->requestType == 'PATH_INFO')
        {
            $this->parsePathInfo();
            $this->setRouteByPathInfo();
        }
        else//if($this->config->requestType == 'GET')
        {
            $this->parseGET();
            $this->setRouteByGET();
        }
        /*
        else
        {
            $this->error("The request type {$this->config->requestType} not supported");
        }
        */
    }

    /**
     * Parse PATH_INFO, get the $URI and $viewType.
     * 
     * @access public
     * @return void
     */
    private function parsePathInfo()
    {
        $pathInfo = $this->getPathInfo('PATH_INFO');
        if(empty($pathInfo)) $pathInfo = $this->getPathInfo('ORIG_PATH_INFO');
        if(!empty($pathInfo) && $pathInfo <> 'index.php')
        {
            $dotPos = strrpos($pathInfo, '.');
	        
            if($dotPos)
            {
                $this->URI      = substr($pathInfo, 0, $dotPos);
            }
            else
            {
                $this->URI      = $pathInfo;
            }
        }
				
				if(!$this->URI) return;//空URL
				
				//a($this->URI);
				
        $urlencode = $this->config->urlencode;
        if($urlencode->enable)
        {
        	if($this->config->pathType == 'full')
        	{
        		$items = explode($this->config->requestFix, $this->URI);
        		if(count($items) < 2
        			|| $items[0] <> $this->config->urlencode->var) 
        		{
        			//$this->error('URI error:' . $this->URI);
        			//出错，不报错
        			$this->URI = '';
        			$_GET = array();
        			return;
        		}
        		
        		$v = $items[1];
        		
        		$link = parseLinkNew($v, $urlencode);
		       	if($link === false) 
		       	{
        			//$this->error('URI error:' . $this->URI);
        			//出错，不报错
        			$this->URI = '';
        			$_GET = array();
        			return;
		      	}
		       	
		       	array_shift($items);//VV-xxx- 删除VV
		       	array_shift($items);//删除xxx
						
		       	array_unshift($items, $link); //加入$link		
		       	
		       	$this->URI = implode($this->config->requestFix, $items);
       		}
        	else
        	{
        		$items = explode($this->config->requestFix, $this->URI);
        		if(count($items) < 1) 
        		{
        			//$this->error('URI error:' . $this->URI);
        			//出错，不报错
        			$this->URI = '';
        			$_GET = array();
        			return;
        		}
        		
        		$v = $items[0];
        		
        		$link = parseLinkNew($v, $urlencode);
		       	if($link === false) 
		       	{
        			//$this->error('URI error:' . $this->URI);
        			//出错，不报错
        			$this->URI = '';
        			$_GET = array();
        			return;
		      	}
		      	
		       	array_shift($items);
		       	
		       	array_unshift($items, $link);
		       	
		       	$this->URI = implode($this->config->requestFix, $items);        			       	
        	}
        }      
        
        //a($this->URI);
     }

    /**
     * Get $PATH_INFO from $_SERVER or $_ENV by the pathinfo var name.
     *
     * Mostly, the var name of PATH_INFO is  PATH_INFO, but may be ORIG_PATH_INFO.
     * 
     * @param   string  $varName    PATH_INFO, ORIG_PATH_INFO
     * @access  private
     * @return  string the PATH_INFO
     */
    private function getPathInfo($varName)
    {
        $value = @getenv($varName);
        if(isset($_SERVER[$varName])) $value = $_SERVER[$varName];
        return trim($value, '/');
    }

    /**
     * Parse GET, get $URI and $viewType.
     * 
     * @access private
     * @return void
     */
    private function parseGET()
    {
        $this->URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";
        $urlencode = $this->config->urlencode;
        if($urlencode->enable)
        {
        	  if(!isset($_GET[$urlencode->var])) 
        	  {
							$this->URI = '';
							$_GET = array();
							return;
        	  }
        		
        		$v = $_GET[$urlencode->var];
						
						$link = parseLinkNew($v, $urlencode);
						
						if($link === false) //出错，不报错
						{
							$this->URI = '';
							$_GET = array();
							return;
						}
						
						parse_str($link, $_GET2);
						
						$_GET = array_merge($_GET,$_GET2);
						
						$_REQUEST = array_merge($_POST, $_GET);
	      }
    }
    
    /**
     * Get the $URL
     * 
     * @param  bool $full  true, the URI contains the webRoot, else only hte URI.
     * @access public
     * @return string
     */
    public function getURI($full = false)
    {
        if($full and $this->config->requestType == 'PATH_INFO')
        {
            if($this->URI) return $this->config->webRoot . $this->URI . '.' . $this->viewType;
            return $this->config->webRoot;
        }
        return $this->URI;
    }


    //-------------------- Routing related methods.--------------------//

    /**
     * Set the name of the module to be called.
     * 
     * @param   string $moduleName  the module name
     * @access  public
     * @return  void
     */
    private function setModuleName($moduleName)
    {
        if(!ctype_alnum($moduleName)) $this->error('moduleName['. $moduleName.'] error');
        $this->moduleName = $moduleName;
    }
   
    /**
     * Set the name of the method calling.
     * 
     * @param string $methodName 
     * @access public
     * @return void
     */
    private function setMethodName($methodName)
    {
        if(!ctype_alnum($methodName)) $this->error('methodName['. $methodName.'] error');
        $this->methodName = $methodName;
    }


    /**
     * Get the path of one module.
     * 
     * @param  string $moduleName    the module name
     * @access public
     * @return string the module path
     */
    public function getModulePath($moduleName)
    {
        return $this->moduleRoot . $moduleName . $this->pathFix;
    }

    /**
     * Set the route according to PATH_INFO.
     * 
     * 1. set the module name.
     * 2. set the method name.
     * 3. set the control file.
     *
     * @access public
     * @return void
     */
    private function setRouteByPathInfo()
    {
        if(!empty($this->URI))
        {
            /* There's the request seperator, split the URI by it. */
            if(strpos($this->URI, $this->config->requestFix) !== false)
            {
                $items = explode($this->config->requestFix, $this->URI);
                if($this->config->pathType == 'full')
                {
                	if(count($items) < 4 
                		|| $items[0] <> $this->config->moduleVar
                		|| $items[2] <> $this->config->methodVar
                		) 
                	 	$this->error('URI error:' . $this->URI);
                	$this->setModuleName($items[1]);
                	$this->setMethodName($items[3]);
                }
                else
                {
                	if(count($items) < 2) $this->error('URI error:' . $this->URI);
                	$this->setModuleName($items[0]);
                	$this->setMethodName($items[1]);
                }
            }    
            /* No reqeust seperator, use the default method name. */
            else
            {
                $this->setModuleName($this->URI);
                $this->setMethodName($this->config->default->method);
            }
        }
        else
        {    
            $this->setModuleName($this->config->default->module);   // use the default module.
            $this->setMethodName($this->config->default->method);   // use the default method.
        }
    }

    /**
     * Set the route according to GET.
     * 
     * 1. set the module name.
     * 2. set the method name.
     * 3. set the control file.
     *
     * @access public
     * @return void
     */
    private function setRouteByGET()
    {
        $moduleName = isset($_GET[$this->config->moduleVar]) ? $_GET[$this->config->moduleVar] : $this->config->default->module;
        $methodName = isset($_GET[$this->config->methodVar]) ? $_GET[$this->config->methodVar] : $this->config->default->method;
        $this->setModuleName($moduleName);
        $this->setMethodName($methodName);
    }

    /**
     * Load a module.
     *
     * 1. include the control file or the extension action file.
     * 2. create the control object.
     * 3. set the params passed in through url.
     * 4. call the method by call_user_function_array
     * 
     * @access public
     * @return bool|object  if the module object of die.
     */
    private function loadModule($moduleName)
    {
        /* Include the contror file of the module. */
        $file2Included = $this->getModulePath($moduleName).'control.php';
        if(!file_exists($file2Included)) $this->error("the control file $file2Included not found");
        chdir(dirname($file2Included));
        include $file2Included;

        /* Set the class name of the control. */
        $className = $moduleName;
        if(!class_exists($className)) $this->error("the control class $className not found");

        /* Create a instance of the control. */
        $module = new $className();
        
        return $module;
		}
		
		/*
		* 运行指定模块和方法
		*/
		public function run()
		{
			try
			{
				ob_start();
				
				$this->parseRequest();//分析请求，主要是模块和方法
				
				$this->loadModule('common')->filter();//执行拦击操作，主要是session和登录认证
	
				$this->runModule();//运行模块的方法
				
				ob_end_flush();
			}
			catch(Exception $ex)
			{
				ob_end_clean();
				//捕获异常，显示500页面
				echo js::locate($this->config->error_page, 'parent');
			}
		}		
		
		
		/*
		* 运行请求的模块和方法
		*/		
		private function runModule()
		{		
        $moduleName = $this->moduleName;
				$module = $this->loadModule($moduleName);
													
        $methodName = $this->methodName;        
        if(!method_exists($module, $methodName)) $this->error("the module $moduleName has no $methodName method");
				$module->setMethodName($methodName);

        /* Get the default setings of the method to be called useing the reflecting. */
        $defaultParams = array();
        $methodReflect = new reflectionMethod($moduleName, $methodName);
        foreach($methodReflect->getParameters() as $param)
        {
            $name    = $param->getName();
            $default = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : '_NOT_SET';
            $defaultParams[$name] = $default;
        }

        /* Set params according PATH_INFO or GET. */
        if($this->config->requestType == 'PATH_INFO')
        {
            $this->setParamsByPathInfo($defaultParams);
        }
        elseif($this->config->requestType == 'GET')
        {
            $this->setParamsByGET($defaultParams);
        }
								
        /* Call the method. */
        call_user_func_array(array(&$module, $methodName), $this->params);
        return $module;
    }

    /**
     * Set the params by PATH_INFO
     * 
     * @param   array $defaultParams the default settings of the params.
     * @access  public
     * @return  void
     */
    private function setParamsByPathInfo($defaultParams)
    {
        /* Spit the URI. */
        $items     = explode($this->config->requestFix, $this->URI);
        $itemCount = count($items);

        $params = array();
        /* The clean mode, only passed in values, no keys. */
        if($this->config->pathType == 'clean')
        {
            /* The first two item is moduleName and methodName. So the params should begin at 2.*/
            for($i = 2; $i < $itemCount; $i ++)
            {
                $key = key($defaultParams);     // Get key from the $defaultParams.
                $params[$key] = $items[$i];
                next($defaultParams);
            }
        }
        /* The full mode, both key and value passed in. */
        elseif($this->config->pathType == 'full')
        {
            for($i = 4; $i < $itemCount; $i += 2)
            {
                $key   = $items[$i];
                $value = $items[$i + 1];
                $params[$key] = $value;
            }
        }
        
        $_GET = $params;
        
        $_REQUEST = array_merge($_POST, $_GET);
        
        $this->params = $this->mergeParams($defaultParams, $params);
    }

    /**
     * Set the params by GET.
     * 
     * @param   array $defaultParams the default settings of the params.
     * @access  public
     * @return  void
     */
    private function setParamsByGET($defaultParams)
    {
        /* Unset the moduleVar, methodVar, viewVar and session var, all the left are the params. */
        //unset($_GET[$this->config->moduleVar]);
        //unset($_GET[$this->config->methodVar]);
        //unset($_GET[$this->config->viewVar]);
        //unset($_GET[$this->config->sessionVar]);

        $this->params = $this->mergeParams($defaultParams, $_GET);
    }

    /**
     * Merge the params passed in and the default params. Thus the params which have default values needn't pass value, just like a function.
     *
     * @param   array $defaultParams     the default params defined by the method.
     * @param   array $passedParams      the params passed in through url.
     * @access  private
     * @return  array the merged params.
     */
    private function mergeParams($defaultParams, $passedParams)
    {
        /* If the not strict mode, the keys of passed params and defaaul params msut be the same. */
        if(!isset($this->config->strictParams) or $this->config->strictParams == false) 
        {
            $passedParams = array_values($passedParams);
            $i = 0;
            foreach($defaultParams as $key => $defaultValue)
            {
                if(isset($passedParams[$i]))
                {
                    $defaultParams[$key] = $passedParams[$i];
                }
                else
                {
                    if($defaultValue === '_NOT_SET') $this->error("The param '$key' should pass value. ");
                }
                $i ++;
            }
        }
        /* If in strict mode, the keys of the passed params must be the same with the default params, but order can be different. */
        else
        {
            foreach($defaultParams as $key => $defaultValue)
            {
                if(isset($passedParams[$key]))
                {
                    $defaultParams[$key] = $passedParams[$key];
                }
                else
                {
                    if($defaultValue === '_NOT_SET') $this->error("The param '$key' should pass value. ");
                }
            }
        }
        return $defaultParams;
    }
 

    //-------------------- Tool methods.------------------//
    
    /**
     * The error handler.
     * 
     * @param string    $message    error message
     * @param string    $file       the file error occers
     * @param int       $line       the line error occers
     * @param bool      $exit       exit the program or not
     * @access public
     * @return void
     */
    public function error($message)
    {
        /* Log the error info. */
        $log = 'Error: '. $message.', backtrace: ';
        $trace = debug_backtrace();
        foreach($trace as $t)
        {
        	$file = isset($t['file'])?$t['file']:'unkown';
        	$line = isset($t['line'])?$t['line']:'unkown';
        	$function = isset($t['function'])?$t['function']:'unkown';
        	$log .= "[$file,$line,$function]";
        }
			  $errorLog = $this->logRoot . 'error.' . date('Ymd') . '.log';  
				error_log(date('H:i:s'). ' | ' . $log . "\r\n", 3, $errorLog);
				
				//记入系统日志，例如windows event log
				//syslog(LOG_ERR, $log);
				
				if(isset($this->lang->error->fatal))
        	throw new Exception($this->lang->error->fatal);
        else
        	die('fatal error');
    }

    /**
     * Load a class file.
     * 
     * First search in $appLibRoot, then $coreLibRoot.
     *
     * @param   string $className  the class name
     * @param   bool   $static     statis class or not
     * @access  public
     * @return  object|bool the instance of the class or just true.
     */
    public function loadClass($className, $static = false, $exitIfNone = true)
    {
        $classFile = $this->libRoot . $className . $this->pathFix . $className . '.class.php';

        if(!helper::import($classFile))
        {
            if($exitIfNone) $this->error("class file $classFile not found");
            return false;
        }

        /* If staitc, return. */
        if($static) return;
				
        /* Instance it. */
				global $$className;				
        if(!class_exists($className) && $exitIfNone) $this->error("the class $className not found in $classFile");       
        if(!is_object($$className)) $$className = new $className();
        return $$className;
    }

    /**
     * Load config and return it as the global config object.
     * 
     * If the module is common, search in $configRoot, else in $modulePath.
     *
     * @param   string $moduleName     module name
     * @param   bool  $exitIfNone     exit or not
     * @access  public
     * @return  object|bool the config object or false.
     */
    public function loadConfig($moduleName = '', $exitIfNone = true)
    {
        /* Set the main config file and extension config file. */
        if($moduleName == '')
        {
            $configFile = $this->configRoot . 'config.php';
        }
        else
        {
            $configFile = $this->getModulePath($moduleName) . 'config.php';
        }
				
        /* Set the files to include. */
        if(!is_file($configFile))
        {
            if($exitIfNone) self::error("config file $configFile not found");
            return false;  //  and no extension file, exit.
        }
        
        global $config;
        if(!is_object($config)) $config = new stdClass();

        static $loadedConfigs = array();
        if(in_array($configFile, $loadedConfigs)) return $config;

        include $configFile;
        $loadedConfigs[] = $configFile;
        $this->config = $config;
        
        return $config;
    }

    /**
     * Load lang and return it as the global lang object.
     * 
     * @param   string $moduleName     the module name
     * @access  public
     * @return  bool|ojbect the lang object or false.
     */
    public function loadLang($moduleName, $exitIfNone = true)
    {
        $modulePath   = $this->getModulePath($moduleName);
        $langFile = $modulePath . 'lang' . $this->pathFix . $this->clientLang . '.php';

        /* Set the files to includ. */
        if(!is_file($langFile))
        {
            if($exitIfNone) self::error("lang file $langFile not found");
            return false;  //  and no extension file, exit.
				}

        global $lang;
        if(!is_object($lang)) $lang = new stdClass();

        static $loadedLangs = array();
        if(in_array($langFile, $loadedLangs)) return $lang;

        include $langFile;
        $loadedLangs[] = $langFile;

        $this->lang = $lang;
        return $lang;
    }
    
    /**
     * Load the model of one module. After loaded, can use $this->modulename to visit the model object.
     * 
     * @param   string  $moduleName
     * @access  public
     * @return  object|bool  the model object or false if model file not exists.
     */
    public function loadModel($moduleName)
    {    	     	  
    		//模块已加载
    		if(isset($this->$moduleName)) return $this->$moduleName;
    		
        if(empty($moduleName)) return false;
        
        $modelFile = $this->getModulePath($moduleName).'model.php';

        if(!helper::import($modelFile)) return false;
        $modelClass = $moduleName . 'Model';
        if(!class_exists($modelClass)) return false;

        $this->$moduleName = new $modelClass();
        return $this->$moduleName;
    }
        
    /*
    * 判断private属性是否存在
    */
    public function exists($key)
    {
    	return isset($this->$key);
    }
    
    /*
    *由于所有属性都是private,不能直接访问，通过定义__get拦击器暴露出来
    */
    public function __get($key)
		{
			if(isset($this->$key)) return $this->$key;
			$this->error("router::__get('$key')");
		}
		
		/**
		* 自动加载类
		* @param $class 类名
		*/
		public static function autoload($class)
		{
			//未加载成功可以不退出,执行下一个加载操作(spl)
			self::getInstance()->loadClass($class, true, false);
		} 		
		
 		/*
		* 载入DAO对象
		*/
		public function loadDAO()
		{
			if(isset($this->dao)) return $this->dao;
						
				//载入DBH对象,dbh是数据库连接句柄,是一个PDO对象
				$dbh = $this->connectByPDO($this->config->db);
				
 	      $dao = $this->loadClass('dao');//内部同时执行$this->dao = $dao;
 	      
 	      $dao->dbh($dbh)->driver($this->config->db->driver);
 	       	      
 	      $this->dao = $dao;
 	      
 	      return $dao;
 	  }
    
    /**
     * Connect database by PDO.
     * 
     * @param  object    $params    the database params.
     * @access private
     * @return object|bool
     */
    private function connectByPDO($params)
    {    		
        if(!isset($params->driver)) $this->error('no pdo driver defined');
        switch($params->driver)
        {
        	case 'mysql':
            $dsn = "mysql:host={$params->host}; port={$params->port}; dbname={$params->name}";
            break;
        	case 'mssql':
        		$dsn = "sqlsrv:Server={$params->host};Database={$params->name};LoginTimeout=30";
        		break;
        	default:
        		$app->error('connectByPDO');
        }
        
        try 
        {
            $dbh = new PDO($dsn, $params->user, $params->password, array(PDO::ATTR_PERSISTENT => $params->persistant));
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $dbh;
        }
        catch (PDOException $exception)
        {
            $this->error($exception->getMessage());
        }
    }     		
}

/**
 * The super object class.
 * 
 * @package framework
 */
class super
{
    /**
     * Construct, set the var scope.
     * 
     * @param   string $scope  the score, can be server, post, get, cookie, session, global
     * @access  public
     * @return  void
     */
    public function __construct($scope)
    {
        $this->scope = $scope;
       	$this->inited = 0;
    }
		
		//初始化session,延迟加载
		private function init()
		{
        if($this->scope == 'session')
        {
       		router::getInstance()->common->startSession();			
       		$this->inited = 1;
       	}       		
		}
		
    /**
     * Set one member value. 
     * 
     * @param   string    the key
     * @param   mixed $value  the value
     * @access  public
     * @return  void
     */
    public function set($key, $value)
    {
        if($this->scope == 'post')
        {
            $_POST[$key] = $value;
        }
        elseif($this->scope == 'get')
        {
            $_GET[$key] = $value;
        }
        elseif($this->scope == 'server')
        {
            $_SERVER[$key] = $value;
        }
        elseif($this->scope == 'cookie')
        {
            $_COOKIE[$key] = $value;
        }
        elseif($this->scope == 'session')
        {
        		if(!$this->inited) $this->init();
            $_SESSION[$key] = $value;
        }
        elseif($this->scope == 'env')
        {
            $_ENV[$key] = $value;
        }
        elseif($this->scope == 'request')
        {
            $_REQUEST[$key] = $value;
        }
        elseif($this->scope == 'global')
        {
            $GLOBAL[$key] = $value;
        }
    }

    /**
     * The magic get method.
     * 
     * @param  string $key    the key
     * @access public
     * @return mixed|bool return the value of the key or false.
     */
     /*
     如果没有索引，原先的处理方法是返回false，导致在browser中处理
     array(0=>'未审核',1=>'已审核')查询条件时，false能自动转换成0，满足
     isset($this->lang->xxx->auditMap[$audit])) $search[]="audit='$audit'";
     加入查询条件"audit=''"，无法获取有效数据，出错
     现在修改为返回空字符串
     */
    public function __get($key)
    {
        if($this->scope == 'get')
        {
            if(isset($_GET[$key])) return $_GET[$key];
            return '';
        }
        elseif($this->scope == 'post')
        {
            if(isset($_POST[$key])) return $_POST[$key];
            return '';
        }
        elseif($this->scope == 'session')
        {
        		if(!$this->inited) $this->init();
            if(isset($_SESSION[$key])) return $_SESSION[$key];
            return '';
        }
        elseif($this->scope == 'request')
        {
            if(isset($_REQUEST[$key])) return $_REQUEST[$key];
            return '';
        }
        elseif($this->scope == 'server')
        {
            if(isset($_SERVER[$key])) return $_SERVER[$key];
            $key = strtoupper($key);
            if(isset($_SERVER[$key])) return $_SERVER[$key];
            return '';
        }
        elseif($this->scope == 'cookie')
        {
            if(isset($_COOKIE[$key])) return $_COOKIE[$key];
            return '';
        }
        elseif($this->scope == 'env')
        {
            if(isset($_ENV[$key])) return $_ENV[$key];
            return '';
        }
        elseif($this->scope == 'global')
        {
            if(isset($GLOBALS[$key])) return $GLOBALS[$key];
            return '';
        }
        else
        {
            return '';
        }
    }

    /**
     * Print the structure.
     * 
     * @access public
     * @return void
     */
    public function a()
    {
        if($this->scope == 'get')     		a($_GET);
        elseif($this->scope == 'post')    a($_POST);
        elseif($this->scope == 'session') 
        {
      		if(!$this->inited) $this->init();
        	a($_SESSION);
        }
        elseif($this->scope == 'request') a($_REQUEST);
        elseif($this->scope == 'server')  a($_SERVER);
        elseif($this->scope == 'cookie')  a($_COOKIE);
        elseif($this->scope == 'env')     a($_ENV);
        elseif($this->scope == 'global')  a($GLOBAL);
    }
    
		/*
		删除某个索引
		*/
    public function remove($key)
    {
        if($this->scope == 'get')     		unset($_GET[$key]);
        elseif($this->scope == 'post')    unset($_POST[$key]);
        elseif($this->scope == 'session') 
        {
      		if(!$this->inited) $this->init();
        	unset($_SESSION[$key]);
        }
        elseif($this->scope == 'request') unset($_REQUEST[$key]);
        elseif($this->scope == 'server')  unset($_SERVER[$key]);
        elseif($this->scope == 'cookie')  unset($_COOKIE[$key]);
        elseif($this->scope == 'env')     unset($_ENV[$key]);
        elseif($this->scope == 'global')  unset($GLOBAL[$key]);
    }
}
