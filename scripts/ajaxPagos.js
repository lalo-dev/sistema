window.onload = inicia;

function inicia() 
{
	document.getElementById("txtCodigoCliente").focus();
	
	$("btnAplicar").disabled=true;
	$("btnCancelar").disabled=true;
	var tipoEstado=$("hdnTabla").value;
	var url='';
	var url2='';
	
	if(tipoEstado=='corresponsal')
		url="scripts/catalogoClientes.php?operacion=5";
	else
		url="scripts/catalogoClientes.php?operacion=4&opt=0";
	
	$("autoClienteC").className = "autocomplete";
	new Ajax.Autocompleter("txtCodigoCliente", "autoClienteC", url, {paramName: "caracteres",afterUpdateElement:datosClientes1});
	$("autoClienteR").className = "autocomplete";
	new Ajax.Autocompleter("txtRazonSocial", "autoClienteR", url, {paramName: "caracteres",afterUpdateElement:datosClientes2});
	
	$Ajax("scripts/monedasPagos.php?operacion=1", {onfinish: cargaTiposP, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$Ajax("scripts/monedasPagos.php?operacion=2", {onfinish: cargaMonedas, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$Ajax("scripts/monedasPagos.php?operacion=3", {onfinish: cargaBancos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
	$("btnAplicar").onclick=guardar;
	$("btnCancelar").onclick=cancelar;
	$("txtCodigoCliente").disabled=false;
	$("txtRazonSocial").disabled=false;
	 
} 

function cancelar(){   
	var tipoEstado=$("hdnTabla").value;
	location = "pagos.php?dato=" +tipoEstado;
}

function datosClientes1() 
{	
	var tipoEstado=$("hdnTabla").value;
		
	if(tipoEstado=='corresponsal')
	{
		var url2 = "opc=0&codigo="+$("txtCodigoCliente").value;
		$Ajax("scripts/datosCorresponsales.php?operacion=3&"+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	}
	else
	{
		var url2 = "opc=0&cveCliente="+$("txtCodigoCliente").value;
		$Ajax("scripts/datosClientes.php?operacion=3&"+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	}
}

function datosClientes2() 
{	

	var tipoEstado=$("hdnTabla").value;
		
	if(tipoEstado=='corresponsal')
	{
		var url2 = "opc=1&cveCliente="+$("txtRazonSocial").value;
		$Ajax("scripts/datosCorresponsales.php?operacion=3&"+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	}
	else
	{
		var url2 = "opc=1&cveCliente="+$("txtRazonSocial").value;
		$Ajax("scripts/datosClientes.php?operacion=3&"+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	}
}

function cveCliente(campos) 
{
	var campo = campos[0];
	var tipoEstado=$("hdnTabla").value;
	
	$("txtRazonSocial").value=campo.razonSocial;
	
	if(tipoEstado=='corresponsal')
	{
		$("txtCodigoCliente").value=campo.cveCorresponsal;
		var url="scripts/datosPagos.php?operacion=2&corresponsal="+$("txtCodigoCliente").value;
	}
	else
	{
		$("txtCodigoCliente").value=campo.cveCliente;
		 var url="scripts/datosPagos.php?operacion=1&cliente="+$("txtCodigoCliente").value;
	}

	$Ajax(url, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function cargaTiposP(tipos){

	//Borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcTipoPago").options.length = 0;
	//Empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("slcTipoPago").options[$("slcTipoPago").options.length] = opcion;
	
	for (var i=0; i<tipos.length; i++){
		var tipo = tipos[i];
		var opcion = new Option(tipo.desc, tipo.id);		
		try {
			$("slcTipoPago").options[$("slcTipoPago").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
}

function cargaMonedas(monedas){
	
	//Borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcMoneda").options.length = 0;
	//Empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("slcMoneda").options[$("slcMoneda").options.length] = opcion;
	
	for (var i=0; i<monedas.length; i++){
		var moneda = monedas[i];
		var opcion = new Option(moneda.desc, moneda.id);
		
		try {
			$("slcMoneda").options[$("slcMoneda").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
	
}
	
function cargaBancos(bancos){
	
	//Borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcBanco").options.length = 0;
	//Empieza la carga de la lista
	var opcion = new Option("Seleccione", "");
	$("slcBanco").options[$("slcBanco").options.length] = opcion;
	
	for (var i=0; i<bancos.length; i++){
		var banco = bancos[i];
		var opcion = new Option(banco.desc, banco.id);
		
		try {
			$("slcBanco").options[$("slcBanco").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
}


var indiceFilaFormulario=1;

function llenaDatos(campos){

	$("txtCodigoCliente").disabled=true;
	$("txtRazonSocial").disabled=true;
	
	$("datos").className="";
    $("btnCancelar").disabled=false;
	if(document.getElementById("gvwFacturas").rows.length>2){	

	var ultima = document.getElementById("gvwFacturas").rows.length;
		for(var j=ultima; j>2; j--){				
	        document.getElementById("gvwFacturas").deleteRow(2);					 		
		}
	}
	
	for (var i=0; i<campos.length; i++){
		var campo = campos[i];
		if(campo.existe!=0)
		{	
			myNewRow = document.getElementById("gvwFacturas").insertRow(-1); 
			myNewRow.id=indiceFilaFormulario;
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtFecha_"+indiceFilaFormulario+"' name='txtFecha_"+indiceFilaFormulario+"' value='" + campo.fecha + 
			"' readonly='readonly' style='text-align:right;width:80px;'/>";
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtFolioDocumento_"+indiceFilaFormulario+"' name='txtFolioDocumento_"+indiceFilaFormulario+
			"' value='" + campo.FolioDocumento + "' readonly='readonly' style='text-align:right;width:120px;' />";
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtCveTipoDocumento_"+indiceFilaFormulario+"' name='txtCveTipoDocumento_"+indiceFilaFormulario+
			"' value='" + campo.cveTipoDocumento + "' readonly='readonly' style='text-align:right;width:90px;' />";
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtMontoNeto_"+indiceFilaFormulario+"' name='txtMontoNeto_"+indiceFilaFormulario+
			"' value='" + formatoMoneda(campo.montoNeto) + "' readonly='readonly' style='text-align:right;width:120px;' />";
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtDescripcion_"+indiceFilaFormulario+"' name='txtDescripcion_"+indiceFilaFormulario+
			"' value='" + campo.descripcion + "' readonly='readonly' style='text-align:right;width:135px;' />";
			myNewCell=myNewRow.insertCell(-1);
			
			myNewCell.innerHTML="<input type='text' id='txtSaldo_"+indiceFilaFormulario+"' name='txtSaldo_"+indiceFilaFormulario+
			"' value='" + formatoMoneda(campo.saldo) + "' readonly='readonly' style='text-align:right;width:120px;'/>"+
			"<input type='hidden' id='txthSaldo_"+indiceFilaFormulario+"' name='txthSaldo_"+indiceFilaFormulario+
			"' value='" + campo.saldo + "' readonly='readonly' style='text-align:right;width:100px;'/>";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtPago_"+indiceFilaFormulario+"' name='txtPago_"+indiceFilaFormulario+
			"' value='0' style='text-align:right;width:100px;' onchange='chkPago(this.value,this,"+campo.saldo +");'/>";			
				
			indiceFilaFormulario++;
			$('hdnContador').value=indiceFilaFormulario;
			$("btnAplicar").disabled=false;
			
			//Si no hay saldo en el documento éste solo se mostrará de manera informativa, no se podrá tomar en cuenta
			if(campo.saldo<=0)
			{
				elementos=$$("#"+myNewRow.id+" input[type=text]");
				elementos.each(
							   function(elemento) { elemento.disabled=true; }
							  );
			}
			
		}else
		{
			$("btnAplicar").disabled=true;
			myNewRow = document.getElementById("gvwFacturas").insertRow(-1); 			 
			myNewRow.id=indiceFilaFormulario;
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.colSpan=7;
			myNewCell.innerHTML="<td>No hay Documentos Asociados</td>";			
		}
	}
	
}

function chkPago(valor,elemento,saldo)
{
	//Primero checar que el valor introducido se un valor válido
	if(isNaN(valor)||valor<0)
	{
		alert("El valor del pago es incorrecto.");
		elemento.focus();
	}
	else if(valor>saldo)
	{
		alert("El pago no puede ser mayor al saldo.");
		elemento.focus();
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

function guardar(){ 

	//Primero checar que los datos sean correctos	
	var continuar=true;
	if($("txtMonto").value == "" || $("txtMonto").value <=0 || isNaN($("txtMonto").value))
	{
		$("txtMonto").addClassName("invalid");
		alert("El monto es incorrecto.");
		var continuar=false;
	}
	else
	{
		$("txtMonto").removeClassName("invalid");
		var notGood=0;		
		if($("slcBanco").value == ""){$("slcBanco").addClassName("invalid"); notGood ++;} else{$("slcBanco").removeClassName("invalid");}
		if($("slcTipoPago").value == ""){$("slcTipoPago").addClassName("invalid"); notGood ++;} else{$("slcTipoPago").removeClassName("invalid");}
		if($("slcMoneda").value == ""){$("slcMoneda").addClassName("invalid"); notGood ++;} else{$("slcMoneda").removeClassName("invalid");}
		if(notGood>0)
		{
			alert("\u00A1Hay Informaci\u00F3n err\u00F3nea que ha sido resaltada en color!");
			var continuar=false;
		}
	}
	
	if(continuar)
	{			
		var totalpagos=$('hdnContador').value;
		var tabla=$("hdnTabla").value;
		var valores="";
		var suma=0;
		var indice=1;
		var deuda=0;
		var documentosOrden="";
		//Se toman los pagos
		var datosCorrectos=true;
		for (var i=1;i<totalpagos;i++)
		{ 	
			if(parseFloat($('txtPago_'+i).value)>parseFloat($('txthSaldo_'+i).value))
			{
				alert("El pago no puede ser mayor al saldo.");
				document.getElementById("txtPago_"+i).focus();
				var datosCorrectos=false;
				break;
			}
			else if(isNaN($('txtPago_'+i).value)||$('txtPago_'+i).value<0)
			{
				alert("El valor del pago es incorrecto.");
				document.getElementById("txtPago_"+i).focus();
				var datosCorrectos=false;
				break;
			}
			else if($('txtPago_'+i).value!=0)
			{
				saldo = $('txtSaldo_'+i).value.replace(/[$]/gi,'');
	
				suma+=parseFloat($('txtPago_'+i).value);
				valores+="&txtFolioDocumento_"+indice+"="+$('txtFolioDocumento_'+i).value	+"&txtCveTipoDocumento_"+indice+"="+$('txtCveTipoDocumento_'+i).value	
				+"&txtSaldo_"+indice+"="+saldo+"&txtPago_"+indice+"="+$('txtPago_'+i).value; 			
				documentosOrden+=indice+". Doc:  "+$('txtFolioDocumento_'+i).value+"   Pago: "+formatoMoneda($('txtPago_'+i).value)+"\n";                   
				indice++;
			}
			deuda+=parseFloat($('txthSaldo_'+i).value);
		}
		
		if(datosCorrectos)
		{
			deuda=Math.round((parseFloat(deuda))*100)/100 ;
			//Checar que el monto no sea superior al total de la deuda
			if(parseFloat($('txtMonto').value)>deuda)
			{
				alert('El monto recibido no puede ser superior al total de deuda del cliente.');
			}
			else if(parseFloat($('txtMonto').value)!=parseFloat(suma))
				alert("El detalle de pagos debe ser igual que el monto de pago.");
			else
			{
				//Valores generales
				valores+="&slcMoneda="+$('slcMoneda').value	+"&slcTipoPago="+$('slcTipoPago').value	+"&slcBanco="+$('slcBanco').value+
				"&txtDocumento="+$('txtDocumento').value; 
				
				var usuario="&usuario="+$("hdnUsuario").value + "&empresa="+$("hdnEmpresaS").value;	
				valores=valores+usuario;
				
				if(confirm("\u00BFEst\u00E1 seguro que desea aplicar los siguientes pagos?\n"+documentosOrden))
				{
					var url="scripts/guardaPagos.php?contador="+(indice-1)+"&cveCliente="+$('txtCodigoCliente').value+"&tabla="+tabla;
					$Ajax(url, {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});	
				}
			    
			}
		}	
	}
}
 
function removePerson(obj){ 
	var oTr = obj;
	while(oTr.nodeName.toLowerCase()!='tr'){
		oTr=oTr.parentNode;
	}
	var root = oTr.parentNode;
	root.removeChild(oTr);
}
 
function fin(res){
	alert(res);
	var tipoEstado=$("hdnTabla").value;
	
	if(tipoEstado=='corresponsal')
		var url="scripts/datosPagos.php?operacion=2&corresponsal="+$("txtCodigoCliente").value;
	else
		 var url="scripts/datosPagos.php?operacion=1&cliente="+$("txtCodigoCliente").value;
		 
	indiceFilaFormulario=1;
	$('txtMonto').value="";
	$('txtDocumento').value="";
	$("status").innerHTML="";
	
	$Ajax(url, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

}
 
function validaGuia(){
	
	var contador=parseInt($('hdnContador').value);	
	var guia=$('txtGuia').value;
	
	var coincide=0;
	for (var i=1; i<contador; i++){
		var guiaGuardada=$('txtGuia_'+i).value;
		if(guia==guiaGuardada)
			coincide=1;
	}
	
	if(coincide>0)
		alert("La guia no se agrego porque ya exixte en la factura");
	else
		camposb();
}
