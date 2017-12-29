<?php

require('../common/head.inc');
require('fpdf.php');
require('chinese.php');
define('FPDF_FONTPATH','font/');

$row_yhbp=check_right($app,array(1,3));//ʡУ����Ա�ͽ�ѧ�������Է���

//$row=$app->fetch_object("select count(*) as num from bkb where aplc is null");
//if($row->num) die('����δ����!');

if($row_yhbp->yhlx==1)
	$dwdm=$_GET['dwdm'];
else
	$dwdm=$row_yhbp->dwdm;
$row_dwb=check_object($dwdm,$app,"select * from dwb where dwdm=$dwdm");


echo '���ɿ���ǩ����Ϳ�����¼��...<br />';
		
$lm_array=array
(
	'qm'=>'����ǩ��',
	'zwh'=>'��λ��',
	'xh'=>'ѧ��',
	'xm'=>'��������',
//	'xb'=>'�����Ա�',
//	'sfzh'=>'�������֤��',
	'kcmc'=>'�γ�����',
	'bz'=>'��ע',	
);

//�п�
$lk_array=array
(
	'qm'=>20,
	'zwh'=>20,
	'xh'=>40,
	'xm'=>20,
//	'xb'=>20,
//	'sfzh'=>40,
	'kcmc'=>40,
	'bz'=>20,
);

//Ϊ���Զ����ͷ����Ϣ����������
class TPDF extends PDF_Chinese
{
	var $width;
			
	function xHeader($title,$dwmc,$jfmc,$kssj)
	{
		$height=20;		
		
		$FontSize=20;
		$this->SetFontSize($FontSize);
		$this->Cell($this->width,$height,$title,0,0,'C');$this->Ln();
		$this->Ln();
		
		$FontSize=12;
		$this->SetFontSize($FontSize);
		$height=15;
		$str='��λ: '.$dwmc.'    ����: '.$jfmc.'           ����ʱ��: '.$kssj;
		$this->Cell($this->width,$height,$str,0,0,'C');$this->Ln();
		$this->Ln();		
	}		

	function Footer()
	{
    $this->SetY(-15);
    $this->Cell(0,10,'��'.$this->PageNo().'ҳ',0,0,'C');
	}
}
		
$pdf=new TPDF('P','pt');
$pdf->AddGBFont();
$pdf->SetFont('GB');
$pdf->SetDrawColor(0,0,0);	
//$pdf->rMargin+=50;
//$pdf->lMargin+=50;
$FontSize=12;
$pdf->SetFontSize($FontSize);
	
$width=$pdf->wPt-($pdf->rMargin+$pdf->lMargin);
$lk_array=jslk($lk_array,$width);

$pdf->width=$width;
//$pdf->lk_array=$qdlk_array;
//$pdf->lm_array=$qdlm_array;

$height=20;
$align='C';

$lc_objs=$app->fetch_objects("select * from lc",'lc');
$kdqk_objs=$app->fetch_objects("select * from kdqk where dwdm='$dwdm' and shbz='Y'",'xh');
$bkb_objs=$app->fetch_objects("select * from bkb where kddm='$dwdm' and csbz='N' order by jfh,zwh");

$qdb_array=array();
foreach($bkb_objs as $row)
{
	$qdb_array[$row->jfh][$row->aplc][$row->zwh]=$row;
}

//a($xsjbdab_objs);

foreach($qdb_array as $jfh=>$u)
{	
	$row_kdqk=$kdqk_objs[$jfh];
	foreach($u as $aplc=>$v)
	{
		$row_lc=$lc_objs[$aplc];
		
		$pdf->AddPage();
		$pdf->xHeader('����ǩ����',$row_dwb->dwmc,$row_kdqk->jfmc,$row_lc->rq.'  '.$row_lc->sj);

		foreach($lm_array as $key=>$value)
			$pdf->Cell($lk_array[$key],$height,$value,1,0,'C');
		$pdf->Ln();		
		
		foreach($v as $zwh=>$row)
		{
			foreach($lm_array as $key=>$value)
			{
				switch($key)
				{
					case 'qm':
						$str='';
						break;
					case 'zwh':
						$str=$zwh;
						break;
					case 'xh':
						$str=$row->xh;
						break;
					case 'xm':
						$str=$row->xm;
						break;
					case 'kcmc':
						$str=$row->kcmc;
						break;
					case 'bz':
						if($row->lc)
							$str='����ԤԼ';
						else
							$str='';
						break;
					}
				$pdf->Cell($lk_array[$key],$height,$str,1,0,$align);
			}
			$pdf->Ln();			
		}
		
		$pdf->Ln();
		$pdf->Cell($pdf->GetStringWidth('�࿼��ǩ��: '),$height,'�࿼��ǩ��: ',0,0);
		$x=$pdf->getX()+5;
		$y=$pdf->getY()+$height-5;
		$pdf->Line($x,$y,$x+150,$y);
		
		$pdf->AddPage();
		$pdf->xHeader('������¼��',$row_dwb->dwmc,$row_kdqk->jfmc,$row_lc->rq.'  '.$row_lc->sj);
		$pdf->Cell($width/3,$height*2,'Ӧ��: ',1,0);
		$pdf->Cell($width/3,$height*2,'ʵ��: ',1,0);
		$pdf->Cell($width/3,$height*2,'ȱ��: ',1,0);
		$pdf->Ln();
		//$pdf->Cell($width,$height,'ȱ�����(ѧ��,����,�γ̣���λ��): ',1,0);
		$x=$pdf->getX()+$pdf->cMargin;
		$y=$pdf->getY()+$height;
		$pdf->Cell($width,300,'',1,1);
		$pdf->Text($x,$y,'ȱ�����(ѧ��,����,�γ̣���λ��): ');
		$x=$pdf->getX()+$pdf->cMargin;
		$y=$pdf->getY()+$height;
		$pdf->Cell($width,300,'',1,1);
		$pdf->Text($x,$y,'����Υ�Ϳ���ѧ�ż�����Υ�����: ');
		$pdf->Cell(0,$height*2,'�࿼��ǩ��: ',1,0);
	}
}

echo 'done!';

$filename='pdf/'.md5(session_id()).'.pdf';
$pdf->Output($filename,'F');

println('<table border="1" width=70% height=100>');

echo '<tr><td>';
echo '<center><a href="'.$filename.'" target="_blank">����˴���ӡ</a></center>';	
echo '</td></tr>';

println('</table>');
require('../common/tail.inc');

?>