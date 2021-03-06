window.onload = inicia;

function inicia() {	

	//Inicializando autocomplete
	$("autoCliente").className = "autocomplete";
	new Ajax.Autocompleter("txtRazonS", "autoCliente", "scripts/catalogoClientes.php?operacion=5", {paramName: "caracteres", afterUpdateElement:datosCorresponsales2});
	
	$("autoClienteC").className = "autocomplete";
	new Ajax.Autocompleter("txtCodigo", "autoClienteC", "scripts/catalogoClientes.php?operacion=5", {paramName: "caracteres",afterUpdateElement:datosCorresponsales1});
	
	$("btnCancelar").onclick=cancelar;

	$("btnGuardar").disabled=true;
	$("btnGuardar").onclick=guardar;
	$("btnImprimir").onclick=guardaPrevia;
	
	
	//Evaluaremos seg�n usuario, las acciones que podr� realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("btnGuardar").style.visibility="hidden";	
		$("btnGuardar").style.display="none";	
	}
	
	$("autoGuia").className = "autocomplete";
	new Ajax.Autocompleter("txtGuia", "autoGuia", "scripts/catalogoGuias.php?operacion=6", {paramName: "caracteres",afterUpdateElement:datosGuia});
	bloquear(2,17,0);
}

function cancelar()
{
	//Quitar los elementos de la tabla primero
	tabla=document.getElementById("guiasCorresponsal");
	for(var j=tabla.rows.length;j>1; j--)
	{
		tabla.deleteRow(1);	
	}
	
	elementos=$$("#pnlDatosGenerales input[type=text],#pnlDatosGenerales select");
    //Limpiar cajas, deshabilitar, y quitar clase de inv�lido
	for(i=0;i<elementos.length;i++) 
    { 
		elementos[i].removeClassName("invalid");
		elementos[i].value="";
    }
	bloquear(0,3,1);	
	$("sltAnyo").disabled=false;
	bloquear(2,17,0);
	
	//Limpiar divs
	$('divGuias').innerHTML="";
	$('divFac').innerHTML="";
	$("status").innerHTML="";
	$('divEstacionGuia').innerHTML="";
}
 
function bloquear(inicio,fin,opcion) 
{ 
	var habilitado=(opcion==0)?true:false;
	elementos=$$("#pnlDatosGenerales input[type=text],#pnlDatosGenerales input[type=button],#pnlDatosGenerales select");
    //Dependiendo del numero indicado se deshabilitaran los controles
	for(i=inicio;i<(fin-1);i++) 
    { 
		elementos[i].disabled=habilitado; 
    }
} 

function datosCorresponsales1() 
{	
	var url2 = "opc=1&codigo="+$("txtCodigo").value;
	$Ajax("scripts/datosCorresponsales.php?operacion=3&"+url2, {onfinish: cveCorresponsal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function datosCorresponsales2() 
{	
	var url2 = "opc=0&codigo=" + $("txtRazonS").value;		
	$Ajax("scripts/datosCorresponsales.php?operacion=3&"+url2, {onfinish: cveCorresponsal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function cveCorresponsal(campos) 
{
	var campo = campos[0];
	$("txtCodigo").value=campo.cveCorresponsal;
	$("txtRazonS").value=campo.razonSocial;
	$("txtPorIva").value=campo.cveImp;
	
	$("autoFactura").className = "autocomplete";	
	var urlG="scripts/catalogoFacturasC.php?operacion=1&corresponsal="+$("txtCodigo").value+"&anyo="+$("sltAnyo").value;
	new Ajax.Autocompleter("txtFolioFactura", "autoFactura",urlG , {paramName: "caracteres", afterUpdateElement:existe});
	$("txtFolioFactura").onchange = existe;	
	
	//Desbloquear campos
	bloquear(2,8,1);
	$("btnCancelar").disabled=false;
	//Bloquear campos
	bloquear(0,3,0);
	$("sltAnyo").disabled=true;
}

function redondeo2decimales(numero)
{
	var original=parseFloat(numero);
	var result=Math.round(original*100)/100 ;
	return result;
}

function formatoMoneda(num)
{
	num = Math.round(parseFloat(num)*Math.pow(10,2))/Math.pow(10,2);
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

function existe() 
{
	$("status").innerHTML="";
	if ($("txtFolioFactura").value!="" )
	{	
		var url = "scripts/existe.php?keys=28&f1value="+$("txtFolioFactura").value+"&corresponsal="+$('txtCodigo').value+"&anyo="+$('sltAnyo').value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}
}

//Esta funcion recibe el valor de la funci�n anterior y lo eval�a	
function next_existe(ex){	

	//Deshabilitamos el c�digo
	$("txtCodigo").disabled=true;
	$("txtRazonS").disabled=true;
	$("txtFolioFactura").disabled=true;
	
	//Extraemos el valor retornado por el servidor en un objeto json	
	var exx=ex[0];
	var exists = exx.existe;
	
	//Si el valor es mayor que cero, entonces el registro existe
	if (exists > 0)
	{	
		//Traer los datos de la Factura	 
		var url = "scripts/datosfacturasC.php?op=5&corresponsal="+$("txtCodigo").value+"&factura="+$("txtFolioFactura").value+"&anyo="+$("sltAnyo").value;
		$Ajax(url,{onfinish: function (datos)
							 {
								dato=datos[0];
								
								//Datos de las gu�as
								var texto="Folio Factura: "+$("txtFolioFactura").value+
										  "<br /><br /> A&ntilde;o Factura: "+$("sltAnyo").value;
								
								if($('txtCodigo').value!=$("txtCodigo").value)
									texto+="<br /><br /> C&oacute;digo Corresponsal: "+$("txtCodigo").value;
								
								texto+="<br /><br /> Importe Factura: $"+dato.importe+
									   "<br /><br /> Saldo Factura: $"+dato.saldoFac;
								
								if(dato.saldoFac==0)
									texto+="<br /><br /> SALDADA";
								
								$('divFac').innerHTML=texto;
								$('divFac').style.color="#0080FF";
								$('divFac').style.color="#0080FF";
								$('divGuias').innerHTML="";
								
							 }, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
		//Cargamos datos
	   	var url = "scripts/datosfacturasC.php?op=1&corresponsal="+$("txtCodigo").value+"&factura="+$("txtFolioFactura").value+"&anyo="+$("sltAnyo").value;
		$Ajax(url, {onfinish: llenaFactura, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
		//Bloquear las gu�as
		$('txtGuia').disabled=true;

		//Controles
		$("btnImprimir").disabled=true;
		$("btnGuardar").disabled=true;

		$("btnVerFactura").disabled=false;
		$("btnVerFactura").onclick=verFactura;		
		$("btnModificar").disabled=false;
		$("btnModificar").onclick=modificar;

		//Imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
	}
	else
	{	
		//Controles
		$("btnGuardar").disabled=false;
		$("btnImprimir").disabled=false;
		//Controles
		$("btnImprimir").disabled=false;
		$("btnGuardar").disabled=false;

		$("btnVerFactura").disabled=true;

		$("btnModificar").disabled=true;

		//Imprimimos un aviso de que se trata de un registro nuevo
		$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
		
		$('divFac').innerHTML="";
		$('divGuias').innerHTML="";
	}
}

function llenaFactura(datos)
{
	dato=datos[0];
	
	//Colocamos los datosgenerales de la Factura
	
	$('txthAnyo').value=dato.anyo;	
	$('txthFolioFactura').value=dato.factura;	
	$('txtfechaFactura').value=dato.fechaFac;
	$('txtPorIva').value=dato.porIva;
	$('txtPorRetencion').value=dato.porRet;
	$('txtImporte').value=dato.impBruto;
	$('txtIva').value=dato.totIva;
	$('txtRetencion').value=dato.totRet;
	$('txtTotal').value=dato.impNeto;	
	
	//Consultamos los datos de las gu�as involucradas en la factura
		//Primero limpiar la tabla
	tabla=document.getElementById("guiasCorresponsal");
	for(var j=tabla.rows.length;j>1; j--)
	{
		tabla.deleteRow(1);	
	}
	
   	var url = "scripts/datosfacturasC.php?op=2&corresponsal="+$("txtCodigo").value+"&factura="+$("txtFolioFactura").value+"&anyo="+$("sltAnyo").value;
	$Ajax(url, {onfinish: llenaGuias, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	//Totales
	$('txtImporte').disabled=false;
	$('txtIva').disabled=false;
	$('txtRetencion').disabled=false;
	$('txtTotal').disabled=false;
}

function llenaGuias(datos)
{
	totalGuias=datos.length;
	
	for(i=0;i<totalGuias;i++)
	{
		dato=datos[i];
			//Tomamos los datos
		var guia=dato.noGuia;
		var tipoE=dato.tipoE;
		var pzas=dato.pzas;
		var peso=dato.peso;
		var noTarifa=dato.noTarifa;
		var ctoEntrega=dato.ctoEntrega;
		var sobrePeso=dato.sobrePeso;
		var ctoSobrePeso=dato.ctoSobrePeso;
		var noDistancia=dato.noDistancia;		
		var ctoDistancia=dato.ctoDistancia;
		var ctoEspecial=dato.ctoEspecial;
		var noViaticos=dato.noViaticos;
		var noGuiaAerea=dato.noGuiaAerea;
		var noExtra1=dato.noExtra1;			
		var noExtra2=dato.noExtra2;
		var obs=dato.obs;	
		var noTotal=dato.noTotal;			
		var cargoMin=dato.cargoMin;		
		var detalle=dato.detalle;		

		myNewRow = document.getElementById("guiasCorresponsal").insertRow(-1); 
		myNewRow.id=indiceFilaFormulario;
		
			//Los primeros tres input no se puden modificar
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtGuia_"+indiceFilaFormulario+"' name='txtGuia_"+indiceFilaFormulario+"' size='5' value='"+guia+"' readonly='readonly' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtTipoE_"+indiceFilaFormulario+"' name='txtTipoE_"+indiceFilaFormulario+"' size='10' value='"+tipoE+
		"' readonly='readonly'/>";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtPiezas_"+indiceFilaFormulario+"' name='txtPiezas_"+indiceFilaFormulario+"' size='5' maxlength='120' "+
		"value='"+pzas+"' readonly='readonly' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input name='txtKilos_"+indiceFilaFormulario+"' type='text' id='txtKilos_"+indiceFilaFormulario+"' size='5' value='"+peso+
		"' onchange='calculaTarifa(this)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtTarifa_"+indiceFilaFormulario+"' class='moneda' name='txtTarifa_"+indiceFilaFormulario+"' size='7' value='"+ 
		noTarifa+"' onchange='calculaTarifa(this)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtCostoE_"+indiceFilaFormulario+"' class='moneda' name='txtCostoE_"+indiceFilaFormulario+"' size='7' value='"+ 
		ctoEntrega+"' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1);	
		myNewCell.innerHTML="<input type='text' id='txtSobrepeso_"+indiceFilaFormulario+"' name='txtSobrepeso_"+indiceFilaFormulario+
		"' size='7' maxlength='120' value='"+sobrePeso+"'  class='moneda' onchange='calculaSobrepeso(this)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtCostoS_"+indiceFilaFormulario+"' class='moneda' name='txtCostoS_"+indiceFilaFormulario+
		"' size='7' value='"+ctoSobrePeso+"' size='8' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input name='txtDistancia_"+indiceFilaFormulario+"' type='text' id='txtDistancia_"+indiceFilaFormulario+
		"' size='7' value='"+noDistancia+"' size='8' onchange='calculaDistancia(this)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1); 
		myNewCell.innerHTML="<input type='text' id='txtCostoD_"+indiceFilaFormulario+"' class='moneda' name='txtCostoD_"+indiceFilaFormulario+
		"' size='7' value='"+ctoDistancia+"' size='8' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtCostoEs_"+indiceFilaFormulario+"' class='moneda' name='txtCostoEs_"+indiceFilaFormulario+
		"' size='7' value='"+ctoEspecial+"' size='8' class='moneda' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtViaticos_"+indiceFilaFormulario+"' class='moneda' name='txtViaticos_"+indiceFilaFormulario+
		"' size='7' value='"+noViaticos+"' maxlength='120' size='8' class='moneda' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtGuiaAerea_"+indiceFilaFormulario+"' name='txtGuiaAerea_"+indiceFilaFormulario+
		"' size='7' value='"+noGuiaAerea+"' maxlength='120' size='8' readonly='readonly'/></td>";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input name='txtExtra1_"+indiceFilaFormulario+"' type='text' id='txtExtra1_"+indiceFilaFormulario+
		"' size='7' value='"+noExtra1+"' size='10' class='moneda' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtExtra2_"+indiceFilaFormulario+"' name='txtExtra2_"+indiceFilaFormulario+
		"' size='7' value='"+noExtra2+"' size='10' class='moneda' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtObservaciones_"+indiceFilaFormulario+"' name='txtObservaciones_"+indiceFilaFormulario+
		"' value='"+obs+"' size='10' class='moneda'/>";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' id='txtTotal_"+indiceFilaFormulario+"' class='moneda' name='txtTotal_"+indiceFilaFormulario+
		"' value='"+noTotal+"' size='7' class='moneda' onchange='calculaTotalesPre(this,0)' onfocus='guardaAnterior(this)' />";
		
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='hidden' id='hdnDetalle_"+indiceFilaFormulario+"' name='hdnDetalle_"+indiceFilaFormulario+
		"' value='"+detalle+"' /><input type='hidden' id='hdnCargoM_"+indiceFilaFormulario+"' name='hdnCargoM_"+indiceFilaFormulario+
		"' value='"+cargoMin+"' /><input type='button'  value='Eliminar' onclick='eliminarGuia("+guia+",this)'>";
				
		indiceFilaFormulario++;
	}
	
}
		
function eliminarGuia(guia,obj)
{
	//Primero verificar que de quitar ese renglon la factura NO quede vac�a
	var tabla=document.getElementById("guiasCorresponsal");
	if(tabla.rows.length==2)
	{
		alert("No puede dejar en ceros la factura.");
	}
	else
	{
		//Preguntar si se desea eliminar la gu�a
		if(confirm("\u00BFEst� seguro que desea eliminar la gu\u00EDa "+guia+" de la factura?\n(ya no podr\u00E1 ser agregada posteriormente a esta factura)"))
		{
			//Eliminar la gu�a de la factura y de la tabla
			if(recalcularTotal(guia))
			{
				//Obtenemos el reglon
				var td     = obj.parentNode;
				var tr     = td.parentNode;
				var numero = tr.id;
					//Tomamos los datos
				valores="&total="+$('txtTotal_'+numero).value+"&detalle="+$('hdnDetalle_'+numero).value;
				
				valores+="&anyoFactura="+$('sltAnyo').value+"&txtImporteF="+$('txtImporte').value+"&txtIvaF="+$('txtIva').value
						  +"&txtRetencionF="+$('txtRetencion').value+"&txtTotalF="+$('txtTotal').value
						  +"&txtFolioFactura="+$('txtFolioFactura').value+"&txtCodigo="+$("txtCodigo").value
						  +"&txtfechaFactura="+$("txtfechaFactura").value+"&txtPorIva="+$('txtPorIva').value
						  +"&txtPorRetencion="+$("txtPorRetencion").value+"&usuario="+$("hdnUsuario").value
						  +"&empresa="+$("hdnEmpresaS").value+"&facAntigua="+$("txthFolioFactura").value+
						  +"&anyoAntiguo="+$("txthAnyo").value;

				var factura=$("txtFolioFactura").value;
				var url="scripts/actualizarFacturaC.php?opcion=0&factura="+factura+"&guia="+guia;

				$Ajax(url,{onfinish: function(res)
									 {
										//Analizamos la respuesta para saber si eliminar el renglon o no
										resultado=res.split("-");
										alert(resultado[0]);
										//Se compelto la operaci�n exitosamente
										if(resultado[1]==1)
											tr.remove();
											
									},metodo: $metodo.POST, parametros: valores, avisoCargando:"loading" });				
			}
		}
	}
}

function recalcularTotal(guia)
{		
	var totalImporte=0;
	var totalIva=0;
	var totalRetencion=0;
	var totalTotal=0;
	var totales=$$('[id^=txtTotal_]'); 
	var guias=$$('[id^=txtGuia_]'); 
	var contador=guias.length;
	
	for (var i=0; i<contador; i++)
	{
		if(guia!=guias[i].value)
			totalImporte=totalImporte+parseFloat(totales[i].value);
	}
	
	var porcentajeIva=($("txtPorIva").value=='')?0:parseInt($("txtPorIva").value)/100;
	var porcentajeRetencion=($("txtPorRetencion").value=='')?0:parseInt($("txtPorRetencion").value)/100;
	
	
	$("txtImporte").value=totalImporte.toFixed(2);
	
	totalIva=porcentajeIva*totalImporte;
	$("txtIva").value=totalIva.toFixed(2);
	
	totalRet=porcentajeRetencion*totalImporte;
	$("txtRetencion").value=totalRet.toFixed(2);
	
	totalTotal=totalImporte+totalIva-totalRet;
	$("txtTotal").value=totalTotal.toFixed(2);
	
	return true;
}

function msjAct(res)
{
	alert(res);
	//Separamos la
}

function datosGuia()
{
   	//Antes de agregar la gu�a, mostraremos la informaci�n de la gu�a		
	var url = "scripts/datosfacturasC.php?op=4&noGuia="+$("txtGuia").value;
	$Ajax(url, {onfinish: function (datos)
						  {
							  dato=datos[0];
							  //Mostrar informaci�n de la gu�a							  
							  if(dato.existe==0)
							  {
									$('divGuias').style.color="#0080FF";
									$('divGuias').innerHTML="GU&Iacute;A "+dato.noGuia+" NO FACTURADA.";
									$('divFac').style.color="#0080FF";
									$('divFac').innerHTML="<br /> Estado: "+dato.edoGuia;
							  }
							  else
							  {
								    var texto="GU&Iacute;A "+dato.noGuia+" FACTURADA.";
									$('divGuias').innerHTML=texto;
									$('divGuias').style.color="#F00";

									var texto="Estado: "+dato.edoGuia+
											  "<br /><br /> Folio Factura: "+dato.noFac+
											  "<br /><br /> A&ntilde;o Factura: "+dato.anyoFac;
											  
									if($('txtCodigo').value!=dato.noCorr)
										texto+="<br /><br /> C&oacute;digo Corresponsal: "+dato.noCorr;
							  
									texto+="<br /><br /> Importe Factura: $"+dato.importe+
										   "<br /><br /> Saldo Factura: $"+dato.saldoFac;
							
									if(dato.saldoFac==0)
										texto+="<br /><br /> SALDADA";

									$('divFac').innerHTML=texto;
									$('divFac').style.color="#F00";
							  }
							  //Mostrar la estaci�n y municipio de la gu�a
							  $('divEstacionGuia').innerHTML="Estaci\u00F3n :"+dato.estacion+"<br />  Municipio :"+dato.municipio;
							  
							  //Traer los datos de la gu�a
							  var url = "scripts/costoGuias.php?guia="+$("txtGuia").value+"&corresponsal="+$("txtCodigo").value;
						  
							$Ajax(url, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
						  }
		  			      , tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function guardaAnterior(obj)
{
	valAnterior=obj.value;
}

//Se guardar� el valor anterior a el cambio
var valAnterior='';
var indiceFilaFormulario=1;
function llenaDatos(campos){
	
	var peso=0;
	var totalImporte=0;
	var totalIva=0;
	var totalSubtotal=0;
	var totalRetencion=0;
	var totalTotal=0;
	
	var indice=campos[0].indice;
		
	//Primero verificar que no se repita la gu�a
	guias=$$('[id^=txtGuia_]');
	var guia=$('txtGuia').value;
	var coincide=0;	
	for (var i=0;i<guias.length;i++)
	{
		var guiaGuardada=guias[i].value;
		if(guia==guiaGuardada)
		{  
			coincide=1;	
			break;
		}
	}
	
	if(coincide>0){ alert("La gu\u00EDa no se agreg\u00F3 porque ya existe en el listado.");}
	else
	{
		if(indice==1)	//No existe la tarifa para realizar los c�lculos
		{
			var guia=campos[0].cveGuia;
			alert("No existe una tarifa para la gu\u00EDa "+guia+".");
		}
		
		for (var i=0; i<campos.length; i++)
		{						  
			var campo = campos[i];
			myNewRow = document.getElementById("guiasCorresponsal").insertRow(-1); 
			myNewRow.id=indiceFilaFormulario;
			
			//Los primeros tres no se puden modificar
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtGuia_"+indiceFilaFormulario+"' name='txtGuia_"+indiceFilaFormulario+"' size='5' value='"+ campo.cveGuia +"' readonly='readonly' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtTipoE_"+indiceFilaFormulario+"' name='txtTipoE_"+indiceFilaFormulario+"' size='10' value='"+ campo.tipoEnvio +"' readonly='readonly'/>";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtPiezas_"+indiceFilaFormulario+"' name='txtPiezas_"+indiceFilaFormulario+"' size='5' maxlength='120' "+
			"value='"+ campo.piezas +"' readonly='readonly' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input name='txtKilos_"+indiceFilaFormulario+"' type='text' id='txtKilos_"+indiceFilaFormulario+"' size='5' value='"+ 
			campo.kg +"' onchange='calculaTarifa(this)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtTarifa_"+indiceFilaFormulario+"' class='moneda' name='txtTarifa_"+indiceFilaFormulario+"' size='7' value='"+ 
			campo.cargo +"' onchange='calculaTarifa(this)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtCostoE_"+indiceFilaFormulario+"' class='moneda' name='txtCostoE_"+indiceFilaFormulario+"' size='7' value='"+ 
			campo.costoEntrega +"' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1);	
			myNewCell.innerHTML="<input type='text' id='txtSobrepeso_"+indiceFilaFormulario+"' name='txtSobrepeso_"+indiceFilaFormulario+
			"' size='7' maxlength='120' value='"+campo.sobrepeso+"'  class='moneda' onchange='calculaSobrepeso(this)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtCostoS_"+indiceFilaFormulario+"' class='moneda' name='txtCostoS_"+indiceFilaFormulario+
			"' size='7' value='"+campo.ctoSobrePeso+"' size='8' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input name='txtDistancia_"+indiceFilaFormulario+"' type='text' id='txtDistancia_"+indiceFilaFormulario+
			"' size='7' value='"+campo.distancia+"' size='8' onchange='calculaDistancia(this)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1); 
			myNewCell.innerHTML="<input type='text' id='txtCostoD_"+indiceFilaFormulario+"' class='moneda' name='txtCostoD_"+indiceFilaFormulario+
			"' size='7' value='"+campo.ctoDistancia+"' size='8' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtCostoEs_"+indiceFilaFormulario+"' class='moneda' name='txtCostoEs_"+indiceFilaFormulario+
			"' size='7' value='"+campo.ctoEspecial+"' size='8' class='moneda' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtViaticos_"+indiceFilaFormulario+"' class='moneda' name='txtViaticos_"+indiceFilaFormulario+
			"' size='7' value='"+campo.ctoViaticos+"' maxlength='120' size='8' class='moneda' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtGuiaAerea_"+indiceFilaFormulario+"' name='txtGuiaAerea_"+indiceFilaFormulario+
			"' size='7' value='"+campo.guiaA+"' maxlength='120' size='8' readonly='readonly'/></td>";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input name='txtExtra1_"+indiceFilaFormulario+"' type='text' id='txtExtra1_"+indiceFilaFormulario+
			"' size='7' value='0' size='10' class='moneda' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtExtra2_"+indiceFilaFormulario+"' name='txtExtra2_"+indiceFilaFormulario+
			"' size='7' value='0' size='10' class='moneda' onchange='calculaCosto(this)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtObservaciones_"+indiceFilaFormulario+"' name='txtObservaciones_"+indiceFilaFormulario+
			"' value='' size='10' class='moneda'/>";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='text' id='txtTotal_"+indiceFilaFormulario+"' class='moneda' name='txtTotal_"+indiceFilaFormulario+
			"' value='0' size='7' class='moneda' onchange='calculaTotalesPre(this,0)' onfocus='guardaAnterior(this)' />";
			
			myNewCell=myNewRow.insertCell(-1);
			myNewCell.innerHTML="<input type='hidden' id='hdnCargoM_"+indiceFilaFormulario+"' name='hdnCargoM_"+indiceFilaFormulario+
			"' value='"+ campo.cargoMinimo +"' /><input type='button'  value='Eliminar' onclick='removePerson(this)'>";
			
			//Calculo del total Parcial
			var costoEntrega=parseFloat($("txtCostoE_" + indiceFilaFormulario).value); 
			var costoSobrepeso=parseFloat($("txtCostoS_" + indiceFilaFormulario).value);
			var costoDistancia=parseFloat($("txtCostoD_" + indiceFilaFormulario).value);
			var costoEspecial=parseFloat($("txtCostoEs_" + indiceFilaFormulario).value);
			var costoViaticos=parseFloat($("txtViaticos_" + indiceFilaFormulario).value);
			var costoExtras=parseFloat($("txtExtra1_" + indiceFilaFormulario).value + $("txtExtra2_" + indiceFilaFormulario).value);
				//Total
			var costoTotal =parseFloat(costoEntrega+costoSobrepeso+costoDistancia+costoEspecial+costoViaticos+costoExtras);
	
			$("txtTotal_" + indiceFilaFormulario).value=redondeo2decimales(costoTotal);
			calculaTotales();
			
			indiceFilaFormulario++;
		}	
		//Vamos a checar controles
		if(!$('txtGuia').disabled) //Inserci�n
		{
			$('btnGuardar').disabled=false;
			$('btnImprimir').disabled=false;
		}
		//Totales
		$('txtImporte').disabled=false;
		$('txtIva').disabled=false;
		$('txtRetencion').disabled=false;
		$('txtTotal').disabled=false;
	}		

	$('txtGuia').value='';
}

function tomar_valores()
{
	var totalguias=$$('[id^=txtGuia_]'); 
	var valores=""; 	
	var a=1;
	for(var i=0;i<totalguias.length; i++)
	{      
		
		var numero= totalguias[i].id.substring(8,totalguias[i].id.length);
		
		valores+="&txtGuia_"+a+"="+$('txtGuia_'+numero).value	+"&txtTipoE_"+a+"="+$('txtTipoE_'+numero).value	+"&txtPiezas_"+a+"="+$('txtPiezas_'+numero).value
		+"&txtKilos_"+a+"="+$('txtKilos_'+numero).value	+"&txtTarifa_"+a+"="+$('txtTarifa_'+numero).value	+"&txtCostoE_"+a+"="+$('txtCostoE_'+numero).value
		+"&txtSobrepeso_"+a+"="+$('txtSobrepeso_'+numero).value	+"&txtCostoS_"+a+"="+$('txtCostoS_'+numero).value	+"&txtDistancia_"+a+"="+$('txtDistancia_'+numero).value
		+"&txtCostoD_"+a+"="+$('txtCostoD_'+numero).value +"&txtCostoEs_"+a+"="+$('txtCostoEs_'+numero).value +"&txtViaticos_"+a+"="+$('txtViaticos_'+numero).value	
		+"&txtGuiaAerea_"+a+"="+$('txtGuiaAerea_'+numero).value	+"&txtExtra1_"+a+"="+$('txtExtra1_'+numero).value+	"&txtExtra2_"+a+"="+$('txtExtra2_'+numero).value
		+"&txtObservaciones_"+a+"="+$('txtObservaciones_'+numero).value	+"&txtTotal_"+a+"="+$('txtTotal_'+numero).value+"&hdnCargoM_"+a+"="+$('hdnCargoM_'+numero).value;
		
		a++;
	}
	
	valores+="&anyoFactura="+$('sltAnyo').value+"&txtImporteF="+$('txtImporte').value+"&txtIvaF="+$('txtIva').value	+"&txtRetencionF="+$('txtRetencion').value+
			 "&txtTotalF="+$('txtTotal').value+"&txtFolioFactura="+$('txtFolioFactura').value+"&txtCodigo="+$("txtCodigo").value+"&totalGuias="+totalguias.length+	
			 "&txtfechaFactura="+$("txtfechaFactura").value+"&txtPorIva="+$('txtPorIva').value+"&txtPorRetencion="+$("txtPorRetencion").value;
	//Datos de usuario
	var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
	valores=valores +usuario;
	
	return valores;
}

function validaValores()
{
	var totalguias=$$('[id^=txtGuia_]'); 
	
	var valores=""; 	
	var totalGuias=0;
	
	for(var i=0;i<totalguias.length; i++)
	{      
		//Tomar renglon
		var numero= totalguias[i].id.substring(8,totalguias[i].id.length);
		var renglon=document.getElementById(numero);
		elementos=$$("#"+i+" input[type=text]");
			
		//Evaluar los datos		
		if(isNaN($('txtKilos_'+numero).value) || isNaN($('txtTarifa_'+numero).value) || isNaN($('txtCostoE_'+numero).value) || isNaN($('txtSobrepeso_'+numero).value) || isNaN($('txtCostoS_'+numero).value) || isNaN($('txtDistancia_'+numero).value) || isNaN($('txtCostoD_'+numero).value) || isNaN($('txtCostoEs_'+numero).value) || isNaN($('txtViaticos_'+numero).value) || isNaN($('txtExtra1_'+numero).value) || isNaN($('txtExtra2_'+numero).value) || isNaN($('txtTotal_'+numero).value))
		{
			for(j=3;j<elementos.length;j++)
			{	 
				if(!(j==(elementos.length-2)))
					elementos[j].style.backgroundColor='#9FCFFF';
			}
			alert('Los datos de la gu\u00EDa '+$('txtGuia_'+numero).value+' no son correctos, deben ser num\u00E9ricos.');
			return false;
		}
		else
		{
			for(h=3;h<elementos.length;h++)
			{	 
				if(!(h==(elementos.length-2)))
					elementos[h].style.backgroundColor='#FFFFFF';
			}
		}
		totalGuias+=parseFloat($('txtTotal_'+numero).value);
	}
	
	//Checar los datos generales	
		//Modificamos la clase a todos
	$("txtFolioFactura").removeClassName("invalid"); 
	$("txtfechaFactura").removeClassName("invalid"); 
	$("txtPorIva").removeClassName("invalid"); 
	$("txtPorRetencion").removeClassName("invalid"); 
	$("txtImporte").removeClassName("invalid"); 
	$("txtIva").removeClassName("invalid"); 
	$("txtRetencion").removeClassName("invalid"); 
	$("txtTotal").removeClassName("invalid"); 


	if($("txtFolioFactura").value=="") 
    { 
		$("txtFolioFactura").addClassName("invalid"); 
		alert("Ingrese el Folio de la Factura."); 
		return false;
    }		
	
	var expresion = /^\s*(\d{2,2})\/(\d{2,2})\/(\d{4,4})\s*$/;
	
	if(($("txtfechaFactura").value!='') && (!($("txtfechaFactura").value.match(expresion))))
	{
		$("txtfechaFactura").addClassName("invalid"); 
		alert("El formato de la fecha es incorrecto (dd/mm/yyyy)."); 
		return false;
	}		
	
	if($("txtPorIva").value!="" && isNaN($("txtPorIva").value)) 
    { 
		$("txtPorIva").addClassName("invalid"); 
		alert("El porcentaje de iva debe ser un n\u00FAmero."); 
		return false;
    }	
	
	if($("txtPorRetencion").value!="" && isNaN($("txtPorRetencion").value)) 
    { 
		$("txtPorRetencion").addClassName("invalid"); 
		alert("El porcentaje de retenci\u00F3n debe ser un n\u00FAmero."); 
		return false;
    }		
		
	if($("txtImporte").value!="")
    { 
		if(isNaN($("txtImporte").value)) 
		{
	   		$("txtImporte").addClassName("invalid"); 
			alert("El importe debe ser un n\u00FAmero."); 
			return false;
		}	
    }
	else
	{
		alert("Debe ingresar el importe."); 
		return false;
	}
	
	 
	if($("txtIva").value!="" && isNaN($("txtIva").value)) 
    { 
		if(isNaN($("txtIva").value)) 
		{
	   		$("txtIva").addClassName("invalid"); 
			alert("El iva debe ser un n\u00FAmero."); 
			return false;
		}	
    }
	
	if($("txtRetencion").value!="") 
    { 
		if(isNaN($("txtRetencion").value)) 
		{
	   		$("txtRetencion").addClassName("invalid"); 
			alert("La retenci\u00F3n debe ser un n\u00FAmero."); 
			return false;
		}	
    }

	
	if($("txtTotal").value!="")
    { 
		if(isNaN($("txtTotal").value)) 
		{
	   		$("txtTotal").addClassName("invalid"); 
			alert("El importe total debe ser un n\u00FAmero."); 
			return false;
		}	
    }
	else
	{
		alert("Debe ingresar el importe total."); 
		$("txtTotal").addClassName("invalid"); 
		return false;
	}
	
	if($("txtImporte").value!=totalGuias)
	{
		if(!confirm("El valor del Importe y de la Suma de Costos son distintos \u00BFDesea continuar?"))
			return false;
	}
	return true;
}

function guardaPrevia()
{
	if(validaValores())
	{
		var valores=tomar_valores();
		$Ajax("scripts/guardarRelacion.php", {metodo: $metodo.POST, onfinish: imprimirEnvio, parametros: valores, avisoCargando:"loading"});
	}
}

function verFactura()
{
	var url="scripts/reporteRelacion.php?opc=1&corresponsal="+$('txtCodigo').value+"&factura="+$('txtFolioFactura').value;    
	var win = new Window({className: "mac_os_x",title:"Relaci�n de gu�as.",top:70,left:160,width:950,height:500,url:url,showEffectOptions:{duration:1.5}}); 
	win.show(); 
}

function imprimirEnvio(res)
{                 
	
	respuesta=res.split("-");
	alert(respuesta[0]);	 
	if(respuesta[1]==1) //No hubo error
	{
		var url="scripts/reporteRelacion.php?opc=0&corresponsal="+$('txtCodigo').value+"&factura="+$('txtFolioFactura').value;    
		var win = new Window({className: "mac_os_x",title:"Relaci�n de gu�as.",top:70,left:160,width:950,height:500,url:url,showEffectOptions:{duration:1.5}}); 
		win.show(); 
	 }
}  

function guardar()
{	
	if(validaValores())
	{
		var valores=tomar_valores();
		$Ajax("scripts/guardarFacturasC.php",{metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
	}
}

function modificar()
{	
	if(validaValores())
	{
		var factura=$('txtFolioFactura').value;	
		
		//Primero verificaremos que el monto total de la factura no haya sido pagado
		var url = "scripts/datosfacturasC.php?op=3&corresponsal="+$("txtCodigo").value+"&factura="+factura+"&anyo="+$("sltAnyo").value;
		$Ajax(url,{onfinish: function (datos)
						     {
								if(datos[0].saldo<=0)
 									alert("No puede continuar, el saldo del pago de la factura es cero.");
								else
								{
									 var valores="&anyoFactura="+$('sltAnyo').value+"&txtImporteF="+$('txtImporte').value+"&txtIvaF="+$('txtIva').value
												 +"&txtRetencionF="+$('txtRetencion').value+"&txtTotalF="+$('txtTotal').value
												 +"&txtFolioFactura="+$('txtFolioFactura').value+"&txtCodigo="+$("txtCodigo").value
												 +"&txtfechaFactura="+$("txtfechaFactura").value+"&txtPorIva="+$('txtPorIva').value
												 +"&txtPorRetencion="+$("txtPorRetencion").value+"&usuario="+$("hdnUsuario").value
												 +"&empresa="+$("hdnEmpresaS").value+"&facAntigua="+$("txthFolioFactura").value
												 +"&anyoAntiguo="+$("txthAnyo").value;  				  
									var url="scripts/actualizarFacturaC.php?opcion=1&factura="+factura;
									$Ajax(url,{metodo: $metodo.POST, onfinish: msjAct, parametros: valores, avisoCargando:"loading"});
								}
								
							 }, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
		
		
	}
}

function fin2(res)
{
	alert(res);
}

function removePerson(obj)
{ 
	var oTr = obj;
	while(oTr.nodeName.toLowerCase()!='tr'){
		oTr=oTr.parentNode;
	}
	var root = oTr.parentNode;
	root.removeChild(oTr);
	
	//Checar si ya no hay datos en la tabla
	tabla=document.getElementById("guiasCorresponsal");
	if(tabla.rows.length==1)
	{
		$('btnGuardar').disabled=true;
		$('btnImprimir').disabled=true;
		//Limpiar los datos
		$('txtImporte').value='';
		$('txtIva').value='';
		$('txtRetencion').value='';
		$('txtTotal').value='';			
	}
	else
		calculaTotales();
}

function calculaCosto(obj){
	var numero= obj.id.substring(obj.id.lastIndexOf("_"),obj.id.length);

	if(isNaN(obj.value))
	{
		alert("Dato incorrecto, debe ingresar un n\u00FAmero."); 
		obj.value=valAnterior;
	}
 	else
	{
		//Calculo del total Parcial
		var costoEntrega   = parseFloat($("txtCostoE"+numero).value); 
		var costoSobrepeso = parseFloat($("txtCostoS"+numero).value);
		var costoDistancia = parseFloat($("txtCostoD"+numero).value);
		var costoEspecial  = parseFloat($("txtCostoEs"+numero).value);
		var costoViaticos  = parseFloat($("txtViaticos"+numero).value);
		var costoExtras    = parseFloat($("txtExtra1"+numero).value) + parseFloat($("txtExtra2"+numero).value);
		var costoTotal     = parseFloat(costoEntrega+costoSobrepeso+costoDistancia+costoEspecial+costoViaticos+costoExtras);
	
		$("txtTotal" + numero).value=redondeo2decimales(costoTotal);
		calculaTotales();
	}
}
 
function calculaTarifa(obj)
{
	var numero= obj.id.substring(obj.id.lastIndexOf("_"),obj.id.length);
	if(isNaN(obj.value))
	{
		alert("Dato incorrecto, debe ingresar un n\u00FAmero."); 
		obj.value=valAnterior;
	}
 	else
	{
		var peso         = parseInt($("txtKilos"+numero).value);
		var tarifa       = parseFloat($("txtTarifa"+numero).value);
		var cargoMinimo  = parseFloat($("hdnCargoM"+numero).value);
		var costoEntrega = peso * tarifa;
		if(costoEntrega>cargoMinimo)
			costoEntrega=costoEntrega; 
		else
			costoEntrega=cargoMinimo; 
		
		$("txtCostoE"+numero).value=redondeo2decimales(costoEntrega);
		calculaCosto(obj);
	}
}
 
function calculaSobrepeso(obj){
	var numero= obj.id.substring(obj.id.lastIndexOf("_"),obj.id.length);
	if(isNaN(obj.value))
	{
		alert("Dato incorrecto, debe ingresar un n\u00FAmero."); 
		obj.value=valAnterior;
	}
 	else
	{	
		var peso   = parseFloat($("txtSobrepeso"+numero).value);
		var tarifa = parseFloat($("txtTarifa"+numero).value);
		var costoSobrepeso = peso * tarifa;          
	
		$("txtCostoS" + numero).value=redondeo2decimales(costoSobrepeso);            
		calculaCosto(obj);
	}
}
 
function calculaDistancia(obj){
	var numero= obj.id.substring(obj.id.lastIndexOf("_"),obj.id.length);
	if(isNaN(obj.value))
	{
		alert("Dato incorrecto, debe ingresar un n\u00FAmero."); 
		obj.value=valAnterior;
	}
 	else
	{
		var distancia = parseFloat($("txtDistancia"+numero).value);
		var tarifa    = parseFloat($("txtCostoD"+numero).value);
		var costoDistancia=distancia * tarifa;          
		
		$("txtCostoD" + numero).value=redondeo2decimales(costoDistancia);            
		calculaCosto(obj);
	}
}
 
function calculaTotalesPre(obj,opcion)
{
	if(isNaN(obj.value))
	{
		alert("Dato incorrecto, debe ingresar un n\u00FAmero."); 
		obj.value=valAnterior;
	}
 	else
	{
		tabla=document.getElementById("guiasCorresponsal");
		if(tabla.rows.length>1){
			if(obj.id=='txtPorIva' && obj.value==100)
			{
				alert("No puede ingresar el 100% de IVA."); 
				obj.value=valAnterior;
			}
			else if(obj.id=='txtPorRetencion' && obj.value==100)
			{
				alert("No puede ingresar el 100% de Retenci\u00F3n."); 
				obj.value=valAnterior;
			}
			else		
			{	
				if(opcion==0)
					calculaTotales();
				else
					calculaTotalesForzado();
			}
		}
		
	}
}

function calculaTotales()
{		
	var totalImporte=0;
	var totalIva=0;
	var totalRetencion=0;
	var totalTotal=0;
	var inGuias=$$('[id^=txtTotal_]'); 
	var contador=inGuias.length;
	
	for (var i=0; i<contador; i++)
	{
		totalImporte=totalImporte+parseFloat(inGuias[i].value);
	}
	
	var porcentajeIva=($("txtPorIva").value=='')?0:parseFloat($("txtPorIva").value)/100;
	var porcentajeRetencion=($("txtPorRetencion").value=='')?0:parseFloat($("txtPorRetencion").value)/100;
	
	porcentajeIva=porcentajeIva.toFixed(3);
	porcentajeRetencion=porcentajeRetencion.toFixed(3);	
	
	$("txtImporte").value=totalImporte.toFixed(2);
	
	totalIva=porcentajeIva*totalImporte;
	$("txtIva").value=totalIva.toFixed(2);
	
	totalRet=porcentajeRetencion*totalImporte;
	$("txtRetencion").value=totalRet.toFixed(2);
	
	totalTotal=totalImporte+totalIva-totalRet;
	$("txtTotal").value=totalTotal.toFixed(2);
}

function calculaTotalesForzado()
{
	var totalImporte=$("txtImporte").value;
	var porcentajeIva=($("txtPorIva").value=='')?0:parseFloat($("txtPorIva").value)/100;
	var porcentajeRetencion=($("txtPorRetencion").value=='')?0:parseFloat($("txtPorRetencion").value)/100;
	
	porcentajeIva=porcentajeIva.toFixed(3);
	porcentajeRetencion=porcentajeRetencion.toFixed(3);	

	totalIva=porcentajeIva*totalImporte;
	$("txtIva").value=totalIva.toFixed(2);
	
	totalRet=porcentajeRetencion*totalImporte;
	$("txtRetencion").value=totalRet.toFixed(2);
	
	totalTotal=parseFloat(totalImporte)+totalIva-totalRet;
	$("txtTotal").value=totalTotal.toFixed(2);
}

function fin(res)
{
	respuesta=res.split("-");

	if(respuesta[1]==1) //No hubo error
	{
		if(confirm(respuesta[0]+" \u00BFDesea Imprimirla?")){
			var url="scripts/reporteRelacion.php?opc=1&corresponsal="+$('txtCodigo').value+"&factura="+$('txtFolioFactura').value;    
			var win = new Window({className: "mac_os_x",title:"Relaci�n de gu�as.",top:70,left:160,width:950,height:500,url:url,showEffectOptions:{duration:1.5}}); 
			win.show(); 
		}
	}
	else
		alert(respuesta[0]);	
	
	//Limpiamos el formulario
	cancelar();
}
		
function validaGuia()
{	
	var contador=$$('[id^=txtGuia_]'); 	
	var guia=$('txtGuia').value;
	contador=parseInt(contador)+1;
	var coincide=0;
	
	for (var i=1; i<contador.length; i++){
	
		var guiaGuardada=$('txtGuia_'+i).value;
		if(guia==guiaGuardada)
			coincide=1;
	}
	
	if(coincide>0)
		alert("La guia no se agrego porque ya exixte en la factura");
	else
		camposb();
}
