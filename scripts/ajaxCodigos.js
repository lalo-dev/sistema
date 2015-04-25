// JavaScript Document
window.onload = inicia;

function inicia() {
	
	campo=document.getElementById("txtCP");
	campo.focus();
	var respuesta= "scripts/estados.php?pais=156";
	$Ajax(respuesta, {onfinish: cargaEstados, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$Ajax(respuesta, {onfinish: cargaEstados2, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
	$("slcEstado").onchange=llenaMunicipios;
	$("slcEstado2").onchange=llenaMunicipios2;
	$("slcMunicipios").onchange=checaMunicipios;
	$("slcMunicipios2").onchange=checarCP;

	
	$("divCodigo").className = "autocomplete";
	new Ajax.Autocompleter("txtCP", "divCodigo", "scripts/catalogoCP.php?operacion=1", {paramName: "caracteres", afterUpdateElement:existeCP});
	$("txtCP").onchange=existeCP;
	
	$("btnModificar").onclick=actualizar;
	$("btnModificarN").onclick=insertar2;
	$("btnGuardar").onclick=insertar;
	$("btnBorrar").onclick=borrar;
	$("btnModificar").disabled=true;
	$("btnModificarN").disabled=true;
	$("btnBorrar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnCancelar").disabled=true;
	
	$("btnCancelar").onclick=deshabilitar;
	//Total de C.P.
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=7", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("btnGuardar").style.visibility="hidden";
		$("btnModificar").style.visibility="hidden";
		$("btnModificarN").style.visibility="hidden";
		$("btnBorrar").style.visibility="hidden";
		
		$("btnGuardar").style.display="none";
		$("btnModificar").style.display="none";	
		$("btnModificarN").style.display="none";
		$("btnBorrar").style.display="none";	
 	
	}

}
function checarCP()
{
	var url="scripts/catalogoCP.php?operacion=5&estado="+$("slcEstado2").value+"&municipio="+$("slcMunicipios2").value;
	$Ajax(url, {onfinish: llenaTabla, tipoRespuesta: $tipo.JSON});
}
var indiceFilaFormulario=2;
function llenaTabla(datos)
{
  if(document.getElementById("tblCp").rows.length>2){
		var ultima = document.getElementById("tblCp").rows.length;
		for(var j=ultima; j>2; j--){				
	        document.getElementById("tblCp").deleteRow(2);
		}
  }
  totalfin=datos[0].total;
  if(totalfin!=0){
	 	for (var i=0; i<datos.length; i++){
			 var dato = datos[i];
			 myNewRow = document.getElementById("tblCp").insertRow(-1); 
			 myNewRow.id=indiceFilaFormulario;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" +dato.estado+"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" +dato.mun+"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" +dato.colonia+"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" +dato.cp+"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" +dato.clave+"</td>";
			 indiceFilaFormulario++;		
		}
	}

}
function deshabilitar()
{
	elemento=document.getElementById('txtMunicipio');
	elemento.style.visibility="invisible";
	elemento.style.display="none";
	$("status").innetHTML="";	
	$("btnModificar").disabled=true;
	$("btnModificarN").disabled=true;
	$("btnBorrar").disabled=true;	
	$("btnGuardar").disabled=true;
	$("btnCancelar").disabled=true;
	$("slcMunicipios").options.length = 0;	
	var opcion = new Option("Seleccione","");	
	$("slcMunicipios").options[$("slcMunicipios").options.length]=opcion;
	elementos=$$("#form2 input[type=text],#form2 select,#form2 textarea");
    for(i=0;i<elementos.length;i++)
	{ 	
		id=elementos[i].id; 
		if(id!="totalReg"){
			$(id).removeClassName("sltEstadosMuniinvalid");
			$(id).value=""; 
		}
	}
	campo=document.getElementById("txtCP");
	campo.focus();
	//Total de C.P.
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=7", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

}
function existeCP(){
	if($("txtCP").value!=""){
    	var valor=$("txtCP").value;
		var url="scripts/existe.php?keys=16&codigo="+valor;
		$Ajax(url, {onfinish: next_existeCP, tipoRespuesta: $tipo.JSON});
	}
}

function next_existeCP(ex)
{
	$("btnCancelar").disabled=false;
	var exx=ex[0];
	var exists = exx.existe;
	campo=document.getElementById("slcEstado");
	campo.focus();
		//Si el valor es mayor que cero, entonces el registro existe
		
		if (exists > 0)
		{ 
			//$("txtCP").value=codigo;
			var clave = exx.cve;
			$Ajax("scripts/catalogoCP.php?operacion=2&codigo="+$("txtCP").value, {onfinish: cargadatosdes, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			//El registro existe por tanto solo se podrá modificar la Colonia y el CP
			$("btnGuardar").disabled=true;
			$("btnModificar").disabled=false;
			$("btnBorrar").disabled=false;
			$("btnModificarN").disabled=false;
			$("btnGuardar").disabled=true;
		}
		else
		{ 
			$("txthCveCP").value="";
			$("txtColonia").value="";
			$("btnModificarN").disabled=true;
			$("btnModificar").disabled=true;
			$("btnGuardar").disabled=false;	
			
			//Limpiar
			var respuesta= "scripts/estados.php?pais=156";
			$Ajax(respuesta, {onfinish: cargaEstados, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			$("slcMunicipios").options.length = 0;
			var opcion = new Option('Seleccione','');	
			$("slcMunicipios").options[$("slcMunicipios").options.length]=opcion;
			

		}
}
function datosCP(){    
    $Ajax("scripts/catalogoCP.php?operacion=2&codigo="+$("txtCP").value, {onfinish: cargadatosdes2, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});   
}
function cargadatosdes(campos){  
	
	dato=campos[0];
	
	$("txtColonia").value=dato.colonia;
	$("txthColonia").value=dato.colonia;
	$("txthCveCP").value=dato.clave;
	$("txtCP").value=dato.codigoPostal;
	$("txthCP").value=dato.codigoPostal;
	
	var respuesta= "scripts/catalogoCP.php?operacion=3&municipio=" +dato.cveMunicipio+"&estado="+dato.cveEstado;
	$Ajax(respuesta, {onfinish: cargaMunicipiosCP, tipoRespuesta: $tipo.JSON});
}
function cargaMunicipiosCP(airls){
	
	
	$("slcMunicipios").options.length = 0;
	
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);	
		try {$("slcMunicipios").options[$("slcMunicipios").options.length]=opcion;}
		catch (e){alert("Error interno");}
	}
	var opcion = new Option('Otro','Otro');	
	$("slcMunicipios").options[$("slcMunicipios").options.length]=opcion;
	
	var airl = airls[0];	
	$("slcMunicipios").value=airl.mun;
	$("slcEstado").value=airl.estado;
	$("slchMunicipios").value=airl.mun;
	$("slchEstado").value=airl.estado;

}
function llenaMunicipios(){

	 var selec_mun = $("slcMunicipios"); 
	 selec_mun.options.length = 0;   
	 var respuesta= "scripts/municipios.php?municipio=" + $("slcEstado").value;
	 $Ajax(respuesta, {onfinish: cargaMunicipios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
function llenaMunicipios2(){

	 var selec_mun = $("slcMunicipios2"); 
	 selec_mun.options.length = 0;   
	 var respuesta= "scripts/municipios.php?municipio=" + $("slcEstado2").value;
	 $Ajax(respuesta, {onfinish: cargaMunicipios2, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
function checaMunicipios()
{
	elemento=document.getElementById('txtMunicipio');
	if($("slcMunicipios").value=="Otro"){
		elemento.style.visibility="visible";
		elemento.style.display="block";
	}else
	{
		elemento.style.visibility="hidden";
		elemento.style.display="none";
	}
}
function cargaEstados(estados){
	
	$("slcEstado").options.length = 0;
	//Empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("slcEstado").options[$("slcEstado").options.length] = opcion;
	
		for (var i=0; i<estados.length; i++){
			var estado = estados[i];
			var opcion = new Option(estado.desc, estado.id);	
			try {$("slcEstado").options[$("slcEstado").options.length]=opcion;}
			catch (e){alert("Error interno");}
		}
}
function cargaEstados2(estados){
	
	$("slcEstado2").options.length = 0;
	//Empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("slcEstado2").options[$("slcEstado2").options.length] = opcion;
	
		for (var i=0; i<estados.length; i++){
			var estado = estados[i];
			var opcion = new Option(estado.desc, estado.id);	
			try {$("slcEstado2").options[$("slcEstado2").options.length]=opcion;}
			catch (e){alert("Error interno");}
		}
}
function cargaMunicipios(airls){

	$("slcMunicipios").options.length = 0;
	//Empieza la carga de la lista
		
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);	
		try {$("slcMunicipios").options[$("slcMunicipios").options.length]=opcion;}catch (e){alert("Error interno");}
	}
	var opcion = new Option('Otro','Otro');	
	$("slcMunicipios").options[$("slcMunicipios").options.length]=opcion;
	
}
function cargaMunicipios2(airls){

	$("slcMunicipios2").options.length = 0;
	//Empieza la carga de la lista
		
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);	
		try {$("slcMunicipios2").options[$("slcMunicipios2").options.length]=opcion;}catch (e){alert("Error interno");}
	}
	
	
}
function insertar2()
{
	if(confirm("\u00bfEsta seguro que desea dar de alta el C.P.?"))
		insertar();
	else
		return false;
}
function borrar()
{
	if(confirm("\u00bfEsta seguro que desea borrar el C.P.?"))
	{
		var valores="";
		valores = valores+"&txthCveCP="		+$("txthCveCP").value;
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
		valores=valores +usuario;	
		 $Ajax("scripts/administraCP.php?operacion=3", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});				
	}
	else{ return false;}
}
function insertar(){
			
		if(allgood()){
			 var valores="";
			 if($("slcMunicipios").value=="Otro")
			 {
				var Municipio=$("txtMunicipio").value;
				var nuevo=1;
			 }else
			 {
				var Municipio=$("slcMunicipios").value;
				var nuevo=0;
			 }
					 
			 valores = valores+"&nuevo="+nuevo+"&txtColonia="		+$("txtColonia").value+"&txtCP="		+$("txtCP").value+"&slcEstado="+$("slcEstado").value+"&slcMunicipios="+Municipio;	
			 var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
			 valores=valores +usuario;	

			 $Ajax("scripts/administraCP.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});				
		}
}
		
function actualizar(){
		if(allgood()){
			 var valores="";
			 
			  if($("slcMunicipios").value=="Otro")
			 {
				var Municipio=$("txtMunicipio").value;
				var nuevo=1;
			 }else
			 {
				var Municipio=$("slcMunicipios").value;
				var nuevo=0;
			 }
			
			  valores = valores+"&nuevo="+nuevo+"&txtColonia="		+$("txtColonia").value+"&txtCP="		+$("txtCP").value+"&slcEstado="+$("slcEstado").value+"&slcMunicipios="+Municipio+"&txthColonia="		+$("txthColonia").value+"&txthCP="		+$("txthCP").value+"&slchEstado="+$("slchEstado").value+"&slchMunicipios="+$("slchMunicipios").value+"&txthCveCP="+$("txthCveCP").value;		

			 var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
			valores=valores +usuario;

			$Ajax("scripts/administraCP.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		}		
}
		
function fin(res){
	alert(res);
	deshabilitar();
}
		
function allgood(){ 
	var notGood = 0;		
	if($("slcEstado").value == ""){$("slcEstado").className = "sltEstadosMuniinvalid"; notGood ++;} else{$("slcEstado").className = "sltEstadosMunivalid";}
	if($("slcMunicipios").value == ""){$("slcMunicipios").className = "sltEstadosMuniinvalid"; notGood ++;} else{$("slcMunicipios").className = "sltEstadosMunivalid";}
	if($("txtColonia").value == ""){$("txtColonia").className = "sltEstadosMuniinvalid"; notGood ++;} else{$("txtColonia").className = "sltEstadosMunivalid";}	
	if(($("txtCP").value.length<4) || (isNaN($("txtCP").value)) ){$("txtCP").className = "sltEstadosMuniinvalid"; notGood ++;} else{$("txtCP").className = "sltEstadosMunivalid";}

	if(notGood > 0){
		alert("\u00A1Hay Informaci\u00F3n err\u00F3nea que ha sido resaltada en color!");
		return false;
	} else { return true; }
			
}
