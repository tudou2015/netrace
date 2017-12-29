<?php

/**
*�� �� COM �� Excel ������(PHP5.x)
*PHPer:T.T.R
*Date:[2007-05-24]
*Ver:1.0.0
*Blog:http://www.Gx3.cn http://Gx3.cn
*QQ:252319874
*/
class excel
{
	static $instance = null;
  private $excel = null;
  private $workbook = null;
  private $workbookadd = null;  
  private $worksheet = null;
  private $worksheetadd = null;
  private $sheetnum = 1;
  private $cells = array();
  private $fields = array();
  private $maxrows;
  private $maxcols;
  private $filename;

  //���캯��
  private function excel()
  {
  	$this->excel = new COM("Excel.Application") or die("Did Not Connect");
  }
       
	//�����
	public static function getInstance()
	{
		if(null == self::$instance)
		{
			self::$instance = new excel();
		}
		return self::$instance;
	}

		//�����ļ���ַ
		public function setFile($filename)
		{
			return $this->filename = $filename;
		}

		//���ļ�
		public function open()
		{
			$this->workbook = $this->excel->WorkBooks->Open($this->filename);
		}

		//����Sheet
		public function setSheet($num = 1)
		{
			if($num > 0)
			{
				$this->sheetnum  = $num;
				$this->worksheet = $this->excel->WorkSheets[$this->sheetnum];
				$this->maxcols   = $this->maxCols();
				$this->maxrows   = $this->maxRows();
				$this->getCells();
			}
		}

		//ȡ�ñ�����ֵ��д������
		private function getCells()
		{
			for($i = 1; $i < $this->maxrows; $i ++)
			{
				for($j = 1; $j < $this->maxcols; $j ++)
				{
					$this->cells[$i][$j]=iconv('GBK','UTF-8',$this->worksheet->Cells($i, $j)->value);
					//$this->cells[$i][$j]=$this->worksheet->Cells($i, $j)->value;
				}
			}
			return $this->cells;
		}

		//���ر����������
		public function getAllData()
		{
			return $this->cells;
		}

		//�����ƶ���Ԫ������
		public function Cell($row, $col)
		{
			return $this->worksheet->Cells($row, $col)->Value;
		}

		//ȡ�ñ���ֶ�������
		public function getFields()
		{
			for($i = 1;$i < $this->maxcols;$i ++)
			{
				$this->fields[]=$this->worksheet->Cells(1, $i)->value;
			}
			return $this->fields;
		}

		//�޸��ƶ���Ԫ������
		public function editCell($row, $col, $value)
		{
			if($this->workbook == null || $this->worksheet == null)
			{
				die("Error:Did Not Connect!");
			}
			else
			{
				$this->worksheet->Cells($row, $col)->Value=$value;
				$this->workbook->Save();
			}
		}

		//�޸�һ������
		public function editOneRow($row, $arr)
		{
			if($this->workbook == null || $this->worksheet == null || $row >= 2)
			{
				die("Error:Did Not Connect!");
			}
			else
			{
				if(count($arr) == $this->maxcols - 1)
				{
					$i = 1;
					foreach($arr as $val)
					{
						$this->worksheet->Cells($row, $i)->Value = $val;
						$i ++;
					}
					$this->workbook->Save();
				}
				else
				{
					die("Error:Column Size Not Same!");
				}
			}
		}

		//ȡ��������
		private function maxCols()
		{
			$i = 1;

			while(true)
			{
				if($this->worksheet->Cells(1, $i)->value == null)
				{
					return $i;
					break;
				}
				$i ++;  
			}
		}

		//ȡ��������
		private function maxRows()
		{
			$i = 1;
			
			while(true)
			{
				if($this->worksheet->Cells($i, 1)->value == null)
				{
					return $i;
					break;
				}
				$i ++;
			}

			return $i;
		}

		//��ȡ�ƶ�������
		public function getOneRow($row = 2)
		{
			if($row >= 2)
			{
				for($i = 1;$i < $this->maxcols;$i ++)
				{
					$arr[] = $this->worksheet->Cells($row, $i)->Value;
				}
			return $arr;
			}
		}

		//�رն���
		public function Close()
		{
			$this->excel->WorkBooks->Close();
			$this->excel=null;
			$this->workbook=null;
			$this->worksheet=null;
			self::$instance=null;
		}
};

?>