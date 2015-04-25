window.onload = inicia;

function inicia() {

	campo=document.getElementById("txtEnvio");
	campo.focus();
	var url="scripts/catalogoGeneral.php?operacion=1&tabla=ctipoenvio&campo=cveTipoEnvio"
	$("divClave").className = "autocomplete";
	
	new Ajax.Autocompleter("txtEnvio", "divClave", url, {paramName: "caracteres",afterUpdateElement:existe});
	
	$("btnBuscar").onclick=existe;	
	$("btnCancelar").onclick=cancelar;
	
	$("btnCancelar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnModificar").disabled=true;
	
	//Cargaremos los Tipos de Envío en una Lista
	$Ajax("scripts/datosGenerales.php?operacion=10&valor="+	$("txtEnvio").value, {onfinish: llenaGrid, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	//Total de Tipos de Envio
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=5", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});		
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
function cancelar()
{
	$("btnModificar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnBuscar").disabled=false;
	$("btnCancelar").disabled=true;
	$("act_des").style.visibility="hidden";
	$("act_des").style.display="none";
	$("status").innerHTML="";
	quitar_invalidos();		
	campo=document.getElementById("txtEnvio");
	campo.focus();
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=5", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});		
	$Ajax("scripts/datosGenerales.php?operacion=10&valor="+$("txtEnvio").value, {onfinish: llenaGrid, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
}
var indiceFilaFormulario=1;	
function llenaGrid(campos)
{
	if(document.getElementById("tblEnvios").rows.length>1){	
	
		var ultima = document.getElementById("tblEnvios").rows.length;
		
			for(var j=ultima; j>1; j--){				
					 document.getElementById("tblEnvios").deleteRow(1);	
							
		}
	}
	var separacion=0;
	for (var i=0; i<campos.length; i++){
			
		 var campo = campos[i];
		if((separacion==0)&&(campo.estado==0)) //Se agregara una separacion para Activos e Inactivos
		 {
			 myNewRow = document.getElementById("tblEnvios").insertRow(-1); 
			 myNewRow.id=indiceFilaFormulario;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.colSpan="3";
			 myNewCell.innerHTML="<th>Env&iacute;os Desactivados</th>";
			 separacion++;
			 indiceFilaFormulario++;
		 }

			 myNewRow = document.getElementById("tblEnvios").insertRow(-1); 
			 myNewRow.id=indiceFilaFormulario;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" + campo.cveMoneda +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" + campo.descripcion +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td ><input type='hidden' id='cveDetalle_"+indiceFilaFormulario+"' value='" + campo.cveMoneda +"'/><input type='button' name='Editar["+indiceFilaFormulario+"]'  value='Editar' onclick='editarDetalle(this,cveDetalle_"+indiceFilaFormulario+".value)'></td>";
		 indiceFilaFormulario++;     
	}
}
function existe() {	
	if ($("txtEnvio").value!=""){
		var url = "scripts/existe.php?keys=1&table=ctipoenvio&field1=cveTipoEnvio&f1value=" + $("txtEnvio").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}else { alert("Ingrese un Env\u00EDo"); document.getElementById("txtEnvio").focus();}
}
function editarDetalle(obj,cveDetalle){   
	$("txtEnvio").value=cveDetalle;
	$("btnModificar").disabled=false;
	$("btnGuardar").disabled=true;
    existe();
	//eliminamos la fila una vez que se cargan los datos
	var oTr = obj;
	while(oTr.nodeName.toLowerCase()!='tr'){
		oTr=oTr.parentNode;
	}
	var root = oTr.parentNode;
	root.removeChild(oTr);
         
}
function insertar(){			
	if(allgood()){
		var valores = "&txtEnvio="		+$("txtEnvio").value		+"&txtDescripcion="		+$("txtDescripcion").value		
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
		valores=valores +usuario;
		$Ajax("scripts/administraTiposE.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		$("txtEnvio").disabled=false;	
	}
		
}
		
function actualizar(){
	if(allgood()){
		 //MPF JUNIO
		if($("chkActivado").checked)
			 estado=1;
		else estado=0;
		var valores ="&txtEnvio="		+$("txtEnvio").value+"&txtDescripcion="		+$("txtDescripcion").value+"&txthClaveEnvio="		+$("txthClaveEnvio").value;		
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value+"&estado="		+estado;	
		valores=valores +usuario;
		$Ajax("scripts/administraTiposE.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		
		$("txtEnvio").disabled=false;		
	}

}
	
function llenaDatos(campos){

	//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var campo = campos[0];
	//Asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
	//APF JUNIO
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
	$("txtEnvio").value = campo.cveMoneda;
	$("txthClaveEnvio").value = campo.cveMoneda;	
	$("txtDescripcion").value = campo.descripcion;
	$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
}

function fin(res){
	alert(res);
	cancelar();		
}
		
function allgood(){
	var notGood = 0;		
	if($("txtEnvio").value==''){$("txtEnvio").className = "invalid"; notGood ++;} else {$("txtEnvio").className = " valid";}
	if($("txtDescripcion").value == ""){$("txtDescripcion").className = "invalid"; notGood ++;}  else{$("txtDescripcion").removeClassName("invalid");}

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
	campo=document.getElementById("txtEnvio");
	campo.focus();
	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0){			
	
		$Ajax("scripts/datosGenerales.php?operacion=9&valor="+	$("txtEnvio").value, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		//cambiamos el manejador del boton actualizar para que apunte a la funcion
		//que actualiza el registro
		$("btnModificar").disabled=false;
		$("btnModificar").onclick=actualizar;
		//mostramos el boton de borrar
		//el boton guardar no es útil aqui, por lo tanto lo ocultamos
		$("btnGuardar").disabled=true;
		$("act_des").style.visibility="visible";
	    if (navigator.appName.indexOf("Explorer") != -1) 
			$("act_des").style.display="block";
		else
			$("act_des").style.display="table-row";
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	}
	else{	//si la funcion devolvio cero, no existe el registro
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="visible";
		//limpiamos los campos del form		
		$("btnGuardar").disabled=false;
		$("btnGuardar").onclick=insertar;
		//imprimimos un aviso de que se trata de un registro nuevo
		$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
		//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
		$("btnModificar").disabled=true;
	}
}
			
