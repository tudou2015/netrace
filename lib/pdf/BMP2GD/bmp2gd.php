<?php
/**
@package bmp2gd
@version 1.0
@date 2010-01-06

@author Mehmet Emin Akyuz
@email me@mehmet-emin.gen.tr
*/

require_once dirname(__FILE__).'/bitops.php';
	

	
	/**
		@param $file string	filename to be converted to gd
		@return false | gdResource
	*/
	function createFromBMP($file)
	{		
		$compressionTypes=array(0=>"RGB",1=>"RLE8",2=>"RLE4",3=>"BITFIELD",4=>"JPEG",5=>"PNG");

		$size=filesize($file);
		$f=fopen($file,'r');
		if(!$f){
			return false;
		}
		
		$header=fread($f,14);
		$header=unpack('C2type/Lsize/vreservedA/vreservedB/Loffset',$header);

		if($header['size']!=$size){
			return false;
		}
		
		$dibHeaderSize=unpack('LdibSize',fread($f,4));
		$dibHeaderSize=$dibHeaderSize['dibSize'];

		$dibHeader=fread($f,$dibHeaderSize-4);
		if($dibHeaderSize==12){
			$dibHeader=unpack("lwidth/lheight/vplaneCount/vbpp",$dibHeader);
			$dibHeader['compressType']=0;
		} else if($dibHeaderSize==40){
			$dibHeader=unpack("lwidth/lheight/vplaneCount/vbpp/LcompressType/Lbytes/lhorizontalResolution/lverticalResolution/LcolorCount/LimportantColorCount",$dibHeader);
		} else if($dibHeaderSize==56){
			$dibHeader=unpack("lwidth/lheight/vplaneCount/vbpp/LcompressType/Lbytes/lhorizontalResolution/lverticalResolution/LcolorCount/LimportantColorCount",$dibHeader);
		} else if($dibHeaderSize==108){
			$dibHeader=unpack("lwidth/lheight/vplaneCount/vbpp/LcompressType/Lbytes/lhorizontalResolution/lverticalResolution/LcolorCount/LimportantColorCount",$dibHeader);
		} else if($dibHeaderSize==124){
			$dibHeader=unpack("lwidth/lheight/vplaneCount/vbpp/LcompressType/Lbytes/lhorizontalResolution/lverticalResolution/LcolorCount/LimportantColorCount",$dibHeader);
		}
		
		if($compressionTypes[$dibHeader['compressType']]!="RGB"){ // only non compressed format is supported
			return false;
		}

		$rowbytes= floor(($dibHeader['width'] * $dibHeader['bpp'] - 1) / 32) * 4 + 4;
		
		$img=imagecreatetruecolor($dibHeader['width'],$dibHeader['height']);
		$white=imagecolorallocate($img,0xff,0xff,0xff);
		
		if($dibHeader['bpp']==32){
			if($dibHeader['height']>0){ // for bottom to top bitmaps
				for($y=1;$y<=$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						$color=unpack('Cblue/Cgreen/Cred/',fread($f,4));
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$dibHeader['height']-$y,$color);
					}
				}
			} else { // for top to bottom bitmaps
				for($y=0;$y<$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						$color=unpack('Cblue/Cgreen/Cred/',fread($f,4));
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$y,$color);
					}
				}
			}
		} else if($dibHeader['bpp']==24){
			if($dibHeader['height']>0){
				for($y=1;$y<=$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						$color=unpack('Cblue/Cgreen/Cred/',fread($f,3));
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$dibHeader['height']-$y,$color);
					}
				}
			} else {
				for($y=0;$y<$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						$color=unpack('Cblue/Cgreen/Cred/',fread($f,3));
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$y,$color);
					}
				}
			}
		} else if($dibHeader['bpp']==16){ // not fully supported but windows paint is not using it anyway
			if($dibHeader['height']>0){
				for($y=1;$y<=$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						$rgb=unpack('vrgb/',fread($f,2));
						$rgb=$rgb['rgb'];
						$color=array(
									 'red'=>getBitsetInt($rgb,5,10)<<3,
									 'green'=>getBitsetInt($rgb,5,5)<<3,
									 'blue'=>getBitsetInt($rgb,5,0)<<3);
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$dibHeader['height']-$y,$color);
					}
				}
			} else {
				for($y=1;$y<=$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						$rgb=unpack('vrgb/',fread($f,2));
						$rgb=$rgb['rgb']<<3;
						$color=array(
									 'red'=>getBitsetInt($rgb,5,10)<<3,
									 'green'=>getBitsetInt($rgb,5,5)<<3,
									 'blue'=>getBitsetInt($rgb,5,0)<<3);
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$y,$color);
					}
				}
			}
		} else if($dibHeader['bpp']==8){
			$colorIndex=array();
			
			for($i=0;$i<256;++$i){
				$colorIndex[]=unpack('Cblue/Cgreen/Cred/',fread($f,4));
			}
			
			if($dibHeader['height']>0){
				for($y=1;$y<=$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						$color=unpack('Cindex/',fread($f,1));
						$color=$colorIndex[$color['index']];
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$dibHeader['height']-$y,$color);
					}
					$skip = $rowbytes -1 - floor(($dibHeader['width']*8 - 1)/8);
					if($skip){
						fread($f, $skip);
					}
				}
			} else {
				for($y=0;$y<$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						$color=unpack('Cindex/',fread($f,1));
						$color=$colorIndex[$color['index']];
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$y,$color);
					}
					$skip = $rowbytes -1 - floor(($dibHeader['width']*8 - 1)/8);
					if($skip){
						fread($f, $skip);
					}
				}
			}
		} else if($dibHeader['bpp']==4){
			$colorIndex=array();
			
			for($i=0;$i<16;++$i){
				$colorIndex[]=unpack('Cblue/Cgreen/Cred/',fread($f,4));
			}
			
			$f4=true;
			if($dibHeader['height']>0){
				for($y=1;$y<=$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						if($f4){
							$index=unpack('Cindex/',fread($f,1));
							$f4=false;
							$index=$index['index'];
							$uindex=getBitsetInt($index,4,4);
							$color=$colorIndex[$uindex];
						} else {
							$f4=true;
							$uindex=getBitsetInt($index,4,0);
							$color=$colorIndex[$uindex];
						}
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$dibHeader['height']-$y,$color);
					}
					$skip = $rowbytes -1 - floor(($dibHeader['width']*4 - 1)/8);
					if($skip){
						fread($f, $skip);
					}
				}
			} else {
				for($y=0;$y<$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						$color=unpack('Cindex/',fread($f,1));
						$color=$colorIndex[$color['index']];
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$y,$color);
					}
					$skip = $rowbytes -1 - floor(($dibHeader['width']*4 - 1)/8);
					if($skip){
						fread($f, $skip);
					}
				}
			}
		} else if($dibHeader['bpp']==1){
			$colorIndex=array();
			
			for($i=0;$i<2;++$i){
				$colorIndex[]=unpack('Cblue/Cgreen/Cred/',fread($f,4));
			}
			
			$f8=0;
			if($dibHeader['height']>0){
				for($y=1;$y<=$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						if($f8==0){
							$index=unpack('Cindex/',fread($f,1));
							$index=$index['index'];
							$uindex=getBit($index,$f8);
							$color=$colorIndex[$uindex?1:0];
						} else {
							$uindex=getBit($index,$f8);
							$color=$colorIndex[$uindex?1:0];
						}
						
						++$f8;
						$f8%=8;
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$dibHeader['height']-$y,$color);
					}
					$skip = $rowbytes -1 - floor(($dibHeader['width'] - 1)/8);
					if($skip){
						fread($f, $skip);
					}
				}
			} else {
				for($y=0;$y<$dibHeader['height'];++$y){
					for($x=0;$x<$dibHeader['width'];++$x){
						if($f8==0){
							$index=unpack('Cindex/',fread($f,1));
							$index=$index['index'];
							$uindex=getBit($index,$f8);
							$color=$colorIndex[$uindex?1:0];
						} else {
							$uindex=getBit($index,$f8);
							$color=$colorIndex[$uindex?1:0];
						}
						
						++$f8;
						$f8%=8;
						$color=imagecolorallocate($img,$color['red'],$color['green'],$color['blue']);
						imagesetpixel($img,$x,$y,$color);
					}
					$skip = $rowbytes -1 - floor(($dibHeader['width'] - 1)/8);
					if($skip){
						fread($f, $skip);
					}
				}
			}
		} else {
			// invalid bmp
			return false;
		}
		
		fclose($f);
		return $img;
	}

?>