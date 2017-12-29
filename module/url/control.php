<?php

class url extends control
{
	public function code()
	{
		if(count($_POST))
		{
			die($this->url->code());
		}
		$this->display();
	}
}
