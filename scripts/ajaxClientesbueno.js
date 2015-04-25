window.onload = inicia;

function inicia() {
$("btnbuscar").onclick=existe;
//inicializando autocomplete
$Ajax("scripts/monedas.php", {onfinish: cargaSucursal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
if($("txtRazonSocial").value!="Razon Social")
	{datosClientes();}
$("autoCliente").className = "autocomplete";
new Ajax.Autocompleter("txtRazonSocial", "autoCliente", "scripts/catalogoClientes.php?operacion=4&tabla=ccliente", {paramName: "caracteres", afterUpdateElement:datosClientes});
$("autoClienteC").className = "autocomplete";
new Ajax.Autocompleter("txtCodigo", "autoClienteC", "scripts/catalogoClientes.php?operacion=4&tabla=ccliente", {paramName: "caracteres",afterUpdateElement:existe});
$Ajax("scripts/tiposCliente.php", {onfinish: cargaTipos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	if($('hdnCliente').value!=0)
            {
                
                //se piden los datos
			var url2 ="scripts/datosClientes.php?operacion=1&codigo="+ $("hdnCliente").value;	
					
			$Ajax(url2, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			
			//cambiamos el manejador del boton actualizar para que apunte a la funcion
			//que actualiza el registro
			$("btnModificar").disabled=false;
			$("btnModificar").onclick=actualizar;
			//agregamos un manejado de evento al boton borrar para que llame a su funcion borrar
			$("btnBorrar").onclick=borrar;
			//mostramos el boton de borrar
			//el boton guardar no es útil aqui, por lo tanto lo ocultamos
			$("btnGuardar").disabled=true;
			activados();
			//imprimimos un mensaje de actualizando
			$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
            }
            $("btnCancelar").onclick=cancelar;
}
function cancelar(){
    
    	
			location = "clientes.php" ;
}

function cargaTipos(airls){
 
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("tipoCliente").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("tipoCliente").options[$("tipoCliente").options.length] = opcion;

	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("tipoCliente").options[$("tipoCliente").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}
function datosClientes() {	
		var url2 = $("txtRazonSocial").value +"&codigo=" + $("txtCodigo").value;					
		$Ajax("scripts/datosClientes.php?operacion=3&cveCliente="+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		}
function cveCliente(campos) {

			var campo = campos[0];
			//$("hdncveCliente").value=campo.cveCliente;
			$("txtCodigo").value=campo.cveCliente;
			$("txtRazonSocial").value=campo.razonSocial
				existe(1);
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

//funcion que verifica si existe un registro que tenga como llave el valor capturado
//hace una peticion que devuelve un único valor
function existe(opc) {
	
//		if ($("txtRazonSocial").value!=""){
	//		if(opc==0)
		//	{
				if ($("txtCodigo").value!=""){
				var url = "scripts/existe.php?keys=1&table=ccliente&field1=cveCliente&f1value=" + $("txtCodigo").value;}
			//}
			//else if(opc==1){
				if ($("txtRazonSocial").value!=""){
				var url = "scripts/existe.php?keys=1&table=ccliente&field1=razonSocial&f1value=" +$("txtRazonSocial").value;} 
//			}
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	//	}
	}
var indiceFilaFormulario=1;		
function addContacto(contactos){
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
 myNewCell.innerHTML="<td><input type='hidden' id='cvesucursal["+indiceFilaFormulario+"]' value='" + contacto.sucursalCliente +"'/><input type='button' name='ver["+indiceFilaFormulario+"]'  value='Ver' onclick='removePerson(this)'></td>";
 indiceFilaFormulario++;
 	
	}
}
							
function addDireccion(direcciones){
 if(document.getElementById("tblDirecciones").rows.length>2){	

	var ultima = document.getElementById("tblDirecciones").rows.length;
		for(var j=ultima; j>2; j--){
				
	             document.getElementById("tblDirecciones").deleteRow(2);	
				 		
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
 myNewCell.innerHTML="<td>" + direccion.telefono +"</td>";
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
 			
$Ajax("scripts/datosDirecciones.php?ver=si&tabla=cliente&sucursal="+url2, {onfinish: addDireccion, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
$("divDirecciones").className="";
}
function editaDireccion(obj){ 
var parentId="cvesucursal[";
var indice=obj.name.lastIndexOf("[");
parentId+=obj.name.substring(indice+1,indice+2)+"]";

location = "direcciones.php?razon=" + $("txtRazonSocial").value + "&cveCliente=" + $("txtCodigo").value+"&cveDireccion="+$(parentId).value+"&tabla=cliente" ;
}
function desactivados() {
	$("txtRazonSocial").disabled=false;
	$("btnBorrar").disabled=true;
	$("btnDirecciones").disabled=true;
	
}
function activados() {
	$("txtCodigo").disabled=true;
	$("txtRazonSocial").disabled=true;
	$("btnBorrar").disabled=false;
	$("btnDirecciones").disabled=false;
	$("btnbuscar").disabled=true;

}
//esta funcion recibe el valor de la función anterior y lo evalúa	
	function next_existe(ex){
		
		//agregamos un manejador de evento al boton cancelar
	
		//deshabilitamos la llave primaria y el boton btnbuscar
		$("txtRazonSocial").disabled=true;
		//extraemos el valor retornado por el servidor en un objeto json
		var exx=ex[0];
		var exists = exx.existe;
		var url = exx.cve;
		//si el valor es mayor que cero, entonces el registro existe
		if (exists > 0){	
		
            if($('hdnCliente').value==0)
            {
                location = "clientes.php?codigo=" +url ;
            }
		}
		else{	//si la funcion devolvio cero, no existe el registro
			//limpiamos los campos del form
			var tmp = $("txtRazonSocial").value;
			$("form2").reset();
			$("txtRazonSocial").value = tmp;
			//agregamos un manejador al boton continuar para que apunte a la funcion
			//que inserta un registro nuevo
			$("btnGuardar").disabled=false;
			$("btnGuardar").onclick=insertar;
			//imprimimos un aviso de que se trata de un registro nuevo
			$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
			//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
			desactivados();
			$("btnModificar").disabled=true;
			}
		}
		
	
		
		function insertar(){
			alert('Wiii');
		if(allgood()){
			var estatus;
			var persona;
			if($("cbxEstatus").checked)
			{estatus=1;}else{estatus=0;}
			for (var i=0; i<form2.rdoMoral.length; i++)
			{
				if(form2.rdoMoral[i].checked){persona=form2.rdoMoral[i].value;}
			}
			
					       
		 var valores = valores + "&txtRazonSocial="		+$("txtRazonSocial").value		+"&txtNombreComenrcial="		+$("txtNombreComenrcial").value		+"&txtRfc="		+$("txtRfc").value;
valores = valores + "&txtLada="			+$("txtLada").value		+"&txtCurp="	+$("txtCurp").value		+"&txtTelefono="		+$("txtTelefono").value		+"&txtFax="		+$("txtFax").value		+"&txtPaginaWeb="		+$("txtPaginaWeb").value		+"&txtImpuesto="		+$("txtImpuesto").value;
valores = valores 	+"&txtCondicionesP="		+$("txtCondicionesP").value	+ "&slcMonedas="			+$("slcMonedas").value+ "&rdoMoral="			+persona+ "&cbxEstatus="			+estatus+"&txtdiasFactura="		+$("txtdiasFactura").value+"&txtDiasCobro="		+$("txtDiasCobro").value+"&txtPlazo="		+$("txtPlazo").value+"&txaRequisitosC="		+$("txaRequisitosC").value+"&txaFactura="		+$("txaFactura").value+"&txtProveedor="		+$("txtProveedor").value+"&tipoCliente="+	$("tipoCliente").value ;	
		//var valores = $("form1").serialize();
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
       
valores=valores +usuario;
 
        $Ajax("scripts/guardarClientes.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
			$("contenedor").className="";
				$("form2").reset();
		}
		
		}
		
		function actualizar(){
			if(allgood()){
		var estatus;
		var persona;
			if($("cbxEstatus").checked)
			{estatus=1;}else{estatus=0;}
			for (var i=0; i<form2.rdoMoral.length; i++)
			{
				if(form2.rdoMoral[i].checked){persona=form2.rdoMoral[i].value;}
			}
				       
		 var valores = valores + "&txtRazonSocial="		+$("txtRazonSocial").value		+"&txtNombreComenrcial="		+$("txtNombreComenrcial").value		+"&txtRfc="		+$("txtRfc").value;
valores = valores + "&txtLada="			+$("txtLada").value		+"&txtCurp="	+$("txtCurp").value		+"&txtTelefono="		+$("txtTelefono").value		+"&txtFax="		+$("txtFax").value		+"&txtPaginaWeb="		+$("txtPaginaWeb").value		+"&txtImpuesto="		+$("txtImpuesto").value;
valores = valores 	+"&txtCondicionesP="		+$("txtCondicionesP").value	+ "&slcMonedas="			+$("slcMonedas").value+ "&rdoMoral="			+persona+ "&cbxEstatus="			+estatus+"&txtdiasFactura="		+$("txtdiasFactura").value+"&txtDiasCobro="		+$("txtDiasCobro").value+"&txtPlazo="		+$("txtPlazo").value+"&txaRequisitosC="		+$("txaRequisitosC").value+"&txaFactura="		+$("txaFactura").value+"&txtProveedor="		+$("txtProveedor").value+"&tipoCliente="+	$("tipoCliente").value ;
var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
valores=valores +usuario;
		$Ajax("scripts/actualizaClientes.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		
		$("form2").reset();
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
			 
		for(var idx = 0; idx < $("slcMonedas").options.length; idx ++){
		if($("slcMonedas").options[idx].value == campo.cveMoneda){
			$("slcMonedas").selectedIndex = idx;
			$("slcMonedas").options[idx].selected=true;			
			}			
		}

 		if(campo.estatus==1)
			{$("cbxEstatus").checked=true;}else{$("cbxEstatus").checked=false;}
	
		$("txtCodigo").value = campo.cveCliente;
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
		$("txtdiasFactura").value = campo.diasFactura;
		$("txtDiasCobro").value = campo.diasCobro;
		$("txtPlazo").value = campo.plazoCobro;
		//$("txaRequisitosC").value = campo.requisitosCobro;
		//$("txaFactura").value = campo.revicionFactura;
		$("txtProveedor").value = campo.noProveedor;
		$("tipoCliente").value = campo.cveTipoC;
		
		for (var i=0; i<document.form2.rdoMoral.length; i++)
			{
				if(document.form2.rdoMoral[i].value==campo.tipoCliente){document.form2.rdoMoral[i].checked = true;}
			}
		
		//imprimimos un mensaje de actualizando
		var url3 ="cliente=" + campo.cveCliente+"&tabla=cliente&campo=cveCliente";
		
		$Ajax("scripts/datosContactos.php?"+url3, {onfinish: addContacto, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
        $("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
		}

		function fin(res){
		  
		alert(res);
		//$("contenedor").className="oculto";
	location = "clientes.php" ;
		
		}
		
		function allgood(){
		var notGood = 0;
		
	
		
		
		
		if(notGood > 0){
			alert("Hay Informacion erronea que ha sido resaltada en color!");
			return false;
			} else
			{
				return true;
			}
			
		}
		