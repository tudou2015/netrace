<?php
/**
 * The model class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * The base class of model.
 * 
 * @package framework
 */
abstract class model
{
	
		protected $moduleName;
		
    /**
     * The construct function.
     *
     * 1. global the global vars, refer them by the class member such as $this->app.
     * 2. set the pathes, config, lang of current module
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->moduleName = $this->getModuleName();
        
        $app = router::getInstance();
        
        $app->loadLang($this->moduleName,   $exit = false);
        $app->loadConfig($this->moduleName, $exit = false);     
    }

    /**
     * Get the module name of this model. Not the module user visiting.
     *
     * This method replace the 'ext' and 'model' string from the model class name, thus get the module name.
     * Not useing $app->getModuleName() because it return the module user is visiting. But one module can be
     * loaded by loadModel() so we must get the module name of thie model.
     * 
     * @access protected
     * @return string the module name.
     */
    protected function getModuleName()
    {
        $className   = get_class($this);
        return str_replace('Model', '', $className);
    }

    //-------------------- DAO related method s--------------------//

		public function __get($key)
		{
			if(isset($this->$key)) return $this->$key;

			$app = router::getInstance();
			
			if($key == 'app') return $app;
			
			if($app->exists($key)) return $app->$key;
			
			/*
			延迟加载dao
			*/
			if($key == 'dao')
			{
 	      return $app->loadDAO();
			}						

			/*
			延迟加载dbh,
			router::dbh已经不存在了,不再提供dbh,model只能通过dao访问数据库
			if($key == 'dbh')
			{
 	      return $app->loadDBH();
			}
			*/			

			//自动加载模块
			if($app->loadModel($key)) return $app->$key;
			
			$app->error("model::__get('$key')");
		}
				

   /**
     * Delete one record.
     * 
     * @param  string    $table  the table name
     * @param  string    $id     the id value of the record to be deleted
     * @access public
     * @return void
     */
    protected function delete($table, $id)
    {
        $this->dao->update($table)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
        $object = str_replace($this->config->db->prefix, '', $table);
        $this->action->create($object, $id, 'delete');
    }     	   
}    
