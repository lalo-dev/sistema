window.onload = inicia;

function inicia() {

	campo=document.getElementById("txtClave");
	campo.focus();
	var url="scripts/catalogoGeneral.php?operacion=12";
	$("divDescripcion").className = "autocomplete";
	
	new Ajax.Autocompleter("txtClave", "divDescripcion", url, {paramName: "caracteres",afterUpdateElement:existe});
	$("btnBuscar").onclick=existe;	
	$("btnCancelar").onclick=habilitar;
	
	$("btnCancelar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnModificar").disabled=true;	
	
	//Total de Bancos
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=9", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	//Cargar los bancos
	$Ajax("scripts/datosBancos.php", {onfinish: addBancos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});	
	
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
	   $("txtClave").disabled=false;
	   
	   //APF JUNIO
	   $("act_des").style.visibility="hidden";
	   $("act_des").style.display="none"; //APF JUNIO
	   $("status").innerHTML="";
	   quitar_invalidos();		
	   campo=document.getElementById("txtClave");
	   campo.focus();	  
	   $Ajax("scripts/catalogoTotales.php?operacion=2&opc=9", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}


function existe() {
		if ($("txtClave").value!=""){
			var url = "scripts/existe.php?keys=25&f1value=" + $("txtClave").value;
			$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
		}else {alert("Ingrese una clave."); document.getElementById("txtClave").focus();}
}
	
function insertar(){
			
		if(allgood()){
			var valores = valores + "&txtNombre="		+$("txtNombre").value+ "&txthNombre="		+$("txthNombre").value;
			var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
			valores=valores +usuario;
			$Ajax("scripts/administraBancos.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		}
}
		
function actualizar(){
	if(allgood()){
		//MPF JUNIO
		if($("chkActivado").checked)
			 estado=1;
		else estado=0;
		var valores = valores + "&txtNombre="		+$("txtNombre").value+ "&txthNombre="		+$("txthNombre").value+ "&txtClave="		+$("txtClave").value+ "&estado="		+estado;
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
		valores=valores +usuario;
		$Ajax("scripts/administraBancos.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});

		//Quitamos el renglon para Activar/Desactivar   
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		$("btnGuardar").disabled=false;	
	}
}
		
function llenaDatos(campos){

	//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var campo = campos[0];
	//Asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
	$("txtClave").value = campo.cveBan;
	$("txtNombre").value = campo.desc;
	$("txthNombre").value = campo.desc;
	
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
	$("tblBancos").className="gridView";
	//Cargar los bancos
	$Ajax("scripts/datosBancos.php", {onfinish: addBancos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});	
}
		
function allgood(){
	var notGood = 0;		
	if($("txtNombre").value == ""){$("txtNombre").addClassName("invalid"); notGood ++;} else{$("txtNombre").removeClassName("invalid");}

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

		$Ajax("scripts/datosGenerales.php?operacion=13&valor="+	$("txtClave").value, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		//cambiamos el manejador del boton actualizar para que apunte a la funcion
		//que actualiza el registro
		$("btnModificar").disabled=false;
		$("btnModificar").disabled=false;
		$("btnModificar").onclick=actualizar;
		$("act_des").style.visibility="visible";
		$("txtClave").disabled=true;
	    if (navigator.appName.indexOf("Explorer") != -1) 
			$("act_des").style.display="block";
		else
			$("act_des").style.display="table-row";
		//el boton guardar no es útil aqui, por lo tanto lo ocultamos
		$("btnGuardar").disabled=true;
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	}
	else{	//Si la funcion devolvio cero, no existe el registro

		//Quitamos función de Activar/Desactivar
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		//limpiamos los campos del form	
	    $("btnGuardar").disabled=false;
		$("btnGuardar").onclick=insertar;
		$("txtClave").disabled=true;
		$("txtClave").value="";
		//imprimimos un aviso de que se trata de un registro nuevo
		$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
		//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
		$("btnModificar").disabled=true;
	}
}
var indiceFilaFormulario=1;
function addBancos(bancos){	

  total=bancos[0].total;
  if(document.getElementById("tblBancos").rows.length>1){
		var ultima = document.getElementById("tblBancos").rows.length;
		for(var j=ultima; j>1; j--){				
	        document.getElementById("tblBancos").deleteRow(1);				 		
		}
  }
  
  if(total==0) 	
  	$("tblBancos").className="oculto";	
  else if(total!=0){

		if(document.getElementById("tblBancos").rows.length>1){
			var ultima = document.getElementById("tblBancos").rows.length;
			for(var j=ultima; j>1; j--){
				document.getElementById("tblBancos").deleteRow(1);				 		
			}
		}
		for (var i=0; i<bancos.length; i++){					
			 var banco = bancos[i];				
			 myNewRow = document.getElementById("tblBancos").insertRow(-1); 
			 myNewRow.id=indiceFilaFormulario;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=banco.cveBanco;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=banco.descripcion;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=banco.estatus;
			 indiceFilaFormulario++;
	
		}
	}	
}
