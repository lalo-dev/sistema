window.onload = inicia;

function inicia() {	
	campo=document.getElementById("txtMoneda");
	campo.focus();	
	var url="scripts/catalogoGeneral.php?operacion=6&tabla=cmonedas&campo=cveMoneda&campo2=descripcion";
	$("divClave").className = "autocomplete";
	
	new Ajax.Autocompleter("txtMoneda", "divClave", url, {paramName: "caracteres",afterUpdateElement:existe});
	$("btnBuscar").onclick=existe;
	
	$("btnCancelar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnModificar").disabled=true;
	
	//Total de Monedas
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=2", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	$("btnCancelar").onclick=cancelar;
	$Ajax("scripts/datosGenerales.php?operacion=8&valor="+	$("txtMoneda").value, {onfinish: llenaGrid, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

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
	$("txtMoneda").disabled=false;	
	$("status").innerHTML="";
	$("act_des").style.visibility="hidden";
	$("act_des").style.display="none";
	$("btnBuscar").disabled=false;
	$("btnModificar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnCancelar").disabled=true;
	quitar_invalidos();		
	campo=document.getElementById("txtMoneda");
	campo.focus();	
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=2", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$Ajax("scripts/datosGenerales.php?operacion=8&valor="+	$("txtMoneda").value, {onfinish: llenaGrid, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
var indiceFilaFormulario=1;	
function llenaGrid(campos)
{
	if(document.getElementById("tblMonedas").rows.length>1){	
		var ultima = document.getElementById("tblMonedas").rows.length;
		for(var j=ultima; j>1; j--){				
			 document.getElementById("tblMonedas").deleteRow(1);			
		}
	}
	var separacion=0;
	for (var i=0; i<campos.length; i++){			
		 var campo = campos[i];
		 if((separacion==0)&&(campo.estado==0)) //Se agregara una separacion para Activos e Inactivos
		 {
			 myNewRow = document.getElementById("tblMonedas").insertRow(-1); 
			 myNewRow.id=indiceFilaFormulario;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.colSpan="3";
			 myNewCell.innerHTML="<th>Monedas Desactivadas</th>";
			 separacion++;
			 indiceFilaFormulario++;
			 
		 }
		 myNewRow = document.getElementById("tblMonedas").insertRow(-1); 
		 myNewRow.id=indiceFilaFormulario;
		 myNewCell=myNewRow.insertCell(-1);
		 myNewCell.innerHTML="<td >" + campo.cveMoneda +"</td>";
		 myNewCell=myNewRow.insertCell(-1);
		 myNewCell.innerHTML="<td>" + campo.descripcion +"</td>";
		 myNewCell=myNewRow.insertCell(-1);
		 myNewCell.innerHTML="<td ><input type='hidden' id='cveDetalle_"+indiceFilaFormulario+"' value='" + campo.cveMoneda +"'/><input type='button' name='Editar["+					indiceFilaFormulario+"]'  value='Editar' onclick='editarDetalle(this,cveDetalle_"+indiceFilaFormulario+".value)'></td>";
		 indiceFilaFormulario++;
   }
}
function existe() {
	
	if ($("txtMoneda").value!=""){
		var url = "scripts/existe.php?keys=8&table=cmonedas&field1=cveMoneda&f1value=" + $("txtMoneda").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}else{ alert("Ingrese una Moneda."); document.getElementById("txtMoneda").focus();}
}
function editarDetalle(obj,cveDetalle){   
	$("txtMoneda").value=cveDetalle;
	existe();
	//Eliminamos la fila una vez que se cargan los datos
	var oTr = obj;
	while(oTr.nodeName.toLowerCase()!='tr'){
	  oTr=oTr.parentNode;
	}
	var root = oTr.parentNode;
	root.removeChild(oTr);         
}
function insertar(){			
	if(allgood()){
		var valores = "&txtMoneda="		+$("txtMoneda").value		+"&txtDescripcion="		+$("txtDescripcion").value;		
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
		valores=valores +usuario;
		$Ajax("scripts/administraMonedas.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		$("txtMoneda").disabled=false;	
	}
	$Ajax("scripts/datosGenerales.php?operacion=8&valor="+	$("txtMoneda").value, {onfinish: llenaGrid, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
		
function actualizar(){
	if(allgood()){
		if($("chkActivado").checked)
			 estado=1;
		else estado=0;

		var valores = "&txtMoneda="		+$("txtMoneda").value		+"&txtDescripcion="		+$("txtDescripcion").value+"&txthClaveMon="		+$("txthClaveMon").value;		
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value+"&estado="		+estado;	
		valores=valores +usuario;
		$Ajax("scripts/administraMonedas.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		$("txtMoneda").disabled=false;		
	}
	$Ajax("scripts/datosGenerales.php?operacion=8&valor="+	$("txtMoneda").value, {onfinish: llenaGrid, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
		
	
function llenaDatos(campos){

	//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var campo = campos[0];
	//Asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
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
	$("txtMoneda").value = campo.cveMoneda;
	$("txthClaveMon").value = campo.cveMoneda;		
	$("txtDescripcion").value = campo.descripcion;

}

function fin(res){
	alert(res);
	cancelar();		
}
		
function allgood(){
	var notGood = 0;		
	if($("txtMoneda").value=='')  {$("txtMoneda").addClassName("invalid"); notGood ++;} else{$("txtMoneda").removeClassName("invalid");}
	if($("txtDescripcion").value=='')  {$("txtDescripcion").addClassName("invalid"); notGood ++;} else{$("txtDescripcion").removeClassName("invalid");}

	if(notGood > 0){
		alert("Hay Informacion erronea que ha sido resaltada en color!");
		return false;
	} else {return true;}
	
}
function next_existe(ex){
	//Extraemos el valor retornado por el servidor en un objeto json
	var exx=ex[0];
	var exists = exx.existe;
	var cve=exx.cve;
	$("btnBuscar").disabled=true;
	$("btnCancelar").disabled=false;
	campo=document.getElementById("txtMoneda");
	campo.focus();

	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0){			
		$Ajax("scripts/datosGenerales.php?operacion=7&valor="+cve, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		$("act_des").style.visibility="visible";
	    if (navigator.appName.indexOf("Explorer") != -1) 
			$("act_des").style.display="block";
		else
			$("act_des").style.display="table-row";
		//cambiamos el manejador del boton actualizar para que apunte a la funcion
		//que actualiza el registro
		$("btnModificar").disabled=false;
		$("btnModificar").onclick=actualizar;
		$("btnGuardar").disabled=true;
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	}
	else{	//Si la funcion devolvio cero, no existe el registro
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		$("btnModificar").disabled=true;
		//limpiamos los campos del form		
		$("btnGuardar").disabled=false;
		$("btnGuardar").onclick=insertar;
		//imprimimos un aviso de que se trata de un registro nuevo
		$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
		//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
		$("btnbuscar").disabled=true;
   }
}
			
