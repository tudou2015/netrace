<?php
/**
 * The dao and sql class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * DAO, data access object.
 * 
 * @package framework
 */
class dao
{
    /* Use these strang strings to avoid conflicting with these keywords in the sql body. */
    const WHERE   = 'WHERE';
    const GROUPBY = 'GROUP BY';
    const HAVING  = 'HAVING';
    const ORDERBY = 'ORDER BY';
    const LIMIT   = 'LIMIT';

    /**
     * The global dbh(database handler) object.
     * 
     * @var object
     * @access protected
     */
    private $dbh;

		private $driver;
		
		private $limit;
		
    /**
     * The sql object, used to creat the query sql.
     * 
     * @var object
     * @access protected
     */
    private $sqlobj;

    /**
     * The table of current query.
     * 
     * @var string
     * @access public
     */
    private $table;

    /**
     * The alias of $this->table.
     * 
     * @var string
     * @access public
     */
    private $alias;

    /**
     * The fields will be returned.
     * 
     * @var string
     * @access public
     */
    private $fields;

    /**
     * The query mode, raw or magic.
     * 
     * This var is used to diff dao::from() with sql::from().
     *
     * @var string
     * @access public
     */
    private $mode;

    /**
     * The query method: insert, select, update, delete, replace.
     *
     * @var string
     * @access public
     */
    private $method;

    /**
     * The queries executed. Every query will be saved in this array.
     * 
     * @var array
     * @access public
     */
    static private $querys = array();


		static public function getAllQuerys()
		{
			return self::$querys;
		}
		
    /**
     * The construct method.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->reset();
    }


    /**
     * Set the $table property.
     * 
     * @param  string $table 
     * @access private
     * @return void
     */
    private function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * Set the $alias property.
     * 
     * @param  string $alias 
     * @access private
     * @return void
     */
    private function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * Set the $fields property.
     * 
     * @param  string $fields 
     * @access private
     * @return void
     */
    private function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * Reset the vars.
     * 
     * @access private
     * @return void
     */
    private function reset()
    {
        $this->setFields('');
        $this->setTable('');
        $this->setAlias('');
        $this->setMode('');
        $this->setMethod('');
        
        $this->limit = null;
    }

    //-------------------- According to the query method, call according method of sql class. --------------------//

    /**
     * Set the query mode. If the method if like findByxxx, the mode is magic. Else, the mode is raw.
     * 
     * @param  string $mode     magic|raw
     * @access private
     * @return void
     */
    private function setMode($mode = '')
    {
        $this->mode = $mode;
    }

    /**
     * Set the query method: select|update|insert|delete|replace 
     * 
     * @param  string $method 
     * @access private
     * @return void
     */
    private function setMethod($method = '')
    {
        $this->method = $method;
    }

    /**
     * The select method, call sql::select().
     * 
     * @param  string $fields 
     * @access public
     * @return object the dao object self.
     */
    public function select($fields = '*')
    {
        $this->setMode('raw');
        $this->setMethod('select');
        $this->sqlobj = sql::select($fields);
        $this->sqlobj->dbh($this->dbh)->driver($this->driver);
        
        $this->limit = null;
        
        return $this;
    }

    /**
     * The select method, call sql::update().
     * 
     * @param  string $table 
     * @access public
     * @return object the dao object self.
     */
    public function update($table)
    {
        $this->setMode('raw');
        $this->setMethod('update');
        $this->sqlobj = sql::update($table);
        $this->sqlobj->dbh($this->dbh)->driver($this->driver);
        $this->setTable($table);
        return $this;
    }

    /**
     * The delete method, call sql::delete().
     * 
     * @access public
     * @return object the dao object self.
     */
    public function delete()
    {
        $this->setMode('raw');
        $this->setMethod('delete');
        $this->sqlobj = sql::delete();
        $this->sqlobj->dbh($this->dbh)->driver($this->driver);
        return $this;
    }

    /**
     * The insert method, call sql::insert().
     * 
     * @param  string $table 
     * @access public
     * @return object the dao object self.
     */
    public function insert($table)
    {
        $this->setMode('raw');
        $this->setMethod('insert');
        $this->sqlobj = sql::insert($table);
        $this->sqlobj->dbh($this->dbh)->driver($this->driver);
        $this->setTable($table);
        return $this;
    }

    /**
     * The insert method, call sql::insert().
     * 
     * @param  string $table 
     * @access public
     * @return object the dao object self.
     */
    public function bulkInsert($table)
    {
    		if($this->driver != 'mysql') 
    		{
    			router::getInstance()->error('dao::bulkInsert not support');
    		}
    		
        $this->setMode('raw');
        $this->setMethod('bulkInsert');
        $this->sqlobj = sql::bulkInsert($table);
        $this->sqlobj->dbh($this->dbh)->driver($this->driver);
        $this->setTable($table);
        return $this;
    }

    /**
     * Set the from table.
     * 
     * @param  string $table 
     * @access public
     * @return object the dao object self.
     */
    public function from($table) 
    {
        $this->setTable($table);
        if($this->mode == 'raw') $this->sqlobj->from($table);
        return $this;
    }

    /**
     * Set the fields.
     * 
     * @param  string $fields 
     * @access public
     * @return object the dao object self.
     */
    public function fields($fields)
    {
        $this->setFields($fields);
        return $this;
    }

    /**
     * Alias a table, equal the AS keyword. (Don't use AS, because it's a php keyword.)
     * 
     * @param  string $alias 
     * @access public
     * @return object the dao object self.
     */
    public function alias($alias)
    {
        if(empty($this->alias)) $this->setAlias($alias);
        $this->sqlobj->alias($alias);
        return $this;
    }

    /**
     * Set the data to update or insert.
     * 
     * @param  object $data         the data object or array
     * @access public
     * @return object the dao object self.
     */
    public function data($data)
    {
        if(!is_object($data)) $data = (object)$data;
        if($this->method == 'insert')
        	$this->sqlobj->idata($data);
        else
        	$this->sqlobj->data($data);
        
        return $this;
    }

    /**
     * Set the data to update or insert.
     * 
     * @param  object $data         the data object or array
     * @access public
     * @return object the dao object self.
     */
    public function datas($datas)
    {
    		if($this->driver != 'mysql') 
    		{
    			router::getInstance()->error('dao::datas not support');
    		}
    		    	
        if(!is_array($datas)) 
        {
					router::getInstance()->error('Must be array');        	
        }
        if(!count($datas)) 
        {
					router::getInstance()->error('Array is empty');        	
        }
        $this->sqlobj->datas($datas);
        return $this;
    }

    //-------------------- The sql related method. --------------------//

    /**
     * Get the sql string.
     * 
     * @access public
     * @return string the sql string after process.
     */
    public function get()
    {
        return $this->sqlobj->get();
    }

    //-------------------- Query related methods. --------------------//
    
    /**
     * Set the dbh. 
     * 
     * You can use like this: $this->dao->dbh($dbh), thus you can handle two database.
     *
     * @param  object $dbh 
     * @access public
     * @return object the dao object self.
     */
    public function dbh($dbh)
    {
        $this->dbh = $dbh;
        return $this;
    }

		//设置驱动类型
		public function driver($driver)
		{
			$this->driver = $driver;
			return $this;
		}
		
    /**
     * Query the sql, return the statement object.
     * 
     * @access public
     * @return object   the PDOStatement object.
     */
    public function query()
    {    	
        $sql = $this->get();
        
        self::$querys[] = $sql;
        
        try
        {
            $this->reset();

            return $this->dbh->query($sql);
        }
        catch (PDOException $e) 
        {
            router::getInstance()->error($e->getMessage() . "[$sql]");
        }
    }

    /**
     * Page the records, set the limit part auto.
     * 
     * @param  object $pager 
     * @access public
     * @return object the dao object self.
     */
    public function page($pager)
    {
        if(!is_object($pager)) return $this;

        /* If the record total is 0, compute it. */
        if($pager->recTotal == 0)
        {
            /* Get the SELECT, FROM position, thus get the fields, replace it by count(*). */
            $sql       = $this->get();
            $selectPOS = strpos($sql, 'SELECT') + strlen('SELECT');
            $fromPOS   = strpos($sql, 'FROM');
            $fields    = substr($sql, $selectPOS, $fromPOS - $selectPOS );
            $sql       = str_replace($fields, ' COUNT(*) AS recTotal ', $sql);
						
            /* Remove the part after order and limit. */
            $subLength = strlen($sql);
            $orderPOS  = strripos($sql, 'order');
            $limitPOS  = strripos($sql , 'limit');
            if($limitPOS) $subLength = $limitPOS;
            if($orderPOS) $subLength = $orderPOS;
            $sql = substr($sql, 0, $subLength);
            self::$querys[] = $sql;
						
            /* Get the records count. */
            try
            {
                $row = $this->dbh->query($sql)->fetch(PDO::FETCH_OBJ);
            }
            catch (PDOException $e) 
            {
                router::getInstance()->error($e->getMessage() . "[$sql]");
            }
            						
            $pager->setRecTotal($row->recTotal);
            $pager->setPageTotal();
            $pager->setPageID();
        }
        
       	$limit = $pager->limit();
				if(!$limit) return $this;
				
        if($this->driver == 'mysql')
      	{
      		$this->sqlobj->limit($limit);
      	}
        else
        {
        	//不支持limit语法
	       		$this->limit = $limit;
  			}      

       	return $this;
    }

    /**
    /* Execute the sql. It's different with query(), which return the stmt object. But this not.
     * 
     * @access public
     * @return int the modified or deleted records.
     */
    public function exec()
    {
        $sql = $this->get();
        
        self::$querys[] = $sql;
        
        try
        {
            $this->reset();
            return $this->dbh->exec($sql);
        }
        catch (PDOException $e) 
        {
            router::getInstance()->error($e->getMessage() . "[$sql]");
        }
    }

    //-------------------- Fetch related methods. -------------------//

    /**
     * Fetch one record.
     * 
     * @param  string $field        if the field is set, only return the value of this field, else return this record
     * @access public
     * @return object|mixed
     */
    public function fetch($field = '')
    {
        if(empty($field)) return $this->query()->fetch();
        $this->setFields($field);
        $result = $this->query()->fetch(PDO::FETCH_OBJ);
        if($result) return $result->$field;
    }

    /**
     * Fetch all records.
     * 
     * @param  string $keyField     the key field, thus the return records is keyed by this field
     * @access public
     * @return array the records
     */
    public function fetchAll($keyField = '')
    {  
    	if(!$this->limit)
    	{
        $stmt = $this->query();
        if(empty($keyField)) return $stmt->fetchAll();
        $rows = array();
        while($row = $stmt->fetch()) $rows[$row->$keyField] = $row;
        return $rows;
      }
      else
      {
				///*sql不支持分页时的处理
				$start = $this->limit[0];
				$end = $this->limit[1];

        $stmt = $this->query();
				
				$i = 0;
       	while($i < $start) 
       	{
       		$row = $stmt->fetch();
       		if(!$row) return array();
       		$i++;
       	}
       	
       	$rows = array();
       	if(empty($keyField))
       	{
       		while($i < $end) 
       		{
       			$row = $stmt->fetch();
       			if(!$row) break;
       			$rows[] = $row;
       			$i ++;
       		}
       	}
       	else
       	{
       		while($i < $end) 
       		{
	       		$row = $stmt->fetch();
  	     		if(!$row) break;
    	   		$rows[$row->$keyField] = $row;
      	 		$i ++;
      	 	}
      	}
	      return $rows;			        
	    }
	      //*/
    }

    /**
     * Fetch array like key=>value.
     *
     * If the keyFiled and valueField not set, use the first and last in the record.
     * 
     * @param  string $keyField 
     * @param  string $valueField 
     * @access public
     * @return array
     */
    public function fetchPairs($keyField = '', $valueField = '')
    {
    		if($this->limit) router::getInstance()->error('dao::fetchPairs has limit');
    		
        $pairs = array();
        $ready = false;
        $stmt  = $this->query();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            if(!$ready)
            {
                if(empty($keyField)) $keyField = key($row);
                if(empty($valueField)) 
                {
                    end($row);
                    $valueField = key($row);
                }
                $ready = true;
            }

            $pairs[$row[$keyField]] = $row[$valueField];
        }
        return $pairs;
    }

    //-------------------- Magic methods.--------------------//

    /**
     * Use it to do some convenient queries.
     * 
     * @param  string $funcName  the function name to be called
     * @param  array  $funcArgs  the params
     * @access public
     * @return object the dao object self.
     */
    public function __call($funcName, $funcArgs)
    {  
         /* Create the max counts of sql class methods, and then create $arg0, $arg1... */
         for($i = 0; $i < SQL::MAX_ARGS; $i ++)
         {
                ${"arg$i"} = isset($funcArgs[$i]) ? $funcArgs[$i] : null;
         }

         $this->sqlobj->$funcName($arg0, $arg1, $arg2);
         
         return $this;//连贯操作
    }

    

    /**
     * Return the last insert ID.
     * 
     * @access public
     * @return int
     */
    public function lastInsertID()
    {
        return $this->dbh->lastInsertID();
    }
}

/**
 * The SQL class.
 * 
 * @package framework
 */
class sql
{
    /**
     * The max count of params of all methods.
     * 
     */
    const MAX_ARGS = 3;

    /**
     * The sql string.
     * 
     * @var string
     * @access private
     */
    private $sql = '';

    /**
     * The global $dbh.
     * 
     * @var object
     * @access protected
     */
    private $dbh;

		//驱动类型,mysql,mssql
		private $driver;
		
		//列名为关键字时，转移字符,mysql下为反引号(`),mssql下为方括号([])
		private $leftEscape;		
		private $rightEscape;		
		
    /**
     * The data to update or insert.
     * 
     * @var mix
     * @access protected
     */
    private $data;

    /**
     * Is the first time to  call set.
     * 
     * @var bool    
     * @access private;
     */
    private $isFirstSet = true;

    /**
     * If in the logic of judge condition or not.
     * 
     * @var bool
     * @access private;
     */
    private $inCondition = false;

    /**
     * The condition is true or not.
     * 
     * @var bool
     * @access private;
     */
    private $conditionIsTrue = false;


    /**
     * The construct function. user factory() to instance it.
     * 
     * @param  string $table 
     * @access private
     * @return void
     */
    private function __construct()
    {
        //$this->magicQuote = get_magic_quotes_gpc();
    }

		public function dbh($dbh)
		{
			$this->dbh = $dbh;
			
			return $this;
		}


		public function driver($driver)
		{
			$this->driver = $driver;
			switch($driver)
			{
				case 'mysql':
					$this->leftEscape = '`';
					$this->rightEscape = '`';
					break;
				case 'mssql':
					$this->leftEscape = '[';
					$this->rightEscape = ']';
					break;
				default:
					$this->leftEscape = '';
					$this->rightEscape = '';
					break;								
			}			
			return $this;
		}
		
    /**
     * The factory method.
     * 
     * @param  string $table 
     * @access public
     * @return object the sql object.
     */
    public static function factory()
    {
        return new sql();
    }

    /**
     * The sql is select.
     * 
     * @param  string $field 
     * @access public
     * @return object the sql object.
     */
    public static function select($field = '*')
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "SELECT $field ";
        return $sqlobj;
    }

    /**
     * The sql is update.
     * 
     * @param  string $table 
     * @access public
     * @return object the sql object.
     */
    public static function update($table)
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "UPDATE $table SET ";
        return $sqlobj;
    }

    /**
     * The sql is insert.
     * 
     * @param  string $table 
     * @access public
     * @return object the sql object.
     */
    public static function insert($table)
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "INSERT INTO $table ";
        return $sqlobj;
    }

    /**
     * The sql is insert.
     * 
     * @param  string $table 
     * @access public
     * @return object the sql object.
     */
    public static function bulkInsert($table)
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "INSERT INTO $table ";
        return $sqlobj;
    }

    /**
     * The sql is delete.
     * 
     * @access public
     * @return object the sql object.
     */
    public static function delete()
    {
        $sqlobj = self::factory();
        $sqlobj->sql = "DELETE ";
        return $sqlobj;
    }

    /**
     * Join the data items by key = value.
     * 
     * @param  object $data 
     * @access public
     * @return object the sql object.
     */
    public function data($data)
    {
        $this->data = $data;
        foreach($data as $field => $value) $this->sql .= $this->escape($field) . ' = ' . $this->quote($value) . ',';
        $this->sql = rtrim($this->sql, ',');    // Remove the last ','.
        return $this;
    }

    /**
     * insert data
     * 
     * @param  object $data 
     * @access public
     * @return object the sql object.
     */
    public function idata($data)
    {
        $this->data = $data;
        $this->sql .= '(';
        foreach($data as $field => $value) $this->sql .= $this->escape($field). ',';
        $this->sql = rtrim($this->sql, ',') . ') values (';    // Remove the last ','.
        foreach($data as $field => $value) $this->sql .= $this->quote($value) . ',';
        $this->sql = rtrim($this->sql, ',') .')';    // Remove the last ','.
        return $this;
    }

    /**
     * Join the data items by key = value.
     * 
     * @param  object $data 
     * @access public
     * @return object the sql object.
     */
    public function datas($datas)
    {
        //$this->data = $data;
        reset($datas);
        list($i, $data) = each($datas);        
        $this->sql .= '(';
        foreach($data as $field => $value) $this->sql .= $this->escape($field). ',';
        $this->sql = rtrim($this->sql, ',');
        $this->sql .= ') values';
        
        foreach($datas as $data) 
        {
        	$this->sql .= '(';
        	foreach($data as $value)
        	{
        		$this->sql .= $this->quote($value) . ',';
        	}
        	$this->sql = rtrim($this->sql, ',');    // Remove the last ','.
        	$this->sql .= '),';
        }
        
       	$this->sql = rtrim($this->sql, ',');    // Remove the last ','.
        return $this;
    }

    /**
     * Aadd an '(' at left.
     * 
     * @param  int    $count 
     * @access public
     * @return ojbect the sql object.
     */
    public function markLeft($count = 1)
    {
        $this->sql .= str_repeat('(', $count);
        return $this;
    }

    /**
     * Add an ')' ad right.
     * 
     * @param  int    $count 
     * @access public
     * @return object the sql object.
     */
    public function markRight($count = 1)
    {
        $this->sql .= str_repeat(')', $count);
        return $this;
    }

    /**
     * The set part.
     * 
     * @param  string $set 
     * @access public
     * @return object the sql object.
     */
    public function set($set)
    {
        if($this->isFirstSet)
        {
            $this->sql .= " $set ";
            $this->isFirstSet = false;
        }
        else
        {
            $this->sql .= ", $set";
        }
        return $this;
    }

    /**
     * Create the from part.
     * 
     * @param  string $table 
     * @access public
     * @return object the sql object.
     */
    public function from($table)
    {
        $this->sql .= "FROM $table";
        return $this;
    }

    /**
     * Create the Alias part.
     * 
     * @param  string $alias 
     * @access public
     * @return object the sql object.
     */
    public function alias($alias)
    {
        $this->sql .= " AS $alias ";
        return $this;
    }

    /**
     * Create the left join part.
     * 
     * @param  string $table 
     * @access public
     * @return object the sql object.
     */
    public function leftJoin($table)
    {
        $this->sql .= " LEFT JOIN $table";
        return $this;
    }

    /**
     * Create the on part.
     * 
     * @param  string $condition 
     * @access public
     * @return object the sql object.
     */
    public function on($condition)
    {
        $this->sql .= " ON $condition ";
        return $this;
    }

    /**
     * Begin condition judge.
     * 
     * @param  bool $condition 
     * @access public
     * @return object the sql object.
     */
    public function beginIF($condition)
    {
        $this->inCondition = true;
        $this->conditionIsTrue = $condition;
        return $this;
    }

    /**
     * End the condition judge.
     * 
     * @access public
     * @return object the sql object.
     */
    public function fi()
    {
        $this->inCondition = false;
        $this->conditionIsTrue = false;
        return $this;
    }

    /**
     * Create the where part.
     * 
     * @param  string $arg1     the field name
     * @param  string $arg2     the operator
     * @param  string $arg3     the value
     * @access public
     * @return object the sql object.
     */
    public function where($arg1, $arg2 = null, $arg3 = null)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        if($arg3 !== null)
        {
            $value     = $this->quote($arg3);
            $condition = "$arg1 $arg2 " . $this->quote($arg3);
        }
        else
        {
            $condition = $arg1;
        }

        $this->sql .= ' ' . DAO::WHERE ." $condition ";
        return $this;
    } 

    /**
     * Create the AND part.
     * 
     * @param  string $condition 
     * @access public
     * @return object the sql object.
     */
    public function andWhere($condition)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " AND $condition ";
        return $this;
    }

    /**
     * Create the OR part.
     * 
     * @param  bool  $condition 
     * @access public
     * @return object the sql object.
     */
    public function orWhere($condition)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " OR $condition ";
        return $this;
    }

    /**
     * Create the '='.
     * 
     * @param  string $value 
     * @access public
     * @return object the sql object.
     */
    public function eq($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " = " . $this->quote($value);
        return $this;
    }

    /**
     * Create '!='.
     * 
     * @param  string $value 
     * @access public
     * @return void the sql object.
     */
    public function ne($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " != " . $this->quote($value);
        return $this;
    }

    /**
     * Create '>'.
     * 
     * @param  string $value 
     * @access public
     * @return object the sql object.
     */
    public function gt($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " > " . $this->quote($value);
        return $this;
    }

    /**
     * Create '<'.
     * 
     * @param  mixed  $value 
     * @access public
     * @return object the sql object.
     */
    public function lt($value)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " < " . $this->quote($value);
        return $this;
    }

    /**
     * Create "between and"
     * 
     * @param  string $min 
     * @param  string $max 
     * @access public
     * @return object the sql object.
     */
    public function between($min, $max)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $min = $this->quote($min);
        $max = $this->quote($max);
        $this->sql .= " BETWEEN $min AND $max ";
        return $this;
    }

    /**
     * Create in part.
     * 
     * @param  string|array $ids   list string by ',' or an array
     * @access public
     * @return object the sql object.
     */
    public function in($ids)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= helper::dbIN($ids);
        return $this;
    }

    /**
     * Create not in part.
     * 
     * @param  string|array $ids   list string by ',' or an array
     * @access public
     * @return object the sql object.
     */
    public function notin($ids)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= ' NOT ' . helper::dbIN($ids);
        return $this;
    }

    /**
     * Create the like by part.
     * 
     * @param  string $string 
     * @access public
     * @return object the sql object.
     */
    public function like($string)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= " LIKE " . $this->quote($string);
        return $this;
    }

    /**
     * Create the order by part.
     * 
     * @param  string $order 
     * @access public
     * @return object the sql object.
     */
    public function orderBy($order)
    {
        if($this->inCondition and !$this->conditionIsTrue) return $this;
        $this->sql .= ' ' . DAO::ORDERBY . " $order";
        return $this;
    }

    /**
     * Create the limit part.
     * 
     * @param  string $limit 
     * @access public
     * @return object the sql object.
     */
    public function limit($limit)
    {    		    	
        if($this->driver != 'mysql') return $this; //由fetchAll处理

       	if(is_array($limit))
       	{
        		$start = $limit[0];
        		$end = $limit[1];
        		$len = $end - $start;
        		$limit = "$start,$len";
       	}
     		$this->sql .= ' ' . DAO::LIMIT . " $limit ";

       	return $this;
    }

    /**
     * Create the groupby part.
     * 
     * @param  string $groupBy 
     * @access public
     * @return object the sql object.
     */
    public function groupBy($groupBy)
    {
        $this->sql .= ' ' . DAO::GROUPBY . " $groupBy";
        return $this;
    }

    /**
     * Create the having part.
     * 
     * @param  string $having 
     * @access public
     * @return object the sql object.
     */
    public function having($having)
    {
        $this->sql .= ' ' . DAO::HAVING . " $having";
        return $this;
    }

    /**
     * Get the sql string.
     * 
     * @access public
     * @return string
     */
    public function get()
    {
        return $this->sql;
    }

    /**
     * Uuote a var.
     * 
     * @param  mixed  $value 
     * @access public
     * @return mixed
     */
    private function quote($value)
    {
        return $this->dbh->quote($value);
    }    

		//列名为关键字时转义    
    private function escape($value)
    {
        return $this->leftEscape. $value. $this->rightEscape;
    }    

    public function beginTransaction()
    {
    	$this->dbh->beginTransaction();
    	return $this;
    }
    
    public function commit()
    {
    	$this->dbh->commit();
    	return $this;
    }
}
