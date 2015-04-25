window.onload = inicia;

function inicia() {
$("btnBorrar").disabled=true;
$("btnModificar").disabled=true;
$("btnGuardar").disabled=true;
$Ajax("scripts/Sucursales.php", {onfinish: cargaSucursal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		$Ajax("scripts/lineaAerea.php", {onfinish: cargarLineas, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
        $Ajax("scripts/envios.php", {onfinish: cargarEnvios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
	var respuesta= "scripts/estados.php?pais=156";
	$Ajax(respuesta, {onfinish: cargaEstados, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	var respuesta= "scripts/estados.php?pais=156";
	$Ajax(respuesta, {onfinish: cargaEstadosD, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$("txtNombredo").onchange=llenaMunicipios;
	$("txtEstadoD").onchange=llenaMunicipiosD;
	if($("txtGuia").value!="Guia House")
	{
	   $("txtGuia").disabled=true;
		$("btnbuscar").disabled=true;
	   $("btnGuardar").disabled=true;
			$("btnBorrar").disabled=false;
			$("btnModificar").disabled=false;
            
        
            var url2 = $("txtGuia").value;					
			$Ajax("scripts/datosGuias.php?cveguia="+url2, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		   
        
       	
				//cambiamos el manejador del boton actualizar para que apunte a la funcion
			//que actualiza el registro
			$("btnModificar").onclick=actualizar;
			//agregamos un manejado de evento al boton borrar para que llame a su funcion borrar
			
			$("btnBorrar").onclick=borrar;
			//Mostramos todos los campos
			//imprimimos un mensaje de actualizando
			$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";}
	$("btnbuscar").onclick=existe;//al hacer click se llama funcion para ver si el registro existe

//inicializando autocomplete
$("autoGuia").className = "autocomplete";
new Ajax.Autocompleter("txtGuia", "autoGuia", "scripts/catalogoGuias.php?operacion=5", {paramName: "caracteres"});
$("autoCliente").className = "autocomplete";
new Ajax.Autocompleter("txtRazonSocial", "autoCliente", "scripts/catalogoDirecciones.php?operacion=2", {paramName: "caracteres", afterUpdateElement:clientes});

//Cargando listas
	
    $("autoPostal").className = "autocomplete";
new Ajax.Autocompleter("txtCodigoPD", "autoPostal", "scripts/catalogoCP.php?operacion=1", {paramName: "caracteres", afterUpdateElement:datosdes});
$("btnCancelar").onclick=function(){
		
		location = "guia.php";
		
		};
    
}

function datosdes(){    
    $Ajax("scripts/catalogoCP.php?operacion=2&codigo="+$('txtCodigoPD').value, {onfinish: cargadatosdes, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
   
}
function cargadatosdes(datos){
    
    dato=datos[0];
    $('txtEstadoD').value=dato.cveEstado;
    var respuesta= "scripts/catalogoCP.php?operacion=3&municipio=" +dato.cveEstado+"&estado="+dato.cveMunicipio;
	$Ajax(respuesta, {onfinish: cargaMunicipiosDG, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
$Ajax("scripts/catalogoCP.php?operacion=4&estado="+dato.cveEstado, {onfinish: cargaSucursal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

    
}
function cargaEstados(estados){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("txtNombredo").options.length = 0;
//empieza la carga de la lista

	for (var i=0; i<estados.length; i++){
	var estado = estados[i];
	var opcion = new Option(estado.desc, estado.id);

		try {
		$("txtNombredo").options[$("txtNombredo").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}
 function cargarEnvios(envios){
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
function cargaEstadosD(estados){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("txtEstadoD").options.length = 0;
//empieza la carga de la lista

	for (var i=0; i<estados.length; i++){
	var estado = estados[i];
	var opcion = new Option(estado.desc, estado.id);

		try {
		$("txtEstadoD").options[$("txtEstadoD").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}
function llenaMunicipios(){
	
	var respuesta= "scripts/municipios.php?municipio=" + $("txtNombredo").value;
	$Ajax(respuesta, {onfinish: cargaMunicipios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	}
	function cargaMunicipios(airls){
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
$("txtNombredo").value = airl.estado;	
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
$("txtEstadoD").value = airl.estado;	
alert("Cargando Guia ...");
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
			
		var url = "scripts/existe.php?keys=1&table=cguias&field1=cveGuia&f1value=" + $("txtGuia").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
		}
		
		
	}
function clientes(){
if ($("txtRazonSocial").value!="Numero Cliente"){
		var url = "scripts/existe.php?keys=6&f1value=" + $("txtRazonSocial").value;
		//alert(url);
		$Ajax(url, {onfinish: trerDirecciones, tipoRespuesta: $tipo.JSON});
		}
}
//esta funcion recibe el valor de la función anterior y lo evalúa	
	function next_existe(ex){
	
		//agregamos un manejador de evento al boton cancelar
		
		//deshabilitamos la llave primaria y el boton btnbuscar
		
		//extraemos el valor retornado por el servidor en un objeto json
		
		var exx=ex[0];
		var exists = exx.existe;
        
		//si el valor es mayor que cero, entonces el registro existe
		if (exists > 0){
			$("txtGuia").value==exx.cve;
			//se piden los datos
			 location = "guia.php?cveGuia=" +exx.cve;
			
		}
		else{	//si la funcion devolvio cero, no existe el registro
			
			//el boton borrar no es útil aqui, por lo tanto lo ocultamos
			$("btnBorrar").disabled=true;
			$("btnModificar").disabled=true;
			$("btnGuardar").disabled=false;
			//agregamos un manejador al boton continuar para que apunte a la funcion
			//que inserta un registro nuevo
			$("btnGuardar").onclick=insertar;
			//imprimimos un aviso de que se trata de un registro nuevo
			$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
			
			
			}
		}
		
	
		
		function insertar(){
			
		if(allgood()){
			var firmat;
			var sellot;
			var respaldot;
			if($("chkSello").checked)
			{sellot=1;}else{sellot=0;}
			if($("chkRespaldo").checked)
			{respaldot=1;}else{respaldot=0;}
			if($("chkFirma").checked)
			{firmat=1;}else{firmat=0;}
		

		var fechaVuelo = $("txtFechaVuelo").value.substring(6,10)+"/"+$("txtFechaVuelo").value.substring(3,5)+"/"+$("txtFechaVuelo").value.substring(0,2);
		var fechatxtRecepcioncye = $("txtRecepcioncye").value.substring(6,10)+"/"+$("txtRecepcioncye").value.substring(3,5)+"/"+$("txtRecepcioncye").value.substring(0,2);
		var fechaVigencia = $("txtVigencia").value.substring(6,10)+"/"+$("txtVigencia").value.substring(3,5)+"/"+$("txtVigencia").value.substring(0,2);
		var fechaEntrega = $("txtFechaEntrega").value.substring(6,10)+"/"+$("txtFechaEntrega").value.substring(3,5)+"/"+$("txtFechaEntrega").value.substring(0,2);
		var fechaAcuse = $("txtFechaA").value.substring(6,10)+"/"+$("txtFechaA").value.substring(3,5)+"/"+$("txtFechaA").value.substring(0,2);
        
		 var valores = "cveGuia="		+$("txtGuia").value		+"&cveLineaArea="		+$("slcLineaA").value		+"&noVuelo="	+$("txtNumeroVuelo").value;
	valores = valores + "&guiaArea="	+$("txtGuiaAerea").value		+"&fechaVuelo="	+fechaVuelo			+"&recepcionCYE="		+fechatxtRecepcioncye;
valores = valores + "&nombreRemitente="		+$("txtRemitente").value		+"&calleRemitente="		+$("txtCalleR").value		+"&telefonoRemitente="		+$("txtTelefonoR").value;
valores = valores + "&rfcRemitente="			+$("txtRfcR").value		+"&destinatario="	+$("txtNombreDes").value		+"&estadoRemitente="		+$("txtNombredo").value		+"&coloniaRemitente="		+$("txtColR").value		+"&municipioRemitente="		+$("txtMunR").value		+"&codigoPR="		+$("txtCodigoPr").value;
valores = valores 	+"&piezas="		+$("txtPiezas").value	+ "&kg="			+$("txtKg").value		+"&volumen="		+$("txtVol").value		+"&validezDias="	+fechaVigencia;
valores = valores + "&sucursalDestino="		+$("slcSucursal").value	+	"&calleD="		+$("txtCalleD").value;
valores=valores + "&ColoniaD="		+$("txtColoniaD").value		+"&MunicipioD="	+	$("txtMunicipioD").value +"&Sello="	+ sellot +"&Firma="	+ firmat +"&Respaldo="	+respaldot;
valores = valores + "&status="		+$("slcStatus").value		+"&fechaEntrega="	+fechaEntrega			+"&recibio="	+$("txtRecibio").value;
valores = valores + "&codigoPD="		+$("txtCodigoPD").value		+"&EstadoD="		+$("txtEstadoD").value		+"&TelefonoD="		+$("txtTelefonoD").value	+"&llegadaacuse="	+fechaAcuse		+"&observaciones="		+$("txaObservaciones").value;
valores=valores + "&TipoEnvio="		+$("slcTipoe").value  +  "&Recoleccion="		+$("slcRecoleccion").value +  "&valorD="		+$("txtValord").value +  "&facturas="		+$("txaFacturas").value +  "&vales="		+$("txtVales").value +  "&recibos="		+$("txaEntregas").value+ "&cveFacturas="		+$("hdncveFacturas").value+  "&hdncveEntregas="		+$("hdncveEntregas").value+  "&txtCodigoC="		+$("txtCodigoC").value+"&cveDireccion="		+$("hdncveDireccion").value;
var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
valores=valores +usuario;
		//var valores = $("form1").serialize();
		//alert(valores);
		$Ajax("scripts/guardarGuia.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		$("contenedor").className="";
		$("form2").reset();
		}
		
		}
		
		function actualizar(){
			
			if(allgood()){
			var firmat;
			var sellot;
			var respaldot;
			if($("chkSello").checked)
			{sellot=1;}else{sellot=0;}
			if($("chkRespaldo").checked)
			{respaldot=1;}else{respaldot=0;}
			if($("chkFirma").checked)
			{firmat=1;}else{firmat=0;}
		var fechaVuelo = $("txtFechaVuelo").value.substring(6,10)+"/"+$("txtFechaVuelo").value.substring(3,5)+"/"+$("txtFechaVuelo").value.substring(0,2);
		var fechatxtRecepcioncye = $("txtRecepcioncye").value.substring(6,10)+"/"+$("txtRecepcioncye").value.substring(3,5)+"/"+$("txtRecepcioncye").value.substring(0,2);
		var fechaVigencia = $("txtVigencia").value.substring(6,10)+"/"+$("txtVigencia").value.substring(3,5)+"/"+$("txtVigencia").value.substring(0,2);
		var fechaEntrega = $("txtFechaEntrega").value.substring(6,10)+"/"+$("txtFechaEntrega").value.substring(3,5)+"/"+$("txtFechaEntrega").value.substring(0,2);
		var fechaAcuse = $("txtFechaA").value.substring(6,10)+"/"+$("txtFechaA").value.substring(3,5)+"/"+$("txtFechaA").value.substring(0,2);
        
		 var valores = "cveGuia="		+$("txtGuia").value		+"&cveLineaArea="		+$("slcLineaA").value		+"&noVuelo="	+$("txtNumeroVuelo").value;
	valores = valores + "&guiaArea="	+$("txtGuiaAerea").value		+"&fechaVuelo="	+fechaVuelo			+"&recepcionCYE="		+fechatxtRecepcioncye;
valores = valores + "&nombreRemitente="		+$("txtRemitente").value		+"&calleRemitente="		+$("txtCalleR").value		+"&telefonoRemitente="		+$("txtTelefonoR").value;
valores = valores + "&rfcRemitente="			+$("txtRfcR").value		+"&destinatario="	+$("txtNombreDes").value		+"&estadoRemitente="		+$("txtNombredo").value		+"&coloniaRemitente="		+$("txtColR").value		+"&municipioRemitente="		+$("txtMunR").value		+"&codigoPR="		+$("txtCodigoPr").value;
valores = valores 	+"&piezas="		+$("txtPiezas").value	+ "&kg="			+$("txtKg").value		+"&volumen="		+$("txtVol").value		+"&validezDias="	+fechaVigencia;
valores = valores + "&sucursalDestino="		+$("slcSucursal").value	+	"&calleD="		+$("txtCalleD").value;
valores=valores + "&ColoniaD="		+$("txtColoniaD").value		+"&MunicipioD="	+	$("txtMunicipioD").value	+"&Sello="	+ sellot +"&Firma="	+ firmat +"&Respaldo="	+respaldot;
valores = valores + "&status="		+$("slcStatus").value		+"&fechaEntrega="	+fechaEntrega			+"&recibio="	+$("txtRecibio").value;
valores = valores + "&codigoPD="		+$("txtCodigoPD").value		+"&EstadoD="		+$("txtEstadoD").value		+"&TelefonoD="		+$("txtTelefonoD").value	+"&llegadaacuse="	+fechaAcuse		+"&observaciones="		+$("txaObservaciones").value;
valores=valores + "&TipoEnvio="		+$("slcTipoe").value  +  "&Recoleccion="		+$("slcRecoleccion").value +  "&valorD="		+$("txtValord").value+  "&facturas="		+$("txaFacturas").value +  "&vales="		+$("txtVales").value +  "&recibos="		+$("txaEntregas").value +  "&cveFacturas="		+$("hdncveFacturas").value+  "&hdncveEntregas="		+$("hdncveEntregas").value+  "&txtCodigoC="		+$("txtCodigoC").value+"&cveDireccion="		+$("hdncveDireccion").value;
var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value+"&cveVale="		+$("hdnVales").value;	
//alert(usuario);
valores=valores +usuario;
		$Ajax("scripts/actualizarGuias.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		
		
		$("contenedor").className="";
		}
		$("form2").reset();
			}
		
		function borrar(){
			
		if (confirm("Confirme que desea borrar")){
		
		var hid ="Campo1=" + $("txtGuia").value;
		hid+="&nombreCampo1=cveGuia";
		hid+="&tabla=cguias" ;
                
		//hid+=&cveCliente 
		$Ajax("scripts/borrarDatos.php", {metodo: $metodo.POST, onfinish: fin, parametros: hid, avisoCargando:"loading"});
		
		
		$("form2").reset();
		
		}
		
		
		}
		function trerDirecciones(ex){
			var exx=ex[0];
			var exists = exx.existe;
			var respuesta= "scripts/municipios.php?municipio=" +  exx.estadoRemitente;
			$Ajax(respuesta, {onfinish: cargaMunicipios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			var urlo="operacion=2&datos="+$("txtRazonSocial").value 
//alert(urlo);			
			$Ajax("scripts/datosClientes.php?"+urlo, {onfinish: llenaDirecciones, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			
		}
		function llenaDirecciones(campos){
				var campo = campos[0];
				//alert('hola');
				$("txtRemitente").value = campo.razonSocial;
				$("txtCalleR").value = campo.calle;
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
		{
			fecha="";
		}
		return fecha;
	}
		function llenaDatos(campos){

		//tomamos el primer objeto del json, ya que siempre devolvera un unico registro
			var campo = campos[0];
		//asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
		//alert('hi');
	
		for(var idx = 0; idx < $("slcLineaA").options.length; idx ++){
		if($("slcLineaA").options[idx].value == campo.cveLineaArea){
			$("slcLineaA").selectedIndex = idx;
			$("slcLineaA").options[idx].selected=true;
			
			}
			
		}
		
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
		$("txtNombreDes").value = campo.nombreD;
		$("txtPiezas").value = campo.piezas;
		$("txtKg").value = campo.kg;
		$("txtVol").value = campo.volumen;
		$("txtRazonSocial").value = campo.cliente;
		$("txtVigencia").value = formatoFecha(campo.validezDias.substring(8,10)+"/"+campo.validezDias.substring(5,7)+"/"+campo.validezDias.substring(0,4));
		
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
		for(var idx1 = 0; idx1 < $("slcStatus").options.length; idx1 ++){
		if($("slcStatus").options[idx1].value == campo.status){
			$("slcStatus").selectedIndex = idx1;
			$("slcStatus").options[idx1].selected=true;
			}
		}
		
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
		//$("txaObservaciones").value = campo.observaciones;
	
			if(campo.sello==1)
			{$("chkSello").checked=true;}else{$("chkSello").checked=false;}
			if(campo.respaldo==1)
			{$("chkRespaldo").checked=true;}else{$("chkRespaldo").checked=false;}
			if(campo.firma==1)
			{$("chkFirma").checked=true;}else{$("chkFirma").checked=false;}
			
			//var facturas=campo.facturas.split(",");
		
		   	//$("txaFacturas").value=facturas[0];
		   	
		//	for (var i=1; i<facturas.length;i++)
			//{
				//	$("txaFacturas").value=$("txaFacturas").value+"\n"+facturas[i];
								
			//}
			
			//var cveFacturas=campo.cvefacturas.split(",");
			//$("hdncveFacturas").value=cveFacturas[0];
			//for (var i=1; i<cveFacturas.length;i++)
			//{
				//$("hdncveFacturas").value=$("hdncveFacturas").value+"\n"+cveFacturas[i];
			
			//}
		
		   	$("txtVales").value=campo.vales;
		   	$("hdnVales").value=campo.cveVale;
		   	
		
			//var entregas=campo.entregas.split(",");
		   	//$("txaEntregas").value=entregas[0];
		   	
			//for (var i=1; i<entregas.length;i++)
			//{
				//	$("txaEntregas").value=$("txaEntregas").value+"\n"+entregas[i];
								
			//}
			//var cveEntregas=campo.cveEntregas.split(",");
			//$("hdncveEntregas").value=cveEntregas[0];
			//for (var i=1; i<cveEntregas.length;i++)
			//{
				//$("hdncveEntregas").value=$("hdncveEntregas").value+"\n"+cveEntregas[i];
			
			//}
		$("hdncveDireccion").value=campo.cveDireccion;
		var respuesta= "scripts/catalogoCP.php?operacion=3&municipio=" +campo.estadoRemitente+"&estado="+campo.municipioRemitente;
	$Ajax(respuesta, {onfinish: cargaMunicipiosG, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
    var respuesta= "scripts/catalogoCP.php?operacion=3&municipio=" +campo.estadoDestinatario+"&estado="+campo.municipioDestinatario;
//alert(respuesta+"hola");	
	$Ajax(respuesta, {onfinish: cargaMunicipiosDG, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			$("txtFechaEntrega").focus();
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
		}


		function fin(res){
		alert(res);
//$("txaEntregas").value=res;
		
		//$("contenedor").className="oculto";
		location = "guia.php";
		
		}
		
		function allgood(){
		var notGood = 0;
		
		//Datos Guía
		if($("txtGuia").value == ""){$("txtGuia").className += " invalid"; notGood ++;} else{$("txtGuia").className = "valid";}
		if($("txtRazonSocial").value == ""){$("txtRazonSocial").className += " invalid"; notGood ++;} else{$("txtRazonSocial").className = "valid";}
		if($("slcStatus").value == ""){$("slcStatus").className += " invalid"; notGood ++;} else{$("slcStatus").className = "valid";}
		//Datos Recepción
		if($("txtRecepcioncye").value == ""){$("txtRecepcioncye").className += " invalid"; notGood ++;} else{$("txtRecepcioncye").className = "valid";}
		if($("slcTipoe").value == ""){$("slcTipoe").className += " invalid"; notGood ++;} else{$("slcTipoe").className = "valid";}
		//Datos Remitente
		if($("txtRemitente").value == ""){$("txtRemitente").className += " invalid"; notGood ++;} else{$("txtRemitente").className = "valid";}
		if($("txtNombredo").value == ""){$("txtNombredo").className += " invalid"; notGood ++;} else{$("txtNombredo").className = "valid";}
		if($("txtColR").value == ""){$("txtColR").className += " invalid"; notGood ++;} else{$("txtColR").className = "valid";}
		if($("txtTelefonoR").value == ""){$("txtCalleR").className += " invalid"; notGood ++;} else{$("txtCalleR").className = "valid";}
		if(isNaN($("txtKg").value)||$("txtTelefonoR").value == ""){$("txtTelefonoR").className += " invalid"; notGood ++;} else{$("txtTelefonoR").className = "valid";}
		if(isNaN($("txtCodigoPr").value)||$("txtCodigoPr").value == ""){$("txtCodigoPr").className += " invalid"; notGood ++;} else{$("txtCodigoPr").className = "valid";}
		
		//Datos Destinatario
		if($("slcSucursal").value == ""){$("slcSucursal").className += " invalid"; notGood ++;} else{$("slcSucursal").className = "valid";}
		if($("txtNombreDes").value == ""){$("txtNombreDes").className += " invalid"; notGood ++;} else{$("txtNombreDes").className = "valid";}
		if($("txtEstadoD").value == ""){$("txtEstadoD").className += " invalid"; notGood ++;} else{$("txtEstadoD").className = "valid";}
		if($("txtMunicipioD").value == ""){$("txtMunicipioD").className += " invalid"; notGood ++;} else{$("txtMunicipioD").className = "valid";}
		if($("slcSucursal").value == ""){$("slcSucursal").className += " invalid"; notGood ++;} else{$("slcSucursal").className = "valid";}
		if($("txtColoniaD").value == ""){$("txtColoniaD").className += " invalid"; notGood ++;} else{$("txtColoniaD").className = "valid";}
		if($("txtCalleD").value == ""){$("txtCalleD").className += " invalid"; notGood ++;} else{$("txtCalleD").className = "valid";}
		if(isNaN($("txtCodigoPD").value)||$("txtCodigoPD").value == ""){$("txtCodigoPD").className += " invalid"; notGood ++;} else{$("txtCodigoPD").className = "valid";}
		if(isNaN($("txtTelefonoD").value)||$("txtTelefonoD").value == ""){$("txtTelefonoD").className += " invalid"; notGood ++;} else{$("txtTelefonoD").className = "valid";}
		//Datos Envío
		if(isNaN($("txtPiezas").value)||($("txtPiezas").value == "")){$("txtPiezas").className += " invalid"; notGood ++;} else{$("txtPiezas").className = "valid";}
		if(isNaN($("txtKg").value)|| $("txtKg").value == ""){$("txtKg").className += " invalid"; notGood ++;} else{$("txtKg").className = "valid";}		
		



		//if($("slcLineaA").value == ""){$("slcLineaA").className += " invalid"; notGood ++;} else{$("slcLineaA").className = "";}
		//if($("txtGuiaAerea").value.length < 3){$("txtGuiaAerea").className += " invalid"; notGood ++;} else{$("txtGuiaAerea").className = "";}
		//if($("txtFlNbr").value.length < 3){$("txtFlNbr").className += " invalid"; notGood ++;} else{$("txtFlNbr").className = "";}
		//if($("txtFechaVuelo").value == ""){$("txtFechaVuelo").className += " invalid"; notGood ++;} else{$("txtFechaVuelo").className = "";}
		//if($("txtRecepcioncye").value.length < 10){$("txtRecepcioncye").className += " invalid"; notGood ++;} else{$("txtRecepcioncye").className = "";}
		//if($("txtRemitente").value.length < 3){$("txtRemitente").className += " invalid"; notGood ++;} else{$("txtRemitente").className = "";}
		//if($("txtCalleR").value.length < 3){$("txtCalleR").className += " invalid"; notGood ++;} else{$("txtCalleR").className = "";}
		//if($("txtNombredo").value == ""){$("txtNombredo").className += " invalid"; notGood ++;} else{$("txtNombredo").className = "";}
		//if($("txtNombreDes").value.length < 3){$("txtNombreDes").className += " invalid"; notGood ++;} else{$("txtNombreDes").className = "";}
		//if($("txtCalleD").value.length < 3){$("txtCalleD").className += " invalid"; notGood ++;} else{$("txtCalleD").className = "";}		
	//	if($("txtCodigoPD").value != ""){
	//	if($("txtCodigoPD").value.length < 5 || isNaN($("txtCodigoPD").value)){$("txtCodigoPD").className += " invalid"; notGood ++;} else{$("txtCodigoPD").className = "";}		
		
	//	if($("txtVol").value == "" || isNaN($("txtVol").value)){$("txtVol").className += " invalid"; notGood ++;} else{$("txtVol").className = "";}		
	//	if($("txtPiezas").value == "" || isNaN($("txtPiezas").value)){$("txtPiezas").className += " invalid"; notGood ++;} else{$("txtPiezas").className = "";}		
		//if($("txtKg").value == "" || isNaN($("txtKg").value) || parseInt($("txtKg").value) > 10000){$("txtKg").className += " invalid"; notGood ++;} else{$("txtKg").className = "";}	
		//if($("txtVigencia").value.length < 10){$("txtVigencia").className += " invalid"; notGood ++;} else{$("txtVigencia").className = "";}
						
		//if($("slcStatus").value == ""){$("slcStatus").className += " invalid"; notGood ++;} else {$("slcStatus").className = "";}
		
		
		
		
		if(notGood > 0){
			alert("Hay Informacion erronea que ha sido resaltada en color!");
			return false;
			} else
			{
				return true;
			}
			
		}
				
		