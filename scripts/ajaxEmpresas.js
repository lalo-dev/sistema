window.onload = inicia;

function inicia() {
	campo=document.getElementById("txtrazon");
	campo.focus();
	var url="scripts/catalogoGeneral.php?operacion=1&tabla=cempresas&campo=razonSocial&campo2=cveEmpresa";
	$("divRazon").className = "autocomplete";
	
	new Ajax.Autocompleter("txtrazon", "divRazon", url, {paramName: "caracteres",afterUpdateElement:existe});
	$("btnBuscar").onclick=existe;	
	$("btnCancelar").onclick=cancelar;
	
	$("btnCancelar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnModificar").disabled=true;
	//Total de Empresas
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=1", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

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
function cancelar(){

	$("txtrazon").disabled=false;
    $("btnModificar").disabled=true;
    $("btnGuardar").disabled=true;
    $("btnCancelar").disabled=true;
    $("btnBuscar").disabled=false;
    $("act_des").style.visibility="hidden";
	$("act_des").style.display="none";
    $("status").innerHTML="";
    quitar_invalidos();		
	campo=document.getElementById("txtrazon");
	campo.focus();
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=1", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

		
}
function existe() {
	if ($("txtrazon").value!=""){
		var url = "scripts/existe.php?keys=1&table=cempresas&field1=razonSocial&f1value=" + $("txtrazon").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}else { alert("Ingrese una Raz\u00F3n Social."); document.getElementById("txtrazon").focus();}
}
function insertar(){
			
	if(allgood()){
		//Modificar el valor de los text area, para poder interpretarlo posteriormente correctamente					
		//g nos indica que se reemplazarán todas las coincidencias gi significa q es sin importar mayúsicuals y minúsculas
	   if (navigator.appName.indexOf("Explorer") != -1) 
			var regX = /\r\n/g;
	   else
			var regX = /\n/g;
			
		var replaceString = '[br]';
		cadena=$("txtDireccion").value;
		direccion=cadena.replace(regX,replaceString);
		
		 var valores = valores + "&txtrazon="		+$("txtrazon").value		+"&txtRfc="		+$("txtRfc").value		
		 +"&txtDireccion="		+direccion;
		   var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
	valores=valores +usuario;
			$Ajax("scripts/administraEmpresas.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
						
	}
}
		
function actualizar(){

	if(allgood()){
		if($("chkActivado").checked)
			 estado=1;
		else estado=0;
			
		//Modificar el valor de los text area, para poder interpretarlo posteriormente correctamente					
		//g nos indica que se reemplazarán todas las coincidencias gi significa q es sin importar mayúsicuals y minúsculas
		if (navigator.appName.indexOf("Explorer") != -1) 
		var regX = /\r\n/g;
		else
		var regX = /\n/g;
		var replaceString = '[br]';
		cadena=$("txtDireccion").value;
		direccion=cadena.replace(regX,replaceString);
			
		var valores =valores +"&hdnClave="		+$("hdnClave").value+ "&txtrazon="		+$("txtrazon").value+"&txthrazon="		+$("txthrazon").value				
		+"&txtRfc="		+$("txtRfc").value		+"&txthRfc="		+$("txthRfc").value		+"&txtDireccion="		+direccion+"&estado="		+estado;
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
		valores=valores +usuario;
		
		$Ajax("scripts/administraEmpresas.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		$("txtrazon").disabled=false;		
	}
}
		
		
function llenaDatos(campos){
	//tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var campo = campos[0];
	//asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
	$("hdnClave").value = campo.cveEmpresa;
	$("txtrazon").value = campo.razonSocial;
	$("txthrazon").value = campo.razonSocial;
	$("txtRfc").value = campo.rfc;	
	$("txthRfc").value = campo.rfc;	
	
	//Ahora sustituiremos los [br] por un salto de línea		
	var regX = /\[br\]/g;
	var replaceString = '\n';
	
	cadena=campo.direccion;
	direccion=cadena.replace(regX,replaceString);		
	
	$("txtDireccion").value = direccion;
	
	if(campo.estado==1)
	{
		$("lblActivado").value="Activado";
		$("chkActivado").checked=true;
	}
	else if(campo.estado==0)
	{
		$("lblActivado").value="Desactivado";
		$("chkActivado").checked=false;
	}
	$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
}

function fin(res){
	alert(res);
	cancelar();
}
		
function allgood(){
	var notGood = 0;		
	if($("txtrazon").value == ""){$("txtrazon").className = "invalid"; notGood ++;} else{$("txtrazon").removeClassName("invalid");}
	if($("txtRfc").value == ""){$("txtRfc").className = "invalid"; notGood ++;} else{$("txtRfc").removeClassName("invalid");}
	
	if(notGood > 0){
		alert("\u00A1Hay Informaci\u00F3n err\u00F3nea que ha sido resaltada en color!");
		return false;
	} else { return true;}			
}

function next_existe(ex){
		
	//extraemos el valor retornado por el servidor en un objeto json
	var exx=ex[0];
	var exists = exx.existe;
	$("btnBuscar").disabled=true;
	$("btnCancelar").disabled=false;
	campo=document.getElementById("txtrazon");
	campo.focus();
	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0){			
	
		var url="scripts/datosGenerales.php?operacion=6&valor="+	$("txtrazon").value;
		$Ajax(url, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		$("act_des").style.visibility="visible";
	   if (navigator.appName.indexOf("Explorer") != -1) 
			$("act_des").style.display="block";
		else
			$("act_des").style.display="table-row";
		//cambiamos el manejador del boton actualizar para que apunte a la funcion
		//que actualiza el registro
		$("btnModificar").disabled=false;
		$("btnModificar").onclick=actualizar;
		//mostramos el boton de borrar
		//el boton guardar no es útil aqui, por lo tanto lo ocultamos
		$("btnGuardar").disabled=true;
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	}
	else{	//si la funcion devolvio cero, no existe el registro
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		//limpiamos los campos del form		
		$("btnGuardar").onclick=insertar;
		$("btnGuardar").disabled=false;
		$("btnModificar").disabled=true;
		//imprimimos un aviso de que se trata de un registro nuevo
		$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
		//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
		$("btnModificar").disabled=true;
	}
}
			
