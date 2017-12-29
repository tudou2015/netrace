<?php
/**
 * The helper class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * The helper class, contains the tool functions.
 *
 * @package framework
 */
class helper
{
    /**
     * Location to another page.
     * 
     * @param   string   $url   the target url.
     * @access  public
     * @return  void
     */
    static public function locate($url)
    {
        //header("location: $url");
        echo js::locate($url);
        exit;
    }	
	
    /**
     * Create a link to a module's method.
     * 
     * This method also mapped in control class to call conveniently.
     * @param string       $moduleName     module name
     * @param string       $methodName     method name
     * @param array $vars_enc              the params passed to the method can be encode, must be array('key' => 'value') 
     * @param array $vars_noenc            the params passed to the method can not be encode, must be array('key' => 'value') 
     * @static
     * @access public
     * @return string the link string.
     */
    static public function createLink($moduleName, $methodName, $vars_enc = array(), $vars_noenc = array())
    {
    		$app = router::getInstance();
				$urlencode = $app->config->urlencode;
				
        /* The PATH_INFO type. */
        if($app->config->requestType == 'PATH_INFO')
        {
            /* If the method equal the default method defined in the config file and the vars is empty, convert the link. */
          	
                if($app->config->pathType == 'full')
                {										
										$vars_enc_result = array();
										foreach($vars_enc as $key => $value)
										{
											$vars_enc_result[] = "{$key}{$app->config->requestFix}{$value}";
										}
										
										//将m和f插入头部
										array_unshift($vars_enc_result,"{$app->config->methodVar}{$app->config->requestFix}{$methodName}");
										array_unshift($vars_enc_result,"{$app->config->moduleVar}{$app->config->requestFix}{$moduleName}");
										
										$vars_noenc_result = array();
										foreach($vars_noenc as $key => $value)
										{
											$vars_noenc_result[] = "{$key}{$app->config->requestFix}{$value}";
										}

										if($urlencode->enable)
										{ 
											//shuffle($vars_enc_result);
											$link = implode($app->config->requestFix, $vars_enc_result);
											$link = createLinkNew($link, $urlencode);
					           	array_unshift($vars_noenc_result,"{$urlencode->var}{$app->config->requestFix}{$link}");
											//shuffle($vars_noenc_result);
  	        					$link = implode($app->config->requestFix, $vars_noenc_result);
  	        				}
          					else
          					{   											          						
          						$vars_noenc_result = array_merge($vars_enc_result,$vars_noenc_result);          						
         							$link = implode($app->config->requestFix, $vars_noenc_result);
          					}                    
                }
                else
                {                	
	                	$link = "$moduleName{$app->config->requestFix}$methodName";
                    foreach($vars_enc as $value) $link .= "{$app->config->requestFix}$value";

										if($urlencode->enable)
										{ 
            					$link = createLinkNew($link, $urlencode);
					           	array_unshift($vars_noenc, $link);
  	        					$link = implode($app->config->requestFix, $vars_noenc);
  	        				}
          					else
          					{   
          						foreach($vars_noenc as $value) $link .= "{$app->config->requestFix}$value";         							
          					}                    
                }

                $link .= '.' . $app->config->default->view;
                //die($link);
        }
        elseif($app->config->requestType == 'GET')// get
        {						
						$vars_enc_result = array();
						foreach($vars_enc as $key => $value)
						{
							$vars_enc_result[] = "$key=$value";
						}
						
						array_unshift($vars_enc_result,"{$app->config->methodVar}={$methodName}");
						array_unshift($vars_enc_result,"{$app->config->moduleVar}={$moduleName}");
						
						$vars_noenc_result = array();
						foreach($vars_noenc as $key => $value)
						{
							$vars_noenc_result[] = "$key=$value";
						}
						
						if($urlencode->enable)
						{ 
							//shuffle($vars_enc_result); 	      		
							$link = implode('&', $vars_enc_result);
            	$link = createLinkNew($link, $urlencode);
	           	array_unshift($vars_noenc_result, "{$urlencode->var}={$link}");
  	        	$link = implode('&', $vars_noenc_result);
  	        }
          	else
          	{   
          		$vars_noenc_result = array_merge($vars_enc_result,$vars_noenc_result);
         			//shuffle($vars_noenc_result);       		
         			$link = implode('&', $vars_noenc_result);          			
          	}
          	
            $link = '/?' . $link; 
        }
        
        return $link;
    }
		 
    
        /**
     * Import a file instend of include or requie.
     * 
     * @param string    $file   the file to be imported.
     * @static
     * @access public
     * @return bool
     */
    static public function import($file)
    {
        static $includedFiles = array();
        if(!isset($includedFiles[$file]))
        {
        		//文件不存在，返回false
        		if(!file_exists($file)) return false;
            $return = include $file;
            if(!$return) return false;
            $includedFiles[$file] = true;
            return true;
        }
        return true;
    }

    /**
     *  Get files match the pattern under one directory.
     * 
     * @access  public
     * @return  array   the files match the pattern
     */
    static public function ls($dir, $pattern = '')
    {
        $files = array();
        $dir = realpath($dir);
        if(is_dir($dir))
        {
            if($dh = opendir($dir))
            {
                while(($file = readdir($dh)) !== false) 
                {
                    if(strpos($file, $pattern) !== false) $files[] = $dir . DIRECTORY_SEPARATOR . $file;
                }
                closedir($dh);
            }
        }
        return $files;
    }

    /**
     * Change directory.
     * 
     * @param  string $path 
     * @static
     * @access public
     * @return void
     */
    static function cd($path = '')
    {
        static $cwd = '';
        if($path)
        {
            $cwd = getcwd();
            chdir($path);
        }
        else
        {
            chdir($cwd);
        }
    }
    
    /**
     *  Get now time use the DT_DATETIME1 constant defined in the lang file.
     * 
     * @access  public
     * @return  datetime  now
     */
    static public function now()
    {
        return date(DT_DATETIME1);
    }    
}


/**
 * Save the sql.
 * 
 * @access protected
 * @return void
 */
function saveSQL()
{
    if(!class_exists('dao')) return;
    $app = router::getInstance();
    $sqlLog = $app->logRoot . 'sql.' . date('Ymd') . '.log';
    $fh = @fopen($sqlLog, 'a');
    if(!$fh) return false;
    fwrite($fh, date('Ymd H:i:s') . ": " . $app->getURI() . "\n");
    foreach(dao::getAllQuerys() as $query) fwrite($fh, "  $query\n");
    fwrite($fh, "\n");
    fclose($fh);
}

/**
 * dump a var.
 * 
 * @param mixed $var 
 * @access public
 * @return void
 */
function a($var)
{
    echo "<xmp class='a-left'>";
    print_r($var);
    echo "</xmp>";
}

/**
 * Save the debug.
 * 
 * @access protected
 * @return void
 */
function saveDebug($str)
{
    $app = router::getInstance();
    $debugLog = $app->logRoot . 'debug.' . date('Ymd') . '.log';
    $fh = @fopen($debugLog, 'a');
    if(!$fh) return false;
    fwrite($fh, date('Ymd H:i:s') . ": " . $app->getURI() . "\n");
    fwrite($fh, "  $str\n");
    fwrite($fh, "\n");
    fclose($fh);
}


/**
 * get current date
 * 
 * @access public
 * @return string
 */
function curDate()
{
	return date('Y-m-d H:i:s');
}


/**
 * create update log and delete unchanged field
 * 
 * @param object $old
 * @param object $new
 * @access public
 * @return object
 */
function getLog($old, $new)
{
	$log = new stdClass();
	
	$notChanged = array();
	foreach($new as $key => $newValue)
	{
		$oldValue = $old->$key;
		$oldKey = 'old_'.$key;
		$newKey = 'new_'.$key;
		if($oldValue <> $newValue)
		{
			$log->$oldKey = $oldValue;
			$log->$newKey = $newValue;
		}
		else
		{
			$notChanged[]=$key;
		}
	}
	
	foreach($notChanged as $key)
	{
		unset($new->$key);
	}
	
	$cnt = 0;
	foreach($log as $key => $value)
	{
		$cnt ++;
	}
	
	return $cnt?$log:$cnt;
}

/**
 * translate right from id list to name list
 * 
 * @param object $types
 * @param object $right
 * @access public
 * @return string
 */
function getRight($types, $right)
{
	if(!$right) return '';
	
	$right = explode(',', $right);
	
	$ret=array();
	foreach($right as $r)
	{
		$ret[]=$types[$r];
	}
	
	return implode(',', $ret);
}


/**
 * read from excel
 * 
 * @param string $excelFile
 * @access public
 * @return array
 */
function readFromXLS($excelFile)
{
		$excel = new COM("excel.application") or die("Unable to instanciate excel");
		$excel->DisplayAlerts = 0;
		$excel->Workbooks->Open($excelFile);
		$csvFile = explode('.', $excelFile);
		$csvFile = $csvFile[0] . '.csv';
		$excel->Workbooks[1]->SaveAs($csvFile, 6);
		//$excel->Workbooks[1]->SaveCopyAs($csvFile);// 6 只是复制一份xls而已，不能进行格式转换
		$excel->Quit();
		$excel = null; 
		unlink($excelFile);
		
		$csv = fopen($csvFile,"r");
		while($d = fgetcsv($csv))
		{
			$data[] = $d;
		}
		fclose($csv);
			
		unlink($csvFile);
		
		return $data;
}

/**
 * read from csv
 * 
 * @param string $csvFile
 * @access public
 * @return array
 */
function readFromCSV($csvFile)
{
		$csv = fopen($csvFile,"r");

		while($d = fgetcsv($csv))
		{
			$data[] = $d;
		}
		fclose($csv);
			
		unlink($csvFile);
		
		return $data;
}

/*
格式化浮点数
0.991111->0.99
$a浮点值
$b精度
*/
function ff($a,$b=2)
{
	return round($a,$b);
	
	$c = explode('.',$a);
	if(count($c) <> 2) return $a;
	
	$c[1] = substr($c[1],0,$b);
	
	return implode('.',$c);
}


function ucache_get($key, &$value)
{
	$config = router::getInstance()->config;
	if(!$config->ucache || !extension_loaded('wincache')) return false;
	
	$value = wincache_ucache_get($key,$ret);
	
	return $ret;
}
    
function ucache_set($key, $value, $ttl = 0)
{
	$config = router::getInstance()->config;
	if(!$config->ucache || !extension_loaded('wincache')) return;
	
	return wincache_ucache_set($key, $value, $ttl);
}


function check_cache($file)
{			
	$config = router::getInstance()->config;
	if(!$config->cache) return false;
	$ttl = $config->ttl;
	return file_exists($file) && (filemtime($file) + $ttl * 60 > time());							
}

function get_cache_file($m,$f,$param)
{
	$str=array();
	foreach($param as $key => $value)
	{
		$str[]="{$key}={$value}";
	}
	
	$app = router::getInstance();

	
	return $app->cacheRoot.$m.$app->pathFix.$f.$app->pathFix.bin2hex(implode('&',$str)).'.html';
}

function put_cache($file,$out)
{
	$config = router::getInstance()->config;
	if(!$config->cache) {echo $out;return;}//不使用cache
	
	$f=dirname($file);
	$m=dirname($f);
	if(!file_exists($m)) mkdir($m);			
	if(!file_exists($f)) mkdir($f);
	
	if($config->gzip) $out=gzencode($out,9);
	$fh=fopen($file,"w");
	fwrite($fh,$out);
	fclose($fh);
}

function get_cache($file)
{
	$config = router::getInstance()->config;
	if(!$config->cache) return;	
	if($config->gzip) header("Content-Encoding: gzip"); 	
	
	echo file_get_contents($file);
}


function mb_array($string) {
    $strlen = mb_strlen($string);
    $ret = array();
    while ($strlen) {
        $ret[] = mb_substr($string,0,1,"UTF-8");
        $string = mb_substr($string,1,$strlen,"UTF-8");
        $strlen = mb_strlen($string);
    }
    return $ret;
} 

/*
建立多层目录
*/
function  CreateFolder( $dir ){    
     return   is_dir ( $dir )  or  (CreateFolder(dirname( $dir ))  and   mkdir ( $dir , 0777));
}


// 0   -> 0.00
// 1   -> 0.01
// 10  -> 0.10
// 100 -> 1.00					
function i2m($a)
{
	$b=strlen($a);
	switch($b)
	{
		case 1: return '0.0' . $a;
		case 2: return '0.' . $a;
		default:
		return substr($a,0,$b-2).'.'.substr($a,$b-2);		
	}
}
  
  
if(!function_exists('hex2bin'))
{
	function hex2bin($data) 
	{
     $len = strlen($data);
     return pack("H" . $len, $data); 
  } 
}

function path2url($path)
{
	 $i = strpos($path,'www');//定位www文件夹
	 $str=substr($path , $i + 3);
	 if(DIRECTORY_SEPARATOR == '/') return $str;
	 return strtr($str,'\\','/');
}


function createLinkOld($s1,$key)
{
	$v = md5($key . $s1);//生成检验和
	$s2 = 'v='.$v.'&'.$s1;
	return $s2;
}

function parseLinkOld($s2,$key)
{
	$i = strpos($s2,'&');
	if($i === false) return false;	
	$v=substr($s2,0,$i);//解析嵌入的检验和
	if($i <> 34 || substr($v,0,2)<>'v=') return false;//长度或标识不符
	$v=substr($v,2);//取检验和
	$s1=substr($s2,$i + 1);//解析s1
	$v2 = md5($key . $s1);//计算检验和
	if($v <> $v2) return false;//比较检验和
	return $s1;
}

//$s1为请求参数串，$cfg为配置参数对象。
function createLinkNew($s1,$cfg)
{
	$v = md5($cfg->key1 . $s1);//生成检验和
	$s2 = 'v='.$v.'&'.$s1;
	$s3=openssl_encrypt($s2,$cfg->aes,$cfg->key2,true,$cfg->iv);//加密
	$s4=strtr(base64_encode($s3),'+/','_*');;//使用改进base64编码处理
	return $s4;
}

//$s4为安全URL串,$cfg为配置参数对象。
function parseLinkNew($s5,$cfg)
{
	$s4 = base64_decode(strtr($s5,'_*','+/'));//解码
	if($s4 === false) return false;//解码失败 
	$s3=openssl_decrypt($s4, $cfg->aes,$cfg->key2,true,$cfg->iv);
	if($s3 === false) return false;//解密失败
	$i = strpos($s3,'&');
	if($i === false) return false;//未找到&
	$v=substr($s3,0,$i);//解析嵌入的检验和
	if($i <> 34 || substr($v,0,2)<>'v=') return false;//长度或标识不符
	$v=substr($v,2);//取检验和
	$s1=substr($s3,$i + 1);//解析s1
	$s2=$cfg->key1 . $s1;
	$v2 = md5($s2);//计算检验和
	if($v <> $v2) return false;//比较检验和
	return $s1;
}
