window.onload = inicia;

function inicia() {

	campo=document.getElementById("txtClave");
	campo.focus();
	var url="scripts/catalogoGeneral.php?operacion=7&tabla=cusuarios&campo=cveUsuario";
	$("divClave").className = "autocomplete";
	
	new Ajax.Autocompleter("txtClave", "divClave", url, {paramName: "caracteres",afterUpdateElement:existe});
	$("btnBuscar").onclick=existe;	
	
	$Ajax("scripts/empresas.php", {onfinish: cargaEmpresas, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$("slcEmpresa").onchange=sucursales;
	$("slcPerfil").onchange=estaciones;
	$("slcEstacion").onchange=estacionesel;
	$("btnCancelar").onclick=cancelar;
	
	$("btnModificar").disabled=true;
	$("btnGuardar").disabled=true;					
	$("btnCancelar").disabled=true;	
	$("btnBorrar").disabled=true;	

	//Total de Usuarios
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=8", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("btnGuardar").style.visibility="hidden";
		$("btnModificar").style.visibility="hidden";
		$("btnBorrar").style.visibility="hidden";
		
		$("btnGuardar").style.display="none";
		$("btnModificar").style.display="none";
		$("btnBorrar").style.display="none";		
	
	}
}

function estacionesel()
{
	$("txthClave").value=$("slcEstacion").value;
}

function cancelar()
{
	$("txtClave").disabled=false;
	$("act_des").style.visibility="hidden";
	$("act_des").style.display="none";
	$("btnModificar").disabled=true;
	$("btnGuardar").disabled=true;					
	$("btnBorrar").disabled=true;						
	$("btnCancelar").disabled=true;					
	$("btnBuscar").disabled=false;						
	$("txtClave").disabled=false;
	$("lblTexto").innerHTML="";
	$("slcEstacion").className='oculto';
	$("txtcveCliente").className='oculto';
	$("status").innerHTML="";
	quitar_invalidos();		
	campo=document.getElementById("txtClave");
	campo.focus();	  
	$Ajax("scripts/catalogoTotales.php?operacion=2&opc=8", {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
function sucursales(){
       $Ajax("scripts/catalogoSucursales.php?empresa="+$("slcEmpresa").value, {onfinish: cargaSucursales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

}
function datosClientes1() 
{	
	var url2 = "opc=1&codigo="+$("txtcveCliente").value;
	$Ajax("scripts/datosClientes.php?operacion=4&cveCliente="+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function cveCliente(campos) {

	var campo = campos[0];
	$("txtcveCliente").value=campo.cveCliente;
	$("txthClave").value=campo.cveCliente;
}

function estaciones(){
   if($("slcPerfil").value=='Corresponsal')
    {
          $("slcEstacion").className='';
   	  $("txtcveCliente").className='oculto1';
	  $("lblTexto").innerHTML="*Estaci\u00F3n";
	  $Ajax("scripts/Sucursales.php", {onfinish: cargaSucursal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
    }
    else if($("slcPerfil").value=='Cliente')
    {
	$("autoCliente").className = "autocomplete";
	new Ajax.Autocompleter("txtcveCliente", "autoCliente", "scripts/catalogoClientes.php?operacion=4", {paramName: "caracteres", 	   afterUpdateElement:datosClientes1});           
          $("slcEstacion").className='oculto1';
   	  $("txtcveCliente").className='';
	  $("lblTexto").innerHTML="*Cve. cliente";
    }	
   else
   {  	
          $("slcEstacion").className='oculto1';
   	  $("txtcveCliente").className='oculto1';
	$("lblTexto").innerHTML="";
    }
}
function cargaSucursal(airls){
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcEstacion").options.length = 0;
	//empieza la carga de la lista
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);

		try {
			$("slcEstacion").options[$("slcEstacion").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
}
function existe() {	
	if ($("txtClave").value!=""){
		var url = "scripts/existe.php?keys=1&table=cusuarios&field1=cveUsuario&f1value=" + $("txtClave").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}else{ alert("Ingrese una Clave de Usuario");}
}

function cargaEmpresas(empresas){
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcEmpresa").options.length = 0;
	//empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("slcEmpresa").options[$("slcEmpresa").options.length] = opcion;
	for (var i=0; i<empresas.length; i++){
		var empresa = empresas[i];
		var opcion = new Option(empresa.desc, empresa.id);

		try {
		$("slcEmpresa").options[$("slcEmpresa").options.length]=opcion;
 		}catch (e){alert("Error interno");}
	}
}
function cargaSucursales(sucursales){
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcSucursal").options.length = 0;
	
	//empieza la carga de la lista

	for (var i=0; i<sucursales.length; i++){
		var sucursal = sucursales[i];
		var opcion = new Option(sucursal.desc, sucursal.id);

		try {
		$("slcSucursal").options[$("slcSucursal").options.length]=opcion;
        }catch (e){alert("Error interno");}
	}
	
}
function insertar(){
			
	
	if(allgood()){
	
		guardar=true;
		if($("slcPerfil").value=="Administrador")
		{
			if(!confirm("\u00BFEst\u00E1 seguro que desea asignar el Perfil de Administrador\n(Este perfil puede realizar todo tipo de transacciones)?"))
			 guardar=false;
		}

		
		if(guardar){

			if($("slcPerfil").value=='Corresponsal')
				estacion=$("slcEstacion").value;
			else if($("slcPerfil").value=='Cliente')
				estacion=$("txtcveCliente").value;
			else estacion='';
			var estatus;
			estatus=1;						       
			var valores = valores + "&txtClave="		+$("txtClave").value		+"&txtNombre="		+$("txtNombre").value		+"&txtNick="		+$("txtNick").value;
			valores = valores +"&slcPerfil="	+$("slcPerfil").value		+"&txtApellidoMaterno="		+$("txtApellidoMaterno").value		+"&txtApellidoPaterno="		+$("txtApellidoPaterno").value		+"&txtAreas="		+$("txtAreas").value		+"&txtDepartamento="		+$("txtDepartamento").value+ "&empresa="		+$("slcEmpresa").value+ "&sucursal="		+$("slcSucursal").value;
			valores = valores 	+"&txtPassword="		+$("txtPassword").value+"&estatus="+estatus;
			var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("slcEmpresa").value+  "&sucursal="+$("slcSucursal").value+  "&estacion="+estacion;	
			valores=valores +usuario;

			if(($("slcPerfil").value=="Corresponsal"||$("slcPerfil").value=="Cliente") && ($("btnGuardar").disabled==false))
			{
				if($("slcPerfil").value=="Corresponsal") var valor=$("slcEstacion").value;
				else var valor=$("txtcveCliente").value;
				var url = "scripts/existe.php?keys=21&f1value=" + valor;
				$Ajax(url, {tipoRespuesta: $tipo.JSON,onfinish: function(datos)
										{
										   if(datos[0].existe>0){
										  	if($("slcPerfil").value=="Corresponsal")
												var mensaje="La estaci\u00F3n ya ha sido asignada a "+datos[0].existe+" usuario(s),\u00BFDesea continuar?";
											else
												var mensaje="El cliente ya ha sido asignado a "+datos[0].existe+" usuario(s),\u00BFDesea continuar?";
											if(confirm(mensaje))	
											{
												$Ajax("scripts/administraUsuarios.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
											}
										  }else //si aún no seha dado de alta un usuario con ese cliente o estación
										  {
											$Ajax("scripts/administraUsuarios.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"}); 
										  }
										}
				});	
			}else{		//Si es un perfil distinto a Cliente o Corresponsal
				$Ajax("scripts/administraUsuarios.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
			}
		}
						
	}
}
		
function actualizar(){
	if(allgood()){
		
		
		if($("slcPerfil").value=="Administrador")
		{
			if(confirm("\u00BFEst\u00E1 seguro que desea asignar el Perfil de Administrador\n(Este perfil puede realizar todo tipo de transacciones)?"))
			{ guardar=true; }else { guardar=false;}
		}
		else guardar=true;
		
		if(guardar){
			if($("slcPerfil").value=='Corresponsal')
				estacion=$("slcEstacion").value;
			else if($("slcPerfil").value=='Cliente')
				estacion=$("txtcveCliente").value;
			else estacion='';

			if($("chkActivado").checked)
				estado=1;
			else estado=0;
			
			var valores = valores + "&txtClave="		+$("txtClave").value		+"&txtNombre="		+$("txtNombre").value		+"&txtNick="		+$("txtNick").value+"&txthNick="		+$("txthNick").value;
			valores = valores +"&slcPerfil="	+$("slcPerfil").value		+"&txtApellidoMaterno="		+$("txtApellidoMaterno").value		+"&txtApellidoPaterno="		+$("txtApellidoPaterno").value		+"&txtAreas="		+$("txtAreas").value		+"&txtDepartamento="		+$("txtDepartamento").value;
			valores = valores 	+"&txtPassword="		+$("txtPassword").value+"&estatus="+estado+ "&empresa="		+$("slcEmpresa").value+ "&sucursal="		+$("slcSucursal").value;
			
			
			var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("slcEmpresa").value+  "&sucursal="+$("slcSucursal").value+  "&estacion="+estacion;	
			valores=valores +usuario;
			
			$Ajax("scripts/administraUsuarios.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
			$("act_des").style.visibility="hidden";
			$("act_des").style.display="none";
			$("txtClave").disabled=false;		
		}
	}

}
		
function borrar(){

	if (confirm("\u00BFConfirma que desea borrar al usuario?")){

		var hid ="wb_id=" + $("txtClave").value+  "&empresa="+$("slcEmpresa").value+  "&sucursal="+$("slcSucursal").value+  "&estacion="+$("slcEstacion").value;
		$Ajax("scripts/administraUsuarios.php?operacion=3", {metodo: $metodo.POST, onfinish: fin, parametros: hid, avisoCargando:"loading"});
						
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
	else if(campo.estado==0)
	{
		$("lblActivado").value="Desactivado";
		$("chkActivado").checked=false;
	}
	$("txtClave").value = campo.cveUsuario;
	$("txtNombre").value = campo.nombre;
	$("txtNick").value = campo.nick;
	$("txthNick").value = campo.nick;
	$("slcPerfil").value = campo.permiso;

	if(campo.permiso=='Corresponsal')
	{
	   $("slcEstacion").className='';
	   $("lblTexto").innerHTML="*Estaci\u00f3n";
	   $Ajax("scripts/Sucursales.php", {onfinish: cargaSucursal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	   var estacion=campo.estacion;
	   setTimeout("asignarValor('"+estacion+"')",300);   
	}else if(campo.permiso=='Cliente')
	{
	   $("lblTexto").innerHTML="*Cve. cliente";
	   $("txtcveCliente").className='';
	   $("txtcveCliente").value=campo.estacion;
	}

	$("txtApellidoMaterno").value = campo.apeidoMaterno;
	$("txtApellidoPaterno").value = campo.apeidoPaterno;
	$("txtAreas").value = campo.cveArea;
	$("txtDepartamento").value = campo.cveDepartamento;
	
	
	$("slcEmpresa").value = campo.empresa;
	//Cargamos la Sucursal
	sucursales();
	setTimeout("asignarValor2('"+campo.sucursal+"')",300);   
	//

	$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
}
function asignarValor(estacion)
{
	$("slcEstacion").value=estacion;
}
function asignarValor2(sucursal)
{
	$("slcSucursal").value = sucursal;
}

function fin(res){
	alert(res);
	cancelar();
}
		
function allgood(){
	var notGood = 0;	
	
		
	if($("txtClave").value==""){$("txtClave").addClassName("invalid"); notGood ++;} else{$("txtClave").removeClassName("invalid");}
	if($("txtNombre").value.length < 3){$("txtNombre").addClassName("invalid"); notGood ++;} else{$("txtNombre").removeClassName("invalid");}
	if($("txtApellidoPaterno").value.length < 3){$("txtApellidoPaterno").addClassName("invalid"); notGood ++;} else{$("txtApellidoPaterno").removeClassName("invalid");}
	if($("txtApellidoMaterno").value.length < 3){$("txtApellidoMaterno").addClassName("invalid"); notGood ++;} else{$("txtApellidoMaterno").removeClassName("invalid");}	
	if($("txtNick").value.length < 3){$("txtNick").addClassName("invalid"); notGood ++;} else{$("txtNick").removeClassName("invalid");}
	if($("txtPassword").value.length < 5){$("txtPassword").addClassName("invalid"); notGood ++;} else{$("txtPassword").removeClassName("invalid");}		
	
	
	if($("slcPerfil").value == ""){$("slcPerfil").addClassName("invalid"); notGood ++;} else{$("slcPerfil").removeClassName("invalid");}
	if($("slcEmpresa").value == ""){$("slcEmpresa").addClassName("invalid"); notGood ++;} else{$("slcEmpresa").className = "";}
	if($("slcPerfil").value=="Corresponsal")
	{
		if($("slcEstacion").value==""){$("slcEstacion").addClassName("invalid"); notGood ++;} else{$("slcEstacion").removeClassName("invalid");}	
	}		
	else if($("slcPerfil").value=="Cliente")
	{			
		if($("txtcveCliente").value==""){$("txtcveCliente").addClassName("invalid"); notGood ++;} else{$("txtcveCliente").removeClassName("invalid");}	
        }
	
	var regX = /[á é í ó ú Á É Í Ó Ú Ñ ñ]/gi; //No permitirá ingresar ni ñ's ni acnetos en NIck y Password
	
	if(notGood > 0){
		// u\00xx para que los carácteres especiales incluyendo acentos salgan bien en el alert
		//más informacion: http://luauf.com/2008/05/20/caracteres-especiales-en-javascript/
		alert("\u00A1Hay Informaci\u00F3n err\u00F3nea que ha sido resaltada en color!");
		return false;
	}else if ($("txtNick").value.match(regX))
	{
		$("txtNick").addClassName("invalid");
		alert("El nick no puede contener ñ's ni acentos");
		return false;
	}
	else if($("txtPassword").value.match(regX))
	{
		$("txtPassword").addClassName("invalid"); 
		alert("La contraseña no puede contener ñ's ni acentos");
		return false;
	}
	else { return true;}
	
	
}

function chkAlta()
{
	

}

function next_existe(ex){

	//extraemos el valor retornado por el servidor en un objeto json
	var exx=ex[0];
	var exists = exx.existe;
	var cve=exx.cve;
	$("btnBuscar").disabled=true;
	$("btnCancelar").disabled=false;
	campo=document.getElementById("txtNombre");
	campo.focus();
	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0){			
		var url2 = cve;
		$("txtClave").disabled=true;
		$("act_des").style.visibility="visible";
		$("act_des").style.	display="table-row";
		$Ajax("scripts/datosGenerales.php?operacion=1&valor="+url2, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		//cambiamos el manejador del boton actualizar para que apunte a la funcion
		//que actualiza el registro
		sucursales();
		estaciones();
		$("btnModificar").disabled=false;
		$("btnBorrar").disabled=false;
		$("btnModificar").onclick=actualizar;
		//agregamos un manejado de evento al boton borrar para que llame a su funcion borrar
		$("btnBorrar").onclick=borrar;
		//mostramos el boton de borrar
		//el boton guardar no es útil aqui, por lo tanto lo ocultamos
		$("btnGuardar").disabled=true;
		
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	}
	else{	//si la funcion devolvio cero, no existe el registro
		$("act_des").style.visibility="hidden";
		$("act_des").style.display="none";
		$("txtClave").disabled=false;
		//limpiamos los campos del form	
		$("btnGuardar").disabled=false;	
		$("btnGuardar").onclick=insertar;
		//imprimimos un aviso de que se trata de un registro nuevo
		$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
		//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
		$("btnBorrar").disabled=true;
		$("btnModificar").disabled=true;
		$Ajax("scripts/catalogoTotales.php?operacion=4", {onfinish: cargarClave, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
	}
}
function cargarClave(valores)
{
	valor=valores[0];
	$("txtClave").value=valor.clave;
	$("txtClave").disabled=true;
}
			
