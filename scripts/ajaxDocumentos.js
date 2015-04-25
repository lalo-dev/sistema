window.onload = inicia;

function inicia() {
	campo=document.getElementById("txtTipoDoc");
	campo.focus();
	var url="scripts/catalogoGeneral.php?operacion=11&tabla=cfoliosdocumentos&campo=descripcion&campo2=tipoDocumento&campo3=cveDocumento";
	$("divClave").className = "autocomplete";
	
	new Ajax.Autocompleter("txtTipoDoc", "divClave", url, {paramName: "caracteres",afterUpdateElement:existe});
	$("btnBuscar").onclick=existe;
	//Total de Tipos de Documentos
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=6", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$("btnCancelar").onclick=cancelar;
	$("btnModificar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnCancelar").disabled=true;

	$("sltTipoDoc").onchange=verificarTipo;
	
	
	//Cargar los Documentos
	$Ajax("scripts/datosDocumentos.php", {onfinish: addDocumentos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	//Carga los Documentos en un select
	$Ajax("scripts/datosDocumentos.php", {onfinish: cargaDocumentos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
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
function verificarTipo()
{
	var valor=$("sltTipoDoc").value;
	if(valor=="Otro")
	{
		$("txtOtro").removeClassName("ocultoOtro");
	} else  $("txtOtro").addClassName("ocultoOtro");
}
function cargaDocumentos(folios){
	//Borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("sltTipoDoc").options.length = 0;
	//Empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("sltTipoDoc").options[$("sltTipoDoc").options.length] = opcion;
		
	for (var i=0; i<folios.length; i++){
		var folio = folios[i];
		var opcion = new Option(folio.tipoDocumento, folio.tipoDocumento);
	
		try {$("sltTipoDoc").options[$("sltTipoDoc").options.length]=opcion;}
		catch (e){alert("Error interno");}
	}
	
	var opcion = new Option("Otro","Otro");
	$("sltTipoDoc").options[$("sltTipoDoc").options.length] = opcion;
}

var indiceFilaFormulario=1;
function addDocumentos(documentos){	

  total=documentos[0].total;
  if(document.getElementById("tblDocumentos").rows.length>1){
		var ultima = document.getElementById("tblDocumentos").rows.length;
		for(var j=ultima; j>1; j--){				
	        document.getElementById("tblDocumentos").deleteRow(1);				 		
		}
  }
  if(total==0) 	$("tblDocumentos").className="oculto";	
  if(total!=0){
		if(document.getElementById("tblDocumentos").rows.length>1){
			var ultima = document.getElementById("tblDocumentos").rows.length;
			for(var j=ultima; j>1; j--){
				document.getElementById("tblDocumentos").deleteRow(1);				 		
			}
		}
		for (var i=0; i<documentos.length; i++){					
			 var documento = documentos[i];				
			 myNewRow = document.getElementById("tblDocumentos").insertRow(-1); 
			 myNewRow.id=indiceFilaFormulario;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=documento.tipoDocumento;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=documento.descripcion;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=documento.folio;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=documento.estado;
			 indiceFilaFormulario++;
	
		}
	}	
}
	
function cancelar()
{
	$("btnModificar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnCancelar").disabled=true;
	$("btnBuscar").disabled=false;
	$("act_des").style.visibility="hidden";
	$("act_des").style.display="none";
	$("status").innerHTML="";
	quitar_invalidos();		
	campo=document.getElementById("txtTipoDoc");
	campo.focus();
	$("txtOtro").addClassName("ocultoOtro");
    $("txtTipoDoc").disabled=false;
	$("sltTipoDoc").disabled=false;
	$Ajax("scripts/datosDocumentos.php", {onfinish: cargaDocumentos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=6", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	//Cargar los Documentos
	$Ajax("scripts/datosDocumentos.php", {onfinish: addDocumentos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

}

function existe() {	
	if ($("txtTipoDoc").value!=""){
		var texto=$("txtTipoDoc").value;
		var valor=texto.split(" - ");					
		$("txtTipoDoc").value=valor[0];
		var url = "scripts/existe.php?keys=1&table=cfoliosdocumentos&field1=cveDocumento&f1value=" + $("txtTipoDoc").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}else{ alert("Ingrese un Tipo de Documento."); document.getElementById("txtTipoDoc").focus();}
}

function insertar(){
			
	if(allgood()){
		
		if(confirm("\u00bfEl tipo de Documento es Correcto (No podr\u00E1 ser modificado posteriormente)?"))
		{
			var valores = valores+"&txtFolio="		+$("txtFolio").value		+"&txtDescripcion="		+$("txtDescripcion").value		+"&sltTipoDoc="		+$("sltTipoDoc").value+ "&txtOtro="		+$("txtOtro").value;
			var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
			valores=valores +usuario;
	
			$Ajax("scripts/administrarDocumentos.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		}
						
	}
		
}
		
function actualizar(){
	if(allgood()){
		if($("chkActivado").checked)
			 estado=1;
		else estado=0;	 
		if($("sltTipoDoc").value=="Otro")
			 var nuevo=1;
		else var nuevo=0;
		var continuar=true;		
		
		if(nuevo==1)
		{
			if(confirm("\u00bfEl tipo de Documento es Correcto (No podr\u00E1 ser modificado posteriormente)?"))
				var continuar=true;
			else var continuar=false;
		}
		
		if(continuar){
			var valores = valores + "&nuevo="		+nuevo+	"&txtFolio="		+$("txtFolio").value		+"&txtDescripcion="		+$("txtDescripcion").value		+"&sltTipoDoc="		+$("sltTipoDoc").value + "&txtOtro="		+$("txtOtro").value+"&hdnClave="		+$("hdnClave").value+"&estado="+estado+"&hdnTipo="+$("hdnTipo").value; 		
	
	
			var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
			var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
			valores=valores +usuario;
			
			$Ajax("scripts/administrarDocumentos.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
			$("txtTipoDoc").disabled=false;		
			//Quitamos el renglon para Activar/Desactivar   
			$("act_des").style.visibility="hidden";
			$("act_des").style.display="none";
		}
	}

}
		
	
function llenaDatos(campos){

	//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var campo = campos[0];
	//asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
	$("txtFolio").value = campo.folio;
	$("txtDescripcion").value = campo.descripcion;
	$("txtTipoDoc").value = campo.cveDocumento;	
	$("hdnTipo").value = campo.tipoDocumento;
	$("hdnClave").value = campo.cveDocumento;
	$("sltTipoDoc").value = campo.tipoDocumento;
	//Checar si se trata de una Factura, Vale o Nota, no permitirá cambiar el concepto
	if((campo.tipoDocumento=="FAC")||(campo.tipoDocumento=="NOTAS")||(campo.tipoDocumento=="VALE")||(campo.tipoDocumento=="CONTR")||(campo.tipoDocumento=="PAG"))
	{
		alert("El tipo de Documento, para este Concepto ("+campo.tipoDocumento+") no podr\u00E1 ser modificado.");
		$("sltTipoDoc").disabled=true;
		if(campo.tipoDocumento!="FAC")
			$("txtDescripcion").disabled=true;
	}else	{$("sltTipoDoc").disabled=false; $("txtDescripcion").disabled=false;}
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
	$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
}

function fin(res){
	alert(res);
	cancelar();
}
		
function allgood(){
	var notGood = 0;		
	if($("sltTipoDoc").value=="Otro"){
		if($("txtOtro").value == ""){$("txtOtro").className = "invalid"; notGood ++;} else{$("txtOtro").removeClassName("invalid");}
	}
	if($("sltTipoDoc").value == ""){$("sltTipoDoc").className = "invalid"; notGood ++;} else{$("sltTipoDoc").removeClassName("invalid");}
	if($("txtTipoDoc").value == ""){$("txtTipoDoc").className = "invalid"; notGood ++;} else{$("txtTipoDoc").removeClassName("invalid");}
	if($("txtDescripcion").value == ""){$("txtDescripcion").className = "invalid"; notGood ++;} else{$("txtDescripcion").removeClassName("invalid");}


	if(notGood > 0){
		alert("\u00A1Hay Informaci\u00F3n err\u00F3nea que ha sido resaltada en color!");
		return false;
	} else	{return true;}
	
}
function next_existe(ex){

	//extraemos el valor retornado por el servidor en un objeto json
	var exx=ex[0];
	var exists = exx.existe;
	campo=document.getElementById("txtTipoDoc");
	campo.focus();
    $("btnCancelar").disabled=false;
    $("btnBuscar").disabled=true;
    $("txtTipoDoc").disabled=true;
	
	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0){			
		$Ajax("scripts/datosGenerales.php?operacion=2&valor="+	$("txtTipoDoc").value, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		//cambiamos el manejador del boton actualizar para que apunte a la funcion
		//que actualiza el registro
		$("btnGuardar").disabled=true;
		$("act_des").style.visibility="visible";
	    if (navigator.appName.indexOf("Explorer") != -1) 
			$("act_des").style.display="block";
		else
			$("act_des").style.display="table-row";
		$("btnModificar").disabled=false;
		$("btnModificar").onclick=actualizar;
		//agregamos un manejado de evento al boton borrar para que llame a su funcion borrar
		$("btnBorrar").onclick=borrar;			
		//mostramos el boton de borrar
		//el boton guardar no es útil aqui, por lo tanto lo ocultamos	
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	
	}
	else{	//si la funcion devolvio cero, no existe el registro
		//Colocamos la nueva clave
		$Ajax("scripts/catalogoTotales.php?operacion=3", {onfinish: cargarNueva, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
		$("sltTipoDoc").value="Otro";
		$("sltTipoDoc").disabled=true;
		verificarTipo();
		
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		//limpiamos los campos del form		
		//imprimimos un aviso de que se trata de un registro nuevo
		$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
		//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
		$("btnModificar").disabled=true;
		$("btnGuardar").disabled=false;
		$("btnGuardar").onclick=insertar;
	
	}
}
function cargarNueva(valores)
{
	valor=valores[0];
	$("txtTipoDoc").value=(parseInt(valor.clave)+1);
}
			
