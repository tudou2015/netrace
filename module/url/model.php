<?php

class urlModel extends model
{
	
	public function code()
	{
   	if($this->session->user->type <> 1||$this->session->user->org <> 1)
   	{
   		return $this->lang->error->noRightOp;
   	}

		$type = $this->post->type;
		$str = $this->post->str;
		
		
		if($type <> 1 && $type <> 2)
		{
			return $this->lang->error->param;
		}
				
		
		if($type == 1)
		{
			return createLinkNew($str, $this->config->urlencode);
		}
		else
		{			
			$result = parseLinkNew($str, $this->config->urlencode);
			
			if($result === false)
			{
				return $this->lang->error->param;
			}
			
			return $result;
		}
				
	}
}

