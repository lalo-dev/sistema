window.onload = inicia;

function inicia() {

	campo=document.getElementById("txtDescripcion");
	campo.focus();
	var respuesta= "scripts/estados.php?pais=156";
	$Ajax(respuesta, {onfinish: cargaEstados, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$("btnCancelar").onclick=cancelar;
   	$("sltNombredo").onchange=traerdatos;
  	$("btnGuardar").disabled=true;
   	$("btnModificar").disabled=true;
	$("btnCancelar").disabled=true;
	
	//APF JUNIO
	$("sucursales_edo").style.visibility="hidden";

	
   	 var url="scripts/catalogoGeneral.php?operacion=9&tabla=cdestinos&campo=descripcion";
	$("divDescripcion").className = "autocomplete";
	new Ajax.Autocompleter("txtDescripcion", "divDescripcion", url, {paramName: "caracteres",afterUpdateElement:existe});
	$("btnBuscar").onclick=existe;	
	
	//Total de Destinos
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=3", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
    	//Lista de Sucursales
	var url= "scripts/lista_sucursales.php?cve_estado="+$("sltNombredo").value+"&opcion=0";
   	 $Ajax(url, {onfinish: cargaLista2, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
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
	$("sltNombredo").disabled=false;
	$("txtDescripcion").disabled=false;
	$("btnCancelar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnModificar").disabled=true;
	$("btnBuscar").disabled=false;
	$("status").innerHTML="";
	$("act_des").style.visibility="hidden";
	$("act_des").style.display="none";
	//Limpir lista
	traerdatos(0);
	quitar_invalidos();		
    campo=document.getElementById("txtDescripcion");
    campo.focus();
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=3", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
function cargaEstados(estados){
	//Borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("sltNombredo").options.length = 0;
	//Empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("sltNombredo").options[$("sltNombredo").options.length] = opcion;
	
	for (var i=0; i<estados.length; i++){
		var estado = estados[i];
		var opcion = new Option(estado.desc, estado.id);
	
		try {$("sltNombredo").options[$("sltNombredo").options.length]=opcion;}
		catch (e){alert("Error interno");}
	}
}

function cargaSucursales(){
   var url= "scripts/lista_sucursales.php?cve_estado="+$("sltNombredo").value+"&opcion=1";
   $Ajax(url, {onfinish: cargaLista, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
var indiceFilaFormulario=1;
function cargaLista(campos) //Agrega los elementos a una tabla
{
	if(document.getElementById("lista_sucursales").rows.length>1){	
		var ultima = document.getElementById("lista_sucursales").rows.length;			
		for(var j=ultima; j>1; j--){				
					 document.getElementById("lista_sucursales").deleteRow(1);	
							
		}
	}
	myNewRow = document.getElementById("lista_sucursales").insertRow(-1); 
	myNewCell=myNewRow.insertCell(-1);
	myNewCell.innerHTML="<td align='center' width='70' >Clave</td>";
	myNewCell=myNewRow.insertCell(-1);
	myNewCell.innerHTML="<td align='center' width='100'>Descripci&oacute;n</td>";			
	myNewCell=myNewRow.insertCell(-1);
	myNewCell.innerHTML="<td align='center' width='70' >Estado</td>";					
	for (var i=0; i<campos.length; i++){
	 color=(i%2);
	 var campo = campos[i];
	 myNewRow = document.getElementById("lista_sucursales").insertRow(-1); 
	 myNewRow.id=indiceFilaFormulario;
	 if(color==1)
		 myNewRow.style.background="#CEE7FF";
	 myNewCell=myNewRow.insertCell(-1);
	 myNewCell.innerHTML="<td align='center' width='70' style='font-size:10px'>" + campo.cveDestino +"</td>";
	 myNewCell=myNewRow.insertCell(-1);
	 myNewCell.innerHTML="<td align='center' width='100' style='font-size:10px'>" + campo.razon +"</td>";
	 myNewCell=myNewRow.insertCell(-1);
	 myNewCell.innerHTML="<td align='center' width='70' style='font-size:10px'>" + campo.estatus +"</td>";
	 indiceFilaFormulario++;     
   }
}

var indiceFilaFormulario2=1;
function cargaLista2(campos) //Agrega los elementos a una tabla
{
	if(document.getElementById("tblSucursales").rows.length>1){	
		var ultima = document.getElementById("tblSucursales").rows.length;			
		for(var j=ultima; j>1; j--){				
					 document.getElementById("tblSucursales").deleteRow(1);	
							
		}
	}			
	for (var i=0; i<campos.length; i++){
	 color=(i%2);
	 var campo = campos[i];
	 myNewRow = document.getElementById("tblSucursales").insertRow(-1); 
	 myNewRow.id=indiceFilaFormulario2;
	 if(color==1)
		 myNewRow.style.background="#CEE7FF";
	 myNewCell=myNewRow.insertCell(-1);
	 myNewCell.innerHTML=campo.cveDestino;
	 myNewCell=myNewRow.insertCell(-1);
	 myNewCell.innerHTML=campo.razon;
	 myNewCell=myNewRow.insertCell(-1);
	 myNewCell.innerHTML=campo.estatus;
	 indiceFilaFormulario2++;     
   }
}

 function traerdatos(opc){   
	//APF JUNIO //Para mostrar las listas si se elije un valor válido
	if(document.getElementById("lista_sucursales").rows.length>1){	
		var ultima = document.getElementById("lista_sucursales").rows.length;			
		for(var j=ultima; j>1; j--){				
					 document.getElementById("lista_sucursales").deleteRow(1);	
							
		}
	}
	
	if(opc==0){
		$("sucursales_edo").style.visibility="hidden";
	}else
	{
		$("sucursales_edo").style.visibility="visible";
		cargaSucursales();
	}	
}
function existe() {
	if ($("txtDescripcion").value!=""){
		valores=$("txtDescripcion").value.split(" - ");
		$("txtDescripcion").value=valores[0];
		var url = "scripts/existe.php?keys=11&table=cdestinos&field1=descripcion&f1value=" + $("txtDescripcion").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}else { alert("Ingrese una Estaci\u00F3n."); document.getElementById("txtDescripcion").focus();}
}
function insertar(){			
	if(allgood()){
		var valores = valores + "&txtDescripcion="		+$("txtDescripcion").value		+"&sltNombredo="		+$("sltNombredo").value	;
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
		valores=valores +usuario;   
		$Ajax("scripts/administraDestinos.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
						
	}
}
function actualizar(){			
	if(allgood()){
		if($("chkActivado").checked)
			 estatus=1;
		else estatus=0;
		var valores = valores + "&txtDescripcion="		+$("txtDescripcion").value		+"&sltNombredo="		+$("sltNombredo").value+"&hdnClave="		+$("hdnClave").value+"&estatus="		+estatus
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
		valores=valores +usuario;   
		$Ajax("scripts/administraDestinos.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
						
	}	
}
		
function llenaDatos(campos){	
	//tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var campo = campos[0];
	//asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
	$("sltNombredo").value = campo.estado;
	$("hdnClave").value = campo.cveDestino;
	$("txtDescripcion").value=campo.descripcion;
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
	
	setTimeout("traerdatos(1)",50);
}

function fin(res){
	alert(res);
	cancelar();		
	$("status").innerHTML="";
	//Lista de Sucursales
	var url= "scripts/lista_sucursales.php?cve_estado="+$("sltNombredo").value+"&opcion=0";
	$Ajax(url, {onfinish: cargaLista2, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});		
}
		
function allgood(){
	var notGood = 0;
	if($("txtDescripcion").value == ""){$("txtDescripcion").addClassName("invalid"); notGood ++;} else{$("txtDescripcion").removeClassName("invalid");}
	if($("sltNombredo").value == ""){$("sltNombredo").addClassName("invalid"); notGood ++;} else{$("sltNombredo").removeClassName("invalid");}
	
	if(notGood > 0){
		alert("\u00A1Hay Informaci\u00F3n err\u00F3nea que ha sido resaltada en color!");			
		return false;
	}else {	return true;}
	
}
function next_existe(ex){       
	//extraemos el valor retornado por el servidor en un objeto json
	var exx=ex[0];
	var exists = exx.existe;
	$("btnBuscar").disabled=true;
	$("btnCancelar").disabled=false;
	campo=document.getElementById("txtDescripcion");
	campo.focus();
	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0){			
	
		$Ajax("scripts/datosGenerales.php?operacion=5&valor="+	$("txtDescripcion").value, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		//cambiamos el manejador del boton actualizar para que apunte a la funcion
		//que actualiza el registro
		 $("btnModificar").onclick=actualizar;
		 $("btnGuardar").disabled=true;
		 $("btnModificar").disabled=false;
		 $("act_des").style.visibility="visible";
		 if (navigator.appName.indexOf("Explorer") != -1) 
			$("act_des").style.display="block";
		 else
			$("act_des").style.display="table-row";

		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";

	}
	else{	
		traerdatos(0);
		$("sltNombredo").value="";
		//Si la funcion devolvio cero, no existe el registro
		//limpiamos los campos del form	
		$("btnModificar").disabled=true;
		$("btnGuardar").disabled=false;
		$("btnGuardar").onclick=insertar;
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		//imprimimos un aviso de que se trata de un registro nuevo
		$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
		//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
		$("btnModificar").disabled=true;		
	}
}
										
