window.onload = inicia;

function inicia()
{
	document.getElementById("imgInfo").style.cursor='pointer';
	document.getElementById("imgInfo").style.visibility='hidden';
	document.getElementById("txtGuia").focus();
	
	//pendiente$('btnGuardarContenido').style.visibility="hidden";
			
	edo_guia(0,'');
	$("act_des").style.visibility="hidden";
	$("act_des").style.display="none";
	
	$("btnModificar").disabled=true;
	$("btnReporte").disabled=true;	
	$("btnGuardar").disabled=true;
	$("btnCancelar").disabled=true;
	$("btnLimpiar").disabled=true;
	$("btnImprimir").disabled=true;
	
	
	$Ajax("scripts/Sucursales.php", {onfinish: cargaSucursal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$Ajax("scripts/lineaAerea.php", {onfinish: cargarLineas, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
   	$Ajax("scripts/envios.php", {onfinish: cargarEnvios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	$("autoContenido").className = "autocomplete";
	new Ajax.Autocompleter("txtContenido", "autoContenido", "scripts/catalogoContenido.php", {paramName: "caracteres", afterUpdateElement:contenidos});	
	
	$("txtNombredo").onchange=llenaMunicipios;
	$("txtEstadoD").onchange=llenaMunicipiosD;
	
	if(($("txtGuia").value!="Guia House")&&($("txtGuia").value!=""))
	{
	  document.getElementById("imgInfo").style.visibility='visible'; 
	   $("txtGuia").disabled=true;
	   $("btnGuardar").disabled=true;
	   $("btnCancelar").disabled=false;
	   $("btnModificar").disabled=false;
	   $("btnReporte").disabled=false;
	   $("btnImprimir").disabled=false;
           

	var guia = $("txtGuia").value;
        $Ajax("scripts/datosDocGuias.php?guia="+guia, {onfinish: function (datos)
							{
								document.getElementById("tdNoFac").innerHTML=datos[0].facturas;
								document.getElementById("tdNoFolio").innerHTML=datos[0].acuses;

								$Ajax("scripts/datosGuias.php?cveguia="+guia, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
							}
							, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	
	   $("btnModificar").onclick=actualizar;
	   $("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	}

	fecha=new Date();
	dia=fecha.getDate();
	mes=fecha.getMonth()+1;
	anyo=fecha.getFullYear();
	if(dia<10) dia="0"+dia;
	if(mes<10) mes="0"+mes;
	fecha_hoy=dia+"/"+mes+"/"+anyo;
	$("txtRecepcioncye").value=fecha_hoy;


	//Inicializando autocomplete
	$("autoGuia").className = "autocomplete";
	new Ajax.Autocompleter("txtGuia", "autoGuia", "scripts/catalogoGuias.php?operacion=5", {paramName: "caracteres",afterUpdateElement:existe});
	$("txtGuia").onchange=existe;
	
	$("autoCliente").className = "autocomplete";
	new Ajax.Autocompleter("txtRazonSocial", "autoCliente", "scripts/catalogoDirecciones.php?operacion=3", {paramName: "caracteres", afterUpdateElement:clientes});
	$("txtRazonSocial").onchange=clientes2;
	
	
	//Primero habrá que elegir la estación para poder iniciar la búsqueda de los consignatarios
	bloquearDes();
	

	//Cargando listas
		
	$("autoPostal").className = "autocomplete";
	new Ajax.Autocompleter("txtCodigoPD", "autoPostal", "scripts/catalogoCP.php?operacion=1", {paramName: "caracteres", afterUpdateElement:datosdes});
		
	
	$("btnCancelar").onclick=function(){	
			location = "guia.php";
	};


	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("btnGuardar").style.visibility="hidden";
		$("btnModificar").style.visibility="hidden";
		
		$("btnGuardar").style.display="none";
		$("btnModificar").style.display="none";		
	}
}

function irReporte()
{
	location="reporteIncidentes.php?guia="+$("txtGuia").value;
}

function mostrarInfo()
{
	document.getElementById("divInfoGuia").style.visibility='visible';
}

function quitarInfo()
{
	document.getElementById("divInfoGuia").style.visibility='hidden';
}

function bloquearDes(opc)
{
	if($("txtGuia").disabled)
		val=false;
	else
		val=true;
	inputs=$$("#derecho2 input[type=text]:not([id=txtCodigoPD]),#derecho2 select:not([id=slcSucursal])");
	for(i=0;i<inputs.length;i++)
	{	
		inputs[i].disabled=val; 
	}
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
	$('txtNombreDes').disabled=false;
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

function cargarLineas(aeros){

	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcLineaA").options.length = 0;
	//empieza la carga de la lista
				
	for (var i=0; i<aeros.length; i++){
		var aero = aeros[i];
		var opcion = new Option(aero.desc, aero.id);
		
		try {
			$("slcLineaA").options[$("slcLineaA").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}

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
	$("status").innerHTML="";
	if ($("txtGuia").value!="" ){
		//Separamos la clave de la guía del Nombre del Remitente
		valor_guia=$("txtGuia").value.split(" - ",1);
		$("txtGuia").value=valor_guia;
		var url = "scripts/existe.php?keys=1&table=cguias&field1=cveGuia&f1value="+$("txtGuia").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}
}
function existeDestinatario() {
	
	$("status").innerHTML="";
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
		$("btnLimpiar").disabled=false;
		$("btnLimpiar").onclick=nuevoCon;
	}else
	{ $('txthNombreDes').value=0;	}
				
}

function llenaDestinatario(datos)
{
	dato=datos[0];	
	$('txthNombreDes').value=dato.cve;
	$('slcSucursal').value=dato.estacion;
	$('txtNombreDes').value=dato.nombre;
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

function clientes(){
	if ($("txtRazonSocial").value!="Numero Cliente"){
			valores=$("txtRazonSocial").value.split(" - ");
			cveCliente=valores[0];
			cveDireccion=valores[3];
			$("txtRazonSocial").value=cveCliente+"-"+cveDireccion;
			var url = "scripts/existe.php?keys=10&f1value="+cveCliente+"&f2value="+cveDireccion;
			$Ajax(url, {onfinish: traerDirecciones, tipoRespuesta: $tipo.JSON});
	}
}

function contenidos()
{
	$("ckbEditar").checked=false;
	valores=$("txtContenido").value.split(" - ");
	$("txtContenido").value=valores[1];
	$("txthContenido").value=valores[0];
	$("rdbNuevo").checked=false;
	$("rdbOtro").checked=false;
	
	llenaDiceContener();
}

function chkControles(valorContenido)
{
	$("txthContenido").value=valorContenido;
	$("txtContenido").value="";
	llenaDiceContener();
}

function clientes2(){
		if ($("txtRazonSocial").value!="Numero Cliente"){
				var url = "scripts/existe.php?keys=6&f1value="+$("txtRazonSocial").value;
				$Ajax(url, {onfinish: traerDirecciones, tipoRespuesta: $tipo.JSON});
		}
}
//esta funcion recibe el valor de la función anterior y lo evalúa	
function next_existe(ex){

	
	var exx=ex[0];
	var exists = exx.existe;
	
	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0){

		//se piden los datos
		 location = "guia.php?cveGuia=" + $("txtGuia").value ;
		 $("act_des").style.visibility="visible";
		 $("act_des").style.display="table-row";
	
	}
	else{	//si la funcion devolvio cero, no existe el registro
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		//el boton borrar no es útil aqui, por lo tanto lo ocultamos
		$("btnModificar").disabled=true;
		$("btnReporte").disabled=true;		
		$("btnGuardar").disabled=false;
		$("btnCancelar").disabled=false;
		//agregamos un manejador al boton continuar para que apunte a la funcion
		//que inserta un registro nuevo
		$("btnGuardar").onclick=insertar;
		$("btnImprimir").disabled=true;
		//imprimimos un aviso de que se trata de un registro nuevo
		$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";			
		//Si no existe es por que la Guía es nueva por lo tanto el estado será Carga Documentandose
		$("slcStatus").value = "Carga Documentandose";
		//checar_estado();
		$("slcStatus").disabled = true;					
	
	}
}
		
	

function insertar(){
	
	if(allgood()){
	
		var firmat;
		var sellot;
		var respaldot;
		var Reexpedicion;
		if($("chkSello").checked)
		{sellot=1;}else{sellot=0;}
		if($("chkRespaldo").checked)
		{respaldot=1;}else{respaldot=0;}
		if($("chkFirma").checked)
		{firmat=1;}else{firmat=0;}
		if($("chkReexpedicion").checked)
		{Reexpedicion=1;}else{Reexpedicion=0;}
		
		var regExp=/%/gi;
		var cadena=$("txaObservaciones").value;
		var observaciones = cadena.replace(regExp,'%25');
		
		var regExp=/%/gi;
		var cadena=$("txaObservacionesC").value;
		var observacionesC = cadena.replace(regExp,'%25');

		var fechaVuelo = $("txtFechaVuelo").value.substring(6,10)+"/"+$("txtFechaVuelo").value.substring(3,5)+"/"+$("txtFechaVuelo").value.substring(0,2);
		var fechatxtRecepcioncye = $("txtRecepcioncye").value.substring(6,10)+"/"+$("txtRecepcioncye").value.substring(3,5)+"/"+$("txtRecepcioncye").value.substring(0,2);
		var fechaVigencia = $("txtVigencia").value.substring(6,10)+"/"+$("txtVigencia").value.substring(3,5)+"/"+$("txtVigencia").value.substring(0,2);
		var fechaEntrega = $("txtFechaEntrega").value.substring(6,10)+"/"+$("txtFechaEntrega").value.substring(3,5)+"/"+$("txtFechaEntrega").value.substring(0,2);
		var fechaAcuse = $("txtFechaA").value.substring(6,10)+"/"+$("txtFechaA").value.substring(3,5)+"/"+$("txtFechaA").value.substring(0,2);
		 
		 
		
		var valores = "cveGuia="		+$("txtGuia").value		+"&observacionesC="+observacionesC+"&txtDContener="+$("txtDContener").value+"&cveLineaArea="		+$("slcLineaA").value		+"&noVuelo="	+$("txtNumeroVuelo").value;
		valores = valores + "&guiaArea="	+$("txtGuiaAerea").value		+"&fechaVuelo="	+fechaVuelo			+"&recepcionCYE="		+fechatxtRecepcioncye;
		valores = valores + "&nombreRemitente="		+$("txtRemitente").value		+"&calleRemitente="		+$("txtCalleR").value		+"&telefonoRemitente="		+$("txtTelefonoR").value;
		valores = valores + "&rfcRemitente="			+$("txtRfcR").value		+"&destinatario="	+$("txtNombreDes").value		+"&estadoRemitente="		+$("txtNombredo").value		+"&coloniaRemitente="		+$("txtColR").value		+"&municipioRemitente="		+$("txtMunR").value		+"&codigoPR="		+$("txtCodigoPr").value;
		valores = valores 	+"&piezas="		+$("txtPiezas").value	+ "&kg="			+$("txtKg").value		+"&volumen="		+$("txtVol").value		+"&validezDias="	+fechaVigencia;
		valores = valores + "&sucursalDestino="		+$("slcSucursal").value	+	"&calleD="		+$("txtCalleD").value;
		valores=valores + "&ColoniaD="		+$("txtColoniaD").value		+"&MunicipioD="	+	$("txtMunicipioD").value +"&Sello="	+ sellot +"&Firma="	+ firmat +"&Respaldo="	+respaldot+"&Reexpedicion="	+Reexpedicion;
		valores = valores + "&status="		+$("slcStatus").value		+"&fechaEntrega="	+fechaEntrega			+"&recibio="	+$("txtRecibio").value;
		valores = valores + "&codigoPD="		+$("txtCodigoPD").value		+"&EstadoD="		+$("txtEstadoD").value		+"&TelefonoD="		+$("txtTelefonoD").value	+"&llegadaacuse="	+fechaAcuse		+"&observaciones="+observaciones;
		valores=valores + "&TipoEnvio="		+$("slcTipoe").value  +  "&Recoleccion="		+$("slcRecoleccion").value +  "&valorD="		+$("txtValord").value +  "&facturas="		+$("txaFacturas").value +  "&vales="		+$("txtVales").value +  "&recibos="		+$("txaEntregas").value+ "&cveFacturas="		+$("hdncveFacturas").value+  "&hdncveEntregas="		+$("hdncveEntregas").value+  "&txtCodigoC="		+$("txtCodigoC").value+"&cveDireccion="		+$("hdncveDireccion").value+"&txthNombreDesP="		+$("txthNombreDesP").value+"&txthNombreDes="		+$("txthNombreDes").value+"&txthAlta="		+$("txthAlta").value+"&txtCtoGuia="+$("txtCtoGuiaAerea").value;
			

		var usuario="&empresa="+$("hdnEmpresaS").value+"&usuario="+$("hdnUsuario").value;
		valores=valores +usuario;

		$Ajax("scripts/guardarGuia.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		$("contenedor").className="";
		//$("form2").reset();
	}
	
}
		
function actualizar(){

	if(allgood()){
	
		if($("chkActivado").checked)
		estatus=1;
		else estatus=0;
		
		var firmat;
		var sellot;
		var respaldot;
		var Reexpedicion;
		if($("chkSello").checked)
		{sellot=1;}else{sellot=0;}
		if($("chkRespaldo").checked)
		{respaldot=1;}else{respaldot=0;}
		if($("chkFirma").checked)
		{firmat=1;}else{firmat=0;}
		if($("chkReexpedicion").checked)
		{Reexpedicion=1;}else{Reexpedicion=0;}
		
		var regExp=/%/gi;
		var cadena=$("txaObservaciones").value;
		var observaciones = cadena.replace(regExp,'%25');

		var regExp=/#/gi;
		var cadena=observaciones;
		var observaciones = cadena.replace(regExp,'%23');
		
		var regExp=/%/gi;
		var cadena=$("txaObservacionesC").value;
		var observacionesC = cadena.replace(regExp,'%25');
		
		var regExp=/#/gi;
		var cadena=observacionesC;
		var observacionesC = cadena.replace(regExp,'%23');

		var fechaVuelo = $("txtFechaVuelo").value.substring(6,10)+"/"+$("txtFechaVuelo").value.substring(3,5)+"/"+$("txtFechaVuelo").value.substring(0,2);
		var fechatxtRecepcioncye = $("txtRecepcioncye").value.substring(6,10)+"/"+$("txtRecepcioncye").value.substring(3,5)+"/"+$("txtRecepcioncye").value.substring(0,2);
		var fechaVigencia = $("txtVigencia").value.substring(6,10)+"/"+$("txtVigencia").value.substring(3,5)+"/"+$("txtVigencia").value.substring(0,2);
		var fechaEntrega = $("txtFechaEntrega").value.substring(6,10)+"/"+$("txtFechaEntrega").value.substring(3,5)+"/"+$("txtFechaEntrega").value.substring(0,2);
		var fechaAcuse = $("txtFechaA").value.substring(6,10)+"/"+$("txtFechaA").value.substring(3,5)+"/"+$("txtFechaA").value.substring(0,2);
		
		var valores = "cveEstadoGuia="		+$("txthStatus").value+"&cveGuia="		+$("txtGuia").value		+"&cveLineaArea="		+$("slcLineaA").value		+"&noVuelo="	+$("txtNumeroVuelo").value;
		valores = valores + "&guiaArea="	+$("txtGuiaAerea").value		+"&fechaVuelo="	+fechaVuelo			+"&recepcionCYE="		+fechatxtRecepcioncye;
		valores = valores + "&nombreRemitente="		+$("txtRemitente").value		+"&calleRemitente="		+$("txtCalleR").value		+"&telefonoRemitente="		+$("txtTelefonoR").value;
		valores = valores + "&rfcRemitente="			+$("txtRfcR").value		+"&destinatario="	+$("txtNombreDes").value		+"&estadoRemitente="		+$("txtNombredo").value		+"&coloniaRemitente="		+$("txtColR").value		+"&municipioRemitente="		+$("txtMunR").value		+"&codigoPR="		+$("txtCodigoPr").value;
		valores = valores 	+"&piezas="		+$("txtPiezas").value	+ "&kg="			+$("txtKg").value		+"&volumen="		+$("txtVol").value		+"&validezDias="	+fechaVigencia;
		valores = valores + "&sucursalDestino="		+$("slcSucursal").value	+	"&calleD="		+$("txtCalleD").value;
		valores=valores + "&ColoniaD="		+$("txtColoniaD").value		+"&MunicipioD="	+	$("txtMunicipioD").value	+"&Sello="	+ sellot +"&Firma="	+ firmat +"&Respaldo="	+respaldot+"&Reexpedicion="	+ Reexpedicion 
		valores = valores + "&status="		+$("slcStatus").value		+"&fechaEntrega="	+fechaEntrega			+"&recibio="	+$("txtRecibio").value;
		valores = valores + "&codigoPD="		+$("txtCodigoPD").value		+"&EstadoD="		+$("txtEstadoD").value		+"&TelefonoD="		+$("txtTelefonoD").value	+"&llegadaacuse="	+fechaAcuse		+"&observaciones="		+observaciones;
		valores=valores + "&TipoEnvio="		+$("slcTipoe").value  +  "&Recoleccion="		+$("slcRecoleccion").value +  "&valorD="		+$("txtValord").value+  "&facturas="		+$("txaFacturas").value +  "&vales="		+$("txtVales").value +  "&recibos="		+$("txaEntregas").value +  "&cveFacturas="		+$("hdncveFacturas").value+  "&hdncveEntregas="		+$("hdncveEntregas").value+  "&txtCodigoC="		+$("txtCodigoC").value+"&cveDireccion="		+$("hdncveDireccion").value+"&estatus="		+estatus+"&txthNombreDes="		+$("txthNombreDes").value+"&txthNombreDesP="		+$("txthNombreDesP").value+"&txthAlta="		+$("txthAlta").value+"&txtCtoGuia="+$("txtCtoGuiaAerea").value;
		

		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value+"&cveVale="		+$("hdnVales").value;	
		
		valores=valores +usuario+"&observacionesC="+observacionesC+"&txtDContener="+$("txtDContener").value;
		

		$Ajax("scripts/actualizarGuias.php", {metodo: $metodo.POST, onfinish: finModificar, parametros: valores, avisoCargando:"loading"});
		
		$("form2").reset();
		$("contenedor").className="";
	}

}
		
function borrar(){
	
	if (confirm("Confirme que desea borrar")){
	
		var hid ="Campo1=" + $("txtGuia").value;
		hid+="&nombreCampo1=cveGuia";
		hid+="&tabla=cguias" ;
				
		$Ajax("scripts/borrarDatos.php", {metodo: $metodo.POST, onfinish: fin, parametros: hid, avisoCargando:"loading"});
		
		$("form2").reset();
	
	}
		
}
function traerDirecciones(ex){
	var exx=ex[0];
	var exists = exx.existe;
	var respuesta= "scripts/municipios.php?municipio=" +  exx.estadoRemitente;
	$Ajax(respuesta, {tipoRespuesta: $tipo.JSON, avisoCargando:"loading", onfinish: cargaMunicipios});
	pausecomp(200);
	var urlo="operacion=2&datos="+$("txtRazonSocial").value 
	$Ajax("scripts/datosClientes.php?"+urlo, {onfinish: llenaDirecciones, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});	

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

function nuevoCon()
{
	//Esta función limpiará todos los valores del consignatario (destinatario), para que sea posible dar de alta este
	elementos=$$(".flddestinatario input[type=text],.flddestinatario select");
    	for(i=0;i<elementos.length;i++)
	{ 	
		id=elementos[i].id; 
		$(id).value="";
	}
	$("txtMunicipioD").options.length = 0;
	//Para poder dar de Alta
	elementos=$$(".flddestinatario input[type=hidden]");
    	for(i=0;i<elementos.length;i++)
	{ 	
		id=elementos[i].id; 
		$(id).value=0;
	}
	$("txtEstadoD").value=1;
	
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
	//Datos dados por el cliente
	$("txaObservacionesC").value=campo.obsCliente;
	$("txtDContener").value=campo.obsDContener;
	
	//Asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
	for(var idx = 0; idx < $("slcLineaA").options.length; idx ++){
		if($("slcLineaA").options[idx].value == campo.cveLineaArea){
			$("slcLineaA").selectedIndex = idx;
			$("slcLineaA").options[idx].selected=true;		
		}	
	}
	
	$("act_des").style.visibility="visible";
	$("act_des").style.	display="table-row";
	//Estado
	if(campo.estatus==1)
	{
		$("lblActivado").value="Activado";
		$("chkActivado").checked=true;
	}
	else if(campo.estatus==0)
	{
		$("lblActivado").value="Desactivado";
		$("chkActivado").checked=false;
	}
	
	$("txtCtoGuiaAerea").value = campo.ctoGuia;	
	$("txtValord").value = campo.Valor;
	$("txtGuiaAerea").value = campo.guiaArea;
	$("txtNumeroVuelo").value = campo.noVuelo;
	$("txtFechaVuelo").value =formatoFecha(campo.fechaVuelo.substring(8,10)+"/"+campo.fechaVuelo.substring(5,7)+"/"+campo.fechaVuelo.substring(0,4));
	
	$("txtRecepcioncye").value =formatoFecha(campo.recepcionCYE.substring(8,10)+"/"+campo.recepcionCYE.substring(5,7)+"/"+campo.recepcionCYE.substring(0,4));
	$("txtRemitente").value = campo.nombreRemitente;
	$("txtCalleR").value = campo.calleRemitente;
	$("txtTelefonoR").value = campo.telefonoRemitente;
	$("txtRfcR").value = campo.rfcRemitente;
	
	$("txtColR").value = campo.coloniaRemitente;
	$("txtCodigoPr").value = campo.codigoPR;
	$("txtPiezas").value = campo.piezas;
	$("txtKg").value = campo.kg;
	$("txtVol").value = campo.volumen;
	$("txtRazonSocial").value = campo.cliente;
	$("txtVigencia").value = formatoFecha(campo.validezDias.substring(8,10)+"/"+campo.validezDias.substring(5,7)+"/"+campo.validezDias.substring(0,4));
	
	$("slcStatus").value =campo.status;
	$("txthStatus").value =campo.status;		
	
	for(var idx1 = 0; idx1 < $("slcSucursal").options.length; idx1 ++){
		if($("slcSucursal").options[idx1].value == campo.sucursalDestino){
			$("slcSucursal").selectedIndex = idx1;
			$("slcSucursal").options[idx1].selected=true;
		}
	}	

	$("txtCalleD"). value = campo.calleDestinatario;
	$("txtCodigoPD").value = campo.codigoPostaldestinatario;
	$("txtColoniaD"). value = campo.coloniaDestinatario;
	//alert(campo.estadoDestinatario);		
	
	$("txtTelefonoD").value = campo.telefonoD;	
	$("txtCodigoC").value = campo.cliente;

		
	var respuesta= "scripts/catalogoCP.php?operacion=3&municipio=" +campo.municipioDestinatario+"&estado="+campo.estadoDestinatario;
	$Ajax(respuesta, {onfinish: cargaMunicipiosDG, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	$("txtFechaEntrega").focus();
	
	if(campo.consignatario==""){
		$("txthNombreDes").value=0;	
		$("btnLimpiar").disabled=true;
	}
	else{
		$("txthNombreDes").value=campo.consignatario;	
		$("btnLimpiar").disabled=false;
		$("btnLimpiar").onclick=nuevoCon;
	}
		
	$("txtNombreDes").value = campo.nombreD;
	$("txthNombreDesP").value = campo.nombreD;
		
	$("txtRecibio").value=campo.recibio
	
	for(var idx = 0; idx < $("slcTipoe").options.length; idx ++){
		if($("slcTipoe").options[idx].value == campo.tipoEnvio){
			$("slcTipoe").selectedIndex = idx;
			$("slcTipoe").options[idx].selected=true;		
		}
	}
	
	for(var idx = 0; idx < $("slcRecoleccion").options.length; idx ++){
		if($("slcRecoleccion").options[idx].value == campo.recoleccion){
			$("slcRecoleccion").selectedIndex = idx;
			$("slcRecoleccion").options[idx].selected=true;
		}
	}
	
	$("txtFechaA").value =formatoFecha(campo.llegadaacuse.substring(8,10)+"/"+campo.llegadaacuse.substring(5,7)+"/"+campo.llegadaacuse.substring(0,4));
	$("txtFechaEntrega").value =formatoFecha(campo.fechaEntrega.substring(8,10)+"/"+campo.fechaEntrega.substring(5,7)+"/"+campo.fechaEntrega.substring(0,4));
	
	var respuesta= "scripts/catalogoCP.php?operacion=3&municipio=" +campo.municipioRemitente+"&estado="+campo.estadoRemitente;
	$Ajax(respuesta, {onfinish: cargaMunicipiosG, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	alert("Cargando Guia ...");
	
	//$("txaObservaciones").value = campo.observaciones;
	
	if(campo.sello==1)
	{$("chkSello").checked=true;}else{$("chkSello").checked=false;}
	if(campo.respaldo==1)
	{$("chkRespaldo").checked=true;}else{$("chkRespaldo").checked=false;}
	if(campo.firma==1)
	{$("chkFirma").checked=true;}else{$("chkFirma").checked=false;}
	if(campo.reexpedicion==1)
	{$("chkReexpedicion").checked=true;}else{$("chkReexpedicion").checked=false;}
	
	
	$("txtVales").value=campo.vales;
	$("hdnVales").value=campo.cveVale;
	
	$("hdncveDireccion").value=campo.cveDireccion;
	
	if(campo.facturada==1)
	{
		$("btnModificar").disabled=true;	
		$("status").innerHTML="<label class='message' for='element_3'>No se puede actualizar (guía facturada)</label>";
	}else{
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	}
	//Hacemos pausa
	$("txtEstadoD").value=campo.estadoDestinatario;	
	$("txtNombredo").value=campo.estadoRemitente;
	

}

function edo_guia(opc,estado)
{

     //Si la opción es 0 cargará todos los estados para la guía
	 var selec_edo = $("slcStatus");  
     selec_edo.options.length = 0;  
	 selec_edo.options.add(new Option("Seleccione un Status",""));
	 if(opc==0)
	 {
		 selec_edo.options.add(new Option("Carga Documentandose","Carga Documentandose"));
		 selec_edo.options.add(new Option("Enviada A destino","Enviada A destino"));
		 selec_edo.options.add(new Option("En proceso de Entrega","En proceso de Entrega"));
		 selec_edo.options.add(new Option("Sin localizar destinatario","Sin localizar destinatario"));
		 selec_edo.options.add(new Option("Dirrecion erronea","Dirrecion erronea"));
		 selec_edo.options.add(new Option("Recabando Sello","Recabando Sello"));
		 selec_edo.options.add(new Option("Faltan documentos","Faltan documentos"));
		 selec_edo.options.add(new Option("Con cita para entrega","Con cita para entrega"));
		 selec_edo.options.add(new Option("Entrega Rechazada","Entrega Rechazada"));
		 selec_edo.options.add(new Option("Cancelada","Cancelada"));
		 selec_edo.options.add(new Option("Entregada","Entregada"));
		 selec_edo.options.add(new Option("Concluida","Concluida"));
	 }else
	 {
		if((estado=="Carga Documentandose")||(estado=="Enviada A destino"))	
		{
			opciones=new Array('Carga Documentandose','Enviada A destino','En proceso de Entrega','Sin localizar destinatario','Dirrecion erronea','Recabando Sello','Faltan documentos','Con cita para entrega','Entrega Rechazada','Cancelada','Entregada','Concluida');
		}
		else if((estado=="Entregada")||(estado=="Concluida")||(estado=="Cancelada")||(estado=="Entrega Rechazada"))	
		{
			opciones=new Array('Con cita para entrega','Entrega Rechazada','Cancelada','Entregada','Concluida');
		}
		else{
			opciones=new Array('Enviada A destino','En proceso de Entrega','Sin localizar destinatario','Dirrecion erronea','Recabando Sello','Faltan documentos','Con cita para entrega','Entrega Rechazada','Cancelada','Entregada','Concluida');

		}
		for(i=0;i<opciones.length;i++)
		{
			selec_edo.options.add(new Option(opciones[i],opciones[i]));
		}
	 }
         
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
	$("txtRazonSocial").disabled=false;
	$("slcStatus").disabled=false;
	$("lblActivado").disabled=false;
	$("chkActivado").disabled=false;
}

function checar_estado()
{
	
	//Si la entrega esta En proceso de Entrega  o Cancelada no se podrá modificar nada
	var estado=$("slcStatus").value;
	if(estado=="Carga Documentandose")
	{
		fieldsets=new Array('fldobservaciones','fldrecepcion','fldaereo','fldenvio');
	}
	else if((estado=="Enviada A destino"))
	{
		fieldsets=new Array('fldobservaciones','fldrecepcion','fldaereo','fldremitente','flddestinatario','fldenvio');
	}
	else if((estado=="Sin localizar destinatario")||(estado=="Dirrecion erronea")||(estado=="Recabando Sello")||(estado=="Faltan documentos"))
	{
		fieldsets=new Array('fldobservaciones','fldaereo','fldremitente','flddestinatario','fldenvio');
	}
	else if(estado=="Entrega Rechazada")
	{
		fieldsets=new Array('fldobservaciones','fldremitente','flddestinatario','fldenvio');
	}
	else if(estado=="Con cita para entrega")
	{
		fieldsets=new Array('fldobservaciones','fldentrega');
	}
	else if(estado=="Entregada")
	{
		fieldsets=new Array('fldobservaciones','fldentrega');
	}
	else if(estado=="Concluida")
	{
		fieldsets=new Array('fldobservaciones','fldacuse','fldfactura','fldentregas','fldvale');
	}else
	{
		fieldsets=new Array('fldobservaciones');
	}
	//Bloqueara TODO primero y luego solo desbloquea lo q debe
	bloquea_todo();
	for(i=0;i<fieldsets.length;i++)
		{
			nombre_fieldset=fieldsets[i];
			inputs=$$('fieldset.'+nombre_fieldset+' input');
			selects=$$('fieldset.'+nombre_fieldset+' select');
			textarea=$$('fieldset.'+nombre_fieldset+' textarea');
			img=$$('fieldset.'+nombre_fieldset+' img');
			
			for(j=0;j<inputs.length;j++){ inputs[j].disabled=false;}
			for(k=0;k<selects.length;k++){ selects[k].disabled=false;}
			for(l=0;l<textarea.length;l++){ textarea[l].disabled=false;}
			for(m=0;m<img.length;m++){ img[m].style.visibility="visible";}

		}
	
}

function fin(res){
	
	alert(res);

	var preg = "\u00BFDesea Imprimir la guia?";
	
	if(confirm(preg))
	{
		imprimirGuia();
		$("form2").reset();
		fecha=new Date();
		dia=fecha.getDate();
		mes=fecha.getMonth()+1;
		anyo=fecha.getFullYear();
		if(dia<10) dia="0"+dia;
		if(mes<10) mes="0"+mes;
		fecha_hoy=dia+"/"+mes+"/"+anyo;
		$("txtRecepcioncye").value=fecha_hoy;		
	}
	else
	{
		location = "guia.php";
	}
}

function finModificar(res)
{
	alert(res);
	location = "guia.php";
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
		
	//Se verificará formato de fecha y válidez , vuelo y vigencia


	if($("txtFechaVuelo").value!=""){
		if(!($("txtFechaVuelo").value.match(expresion)))
		{$("txtFechaVuelo").addClassName("invalid"); alert("El formato de la fecha de vuelo es incorrecto (dd/mm/yyyy)."); return false;} 
		else{$("txtFechaVuelo").removeClassName("invalid");}
	}
	
	if($("txtVigencia").value!=""){
		if(!($("txtVigencia").value.match(expresion)))
		{$("txtVigencia").addClassName("invalid"); alert("El formato de la fecha de vigencia es incorrecto (dd/mm/yyyy)."); return false;} 
		else{$("txtVigencia").removeClassName("invalid");}
	}
	
	if($("txtFechaEntrega").value!=""){
		if(!($("txtFechaEntrega").value.match(expresion)))
		{$("txtFechaEntrega").addClassName("invalid"); alert("El formato de la fecha de entrega es incorrecto (dd/mm/yyyy)."); return false;} 
		else{$("txtFechaEntrega").removeClassName("invalid");}
		if($('txtRecepcioncye').value!="")
		{
			//Checar que la fecha de entrega no sea superior a la fecha de vigencia (sólo se preguntará)
			valores=$('txtRecepcioncye').value.split("/");
			recep = new Date(valores[2],(valores[1]-1),valores[0]).getTime();
			valores=$('txtFechaEntrega').value.split("/");
			entrega = new Date(valores[2],(valores[1]-1),valores[0]).getTime();
			if(entrega<recep)
			{
				if(!confirm("La fecha de recepci\u00F3n es mayor a la de entrega,\u00BFDesea continuar?"))
					return false;
					
			}			
		}
		if($('txtFechaVuelo').value!="")
		{
			//Checar que la fecha de vuelo no sea superior a la fecha de entrega (sólo se preguntará)
			valores=$('txtFechaVuelo').value.split("/");
			vuelo = new Date(valores[2],(valores[1]-1),valores[0]).getTime();
			valores=$('txtFechaEntrega').value.split("/");
			entrega = new Date(valores[2],(valores[1]-1),valores[0]).getTime();
			if(entrega<vuelo)
			{
				if(!confirm("La fecha de vuelo es mayor a la de entrega,\u00BFDesea continuar?"))
					return false;
					
			}			
		}		
		if($('txtVigencia').value!="")
		{
			//Checar que la fecha de entrega no sea superior a la fecha de vigencia (sólo se preguntará)
			valores=$('txtVigencia').value.split("/");
			vigencia = new Date(valores[2],(valores[1]-1),valores[0]).getTime();
			valores=$('txtFechaEntrega').value.split("/");
			entrega = new Date(valores[2],(valores[1]-1),valores[0]).getTime();
			if(entrega>vigencia)
			{
				if(!confirm("La fecha de entrega es mayor a la de vigencia,\u00BFDesea continuar?"))
					return false;
					
			}			
		}
       }

	if($("txtFechaA").value!=""){
		if(!($("txtFechaA").value.match(expresion)))
		{$("txtFechaA").addClassName("invalid"); alert("El formato de la fecha de acuse es incorrecto (dd/mm/yyyy)."); return false;} 
		else{$("txtFechaA").removeClassName("invalid");}
		//Checar que la fecha de entrega sea no sea superior a la fecha de acuse (sólo se preguntará)
		valores=$('txtFechaA').value.split("/");
		acuse = new Date(valores[2],(valores[1]-1),valores[0]).getTime();
		valores=$('txtFechaEntrega').value.split("/");
		entrega = new Date(valores[2],(valores[1]-1),valores[0]).getTime();
		if(entrega>acuse)
		{
			alert("La fecha de acuse es menor que la de entrega.");
			$("txtFechaA").addClassName("invalid");
			return false;				
		}	
	}
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
		elementos=$$("#form2 input[type=text],#form2 select,#form2 textarea");
		for(i=0;i<elementos.length;i++)
		{ 	
			id=elementos[i].id; 
			if((id!="txtGuia")&&(id!="txtRazonSocial")&&(id!="txtCodigoC")&&(id!="slcStatus")){
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
				if($("txtRazonSocial").value == "")  {$("txtRazonSocial").addClassName("invalid"); notGood ++;}   else{$("txtRazonSocial").removeClassName("invalid");}
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
			var texto = "Esta asignando el \""+$('txtGuia').value+"\" como n\u00FAmero de gu\u00EDa.\n\u00BFEl n\u00FAmero de gu\u00EDa es correcto?(No podr\u00E1 ser modificado posteriormente)";
			if(!confirm(texto)){ return false;}
		}
		return true;	
	}
	
}

function imprimirGuia()
{
	var impNum = "si";
	if($("rdbImprimirNo").checked)
	{
		impNum = "no";
	}
	
	var valores="txtGuia="+$('txtGuia').value+"&imprimirNum="+impNum+"&txtRangoInicio="+""+"&txtRangoFin="+""+"&hddCveCliente="+""+"&txtFechaInicio="+""+"&txtFechaFin="+"";
	
	var win = new Window({className: "mac_os_x", title: "Guias", top:30, left:300, width:700, height:400, url: 'scripts/guiapdfRI.php?'+valores , showEffectOptions: {duration:1.5}});
	win.show();
}

function validarTamanio(elemento,longitud)
{
	var v=elemento.value;
	if(v.length>longitud)
	{
		elemento.value=v.substring(0,longitud);
		alert('solo se pueden ingresar ' + longitud + ' caracteres');
	}
}

function cargaSelectTitulo()
{
	$Ajax("scripts/phpCargaContenido.php?operacion=11", {onfinish: function(data)
		{
			/****alert(data['option']);
			//$("slcDiceContener").innerHTML=data['option'];
			$('txtDContener').value = data['option'];
			$('trTitulo').value = '';
			$('txtDContener').value = '';
			$('ckbEditar').checked = false;
			$('divCheck').style.visibility='hidden';
			$('trTitulo').style.visibility='hidden';
			document.getElementById('txtDContener').readOnly=true;****/
			
			
			
			// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
			$("slcDiceContener").options.length = 0;
			//empieza la carga de la lista
			var opcion = new Option("Seleccione una opcion", "");
			$("slcDiceContener").options[$("slcDiceContener").options.length] = opcion;
			
			for (var i=0; i<data.length; i++){
				var airl = data[i];
				var opcion = new Option(airl.desc, airl.id);
				
				try
				{
					$("slcDiceContener").options[$("slcDiceContener").options.length]=opcion;
				}
				catch (e)
				{
					alert("Error interno");
				}
			}
			
			opcion = new Option("Nuevo Registro","N");
			$("slcDiceContener").options[$("slcDiceContener").options.length]=opcion;
			opcion = new Option("Otros", "O");
			$("slcDiceContener").options[$("slcDiceContener").options.length]=opcion;
			
			$('trTitulo').value = '';
			$('txtDContener').value = '';
			$('ckbEditar').checked = false;
			$('divCheck').style.visibility='hidden';
			$('trTitulo').style.visibility='hidden';
			document.getElementById('txtDContener').readOnly=true;
			
		}, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"
	});
}

function llenaDiceContener()
{
	var noContenido=$('txthContenido').value;
	
	$('btnGuarcarContenido').value = 'Guardar Nuevo';
	$('btnGuarcarContenido').onclick = guardarDiceContener;
	
	if(noContenido == 'N')
	{
		document.getElementById('txtDContener').readOnly=false;
		$('trTitulo').style.visibility='visible';
		$('divCheck').style.visibility='hidden';
		$('txtTitulo').value = '';
		$('txtDContener').value = '';
	}
	else if(noContenido == 'O')
	{
		document.getElementById('txtDContener').readOnly=false;
		$('trTitulo').style.visibility='hidden';
		$('divCheck').style.visibility='hidden';
		$('txtTitulo').value = '';
		$('txtDContener').value = '';
	}
	else
	{
		$('divCheck').style.visibility='visible';
		$('trTitulo').style.visibility='hidden';
		var url='scripts/phpCargaContenido.php?operacion=2&id='+noContenido;
		$Ajax(url, {onfinish: function(data)
			{
				document.getElementById('txtDContener').value = data[0];
				document.getElementById('txtDContener').readOnly=true;
			}, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"
		});
	}
}

function guardarDiceContener()
{
	var titulo = $('txtTitulo').value;
	var diceContener = $('txtDContener').value;
	var idUsuario = $('hddUsuario').value;
	//$Ajax('scripts/phpCargaContenido.php?operacion=3&nombre='+titulo+'&descripcion='+diceContener+'&usuario='+idUsuario, {onfinish: function(data)
	$Ajax('scripts/phpCargaContenido.php?operacion=3&nombre='+titulo+'&descripcion='+diceContener+'&usuario='+idUsuario, {onfinish: function(data)
		{
			if(data == '0')
			{
				alert('Registro ingresado con Exito.');
				
				document.getElementById("txtTitulo").value = "";
				document.getElementById("txtDContener").value = "";
				$('trTitulo').style.visibility='hidden';
				
				cargaSelectTitulo();
			}
			else
			{
				alert('Se ha generado un Error ('+data+')! ! !');
			}
		}
	});
}

function edicionDiceContener()
{
	if($("ckbEditar").checked)
	{
		$('trTitulo').style.visibility='visible';
		$Ajax('scripts/phpCargaContenido.php?operacion=2&id='+$('txthContenido').value+'&edit=mmm', {onfinish: function(data)
			{
				document.getElementById('txtTitulo').value = data[1];
				document.getElementById('txtDContener').value = data[0];
				document.getElementById('txtDContener').readOnly=false;
				
				$('btnGuarcarContenido').value = 'Modificar';
				$('btnGuarcarContenido').onclick=function()
				{
					$Ajax('scripts/phpCargaContenido.php?operacion=4&id='+$('txthContenido').value+'&nuevoTitulo='+$('txtTitulo').value+'&nuevaDescripcion='+$('txtDContener').value+'&usario='+$('hddUsuario').value, {onfinish: function(data)
						{
							if(data == '0')
							{
								alert('Cambio realizado con exito');
								cargaSelectTitulo();
							}
							else
							{
								alert('Se genero un Error: ('+data+')');
							}
						}
					});
				}
					
			}, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"
		});
	}
	else
	{
		$("ckbEditar").checked = false;
		//$('ckbEditar').style.visibility='hidden';
		$('trTitulo').style.visibility='hidden';
	}
}