window.onload = inicia;

function inicia() {	

	cancelar();
	$("btnCancelar").onclick=cancelar;	
	$("btnBuscar").onclick=datosNota;	
	
	//Inicializando autocompletados
	var url1="scripts/catalogoClientes.php?operacion=4&opt=0";
	var url2="scripts/catalogoContrarecibo.php?operacion=2";	
	
	$("autoCliente").className = "autocomplete";
	new Ajax.Autocompleter("txtRazonS", "autoCliente",url1, {paramName: "caracteres",afterUpdateElement:datosClientes});

	$("autoNota").className = "autocomplete";
	new Ajax.Autocompleter("txtNotaC", "autoNota",url2, {paramName: "caracteres",afterUpdateElement:datosNota});

	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("bntGuardar").style.visibility="hidden";
		$("bntGuardar").style.display="none";
	}

}

function cancelar()
{
	document.getElementById("txtNotaC").focus();
	$("form2").reset();
	$("btnGuardar").disabled=true;
	$("btnModificar").disabled=true;
	$("btnCalcular").disabled=true;	
	$("txtIva").disabled=true;
	$("txtTotal").disabled=true;
	$("txtRetencion").disabled=true;
	$("txtTotalBruto").disabled=true;
	$("txtFolioFactura").disabled=true;

	
	//Traer el valor de la siguiente Nota de Crédito
	url="scripts/catalogoTotales.php?operacion=8";
	$Ajax(url, {tipoRespuesta: $tipo.JSON, avisoCargando:"loading",onfinish: function(datos)
																		 {
																			$("txtNotaC").value=datos[0].clave;
																		 }
		
		   });
	
}

function datosClientes() 
{	
	var url ="scripts/datosClientes.php?operacion=5&opc=0&codigo="+$("txtRazonS").value;
	$Ajax(url, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function cveCliente(campos) 
{
	var campo = campos[0];
	$('hdncveIva').value=campo.cveImpuesto;
	$('hdncveCliente').value=campo.cveCliente;
	$('txtRazonS').value=campo.razonSocial;
	
	pausecomp(150);
	//Inicializa Autocomplete para Facturas de Cliente
	var url="scripts/catalogoFacturas.php?operacion=2&cliente=" + campo.cveCliente;	
	$("autoFactura").className = "autocomplete";	
	new Ajax.Autocompleter("txtFolioFactura", "autoFactura", url, {paramName: "caracteres",afterUpdateElement:retencion});
	
	$("txtFolioFactura").disabled=false;
}


function datosNota() 
{	
	if ($("txtNotaC").value!="")
	{
		var url="scripts/datosNotac.php?operacion=2&codigo="+$('txtNotaC').value;
		$Ajax(url,{onfinish: llenaNota, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	}else {alert("Ingrese una Nota de Cr\u00E9dito."); document.getElementById("txtNotaC").focus();}
	
}

function llenaNota(campos)
{
	var campo = campos[0];

	$('txtFolioFactura').value=campo.folioFactura;
	$('txtRazonS').value=campo.razonCliente;
	$('hdncveCliente').value=campo.idCliente;

	//Ahora sustituiremos los [br] por un salto de línea
	var regX = /\[br\]/g;
	var replaceString = '\n';
	cadena=campo.descripcionNota;
	descripcion=cadena.replace(regX,replaceString); 

	$('txaFacturas').value=descripcion;

	$('txtSaldo').value=formatoMoneda(campo.saldoDeuda);
	$('hdnSaldo').value=campo.saldoDeuda;
	
	$('txtImporte').value=campo.canImporteTotal;
	$('txthImporteAnterior').value=parseFloat(campo.canImporteTotal);
	$('txthImporteDisponible').value=(parseFloat(campo.canImporteTotal)+parseFloat(campo.saldoDeuda));	
	
	$('txtTotalBruto').value=formatoMoneda(campo.canImporte);
	$('hdnTotalBruto').value=campo.canImporte;
	
	$('txtIva').value=formatoMoneda(campo.canIva);
	$('hdncveIva').value=campo.porcentajeImp;
	
	$('txtRetencion').value=formatoMoneda(campo.canRetencion);
	$('hdncveRetencion').value=campo.porcentajeRet;
	
	$('txtTotal').value=formatoMoneda(campo.canImporteTotal);
	$('hdnTotal').value=campo.canImporteTotal;
	
	//Control a botones y caja
	$('btnModificar').disabled=false;
	$('btnCalcular').disabled=false;
	$('txtSaldo').disabled=true;	
	$('btnModificar').onclick=modificar;
	$('btnCalcular').onclick=calculaTotal2;
	$('txtImporte').onchange=calculaTotal2;
	
}

function pausecomp(millis) 
{
	var date = new Date();
	var curDate = null;
	
	do { curDate = new Date(); } 
	while(curDate-date < millis);
}

function retencion()
{
	var url="scripts/datosNotac.php?operacion=1&codigo="+$('txtFolioFactura').value;
	$Ajax(url,{onfinish: datosNotas, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});	
	$('txtFolioFactura').disabled=true;
	$('btnGuardar').disabled=false;
	$('btnGuardar').onclick=guardar;
	$('btnCalcular').disabled=false;
	$('btnCalcular').onclick=calculaTotal;
}

function datosNotas(campos)
{
	var campo = campos[0];
	$('hdncveRetencion').value=campo.porRet;
	$('hdncveIva').value=campo.porImpuesto;
	$('txtSaldo').value=formatoMoneda(campo.saldo);
	$('hdnSaldo').value=campo.saldo;
	$('txtSaldo').disabled=true;
	$('txtImporte').value=campo.saldo;
	calculaTotal();
	$('txtImporte').onchange=calculaTotal;

}

function calculaIva(importe){
	var porcentaje=	parseInt($("hdncveIva" ).value)/100;
	var iva=importe*porcentaje;
	return iva;
}

function calculaRetencion(importe)
{
	var porcentaje=$('hdncveRetencion').value;
	var retencion=parseFloat(porcentaje*importe/100);
	return retencion;
}

function calculaTotal()
{
	//Que sea npumero, que no este vacío y que no sea igual o menor a cero,que el importe ingresado no sea superior al saldo de la cuenta
	if( (isNaN($('txtImporte').value)) || ($('txtImporte').value=='') || ($('txtImporte').value<0) )
	{	
		alert("El valor del importe es incorrecto.");
	}
	else
	{
		//Checar que el importe ingresado no sea superior al saldo de la cuenta
		if(parseFloat($('hdnSaldo').value)<parseFloat($('txtImporte').value))
		{
			alert("El importe no puede ser mayor al saldo.");
		}
		else
		{
			//Ecuacion
			//totalBruto=totalNeto/((1+impuesto/100)-(retencion/100));
			var impuesto  = 1+($('hdncveIva').value/100);
			var retencion = $('hdncveRetencion').value/100;
			var totalNeto = $('txtImporte').value;
			var totalGrabacion=impuesto-retencion;
			var totalBruto=(totalNeto/totalGrabacion).toFixed(2);
	
			//Ahora si sobre el totalBruto se calculan los impuestos
			var importe=totalBruto;
			var iva=parseFloat(calculaIva(importe));
			var retencion=parseFloat(calculaRetencion(importe));
			$('txtIva').value=formatoMoneda(iva);
			$('txtRetencion').value=formatoMoneda(retencion);
			
			var subtotal=parseFloat(importe + iva);
			$('hdnTotal').value=totalNeto;
			$('txtTotal').value=formatoMoneda(totalNeto);
			
			$('txtTotalBruto').value=formatoMoneda(totalBruto);
			$('hdnTotalBruto').value=totalBruto;
			
			$('btnGuardar').onclick=guardar;	
		}
	}  	
}

function calculaTotal2()
{
	//Que sea npumero, que no este vacío y que no sea igual o menor a cero,que el importe ingresado no sea superior al saldo de la cuenta
	if( (isNaN($('txtImporte').value)) || ($('txtImporte').value=='') || ($('txtImporte').value<0) )
	{	
		alert("El valor del importe es incorrecto.");
	}
	else
	{
		//Checar que el importe ingresado no sea superior al saldo de la cuenta
		if(parseFloat($('txthImporteDisponible').value)<parseFloat($('txtImporte').value))
		{																   
			alert("El importe no puede ser mayor al 'saldo' (dispone de $"+$('txthImporteDisponible').value+" para la nota).");
		}
		else
		{
			//Ecuacion
			//totalBruto=totalNeto/((1+impuesto/100)-(retencion/100));
			var impuesto  = 1+($('hdncveIva').value/100);
			var retencion = $('hdncveRetencion').value/100;
			var totalNeto = $('txtImporte').value;
			var totalGrabacion=impuesto-retencion;
			var totalBruto=(totalNeto/totalGrabacion).toFixed(2);
	
			//Ahora si sobre el totalBruto se calculan los impuestos
			var importe=totalBruto;
			var iva=parseFloat(calculaIva(importe));
			var retencion=parseFloat(calculaRetencion(importe));
			$('txtIva').value=formatoMoneda(iva);
			$('txtRetencion').value=formatoMoneda(retencion);
			
			var subtotal=parseFloat(importe + iva);
			$('hdnTotal').value=totalNeto;
			$('txtTotal').value=formatoMoneda(totalNeto);
			
			$('txtTotalBruto').value=formatoMoneda(totalBruto);
			$('hdnTotalBruto').value=totalBruto;
			
			$('btnGuardar').onclick=guardar;	
		}
	}  	
}

function formatoMoneda(num)
{
	num=parseFloat(num).toFixed(2);
	prefix = '$';
	num += '';
	var splitStr = num.split('.');
	var splitLeft = splitStr[0];
	var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';
	splitRight = splitRight + '00';
	splitRight = splitRight.substr(0,3);
	
	var regx = /(\d+)(\d{3})/;
	
	while (regx.test(splitLeft)) {
		splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
	}
	return prefix + splitLeft + splitRight;
}

function guardar()
{
	//Que sea npumero, que no este vacío y que no sea igual o menor a cero que el importe ingresado no sea superior al saldo de la cuenta
	if( $('txtImporte').value=='' || ($('txtImporte').value<0) )
	{	
		alert("El valor del importe es incorrecto.");
	}
	else
	{
		if(isNaN($('txtImporte').value)) 
			alert("El valor del importe es incorrecto.");
		else{
			//Checar que el importe ingresado no sea superior al saldo de la cuenta
			if($('hdnSaldo').value<$('hdnTotal').value)
			{
				alert("El importe no puede ser mayor al saldo.");
			}
			else
			{
				var valores=tomarValores();		
				$Ajax("scripts/administraNotasC.php?opc=0&cveCliente="+$('hdncveCliente').value, {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
			}
		}
	}
		
}

function modificar()
{
	//Que sea npumero, que no este vacío y que no sea igual o menor a cero que el importe ingresado no sea superior al saldo de la cuenta
	if( (isNaN($('txtImporte').value)) || ($('txtImporte').value=='') || ($('txtImporte').value<0) )
	{	
		alert("El valor del importe es incorrecto.");
	}
	else
	{
		//Checar que el importe ingresado no sea superior al saldo de la cuenta
		if(parseFloat($('txthImporteDisponible').value)<parseFloat($('txtImporte').value))
		{
			alert("El importe no puede ser mayor al 'saldo' (dispone de $"+$('txthImporteDisponible').value+" para la nota).");
		}
		else
		{
			var aumento=0;
			//Checar si se modifico la cantidad de la nota
			if(parseFloat($("txthImporteAnterior").value)<parseFloat($('hdnTotal').value))
			{
				//Aumento la cantidad
				var saldoDeuda=parseFloat($('hdnTotal').value)-parseFloat($("txthImporteAnterior").value);
				var aumento=1;
			}
			else if(parseFloat($("txthImporteAnterior").value)>parseFloat($('hdnTotal').value))
			{
				//Resto la cantidad
				var saldoDeuda=parseFloat($("txthImporteAnterior").value)-parseFloat($('hdnTotal').value);				
				var aumento=2;
			}
			else saldoDeuda=0;
			
			var deuda="&saldoDeuda="+saldoDeuda+"&aumento="+aumento+"&numNota="+$("txtNotaC").value;
			var valores=tomarValores();		
			
			valores+=deuda;
			
			var url="scripts/administraNotasC.php?opc=1&cveCliente="+$('hdncveCliente').value;
			$Ajax(url, {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		}
	}	
}

function tomarValores()
{
	//Quitar signos de más en las cantidades		
	var retencion = $('txtRetencion').value;
	var iva=$('txtIva').value;
	
	retencion = retencion.replace(/[$]/gi,'');
	iva = iva.replace(/[$]/gi,'');	

	//Modificar el valor de los text area, para poder interpretarlo posteriormente correctamente
	//g nos indica que se reemplazarán todas las coincidencias gi significa q es sin importar mayúsicuals y minúsculas
	if (navigator.appName.indexOf("Explorer") != -1) 
		var regX = /\r\n/g;
	else
		var regX = /\n/g;

	var replaceString = '[br]';
	cadena=$('txaFacturas').value;

	descripcion=cadena.replace(regX,replaceString);
	
	var valores="&folioFactura="+$('txtFolioFactura').value+"&idCliente="+$('hdncveCliente').value+
				"&saldo="+$('hdnSaldo').value+"&pago="+$('hdnTotal').value+"&impBruto="+$('hdnTotalBruto').value+					
				"&retencion="+retencion+"&porRetencion="+$('hdncveRetencion').value+"&iva="+iva+"&porIva="+$('hdncveIva').value+
				"&descripcion="+descripcion;
				
	var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
	
	valores=valores +usuario;
	
	return valores;
}

function fin(res)
{	
	alert(res);
	$("status").innerHTML="";
	cancelar();
}
