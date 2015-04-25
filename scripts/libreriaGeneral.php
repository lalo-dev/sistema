<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
function quitarAcentos($cadena){ //Tomado de :http://www.tutores.org/?codigo=1415&Eliminar-acentos-de-una-cadena
	
	$buscar = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$sustitucion = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	
	$cadenaFinal=strtr($cadena,$buscar,$sustitucion);
	
	return $cadenaFinal;
}
	
function fechaCon($fecha)            //Convierte el formato de la Fecha
{
	$fechafinal="";
		
	if($fecha!=""){
		list($dia,$mes,$anyo)=explode("-",$fecha);
		$fechafinal=$anyo."/".$mes."/".$dia;
	}
	if($fechafinal=="00/00/0000")
		$fechafinal="";
	return $fechafinal;
}

function subCadena($cadena,$longitud) //Deuvuelve n numero de carácteres, para que no se encimen los resultados en la tabla
{
	$cadenaFin = substr(trim($cadena),0,$longitud); 
	return $cadenaFin;
}

function cambioCadena($cadena)				//Checa si la cadena tiene SELLO Y FIRMA y se cambia por S/F para que quepa en la tabla
{
	$cadena=strtoupper($cadena);
	$cadena = str_replace("SELLO","S",$cadena);
	$cadena = str_replace("FIRMA","F",$cadena);
	
	return $cadena;
}

function redondeaNum($numero){
	$numero=$numero+0; //Para convertir la cadena a número		
	$valor = (is_float($numero)) ? (floor($numero * 100)/100) : $numero;
	return $valor;
}

function valorRango($valorRango){
	$separar = explode(' ',$valorRango);
	return $separar[2];
} 

function fecha($fecha,$opc)
{
	list($dia,$mes,$anyo)=explode("-",$fecha);

	$meses=array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
	setType($mes,integer);

	if($opc==0)
		$fechafinal=$dia." ".$meses[$mes-1]." ".$anyo;	
	else
		$fechafinal=strtolower($dia." de ".$meses[$mes-1]." de ".$anyo);	
	return $fechafinal;
}


function dosLineas($letras)
{
	$cadena1=substr($letras,0,66);
	$cadena2=substr($letras,66,(strlen($letras)));
	$siguiente = substr($letras,66,1);	

	if($siguiente!=" ") 								//Si se corta la palabra la bajamos
	{
		$longitud=1;
		$a=0;
		for($i=0;$i<(strlen($cadena1));$i++)
		{

			$num=$longitud*(-1);
			if($i!=0) $anterior = substr($cadena1,$num,($num+1));
			else 	  $anterior = substr($cadena1,$num);
			
			if($anterior!=" "){
				$palabra[$a]=$anterior;
				$a++;
			}
			else
				break;
			$longitud++;
			
		}
		$cadena1=substr($letras,0,(65-$a));
		
		//Revertimos palabra
		$final="";
		for($i=0;$i<count($palabra);$i++)
		{
			$posicion=count($palabra)-$i-1;
			$final=$final.$palabra[$posicion];
		}
		
		$cadena2=substr($letras,66,(strlen($letras)));
		
	}else $final="";
	
	return array($cadena1,$cadena2);
}

function formatoEntero($num)
{
	$enteros=explode(".",$num);
	$entero=$enteros[0];
	$numero=$entero;
	$longitud=strlen($entero);
	$n=0;

	if($longitud>3)
	{
		$numero="";
		for($i=$longitud;$i>=0;$i--)
		{
			$numero=$numero.$entero[$i];
			if(($n%3==0)&&($n!=0)&&($n!=$longitud)){
				$numero=$numero.",";
			}
			$n++;
		}
		//Revertir cadena
		$longitud=strlen($numero);
		$final="";
		for($i=$longitud;$i>=0;$i--)
		{
			$final=$final.$numero[$i];
		}
		$numero=$final;
	}
	$decimales=substr($enteros[1],0,2);
	if($decimales=="") $decimales="00";
	return $numero.".".$decimales;
}

function cambiaf_a_normal($fecha){ 
   	preg_match( "#([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})#", $fecha, $mifecha); 
   	$lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 
   	return $lafecha; 
} 
function cambiaf_a_mysql($fecha){ 
   	preg_match( "#([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})#", $fecha, $mifecha); 
   	$lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
   	return $lafecha; 
} 

function suma_fechas($fecha,$ndias) 
{ 
	if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha)) 
		list($dia,$mes,$año)=explode("/", $fecha); 
	if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha)) 
		list($año,$mes,$dia)=explode("-",$fecha); 
	
	$diaFin=$dia+$ndias;
	$nuevafecha= date('d/m/Y',mktime(0,0,0,$mes,$diaFin,$año));

	return ($nuevafecha);
} 
function restaFechas($date1, $date2)
{
    $s = strtotime($date1)-strtotime($date2);
    $d = intval($s/86400);
    $s -= $d*86400;
    $h = intval($s/3600);
    $s -= $h*3600;
    $m = intval($s/60);
    $s -= $m*60;
     
    return $d;
}
function redondear_dos_decimal($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}
function nombre_mes($fecha,$operacion){
$patron1 = "/\-/";

if (preg_match($patron1, $fecha))
list($anio,$mes,$dia) = explode('-', $fecha);
else
list($dia,$mes,$anio) = explode('/', $fecha);

$abreviaturas=array("01" => array("abrev"=>"ene","completo"=>"Enero"),
"02" => array("abrev"=>"feb","completo"=>"Febrero"),
"03" => array("abrev"=>"mar","completo"=>"Marzo"),
"04" => array("abrev"=>"abr","completo"=>"Abril"),
"05" => array("abrev"=>"may","completo"=>"Mayo"),
"06" => array("abrev"=>"jun","completo"=>"Junio"),
"07" => array("abrev"=>"jul","completo"=>"Julio"),
"08" => array("abrev"=>"ago","completo"=>"Agosto"),
"09" => array("abrev"=>"sep","completo"=>"Septiembre"),
"10" => array("abrev"=>"oct","completo"=>"Octubre"),
"11" => array("abrev"=>"nov","completo"=>"Noviembre"),
"12" => array("abrev"=>"dic","completo"=>"Diciembre")
             ); 
/*$abreviaturas=array("01" => array("abrev"=>"ene","completo"=>"ENERO"),
             "02" => array("abrev"=>"feb","completo"=>"FEBRERO"),
             "03" => array("abrev"=>"mar","completo"=>"MARZO"),
             "04" => array("abrev"=>"abr","completo"=>"ABRIL"),
             "05" => array("abrev"=>"may","completo"=>"MAYO"),
             "06" => array("abrev"=>"jun","completo"=>"JUNIO"),
             "07" => array("abrev"=>"jul","completo"=>"JULIO"),
             "08" => array("abrev"=>"ago","completo"=>"AGOSTO"),
             "09" => array("abrev"=>"sep","completo"=>"SEPTIEMBRE"),
             "10" => array("abrev"=>"oct","completo"=>"OCTUBRE"),
             "11" => array("abrev"=>"nov","completo"=>"NOVIEMBRE"),
             "12" => array("abrev"=>"dic","completo"=>"DICIEMBRE")
             ); */
             
   if($operacion==0)
   {
$fechaAv=$dia.'-'.$abreviaturas[$mes]['abrev'].'-'.$anio;
return $fechaAv;
   } 
   else
   {
$final=$dia.' de '.$abreviaturas[$mes]['completo'].' del '.$anio;
return $final;
   }  
} 

function num2letras($num, $fem = false, $dec = true) { 
//if (strlen($num) > 14) die("El n?mero introducido es demasiado grande"); 
   $matuni[2]  = "DOS"; 
   $matuni[3]  = "TRES"; 
   $matuni[4]  = "CUATRO"; 
   $matuni[5]  = "CINCO"; 
   $matuni[6]  = "SEIS"; 
   $matuni[7]  = "SIETE"; 
   $matuni[8]  = "OCHO"; 
   $matuni[9]  = "NUEVE"; 
   $matuni[10] = "DIEZ"; 
   $matuni[11] = "ONCE"; 
   $matuni[12] = "DOCE"; 
   $matuni[13] = "TRECE"; 
   $matuni[14] = "CATORCE"; 
   $matuni[15] = "QUINCE"; 
   $matuni[16] = "DIECISEIS"; 
   $matuni[17] = "DIECISIETE"; 
   $matuni[18] = "DICIOCHO"; 
   $matuni[19] = "DIECINUEVE"; 
   $matuni[20] = "VEINTE"; 
   $matunisub[2] = "DOS"; 
   $matunisub[3] = "TRES"; 
   $matunisub[4] = "CUATRO"; 
   $matunisub[5] = "QUIN"; 
   $matunisub[6] = "SEIS"; 
   $matunisub[7] = "SETE"; 
   $matunisub[8] = "OCHO"; 
   $matunisub[9] = "NOVE"; 

   $matdec[2] = "VEINT"; 
   $matdec[3] = "TREINTA"; 
   $matdec[4] = "CUARENTA"; 
   $matdec[5] = "CINCUENTA"; 
   $matdec[6] = "SESENTA"; 
   $matdec[7] = "SETENTA"; 
   $matdec[8] = "OCHENTA"; 
   $matdec[9] = "NOVENTA"; 
   $matsub[3]  = 'MILL'; 
   $matsub[5]  = 'BILL'; 
   $matsub[7]  = 'MILL'; 
   $matsub[9]  = 'TRILL'; 
   $matsub[11] = 'MILL'; 
   $matsub[13] = 'BILL'; 
   $matsub[15] = 'MILL'; 
   $matmil[4]  = 'MILLONES'; 
   $matmil[6]  = 'BILLONES'; 
   $matmil[7]  = 'DE BILLONES'; 
   $matmil[8]  = 'MILLONES DE BILLONES'; 
   $matmil[10] = 'TRILLONES'; 
   $matmil[11] = 'DE TRILLONES'; 
   $matmil[12] = 'MILLONES DE TRILLONES'; 
   $matmil[13] = 'DE TRILLONES'; 
   $matmil[14] = 'BILLONES DE TRILLONES'; 
   $matmil[15] = 'DE BILLONES DE TRILLONES'; 
   $matmil[16] = 'MILLONES DE BILLONES DE TRILLONES'; 

   $num = trim((string)@$num); 
   if ($num[0] == '-') { 
      $neg = 'menos '; 
      $num = substr($num, 1); 
   }else 
      $neg = ''; 
   while ($num[0] == '0') $num = substr($num, 1); 
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
   $zeros = true; 
   $punt = false; 
   $ent = ''; 
   $fra = ''; 
   for ($c = 0; $c < strlen($num); $c++) { 
      $n = $num[$c]; 
      if (! (strpos(".,'''", $n) === false)) { 
         if ($punt) break; 
         else{ 
            $punt = true; 
            continue; 
         } 

      }elseif (! (strpos('0123456789', $n) === false)) { 
         if ($punt) { 
            if ($n != '0') $zeros = false; 
            $fra .= $n; 
         }else 

            $ent .= $n; 
      }else 

         break; 

   } 
   $ent = '     ' . $ent; 
   if ($dec and $fra and ! $zeros) { 
      $fin = ' coma'; 
      for ($n = 0; $n < strlen($fra); $n++) { 
         if (($s = $fra[$n]) == '0') 
            $fin .= ' cero'; 
         elseif ($s == '1') 
            $fin .= $fem ? ' UNO' : ' UN'; 
         else 
            $fin .= ' ' . $matuni[$s]; 
      } 
   }else 
      $fin = ''; 
   if ((int)$ent === 0) return 'Cero ' . $fin; 
   $tex = ''; 
   $sub = 0; 
   $mils = 0; 
   $neutro = false; 
   while ( ($num = substr($ent, -3)) != '   ') { 
      $ent = substr($ent, 0, -3); 
      if (++$sub < 3 and $fem) { 
         $matuni[1] = 'UNO'; 
         $subcent = 'OS'; 
      }else{ 
         $matuni[1] = $neutro ? 'UN' : 'UNO'; 
         $subcent = 'OS'; 
      } 
      $t = ''; 
      $n2 = substr($num, 1); 
      if ($n2 == '00') { 
      }elseif ($n2 < 21) 
         $t = ' ' . $matuni[(int)$n2]; 
      elseif ($n2 < 30) { 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = 'I' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      }else{ 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = ' Y ' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      } 
      $n = $num[0]; 
      if ($n == 1) { 
         $t = ' CIENTO' . $t; 
      }elseif ($n == 5){ 
         $t = ' ' . $matunisub[$n] . 'IENT' . $subcent . $t; 
      }elseif ($n != 0){ 
         $t = ' ' . $matunisub[$n] . 'CIENT' . $subcent . $t; 
      } 
      if ($sub == 1) { 
      }elseif (! isset($matsub[$sub])) { 
         if ($num == 1) { 
            $t = ' MIL'; 
         }elseif ($num > 1){ 
            $t .= ' MIL'; 
         } 
      }elseif ($num == 1) { 
         $t .= ' ' . $matsub[$sub] . 'ÓN'; 
      }elseif ($num > 1){ 
         $t .= ' ' . $matsub[$sub] . 'ONES'; 
      }   
      if ($num == '000') $mils ++; 
      elseif ($mils != 0) { 
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
         $mils = 0; 
      } 
      $neutro = true; 
      $tex = $t . $tex; 
   } 
   $tex = $neg . substr($tex, 1) . $fin; 
   return ucfirst($tex); 
} 

?>
