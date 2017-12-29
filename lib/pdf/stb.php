<?php
require('../common/head.inc');
require('fpdf.php');
require('chinese.php');
define('FPDF_FONTPATH','font/');

require_once dirname(__FILE__).'/BMP2GD/bmp2gd.php';

$row_yhbp=check_right($app,array(1));//省校管理员可以访问
		
//为了自动输出头部信息，定义子类
class TPDF extends PDF_Chinese
{
	var $width,$idx;
			
	function xHeader($title,$dwmc,$jfmc,$kssj,$zwh,$xm,$xh,$kcmc)
	{
		$height=20;		
		
		$FontSize=20;
		$this->SetFontSize($FontSize);
		$this->Cell($this->width,$height,$title,0,0,'C');$this->Ln();
		$this->Ln();
		
		$FontSize=12;
		$this->SetFontSize($FontSize);
		$height=15;
		$str='单位: '.$dwmc.'    考场: '.$jfmc.'           考试时间: '.$kssj;
		$this->Cell($this->width,$height,$str,0,0,'C');$this->Ln();
		$this->Ln();		
		
		$str='座位号: '.$zwh.'    姓名: '.$xm.'           学号: '.$xh.'             课程: '.$kcmc;
		$this->Cell($this->width,$height,$str,0,0,'C');$this->Ln();
		$this->Ln();		
	}		

	function Footer()
	{
 		$this->SetFont('GB','B');
 	  $this->SetY(-15);
    $this->Cell(0,10,'第'.$this->idx.'页',0,0,'C');
    $this->idx ++;
	}
}

function resizeImage($im,$maxwidth,$maxheight,$to)
{

$pic_width = imagesx($im);
$pic_height = imagesy($im);

if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
{
$resizewidth_tag = false;
$resizeheight_tag = false;

if($maxwidth && $pic_width>$maxwidth)
{
$widthratio = $maxwidth/$pic_width;
$resizewidth_tag = true;
}

if($maxheight && $pic_height>$maxheight)
{
$heightratio = $maxheight/$pic_height;
$resizeheight_tag = true;
}

if($resizewidth_tag && $resizeheight_tag)
{
if($widthratio < $heightratio)
$ratio = $widthratio;
else
$ratio = $heightratio;
}

if($resizewidth_tag && !$resizeheight_tag)
$ratio = $widthratio;
if($resizeheight_tag && !$resizewidth_tag)
$ratio = $heightratio;

$newwidth = $pic_width * $ratio;
$newheight = $pic_height * $ratio;

//创建空白图像
$newim = imagecreatetruecolor($newwidth,$newheight);

//重采样拷贝部分图像并调整大小
imagecopyresampled($newim,$im ,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
}
else
{
//创建空白图像
$newim = imagecreatetruecolor($pic_width,$pic_height);
//重采样拷贝部分图像并调整大小
imagecopyresampled($newim,$im,0,0,0,0,$pic_width,$pic_height,$pic_width,$pic_height);
}

imagepng($newim,$to);

imagedestroy($newim);
}

function putTGorABCD(&$pdf,$height,$stid,$lx,$prefix,$nr,$col,$sid)
{
  if($lx==1)
  {   	
  	$pdf->write($height,"{$prefix} {$nr}");
  	$pdf->Ln();  	
  }
  else
  {
  	//echo $tg;
  	$pdf->write($height,"{$prefix}   ");
  	$res=@imagecreatefromstring($nr);
  	if(!$res)
  	{
  		$file="pdf/st{$stid}{$col}{$sid}.bmp";
  		$fh=fopen($file,"w+");
  		fwrite($fh,$nr);
  		fclose($fh);
  		$res=@createFromBMP($file);
  		if(!$res) die("图片类型不支持![{$col},stid={$stid}]");
  		unlink($file);
  	}
  	$file="pdf/st{$stid}{$col}{$sid}.png";
  	$x=$pdf->getX();//当前位置
  	$y=$pdf->getY();
  	$w=imagesx($res);//图像大小
  	$h=imagesy($res);
		$maxx=$pdf->w-$pdf->rMargin;//右边界
		$maxy=$pdf->h-$pdf->bMargin;//下边界
		
		//则另起一页

		$resize=0;
		//放置该图像会超过下边界,放不下
  	if($y + $h > $maxy)
  	{
/*  		
 			//图像的3/4高度能放下则缩放 		
 			if($h * 3/4 <= $maxy - $pdf->tMargin)//当前页
 			{
 				$resize=1;
 				$h = $maxy - $y;
 			}
  		else 
*/  		
  		//图像的3/4高度超过空闲区域高度(隐含包括超过最大有效高度),加新页
  		{
				$pdf->addPage();			
		
				//图像高度超过最大有效空闲区域高度则缩放
 				if($h > $maxy-$pdf->tMargin)//已另起一页
 				{
	 				$resize=1;
	 				$h = $maxy-$pdf->tMargin;
 				} 		
	  	}				
 		}
 		
  	if($x+$w>$maxx)//宽度超过右边界，缩放
  	{
  		$resize=1;  		
  		$w=$maxx-$x;
  	}
  	
  	if($resize)
  	{
  		resizeImage($res,$w,$h,$file);//缩放
  	}
  	else
  	{
  		imagepng($res, $file);					
  	}
  	imagedestroy($res);
  	$info=getimagesize($file);
  	$w=$info[0]; //现在图像大小 	
  	$h=$info[1];
  	$pdf->Image($file,$pdf->getX(),$pdf->getY(),'png');
  	//$pdf->Rect($pdf->getX(),$pdf->getY(),$w,$h);
  	unlink($file);
  	if($h<$height) $h=$height;
  	$pdf->Ln($h);
  }
}
	
function putST(&$pdf,$seq,&$row_xsstb,&$stlx_array,$height,$sid)
{
	$stid=$row_xsstb->stid;
	$stlx=$row_xsstb->stlx;
	$stlx_str=$stlx_array[$stlx];
	
	$tglx=$row_xsstb->tglx;
	$alx=$row_xsstb->alx;
	$blx=$row_xsstb->blx;
	$clx=$row_xsstb->clx;
	$dlx=$row_xsstb->dlx;				
	$tg=base64_decode($row_xsstb->tg);
	$a=base64_decode($row_xsstb->a);
	$b=base64_decode($row_xsstb->b);
	$c=base64_decode($row_xsstb->c);
	$d=base64_decode($row_xsstb->d);
	if($stlx<>6)
		$prefix="{$seq}、[$stid][{$stlx_str}]";
	else
		$prefix="[$stid][{$stlx_str}]";
 	$pdf->SetFont('GB','B');
	putTGorABCD($pdf,$height,$stid,$tglx,$prefix,$tg,'tg',$sid);
	
	switch($stlx)
	{
		case 1:
		case 2:
		case 7:
	  	$pdf->SetFont('GB');
			putTGorABCD($pdf,$height,$stid,$alx,"A) ",$a,'a',$sid);
			putTGorABCD($pdf,$height,$stid,$blx,"B) ",$b,'b',$sid);
			putTGorABCD($pdf,$height,$stid,$clx,"C) ",$c,'c',$sid);
			putTGorABCD($pdf,$height,$stid,$dlx,"D) ",$d,'d',$sid);
			break;
	}	
}
		

$sid=md5(session_id());
	
//$row=$app->fetch_object("select count(*) as num from bkb where aplc is null");
//if($row->num) die('考场未编排!');

$row=$app->fetch_object("select count(*) as num from dwb where dwdm in (select kddm from bkb) and scsj=0");
if($row->num) die('试卷未生成!');

$dwdm=$_GET['dwdm'];
$row_dwb=check_object($dwdm,$app,"select * from dwb where dwdm=$dwdm");

echo '生成试卷...<br />';
		
$pdf=new TPDF('P','pt');
$pdf->AddGBFont();
//$pdf->SetFont('GB');
$pdf->SetDrawColor(0,0,0);	
//$pdf->rMargin+=50;
//$pdf->lMargin+=50;
//$FontSize=12;
//$pdf->SetFontSize($FontSize);
	
$width=$pdf->wPt-($pdf->rMargin+$pdf->lMargin);
//$lk_array=jslk($lk_array,$width);

$pdf->width=$width;
//$pdf->lk_array=$qdlk_array;
//$pdf->lm_array=$qdlm_array;

$height=20;
//$align='C';

$lc_objs=$app->fetch_objects("select * from lc",'lc');
$kdqk_objs=$app->fetch_objects("select * from kdqk where dwdm='$dwdm' and shbz='Y'",'xh');
$bkb_objs=$app->fetch_objects("select * from bkb where kddm='$dwdm' and csbz='N' order by jfh,zwh");

$bkb_array=array();
foreach($bkb_objs as $row)
{
	$bkb_array[$row->jfh][$row->aplc][$row->zwh]=$row;
}

$xssjb_objs=$app->fetch_objects("select * from xssjb where kddm='$dwdm' order by id");
$xssjb_array=array();
foreach($xssjb_objs as $row)
{
	$xssjb_array[$row->bkid][$row->seq]=$row;
}

foreach($bkb_array as $jfh=>$u)
{	
	$row_kdqk=$kdqk_objs[$jfh];
	foreach($u as $aplc=>$v)
	{
		$row_lc=$lc_objs[$aplc];		
		
		foreach($v as $zwh=>$row_bkb)
		{
			$pdf->AddPage();
 			$pdf->SetFont('GB','B');			
 			$pdf->idx=1;
			$pdf->xHeader('学生试卷',$row_dwb->dwmc,$row_kdqk->jfmc,$row_lc->rq.'  '.$row_lc->sj,$row_bkb->zwh,$row_bkb->xm,$row_bkb->xh,$row_bkb->kcmc);

			$w=$xssjb_array[$row_bkb->id];
			
			foreach($w as $seq=>$row_xsstb)
			{
				//if($row_xsstb->seq==1) echo "{$row_xsstb->kddm},{$row_xsstb->xh},{$row_xsstb->kcid},{$row_xsstb->stid},{$row_xsstb->seq}<br />";
				
				putST($pdf,$seq,$row_xsstb,$stlx_array,$height,$sid);				
			}
		}				
	}
}

echo 'done!';

$file="pdf/{$sid}.pdf";
$pdf->Output($file,'F');

println('<table border="1" width=70% height=100>');

echo '<tr><td>';
echo '<center><a href="'.$file.'" target="_blank">点击此处打印</a></center>';	
echo '</td></tr>';

println('</table>');
require('../common/tail.inc');

?>