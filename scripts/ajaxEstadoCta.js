window.onload = inicia;

function inicia() 
{
	document.getElementById("txtCodigo").focus();
	
	var tipoEstado=$("hdnTabla").value;
	var url='';
	var url2='';
	
	if(tipoEstado=='corresponsal')
		url="scripts/catalogoClientes.php?operacion=5";
	else
		url="scripts/catalogoClientes.php?operacion=4";
	
	$("autoCodigo").className = "autocomplete";
	new Ajax.Autocompleter("txtCodigo", "autoCodigo",url , {paramName: "caracteres",afterUpdateElement:datosCliCorr});
	
	$("autoRazon").className = "autocomplete";
	new Ajax.Autocompleter("txtRazonSocial", "autoRazon",url , {paramName: "caracteres",afterUpdateElement:datosCliCorr2});

	$("btnBuscar").onclick=buscar;
	
	$("btnCancelar").onclick=function(){
		var tipoEstado=$("hdnTabla").value;
		location = "EstadoCta.php?dato="+tipoEstado;
	};
	
	$("btnBuscar").disabled=true;
	$("btnImprimir").disabled=true;
	$("btnCancelar").disabled=true;
}

function datosCliCorr() 
{
	var tipoEstado=$("hdnTabla").value;
	
	codigo=$("txtCodigo").value;
	
	if(tipoEstado=='corresponsal')
		var url ="scripts/datosCorresponsales.php?operacion=3&opc=0&codigo=" + codigo;
	else
		var url ="scripts/datosClientes.php?operacion=3&cveCliente=" + codigo;

	$Ajax(url, {onfinish: cveClieCorr, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function datosCliCorr2() 
{
	var tipoEstado=$("hdnTabla").value;
	codigo=$("txtRazonSocial").value;
	
	if(tipoEstado=='corresponsal')
		var url ="scripts/datosCorresponsales.php?operacion=3&opc=1&codigo=" + codigo;
	else
		var url ="scripts/datosClientes.php?operacion=3&cveCliente=" + codigo;

	$Ajax(url, {onfinish: cveClieCorr, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
		
function cveClieCorr(campos) 
{

	var campo = campos[0];
	var tipoEstado=$("hdnTabla").value;
	
	if(tipoEstado=='corresponsal')
		$("txtCodigo").value=campo.cveCorresponsal;
	else
		$("txtCodigo").value=campo.cveCliente;
	
	$("txtRazonSocial").value=campo.razonSocial;
	
	$("txtCodigo").disabled=true;
	$("txtRazonSocial").disabled=true;		
	$("btnBuscar").disabled=false;
}

function buscar()
{ 
	if(allgood()){
		var inicio  = $('txtFecha1').value;
		var termino = $('txtFecha2').value;
		var tabla= $("hdnTabla").value;
		
		if($("hdnTabla").value=='corresponsal')
			var opcion=2;
		else
			var opcion=1;
			
		var url="scripts/catalogoEdoCta.php?codigo="+$('txtCodigo').value	+"&inicio="+inicio	+"&termino="+termino+"&opc="+opcion; 
		$Ajax(url, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	 }
}

var indiceFilaFormulario=1;
function llenaDatos(campos)
{
		

	$('dvdDatos').className="";
	var dato=campos[0];
	
	saldoI=(dato.saldoI=="") ? 0 : dato.saldoI;
	saldoF=(dato.saldoF=="") ? 0 : dato.saldoF;
		
	$("lblFecha1").innerHTML="<label class='message' for='element_3'>" +$('txtFecha1').value + "</label>";
	$("lblSaldo1").innerHTML="<label class='message' for='element_3'>" + formatoMoneda(saldoI) + "</label>";
	$("lblFecha2").innerHTML="<label class='message' for='element_3'>" +$('txtFecha2').value + "</label>";
	$("lblSaldo2").innerHTML="<label class='message' for='element_3'>" + formatoMoneda(saldoF)+ "</label>";
	
	$("btnImprimir").disabled=false;
	$("btnCancelar").disabled=false;

	//Limpiar primero la tabla
	var ultima = document.getElementById("gvwDocumentos").rows.length;
	for(var j=ultima;j>1; j--){				
		document.getElementById("gvwDocumentos").deleteRow(1);					 		
	}

	
	//Checar si el total fue cero
	if(campos[0].total==0)
	{
		myNewRow = document.getElementById("gvwDocumentos").insertRow(-1); 			 
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.colSpan=7;
		myNewCell.innerHTML="No hay Cuentas Asociadas";		
	}
	else
	{
		for (var i=0; i<campos.length; i++)
		{
			var campo = campos[i];
	
			myNewRow = document.getElementById("gvwDocumentos").insertRow(-1); 
			myNewRow.id=indiceFilaFormulario;
		
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.height=25;
			myNewCell.innerHTML=campo.fecha;
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.height=25;
			myNewCell.innerHTML=campo.folioDocumento;
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.height=25;
			myNewCell.innerHTML=campo.cveTipoDocumento;
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.height=25;
			myNewCell.innerHTML=campo.ref;
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.height=25;
			myNewCell.style.textAlign='right';
			myNewCell.innerHTML=formatoMoneda(campo.Cargo);		
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.height=25;
			myNewCell.style.textAlign='right';
			myNewCell.innerHTML=formatoMoneda(campo.Abono);
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.height=25;
			myNewCell.style.textAlign='right';
			myNewCell.innerHTML=formatoMoneda(campo.saldo);
			
			indiceFilaFormulario++;
		}
	}
	$("btnImprimir").onclick=imprimir;
}

function allgood()
{
	//Checar que las fechas ya se de Entrega o la de Acuse esten correctas
	var expresion = /^\s*(\d{2,2})\/(\d{2,2})\/(\d{4,4})\s*$/;
	if($("txtFecha1").value=="" || $("txtFecha2").value=="")
	{
		alert("Debe ingresar la fecha Incial y Final."); 
		return false;
	}
	else
	{

		//Obtener datos de la fecha
		valoresI=$('txtFecha1').value.split("/");
		valoresF=$('txtFecha2').value.split("/");
		if(!($("txtFecha1").value.match(expresion)))
			{$("txtFecha1").addClassName("invalid"); alert("El formato de la fecha Incial es incorrecto (dd/mm/yyyy)."); return false;} 
		else
		{
			$("txtFecha1").addClassName("invalid");
			if (valoresI[1] < 1 || valoresI[1] > 12){
				alert ("Valor de mes no v\u00E1lido: '" + valoresI[1] + "'.\nEl rango permitido es de 01 a 12.");
				return false;
			}
			else{
				var d_numdays = new Date(valoresI[2], valoresI[1], 0);
				if (valoresI[0] > d_numdays.getDate()){
					alert("D\u00EDa de mes no v\u00E1lido: '" + valoresI[0] + "'.\nEl rango permitido para el mes seleccionado es de 01 a " + d_numdays.getDate() + ".");
					return false;
				}
				else
					$("txtFecha1").removeClassName("invalid");
			}
		}
		
		if(!($("txtFecha2").value.match(expresion)))
			{$("txtFecha2").addClassName("invalid"); alert("El formato de la fecha Final es incorrecto (dd/mm/yyyy)."); return false;} 
		else
		{
			$("txtFecha2").addClassName("invalid");

			if (valoresF[1] < 1 || valoresF[1] > 12){
				alert ("Valor de mes no v\u00E1lido: '" + valoresI[1] + "'.\nEl rango permitido es de 01 a 12.");
				return false;
			}
			else{
				var d_numdays = new Date(valoresF[2], valoresF[1], 0);
				if (valoresF[0] > d_numdays.getDate()){
					alert("D/u00EDa de mes no v\u00E1lido: '" + valoresF[0] + "'.\nEl rango permitido para el mes seleccionado es de 01 a " + d_numdays.getDate() + ".");
					return false;
				}
				else
					$("txtFecha2").removeClassName("invalid");
			}
		}
		//Checar que la fecha de 2 no sea inferior a la 1
		valores=$('txtFecha1').value.split("/");
		inicio = new Date(valores[2],valores[1],valores[0]).getTime();
		
		valores=$('txtFecha2').value.split("/");
		fin = new Date(valores[2],valores[1],valores[0]).getTime();
		if(fin<inicio)
		{
			alert("La fecha final es mayor a la incial.");
			return false;				
		}	
	}
	return true;
}


 
function imprimir()
{
	var inicio=$('txtFecha1').value;
	var termino=$('txtFecha2').value;
	
	var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
	
	var opcion=($("hdnTabla").value=='corresponsal')?2:1;
	var titulo=($("hdnTabla").value=='corresponsal')?'Corresponsal':'Cliente';
	
	var url="scripts/pdfEdoCta.php?codigo="+$('txtCodigo').value	+"&inicio="+inicio	+"&termino="+termino+"&opc="+opcion+usuario; 
	
	var win = new Window({className: "mac_os_x", title: "Estado de Cuenta del "+titulo, top:70, left:100, width:1200, height:500, url: url, showEffectOptions: {duration:1.5}});
	win.show(); 		
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
	$("txtDocumento").value=res;
	alert(res);
	$("status").innerHTML="";
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
