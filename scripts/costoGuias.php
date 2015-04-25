<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
 
header("Content-Type: text/Text; charset=ISO-8859-1 Cache-Control: no-store, no-cache, must-revalidate");
header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

include("bd.php");

//Obtenemos los datos
$cveGuia=$_GET['guia'];
$cveCorresponsal=$_GET['corresponsal'];


//El origen siempre será el mismo
$sql="SELECT cguias.cveGuia,cguias.guiaArea,cguias.tipoEnvio,cguias.piezas,IF(cguias.kg>cguias.volumen,cguias.kg,cguias.volumen) AS peso,".
	 "datostarifas.primerRango,datostarifas.segundoRango,datostarifas.Tercerrango,datostarifas.cuartoRango,".
	 "datostarifas.distancia,datostarifas.cargoMinimo,datostarifas.sobrepeso,datostarifas.costoSobrepeso,".
	 "datostarifas.costoDistancia,datostarifas.costoEntrega,datostarifas.costoEspecial,datostarifas.costoViaticos,".
	 "rango1,rango2,rango3,rango4 ".
	 "FROM cconsignatarios,cguias ".
	 "INNER JOIN (".
				 "SELECT cdetalletarifa.costoSobrepeso,cdetalletarifa.costoDistancia,cdetalletarifa.costoEntrega,cdetalletarifa.costoEspecial,".
				 "cdetalletarifa.costoViaticos,cdetalletarifa.tipoEnvio,cdetalletarifa.primerRango,cdetalletarifa.segundoRango,".
				 "cdetalletarifa.Tercerrango,cdetalletarifa.cuartoRango,cdetalletarifa.distancia,cdetalletarifa.cargoMinimo,".
				 "cdetalletarifa.sobrepeso,ctarifascorresponsales.primerRango AS rango1, ctarifascorresponsales. estadoDestino,".
				 "ctarifascorresponsales.municipioOrigen,ctarifascorresponsales.segundoRango AS rango2,ctarifascorresponsales.tercerRango AS rango3,".
				 "ctarifascorresponsales.cuartoRango AS rango4,ctarifascorresponsales.municipioDestino ".
				 "FROM cdetalletarifa ".
				 "INNER JOIN ctarifascorresponsales ".
				 "ON ctarifascorresponsales.cveCorresponsal=cdetalletarifa.cveCorresponsal ".
				 "AND ctarifascorresponsales.cveTarifa=cdetalletarifa.cveTarifa ".
				 "AND ctarifascorresponsales.estadoOrigen=9 ".
				 "AND ctarifascorresponsales.municipioOrigen=17 ".
				 "AND cdetalletarifa.cveCorresponsal=".$cveCorresponsal." ".
				 ") AS datostarifas ".
	"ON cguias.tipoEnvio=datostarifas.tipoEnvio ".
	"WHERE cguias.cveGuia='".$cveGuia."' ".
	"AND datostarifas.municipioDestino=cconsignatarios.municipio ".
	"AND datostarifas.estadoDestino=cconsignatarios.estado ".
	"AND cguias.cveConsignatario=cconsignatarios.cveConsignatario"; 

	
	$campos = $bd->Execute($sql);

	$respuesta = "[";
	foreach($campos as $campo){

			 $rango1= valorRango($campo['rango1']);
			 $rango2= valorRango($campo['rango2']);
			 $rango3= valorRango($campo['rango3']);
			 $peso=$campo["peso"];
				
			if($peso<= $rango1)
			{
				$cargo=$campo['primerRango'];
			}
			if($peso > $rango1 AND $peso<= $rango2){
				$cargo=$campo['segundoRango'];
			}
			if($peso> $rango2 AND $peso<= $rango3){
				$cargo=$campo['Tercerrango'];
			}
			if($peso>= $rango3 ){
				$cargo=$campo['cuartoRango'];
			}
			$costoEntrega=$peso*$cargo;
			$cargoMinimo=$campo['cargoMinimo'];
			if($costoEntrega<$cargoMinimo)
				$costoEntrega=$cargoMinimo;
			
			$respuesta .= "{indice:'0',cveGuia:'".$campo["cveGuia"]."',cargo:'".$cargo."',distancia:'".$campo["distancia"]. 
						  "',guiaA:'".$campo["guiaArea"]."',cargoMinimo:'".$campo["cargoMinimo"]."',sobrepeso:'".$campo["sobrepeso"]."',costoEntrega:'".$costoEntrega.
						  "',ctoSobrePeso:'".$campo["costoSobrepeso"]."',ctoDistancia:'".$campo["costoDistancia"]."',costoEntrega:'".$costoEntrega.
						  "',ctoEspecial:'".$campo["costoEspecial"]."',ctoViaticos:'".$campo["costoViaticos"]."', tipoEnvio:'".$campo["tipoEnvio"].
						  "',piezas:'".$campo["piezas"]."',kg:'".$campo["peso"]."'},";

    }
	//Quitamos último carácter
	$respuesta = substr($respuesta, 0, strlen($respuesta)-1);
	$respuesta .= "]";

	if($respuesta=="]")
	{
		$sql="SELECT guiaArea,tipoEnvio,piezas,IF(kg>volumen,kg,volumen) AS peso FROM cguias WHERE cveGuia='".$cveGuia."'";

		$campos = $bd->Execute($sql);
		foreach($campos as $campo){
			$respuesta="[{indice:'1',cveGuia:'".$cveGuia."',cargo:'0',distancia:'0',guiaA:'".$campo["cveGuia"]."',cargoMinimo:'0',sobrepeso:'0',costoEntrega:'0',".
						"ctoSobrePeso:'0',ctoDistancia:'0',costoEntrega:'0',ctoEspecial:'0',ctoViaticos:'0',tipoEnvio:'".$campo["tipoEnvio"].
						"',piezas:'".$campo["piezas"]."',kg:'".$campo["peso"]."'}]";
		}
	}

	echo $respuesta;

function valorRango($valorRango){
    $separar = explode(' ',$valorRango);
     return $separar[2];
}

?>
