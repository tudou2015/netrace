<?php
/**
 * The control file of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common
 * @version     $Id: control.php 2153 2011-10-11 07:43:27Z wwccss $
 * @link        http://www.zentao.net
 */
class common extends control
{

		public function filter()
		{               
				//延迟session        
				//if(isset($this->config->filter['session'])) $this->common->startSession();
        
        $this->common->sendHeader();
        
        if(isset($this->config->filter['auth'])) 
        {
				  $this->common->checkPriv();
				}				
		}
		
}
