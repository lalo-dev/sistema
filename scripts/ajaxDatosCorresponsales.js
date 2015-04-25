window.onload = inicia;

function inicia() {
	
	
	//Primero verificar la estación a la que pertenece el Corresponsal
	 $Ajax("scripts/datosUsuarios.php?operacion=1", {onfinish: cargaEstacion, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	edo_guia(0,'');
	
	document.getElementById("txtGuia").focus();
	$("btnModificar").disabled=true;
	$("btnCancelar").disabled=true;
	$("btnModificar").onclick=actualizar;
	$("btnbuscar").onclick=existe;

	
	if(($("txtGuia").value!="Guia House")&&($("txtGuia").value!=""))
	{
	   $("txtGuia").disabled=true;
	   $("btnModificar").disabled=false; 
	   $("btnCancelar").disabled=false;
       	   setTimeout("existe2()",150);
	}
	
	setTimeout("inicializar()",150);
	

}
function inicializar()
{

	//Inicializando autocomplete
	$("autoGuia").className = "autocomplete";
	new Ajax.Autocompleter("txtGuia", "autoGuia", "scripts/catalogoGuiasCorresponsal.php?operacion=1&estacion="+$("txthEstacion").value, {paramName: "caracteres",afterUpdateElement:existe});
	$("txtGuia").onchange=existe;	
	$("btnCancelar").onclick=function(){	
		location = "guiaCorresponsal.php";
	};

}

function cargaEstacion(datos)
{
	dato=datos[0];
	$("txthEstacion").value=dato.estacion;
}



//Funcion que verifica si existe una guia
//hace una peticion que devuelve un único valor
function existe() {

	$("status").innerHTML="";
	if ($("txtGuia").value!="" ){
		//Separamos la clave de la guía del Nombre del Remitente
		valor_guia=$("txtGuia").value.split(" - ",1);
		$("txtGuia").value=valor_guia;
		var url = "scripts/existeUsuarios.php?keys=1&table=cguias&field1=cveGuia&f1value="+$("txtGuia").value+"&estacion="+$("txthEstacion").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}
}
function existe2() {	
	$("status").innerHTML="";
	if ($("txtGuia").value!="" ){
		var url = "scripts/existeUsuarios.php?keys=1&table=cguias&field1=cveGuia&f1value="+$("txtGuia").value+"&estacion="+$("txthEstacion").value;
				
		$Ajax(url, {onfinish: next_existe2, tipoRespuesta: $tipo.JSON});
	}
}

//esta funcion recibe el valor de la función anterior y lo evalúa	
function next_existe(ex){

	var exx=ex[0];
	var exists = exx.existe;
	var mod = exx.modificar;
	
	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0){
		if(mod==1)
		{ 
			$("status").innerHTML="<label class='message' for='element_3'>La Gu\u00EDa no puede ser modificada\n (cualquier aclaraci\u00F3n contactar a la central)</label>"; 
		//el boton borrar no es útil aqui, por lo tanto lo ocultamos
		$("btnModificar").disabled=true;
		$("btnCancelar").disabled=true;
		}else{ 
		//se piden los datos
		 location = "guiaCorresponsal.php?cveGuia=" + $("txtGuia").value ;
		}	
	}
	else{ //si la funcion devolvio cero, no existe el registro

		//el boton borrar no es útil aqui, por lo tanto lo ocultamos
		$("btnModificar").disabled=true;
		$("btnCancelar").disabled=true;
		$("status").innerHTML="<label class='message' for='element_3'>La Gu\u00EDa no existe</label>";		
	}
}
function datos(url)
{

	$Ajax(url, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
		
function next_existe2(ex){

	
	var exx=ex[0];
	var exists = exx.existe;

	if (exists > 0){
		var url2 = $("txtGuia").value;					
		var url="scripts/datosGuiasUsuarios.php?cveguia="+url2;
		setTimeout("datos('"+url+"')",50);
	    		
	
	}else 
	{
		//Reiniciar todo
		$("btnModificar").disabled=true;
		$("btnCancelar").disabled=true;

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
		
		var fechaEntrega = $("txtFechaEntrega").value.substring(6,10)+"-"+$("txtFechaEntrega").value.substring(3,5)+"-"+$("txtFechaEntrega").value.substring(0,2);		
		
		var valores = "cveEstadoGuia="		+$("txthStatus").value+"&Sello="	+ sellot +"&Firma="	+ firmat +"&Respaldo="	+respaldot+"&recibio="	+$("txtRecibio").value+"&fechaEntrega="	+fechaEntrega+ "&slcStatus="		+$("slcStatus").value+"&cveGuia="		+$("txtGuia").value	;		
		
		 var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value+"-"+$("hdnSucursal").value;	
		
		valores=valores +usuario;
	
		$Ajax("scripts/actualizarGuiasUsuarios.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		
		$("form2").reset();
		$("contenedor").className="";
	}

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
	
	//Asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
	
	//Cliente
	$("txtRazonSocial").value  = campo.cliente;
	
	//Remitente
	$("txtRemitente").value  = campo.nombreRemitente;
	$("txtCalleR").value     = campo.calleRemitente;
	$("txtTelefonoR").value  = campo.telefonoRemitente;
	$("txtRfcR").value       = campo.rfcRemitente;	
	$("txtColR").value       = campo.coloniaRemitente;
	$("txtCodigoPr").value   = campo.codigoPR;
	
	$("txtNombredo").value   = campo.estadoRemitente;
	$("txtMunR").value       = campo.municipioRemitente;
	
	
	
	//Destinatario	
	$("txtNombreDes").value  = campo.nombreD;
	$("txtEstadoD").value    = campo.estadoDestinatario;
	$("txtMunicipioD").value = campo.municipioDestinatario;
	$("slcSucursal").value = campo.sucursalDestino;
	
	$("txtColoniaD").value   = campo.coloniaDestinatario;
	$("txtCalleD").value     = campo.calleDestinatario;
	$("txtCodigoPD").value   = campo.codigoPostaldestinatario;
	$("txtTelefonoD").value  = campo.telefonoD;	
	
	edo_guia(1,campo.status);
	
	$("txtRecibio").value  = campo.recibio;	
	$("txtFechaEntrega").value =formatoFecha(campo.fechaEntrega.substring(8,10)+"/"+campo.fechaEntrega.substring(5,7)+"/"+campo.fechaEntrega.substring(0,4));
	
	if(campo.sello==1)
	{$("chkSello").checked=true;}else{$("chkSello").checked=false;}
	if(campo.respaldo==1)
	{$("chkRespaldo").checked=true;}else{$("chkRespaldo").checked=false;}
	if(campo.firma==1)
	{$("chkFirma").checked=true;}else{$("chkFirma").checked=false;}
	
	$("txtFechaEntrega").focus();
	//Imprimimos un mensaje de actualizando
	$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	
	$("slcStatus").value  = campo.status;
	
	$("txthStatus").value = campo.status;
	$("txtRazonSocial").value = campo.cliente;
	
}
function edo_guia(opc,estado)
{

     //Si la opción es 0 cargará todos los estados para la guía
	 var selec_edo = $("slcStatus");  
     selec_edo.options.length = 0;  
	 selec_edo.options.add(new Option("Seleccione un Status",""));
	 if(opc==0)
	 {
		 selec_edo.options.add(new Option("Enviada A destino","Eviada A destino"));
		 selec_edo.options.add(new Option("En proceso de Entrega","En proceso de Entrega"));
		 selec_edo.options.add(new Option("Sin localizar destinatario","Sin localizar destinatario"));
		 selec_edo.options.add(new Option("Dirrecion erronea","Dirrecion erronea"));
		 selec_edo.options.add(new Option("Recabando Sello","Recabando Sello"));
		 selec_edo.options.add(new Option("Faltan documentos","Faltan documentos"));
		 selec_edo.options.add(new Option("Con cita para entrega","Con cita para entrega"));
		 selec_edo.options.add(new Option("Entrega Rechazada","Entrega Rechazada"));
		 selec_edo.options.add(new Option("Cancelada","Cancelada"));
		 selec_edo.options.add(new Option("Entregada","Entregada"));
	 }else
	 {
		if((estado=="Entregada")||(estado=="Cancelada")||(estado=="Entrega Rechazada"))	
		{
			opciones=new Array('Con cita para entrega','Entrega Rechazada','Cancelada','Entregada');
		}
		else{
			opciones=new Array('Enviada A destino','En proceso de Entrega','Sin localizar destinatario','Dirrecion erronea','Recabando Sello','Faltan documentos','Con cita para entrega','Entrega Rechazada','Cancelada','Entregada');

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

function fin(res){
	alert(res);
	location = "guiaCorresponsal.php";
}
		
function allgood(){
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

		if(($("slcStatus").value =="Entregada") || ($("txtFechaEntrega").value != ""))
		{
			if($("txtFechaEntrega").value == ""){$("txtFechaEntrega").addClassName("invalid"); notGood ++;} 
			else{
				//Checar que las fechas ya se de Entrega o la de Acuse esten correctas
				var expresion = /^\s*(\d{2,2})\/(\d{2,2})\/(\d{4,4})\s*$/;	
					
				//Se verificará formato de fecha y válidez , vuelo y vigencia		
				if(!($("txtFechaEntrega").value.match(expresion)))
				{$("txtFechaEntrega").addClassName("invalid"); alert("El formato de la fecha de entrega es incorrecto (dd/mm/yyyy)."); return false;} 
				else{$("txtFechaEntrega").removeClassName("invalid");}
			}
			
			
			
			if($("slcStatus").value =="Entregada"){
				var chks=4;
				if($(chkSello).checked) chks ++;
				if($(chkFirma).checked) chks ++;
				if($(chkRespaldo).checked) chks ++;
				if($(chkRespaldo).checked) chks ++;
			
				if(chks==4)//Significa que no seleccionaron ningún check
				{
					$("error").innerHTML="<label style='font-size:12px;font-family:Verdana, Geneva, sans-serif;color:#F90;'>Seleccione alguna de las opciones</label>";					
					notGood++;
									
				}else
				{
					$("error").innerHTML="";
				
				}
			}
		
		}

	}
		
	if(notGood > 0){
		alert("Hay Informacion erronea que ha sido resaltada en color!");
		return false;
	} else 	{ return true;	}
		
}
				
		
