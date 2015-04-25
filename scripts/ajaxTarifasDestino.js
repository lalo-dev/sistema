window.onload = inicia;

function inicia() {

	var url="scripts/catalogoTarifasC.php?operacion=6";
	$("divTarifa").className = "autocomplete";
	new Ajax.Autocompleter("txtTarifa", "divTarifa", url, {paramName: "caracteres",afterUpdateElement:existe_dir});
		
	var respuesta= "scripts/estados.php?pais=156";
	$Ajax(respuesta, {onfinish: cargaEstados, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	var respuesta= "scripts/estados.php?pais=156";
	$Ajax(respuesta, {onfinish: cargaEstadosD, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	respuesta= "scripts/datosTarifasC.php?operacion=5";
	$Ajax(respuesta, {onfinish: llenaRangos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$Ajax("scripts/envios.php", {onfinish: cargarEnvios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$Ajax("scripts/tiposCliente.php", {onfinish: cargaTipos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	//Para la modificación de tarifas
	$Ajax("scripts/envios.php", {onfinish: cargarEnvios2, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	$("slcEstados").onchange=verGrid;
	$("slcEstadosD").onchange=verGrid2;
	$("btnModificar").onclick=modificarTarifaR;
	$("btnGuardar").onclick=existe;
	$("btnModificarT").disabled=true;
	$("btnCancelarT").onclick=cancelar;
	$("btnCancelarT").disabled=true;
	$("txtTarifa").disabled=false;
	$("btnAumentarT").onclick=aumentarTarifas;
	$("btnReducirT").onclick=reducirTarifas;
	$("btnAumentarCM").onclick=aumentarCargoMinimo;
	$("btnReducirCM").onclick=reducirCargoMinimo;
	
	$("btnCancelar").onclick=cancelar;
	respuesta= "scripts/municipios.php?municipio=9" ;
	$Ajax(respuesta, {onfinish: cargaMunicipiosb, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});		

	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("btnGuardar").style.visibility="hidden";
		$("btnModificarT").style.visibility="hidden";
		$("btnModificar").style.visibility="hidden";
		$("rowCargo").style.visibility="hidden";
		$("btnModificar").style.visibility="hidden";
		$("btnCancelar").style.visibility="hidden";
		
		$("btnGuardar").style.display="none";
		$("btnModificarT").style.display="none";		
		$("btnModificar").style.display="none";		
		$("rowCargo").style.display="none";		
		$("rowTarifa").style.display="none";		
		$("btnCancelar").style.display="none";	
		
	}

}

function fin3(res)
{
	alert(res);
	cancelar();
}
function aumentarCargoMinimo()
{
	notGood=0;
	if(isNaN($("txtCantidadCM").value)||($("txtCantidadCM").value == "")){$("txtCantidadCM").addClassName("invalid"); notGood ++;} else{$("txtCantidadCM").removeClassName("invalid");}
	if(notGood>0)
	{
		alert('Cantidad incorrecta para afectar Cargo M\u00EDnimo');
	}
	else{
		var mensaje="";
		if($("slctipoClienteCargoMin").value!="" && $("slctipoEnvio").value!="")
			mensaje+=" para el tipo de cliente "+$("slctipoClienteCargoMin").value+" y el env\u00EDo "+$("slctipoEnvio").value;
		else if($("slctipoClienteCargoMin").value!="") mensaje+=" para el tipo de cliente "+$("slctipoClienteCargoMin").value;
		else if($("slctipoEnvio").value!="") mensaje+=" para el tipo de env\u00EDo "+$("slctipoEnvio").value;		
		if (confirm("\u00bfConfirma que desea aumentar el cargo m\u00EDnimo"+mensaje+"? \n (Todos se ver\u00E1n afectados)")){		
			var valores ="&porcentaje="+$("txtCantidadCM").value+"&opcion=3&slctipoClienteCargoMin="+$("slctipoClienteCargoMin").value+
						 "&slctipoEnvio="+$("slctipoEnvio").value;
			$Ajax("scripts/afectarTarifas.php", {metodo: $metodo.POST,  onfinish: fin3 ,parametros: valores, avisoCargando:"loading"});
	
		}
	}
}

function reducirCargoMinimo()
{
	notGood=0;
	if(isNaN($("txtCantidadCM").value)||($("txtCantidadCM").value == "")){$("txtCantidadCM").addClassName("invalid"); notGood ++;} else{$("txtCantidadCM").removeClassName("invalid");}
	if(notGood>0)
	{
		alert('Cantidad incorrecta para afectar Cargo M\u00EDnimo');
	}
	else{
		var mensaje="";
		if($("slctipoClienteCargoMin").value!="" && $("slctipoEnvio").value!="")
			mensaje+=" para el tipo de cliente "+$("slctipoClienteCargoMin").value+" y el env\u00EDo "+$("slctipoEnvio").value;
		else if($("slctipoClienteCargoMin").value!="") mensaje+=" para el tipo de cliente "+$("slctipoClienteCargoMin").value;
		else if($("slctipoEnvio").value!="") mensaje+=" para el tipo de env\u00EDo "+$("slctipoEnvio").value;
		if (confirm("\u00bfConfirma que desea disminuir el cargo m\u00EDnimo"+mensaje+"? \n(Todos se ver\u00E1n afectados)")){		
			var valores ="&porcentaje="+$("txtCantidadCM").value+"&opcion=2&slctipoClienteCargoMin="+$("slctipoClienteCargoMin").value+
						 "&slctipoEnvio="+$("slctipoEnvio").value;			
			$Ajax("scripts/afectarTarifas.php", {metodo: $metodo.POST,  onfinish: fin3 ,parametros: valores, avisoCargando:"loading"});
	
		}
	}
}

function aumentarTarifas()
{
	notGood=0;
	if(isNaN($("txtCantidad").value)||($("txtCantidad").value == "")){$("txtCantidad").addClassName("invalid"); notGood ++;} else{$("txtTarifa1").removeClassName("invalid");}
	if(notGood>0)
	{
		alert('Porcentaje incorrecto para afectar las Tarifas');
	}
	else{
		
		var mensaje="";
		if($("slctipoClienteRangos").value!="") mensaje=" para el tipo de cliente "+$("slctipoClienteRangos").value;
		if (confirm("\u00bfConfirma que desea aumentar las tarifas"+mensaje+"? (Todas se ver\u00E1n afectadas)")){		
			var valores ="&porcentaje="+$("txtCantidad").value+"&opcion=0&slctipoClienteRangos="+$("slctipoClienteRangos").value;			
			$Ajax("scripts/afectarTarifas.php", {metodo: $metodo.POST,  onfinish: fin3 ,parametros: valores, avisoCargando:"loading"});
	
		}
	}
}

function reducirTarifas()
{
	notGood=0;
	if(isNaN($("txtCantidad").value)||($("txtCantidad").value == "")){$("txtCantidad").addClassName("invalid"); notGood ++;} else{$("txtTarifa1").removeClassName("invalid");}
	if(notGood>0)
	{
		alert('Porcentaje incorrecto para afectar las Tarifas');
	}
	else{
		var mensaje="";
		if($("slctipoClienteRangos").value!="") mensaje=" para el tipo de cliente "+$("slctipoClienteRangos").value;
		if (confirm("\u00bfConfirma que desea reducir las tarifas"+mensaje+"? (Todas se ver\u00E1n afectadas)")){		
			var valores ="&porcentaje="+$("txtCantidad").value+"&opcion=1&slctipoClienteRangos="+$("slctipoClienteRangos").value;			
			$Ajax("scripts/afectarTarifas.php", {metodo: $metodo.POST, onfinish: fin3, parametros: valores, avisoCargando:"loading"});
	
		}
	}
}


function existe_dir()
{
		if ($("txtTarifa").value!=""){
				var cve_tarifa=($("txtTarifa").value).split("-");
				$("txtTarifa").value=cve_tarifa[0];	
				llenaEstados(0,0,0,0,5,1,0,0);
				$("btnCancelarT").disabled=false;
				$("txtTarifa").disabled=true;
	   }
}
function cancelar(){
				location = "tarifasDestinos.php" ;
		
		}
function cargaTipos(airls){

	// Borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	
	
	$("tipoCliente").options.length = 0;
	$("slctipoClienteRangos").options.length = 0;
	//$("slctipoClienteCargoMin").options.length = 0;	
	//empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("tipoCliente").options[$("tipoCliente").options.length] = opcion;
	var opcion = new Option("Seleccione", "");
	$("slctipoClienteRangos").options[$("slctipoClienteRangos").options.length] = opcion;
	var opcion = new Option("Seleccione", "");
	$("slctipoClienteCargoMin").options[$("slctipoClienteCargoMin").options.length] = opcion;

	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);

		try {
			$("slctipoClienteRangos").options[$("slctipoClienteRangos").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
	
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);

		try {
			$("slctipoClienteCargoMin").options[$("slctipoClienteCargoMin").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
	
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);

		try {
			$("tipoCliente").options[$("tipoCliente").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
	
	$("tipoCliente").onchange=verGrid5;	
}

function verGrid(){
	llenaMunicipios();
	llenaEstados($("slcEstados").value,1,1,1,1,0,0,0);
	$("slcMunicipios").onchange=verGrid3;
}

function cargarEnvios(envios){
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcTipoe").options.length = 0;
	//empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("slcTipoe").options[$("slcTipoe").options.length] = opcion;
	
	for (var i=0; i<envios.length; i++){
		var envio = envios[i];
		var opcion = new Option(envio.desc, envio.id);
	
		try {
			$("slcTipoe").options[$("slcTipoe").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
	$("slcTipoe").onchange=verGrid6;
}

function cargarEnvios2(envios)
{ 
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd 
	$("slctipoEnvio").options.length = 0; 
	//empieza la carga de la lista 
	var opcion = new Option("Seleccione", ""); 
	$("slctipoEnvio").options[$("slctipoEnvio").options.length]=opcion; 

	for (var i=0; i<envios.length; i++)
	{ 
		var envio = envios[i]; 
		var opcion = new Option(envio.desc, envio.id); 
		try { $("slctipoEnvio").options[$("slctipoEnvio").options.length]=opcion; 
		}catch (e){alert("Error interno");} 
	}
}


	
function llenaRangos(campos){
   
	var campo = campos[0];  
        		              
	$("txtprimerRango").value = campo.primerRango;
	$("txtsegundoRango").value = campo.segundoRango;
	$("txttercerRango").value = campo.tercerRango;
	$("txtcuartoRango").value = campo.cuartoRango;			
	$("thPrimero").innerHTML= campo.primerRango;
	$("thSegundo").innerHTML= campo.segundoRango ;
	$("thTercero").innerHTML=campo.tercerRango ;
	$("thCuarto").innerHTML= campo.cuartoRango ;
	$("thPrimero2").innerHTML= campo.primerRango;
	$("thSegundo2").innerHTML= campo.segundoRango ;
	$("thTercero2").innerHTML=campo.tercerRango ;
	$("thCuarto2").innerHTML= campo.cuartoRango ;                
                
}	
function verGrid2(){
		llenaMunicipiosD();
		llenaEstados($("slcEstados").value,$("slcMunicipios").value,$("slcEstadosD").value,1,3,0,0,0);
		$("slcMunicipiosD").onchange=verGrid4;
}
function verGrid3(){
	llenaEstados($("slcEstados").value,$("slcMunicipios").value,1,1,2,0,0,0);

	}
function verGrid4(){
	llenaEstados($("slcEstados").value,$("slcMunicipios").value,$("slcEstadosD").value,$("slcMunicipiosD").value,4,0,0,0);

	}
function verGrid5(){
	llenaEstados($("slcEstados").value,$("slcMunicipios").value,$("slcEstadosD").value,$("slcMunicipiosD").value,6,0,$("tipoCliente").value,0);

	}
function verGrid6(){
	llenaEstados($("slcEstados").value,$("slcMunicipios").value,$("slcEstadosD").value,$("slcMunicipiosD").value,7,0,$("tipoCliente").value,$("slcTipoe").value);

	}	

function cargaEstados(estados){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("slcEstados").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("slcEstados").options[$("slcEstados").options.length] = opcion;

	for (var i=0; i<estados.length; i++){
	var estado = estados[i];
	var opcion = new Option(estado.desc, estado.id);

		try {
		$("slcEstados").options[$("slcEstados").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	$("slcEstados").value=9;
	}
 function modificarTarifaR(){				
			if(allgood2())
            {				       
				 var valores = "&txtprimerRango="	+$("txtprimerRango").value		+"&txtsegundoRango="		+$("txtsegundoRango").value		+"&txttercerRango="		+$("txttercerRango").value		+"&txtcuartoRango="		+$("txtcuartoRango").value	;
                 var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
valores=valores +usuario;
                 $Ajax("scripts/guardarTarifasC.php?operacion=6", {metodo: $metodo.POST, onfinish: fins, parametros: valores, avisoCargando:"loading"});
		
  
            }		
		}
function cargaEstadosD(estados){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("slcEstadosD").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("slcEstadosD").options[$("slcEstadosD").options.length] = opcion;

	for (var i=0; i<estados.length; i++){
	var estado = estados[i];
	var opcion = new Option(estado.desc, estado.id);

		try {
		$("slcEstadosD").options[$("slcEstadosD").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}
function llenaMunicipios(){
	
	var respuesta= "scripts/municipios.php?municipio=" + $("slcEstados").value;
	$Ajax(respuesta, {onfinish: cargaMunicipios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	}
	function cargaMunicipios(airls){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("slcMunicipios").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("slcMunicipios").options[$("slcMunicipios").options.length] = opcion;

	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("slcMunicipios").options[$("slcMunicipios").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}	
	function cargaMunicipiosb(airls){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("slcMunicipios").options.length = 0;
//empieza la carga de la lista
	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("slcMunicipios").options[$("slcMunicipios").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	$("slcMunicipios").value=17;
	}	
	function llenaMunicipiosD(){
	
	var respuesta= "scripts/municipios.php?municipio=" + $("slcEstadosD").value;
	$Ajax(respuesta, {onfinish: cargaMunicipiosD, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	}
	function cargaMunicipiosD(airls){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("slcMunicipiosD").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("slcMunicipiosD").options[$("slcMunicipiosD").options.length] = opcion;

	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("slcMunicipiosD").options[$("slcMunicipiosD").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}	
	
var indiceFilaFormulario=1;		
function addTarifa(tarifas){
	
var indicador=tarifas[0];

if(indicador.indice!=0)	
{
		$("visible").className="oculto";
}	
else{
	$("visible").className="";
	
	if(document.getElementById("tablaFormulario").rows.length>2){	

	var ultima = document.getElementById("tablaFormulario").rows.length;
		for(var j=ultima; j>2; j--){
				
	             document.getElementById("tablaFormulario").deleteRow(2);	
				 		
	}
}

	for (var i=0; i<tarifas.length; i++){
				
				var tarifa = tarifas[i];
			
			 myNewRow = document.getElementById("tablaFormulario").insertRow(-1); 
			 myNewRow.id=indiceFilaFormulario;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td >" + tarifa.origen +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" + tarifa.morigen +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			  myNewCell.innerHTML="<td>" + tarifa.destino +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" + tarifa.mdestino +"</td>";
             myNewCell=myNewRow.insertCell(-1);
			  myNewCell.innerHTML="<td>" + tarifa.cveTipoc +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			  myNewCell.innerHTML="<td>" + tarifa.tipoEnvio +"</td>";
			  var estatus='';
			  if(tarifa.estatus==1)
			  {estatus='Activa'}else{estatus='Desactiva'}
			   myNewCell=myNewRow.insertCell(-1);
			  myNewCell.innerHTML="<td>" + estatus +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" + tarifa.cargo99 +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			  myNewCell.innerHTML="<td>" + tarifa.cargo299 +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" + tarifa.cargo300 +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
              myNewCell.innerHTML="<td>" + tarifa.cuartoRango +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" + tarifa.cargoMinimo +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td><input type='hidden' id='cveDetalle_"+indiceFilaFormulario+"' value='" + tarifa.cveTarifa +"'/><input type='button' name='Editar["+indiceFilaFormulario+"]'  value='Editar' onclick='editarDetalle(this,cveDetalle_"+indiceFilaFormulario+")'></td>";
			 indiceFilaFormulario++;
 	
	}
}	
}


//funcion que verifica si existe un registro que tenga como llave el valor capturado
//hace una peticion que devuelve un único valor$("slcEstados").value,$("slcMunicipios").value,$("slcEstadosD").value,$("slcMunicipiosD").valuehdnTipoTarifa
function existe() {				
	if ($("slcEstados").value!=""){
		condicion="&estadoOrigen="+$("slcEstados").value+"&origen ="+$("slcMunicipios").value+"&estadoDestino="+$("slcEstados").value+"&destino="+$("slcMunicipiosD").value+"&cveTipoc="+ $("tipoCliente").value+"&tipoEnvio="+ $("slcTipoe").value;
			var url = "scripts/existe.php?keys=14&table=ctarifas"+condicion;
			$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
		}
	}
//esta funcion recibe el valor de la función anterior y lo evalúa	
	function next_existe(ex){	

		//extraemos el valor retornado por el servidor en un objeto json
		var exx=ex[0];
		var exists = exx.existe;
		//si el valor es mayor que cero, entonces el registro existe
		if (exists > 0){	
			//se piden los datos
			alert("Ya existe una tarifa con ese destino");
					
			}
		else{	//si la funcion devolvio cero, no existe el registro
			//limpiamos los campos del form
			
			insertar();	
			llenaEstados($("slcEstados").value,$("slcMunicipios").value,$("slcEstadosD").value,$("slcMunicipiosD").value,4,0,0,0);

			
			}
		}
		
	function insertar(){	
				
				if(allgood()){
				var estatus=0;
					if($("cbxEstatus").checked)
			{estatus=1;}else{estatus=0;}		       
				 var valores = "&origen="		+$("slcEstados").value		+"&munOrigen="		+$("slcMunicipios").value		+"&destino="		+$("slcEstadosD").value;
		valores = valores + "&munDestino="			+$("slcMunicipiosD").value		+"&tipoEnvio="	+$("slcTipoe").value		+"&tarifaMinima="		+$("txtTarifaMin").value		+"&tarifa1="		+$("txtTarifa1").value		+"&tarifa2="		+$("txtTarifa2").value		+"&tarifa3="		+$("txtTarifa3").value	+"&tarifa4="		+$("txtTarifa4").value	+"&tipoTarifa=" + $("hdnTipoTarifa").value;
	    var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value+  "&tipoCliente="+$("tipoCliente").value+"&estatus="+estatus;	
valores=valores +usuario;  

		$Ajax("scripts/guardarTarifas.php", {metodo: $metodo.POST, onfinish: fin3, parametros: valores, avisoCargando:"loading"});
		
				
				}
		
		}
		function fin3(res)
		{
			alert(res);
			cancelar();
		}
		
		function modificarTarifa(){	
				
				if(allgood()){
						var estatus=0;
					if($("cbxEstatus").checked)
			{estatus=1;}else{estatus=0;}	       
				 var valores = "";
		valores = valores + "&tarifaMinima="		+$("txtTarifaMin").value		+"&tarifa1="		+$("txtTarifa1").value		+"&tarifa2="		+$("txtTarifa2").value		+"&tarifa3="		+$("txtTarifa3").value	+"&tarifa4="		+$("txtTarifa4").value	+"&cveTarifa=" + $("hdncveTarifa").value;
	    var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value+"&estatus="+estatus;	
valores=valores +usuario;   
		$Ajax("scripts/modificarTarifas.php", {metodo: $metodo.POST, onfinish: fins, parametros: valores, avisoCargando:"loading"});
				//$("form2").reset();
				}
		
		}
		
		function llenaEstados(origen,zona,estado,destino,operacion,opc,cliente,envio){
		
			var respuesta= "scripts/datosTarifas.php?origen=" + origen +"&zona="+zona+"&estado="+estado+"&destino="+destino+"&operacion="+operacion+"&tabla="+$("hdnTipoTarifa").value;
			if(opc==1)
				var respuesta= "scripts/datosTarifas.php?tarifa="+$("txtTarifa").value+"origen=" + origen +"&zona="+zona+"&estado="+estado+"&destino="+destino+"&operacion="+operacion+"&tabla="+$("hdnTipoTarifa").value;
			else
				var respuesta= "scripts/datosTarifas.php?origen=" + origen +"&zona="+zona+"&estado="+estado+"&destino="+destino+"&operacion="+operacion+"&tabla="+$("hdnTipoTarifa").value+"&tipoCliente="+cliente+"&tipoEnvio="+envio;

				$Ajax(respuesta, {onfinish: addTarifa, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			
			}
		
		

		function fin(res){
		
		alert(res);
		
		
		}
		
		function fins(res){
		
		alert(res);
		location = "tarifasDestinos.php" ;
		
		}
		function cancelar(){
		
		location = "tarifasDestinos.php" ;
		
		}
		function allgood2(){
			var notGood = 0;
		
			if($("txtprimerRango").value == ""){$("txtprimerRango").addClassName("invalid"); notGood ++;} else{$("txtprimerRango").removeClassName("invalid");}	
			if($("txtsegundoRango").value == ""){$("txtsegundoRango").addClassName("invalid"); notGood ++;} else{$("txtsegundoRango").removeClassName("invalid");}	
			if($("txttercerRango").value == ""){$("txttercerRango").addClassName("invalid"); notGood ++;} else{$("txttercerRango").removeClassName("invalid");}	
			if($("txtcuartoRango").value == ""){$("txtcuartoRango").addClassName("invalid"); notGood ++;} else{$("txtcuartoRango").removeClassName("invalid");}	
			
			if(notGood > 0){
				alert("Hay Informacion erronea que ha sido resaltada en color!");
				$("btnCancelarT").disabled=false;
				return false;
			}else
			{
				return true;
			}

		}
		function allgood(){
		var notGood = 0;
		
		if($("slcEstados").value == ""){$("slcEstados").addClassName("invalid"); notGood ++;} else{$("slcEstados").removeClassName("invalid");}	
		if($("slcMunicipios").value == ""){$("slcMunicipios").addClassName("invalid"); notGood ++;} else{$("slcMunicipios").removeClassName("invalid");}	
		if($("slcEstadosD").value == ""){$("slcEstadosD").addClassName("invalid"); notGood ++;} else{$("slcEstadosD").removeClassName("invalid");}	
		if($("slcMunicipiosD").value == ""){$("slcMunicipiosD").addClassName("invalid"); notGood ++;} else{$("slcMunicipiosD").removeClassName("invalid");}	
		if($("slcTipoe").value == ""){$("slcTipoe").addClassName("invalid"); notGood ++;} else{$("slcTipoe").removeClassName("invalid");}	
		if($("tipoCliente").value == ""){$("tipoCliente").addClassName("invalid"); notGood ++;} else{$("tipoCliente").removeClassName("invalid");}	
		
		//Checar Datos de Envío
		if(isNaN($("txtTarifa1").value)||($("txtTarifa1").value == "")){$("txtTarifa1").addClassName("invalid"); notGood ++;} else{$("txtTarifa1").removeClassName("invalid");}
		if(isNaN($("txtTarifa2").value)||($("txtTarifa2").value == "")){$("txtTarifa2").addClassName("invalid"); notGood ++;} else{$("txtTarifa2").removeClassName("invalid");}
		if(isNaN($("txtTarifa3").value)||($("txtTarifa3").value == "")){$("txtTarifa3").addClassName("invalid"); notGood ++;} else{$("txtTarifa3").removeClassName("invalid");}
		if(isNaN($("txtTarifa4").value)||($("txtTarifa4").value == "")){$("txtTarifa4").addClassName("invalid"); notGood ++;} else{$("txtTarifa4").removeClassName("invalid");}

		
		if(notGood > 0){
			alert("Hay Informacion erronea que ha sido resaltada en color!");
			$("btnCancelarT").disabled=false;
			return false;
			} else
			{
				return true;
			}
			
		}
		function editarDetalle(obj,cveDetalle){
			
	  var url = "scripts/datosTarifasC.php?operacion=6&tarifa="+ $(cveDetalle).value;
	  $("hdncveTarifa").value = $(cveDetalle).value;
	  $Ajax(url, {onfinish: editaDetalle, tipoRespuesta: $tipo.JSON});

      //$("btnModificarD").className = "";
      //$("btnCancelarD").className = "";
      //$("btnModificarD").onclick=modificarDetalle;	
      //$("btnCancelarD").onclick=limpiarDetalle;	
      //$("btnGuardar").disabled=true;
      //eliminamos la fila una vez que se cargan los datos
      var oTr = obj;
         while(oTr.nodeName.toLowerCase()!='tr'){
          oTr=oTr.parentNode;
         }
         var root = oTr.parentNode;
         root.removeChild(oTr);
         
	}
	 function editaDetalle(campos){

	 var campo=campos[0];
	 var respuesta= "scripts/catalogoCP.php?operacion=3&municipio=" +campo.origen+"&estado="+campo.estadoOrigen;
	$Ajax(respuesta, {onfinish: cargaMunicipiosG, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
    var respuesta= "scripts/catalogoCP.php?operacion=3&municipio=" +campo.destino+"&estado="+campo.estadoDestino;
	$Ajax(respuesta, {onfinish: cargaMunicipiosDG, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			                
                $("slcEstados").value = campo.estadoOrigen;
                $("slcEstadosD").value = campo.estadoDestino;
				$("slcTipoe").value = campo.tipoEnvio;
             $("tipoCliente").value = campo.cveTipoc;
             if(campo.estatus==1)
			{$("cbxEstatus").checked=true;}else{$("cbxEstatus").checked=false;}
	
				$("txtTarifa1").value = campo.cargo99;
				$("txtTarifa2").value = campo.cargo299;
				$("txtTarifa3").value = campo. cargo300;
				$("txtTarifa4").value = campo.cuartoRango;
				$("txtTarifaMin").value = campo.cargoMinimo;
				$("btnModificarT").disabled=false; 
         $("btnModificarT").onclick=modificarTarifa;    	 
         $("btnGuardar").disabled=true;   
          $("slcEstados").disabled = true;
                $("slcEstadosD").disabled = true;
				$("slcTipoe").disabled = true;  
				$("slcMunicipiosD").disabled = true;
				$("slcMunicipios").disabled = true; 
				$("tipoCliente").disabled = true;
				$("btnCancelarT").disabled=false;
    }
    function cargaMunicipiosDG(airls){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("slcMunicipiosD").options.length = 0;
//empieza la carga de la lista

	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("slcMunicipiosD").options[$("slcMunicipiosD").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	var airl = airls[0];
    
$("slcMunicipiosD").value=airl.mun;
	}
function cargaMunicipiosG(airls){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("slcMunicipios").options.length = 0;
//empieza la carga de la lista

	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc,airl.id);

		try {
		$("slcMunicipios").options[$("slcMunicipios").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
var airl = airls[0];
    
	$("slcMunicipios").value=airl.mun;

}	
