window.onload = inicia;

function inicia() 
{
	$("btnGuardar").disabled   = true;
	$("btnModificar").disabled = true;
	$("btnImprimir").disabled  = true;
	$("btnAgregar").disabled   = true;
	$("btnCancelar").disabled  = true;
	$("txtImporte").disabled   = true;	
	$("btnImprimir").onclick   = imprimirContra;
	$("btnCancelar").onclick   = cancelar;
	$("btnBuscar").onclick     = existeContra;
	$("btnGuardar").onclick    = guardar;
	$("btnModificar").onclick  = modificar;
	
	
	var url1='';
	var url2='';
	var url3='';	

	url1="scripts/catalogoContrarecibo.php?operacion=1";	
	url2="scripts/catalogoClientes.php?operacion=4&opt=0";
	url3="scripts/catalogoFacturas.php?operacion=5&opc=0";
	
	//Para buscar el Contrarecibo
	$("autoContra").className = "autocomplete";
	new Ajax.Autocompleter("txtNumC", "autoContra", url1, {paramName: "caracteres",afterUpdateElement:existeContra});
	
	//Para búsquedas de Facturas	  
	$("autoClienteC").className = "autocomplete";
	new Ajax.Autocompleter("txtCodigoCliente", "autoClienteC", url2, {paramName: "caracteres",afterUpdateElement:datosClientesNum});
	$("autoClienteR").className = "autocomplete";
	new Ajax.Autocompleter("txtRazonSocial", "autoClienteR", url2, {paramName: "caracteres",afterUpdateElement:datosClientesRazon});
	$("autoFactura").className = "autocomplete";
	new Ajax.Autocompleter("txtFactura", "autoFactura", url3, {paramName: "caracteres",afterUpdateElement:datosClientesFactura});

	document.getElementById("datosHis").style.visibility="hidden";
	document.getElementById("txtNumC").focus();
	numContrarecibo();

	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("btnAgregar").style.visibility="hidden";
		$("btnAgregar").style.display="none";

	}
}

function existeContra()
{
	//Checaremos si exite el contrarecibo
	if ($("txtNumC").value!=""){
		var url = "scripts/existe.php?keys=27&f1value=" + $("txtNumC").value;
		$Ajax(url, {onfinish: nextExisteContra, tipoRespuesta: $tipo.JSON});
	}else {alert("Ingrese un n\u00FAmero de Contrarecibo."); document.getElementById("txtNumC").focus();}
}

function nextExisteContra(ex)
{
	//Extraemos el valor retornado por el servidor en un objeto json
	var exx=ex[0];
	var exists = exx.existe;
	
	//Si el valor es mayor que cero, entonces el registro existe
	if (exists > 0)
	{	
		//Movemos controles (como sabemos que si existe el contrarecibo es porq tiene por lo menos una factura, el proceso lo hacemos aquí)
		$("btnCancelar").disabled = false;	
		$("btnImprimir").disabled = false;
		$("btnAgregar").disabled  = false;
		$("btnModificar").disabled=false;
		
		$("btnGuardar").disabled=true;
		
		$("txtNumC").disabled=true;
		
		
		//Primero limipiamos los datos
		if(document.getElementById("datosHis").rows.length>2){	
			var ultima = document.getElementById("datosHis").rows.length;
			for(var j=ultima; j>2; j--){				
					 document.getElementById("datosHis").deleteRow(2);					 		
			}
		}
		if(document.getElementById("tblFacturas").rows.length>2){	
			var ultima = document.getElementById("tblFacturas").rows.length;
			for(var j=ultima; j>2; j--){				
					 document.getElementById("tblFacturas").deleteRow(2);					 		
			}
		}
		
		//Buscaremos los datos Generales del Contrarecibo
		var url="scripts/datosContrarecibo.php?operacion=1&numContrarecibo="+$("txtNumC").value;
		$Ajax(url, {tipoRespuesta: $tipo.JSON,onfinish: function traerCliente(datos)
														{
															$("txtCodigoCliente").value=datos[0].numCliente;
															datosClientesContra();
														}
													});
		
		//Buscamos las facturas incorporadas en el Contrarecibo
		var url="scripts/datosContrarecibo.php?operacion=2&numContrarecibo="+$("txtNumC").value;
		$Ajax(url, {onfinish: cargarFacturas, tipoRespuesta: $tipo.JSON});
		
		//Imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";

	}
	else{	
		//Restauramos todo
		alert('El contrarecibo no existe.');
		//Borrar tablas
		if(document.getElementById("datosHis").rows.length>2){	
			var ultima = document.getElementById("datosHis").rows.length;
			for(var j=ultima; j>2; j--){				
					 document.getElementById("datosHis").deleteRow(2);					 		
			}
		}
		if(document.getElementById("tblFacturas").rows.length>2){	
			var ultima = document.getElementById("tblFacturas").rows.length;
			for(var j=ultima; j>2; j--){				
					 document.getElementById("tblFacturas").deleteRow(2);					 		
			}
		}
		//Formatear form
		$("form2").reset(); 
		inicia();	 
	 	$("txtRazonSocial").disabled=false;
		$("txtCodigoCliente").disabled=false;
	}
}

function cargarFacturas(datos)
{
	for(i=0;i<datos.length;i++)
	{
		dato=datos[i];
		myNewRow = document.getElementById("tblFacturas").insertRow(-1); 
		myNewRow.id=indiceFilaFormulario;
		myNewCell=myNewRow.insertCell(-1);  
		myNewCell.innerHTML=dato.folioDocumento+"<input type='hidden' name='flFac"+indiceFilaFormulario+"' id='flFac"+indiceFilaFormulario+
		"' value='"+dato.folioDocumento+"'/>"+"<input type='hidden' name='idCtr"+indiceFilaFormulario+"' id='idCtr"+indiceFilaFormulario+
		"' value='"+dato.idContr+"'/>";
		
		myNewCell.style.textAlign="right";
		myNewCell=myNewRow.insertCell(-1); 
		myNewCell.style.textAlign="right";
		if(dato.fecha!="0000-00-00")
			fechafin=formatoFecha(dato.fecha); 
		else
			fechafin=dato.fecha;
		myNewCell.innerHTML=fechafin+"<input type='hidden' name='flFec"+indiceFilaFormulario+"' id='flFec"+indiceFilaFormulario+"' value='"+fechafin+"'/>";
		myNewCell=myNewRow.insertCell(-1); 
		myNewCell.style.textAlign="right";	
		myNewCell.innerHTML=dato.monto+"<input type='hidden' name='flMon"+indiceFilaFormulario+"' id='flMon"+indiceFilaFormulario+"' value='"+dato.monto+"'/>";
		myNewCell=myNewRow.insertCell(-1); 
		myNewCell.style.textAlign="center";	
		myNewCell.innerHTML=dato.estado;
		myNewCell=myNewRow.insertCell(-1);  
		myNewCell.style.textAlign="right";
		myNewCell.innerHTML="<input type='button' value='Eliminar' onclick='quitar(this,1,"+dato.idContr+")'>";
		indiceFilaFormulario++;
	}
}

function numContrarecibo()
{
	url="scripts/catalogoTotales.php?operacion=6";
	$Ajax(url, {tipoRespuesta: $tipo.JSON, avisoCargando:"loading",onfinish: function(datos)
																		 {
																			$("txtNumC").value=datos[0].clave;
																		 }
		
		   });
}

function datosClientesNum() 
{	
	var arcPhp='datosClientes';
	var url2 = "opc=0&codigo="+$("txtCodigoCliente").value;
	$Ajax("scripts/" + arcPhp + ".php?operacion=4&"+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function datosClientesRazon() 
{	
	var arcPhp='datosClientes';
	var url2 = "opc=1&codigo="+$("txtRazonSocial").value;
	$Ajax("scripts/" + arcPhp + ".php?operacion=4&"+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function datosClientesFactura() 
{	
	if($("txtCodigoCliente").value!='')
		url="scripts/catalogoFacturas.php?operacion=5&opc=2&cveFactura="+$("txtFactura").value+"&cveClente="+$("txtCodigoCliente").value;
	else
		url="scripts/catalogoFacturas.php?operacion=5&opc=1&cveFactura="+$("txtFactura").value;

	$Ajax(url, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$("btnAgregar").onclick=agregarFacturas;
	$("btnAgregar").disabled=false;
	$("btnCancelar").disabled=false;
	
	$("txtRazonSocial").disabled=true;
	$("txtCodigoCliente").disabled=true;
	setTimeout("Importe()",60);
}

function datosClientesContra() 
{	
	var arcPhp='datosClientes';
	var url2 = "opc=0&codigo="+$("txtCodigoCliente").value;
	$Ajax("scripts/" + arcPhp + ".php?operacion=4&"+url2, {onfinish: cveClienteContra, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function agregarFacturas()
{
	//Checar que la factura a agregar sea válida
	if($("txtImporte").value!="")
	{
		//Checar primero que la factura NO este agregada ya
		var inFact=$$('[id^=flFac]'); 
		factura=$("txtFactura").value;
		agregar=true;
		for (var i=0; i<inFact.length; i++){      
			valor=inFact[i].value; 
			if(valor==factura){
				alert("La factura ya hab\u00EDa sido ingresada.");
				agregar=false;
				break;
			}
		}
	
		if(agregar){
			url="scripts/catalogoFacturas.php?operacion=5&opc=1&cveFactura="+$("txtFactura").value;
			$Ajax(url, {onfinish: agregarFactura, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			if($("txtNumC").disabled==false)
			{
				$("btnGuardar").disabled=false;
			}else
				$("btnImprimir").disabled=true;
		}
		
		//Limpiamos importe y factura
		document.getElementById("txtNumC").focus();
		document.getElementById("txtFactura").value="";
		document.getElementById("txtImporte").value="";
	}
}

var indiceFilaFormulario=1; 
function agregarFactura(datos)
{
	dato=datos[0];

	myNewRow = document.getElementById("tblFacturas").insertRow(-1); 
    myNewRow.id=indiceFilaFormulario;
	myNewCell=myNewRow.insertCell(-1);  
	myNewCell.innerHTML=dato.folioDocumento+"<input type='hidden' name='flFac"+indiceFilaFormulario+"' id='flFac"+indiceFilaFormulario+"' value='"+dato.folioDocumento+"'/>";
	myNewCell.style.textAlign="right";
	myNewCell=myNewRow.insertCell(-1); 
	myNewCell.style.textAlign="right";
	if(dato.fecha!="0000-00-00")
		fechafin=formatoFecha(dato.fecha); 
	else
		fechafin=dato.fecha;
	myNewCell.innerHTML=fechafin+"<input type='hidden' name='flFec"+indiceFilaFormulario+"' id='flFec"+indiceFilaFormulario+"' value='"+fechafin+"'/>";
	myNewCell=myNewRow.insertCell(-1); 
	myNewCell.style.textAlign="right";	
	myNewCell.innerHTML=dato.saldo+"<input type='hidden' name='flMon"+indiceFilaFormulario+"' id='flMon"+indiceFilaFormulario+"' value='"+dato.saldo+"'/>";
	myNewCell=myNewRow.insertCell(-1); 
	myNewCell.style.textAlign="center";	
	myNewCell.innerHTML=dato.estado;
	myNewCell=myNewRow.insertCell(-1);  
	myNewCell.style.textAlign="right";
	myNewCell.innerHTML="<input type='button'  value='Eliminar' onclick='quitar(this,0,0)'>";
	indiceFilaFormulario++;
}

function formatoFecha(fecha)
{
	datos=fecha.split("-");
	anyo=datos[0];
	mes=datos[1];
	dia=datos[2];
	meses=new Array('ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic');
	fechaf=dia+"-"+meses[mes-1]+"-"+anyo;
	return fechaf;
}

function quitar(objeto,opcion,idContr)
{
	boton=objeto;
	columna=boton.parentNode;
	renglon=columna.parentNode;
	id_renglon=renglon.id;
	
	//Checar que si ya no hay nadie el botón de Imprimir no este activo
	if(document.getElementById("tblFacturas").rows.length<=2)
		$("btnImprimir").disabled=true;

	if(opcion==1)
	{
		if(confirm("\u00BFEst\u00E1 seguro que desea eliminar la factura?")){
			document.getElementById(id_renglon).remove(0);
			var url="scripts/administraContrarecibo.php?operacion=3&idContr="+idContr;
			$Ajax(url,{metodo: $metodo.POST, avisoCargando:"loading",onfinish: function(res)
																			   {
																					alert(res);
																			   }
																	});
		}
	}
	else
		document.getElementById(id_renglon).remove(0);
}

function Importe()
{
	//Poner el importe de la Factura
	url="scripts/catalogoFacturas.php?operacion=5&opc=1&cveFactura="+$("txtFactura").value;
	$("status").innerHTML="cargando..";
	$Ajax(url, {tipoRespuesta: $tipo.JSON, avisoCargando:"loading",onfinish: function(datos)
																			 {
																						$("txtImporte").value=datos[0].saldo;
																			 }
		  	
		  	   });
	$("status").innerHTML="";
}

function Importe2()
{
	//Poner el importe de la Factura
	url="scripts/catalogoFacturas.php?operacion=5&opc=1&cveFactura="+$("txtFactura").value;
	$("status").innerHTML="cargando..";
	$Ajax(url, {tipoRespuesta: $tipo.JSON, avisoCargando:"loading",onfinish: function(datos)
																			 {
																				$("txtImporte").value=datos[0].saldo;
																			 }
		  	
		  	   });
	$("btnAgregar").onclick=agregarFacturas;
	$("btnAgregar").disabled=false;
	$("btnCancelar").disabled=false;
	$("status").innerHTML="";
}

function cveCliente(campos) 
{
	//Si ya están cargados los datos, no permitirá cambiar al Cliente
	if(document.getElementById("tblFacturas").rows.length<=2)
	{	
		var campo = campos[0];
		
		$("txtCodigoCliente").value=campo.cveCliente;
		$("txtRazonSocial").value=campo.razonSocial;
		
		//Cargar datos en la Tabla General
		var url="scripts/datosFactura.php?operacion=1&cveCliente="+$("txtCodigoCliente").value;
		$Ajax(url, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
		$("txtRazonSocial").disabled=true;
		$("txtCodigoCliente").disabled=true;
		$("btnAgregar").onclick=agregarFacturas;
		$("btnAgregar").disabled=false;
		$("btnCancelar").disabled=false;
		$("status").innerHTML="";
				
		//Limpiar contenido del div
		$("autoFactura").innerHTML="";
		url="scripts/catalogoFacturas.php?operacion=5&opc=2&cliente="+$("txtCodigoCliente").value;	
		new Ajax.Autocompleter("txtFactura", "autoFactura", url, {paramName: "caracteres",afterUpdateElement:Importe2});
	}
}

function cveClienteContra(campos) 
{
	var campo = campos[0];
	
	$("txtCodigoCliente").value=campo.cveCliente;
	$("txtRazonSocial").value=campo.razonSocial;
	
	//Cargar datos en la Tabla General
	var url="scripts/datosFactura.php?operacion=1&cveCliente="+$("txtCodigoCliente").value;
	$Ajax(url, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	$("txtRazonSocial").disabled=true;
	$("txtCodigoCliente").disabled=true;
	$("btnAgregar").onclick=agregarFacturas;
	$("btnAgregar").disabled=false;
	$("btnCancelar").disabled=false;
	$("status").innerHTML="";
			
	//Limpiar contenido del div
	$("autoFactura").innerHTML="";
	url="scripts/catalogoFacturas.php?operacion=5&opc=2&cliente="+$("txtCodigoCliente").value;	
	new Ajax.Autocompleter("txtFactura", "autoFactura", url, {paramName: "caracteres",afterUpdateElement:Importe2});

}

function guardar()
{
	//Tomar todos los datos para el Contra Recibo
    var inFact=$$('[id^=flFac]'); 
 	var inMon=$$('[id^=flMon]'); 
    var inFecha=$$('[id^=flFec]'); 
	var inObs=$$('[id^=flObs]'); 
	
	var longitudvalores=inFact.length;
	var valores="";
	var indice=1;
	
	for(i=0;i<longitudvalores;i++)
	{
		var folio=inFact[i].value;
		var fecha=inFecha[i].value;

		var monto=inMon[i].value;
		var fecha=convertirFecha(fecha);

		if(indice==1)
			var valores=valores+"folio_"+indice+"="+folio+"&fecha_"+indice+"="+fecha+"&monto_"+indice+"="+monto;
		else
			var valores=valores+"&folio_"+indice+"="+folio+"&fecha_"+indice+"="+fecha+"&monto_"+indice+"="+monto;
		indice++;
	}
	var valores=valores+"&noCliente="+$("txtCodigoCliente").value+"&total="+longitudvalores;
	var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
	valores=valores +usuario;
	
	//Vamos a guardar primero los valores
	$Ajax("scripts/administraContrarecibo.php?operacion=1", {metodo: $metodo.POST, onfinish: imprimirContraGuardar, parametros: valores, avisoCargando:"loading"});
	
}

function imprimirContraGuardar(res)
{
	var resultados=res.split("-");
	//Mostramos el resultado del proceso
	alert(resultados[0]);
	
	//En caso de haber sido extioso
	if(resultados[1]==1)
	{
		var url ="scripts/contraRecibo.php?noContra="+$("txtNumC").value+"&cliente="+$("txtRazonSocial").value;		
		var win = new Window({className: "mac_os_x", title: "Contra Recibo", top:70, left:100, width:1200, height:450, url: url, showEffectOptions: {duration:1.5}});  
		win.show(); 
		//Actualizar el folio
		url="scripts/catalogoTotales.php?operacion=7";
		$Ajax(url, {tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	}	
	limpiar();
}



function modificar()
{
	//Tomar todos los datos para el Contra Recibo
	var inContra=$$('[id^=idCtr]'); 
    var inFact=$$('[id^=flFac]'); 
 	var inMon=$$('[id^=flMon]'); 
    var inFecha=$$('[id^=flFec]'); 
	var inObs=$$('[id^=flObs]'); 
	
	var valores="";		
	var longitudActualizaciones=inContra.length;
	var longitudvalores=inFact.length;
	var valores=valores+"&noCliente="+$("txtCodigoCliente").value+"&no_contra="+$("txtNumC").value+"&total="+longitudvalores;
	var a=0;
	var indice=1;

	for(i=0;i<longitudvalores;i++)
	{
		var folio=inFact[i].value;
		var fecha=inFecha[i].value;

		var monto=inMon[i].value;

		var fecha=convertirFecha(fecha);
		
		if(i<longitudActualizaciones)	
		{
			var valores=valores+"&cveCR_"+indice+"="+inContra[a].value+"&folio_"+indice+"="+folio+"&fecha_"+indice+"="+fecha+"&monto_"+indice+"="+monto;
			a++;
		}
		else
			var valores=valores+"&folio_"+indice+"="+folio+"&fecha_"+indice+"="+fecha+"&monto_"+indice+"="+monto;
		
		indice++;
	}
	
	var usuario="&totUp="+(a--)+"&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
	valores=valores +usuario;
	
	//Vamos a modificar primero los valores
	$Ajax("scripts/administraContrarecibo.php?operacion=2", {metodo: $metodo.POST, onfinish: imprimirContraMod, parametros: valores, avisoCargando:"loading"});

}

function imprimir()
{
	//Tomar todos los datos para el Contra Recibo
    var inFact=$$('[id^=flFac]'); 
 	var inMon=$$('[id^=flMon]'); 
    var inFecha=$$('[id^=flFec]'); 
	var inObs=$$('[id^=flObs]'); 
	
	var longitudvalores=inFact.length;
	var valores="";
	var indice=1;
	
	for(i=0;i<longitudvalores;i++)
	{
		var folio=inFact[i].value;
		var fecha=inFecha[i].value;

		var monto=inMon[i].value;

		if(indice==1)
			var valores=valores+"folio_"+indice+"="+folio+"&fecha_"+indice+"="+fecha+"&monto_"+indice+"="+monto;
		else
			var valores=valores+"&folio_"+indice+"="+folio+"&fecha_"+indice+"="+fecha+"&monto_"+indice+"="+monto;
		indice++;
	}
	var valores=valores+"&noCliente="+$("txtCodigoCliente").value+"&rfcCliente="+$("txtRazonSocial").value+"&no_contra="+$("txtNumC").value+"&total="+longitudvalores;
	var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
	valores=valores +usuario;

	//Vamos a guardar primero los valores
	$Ajax("scripts/administraContrarecibo.php?operacion=1", {metodo: $metodo.POST, onfinish: imprimirContra, parametros: valores, avisoCargando:"loading"});
}

function imprimirContra()
{
	var valores=valores
	
	var url2="scripts/contraRecibo.php?noContra="+$("txtNumC").value+"&cliente="+$("txtRazonSocial").value;
	var win = new Window({className: "mac_os_x", title: "Contra Recibo", top:70, left:100, width:1200, height:450, url: url2, showEffectOptions: {duration:1.5}});  
	win.show();     
	limpiar();
}

function limpiar()
{
	$("form2").reset();
	//Borrar tablas
	if(document.getElementById("datosHis").rows.length>2){	
		var ultima = document.getElementById("datosHis").rows.length;
		for(var j=ultima; j>2; j--){				
	             document.getElementById("datosHis").deleteRow(2);					 		
		}
	}
	if(document.getElementById("tblFacturas").rows.length>2){	
		var ultima = document.getElementById("tblFacturas").rows.length;
		for(var j=ultima; j>2; j--){				
	             document.getElementById("tblFacturas").deleteRow(2);					 		
		}
	}
	inicia();
	//Desbloquear
	$("txtNumC").disabled=false;
}

function imprimirContraMod(res)
{
	var resultados=res.split("-");
	//Mostramos el resultado del proceso
	alert(resultados[0]);
	
	//En caso de haber sido extioso
	if(resultados[1]==1)
	{
		var url ="scripts/contraRecibo.php?noContra="+$("txtNumC").value+"&cliente="+$("txtRazonSocial").value;		
		var win = new Window({className: "mac_os_x", title: "Contra Recibo", top:70, left:100, width:1200, height:450, url: url, showEffectOptions: {duration:1.5}});  
		win.show();     

	}	
	limpiar();
}

function cancelar()
{
	location.reload();
}

var indiceFilaFormulario=1;
function llenaDatos(campos){

	document.getElementById("datosHis").style.visibility="visible";

	if(document.getElementById("datosHis").rows.length>2){	
		var ultima = document.getElementById("datosHis").rows.length;
		for(var j=ultima; j>2; j--){				
	             document.getElementById("datosHis").deleteRow(2);					 		
		}
	}
	if(campos[0].existe!=0){
		for (var i=0; i<campos.length; i++){
			var campo = campos[i];
			myNewRow = document.getElementById("datosHis").insertRow(-1); 
			myNewCell=myNewRow.insertCell(-1);  
			myNewCell.innerHTML=campo.folioDocumento;
			myNewCell.style.textAlign="right";
			myNewCell=myNewRow.insertCell(-1);
			if(campo.fecha!="0000-00-00")
				fechafin=formatoFecha(campo.fecha);
			else
				fechafin=campo.fecha;
			myNewCell.innerHTML=fechafin;
			myNewCell.style.textAlign="right";
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML=campo.montoNeto;
			myNewCell.style.textAlign="right";
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML=campo.saldo;
			myNewCell.style.textAlign="right";
		}
	}
}

//Recibe la fecha en formato dd/M/Y pe: 01/oct/2010
function convertirFecha(fecha) 
{
	meses=new Array('ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic');
	
	valores=fecha.split("-");
	dia  = valores[0];
	mes  = valores[1];
	anyo = valores[2];
	
	mesFinal = meses.indexOf(mes)+1;
	
	fechaFinal=anyo+"-"+mesFinal+"-"+dia;
	
	return fechaFinal;
}
