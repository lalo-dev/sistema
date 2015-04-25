window.onload = inicia;

function inicia() {
	$("btnbuscar").onclick=existe;
	$("btnDirecciones").disabled=true;
	
	//inicializando autocomplete
	$("autoCliente").className = "autocomplete";
	new Ajax.Autocompleter("txtRazonSocial", "autoCliente", "scripts/catalogoClientes.php?operacion=5", {paramName: "caracteres", afterUpdateElement:datosCorresponsales1});

	$("autoClienteC").className = "autocomplete";
	new Ajax.Autocompleter("txtCodigo", "autoClienteC", "scripts/catalogoClientes.php?operacion=5", {paramName: "caracteres",afterUpdateElement:datosCorresponsales2});
	$Ajax("scripts/monedas.php", {onfinish: cargaSucursal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$("btnCancelar").onclick=cancelar;
	deshabilitar();

	if($('hdnCorresponsal').value!=0)
	{	
		$Ajax("scripts/datosCorresponsales.php?operacion=1&codigo="+$("hdnCorresponsal").value, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
				
		//Cambiamos el manejador del boton actualizar para que apunte a la funcion
		//que actualiza el registro
		$("btnModificar").disabled=false;
		$("btnModificar").onclick=actualizar;

		$("btnGuardar").disabled=true;
		$("btnDirecciones").disabled=false;

		$("act_des").style.visibility="visible";
		$("act_des").style.	display="table-row";

		$("btnModificar").disabled=false;
		$("btnModificar").onclick=actualizar;
	
		$("btnCancelar").disabled=false;	
		$("btnbuscar").disabled=true;				
		$("txtCodigo").disabled=true;	
		$("txtRazonSocial").disabled=false;

		//Imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";	

	}

	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("btnGuardar").style.visibility="hidden";
		$("btnModificar").style.visibility="hidden";
		$("btnDirecciones").style.visibility="hidden";
		
		$("btnGuardar").style.display="none";
		$("btnModificar").style.display="none";		
		$("btnDirecciones").style.display="none";	
	}
}
function deshabilitar()
{
	$("btnModificar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnCancelar").disabled=true;
	$("btnDirecciones").disabled=true;
}
function cancelar(){   	
	  location = "corresponsales.php" ;
	   $("act_des").style.visibility="hidden";
	   $("act_des").style.display="none";
}
function datosCorresponsales1() {	
		var url2 = "opc=1&codigo="+$("txtRazonSocial").value;
		$Ajax("scripts/datosCorresponsales.php?operacion=3&"+url2, {onfinish: cveCorresponsal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		}
function datosCorresponsales2() {	
		var url2 = "opc=0&codigo=" + $("txtCodigo").value;		
		$Ajax("scripts/datosCorresponsales.php?operacion=3&"+url2, {onfinish: cveCorresponsal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		}
function cveCorresponsal(campos) {

			var campo = campos[0];
			//$("hdncveCorresponsal").value=campo.cveCorresponsal;
			$("txtCodigo").value=campo.cveCorresponsal;
			$("txtRazonSocial").value=campo.razonSocial
				existe();
		}

//funcion que verifica si existe un registro que tenga como llave el valor capturado
//hace una peticion que devuelve un único valor
function existe() {
			if ($("txtRazonSocial").value!="") {
				var url = "scripts/existe.php?keys=13&table=ccorresponsales&field1=razonSocial&f1value=" + $("txtRazonSocial").value;
				$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
			}else if($("txtCodigo").value!='')
			{
				var url = "scripts/existe.php?keys=13&table=ccorresponsales&field1=cveCorresponsal&f1value=" + $("txtCodigo").value;
				$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});			
			}else {alert('Debe ingresar una Raz\u00F3n Social o un C\u00F3digo.');}
}
var indiceFilaFormulario=1;		
function addContacto(contactos){
 total=contactos[0].total;
  if(document.getElementById("tablaFormulario").rows.length>1){
		var ultima = document.getElementById("tablaFormulario").rows.length;
		for(var j=ultima; j>1; j--){				
	        document.getElementById("tablaFormulario").deleteRow(1);				 		
		}
  }
  if(total!=0){
	if(document.getElementById("tablaFormulario").rows.length>2){	
			var ultima = document.getElementById("tablaFormulario").rows.length;
				for(var j=ultima; j>2; j--){
					document.getElementById("tablaFormulario").deleteRow(2);	
								
			}
	}
	for (var i=0; i<contactos.length; i++){
			var contacto = contactos[i];
			
			 myNewRow = document.getElementById("tablaFormulario").insertRow(-1); 
			 myNewRow.id=indiceFilaFormulario;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" + contacto.nombre +" " + contacto.apellidoPaterno +"</td>";
			  myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" + contacto.telefono +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" + contacto.sucursalCliente +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td><input type='hidden' id='cvesucursal["+indiceFilaFormulario+"]' value='" + contacto.cveDireccion +"'/><input type='button' name='ver["+indiceFilaFormulario+"]'  value='Ver' onclick='removePerson(this)'></td>";
			 indiceFilaFormulario++;
 	
		}
	}
}
							
function addDireccion(direcciones){
if(document.getElementById("tblDirecciones").rows.length>=2){
		var ultima = document.getElementById("tblDirecciones").rows.length;
		for(var j=ultima; j>1; j--){				
	        document.getElementById("tblDirecciones").deleteRow(1);				 		
		}
}
for (var i=0; i<direcciones.length; i++){
	var direccion = direcciones[i];
	
 myNewRow = document.getElementById("tblDirecciones").insertRow(-1); 
 myNewRow.id=indiceFilaFormulario;
 myNewCell=myNewRow.insertCell(-1);
 myNewCell.innerHTML="<td>" + direccion.tipoDireccion +"</td>";
 myNewCell=myNewRow.insertCell(-1);
 myNewCell.innerHTML="<td>" + direccion.calle +"</td>";
 myNewCell=myNewRow.insertCell(-1);
 myNewCell.innerHTML="<td>" + direccion.colonia +"</td>";
 myNewCell=myNewRow.insertCell(-1);
 myNewCell.innerHTML="<td>" + direccion.estado +"</td>";
 myNewCell=myNewRow.insertCell(-1);
 myNewCell.innerHTML="<td>" + direccion.municipio +"</td>";
 myNewCell=myNewRow.insertCell(-1);
 myNewCell.innerHTML="<td>" + direccion.codigoPostal +"</td>";
 myNewCell=myNewRow.insertCell(-1);
 myNewCell.innerHTML="<td><input type='hidden' id='cvesucursal["+indiceFilaFormulario+"]' name='cvesucursal["+indiceFilaFormulario+"]' value='" + direccion.cveDireccion +"'/><input type='button' name='ver["+indiceFilaFormulario+"]'  value='Editar' onclick='editaDireccion(this)'></td>";
 
 indiceFilaFormulario++;
 	
	}
} 
function removePerson(obj){ 
var parentId="cvesucursal[";
var indice=obj.name.lastIndexOf("[");
parentId+=obj.name.substring(indice+1,indice+2)+"]";

 var url2 = $(parentId).value + "&cliente=" + $("txtCodigo").value;					
$Ajax("scripts/datosDirecciones.php?ver=si&sucursal="+url2, {onfinish: addDireccion, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
$("divDirecciones").className="";
}
function editaDireccion(obj){ 
var parentId="cvesucursal[";
var indice=obj.name.lastIndexOf("[");
parentId+=obj.name.substring(indice+1,indice+2)+"]";
var numP=document.getElementById("txthPer").value;
location = "direcciones.php?nP="+numP+"&razon=" + $("txtRazonSocial").value + "&cveCliente=" + $("txtCodigo").value+"&cveDireccion="+$(parentId).value ;
}

function desactivados() {
	$("txtRazonSocial").disabled=false;
	$("btnDirecciones").disabled=true;
	
}
function activados() {
	$("txtCodigo").disabled=true;
	$("txtRazonSocial").disabled=false;
	$("btnbuscar").disabled=true;
	$("btnDirecciones").disabled=false;

}
//esta funcion recibe el valor de la función anterior y lo evalúa	
	function next_existe(ex){
		
		
		//Deshabilitamos la llave primaria y el boton btnbuscar
		$("txtCodigo").disabled=true;
		$("btnbuscar").disabled=true;
		$("btnCancelar").disabled=false;
		
		
		//extraemos el valor retornado por el servidor en un objeto json
		var exx=ex[0];
		var exists = exx.existe;
		//si el valor es mayor que cero, entonces el registro existe
		if (exists > 0){	
			
			//se piden los datos
			
			if($("txtCodigo").value!=''){
				$Ajax("scripts/datosCorresponsales.php?operacion=1&codigo="+$("txtCodigo").value, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			}
			else{
				$Ajax("scripts/datosCorresponsales.php?operacion=1&cveCorresponsal="+$("txtRazonSocial").value, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			}
				
			//Cambiamos el manejador del boton actualizar para que apunte a la funcion
			//que actualiza el registro
			$("btnModificar").disabled=false;
			$("btnModificar").onclick=actualizar;
			
			$("btnGuardar").disabled=true;
			$("btnDirecciones").disabled=false;
			
			$("act_des").style.visibility="visible";
   	        $("act_des").style.	display="table-row";
			
			//Imprimimos un mensaje de actualizando
			$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
		}
		else{	//Si la funcion devolvio cero, no existe el registro
				//limpiamos los campos del form
			var tmp1 = $("txtCodigo").value;		
			var tmp2 = $("txtRazonSocial").value;
			$("form2").reset();
			$("txtRazonSocial").value = tmp2;
			
			$("btnDirecciones").disabled=true;
			$("txtCodigo").disabled=true;
			
			//Agregamos un manejador al boton continuar para que apunte a la funcion
			//que inserta un registro nuevo
			$("btnGuardar").disabled=false;
			$("btnGuardar").onclick=insertar;
			
			$("btnModificar").disabled=true;	
			$("btnDirecciones").disabled=true;
			
			//Imprimimos un aviso de que se trata de un registro nuevo
			$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
			//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
			$("act_des").style.visibility="hidden";
   	        $("act_des").style.display="none";

			
			}
		}
		
	
		
		function insertar(){
			
		if(allgood()){
			var estatus;
			var persona;

			for (var i=0; i<form2.rdoMoral.length; i++)
			{
				if(form2.rdoMoral[i].checked){persona=form2.rdoMoral[i].value;}
			}
			
					       
		 var valores = valores + "&txtRazonSocial="		+$("txtRazonSocial").value		+"&txtNombreComenrcial="		+$("txtNombreComenrcial").value		+"&txtRfc="		+$("txtRfc").value;
valores = valores + "&txtLada="			+$("txtLada").value		+"&txtCurp="	+$("txtCurp").value		+"&txtTelefono="		+$("txtTelefono").value		+"&txtFax="		+$("txtFax").value		+"&txtPaginaWeb="		+$("txtPaginaWeb").value		+"&txtImpuesto="		+$("txtImpuesto").value;
valores = valores 	+"&txtCondicionesP="		+$("txtCondicionesP").value	+ "&slcMonedas="			+$("slcMonedas").value+ "&rdoMoral="			+persona;	
		//var valores = $("form1").serialize();
        var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
valores=valores +usuario;
		$Ajax("scripts/guardarCorresponsal.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
			$("contenedor").className="";
				$("form2").reset();

		}
		
		}
		
		function actualizar(){
			if(allgood()){

		var persona;
			if($("chkActivado").checked)
				 estado=1;
			else estado=0;

			for (var i=0; i<form2.rdoMoral.length; i++)
			{
				if(form2.rdoMoral[i].checked){persona=form2.rdoMoral[i].value;}
			}
				       
		 var valores='';
		 valores = valores + "&txtRazonSocial="		+$("txtRazonSocial").value		+"&txtNombreComenrcial="		+$("txtNombreComenrcial").value		+"&txtRfc="		+$("txtRfc").value;
        valores = valores + "&txtLada="			+$("txtLada").value		+"&txtCurp="	+$("txtCurp").value		+"&txtTelefono="		+$("txtTelefono").value		+"&txtFax="		+$("txtFax").value		+"&txtPaginaWeb="		+$("txtPaginaWeb").value		+"&txtImpuesto="		+$("txtImpuesto").value;
        valores = valores 	+"&txtCondicionesP="		+$("txtCondicionesP").value	+ "&slcMonedas="			+$("slcMonedas").value+ "&rdoMoral="			+persona+ "&estado="			+estado;
        var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value + "&wb_id=" + $("txtCodigo").value;	
        valores=valores +usuario;
//		alert(valores);
		$Ajax("scripts/actualizaCorresponsales.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		$("act_des").style.visDibility="hidden";
		$("act_des").style.display="none";
		$("form2").reset();
		$("txtCodigo").disabled=false;
		
		}
		
			}
		
		function borrar(){

		if (confirm("Confirme que desea borrar")){
		
		var hid ="wb_id=" + $("txtCodigo").value;
		$Ajax("scripts/borraClientes.php", {metodo: $metodo.POST, onfinish: fin, parametros: hid, avisoCargando:"loading"});
								
		}
		
		
		}
	
		function llenaDatos(campos){
	
		//tomamos el primer objeto del json, ya que siempre devolvera un unico registro
			var campo = campos[0];
		//asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
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
			
		for(var idx = 0; idx < $("slcMonedas").options.length; idx ++){
		if($("slcMonedas").options[idx].value == campo.cveMoneda){
			$("slcMonedas").selectedIndex = idx;
			$("slcMonedas").options[idx].selected=true;			
			}			
		}
 
	
		$("txtCodigo").value = campo.cveCorresponsal;
		$("txtRazonSocial").value = campo.razonSocial;
		$("txtNombreComenrcial").value = campo.nombreComercial;
		$("txtRfc").value = campo.rfc;
		$("txtLada").value = campo.lada;
		$("txtCurp").value = campo.curp;
		$("txtTelefono").value = campo.telefono;
		$("txtFax").value = campo.fax;
		$("txtPaginaWeb").value = campo.paginaWeb;
		$("txtImpuesto").value = campo.cveImpuesto;
		$("txtCondicionesP").value = campo.condicionesPago;
		
		//$("txtdiasFactura").value = campo.diasFactura;
		//$("txtDiasCobro").value = campo.diasCobro;
		//$("txtPlazo").value = campo.plazoCobro;
		//$("txaRequisitosC").value = campo.requisitosCobro;
		//$("txaFactura").value = campo.revicionFactura;
		//$("txtProveedor").value = campo.noProveedor;
		
		for (var i=0; i<document.form2.rdoMoral.length; i++)
			{
				if(document.form2.rdoMoral[i].value==campo.tipoCliente){document.form2.rdoMoral[i].checked = true;}
			}
		
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
		var url3 ="cliente=" + campo.cveCorresponsal+"&tabla=ccontactosproveedores&campo=cveCorresponsal";
		$Ajax("scripts/datosContactos.php?"+url3, {onfinish: addContacto, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
		}

		function fin(res){
		 	alert(res);
			location = "corresponsales.php" ;		
		}
		
		function allgood(){
		var notGood = 0;
		
			//if($("txtKg").value == "" || isNaN($("txtKg").value) || parseInt($("txtKg").value) > 10000){$("txtKg").addClassName("invalid"); notGood ++;} else{$("txtKg").className = "";}	
		//if($("txtCodigo").value.length <1){$("txtCodigo").addClassName("invalid"); notGood ++;} else{$("txtCodigo").removeClassName("invalid");}

		if($("txtRazonSocial").value.length < 2){$("txtRazonSocial").addClassName("invalid"); notGood ++;} else{$("txtRazonSocial").removeClassName("invalid");}

		if($("txtImpuesto").value.length < 1){$("txtImpuesto").addClassName("invalid"); notGood ++;} else{$("txtImpuesto").removeClassName("invalid");}

		if($("txtCondicionesP").value.length < 1){$("txtCondicionesP").addClassName("invalid"); notGood ++;} else{$("txtCondicionesP").removeClassName("invalid");}

		if($("slcMonedas").value == ""){$("slcMonedas").addClassName("invalid"); notGood ++;} else {$("slcMonedas").removeClassName("invalid");}

		
		
		
		if(notGood > 0){
			alert("Hay Informacion erronea que ha sido resaltada en color!");
			return false;
			} else
			{
				return true;
			}
			
		}
	function cargaSucursal(airls){
 
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("slcMonedas").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("slcMonedas").options[$("slcMonedas").options.length] = opcion;

	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("slcMonedas").options[$("slcMonedas").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}	

function imprimirCorresponsales()
{
	var win = new Window({className: "mac_os_x", title: "Reporte de Operaciones", top:70, left:100, width:1200, height:500, url: "scripts/reporteCorresponsales.php", showEffectOptions: {duration:1.5}});
	win.show();
}
