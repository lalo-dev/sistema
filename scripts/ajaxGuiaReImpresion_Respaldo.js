window.onload = inicia;

function inicia() {
		
	$("btnModificar").disabled=true;
	$("btnCancelar").disabled=true;	
	
	$Ajax("scripts/Sucursales.php", {onfinish: cargaSucursal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
   	$Ajax("scripts/envios.php", {onfinish: cargarEnvios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
	$("autoCliente").className = "autocomplete";
	new Ajax.Autocompleter("txtCliente", "autoCliente", "scripts/catalogoClientes.php?operacion=4", {paramName: "caracteres", afterUpdateElement:datosClientes1});
	$("autoClienteC").className = "autocomplete";

	
	$("txtNombredo").onchange=llenaMunicipios;
	$("txtEstadoD").onchange=llenaMunicipiosD;
	
	if(($("txtGuia").value!="Guia House")&&($("txtGuia").value!=""))
	{
	  $("btnCancelar").disabled=false;
	  $("btnModificar").disabled=false;      
		var guia = $("txtGuia").value;
    	$Ajax("scripts/datosDocGuias.php?guia="+guia, {onfinish: function (datos)
							{
								$Ajax("scripts/datosGuias.php?cveguia="+guia, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
							}
							, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});	
	   $("btnModificar").onclick=imprimir;
	}
	
	//Inicializando autocomplete
	$("autoGuia").className = "autocomplete";
	new Ajax.Autocompleter("txtGuia", "autoGuia", "scripts/catalogoGuias.php?operacion=5", {paramName: "caracteres",afterUpdateElement:existe});
	$("txtGuia").onchange=existe;
	
	//Primero habrá que elegir la estación para poder iniciar la búsqueda de los consignatarios
	bloquearDes();
	
	//Cargando listas	
	$("autoPostal").className = "autocomplete";
	new Ajax.Autocompleter("txtCodigoPD", "autoPostal", "scripts/catalogoCP.php?operacion=1", {paramName: "caracteres", afterUpdateElement:datosdes});
	
	$("btnCancelar").onclick=function(){	
		location = "guiaReImpresion.php";
	};

	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("btnModificar").style.visibility="hidden";
		$("btnModificar").style.display="none";		
	}

}

function bloquearDes(opc)
{
	if($("txtGuia").disabled)
		val=false;
	else
		val=true;
	inputs=$$("#derecho2 input[type=text]:not([id=txtCodigoPD]),#derecho2 select:not([id=slcSucursal])");
	for(i=0;i<inputs.length;i++)
	{	inputs[i].disabled=val; }
	$("slcSucursal").onchange=cargarDes;
}

function cargarDes()
{
	$("autoDestinatario").className = "";	
	if($("slcSucursal").value!=""){
		setTimeout("asignarVal()",200);	
		alert("Cargando consignatarios.");	
		$("autoDestinatario").className = "autocomplete";
	}

}

function asignarVal(){
		$("txtNombreDes").value="";
		inputs=$$("#derecho2 input[type=text]:not([id=txtCodigoPD]),#derecho2 select:not([id=slcSucursal])");
		for(i=0;i<inputs.length;i++)
		{	inputs[i].disabled=false; }
		new Ajax.Autocompleter("txtNombreDes", "autoDestinatario", "scripts/catalogoConsignatarios.php?operacion=2&estacion="+$("slcSucursal").value+"&codigo="+$("txtCodigoPD").value, {paramName: "caracteres", afterUpdateElement:existeDestinatario});
}

function datosdes(){  
	valores= $('txtCodigoPD').value.split("-");
        $('txtCodigoPD').value=valores[0];
	$('txtColoniaD').value=valores[2];		
	$Ajax("scripts/catalogoCP.php?operacion=6&codigo="+valores, {onfinish: datosCP, tipoRespuesta: $tipo.JSON});
}

function cargarDesCP(){

	$("autoDestinatario").className = "";		
	
	if($('txtCodigoPD').value!=""){
		new Ajax.Autocompleter("txtNombreDes", "autoDestinatario", "scripts/catalogoConsignatarios.php?operacion=2&estacion="+$("slcSucursal").value+"&codigo="+$("txtCodigoPD").value, {paramName: "caracteres", afterUpdateElement:existeDestinatario});	
		$("autoDestinatario").className = "autocomplete";
	}else
	{
		$('txtNombreDes').disabled=true;		
		setTimeout("asignarVal2()",300);		
		alert("Actualizando Consignatarios");
		$("autoDestinatario").className = "autocomplete";
	}

}

function asignarVal2(){
	new Ajax.Autocompleter("txtNombreDes", "autoDestinatario", "scripts/catalogoConsignatarios.php?operacion=2&estacion="+$("slcSucursal").value, {paramName: "caracteres", afterUpdateElement:existeDestinatario});
		
}

function datosCP(datos)
{
	dato=datos[0];
	if($('txtCodigoPD').value!=""){
		new Ajax.Autocompleter("txtNombreDes", "autoDestinatario", "scripts/catalogoConsignatarios.php?operacion=2&estacion="+$("slcSucursal").value+"&codigo="+$("txtCodigoPD").value, {paramName: "caracteres", afterUpdateElement:existeDestinatario});	
	}else
	{
		new Ajax.Autocompleter("txtNombreDes", "autoDestinatario", "scripts/catalogoConsignatarios.php?operacion=2&estacion="+$("slcSucursal").value, {paramName: "caracteres", afterUpdateElement:existeDestinatario});	
	}

    $('txtEstadoD').value=dato.cveEstado;
	llenaMunicipiosD();
	setTimeout("datoMun("+dato.cveMunicipio+")",40); //Tiempo para que cargue los Municipios, y si es posible poner su valor
}

function datoMun(municipio)
{
	$('txtMunicipioD').value=municipio;
}


function cargarEnvios(envios)
{
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcTipoe").options.length = 0;
	//empieza la carga de la lista

	for (var i=0; i<envios.length; i++){
		var envio = envios[i];
		var opcion = new Option(envio.desc, envio.id);
	
		try {
			$("slcTipoe").options[$("slcTipoe").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
	$("slcTipoe").value="NORMAL";
}
	

function llenaMunicipios(){
	
	var respuesta= "scripts/municipios.php?municipio=" + $("txtNombredo").value;
	$Ajax(respuesta, {onfinish: cargaMunicipios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	}
function cargaMunicipios(airls)
{
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("txtMunR").options.length = 0;
	//empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("txtMunR").options[$("txtMunR").options.length] = opcion;
	
	for (var i=0; i<airls.length; i++){	
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);
		
		try {
			$("txtMunR").options[$("txtMunR").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
}

function cargaMunicipiosG(airls){
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("txtMunR").options.length = 0;
	//empieza la carga de la lista
	
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);
		
		try {
			$("txtMunR").options[$("txtMunR").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
	var airl = airls[0];
	
	$("txtMunR").value=airl.mun;	
}	
 	
function llenaMunicipiosD(){

	var respuesta= "scripts/municipios.php?municipio=" + $("txtEstadoD").value;
	$Ajax(respuesta, {onfinish: cargaMunicipiosD, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

}
function cargaMunicipiosD(airls){
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("txtMunicipioD").options.length = 0;
	//empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("txtMunicipioD").options[$("txtMunicipioD").options.length] = opcion;
	
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);
		
		try {
			$("txtMunicipioD").options[$("txtMunicipioD").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
	
}
	
function cargaMunicipiosDG(airls){
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("txtMunicipioD").options.length = 0;
	//empieza la carga de la lista
	
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);
		
		try {
			$("txtMunicipioD").options[$("txtMunicipioD").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
	var airl = airls[0];
	
	$("txtMunicipioD").value=airl.mun;

}

function cargaSucursal(airls){
 
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcSucursal").options.length = 0;
	//empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("slcSucursal").options[$("slcSucursal").options.length] = opcion;
	
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);
		
		try {
			$("slcSucursal").options[$("slcSucursal").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}	
}

//funcion que verifica si existe una guia
//hace una peticion que devuelve un único valor
function existe() {	
	//$("status").innerHTML="";
	if ($("txtGuia").value!="" ){
		//Separamos la clave de la guía del Nombre del Remitente
		valor_guia=$("txtGuia").value.split(" - ",1);
		$("txtGuia").value=valor_guia;
		var url = "scripts/existe.php?keys=1&table=cguias&field1=cveGuia&f1value="+$("txtGuia").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}
}
function existeDestinatario() {
	if ($("txtNombreDes").value!="" ){
		//Separamos la clave del Consignatario
		valores=$("txtNombreDes").value.split("-");
		$("txtNombreDes").value=valores[1];
		var url = "scripts/existe.php?keys=19&f1value="+valores[0];
		$Ajax(url, {onfinish: next_existeDestinatario, tipoRespuesta: $tipo.JSON});
	}
}

function next_existeDestinatario(ex)
{
	
	existe=ex[0];
	if(existe.existe>0){
		var url = "scripts/datosConsignatarios.php?operacion=1&consignatario="+existe.cve;
		$Ajax(url, {onfinish: llenaDestinatario, tipoRespuesta: $tipo.JSON});
	}else
	{ $('txthNombreDes').value=0;	}
				
}

function llenaDestinatario(datos)
{
	dato=datos[0];	
	$('txthNombreDes').value=dato.cve;
	$('slcSucursal').value=dato.estacion;
	$('txtNombreDes').value=dato.nombre;
	$('txtNombreDes').disabled=true;
	$('txthNombreDesP').value=dato.nombre;	
	$('txtEstadoD').value=dato.estado;	
	$('txtColoniaD').value=dato.colonia;
	$('txtCalleD').value=dato.calle;
	$('txtCodigoPD').value=dato.codigoPostal;
	$('txtTelefonoD').value=dato.telefono;	
	
	var respuesta= "scripts/municipios.php?municipio=" + dato.estado;
	$Ajax(respuesta, {onfinish: cargaMunicipios2, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	setTimeout("cargaMunicipio("+dato.cve+")",60);
	
}

function cargaMunicipio(consignatario)
{
	var url = "scripts/datosConsignatarios.php?operacion=1&consignatario="+consignatario;
	$Ajax(url, {onfinish: llenaDestinatarioMunicipio, tipoRespuesta: $tipo.JSON});

}
function llenaDestinatarioMunicipio(datos)
{
	dato=datos[0];	
	$('txtMunicipioD').value=dato.municipio;
}
function cargaMunicipios2(airls){
		// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
		$("txtMunicipioD").options.length = 0;
		//empieza la carga de la lista
		var opcion = new Option("Seleccione", "");
		$("txtMunicipioD").options[$("txtMunicipioD").options.length] = opcion;
		
		for (var i=0; i<airls.length; i++){
		
			var airl = airls[i];
			var opcion = new Option(airl.desc, airl.id);
			
			try { $("txtMunicipioD").options[$("txtMunicipioD").options.length]=opcion;}
			catch (e){alert("Error interno");}
		}
}	

//esta funcion recibe el valor de la función anterior y lo evalúa	
function next_existe(ex){

	
	var exx=ex[0];
	var exists = exx.existe;
	
	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0){
		//se piden los datos
		 location = "guiaReImpresion.php?cveGuia=" + $("txtGuia").value ;
	}
	else{
		//si la funcion devolvio cero, no existe el registro
		//el boton borrar no es útil aqui, por lo tanto lo ocultamos
		$("btnModificar").disabled=true;
		$("btnCancelar").disabled=false;
	}
}

function borrar(){
	
	if (confirm("Confirme que desea borrar")){
	
		var hid ="Campo1=" + $("txtGuia").value;
		hid+="&nombreCampo1=cveGuia";
		hid+="&tabla=cguias" ;
				
		$Ajax("scripts/borrarDatos.php", {metodo: $metodo.POST, onfinish: fin, parametros: hid, avisoCargando:"loading"});
		
		$("form3").reset();
	
	}
		
}

function llenaDirecciones(campos){
	var campo = campos[0];
	$("txtRemitente").value = campo.razonSocial;
	if(campo.numero=="") numero="";
	else numero=" No."+campo.numero;
	$("txtCalleR").value = campo.calle+numero;
	$("txtTelefonoR").value = campo.telefono;
	$("txtRfcR").value = campo.rfc;
	$("txtNombredo").value = campo.cveEstado;
	$("txtColR").value = campo.colonia;
	$("txtCodigoPr").value = campo.codigoPostal;
	$("txtMunR").value = campo.cveMunicipio;
	$("txtCodigoC").value = campo.cveCliente;
	$("hdncveDireccion").value=campo.cveDireccion;
	
}

function formatoFecha(fecha)
{
	if(fecha=="00/00/0000")
	{ fecha="";	}
	return fecha;
}

function pausecomp(millis) 
{
	var date = new Date();
	var curDate = null;
	
	do { curDate = new Date(); } 
	while(curDate-date < millis);
}

function llenaDatos(campos){
	//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var campo = campos[0];

	$("txtRemitente").value = campo.nombreRemitente;
	$("txtCalleR").value = campo.calleRemitente;
	$("txtTelefonoR").value = campo.telefonoRemitente;
	$("txtRfcR").value = campo.rfcRemitente;

	$("txtColR").value = campo.coloniaRemitente;
	$("txtCodigoPr").value = campo.codigoPR;
	$("txtPiezas").value = campo.piezas;
	$("txtKg").value = campo.kg;
	$("txtVol").value = campo.volumen;
	$("txthStatus").value = campo.status;
	
	$('txaObservacionesC').value = campo.obsCliente;
	$('txtDContener').value = campo.obsDContener;
	
	for(var idx1 = 0; idx1 < $("slcSucursal").options.length; idx1 ++){
		if($("slcSucursal").options[idx1].value == campo.sucursalDestino){
			$("slcSucursal").selectedIndex = idx1;
			$("slcSucursal").options[idx1].selected=true;
		}
	}	

	$("txtCalleD"). value = campo.calleDestinatario;
	$("txtCodigoPD").value = campo.codigoPostaldestinatario;
	$("txtColoniaD"). value = campo.coloniaDestinatario;
	
	$("txtTelefonoD").value = campo.telefonoD;	
	$("txtCodigoC").value = campo.cliente;
			
	var respuesta= "scripts/catalogoCP.php?operacion=3&municipio=" +campo.municipioDestinatario+"&estado="+campo.estadoDestinatario;
	$Ajax(respuesta, {onfinish: cargaMunicipiosDG, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	if(campo.consignatario==""){
		$("txthNombreDes").value=0;	
	}
	else{
		$("txthNombreDes").value=campo.consignatario;
	}
		
	$("txtNombreDes").value = campo.nombreD;
	$("txthNombreDesP").value = campo.nombreD;
		
	var respuesta= "scripts/catalogoCP.php?operacion=3&municipio=" +campo.municipioRemitente+"&estado="+campo.estadoRemitente;
	$Ajax(respuesta, {onfinish: cargaMunicipiosG, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	alert("Cargando Guia ...");
	
	$("hdncveDireccion").value=campo.cveDireccion;
	//Hacemos pausa
	$("txtEstadoD").value=campo.estadoDestinatario;	
	$("txtNombredo").value=campo.estadoRemitente;
	
}

function bloquea_todo()
{
//	alert('');
	inputs=$$('input');
	selects=$$('select');
	textarea=$$('textarea');
	img=$$('.tcalIcon');
	
	//Bloquear TODO
	for(i=0;i<inputs.length;i++)  { if(inputs[i].type!="button") inputs[i].disabled=true;}
	for(i=0;i<selects.length;i++) { selects[i].disabled=true;}
	for(i=0;i<textarea.length;i++){ textarea[i].disabled=true;}
	for(i=0;i<img.length;i++){ img[i].style.visibility="hidden";}
	//Desbloquear Generales
	$("txtGuia").disabled=false;
	////$("txtRazonSocial").disabled=false;
	////$("slcStatus").disabled=false;
	$("lblActivado").disabled=false;
	$("chkActivado").disabled=false;
}

function fin(res){
	alert(res);
	location = "guiaReImpresion.php";
}
		
function allgood(){
	
	
	var checar=true;
	if($('txtFacturas').value!="")
	{
		if(confirm("A\u00FA no ha ingresado una factura,\u00BFDesea ingresarla?"))
			checar=false;
		else
			checar=true;
	}
	else if($('txtEntrega').value!="")
	{
		if(confirm("A\u00FA no ha ingresado una entrega,\u00BFDesea ingresarla?"))
			checar=false;
		else
			checar=true;
	}
	

	if(!checar){
		return false;
	}
	else
	{
		if($('txaFacturas').value!="")
		{
			//Checar que no se repita el valor
			var cadena=$('txaFacturas').value;   
			arreglo=cadena.split(",");
			longitud1=arreglo.length;
			arreglo2=arreglo.uniq();         //Traerá arreglo sin repeticiones
			longitud2=arreglo2.length;
			
			if(longitud1 != longitud2)		//Valores repetidos
			{
				alert("Existe un error en las facturas, debe haber un valor repetido (o hay comas seguidas).");
				return false;
			}
		}
		if($('txaEntregas').value!="")
		{
			//Checar que no se repita el valor
			var cadena=$('txaEntregas').value;   
			arreglo=cadena.split(",");
			longitud1=arreglo.length;
			
			arreglo2=arreglo.uniq();         //Traerá arreglo sin repeticiones
			longitud2=arreglo2.length;
			
			if(longitud1 != longitud2)		//Valores repetidos
			{
				alert("Existe un error en las entregas, debe haber un valor repetido (o hay comas seguidas).");
				return false;
			}
		}
	}
	
	
	//Checar que las fechas ya se de Entrega o la de Acuse esten correctas
	var expresion = /^\s*(\d{2,2})\/(\d{2,2})\/(\d{4,4})\s*$/;
	
	//Checar Volumne piezas y kg
	if($("txtPiezas").value!="" && (isNaN($("txtPiezas").value))){
		alert("No. de piezas incorrecto.");
		return false;
	}
	if($("txtKg").value!="" && (isNaN($("txtKg").value))){
		alert("Peso incorrecto.");
		return false;
	}
	if($("txtVol").value!="" && (isNaN($("txtVol").value))){
		alert("Vol\u00FAmen incorrecto.");
		return false;
	}
	
	var notGood = 0;
	
	//En caso de haber ingresado algún nombre de Destinatario, se comprobará que se hayan ingresado el resto de los datos
	if($('txtNombreDes').value!="")
	{
		//Datos Destinatario
		if($("slcSucursal").value == "")  {$("slcSucursal").addClassName("invalid"); notGood ++;}   else{$("slcSucursal").removeClassName("invalid");}
		if($("txtNombreDes").value == "") {$("txtNombreDes").addClassName("invalid"); notGood ++;}  else{$("txtNombreDes").removeClassName("invalid");}
		if($("txtEstadoD").value == "")   {$("txtEstadoD").addClassName("invalid"); notGood ++;}    else{$("txtEstadoD").removeClassName("invalid");}
		if($("txtMunicipioD").value == ""){$("txtMunicipioD").addClassName("invalid"); notGood ++;} else{$("txtMunicipioD").removeClassName("invalid");}
		if($("txtColoniaD").value == "")  {$("txtColoniaD").addClassName("invalid"); notGood ++;}   else{$("txtColoniaD").removeClassName("invalid");}
		if(isNaN($("txtCodigoPD").value)||$("txtCodigoPD").value == ""){$("txtCodigoPD").addClassName("invalid"); notGood ++;} else{$("txtCodigoPD").removeClassName("invalid");}
		$('txthAlta').value=1;
	}else 
	{
		$("slcSucursal").removeClassName("invalid");
		$("txtNombreDes").removeClassName("invalid");
		$("txtEstadoD").removeClassName("invalid");
		$("txtMunicipioD").removeClassName("invalid");
		$("txtCalleD").removeClassName("invalid");
		$("txtCodigoPD").removeClassName("invalid");
		$('txthAlta').value=0;
	
	}

	
	if(notGood > 0){
		alert("La informaci\u00F3n para el consignatario es incorrecta!");
		return false;
	} 	
	
	
	var notGood = 0;
	$("error").innerHTML="";
	
	//Las validaciones se harán apartir del Estado de la Guia
	if($("slcStatus").value == ""){$("slcStatus").className = "invalid"; notGood ++;} 
	else
	{
		$("slcStatus").removeClassName("invalid");
		//Checar Valores según estado
		//Se limpiarán, por que serán evaludas después
		elementos=$$("#form3 input[type=text],#form3 select,#form3 textarea");
		for(i=0;i<elementos.length;i++)
		{ 	
			id=elementos[i].id; 
			if((id!="txtGuia")&&(id!="txtCodigoC")&&(id!="slcStatus")){
				$(id).removeClassName("invalid");
			}
		}
		if($("slcStatus").value =="Carga Documentandose")
		{
			//Checar Datos de Recepción
			if($("slcTipoe").value == ""){$("slcTipoe").addClassName("invalid"); notGood ++;} else{$("slcTipoe").removeClassName("invalid");}
			if($("txtRecepcioncye").value == ""){$("txtRecepcioncye").addClassName("invalid"); notGood ++; } 
			else
			{	
				if(!($("txtRecepcioncye").value.match(expresion)))
				{$("txtRecepcioncye").addClassName("invalid"); alert("El formato de la fecha de recepci\u00F3n es incorrecto (dd/mm/yyyy)."); return false;} 
				else{$("txtRecepcioncye").removeClassName("invalid");}
			}
			
			//Checar Datos de Envío
			if(isNaN($("txtPiezas").value)||($("txtPiezas").value == "")){$("txtPiezas").addClassName("invalid"); notGood ++;} else{$("txtPiezas").removeClassName("invalid");}
		
		}

		if((($("slcStatus").value =="En proceso de Entrega"))||($("slcStatus").value =="Enviada A destino")||($("slcStatus").value =="Entrega Rechazada")||($("slcStatus").value =="Sin localizar destinatario")||($("slcStatus").value =="Dirrecion erronea")||($("slcStatus").value =="Recabando Sello")||($("slcStatus").value =="Faltan documentos")||($("slcStatus").value =="Concluida")||($("slcStatus").value =="Con cita para entrega")||($("slcStatus").value =="Entregada"))
		{
				//Checar Datos de Recepción
				if($("slcTipoe").value == ""){$("slcTipoe").addClassName("invalid"); notGood ++;} else{$("slcTipoe").removeClassName("invalid");}		
				if($("txtRecepcioncye").value == ""){$("txtRecepcioncye").addClassName("invalid"); notGood ++;} else{$("txtRecepcioncye").removeClassName("invalid");}	
	
				//Checar Datos de Envío
				if(isNaN($("txtPiezas").value)||($("txtPiezas").value == "")){$("txtPiezas").addClassName("invalid"); notGood ++;} else{$("txtPiezas").removeClassName("invalid");}
				//Datos Remitente
				if($("txtRemitente").value == "") {$("txtRemitente").addClassName("invalid"); notGood ++;} else{$("txtRemitente").removeClassName("invalid");}
				if($("txtNombredo").value == "")  {$("txtNombredo").addClassName("invalid"); notGood ++;}  else{$("txtNombredo").removeClassName("invalid");}
				if($("txtColR").value == "")      {$("txtColR").addClassName("invalid"); notGood ++;}      else{$("txtColR").removeClassName("invalid");}
				if($("txtTelefonoR").value == "") {$("txtCalleR").addClassName("invalid"); notGood ++;}    else{$("txtCalleR").removeClassName("invalid");}
				if($("txtMunR").value == "")      {$("txtMunR").addClassName("invalid"); notGood ++;}      else{$("txtMunR").removeClassName("invalid");}				
				if(isNaN($("txtKg").value)||$("txtKg").value == "")            {$("txtKg").addClassName("invalid"); notGood ++;} else{$("txtKg").removeClassName("invalid");}
				if(isNaN($("txtCodigoPr").value)||$("txtCodigoPr").value == ""){$("txtCodigoPr").addClassName("invalid"); notGood ++;} else{$("txtCodigoPr").removeClassName("invalid");}
				
				//Datos Destinatario
				if($("slcSucursal").value == "")  {$("slcSucursal").addClassName("invalid"); notGood ++;}   else{$("slcSucursal").removeClassName("invalid");}
				if($("txtNombreDes").value == "") {$("txtNombreDes").addClassName("invalid"); notGood ++;}  else{$("txtNombreDes").removeClassName("invalid");}
				if($("txtEstadoD").value == "")   {$("txtEstadoD").addClassName("invalid"); notGood ++;}    else{$("txtEstadoD").removeClassName("invalid");}
				if($("txtMunicipioD").value == ""){$("txtMunicipioD").addClassName("invalid"); notGood ++;} else{$("txtMunicipioD").removeClassName("invalid");}
				if($("slcSucursal").value == "")  {$("slcSucursal").addClassName("invalid"); notGood ++;}   else{$("slcSucursal").removeClassName("invalid");}

				if($("txtCalleD").value == "")    {$("txtCalleD").addClassName("invalid"); notGood ++;}     else{$("txtCalleD").removeClassName("invalid");}
				if(isNaN($("txtCodigoPD").value)||$("txtCodigoPD").value == ""){$("txtCodigoPD").addClassName("invalid"); notGood ++;} else{$("txtCodigoPD").removeClassName("invalid");}
		}

		if(($("slcStatus").value =="Entregada")||($("slcStatus").value =="Concluida"))
		{
	
			if($("txtFechaEntrega").value == ""){$("txtFechaEntrega").addClassName("invalid"); notGood ++;} else{$("txtFechaEntrega").removeClassName("invalid");}
		
			
			
			var chks=4;
			if($("chkSello").checked) chks++;
			if($("chkFirma").checked) chks ++;
			if($("chkRespaldo").checked) chks ++;
			if($("chkReexpedicion").checked) chks ++;
			
			if(chks==4)//Significa que no seleccionaron ningún check
			{
				$("error").innerHTML="<label style='font-size:12px;font-family:Verdana, Geneva, sans-serif;color:#F90;'>Seleccione alguna de las opciones</label>";					
				notGood++;
									
			}else
			{
				$("error").innerHTML="";
				
			}
	
		}
		if($("slcStatus").value =="Concluida")
		{
			//Checar los Datos del Acuse				
			if($("txtFechaA").value == ""){ $("txtFechaA").addClassName("invalid"); notGood ++;} else{$("txtFechaA").removeClassName("invalid");}	
	
			
		}
		if($("txtGuiaAerea").value!=''){
			if(isNaN($("txtCtoGuiaAerea").value)){$("txtCtoGuiaAerea").addClassName("invalid"); notGood ++;} else{$("txtCtoGuiaAerea").removeClassName("invalid");}
			if($("txtCtoGuiaAerea").value==0 ||$("txtCtoGuiaAerea").value=='')
			{
				if(!confirm("El n\u00FAmero de gu\u00EDa a\u00E9rea no tiene un costo,el costo de todas las gu\u00EDa con ese mismo n\u00FAmero ser\u00E1 de 0.\n\u00BFDesea continuar?")){ return false;}
			}
		}
		else
			$("txtCtoGuiaAerea").value='';
		
	}

	if(notGood > 0){
		alert("\u00A1Hay informaci\u00F3n err\u00F3nea que ha sido resaltada en color!");
		return false;
	} 
	else { 
		
		if(($("slcStatus").value =="Carga Documentandose")&&($("txtGuia").disabled==false)){
			//Preguntar por el número de guía ya que posteriromente no se podrá modificar
			if(!confirm("Esta asignando el \""+$('txtGuia').value+"\" como n\u00FAmero de gu\u00EDa.\n\u00BFEl n\u00FAmero de gu\u00EDa es correcto?(No podr\u00E1 ser modificado posteriormente)")){ return false;}
		}
		return true;	
	}
	
}

function imprimir()
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	var impNum = "si";
	if($("rdbImprimirNo").checked)
	{
		impNum = "no";
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if($('rdbRango').checked)
	{
		if(allGood())
		{
			if($('txtCliente').value=="")
			{
				$('hddCveCliente').value=0;
			}
			//Obtenemos los datos +"&imprimirNum="+impNum
			var valores="imprimirNum="+impNum+"&txtRangoInicio="+$('txtRangoInicio').value+"&txtRangoFin="+$('txtRangoFin').value+"&hddCveCliente="+$('hddCveCliente').value+
					    "&txtFechaInicio="+$('txtFechaInicio').value+"&txtFechaFin="+$('txtFechaFin').value;
			
			var win = new Window({className: "mac_os_x", title: "Guias", top:70, left:300, width:700, height:400, url: 'scripts/guiapdfRI.php?'+valores, showEffectOptions: {duration:1.5}});
        win.show();
		}
	}
	else
	{
		var numGuia = $('txtGuia').value.toUpperCase();
		var numPiezas = $('txtPiezas').value.toUpperCase();
		var numKilos = $('txtKg').value.toUpperCase();
		var volumen = $('txtVol').value.toUpperCase();
		var nombreR = $('txtRemitente').value.toUpperCase();
		var estadoR = $('txtNombredo');
		estadoR = estadoR.options[estadoR.selectedIndex].text.toUpperCase();
		var municipioR = $('txtMunR');
		municipioR = municipioR.options[municipioR.selectedIndex].text.toUpperCase();	
		var calleR = $('txtCalleR').value.toUpperCase();
		var coloniaR = $('txtColR').value.toUpperCase();
		var cpR = $('txtCodigoPr').value.toUpperCase();
		var telefonoR = $('txtTelefonoR').value.toUpperCase();
		var nombreC = $('txtNombreDes').value.toUpperCase();
		var estadoC = $('txtEstadoD');
		estadoC = estadoC.options[estadoC.selectedIndex].text.toUpperCase();
		var municipioC = $('txtMunicipioD').value;
		//alert('ogigen: '+municipioC);
		parseInt(municipioC);
		parseado = municipioC/municipioC;
		if(parseado == 1)
		{
			//alert('case 1');
			//alert(municipioC);
			municipioC = $('txtMunicipioD');
			municipioC = municipioC.options[municipioC.selectedIndex].text.toUpperCase();
		}
		else
		{
			//alert('case 2');
			estadoC = '';
		}
		var calleC = $('txtCalleD').value.toUpperCase();
		var coloniaC = $('txtColoniaD').value.toUpperCase();
		var cpC = $('txtCodigoPD').value.toUpperCase();
		var telefonoC = $('txtTelefonoD').value.toUpperCase();
		
		
		var valores="txtGuia="+$('txtGuia').value+"&imprimirNum="+impNum+"&txtRangoInicio="+$('txtRangoInicio').value+"&txtRangoFin="+$('txtRangoFin').value+"&hddCveCliente="+$('hddCveCliente').value+"&txtFechaInicio="+$('txtFechaInicio').value+"&txtFechaFin="+$('txtFechaFin').value;
		var win = new Window({className: "mac_os_x", title: "Guias", top:70, left:300, width:700, height:400, url: 'scripts/guiapdfRI.php?'+valores, showEffectOptions: {duration:1.5}});
        win.show();
	}
}

/********/
function chkFiltro(objeto,opcion)
{
	//Si opcion=0 --> Guia unica
	if(opcion==0 && objeto.checked)
	{
		$('txtRangoInicio').value="";
		$('txtRangoInicio').disabled=true;
		
		$('txtRangoFin').value="";
		$('txtRangoFin').disabled=true;
		
		$('txtCliente').value="";
		$('txtCliente').disabled=true;
		
		$('hddCveCliente').value=0;
		$('hddCveCliente').disabled=true;
		
		$('txtFechaInicio').value="";
		$('txtFechaInicio').disabled=true;
		
		$('txtFechaFin').value="";
		$('txtFechaFin').disabled=true;	
		
		$('txtGuia').value="";
		$('txtGuia').disabled=false;				     		
		$('btnModificar').disabled=true;
	}
	//Si opcion=1 --> Rango Guias	
	if(opcion==1 && objeto.checked)
	{
		$('txtGuia').value="";
		$('txtGuia').disabled=true;		
		$('btnModificar').disabled=false;				
		$("btnModificar").onclick=imprimir;		
		
		$('txtRangoInicio').value="";
		$('txtRangoInicio').disabled=false;
		
		$('txtRangoFin').value="";
		$('txtRangoFin').disabled=false;
		
		$('txtCliente').value="";
		$('txtCliente').disabled=false;
		
		$('hddCveCliente').value=0;
		$('hddCveCliente').disabled=false;
		
		$('txtFechaInicio').value="";
		$('txtFechaInicio').disabled=false;
		
		$('txtFechaFin').value="";
		$('txtFechaFin').disabled=false;
	}
}

function datosClientes1() 
{	
	var url2 = "opc=1&codigo="+$("txtCliente").value;
	$Ajax("scripts/datosClientes.php?operacion=4&cveCliente="+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function cveCliente(campos) 
{
	var campo = campos[0];
	$("hddCveCliente").value=campo.cveCliente;
	$("txtCliente").value=campo.razonSocial;
	existe();
}

function allGood()
{
	//Checar que se hayan metido las guias
	if($('txtRangoInicio').value=="")
	{
		alert("Debe ingresar el Rango Inicial");
		return false;
	}
	else if((isNaN($('txtRangoInicio').value)))
	{
		alert("El Rango Inicial debe ser numero.");
		return false;
	}
	
	if($('txtRangoFin').value=="")
	{
		alert("Debe ingresar el Rango Final");
		return false;	
	}
	else if((isNaN($('txtRangoFin').value)))
	{
		alert("El Rango Final debe ser numero.");
		return false;
	}
	
	if(parseInt($('txtRangoInicio').value)>parseInt($('txtRangoFin').value))
	{
		alert("El Rango Final no puede ser menor al Rango Inicial.");
		return false;
	}
	
	//Fechas
	if($("txtFechaInicio").value!="")
	{
		if($("txtFechaFin").value=="")
		{
			alert("Debe ingresar las dos fechas.");
			return false;
		}
	}
	
	if($("txtFechaInicio").value!="" && $("txtFechaFin").value!="")
	{
		//Checar que las fechas esten correctas
		var expresion = /^\s*(\d{2,2})\/(\d{2,2})\/(\d{4,4})\s*$/;	
	
		if((!($("txtFechaInicio").value.match(expresion))))
		{$("txtFechaInicio").addClassName("invalid"); alert("El formato de la fecha inicial es incorrecto (dd/mm/yyyy)."); return false;} 
		else $("txtFechaInicio").removeClassName("invalid"); 
		
		if((!($("txtFechaFin").value.match(expresion))))
		{$("txtFechaFin").addClassName("invalid"); alert("El formato de la fecha final es incorrecto (dd/mm/yyyy)."); return false;} 
		else $("txtFechaFin").removeClassName("invalid"); 	
		
		//Checar que la fecha de desde no sea superior a la fecha de hasta 
		valores=$('txtFechaInicio').value.split("/");
		desde = new Date(valores[2],valores[1],valores[0]).getTime();
		valores=$('txtFechaFin').value.split("/");
		hasta = new Date(valores[2],valores[1],valores[0]).getTime();
		if(desde>hasta)
		{
			alert("La fecha de Final es mayor a la Inicial.");
			return false;
		}
	}
	
	return true;
}