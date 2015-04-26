<?php
require_once('bd.php');
require_once('pdfTable.php');
require_once('libreriaGeneral.php');

class PDF extends PDF_Table
{
    function Header()
    {
    }
    //Cabecera de p�gina
    
    function Footer()
    {
    }
}

    //Creaci�n del objeto de la clase heredada
    $pdf=new PDF('P','mm','letter');
    $pdf->AliasNbPages();
    $pdf->SetDisplayMode(60,'default'); //Para que el zoom este al 100%, normal real
    
/*************************************************************************************************************/
    
    $txtGuia = $_REQUEST['txtGuia'];
    $cveGuiaInt = $_REQUEST['txtRangoInicio'];
    $rangoInicio = $_REQUEST['txtRangoInicio'];
    $rangoFin = $_REQUEST['txtRangoFin'];
    $cveCliente = $_REQUEST['hddCveCliente'];
    $fechaInicio = $_REQUEST['txtFechaInicio'];
    $tell = $_REQUEST['telefono'];

    
    if($fechaInicio!="")
    {
        list($dia,$mes,$anyo)=explode("/",$fechaInicio);
        $fechaInicio=$anyo."-".$mes."-".$dia;
    }
    $fechaFin = $_REQUEST['txtFechaFin'];
    if($fechaFin!="")
    {   
        list($dia,$mes,$anyo)=explode("/",$fechaFin);
        $fechaFin=$anyo."-".$mes."-".$dia;  
    }
    $condicion = "";
    
    if($txtGuia != "")
    {
        $condicion .= "AND cveGuia = $txtGuia ";
    }
    if($rangoInicio != "")
    {
        $condicion .= "AND cveGuiaInt BETWEEN $rangoInicio AND $rangoFin ";
    }
    if($fechaInicio != "")
    {
        $condicion .= "AND cguias.fechaCreacion BETWEEN '$fechaInicio' AND '$fechaFin' ";
    }
    if($cveCliente != "")
    {
        if($cveCliente != 0)
            $condicion .= "AND cveCliente = $cveCliente ";
    }
    
    $sql = "SELECT
            cguias.cveGuiaInt,
            cguias.obsRemitente,
            cguias.recepcionCYE,
            cguias.nombreRemitente,
            cguias.calleRemitente,
            cguias.telefonoRemitente,
            cguias.rfcRemitente,
            cguias.coloniaRemitente,
            cguias.municipioRemitente,
            cguias.estadoRemitente,
            cguias.codigoPostalRemitente,
            cguias.contCarga,
            cguias.piezas,
            cguias.volumen,
            cguias.kg,
             (SELECT cestados.nombre AS estadoR FROM `cmunicipios` 
             INNER JOIN cestados ON cveEstado=cmunicipios.`cveEntidadFederativa`
             WHERE cveEntidadFederativa=cguias.estadoRemitente AND cveMunicipio=cguias.municipioRemitente) AS estadoR,
             (SELECT cmunicipios.nombre AS municipio FROM `cmunicipios` 
             INNER JOIN cestados ON cveEstado=cmunicipios.`cveEntidadFederativa`
             WHERE cveEntidadFederativa=cguias.estadoRemitente AND cveMunicipio=cguias.municipioRemitente) AS municipioR,
            cconsignatarios.nombre,
            cconsignatarios.estado,
            cconsignatarios.municipio,
            cconsignatarios.colonia,
            cconsignatarios.municipio,
            cconsignatarios.estado,
            cconsignatarios.calle,
            cconsignatarios.codigoPostal,
            cconsignatarios.telefono,
            (SELECT cestados.nombre AS estado FROM `cmunicipios` 
             INNER JOIN cestados ON cveEstado=cmunicipios.`cveEntidadFederativa`
             WHERE cveEntidadFederativa=cconsignatarios.estado AND cveMunicipio=cconsignatarios.municipio) AS estadoD,
             (SELECT cmunicipios.nombre AS municipio FROM `cmunicipios` 
             INNER JOIN cestados ON cveEstado=cmunicipios.`cveEntidadFederativa`
             WHERE cveEntidadFederativa=cconsignatarios.estado AND cveMunicipio=cconsignatarios.municipio) AS municipioD
           FROM cguias
           LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario
           WHERE cguias.cveGuia != '' ".$condicion."
           ORDER BY cguias.cveCliente ASC,cguias.cveGuiaInt,cguias.fechaCreacion DESC;";
    $resSql = $bd->Execute($sql);
    
    $leyendaObs = "";
    
    foreach($resSql as $reg)
    {
        $numTel = "";
        $leyendaObs = "";
        $resFacturas = $bd->Execute("SELECT facturaSoporte,cveFacturaS FROM cfacturassoporte WHERE cveGuia= '".$reg["cveGuiaInt"]."'");
        $facturaEnviar = $resFacturas[0]["facturaSoporte"];
        
        $resEntregas = $bd->Execute("SELECT cveEntregaS,entregasSoporte FROM centregassoporte WHERE cveGuia= '".$reg["cveGuiaInt"]."'");
        $entregaEnviar = $resEntregas[0]["entregasSoporte"];
        
        $resVales = $bd->Execute("SELECT valeSoporte,cveValeS FROM cvalessoporte WHERE cveGuia= '".$reg["cveGuiaInt"]."'");
        $valeEnviar = $resVales[0]["valeSoporte"];
        
        if($facturaEnviar != "")
        {
            $leyendaObs .= "FACTURA(S) ".$facturaEnviar." ";
        }
        if($entregaEnviar != "")
        {
            $leyendaObs .= " ENTREGA(S) " . $entregaEnviar." ";
        }
        if($valeEnviar != "")
        {
            $leyendaObs .= " VALE " . $valeEnviar;
        }
        if($tell == "")
        {
            $numTel = $reg['telefono'];
        }
        
        //if (isset($_GET[imprimirNum]) && $_GET[imprimirNum] == "no")
        if (isset($_REQUEST[imprimirNum]) && $_REQUEST[imprimirNum] == "no")
        {
            $reg['cveGuiaInt'] = "";
        }
        
        
        if($reg['recepcionCYE'] == "0000-00-00")
        {
            $reg['recepcionCYE'] = "";
        }
        else
        {
            list($anio,$mes,$dia) = explode("-",$reg['recepcionCYE']);
            $reg['recepcionCYE'] = $dia."-".$mes."-".$anio;
        }
        
        //Cada 3 impresiones se va a agregar una hoja
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true,0);
        $pdf->SetMargins(1,1,1);
        
        //L�nea 1 --> FOLIO
        $pdf->SetFont('Arial','',18);
        $pdf->SetXY(167,10);/*170,10*/
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(43,9,$reg['cveGuiaInt'],1,0,'C',0);
                
        //L�nea 1 --> No. PIEZAS
        if($reg['piezas'] == 0){$piezas = "";}else{$piezas = $reg['piezas'];}
        if($reg['kg'] == 0){$kg = "";}else{$kg = $reg['kg'];}
        if($reg['volumen'] == 0){$volumen = "";}else{$volumen = $reg['volumen'];}
        
        $reg['contCarga'] = strtoupper($reg['contCarga']);
        $reg['obsRemitente'] = strtoupper($reg['obsRemitente']);
        
        $diceContener = partirTexto($reg['contCarga']);
        
        $pdf->SetFont('Arial','',18);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(30);
        $pdf->SetXY(7,28);/*5,29*/
        $pdf->Cell(49,9,$reg['recepcionCYE'],1,0,'C',0);//FECHA
        $pdf->SetFont('Arial','',14);
        $pdf->SetXY(52,28);/*48,27*/
        $pdf->CellFitScale(80,8,$diceContener[0],0,0,'C',0);//borde,0,Negrita o no,0
        $pdf->SetXY(52,32);/*48,31*/
        $pdf->CellFitScale(80,8,$diceContener[1],0,0,'C',0);//borde,0,Negrita o no,0
        $pdf->SetXY(132,28);/*130,29*/
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(26,9,$piezas,1,0,'C',0);
        $pdf->Cell(26,9,$kg,1,0,'C',0);
        $pdf->Cell(26,9,$volumen,1,0,'C',0);
        
        $nombreP=partirTexto2($reg['nombreRemitente']);
        $nombre1=$nombreP[0];
        $nombre2=$nombreP[1];
        
        $nombreP2=partirTexto2($reg['nombre']);
        $nombre3=$nombreP2[0];
        $nombre4=$nombreP2[1];
        
        //L�nea 2 --> NOMBRE 1 y 2
        $pdf->SetFont('Arial','',9);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln();
        $pdf->SetXY(27,38);/*15,40*/
        $pdf->CellFitScale(88,4,$reg['nombreRemitente'].$reg['nombreRemitente'],0,0,'L',0);
        $pdf->SetXY(27,42);/*0,$y+5*/
        $pdf->CellFitScale(103,4,$nombre2,0,0,'L',0);
        
        //L�nea 2 --> NOMBRE 1 y 2 Consignatario
        $pdf->SetFont('Arial','',9);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln();
        $pdf->SetXY(134,38);/*130,40*/
        $pdf->CellFitScale(82,4,$reg['nombre'],0,0,'L',0);
        $pdf->SetXY(134,42);/*103,$z+5*/
        $pdf->CellFitScale(109,4,$nombre4,0,0,'L',0);
        
        //L�nea 3 --> CALLE
        $pdf->SetFont('Arial','',8);
        $pdf->Ln(4.5);/*4.5*/
        $pdf->SetX(20);/*5*/
        $pdf->CellFitScale(96,4,$reg['calleRemitente'],0,0,'L',0);
        $pdf->SetX(120);/*113*/
        $pdf->CellFitScale(96,4,$reg['calle'],0,0,'L',0);
        $pdf->Ln(4);
        $pdf->SetX(20);/*5*/
        $pdf->CellFitScale(96,4,$reg['coloniaRemitente'],0,0,'L',0);
        $pdf->SetX(120);/*113*/
        $pdf->CellFitScale(96,4,$reg['colonia'],0,0,'L',0);
            
        //L�nea 4
        $pdf->SetFont('Arial','',8);
        $pdf->Ln(3.5);
        $pdf->SetX(20);/*5*/
        $pdf->CellFitScale(96,5,$reg['municipioR'].' '.$reg['estadoR'],0,0,'L',0);
        $pdf->SetX(120);/*113*/
        $pdf->CellFitScale(96,5,$reg['municipioD'].' '.$reg['estadoD'],0,0,'L',0);
        $pdf->Ln(5);
        $pdf->SetX(20);/*5*/
        $pdf->CellFitScale(43,4,$reg['codigoPostalRemitente'],0,0,'L',0);
        $pdf->CellFitScale(43.5,4,$reg['telefonoRemitente'],0,0,'L',0);
        $pdf->SetFont('Arial','',8);
        $pdf->SetX(120);/*113*/
        $pdf->CellFitScale(42,4,$reg['codigoPostal'],0,0,'L',0);
        $pdf->CellFitScale(42.5,4,$numTel,0,0,'L',0);
        
        //L�nea 4 Observaciones
        
        $reg['obsRemitente'] = strtoupper($reg['obsRemitente'].'mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmMMMMMMMMMMMMMMMMMMM');
        $regObsRemitente = partirTexto($reg['obsRemitente']);
        
        $pdf->SetFont('Arial','',8);
        $pdf->Ln(21.5);/*21.5*/
        $pdf->SetX(8);
        $pdf->CellFitScale(216,4,utf8_decode($regObsRemitente[0]),0,0,'L',0);
        $pdf->Ln(3.25);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','',8);
        if($leyendaObs == "")
        {
            $pdf->CellFitScale(216,4,utf8_decode($regObsRemitente[1]),0,0,'L',0);
        }
        else
        {
            //$pdf->CellFitScale(216,4,$leyendaObs.'WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW',0,0,'L',0);
            $mitexto = wordwrap('WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW',84,'\n',true);
            $pdf->CellFitScale(216,4,$mitexto,0,0,'L',0);
        }
    }
/*************************************************************************************************************/

function partirTexto($texto)
{
    $divisor=strlen($texto)/2;
    $texto1=substr($texto,0,$divisor);
    $texto2=substr($texto,$divisor,strlen($texto));
    $siguiente=substr($texto,$divisor,1);
    if($siguiente!=" ") //Significa que la palabra se corto
    {
        $palabra=encontrarEspacio($texto2);
        $texto2=$palabra[1];
        $texto1.=$palabra[0];
    }
    $resultados=array($texto1,$texto2);
    return $resultados;
     
    if( $cuentaCadena > 100 )
    {
        $divisor=strlen($texto)/2;
        $texto1=substr($texto,0,$divisor);
        $texto2=substr($texto,$divisor,strlen($texto));
        $siguiente=substr($texto,$divisor,1);
        if($siguiente!=" ") //Significa que la palabra se corto
        {
            $palabra=encontrarEspacio($texto2);
            $texto2=$palabra[1];
            $texto1.=$palabra[0];
        }
        $resultados=array($texto1,$texto2);
        return $resultados;
    }
    else
    {
        $resultados=array($texto,$texto);
        return $resultados;
    }
}
function partirTexto2($texto)
{
	$divisor=strlen($texto)/2;
    $texto1=substr($texto,0,$divisor);
    $texto2=substr($texto,$divisor,strlen($texto));
    $siguiente=substr($texto,$divisor,1);
    if($siguiente!=" ") //Significa que la palabra se corto
    {
        $palabra=encontrarEspacio($texto2);
        $texto2=$palabra[1];
        $texto1.=$palabra[0];
    }
    $resultados=array($texto1,$texto2);
    return $resultados;
}

//***** Esta funcion de debe cambiar de nombre de wordwrap a cortarTexto
//function WordWrap(&$text, $maxwidth)
function cortarTexto($text, $maxwidth)
{
    $text = trim($text);
    if ($text==='')
        return 0;
    $space = $this->GetStringWidth(' ');
    $lines = explode("\n", $text);
    $text = '';
    $count = 0;

    foreach ($lines as $line)
    {
        $words = preg_split('/ +/', $line);
        $width = 0;

        foreach ($words as $word)
        {
            $wordwidth = $this->GetStringWidth($word);
            if ($wordwidth > $maxwidth)
            {
                // Word is too long, we cut it
                for($i=0; $i<strlen($word); $i++)
                {
                    $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                    if($width + $wordwidth <= $maxwidth)
                    {
                        $width += $wordwidth;
                        $text .= substr($word, $i, 1);
                    }
                    else
                    {
                        $width = $wordwidth;
                        $text = rtrim($text)."\n".substr($word, $i, 1);
                        $count++;
                    }
                }
            }
            elseif($width + $wordwidth <= $maxwidth)
            {
                $width += $wordwidth + $space;
                $text .= $word.' ';
            }
            else
            {
                $width = $wordwidth + $space;
                $text = rtrim($text)."\n".$word.' ';
                $count++;
            }
        }
        $text = rtrim($text)."\n";
        $count++;
    }
    $text = rtrim($text);
    return $count;
}

function encontrarEspacio($texto)
{
    $longitud=strlen($texto);
    $palabra="";
    for($i=0;$i<$longitud;$i++)
    {
        if($texto[$i]!=" ")
        {
            $palabra.=$texto[$i];
        }
        else
        {
            break;
        }
    }
    $i++;
    $textoFinal=substr($texto,$i,$longitud);
    $resultados=array($palabra,$textoFinal);
    return $resultados;
}

//Poner el nombre para descargar el archivo
//$pdf->Output('MiNuevoPDF.pdf',D);
$pdf->Output();
?>
