window.onload = inicia;

function inicia() {

	campo=document.getElementById("txtrazon");
	campo.focus();
	var url="scripts/catalogoGeneral.php?operacion=10&tabla=csucursales&campo=cveSucursal&campo2=nombre&campo3=cveEmpresa";
	$("divRazon").className = "autocomplete";

	$("btnBuscar").onclick=existe;
	new Ajax.Autocompleter("txtrazon", "divRazon", url, {paramName: "caracteres",afterUpdateElement:existe});

	$("btnCancelar").onclick=cancelar;
	//Total de Sucursales
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=4", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"})
	$Ajax("scripts/empresas.php", {onfinish: cargaEmpresas, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	//Cargar las Sucursales
	$Ajax("scripts/datosSucursales.php", {onfinish: addSucursales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$("btnModificar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnCancelar").disabled=true;

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
var indiceFilaFormulario=1;
function addSucursales(sucursales){	

  total=sucursales[0].total;
  if(document.getElementById("tblSucursales").rows.length>1){
		var ultima = document.getElementById("tblSucursales").rows.length;
		for(var j=ultima; j>1; j--){				
	        document.getElementById("tblSucursales").deleteRow(1);				 		
		}
  }
  if(total==0) 	$("tblSucursales").className="oculto";	
  if(total!=0){
		if(document.getElementById("tblSucursales").rows.length>1){
			var ultima = document.getElementById("tblSucursales").rows.length;
			for(var j=ultima; j>1; j--){
				document.getElementById("tblSucursales").deleteRow(1);				 		
			}
		}
		renglon_extra=false;
		for (var i=0; i<sucursales.length; i++){				

			 var sucursal = sucursales[i];
			 if((!renglon_extra)&&(sucursal.estatus==0))
			 {
				myNewRow = document.getElementById("tblSucursales").insertRow(-1); 
				myNewRow.id=indiceFilaFormulario;
				elemento=document.getElementById(myNewRow.id);
				myNewCell=myNewRow.insertCell(-1);
				myNewCell.colSpan="5";
				myNewCell.innerHTML="<th align='center'>Sucursales asignadas a empresas desactivadas.</th>";
				renglon_extra=true;
				indiceFilaFormulario++;
			 }
				
			 valora=sucursal.cveSucursal+"/"+sucursal.nombre+"/"+sucursal.razonSocial+"/"+sucursal.cveEmpresa;
			 myNewRow = document.getElementById("tblSucursales").insertRow(-1); 
			 myNewRow.id=indiceFilaFormulario;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=sucursal.cveSucursal;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=sucursal.nombre;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=sucursal.cveEmpresa;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=sucursal.razonSocial;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=sucursal.estado;			 
			 myNewCell=myNewRow.insertCell(-1);
			 if(sucursal.estatus==1)	  			 
				 myNewCell.innerHTML="<input type='button' name='Editar["+indiceFilaFormulario+"]'  value='Editar' onclick='existe2(this);'>";
			 else
				 myNewCell.innerHTML="";
			myNewCell.style.textAlign="center";
			 indiceFilaFormulario++;
		}
	}	
}
function cancelar()
{
    $("slcEmpresa").disabled=false;
	//$("txtrazon").disabled=false;	
    $("btnModificar").disabled=true;
    $("btnGuardar").disabled=true;
    $("btnCancelar").disabled=true;
	$("btnBuscar").disabled=false;
	 //APF JUNIO
    $("act_des").style.visibility="hidden";
    $("act_des").style.display="none"; //APF JUNIO
    $("status").innerHTML="";
	quitar_invalidos();
	campo=document.getElementById("txtrazon");
	campo.focus();
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=4", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"})
	//Cargar las Sucursales
	$Ajax("scripts/datosSucursales.php", {onfinish: addSucursales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
function cargaEmpresas(empresas){
	//Borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcEmpresa").options.length = 0;
	//Empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("slcEmpresa").options[$("slcEmpresa").options.length] = opcion;
	
	for (var i=0; i<empresas.length; i++){
		var empresa = empresas[i];
		var opcion = new Option(empresa.desc, empresa.id);
		try {$("slcEmpresa").options[$("slcEmpresa").options.length]=opcion;}
		catch (e){alert("Error interno");}
	}
}

function existe() {
	if ($("txtrazon").value!=""){
		valores=$("txtrazon").value.split(" - ");
		var url = "scripts/existe.php?keys=2&table=csucursales&field1=cveSucursal&f1value=" + valores[0]+"&field2=cveEmpresa&f2value=" + valores[3];
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}else { alert("Ingrese un nombre.");}
}
function existe2(val) {
		columna=val.parentNode;
		renglon=columna.parentNode;
		//Asignamos un id al renglon, para poder ocuparlo
		renglon.id="renglontmp";
		elementos=$$("#renglontmp td");
		val=elementos[0].innerHTML+" - "+elementos[1].innerHTML+" - "+elementos[3].innerHTML+" - "+elementos[2].innerHTML;
		valores=val.split(" - ");
		//Quitamos el id al renglon
		renglon.id="";
		var url = "scripts/existe.php?keys=2&table=csucursales&field1=cveSucursal&f1value=" + valores[0]+"&field2=cveEmpresa&f2value=" + valores[3];
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
}
function insertar(){		
	if(allgood()){
		var valores = valores + "&txtrazon="		+$("txtrazon").value		+"&slcEmpresa="		+$("slcEmpresa").value	;
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
		valores=valores +usuario;   
		$Ajax("scripts/administraSucursales.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
						
	}
}
function actualizar(){
		
	if(allgood()){
		 //APF JUNIO
		//Quitamos el renglon para Activar/Desactivar   
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		//MPF JUNIO
		if($("chkActivado").checked)
			 estado=1;
		else estado=0;
		 
		 var valores = valores +"&hdnClave="		+$("hdnClave").value+ "&txtrazon="		+$("txtrazon").value		+"&slcEmpresa="		+$("slcEmpresa").value+"&hdnClaveEm="		+$("hdnClaveEm").value+ "&txthrazon="		+$("txthrazon").value;
		 var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value+"&estado="		+estado;	
		 valores=valores +usuario;   
		 $Ajax("scripts/administraSucursales.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
						
	}
}	

function llenaDatos(campos){
	//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var campo = campos[0];
	//asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
	$("txtrazon").value = campo.nombre;
	$("txthrazon").value = campo.nombre;	
	$("hdnClave").value = campo.cveSucursal;
	$("slcEmpresa").value=campo.cveEmpresa;
	$("hdnClaveEm").value = campo.cveEmpresa;
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
	cancelar();			
}
		
function allgood(){
	var notGood = 0;
	if($("txtrazon").value == ""){$("txtrazon").addClassName("invalid"); notGood ++;} else{$("txtrazon").removeClassName("invalid");}
	if($("slcEmpresa").value == ""){$("slcEmpresa").addClassName("invalid"); notGood ++;} else{$("slcEmpresa").removeClassName("invalid");}
	
	if(notGood > 0){
		alert("Hay Informacion erronea que ha sido resaltada en color!");
		return false;
	} else {return true;}
	
}
function next_existe(ex){
	//Extraemos el valor retornado por el servidor en un objeto json
	var exx=ex[0];
	var exists = exx.existe;
	var sucursal=exx.campo1;
	var empresa=exx.campo2;
	$("btnBuscar").disabled=true;
	$("btnCancelar").disabled=false;
	campo=document.getElementById("txtrazon");
	campo.focus();
	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0){

		$Ajax("scripts/datosGenerales.php?operacion=11&valor="+	sucursal+"&valor2="+empresa, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

		//cambiamos el manejador del boton actualizar para que apunte a la funcion
		//que actualiza el registro
		 $("btnModificar").disabled =false;
		 $("btnModificar").onclick  =actualizar;			 
		 $("btnGuardar").disabled   =true;
		 $("act_des").style.visibility="visible";
		 if (navigator.appName.indexOf("Explorer") != -1) 
			$("act_des").style.display="block";
		 else
			$("act_des").style.display="table-row";
		 //imprimimos un mensaje de actualizando
		 $("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	}
	else{	
		//si la funcion devolvio cero, no existe el registro
		$("slcEmpresa").disabled=false;
		//limpiamos los campos del form	
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		$("btnGuardar").onclick=insertar;
		//imprimimos un aviso de que se trata de un registro nuevo
		$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
		//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
		$("btnModificar").disabled=true;
		$("btnModificar").disabled=true;
		$("btnGuardar").disabled=false;
	}
}
			
