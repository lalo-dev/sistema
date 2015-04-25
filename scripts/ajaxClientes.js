window.onload = inicia;

function inicia() {
	
$("btnbuscar").onclick=existe;
	//inicializando autocomplete
	$Ajax("scripts/monedas.php", {onfinish: cargaSucursal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	$("autoCliente").className = "autocomplete";
	new Ajax.Autocompleter("txtRazonSocial", "autoCliente", "scripts/catalogoClientes.php?operacion=4", {paramName: "caracteres", afterUpdateElement:datosClientes1});
	$("autoClienteC").className = "autocomplete";
	new Ajax.Autocompleter("txtCodigo", "autoClienteC", "scripts/catalogoClientes.php?operacion=4", {paramName: "caracteres",afterUpdateElement:datosClientes2});
	$Ajax("scripts/tiposCliente.php", {onfinish: cargaTipos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$("btnCancelar").onclick=cancelar;
	deshabilitar();


	if($('hdnCliente').value!=0)
	{
		        
		//Responde cuando se dió volver , desde contactos y/o direcciones
		var url2 ="scripts/datosClientes.php?operacion=1&codigo="+ $("hdnCliente").value;	
			
		$Ajax(url2, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
		$("btnModificar").disabled=false;
		$("btnModificar").onclick=actualizar;
	
		$("btnCancelar").disabled=false;
		$("btnDirecciones").disabled=false;
		$("btnGuardar").disabled=true;
		$("btnbuscar").disabled=true;
				
		$("txtCodigo").disabled=true;
	
		$("txtRazonSocial").disabled=false;
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	}
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
function deshabilitar()
{
	$("btnModificar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnCancelar").disabled=true;
	$("btnDirecciones").disabled=true;
}

function cancelar(){
    
    	location = "clientes.php" ;
	    $("act_des").style.visibility="hidden";
	    $("act_des").style.display="none";
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
function datosClientes1() {	
		var url2 = "opc=1&codigo="+$("txtRazonSocial").value;
		$Ajax("scripts/datosClientes.php?operacion=4&cveCliente="+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		}
function datosClientes2() {	
		var url2 = "opc=0&codigo="+$("txtCodigo").value;
		$Ajax("scripts/datosClientes.php?operacion=4&cveCliente="+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		}

function cveCliente(campos) {

			var campo = campos[0];
//			$("hdncveCliente").value=campo.cveCliente;
			$("txtCodigo").value=campo.cveCliente;
			$("txtRazonSocial").value=campo.razonSocial;
			existe();
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
function existe() {
	if($("txtRazonSocial").value!=""){
		var url = "scripts/existe.php?keys=7&table=ccliente&field1=razonSocial&f1value=" + $("txtRazonSocial").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}else{ alert('Debe insertar una Razón Social.');}
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
		 myNewCell.innerHTML="<td>" + contacto.lada+" - "+contacto.telefono +"</td>";
		 myNewCell=myNewRow.insertCell(-1);
		 myNewCell.innerHTML="<td>" + contacto.sucursalCliente +"</td>";
		 myNewCell=myNewRow.insertCell(-1);
		 myNewCell.innerHTML="<td><input type='hidden' id='cvesucursal["+indiceFilaFormulario+"]' value='" + contacto.cveDireccion +"'/><input type='button' name='ver["+indiceFilaFormulario+"]'  value='Ver' onclick='removePerson(this)'></td>";
		 indiceFilaFormulario++;
 	
		}
	}
}
var indiceFilaFormulario2=1;
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
 myNewRow.id=indiceFilaFormulario2;
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
 myNewCell.innerHTML="<td align='center'><input type='hidden' id='cvesucursal["+indiceFilaFormulario2+"]' name='cvesucursal["+indiceFilaFormulario2+"]' value='" + direccion.cveDireccion +"'/><input type='button' name='ver["+indiceFilaFormulario2+"]'  value='Editar' onclick='editaDireccion(this)'></td>";

 indiceFilaFormulario2++;
 	
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
	var numP=document.getElementById("txthPer").value;
	location = "direcciones.php?nP="+numP+"&razon=" + $("txtRazonSocial").value + "&cveCliente=" + $("txtCodigo").value+"&cveDireccion="+$(parentId).value+"&tabla=cliente";
}

//Esta funcion recibe el valor de la función anterior y lo evalúa	
	function next_existe(ex){
		
		$("txtRazonSocial").disabled=false;
		$("txtCodigo").disabled=false;
		$("btnbuscar").disabled=true;
		$("btnCancelar").disabled=false;
			
		//Agregaremos evento al Botónd e Modificar y deshabilatermos y habilitaremos botones
		
		//Extraemos el valor retornado por el servidor en un objeto json
		var exx=ex[0];
		var exists = exx.existe;
		var url = exx.cve;

		//Si el valor es mayor que cero, entonces el registro existe
		if (exists > 0){	
		
	        	var url2 ="scripts/datosClientes.php?operacion=1&codigo="+url;
			$Ajax(url2, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			
			$("btnModificar").disabled=false;
			$("btnModificar").onclick=actualizar;
			
			$("btnDirecciones").disabled=false;
			$("btnGuardar").disabled=true;
						
			$("txtCodigo").disabled=true;

			//Imprimimos un mensaje de actualizando
			$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
			
		}
		else{	//Si la funcion devolvio cero, no existe el registro
			//Limpiamos los campos del form
			var tmp = $("txtRazonSocial").value;
			$("form2").reset();
			$("act_des").style.visibility="hidden";
   	        $("act_des").style.display="none";
			$("txtRazonSocial").value =tmp ;
			//Agregamos un manejador al boton continuar para que apunte a la funcion que inserta un registro nuevo
			$("btnGuardar").disabled=false;
			$("btnGuardar").onclick=insertar;
			
			$("btnModificar").disabled=true;			
			$("btnDirecciones").disabled=true;			
			
			$("txtCodigo").disabled=true;
			
			//imprimimos un aviso de que se trata de un registro nuevo
			$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
		

			}
		}
		
	
		
		function insertar(){

			if(allgood()){
				guardarDatos();
			}		
		}

		function guardarDatos(){

		
			var persona;
			for (var i=0; i<document.form2.rdoMoral.length; i++)
			{
				if(document.form2.rdoMoral[i].checked){persona=document.form2.rdoMoral[i].value;}
			}

			/*//Modificar el valor de los text area, para poder interpretarlo posteriormente correctamente					
			//g nos indica que se reemplazarán todas las coincidencias gi significa q es sin importar mayúsicuals y minúsculas
			var regX = /\n/g;
			var regX = /\r\n/g;

			var replaceString ='[br]';
			cadena=$("txaRequisitosC").value;
			txaRequisitosC=cadena.replace(regX,replaceString);

			cadena=$("txaFactura").value;
			txaFactura=cadena.replace(regX,replaceString);*/

			txaRequisitosC=$("txaRequisitosC").value;
			txaFactura=$("txaFactura").value;

			 var valores = valores + "&folioCliente="+$("txtFolio").value+"&txtRazonSocial="		+$("txtRazonSocial").value		+"&txtNombreComenrcial="		+$("txtNombreComenrcial").value		
			 +"&txtRfc="		+$("txtRfc").value;
			valores = valores + "&txtLada="			+$("txtLada").value		+"&txtCurp="	+$("txtCurp").value		
			+"&txtTelefono="		+$("txtTelefono").value		+"&txtFax="		+$("txtFax").value		+"&txtPaginaWeb="		
			+$("txtPaginaWeb").value		+"&txtImpuesto="		+$("txtImpuesto").value;
			valores = valores 	+"&txtCondicionesP="		+$("txtCondicionesP").value	+ "&slcMonedas="			
			+$("slcMonedas").value+ "&rdoMoral="			+persona+ "&txtdiasFactura="		+$("txtdiasFactura").value+"&txtDiasCobro="		
			+$("txtDiasCobro").value+"&txtPlazo="		+$("txtPlazo").value+"&txaRequisitosC="		+txaRequisitosC+"&txaFactura="		
			+txaFactura+"&txtProveedor="		+$("txtProveedor").value+"&tipoCliente="+	$("tipoCliente").value ;	

			var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
			valores=valores +usuario;
			$Ajax("scripts/guardarClientes.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
			$("contenedor").className="";
			$("form2").reset();
		}
		
		function actualizar(){
			
			if(allgood()){
				//Vamos a checar que el NO haya folio mayores al folio que se está ingresando
				var url = "scripts/existe.php?keys=23&folio="+$("txtFolio").value+"&cliente="+$("txtCodigo").value;
				$Ajax(url, {tipoRespuesta: $tipo.JSON ,onfinish: function (datos)
				{
					dato=datos[0];
					existe=dato.existe;
					permiso=dato.permiso;
					if(existe>0){
						if(permiso==0)
						{
							alert("El folio que ingres\u00F3 es incorrecto: hay acuses con folios superiores.");
						}
						else	
						{
							if(confirm("El folio que ingres\u00F3 es incorrecto.\nHay acuses con folios superiores.\u00BFDesea continuar?"))
							{
								modDato();
							}//if
						}//else
					}//if
					else
						modDato();
	   	
				}});//AJax
			}//all
		}
		
		function modDato()
		{
			var persona;
		   	if($("chkActivado").checked)
					 estado=1;
			else estado=0;
			for (var i=0; i<document.form2.rdoMoral.length; i++)
			{
				if(document.form2.rdoMoral[i].checked){persona=document.form2.rdoMoral[i].value;}
			}

			/*//Modificar el valor de los text area, para poder interpretarlo posteriormente correctamente					
			//g nos indica que se reemplazarán todas las coincidencias gi significa q es sin importar mayúsicuals y minúsculas
			var regX = /\n/g;
			var replaceString = '[br]';
			cadena=$("txaRequisitosC").value;
			txaRequisitosC=cadena.replace(regX,replaceString);

			cadena=$("txaFactura").value;
			txaFactura=cadena.replace(regX,replaceString);*/
			txaFactura=$("txaFactura").value;
			txaRequisitosC=$("txaRequisitosC").value;
			 		
			 var valores = valores +  "&folioCliente="+$("txtFolio").value+"&txtCodigo="		+$("txtCodigo").value	 + "&txtRazonSocial="		+$("txtRazonSocial").value		+
			 "&txtNombreComenrcial="		+$("txtNombreComenrcial").value		+"&txtRfc="		+$("txtRfc").value;
			valores = valores + "&txtLada="			+$("txtLada").value		+"&txtCurp="	+$("txtCurp").value		
			+"&txtTelefono="		+$("txtTelefono").value		+"&txtFax="		+$("txtFax").value		+"&txtPaginaWeb="		+$("txtPaginaWeb").value		
			+"&txtImpuesto="		+$("txtImpuesto").value;
			valores = valores 	+"&txtCondicionesP="		+$("txtCondicionesP").value	+ "&slcMonedas="			+$("slcMonedas").value+ "&rdoMoral="			
			+persona+ "&estado="			+estado+"&txtdiasFactura="		+$("txtdiasFactura").value+"&txtDiasCobro="		
			+$("txtDiasCobro").value+"&txtPlazo="		
			+$("txtPlazo").value+"&txaRequisitosC="		+txaRequisitosC+"&txaFactura="		+txaFactura+"&txtProveedor="		
			+$("txtProveedor").value+"&tipoCliente="+	$("tipoCliente").value ;
			var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
			valores=valores +usuario;
			//alert(valores);
			$Ajax("scripts/actualizaClientes.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
			$("act_des").style.visibility="hidden";
			$("act_des").style.display="none";

			$("form2").reset();
		}


		function borrar(){

		if (confirm("Confirme que desea borrar")){
		
		var hid ="wb_id=" + $("txtCodigo").value;
		$Ajax("scripts/borraClientes.php", {metodo: $metodo.POST, onfinish: fin, parametros: hid, avisoCargando:"loading"});
								
		}
		
		
		}
	
		function llenaDatos(campos){
	
			//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
			var campo = campos[0];
			//Asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
						 
			for(var idx = 0; idx < $("slcMonedas").options.length; idx ++){
			if($("slcMonedas").options[idx].value == campo.cveMoneda){
				$("slcMonedas").selectedIndex = idx;
				$("slcMonedas").options[idx].selected=true;			
				}			
			}
			$("act_des").style.visibility="visible";
		        $("act_des").style.display="block";

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
			$("txtProveedor").value = campo.noProveedor;
			$("tipoCliente").value = campo.cveTipoC;
			$("txtFolio").value=campo.folioCliente;
		
			//Ahora sustituiremos los [br] por un salto de línea		
			var regX = /\[br\]/g;
			var replaceString = '\n';
		
			cadena=campo.requisitosCobro;
			txaRequisitosC=cadena.replace(regX,replaceString);		
				
			cadena=campo.revicionFactura;
			txaFactura=cadena.replace(regX,replaceString);		
		
			$("txaRequisitosC").value = txaRequisitosC;
			$("txaFactura").value = txaFactura;
			
			
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
			location = "clientes.php" ;
		
		}
		
		function allgood(){
			var notGood = 0;
			
			if($("txtRazonSocial").value.length < 1){$("txtRazonSocial").className += " invalid"; notGood ++;} else{$("txtRazonSocial").className = "valid";}
			if($("tipoCliente").value==""){$("tipoCliente").className += " invalid"; notGood ++;} else{$("tipoCliente").className = "valid";}		
			if($("txtTelefono").value.length < 5){$("txtTelefono").className += " invalid"; notGood ++;} else{$("txtTelefono").className = "valid";}
			if($("txtProveedor").value.length < 1){$("txtProveedor").className += " invalid"; notGood ++;} else{$("txtProveedor").className = "valid";}

			if($("txtImpuesto").value=="" ||  isNaN($("txtImpuesto").value)){$("txtImpuesto").className += " invalid"; notGood ++;} else{$("txtImpuesto").className = "valid";}
			
			if($("txtFolio").value=="" ||  isNaN($("txtFolio").value)){$("txtFolio").className += " invalid"; notGood ++;} else{$("txtFolio").className = "valid";}

			if(notGood > 0){
				alert("Hay Informaci\u00F3n erronea que ha sido resaltada en color!");
				return false;
			} else { return true;}
			
		}
		
