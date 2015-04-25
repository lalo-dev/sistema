<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
define('HTML2FPDF_VERSION','3 0.0 (beta) ');
define('FPDF_FONTPATH','clasepdf/font/');
define('HTML2FPDF', 'clasepdf/font/'); /* define el componente de directorio de fuentes*/

require('../reporteUt/clasepdf/fpdf.php');


class PDF_Table extends FPDF 
{ 
	var $widths; 
	var $aligns; 
	var $borders;
	
	function SetBorders($w) 
	{ 
		//Set the borders of column  
		$this->borders=$w; 
	} 
	
	function SetWidths($w) 
	{ 
		//Set the array of column widths 
		$this->widths=$w; 
	} 
		
	function SetAligns($a) 
	{ 
		//Set the array of column alignments 
		$this->aligns=$a; 
	} 
	
	function Row($data,$h,$fill) 
	{ 
		
		//Issue a page break first if needed 
		$this->CheckPageBreak($h); 
		if($fill==true) $color=1;
		else $color=0;
		//Draw the cells of the row 
		for($i=0;$i<count($data);$i++) 
		{ 
			$w=$this->widths[$i]; 
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C'; 
			//Save the current position 
			$x=$this->GetX(); 
			$y=$this->GetY(); 
			
			if(isset($this->borders[$i])) //If doesn´t specify the borders, they would be like 1 ='TBRL'
			{
				//Print the text 
				$this->MultiCell($w,5,$data[$i],'LR',$a,$color);
			}
			else 
			{
				//Draw the border 
				$this->Rect($x,$y,$w,$h); 
				//Print the text 
				$this->MultiCell($w,5,$data[$i],0,$a,$color);
			}
			 
			//Put the position to the right of the cell 
			$this->SetXY($x+$w,$y); 
		} 
		//Go to the next line 
		$this->Ln($h); 
	} 
	function heigth($data) 
	{ 
		//Calculate the height of the row 
		$nb=0; 
		for($i=0;$i<count($data);$i++)
		{ 
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i])); 
		$h=5*$nb; 
	   }
	   return $h;
	} 
	
	function CheckPageBreak($h) 
	{ 
		//If the height h would cause an overflow, add a new page immediately 
		if($this->GetY()+$h>$this->PageBreakTrigger) 
			$this->AddPage($this->CurOrientation); 
	} 
	
	function NbLines($w,$txt) 
	{ 
		//Computes the number of lines a MultiCell of width w will take 
		$cw=&$this->CurrentFont['cw']; 
		if($w==0) 
			$w=$this->w-$this->rMargin-$this->x; 
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize; 
		$s=str_replace("\r",'',$txt); 
		$nb=strlen($s); 
		if($nb>0 and $s[$nb-1]=="\n") 
			$nb--; 
		$sep=-1; 
		$i=0; 
		$j=0; 
		$l=0; 
		$nl=1; 
		while($i<$nb) 
		{ 
			$c=$s[$i]; 
			if($c=="\n") 
			{ 
				$i++; 
				$sep=-1; 
				$j=$i; 
				$l=0; 
				$nl++; 
				continue; 
			} 
			if($c==' ') 
				$sep=$i; 
			$l+=$cw[$c]; 
			if($l>$wmax) 
			{ 
				if($sep==-1) 
				{ 
					if($i==$j) 
						$i++; 
				} 
				else 
					$i=$sep+1; 
				$sep=-1; 
				$j=$i; 
				$l=0; 
				$nl++; 
			} 
			else 
				$i++; 
		} 
		return $nl; 
	} 

//Celdas Ajustadas

	//Cell with horizontal scaling if text is too wide
	function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
	{
		//Get string width
		$str_width=$this->GetStringWidth($txt);

		//Calculate ratio to fit cell
		if($w==0)
			$w = $this->w-$this->rMargin-$this->x;	
		$ratio = ($w-$this->cMargin*2)/$str_width;

		$fit = ($ratio < 1 || ($ratio > 1 && $force));
		if ($fit)
		{
			if ($scale)
			{
				//Calculate horizontal scaling
				$horiz_scale=$ratio*100.0;
				//Set horizontal scaling
				$this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
			}
			else
			{
				//Calculate character spacing in points
				$char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
				//Set character spacing
				$this->_out(sprintf('BT %.2F Tc ET',$char_space));
			}
			//Override user alignment (since text will fill up cell)
			$align='';
		}

		//Pass on to Cell method
		$this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

		//Reset character spacing/horizontal scaling
		if ($fit)
			$this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
	}

	//Cell with horizontal scaling only if necessary
	function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
	{
		if($txt=="")
			$this->Cell($w,$h,$txt='',$border,$ln,$align,$fill,$link);
		else
			$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
	}

	//Cell with horizontal scaling always
	function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
	{
		$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
	}

	//Cell with character spacing only if necessary
	function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
	{
		$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
	}

	//Cell with character spacing always
	function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
	{
		//Same as calling CellFit directly
		$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
	}

	//Patch to also work with CJK double-byte text
	function MBGetStringLength($s)
	{
		if($this->CurrentFont['type']=='Type0')
		{
			$len = 0;
			$nbbytes = strlen($s);
			for ($i = 0; $i < $nbbytes; $i++)
			{
				if (ord($s[$i])<128)
					$len++;
				else
				{
					$len++;
					$i++;
				}
			}
			return $len;
		}
		else
			return strlen($s);
	}
	
	
	/*Rectangulos redondeados*/
	function RoundedRect($x, $y, $w, $h, $r, $style = '')
	{
		$k = $this->k;
		$hp = $this->h;
		if($style=='F')
			$op='f';
		elseif($style=='FD' || $style=='DF')
			$op='B';
		else
			$op='S';
		$MyArc = 4/3 * (sqrt(2) - 1);
		$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
		$xc = $x+$w-$r ;
		$yc = $y+$r;
		$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

		$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
		$xc = $x+$w-$r ;
		$yc = $y+$h-$r;
		$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
		$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
		$xc = $x+$r ;
		$yc = $y+$h-$r;
		$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
		$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
		$xc = $x+$r ;
		$yc = $y+$r;
		$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
		$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
		$this->_out($op);
	}

	function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
	{
		$h = $this->h;
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
			$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
	}

} 


?>