window.onload = inicia;

function inicia() {

	campo=document.getElementById("txtClave");
	campo.focus();
	var url="scripts/catalogoGeneral.php?operacion=4&tabla=clineasaereas&campo2=descripcion&campo=cveLineaArea";
	$("divDescripcion").className = "autocomplete";
	
	new Ajax.Autocompleter("txtClave", "divDescripcion", url, {paramName: "caracteres",afterUpdateElement:existe});
	$("btnBuscar").onclick=existe;	
	$("btnCancelar").onclick=habilitar;
	
	$("btnCancelar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnModificar").disabled=true;	
	
	//Total de Líneas Aereas
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=0", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	//Cargar las líneas áereas
	$Ajax("scripts/datosAerolineas.php", {onfinish: addLineas, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
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

function habilitar(){
       $("btnModificar").disabled=true;
       $("btnGuardar").disabled=true;
 	   $("btnBuscar").disabled=false;
	   $("btnCancelar").disabled=true;
	   //APF JUNIO
	   $("act_des").style.visibility="hidden";
	   $("act_des").style.display="none"; //APF JUNIO
	   $("status").innerHTML="";
	   quitar_invalidos();		
	   campo=document.getElementById("txtClave");
	   campo.focus();	  
	   $Ajax("scripts/catalogoTotales.php?operacion=2&opc=0", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}


function existe() {
		if ($("txtClave").value!=""){
			var url = "scripts/existe.php?keys=1&table=clineasaereas&field1=cveLineaArea&f1value=" + $("txtClave").value;
			$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
		}else {alert("Ingrese una Aerol\u00EDnea."); document.getElementById("txtClave").focus();}
	}
function insertar(){
			
		if(allgood()){
			 var valores = valores + "&txtClave="		+$("txtClave").value		+"&txtDescripcion="		+$("txtDescripcion").value		+"&txtContacto="		+$("txtContacto").	value+"&txtTelefono="		+$("txtTelefono").value;
    	     var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
			valores=valores +usuario;
         	$Ajax("scripts/administraAerolineas.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		}
}
		
function actualizar(){
	if(allgood()){
		//MPF JUNIO
		if($("chkActivado").checked)
			 estado=1;
		else estado=0;
		var valores = valores + "&txthClave="		+$("txthClave").value+ "&txtClave="		+$("txtClave").value		+"&txtDescripcion="		+$("txtDescripcion").value		+"&txtContacto="		+$("txtContacto").value+"&txtTelefono="		+$("txtTelefono").value+"&estado="		+estado;
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
		valores=valores +usuario;
		$Ajax("scripts/administraAerolineas.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		//APF JUNIO
		//Quitamos el renglon para Activar/Desactivar   
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		$("btnGuardar").disabled=false;
	
	}
}
		
function llenaDatos(campos){

	//tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var campo = campos[0];
	//asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
	$("txtClave").value = campo.cveLineaArea;
	$("txthClave").value = campo.cveLineaArea;
	$("txtDescripcion").value = campo.descripcion;
	$("txtContacto").value = campo.contacto;	
	$("txtTelefono").value = campo.telefono;	
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
	
	//APF JUNIO
	$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
}

function fin(res){
	alert(res);
	habilitar();
	//Cargar las líneas áereas
	$Ajax("scripts/datosAerolineas.php", {onfinish: addLineas, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
		
function allgood(){
	var notGood = 0;		
	if($("txtDescripcion").value == ""){$("txtDescripcion").addClassName("invalid"); notGood ++;} else{$("txtDescripcion").removeClassName("invalid");}
	if($("txtClave").value == ""){$("txtClave").addClassName("invalid"); notGood ++;} else{$("txtClave").removeClassName("invalid");}

	if(notGood > 0){
		alert("\u00A1Hay Informaci\u00F3n err\u00F3nea que ha sido resaltada en color!");
		return false;
	} else {return true;}
		
}
function next_existe(ex){
	
	//extraemos el valor retornado por el servidor en un objeto json
	var exx=ex[0];
	var exists = exx.existe;
	$("btnBuscar").disabled=true;
	$("btnCancelar").disabled=false;
	campo=document.getElementById("txtClave");
	campo.focus();
	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0){			

		$Ajax("scripts/datosGenerales.php?operacion=4&valor="+	$("txtClave").value, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		//cambiamos el manejador del boton actualizar para que apunte a la funcion
		//que actualiza el registro
		$("btnModificar").disabled=false;
		$("btnModificar").disabled=false;
		$("btnModificar").onclick=actualizar;
		$("act_des").style.visibility="visible";
	    if (navigator.appName.indexOf("Explorer") != -1) 
			$("act_des").style.display="block";
		else
			$("act_des").style.display="table-row";
		//el boton guardar no es útil aqui, por lo tanto lo ocultamos
		$("btnGuardar").disabled=true;
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	}
	else{	//si la funcion devolvio cero, no existe el registro
		//Quitamos función de Activar/Desactivar
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		//limpiamos los campos del form	
	   $("btnGuardar").disabled=false;
		$("btnGuardar").onclick=insertar;
		//imprimimos un aviso de que se trata de un registro nuevo
		$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
		//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
		$("btnModificar").disabled=true;
	}
}
var indiceFilaFormulario=1;
function addLineas(lineas){	

  total=lineas[0].total;
  if(document.getElementById("tblLineas").rows.length>1){
		var ultima = document.getElementById("tblLineas").rows.length;
		for(var j=ultima; j>1; j--){				
	        document.getElementById("tblLineas").deleteRow(1);				 		
		}
  }
  if(total==0) 	$("tblLineas").className="oculto";	
  else if(total!=0){
		if(document.getElementById("tblLineas").rows.length>1){
			var ultima = document.getElementById("tblLineas").rows.length;
			for(var j=ultima; j>1; j--){
				document.getElementById("tblLineas").deleteRow(1);				 		
			}
		}
		for (var i=0; i<lineas.length; i++){					
			 var linea = lineas[i];				
			 myNewRow = document.getElementById("tblLineas").insertRow(-1); 
			 myNewRow.id=indiceFilaFormulario;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=linea.empresa;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=linea.cveLinea;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=linea.descripcion;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=linea.contacto;
			 indiceFilaFormulario++;
	
		}
	}	
}
