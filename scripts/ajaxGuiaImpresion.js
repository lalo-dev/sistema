// JavaScript Document

window.onload = inicia;

var oculto = '';
var todosNumerosGuias = '';
var notIn = '0';
var lista = '';

function inicia()
{
	$('txtRemitente').focus();
	$('divAutocompletar').className = 'autocomplete';
	
	new Ajax.Autocompleter("txtRemitente", "divAutocompletar", "scripts/catalogoDirecciones.php?operacion=3", {paramName: "caracteres", afterUpdateElement:clientes});
}

function clientes(){
	if ($("txtRemitente").value!="Numero Cliente"){
			valores=$("txtRemitente").value.split(" - ");
			cveCliente=valores[0];
			$('hddCveCliente').value = cveCliente;
			cveDireccion=valores[3];
			$("txtRemitente").value=cveCliente+"-"+cveDireccion;
			var url = "scripts/existe.php?keys=10&f1value="+cveCliente+"&f2value="+cveDireccion;
			$Ajax(url, {onfinish: traerDirecciones, tipoRespuesta: $tipo.JSON});
	}
}

function traerDirecciones(ex){
	var exx=ex[0];
	var exists = exx.existe;
	var respuesta= "scripts/municipios.php?municipio=" +  exx.estadoRemitente;
	$Ajax(respuesta, {tipoRespuesta: $tipo.JSON, avisoCargando:"loading", onfinish: cargaMunicipios});
	//pausecomp(200);
	var urlo="operacion=2&datos="+$("txtRemitente").value;
	$Ajax("scripts/datosClientes.php?"+urlo, {onfinish: llenaDirecciones, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});	

}

function cargaMunicipios(airls)
{
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("txtMunR").options.length = 0;
	//empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("txtMunR").options[$("txtMunR").options.length] = opcion;
	
	for (var i=0; i<airls.length; i++){	
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);
		
		try {
			$("txtMunR").options[$("txtMunR").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
}

  function llenaDirecciones(campos){
	var campo = campos[0];
	$("txtRemitente").value = campo.razonSocial;
	if(campo.numero=="") numero="";
	else numero=" No."+campo.numero;
	$("txtCalleR").value = campo.calle+numero;
	$("txtTelefonoR").value = campo.telefono;
	$("txtRfcR").value = campo.rfc;
	$("txtNombredo").value = campo.cveEstado;
	$("txtColR").value = campo.colonia;
	$("txtCodigoPr").value = campo.codigoPostal;
	$("txtMunR").value = campo.cveMunicipio;
	//$("txtCodigoC").value = campo.cveCliente;alert('mmmm');
	$("hdncveDireccion").value=campo.cveDireccion;
	
	$('txtRemitente').disabled = false;
	$('txtCalleR').disabled = false;
	$('txtTelefonoR').disabled = false;
	$('txtRfcR').disabled = false;
	$('txtNombredo').disabled = false;
	$('txtColR').disabled = false;
	$('txtCodigoPr').disabled = false;
	$('txtMunR').disabled = false;
	$('txtNumInicio').disabled = false;
	//$('txtTotalGuias').disabled = false;
	
	$('rdbSi').disabled = true;
	$('rdbNo').disabled = true;
	
	//$('btnImprimirGuias').disabled = false;
	
	/*if($('rdbSi').checked == true)
	{
		$('txtTotalGuias').disabled = false;
		/*$('txtTotalGuias').focus();
	}
	else
	{*/
		$('txtNumInicio').disabled = false;
		$('txtNumInicio').focus();
	/*}*/
}
		
function buscaGuia()//BUSCA SI EL NUMERO DE GUIA INTRODUCIDO EXISTE EN LA BASE DE DATOS
{
	if($('txtNumInicio').value != '')
	{
		if(isNaN($('txtNumInicio').value))
		{
			mensaje = 'El Numero de Folio es Incorrecto';
			$('txtNumInicio').value='';
			alert(mensaje);
			$('txtNumInicio').focus();			
		}
		else
		{
			var url = 'scripts/libreriaImpresion.php?case=4&numGuia='+$('txtNumInicio').value;
			$Ajax(url, {onfinish: validaDisponoble, tipoRespuesta: $tipo.JSON});
		}
	}
}

/*buscaGuia()*/
function validaDisponoble(disponible) // RECIBE LA CANTIDAD DE REGISTROS QUE CONTIENEN EL NUMERO DE GUIA INTRODUCIDO
{
	if(disponible == 0) // SI EL RESULTADO DE LA BUSQUEDA ES "0" ES PORQUE EL NUMERO DE GUIA AUN NO ES OCUPADO
	{
		$('rdbSi').disabled = true;
		$('rdbNo').disabled = true;
		$('txtRemitente').disabled = true;
		$('txtRfcR').disabled = true;
		$('txtNombredo').disabled = true;
		$('txtMunR').disabled = true;
		$('txtCalleR').disabled = true;
		$('txtColR').disabled = true;
		$('txtCodigoPr').disabled = true;
		$('txtTelefonoR').disabled = true;
		
		$('txtTotalGuias').disabled = false;
		$('txtTotalGuias').value = '';
		$('txtTotalGuias').focus();
	}
	else
	{
		$('txtNumInicio').value = '';
		alert('Numero de Guia no disponible ! ! !');
		$('txtNumInicio').focus();
	}
}

/*validaDisponibles(disponible)*/
function obtenerDisponibles() // OBTIENE EL RANGO DE GUIAS DISPONIBLES TOMANDO COMO INICIO EL NUMERO DE GUIA INGRESADO
{
	var guiaInicio = $('txtNumInicio').value;
	var cantidad = $('txtTotalGuias').value;
	alert('Buscando disponibilidad ....');
			
	var url = 'scripts/libreriaImpresion.php?case=5&inicio='+guiaInicio+'&cantidad='+cantidad;
	$Ajax(url, {onfinish: validarImpresionGuias, tipoRespuesta: $tipo.JSON});
	
}

/*obtenerDisponibles()*/
function validarImpresionGuias(rango) // EN CASO DE SER NECESARIO NOTIFICA EL RANGO DISPONIBLE Y SI ES ACEPTADO GENERA LOS REGISTROS DE LA PRE-IMPRESION DE GUIAS
{				
	if(rango == 0)
	{
		$('btnImprimirGuias').disabled = false;
		
		var inicio = parseInt($('txtNumInicio').value);
		var total = parseInt($('txtTotalGuias').value);
		var operacion = inicio + (total - 1);
		$('txtUltimoFolio').value = operacion;
		
		$('btnImprimirGuias').onclick = enviaDatosImpresion;
				
	}
	else
	{
		var confirmacion = confirm('Solo se podra imprimir '+rango+' guia(s)');
		if(confirmacion)
		{
			var inicio = $('txtNumInicio').value;
			$('txtTotalGuias').value = rango;
			$('txtUltimoFolio').value = (parseInt(inicio) + ( parseInt(rango)-1 ));
			
			$('btnImprimirGuias').disabled = false;
			$('btnImprimirGuias').onclick = enviaDatosImpresion;
		
		}
		else
		{
			$('txtNumInicio').value = '';
			$('txtTotalGuias').value = '';
			$('txtTotalGuias').disabled = true;
			$('txtNumInicio').focus();
		}
	}
}

function cargarRemitente(imprimir)
{
	$('hddImprimirNumGuia').value = imprimir;
	$('txtRemitente').disabled = false;
	$('txtRemitente').focus();
	
	oculto = imprimir;
	/*if(imprimir == 'si')
	{
		$('divMostrarSi').style.display = 'block';
		$('divMostrarNo').style.display = 'none';
	}
	else
	{
		$('divMostrarSi').style.display = 'none';
		$('divMostrarNo').style.display = 'block';
	}*/
}

function imprimirGuias()
{
	var mensaje = 'ok';
	var folio = 0;
	if($('rdbSi').checked == true)
	{
		if($('txtTotalGuias').value != '')
		{
			if(isNaN($('txtTotalGuias').value))
			{
				$('txtTotalGuias').focus();
				mensaje = 'El Total de Folios es Incorrecto';
			}
		}
		else
		{
			$('txtTotalGuias').focus();
			mensaje = 'Es necesario ingresar el Total de Folios a Imprimir';
		}
	}
	else
	{
		//Inicio
		if($('txtNumInicio').value != '')
		{
			if(isNaN($('txtNumInicio').value))
			{
				mensaje = 'El Numero de Folio es Incorrecto';
			}
			else
			{
				//Fin
				if($('txtTotalGuias').value != '')
				{
					if(isNaN($('txtTotalGuias').value))
					{
						mensaje = 'El Numero de Total de Guias es Incorrecto';
					}
				}
				else
				{
					mensaje = 'Es necesario ingresar el Total de Guias.';
				}
			}
		}
		else
		{
			mensaje = 'Es necesario ingresar el Numero de Folio.';
		}
	}
	if(mensaje == 'ok' )
	{
		/*if($('rdbNo').checked == true)
		{
			obtenerDisponibles();
		}
		else
		{*/
			enviaDatosImpresion();
		/*}*/
	}
	else
	{
		alert(mensaje);
	}
}

function enviaDatosImpresion()
{
		/*var cveEmp= $('hddEmpresa').value;
		var cveSuc = $('hddSucursal').value;
		var cveGuia = $('txtNumInicio').value;
		var cveCli = $('hddCveCliente').value;
		var nombre = $('txtRemitente').value;
		var calle = $('txtCalleR').value;
		var colonia = $('txtColR').value;
		var municipio = $('txtMunR');
		municipio = municipio.options[municipio.selectedIndex].text;
		var municipioNum = $('txtMunR').value;
		var estado = $('txtNombredo');
		estado = estado.options[estado.selectedIndex].text;
		var estadoNum = $('txtNombredo').value;
		var cp = $('txtCodigoPr').value;
		var telefono = $('txtTelefonoR').value;
		var rfc = $('txtRfcR').value;
		var cveGuiaInt = $('txtNumInicio').value;
		var usuario = $('hddUsuario').value;
		var totalFolios = $('txtTotalGuias').value;
		var numGuiaInicio = $('txtNumInicio').value;
		var totalGuias = $('txtTotalGuias').value;
		
		url= '';
		valoresphp = '';
		valorespdf = '';
		
		if($('rdbSi').checked == true)
		{
			url= 'scripts/libreriaImpresion.php?case=6';
		}
		else if($('rdbNo').checked == true)
		{
			url= 'scripts/libreriaImpresion.php?case=7';
		}
		
		valoresphp = '&oculto='+oculto+'&empresa='+cveEmp+'&sucursal='+cveSuc+'&cveGuiaInt='+cveGuiaInt+'&cveCliente='+cveCli+'&nombre='+nombre+'&calle='+calle+'&colonia='+colonia+'&municipio='+municipioNum+'&estado='+estadoNum+'&cp='+cp+'&telefono='+telefono+'&rfc='+rfc+'&usuario='+usuario+'&totalFolios='+totalFolios+'&numGuiaInicio='+numGuiaInicio+'&totalGuias='+totalGuias;
			
		valorespdf = 'nombreR='+nombre+'&calleR='+calle+'&coloniaR='+colonia+'&municipioR='+municipio+'&estadoR='+estado+'&cpR='+cp+'&telefonoR='+telefono;
		
		//alert(url+valoresphp);
		
		$Ajax(url, {metodo: $metodo.POST, onfinish: limpiarForm, parametros: valoresphp, avisoCargando:"loading"});*/
		
		/*$("btnImprimirGuias").onclick=function()
		{*/
			alert('press button');
			var cveEmp= $('hddEmpresa').value;
			var cveSuc = $('hddSucursal').value;
			var cveGuia = $('txtNumInicio').value;
			var cveCli = $('hddCveCliente').value;
			var nombre = $('txtRemitente').value;
			var calle = $('txtCalleR').value;
			var colonia = $('txtColR').value;
			var municipio = $('txtMunR');
			municipio = municipio.options[municipio.selectedIndex].text;
			var municipioNum = $('txtMunR').value;
			var estado = $('txtNombredo');
			estado = estado.options[estado.selectedIndex].text;
			var estadoNum = $('txtNombredo').value;
			var cp = $('txtCodigoPr').value;
			var telefono = $('txtTelefonoR').value;
			var rfc = $('txtRfcR').value;
			var cveGuiaInt = $('txtNumInicio').value;
			var usuario = $('hddUsuario').value;
			var totalFolios = $('txtTotalGuias').value;
			var numGuiaInicio = $('txtNumInicio').value;
			var totalGuias = $('txtTotalGuias').value;
			var ultimoFolio = $('txtUltimoFolio').value;
			
			url= '';
			valoresphp = '';
			valorespdf = '';
			
			/*if($('rdbSi').checked == true)
			{*/
				url= 'scripts/libreriaImpresion.php?case=6';
			/*}
			else if($('rdbNo').checked == true)
			{
				url= 'scripts/libreriaImpresion.php?case=7';
			}*/
			valoresphp = '&oculto='+oculto+'&empresa='+cveEmp+'&sucursal='+cveSuc+'&cveGuiaInt='+cveGuiaInt+'&cveCliente='+cveCli+'&nombre='+nombre+'&calle='+calle+'&colonia='+colonia+'&municipio='+municipioNum+'&estado='+estadoNum+'&cp='+cp+'&telefono='+telefono+'&rfc='+rfc+'&usuario='+usuario+'&totalFolios='+totalFolios+'&numGuiaInicio='+numGuiaInicio+'&totalGuias='+totalGuias+'&ultimoFolio='+ultimoFolio;
				
			valorespdf = 'nombreR='+nombre+'&calleR='+calle+'&coloniaR='+colonia+'&municipioR='+municipio+'&estadoR='+estado+'&cpR='+cp+'&telefonoR='+telefono;
			
			//alert(url+valoresphp);
			
			$Ajax(url, {metodo: $metodo.POST, onfinish: limpiarForm, parametros: valoresphp, avisoCargando:"loading"});
		/*};*/
}

function limpiarForm(respuesta)
{
	alert(respuesta);
	if(respuesta == '0')
	{
		var win = new Window({className: "mac_os_x", title: "Guias", top:70, left:300, width:700, height:400, url: 'scripts/guiapdf.php?oculto='+oculto+'&canImp='+$('txtTotalGuias').value+'&inicio='+$('txtNumInicio').value+'&fin='+$('txtUltimoFolio').value+'&'+valorespdf , showEffectOptions: {duration:1.5}});  
        win.show();
	}
	else
	{
		alert('A ocurrido un Error: '+respuesta);
	}
	
	oculto = 'no';
	$('formImprimirGuias').reset();
	$('rdbSi').disabled = false;
	$('rdbNo').disabled = false;
	$('txtRemitente').disabled = true;
	$('hddCveCliente').value = 0;
	$('txtRfcR').disabled = true;
	$('hdncveDireccion').value = '';
	$('txtNombredo').disabled = true;
	$('txtMunR').disabled = true;
	$('txtCalleR').disabled = true;
	$('txtColR').disabled = true;
	$('txtCodigoPr').disabled = true;
	$('txtTelefonoR').disabled = true;
	$('txtNumInicio').disabled = true;
	$('txtTotalGuias').disabled = true;
	$('txtUltimoFolio').disabled = true;
	//$('txtTotalGuias').disabled = true;
	/*$('').disabled = true;
	$('').disabled = true;
	$('').disabled = true;*/
	$('btnImprimirGuias').disabled = true;
	
	$('txtRemitente').focus();
}

function limpiarForm0()
{
	window.location='guiaImpresion.php';
}

function cargarDisponibles()
{
	url = 'scripts/libreriaImpresion.php?';
	valores = 'case=52&notIn='+notIn;
	
	$Ajax(url, {metodo: $metodo.POST, onfinish: pintarDisponible, parametros: valores, avisoCargando:"loading"});
}
function pintarDisponible(disponible)
{
	$('txtNumInicio').value = disponible;
}

function validacionImprimir()
{
	if($('txtTotalGuias').value != '' )
	{
		var folio = $('txtTotalGuias').value;
		var esNum = folio/folio;
		if(esNum == 1)
		{
			$('btnImprimirGuias').disabled = false;
			$('btnImprimirGuias').onclick = imprimirGuias;
		}
		else
		{
			$('txtTotalGuias').value = '';
			alert('El dato introducido no es un numero');
			$('txtTotalGuias').focus();
		}
	}
	else
	{
		alert('Es necesario introducir el numero de Folio por asignar');
	}
}