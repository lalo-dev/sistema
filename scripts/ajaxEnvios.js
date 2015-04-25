window.onload = inicia;   
 
function inicia() 
{  
    $("txtPorcentajeSeguro").onchange=calculaSeguro; 
    $("txtPrecioA").onchange=calculamasFlete;     
    $Ajax("scripts/creaFactura.php?operacion=1", {onfinish: creaFolio, tipoRespuesta: $tipo.JSON});  
    //Inicializando autocomplete  
    $("autoCliente").className = "autocomplete";  
    new Ajax.Autocompleter("txtRazonS", "autoCliente", "scripts/catalogoClientes.php?operacion=4&opt=0", {paramName: "caracteres",afterUpdateElement:datosClientes});
     
    $("txtValorD").onchange=calculaRetencion;  
    $("txtRazonS").disabled=false;  
    $("btnGuardar").disabled=true;  
    $("btnImprimir").disabled=true;
	$("btnFactura").disabled=true;
    $("btnModificar").disabled=true; 
    $("btnLiberar").disabled=true;  
    $("btnCancelar").disabled=true;      
    $("btnCancelar").onclick=function(){  
        location = "envios.php";      
    };  
     
    //Bloquear todos los input después del de Razon Social 
    bloquear(); 
    titulos(); 
	$("btnFactura").onclick=imprimirFac;
	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("btnGuardar").style.visibility="hidden";
		$("btnModificar").style.visibility="hidden";
		$("btnLiberar").style.visibility="hidden";
	
		$("btnGuardar").style.display="none";
		$("btnModificar").style.display="none";
		$("btnLiberar").style.display="none";				
	}



}  
 
function bloquear() 
{ 
    fieldset=$$('fieldset:not([id=cliente])'); 
    for(i=0;i<9;i++) 
    { 
        fieldset[i].id="tmp"; 
        inputs=$$('#tmp input[type=text],#tmp input[type=radio]'); 
        for(j=0;j<inputs.length;j++){ 
            inputs[j].disabled=true; 
        } 
        fieldset[i].id=""; 
 
    } 
} 

function desbloquear() 
{ 
    fieldset=$$('fieldset:not([id=cliente])'); 
    for(i=0;i<9;i++) 
    { 
        fieldset[i].id="tmp"; 
        inputs=$$('#tmp input[type=text],#tmp input[type=radio]'); 
        for(j=0;j<inputs.length;j++){ 
            inputs[j].disabled=false; 
        } 
        fieldset[i].id=""; 
    } 
} 
 
function redondeo2decimales(numero)  
{  
	result=parseFloat(numero).toFixed(2);
	return result;   
}  
 
function formatoMoneda(num)  
{  
	num=redondeo2decimales(num);
	
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
 
function creaFolio(folios)
{  
    //Tomamos el primer objeto del json, ya que siempre devolvera un unico registro  
    var folio = folios[0];  
    //Asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos       
    $("txtFolioFactura").value = folio.folio;            
}  
 
function datosClientes() 
{      
	var url2 = $("txtRazonS").value;     
    $Ajax("scripts/datosClientes.php?operacion=3&cveCliente="+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});  
}  
 
function cveCliente(campos) 
{     
    	var campo = campos[0];  
          
        $("txtRazonS").value=campo.razonSocial; 
        $("hdncveCliente").value=campo.cveCliente;    
        $("thIva").innerHTML=$("thIva").innerHTML+"("+campo.impuesto+"%)"; 
        $("txthValorD").value=campo.impuesto; 
         
 
        //Total de Guías no Pagadas
        $Ajax("scripts/catalogoTotales.php?operacion=5&valor1="+campo.cveCliente, {onfinish: cargarTotales2, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"}); 
                 
        $("autoGuia").className = "autocomplete";  
        $("autoFolio").className = "autocomplete";  
        $("autoVale").className = "autocomplete";  

        //Búsquedas 
	    $("autoFactura").className = "autocomplete";  
	    new Ajax.Autocompleter("txtFolioFactura", "autoFactura", "scripts/catalogoFacturas.php?operacion=4&cliente="+$("hdncveCliente").value, {paramName: "caracteres",afterUpdateElement:camposd});  

        var urlG="scripts/catalogoGuias.php?operacion=4&cliente="+campo.cveCliente;                                              //Guía 
        new Ajax.Autocompleter("txtGuia", "autoGuia",urlG , {paramName: "caracteres", afterUpdateElement:camposb});  
        var urlV="scripts/catalogoVales.php?cliente="+campo.cveCliente;                      		                         
        var urlB="scripts/catalogoAcuses.php?operacion=3&cliente="+campo.cveCliente;                                           //Folio 
        new Ajax.Autocompleter("txtFolio", "autoFolio",urlB, {paramName: "caracteres",afterUpdateElement:camposc});  

         
        var urlV="scripts/catalogoVales.php?cliente="+campo.cveCliente;                      		                           //Vale 
        new Ajax.Autocompleter("txtPlanificacion", "autoVale", urlV, {paramName: "caracteres",afterUpdateElement:camposa});  

        desbloquear(); 
        $("btnCancelar").disabled=false; 
		$("txtRazonS").disabled=true; 
}  
 
function camposc()  
{   
    var folios =$('hdnFolios').value;  
    var nuevofolio=$("txtFolio").value;  

    $Ajax("scripts/datosEnvios.php?operacion=1&folio="+$("txtFolio").value+"&cliente="+$("hdncveCliente").value, {onfinish: llenaDatosP, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"}); 
         
    $("hdnFolios").value=$("hdnFolios").value+","+$("txtFolio").value; 
	
	//Limpiar txt
	$("txtFolio").value="";
}  
 
function titulos() 
{ 
    checks=$$('input[type="checkbox"]'); 
    for(i=0;i<checks.length;i++) 
    { 
        checks[i].title="Quitar Concepto"; 
        checks[i].style.cursor="pointer"; 
    } 
} 
 
function camposa()  
{   
    var vales =$('hdnVale').value;  
    var nuevoVale=$("txtPlanificacion").value;  
     
    $Ajax("scripts/datosEnvios.php?operacion=2&folio="+$("txtPlanificacion").value, {onfinish: llenaDatosP, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});          
    $("hdnVale").value=$("hdnVale").value+","+$("txtPlanificacion").value;  
	
	//Limpiar txt
	$("txtPlanificacion").value="";
}  
 
function camposb()  
{  
    $Ajax("scripts/datosEnvios.php?operacion=3&cveguia="+$("txtGuia").value, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});  
	//Limpiar txt
	$("txtGuia").value="";
	
}  
 
function camposd()  
{  
    var url = "scripts/existe.php?keys=22&f1value=" + $("txtFolioFactura").value;
	$Ajax(url, {tipoRespuesta: $tipo.JSON ,onfinish:  function(datos)
													  {
													  	existe=datos[0].existe;
														if(existe>0)
														{

																$Ajax("scripts/datosEnvios.php?operacion=4&folio="+$("txtFolioFactura").value, {onfinish: llenaDatos2, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});  
																//Verificaremos que factura es la que se eligió
																var factura=$("txtFolioFactura").value;
																chkFactura(factura);
																mensaje=false;

														}
														else
														{
																$("btnGuardar").onclick=guardar;
																$("btnGuardar").disabled=false;
																$("btnModificar").disabled=true;
																$("txtFolioFactura").disabled=false;
														}
													  }		      	
			    });
}  

function chkFactura()
{
    var url = "scripts/catalogoFacturas.php?operacion=7&f1value=" + $("txtFolioFactura").value;
	$Ajax(url, {tipoRespuesta: $tipo.JSON ,onfinish:  function(datos)
													  {
															dato=datos[0];
															referencia=dato.referencia;
															seguro=dato.seguro;
															$("txthSeguro").value=seguro;
															$("btnModificar").onclick=modificar;
															//Si es una factura de seguro, solo podrá CONSULTAR, no puede ni IMPRIMIR ni MODIFICAR
															if(seguro==1)
															{
																$$("input[type=button]").each(function(Element){Element.disabled=true;});
																$("btnCancelar").disabled=false;
																$("btnModificar").disabled=false;
															}
															else //Sea principal o de corte, podrá imprimir el reporte 
															{
																$("btnModificar").disabled=false;
																$("btnImprimir").disabled=false;
																$("btnLiberar").disabled=false;
																 //Permitimos que se puedan liberar las guías
																$("btnLiberar").onclick=liberar;
																if(referencia==0) //Solo si es principal podrá imprimir la factura completa y liberar guías
																{
																	$("btnFactura").disabled=false;
																}
															}
													  }
				});
	$("btnGuardar").disabled=true;
	$("txtFolioFactura").disabled=true;
}

function liberar()
{

	var valores="&txtFolios=hi";     
	var url="scripts/liberarGuias.php?operacion=1&folio="+$("txtFolioFactura").value;
	$Ajax(url,{metodo: $metodo.POST, parametros: valores, avisoCargando:"loading",onfinish: msjAct}); 
}

var indiceFilaFormulario2=1;
function llenaDatos2(campos)
{	
	//Limpiar primero la Tabla
	tabla=document.getElementById("tablaFormulario");
	for(var j=tabla.rows.length;j>1; j--)
	{
		tabla.deleteRow(1);	
	}
	
	var guias=new Array(); 
    var inGuias=$$('[id^=txtGuia_]'); 
 
    for (var i=0; i<inGuias.length; i++){      
        nombre=inGuias[i].id; 
        numero=nombre.substr(8,nombre.length); 
        var guiaGuardada=$('txtGuia_'+numero).value;  
        guias[i]=guiaGuardada; 
    }  

	//Checar si se trata de una Factura de seguro
	facturaSeguro=$("txthSeguro").value;
    var peso;  
    var totalImporte=0;  
    var totalIva=0;  
    var totalSubtotal=0;  
    var totalRetencion=0;  
    var totalTotal=0;  
    var totalRegistros=campos.length-1;
    for (var i=0; i<totalRegistros; i++)
	{ 
        var campo = campos[i]; 
        var guiaActual=guias.indexOf(campo.cveGuia); 
        //Si el índice es distinto a -1 significa la guía ya existe 
        if(guias.length==0) guiaActual=-1; 
         
        if(guiaActual==-1){              
            var valorf=campo.valorDeclarado;                 
            if(valorf <= 0) {valorf=0;} 
            var retencionf=parseFloat(valorf*campo.importe/100);  
            var totalf=campo.subtotal-retencionf; 
            //Si se sobrepasa el valor permitido , no se aceptará la guía 
            var tope=$("txtTope").value; 
            if(totalf>tope) 
            { 
                alert("La gu\u00EDa '"+ campo.cveGuia+"' sobrepasa el tope de la factura."); 
            } 
            else 
            { 
                $("datos").className="";  
 
                myNewRow = document.getElementById("tablaFormulario").insertRow(-1);   
                myNewRow.id=indiceFilaFormulario2; 
                 
                myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' name='txtFecha_"+indiceFilaFormulario2+"' type='text' id='txtFecha_"+indiceFilaFormulario2+"' value='"+ campo.recepcionCYE +"' size='10' readonly='true'/>";  
                myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtGuia_"+indiceFilaFormulario2+"' name='txtGuia_"+indiceFilaFormulario2+"' value='"+ campo.cveGuia +"' size='10' readonly='true' />";  
                myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtFactura_"+indiceFilaFormulario2+"' name='txtFactura_"+indiceFilaFormulario2+"' value='"+ campo.cvefacturas +"' readonly='true'>";  
                 
                myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtFolio_"+indiceFilaFormulario2+"' name='txtFolio_"+indiceFilaFormulario2+"' maxlength='120' value='"+ campo.planificacionFolio +"'  readonly='true' />";  
                myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' name='txtDestino_"+indiceFilaFormulario2+"' type='text' id='txtDestino_"+indiceFilaFormulario2+"' value='"+ campo.sucursalDestino +"' size='8' readonly='true' />";  
                myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtDestinatario_"+indiceFilaFormulario2+"' name='txtDestinatario_"+indiceFilaFormulario2+"' value='"+ campo.nombreDestinatario +"' readonly='true'/>";     
				myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtObservacion_"+indiceFilaFormulario2+"' name='txtObservacion_"+indiceFilaFormulario2+"' value='"+ campo.tipoEnvio +"' size='10' readonly='true'/>";  
                 
                var valorD=campo.valorDeclarado;                 
                if(valorD <= 0) {valorD=0;} 
                 
                myNewCell=myNewRow.insertCell(-1); 
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtValorD_"+indiceFilaFormulario2+"' name='txtValorD_"+indiceFilaFormulario2+"' maxlength='120' value='"+ valorD +"'  class='moneda' readonly='true'/>"; 
                
				 
				myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtpiezas_"+indiceFilaFormulario2+"' name='txtpiezas_"+indiceFilaFormulario2+"' value='"+ campo.piezas +"' size='8' readonly='true'/>";  
				
			   //Los input de aquí si se podrán modificar
				myNewCell=myNewRow.insertCell(-1);  

                myNewCell.innerHTML="<input readonly='readonly' name='txtPeso_"+indiceFilaFormulario2+"' type='text' id='txtPeso_"+indiceFilaFormulario2+"' value='"+ campo.peso +"' size='8' onchange='calculaFlete(this)' onkeypress='return chk_num(this.id,event)'/>";  
                 
				
				myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtTarifa_"+indiceFilaFormulario2+"' name='txtTarifa_"+indiceFilaFormulario2+"' value='"+ campo.cargo +"' size='8' onchange='calculaFlete(this)' onkeypress='return chk_num(this.id,event)'/>";    
				
              
                myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtFleteF_"+indiceFilaFormulario2+"' name='txtFleteF_"+indiceFilaFormulario2+"' value='"+ campo.flete +"' size='8' class='moneda' onchange='calculaDemas(this)' onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtFlete_"+indiceFilaFormulario2+"' name='txtFlete_"+indiceFilaFormulario2+"' value='"+ campo.flete +"'>"; 
				
                myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtSeguroF_"+indiceFilaFormulario2+"' name='txtSeguroF_"+indiceFilaFormulario2+"' maxlength='120' size='8' class='moneda' onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtSeguro_"+indiceFilaFormulario2+"' name='txtSeguro_"+indiceFilaFormulario2+"' value='0' />";    
                 
                myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtAcuseF_"+indiceFilaFormulario2+"' name='txtAcuseF_"+indiceFilaFormulario2+"' value='"+ campo.acuse +"' maxlength='120' size='8' class='moneda' onkeypress='return chk_num(this.id,event)' onchange='calculaDemas2(this,this.value)'/><input type='hidden' id='txtAcuse_"+indiceFilaFormulario2+"' name='txtAcuse_"+indiceFilaFormulario2+"' value='"+ campo.acuse +"' />";    
				
                myNewCell=myNewRow.insertCell(-1);  
                var importe=formatoMoneda(campo.importe);  
                myNewCell.innerHTML="<input readonly='readonly' name='txtImporteF_"+indiceFilaFormulario2+"' type='text' id='txtImporteF_"+indiceFilaFormulario2+"' value='"+ importe +"' size='10' class='moneda' onkeypress='return chk_num(this.id,event)'/><input name='txtImporte_"+indiceFilaFormulario2+"' type='hidden' id='txtImporte_"+indiceFilaFormulario2+"' value='"+ campo.importe +"'/>";  
				
                myNewCell=myNewRow.insertCell(-1);  
                var iva=formatoMoneda(campo.iva);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtIvaF_"+indiceFilaFormulario2+"' name='txtIvaF_"+indiceFilaFormulario2+"' value='"+ iva +"' size='10' class='moneda'onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtIva_"+indiceFilaFormulario2+"' name='txtIva_"+indiceFilaFormulario2+"' value='"+ campo.iva +"'/>";  
                 
                myNewCell=myNewRow.insertCell(-1);  
                var subtotal=formatoMoneda(campo.subtotal);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtSubtotalF_"+indiceFilaFormulario2+"' name='txtSubtotalF_"+indiceFilaFormulario2+"' value='"+ subtotal +"' size='10' class='moneda' onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtSubtotal_"+indiceFilaFormulario2+"' name='txtSubtotal_"+indiceFilaFormulario2+"' value='"+ campo.subtotal +"'/>";  
                 
                if(campo.retencionIva){
					retencionb=formatoMoneda(campo.retencionIva);
				}
				
				myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtRetencionIvaF_"+indiceFilaFormulario2+"' name='txtRetencionIvaF_"+indiceFilaFormulario2+"' value='"+ retencionb +"' maxlength='120' size='15' class='moneda' onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtRetencionIva_"+indiceFilaFormulario2+"' name='txtRetencionIva_"+indiceFilaFormulario2+"' value='"+ campo.retencionIva +"' />";  
                
				myNewCell=myNewRow.insertCell(-1);                       
                var totalPr=formatoMoneda(campo.total); 
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtTotalF_"+indiceFilaFormulario2+"' name='txtTotalF_"+indiceFilaFormulario2+"' maxlength='120' value='"+ totalPr +"' size='10' class='moneda' onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtTotal_"+indiceFilaFormulario2+"' name='txtTotal_"+indiceFilaFormulario2+"' value='"+ campo.total +"' />";  
                 
                myNewCell=myNewRow.insertCell(-1);  
                myNewCell.innerHTML="<input readonly='readonly' type='text' id='txtObservacionB_"+indiceFilaFormulario2+"' name='txtObservacionB_"+indiceFilaFormulario2+"' value='"+campo.observacionB+"' maxlength='120' />";  
                myNewCell=myNewRow.insertCell(-1); 
				if(facturaSeguro==1)
				{
					myNewCell.innerHTML="<input readonly='readonly' type='hidden' id='hdnCargoM_"+indiceFilaFormulario2+"' name='hdnCargoM_"+indiceFilaFormulario2+"' value='"+ campo.cargoMinimo +"' /><input type='hidden' id='cveIva_"+indiceFilaFormulario2+"' name='cveIva_"+indiceFilaFormulario2+"' value='"+ campo.cveIva +"' /><input type='button'  value='Eliminar' readonly='readonly'>";  
				}
				else{
	                myNewCell.innerHTML="<input readonly='readonly' type='hidden' id='hdnCargoM_"+indiceFilaFormulario2+"' name='hdnCargoM_"+indiceFilaFormulario2+"' value='"+ campo.cargoMinimo +"' /><input type='hidden' id='cveIva_"+indiceFilaFormulario2+"' name='cveIva_"+indiceFilaFormulario2+"' value='"+ campo.cveIva +"' /><input type='button'  value='Eliminar' onclick='removeGuia(this,"+campo.cveGuia+")'>";  
				}
                 
                calculaTotales();   
                if(campo.valorDeclarado>0)  
                {      
                    var valorD=parseInt(campo.valorDeclarado);  
                    var porcentajeSeguro=parseInt($("txtPorcentajeSeguro").value)/100;   
                    var seguro=valorD * porcentajeSeguro;  
                    $("txtSeguro_" + indiceFilaFormulario2).value=redondeo2decimales(seguro);  
                    $("txtSeguroF_" + indiceFilaFormulario2).value=formatoMoneda(redondeo2decimales(seguro));   
                } 
            
                indiceFilaFormulario2++;  
                $('hdnContador').value=indiceFilaFormulario2;                
            } 
        } 
    } 

	$("btnImprimir").onclick=guardarEnvio;
	$("totalImporte").readOnly=true;
	$("totalIva").readOnly=true;
	$("totalSubtotal").readOnly=true;
	$("totalRetencion").readOnly=true;
	$("totalTotal").readOnly=true;
	
	var campo = campos[i]; 
	
	//Poner los totales de la Factura
	$("totalImporte").value=campo.importeTFactura;
	$("totalIva").value=campo.ivaTFactura;
	$("totalSubtotal").value=campo.subtotalTFactura;
	$("totalRetencion").value=campo.retencionTFactura;
	$("totalTotal").value=campo.totalTFactura;
	
	//Ahora poner como diabled todos ls demas input
	 bloquear(); 
	 //Desbloquear los radio button
     inputs=$$('#form2 input[type=radio]');
	 for(j=0;j<inputs.length;j++){ 
            inputs[j].disabled=false; 
     } 
	 //Cargamos la Retención y el Porcentaje del Seguro
	 var url="scripts/datosEnvios.php?operacion=5&folio="+$("txtFolioFactura").value+"&cliente="+$("hdncveCliente").value;
     $Ajax(url,{tipoRespuesta: $tipo.JSON, avisoCargando:"loading",onfinish: function (datos)
																			 {
																				dato=datos[0];
																				$("txtValorD").value=dato.porcentajeRetI;
																				$("txtPorcentajeSeguro").value=dato.porcentajeSeg;
																				$("txtTope").value=dato.tope;
																			 }
			   });  	 
	 //Ahora llenaremos los datos de campo de envío
	 var url="scripts/datosEnvios.php?operacion=6&folio="+$("txtFolioFactura").value+"&cliente="+$("hdncveCliente").value;
     $Ajax(url,{tipoRespuesta: $tipo.JSON, avisoCargando:"loading",onfinish: function (datos)
																			 {
																				dato=datos[0];
																				$i=0;
																				
																				check=$$("#form2 input[type=checkbox]");
																				if(dato.fecha==0) check[0].checked=false;
																				else			  check[0].checked=true;
																				
																				if(dato.noGuia==0) check[1].checked=false;
																				else			   check[1].checked=true;

																				if(dato.remicion==0) check[2].checked=false;
																				else			     check[2].checked=true;

																				if(dato.planificacion==0) check[3].checked=false;
																				else			  check[3].checked=true;
																				
																				if(dato.destino==0) check[4].checked=false;
																				else			  check[4].checked=true;

																				if(dato.destinatario==0) check[5].checked=false;
																				else			  check[5].checked=true;

																				if(dato.observacion==0) check[6].checked=false;
																				else			  check[6].checked=true;

																				if(dato.valorDeclarado==0) check[7].checked=false;
																				else			  check[7].checked=true;

																				if(dato.piezas==0) check[8].checked=false;
																				else			  check[8].checked=true;

																				if(dato.peso==0) check[9].checked=false;
																				else			  check[9].checked=true;
																				
																				if(dato.tarifa==0) check[10].checked=false;
																				else			  check[10].checked=true;

																				if(dato.flete==0) check[11].checked=false;
																				else			  check[11].checked=true;

																				if(dato.seguro==0) check[12].checked=false;
																				else			  check[12].checked=true;

																				if(dato.acuse==0) check[13].checked=false;
																				else			  check[13].checked=true;

																				if(dato.importe==0) check[14].checked=false;
																				else			  check[14].checked=true;

																				if(dato.iva==0) check[15].checked=false;
																				else			  check[15].checked=true;																				
																				
																				if(dato.subtotal==0) check[16].checked=false;
																				else			  check[16].checked=true;

																				if(dato.retencion==0) check[17].checked=false;
																				else			  check[17].checked=true;


																				if(dato.total==0) check[18].checked=false;
																				else			  check[18].checked=true;
																				
																				if(dato.observaciones==0) check[19].checked=false;
																				else			  check[19].checked=true;
																			 }
			   });  	 
}

function removeGuia(obj,guia)
{
	//Primero checar que la factura no se vaya a quedar en ceros
	norenglones=document.getElementById("tablaFormulario").rows.length-1; //Se resta uno,considerando que se eliminará el renglon seleccionado
	norenglones=9;
	if(norenglones<=1)
	{
		alert("No es posible dejar una factura en ceros.");
	}
	else{
		columna=obj.parentNode;
		renglon=columna.parentNode;
		numero=renglon.id;
		valorDeclarado=$("txtValorD_"+numero).value;
		seguro=$("txtSeguro_"+numero).value;
		//Importe,iva,subtotal,retencion,total
		importe=$("txtImporte_"+numero).value;
		iva=$("txtIva_"+numero).value;
		subtotal=$("txtSubtotal_"+numero).value;
		retencion=$("txtRetencionIva_"+numero).value;
		total=$("txtTotal_"+numero).value;		
		
		if(confirm("\u00BFEst\u00E1 seguro que desea eliminar la gu\u00EDa "+guia+" de la factura?\n(ya no podr\u00E1 ser agregada posteriormente a \u00E9sta factura).")){
			renglon.remove();
			//Rehacer TOTALES
			calculaTotales();
			//Remover la guía de la factura
			valores="&txttotalImporte="+$('totalImporte').value    +"&txttotalIva="+$('totalIva').value    +"&txttotalSubtotal="+$('totalSubtotal').value+
			"&txttotalRetencion="+$('totalRetencion').value    +"&txttotalTotal="+$('totalTotal').value+"&txtcveCliente="+$("hdncveCliente").value+
			"&valorDeclarado="+valorDeclarado+"&seguro="+seguro+"&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value+		
			"&importe="+importe+"&iva="+iva+"&subtotal="+subtotal+"&retencion="+retencion+"&total="+total+		
			"&valorDeclarado="+valorDeclarado+"&seguro="+seguro+"&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;
			factura=$("txtFolioFactura").value;
			$Ajax("scripts/actualizarFacturas.php?opcion=0&factura="+factura+"&guia="+guia, {metodo: $metodo.POST, onfinish: msjAct, parametros: valores, avisoCargando:"loading"})	
		}
	}
}

function msjAct(res)
{
	alert(res);
	//finalizar();
}

function llenaDatosP(campos){   
	pausecomp(100);
	llenaDatos(campos);
}

function pausecomp(millis) 
{
	var date = new Date();
	var curDate = null;
	
	do { curDate = new Date(); } 
	while(curDate-date < millis);
} 
var indiceFilaFormulario=1;   
function llenaDatos(campos){          
	if(campos[0].totalR==0)
	{
		alert("No hay gu\u00EDas disponibles en este documento.");
	}
	else
	{	   
		var guias=new Array(); 
		var inGuias=$$('[id^=txtGuia_]'); 
 
		for (var i=0; i<inGuias.length; i++){      
			nombre=inGuias[i].id; 
			numero=nombre.substr(8,nombre.length); 
			var guiaGuardada=$('txtGuia_'+numero).value;  
			guias[i]=guiaGuardada; 
		}  
		 
		var peso;  
		var totalImporte=0;  
		var totalIva=0;  
		var totalSubtotal=0;  
		var totalRetencion=0;  
		var totalTotal=0;  
		 
		for (var i=0; i<campos.length; i++){  
	 
			var campo = campos[i]; 
			var guiaActual=guias.indexOf(campo.cveGuia); 
			//Si el índice es distinto a -1 significa la guía ya existe 
			if(guias.length==0) guiaActual=-1; 
			 
			if(guiaActual==-1){ 
				 
				var valorf=campo.valorDeclarado;                 
				if(valorf <= 0) {valorf=0;} 
				var retencionf=parseFloat(valorf*campo.importe/100);  
				var totalf=campo.subtotal-retencionf; 

				//Si se sobrepasa el valor permitido , no se aceptará la guía 
				var tope=$("txtTope").value; 
				if(totalf>tope) 
				{ 
					alert("La gu\u00EDa '"+ campo.cveGuia+"' sobrepasa el tope de la factura."); 
				} 
				else 
				{ 
					$("datos").className="";  
	 
					myNewRow = document.getElementById("tablaFormulario").insertRow(-1);   
					myNewRow.id=indiceFilaFormulario; 
					 
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input name='txtFecha_"+indiceFilaFormulario+"' type='text' id='txtFecha_"+indiceFilaFormulario+"' value='"+ campo.recepcionCYE +"' size='10' readonly='true'/>";  
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='text' id='txtGuia_"+indiceFilaFormulario+"' name='txtGuia_"+indiceFilaFormulario+"' value='"+ campo.cveGuia +"' size='10' readonly='true' />";  
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='text' id='txtFactura_"+indiceFilaFormulario+"' name='txtFactura_"+indiceFilaFormulario+"' value='"+ campo.cvefacturas +"' readonly='true'>";  
					 
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='text' id='txtFolio_"+indiceFilaFormulario+"' name='txtFolio_"+indiceFilaFormulario+"' maxlength='120' value='"+ campo.planificacionFolio +"'  readonly='true' />";  
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input name='txtDestino_"+indiceFilaFormulario+"' type='text' id='txtDestino_"+indiceFilaFormulario+"' value='"+ campo.sucursalDestino +"' size='8' readonly='true' />";  
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='text' id='txtDestinatario_"+indiceFilaFormulario+"' name='txtDestinatario_"+indiceFilaFormulario+"' value='"+ campo.nombreDestinatario +"' readonly='true'/>";     
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='text' id='txtObservacion_"+indiceFilaFormulario+"' name='txtObservacion_"+indiceFilaFormulario+"' value='"+ campo.tipoEnvio +"' size='10' readonly='true'/>";  
					 
					var valorD=campo.valorDeclarado;                 
					if(valorD <= 0) {valorD=0;} 
					 
					myNewCell=myNewRow.insertCell(-1); 
					myNewCell.innerHTML="<input type='text' id='txtValorD_"+indiceFilaFormulario+"' name='txtValorD_"+indiceFilaFormulario+"' maxlength='120' value='"+ valorD +"'  class='moneda' readonly='true'/>"; 
					
					 
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='text' id='txtpiezas_"+indiceFilaFormulario+"' name='txtpiezas_"+indiceFilaFormulario+"' value='"+ campo.piezas +"' size='8' readonly='true'/>";  
					
				   //Los input de aquí si se podrán modificar
					myNewCell=myNewRow.insertCell(-1);  
	
					myNewCell.innerHTML="<input name='txtPeso_"+indiceFilaFormulario+"' type='text' id='txtPeso_"+indiceFilaFormulario+"' value='"+ campo.peso +"' size='8' onchange='calculaFlete(this)' onkeypress='return chk_num(this.id,event)'/>";  
					 
					
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='text' id='txtTarifa_"+indiceFilaFormulario+"' name='txtTarifa_"+indiceFilaFormulario+"' value='"+ campo.cargo +"' size='8' onchange='calculaFlete(this)' onkeypress='return chk_num(this.id,event)'/>";    
					
				  
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='text' id='txtFleteF_"+indiceFilaFormulario+"' name='txtFleteF_"+indiceFilaFormulario+"' value='"+ campo.flete +"' size='8' class='moneda' onchange='calculaDemas(this)' onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtFlete_"+indiceFilaFormulario+"' name='txtFlete_"+indiceFilaFormulario+"' value='"+ campo.flete +"'>"; 
					
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='text' id='txtSeguroF_"+indiceFilaFormulario+"' name='txtSeguroF_"+indiceFilaFormulario+"' maxlength='120' size='8' class='moneda' onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtSeguro_"+indiceFilaFormulario+"' name='txtSeguro_"+indiceFilaFormulario+"' value='0' />";    
					 
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='text' id='txtAcuseF_"+indiceFilaFormulario+"' name='txtAcuseF_"+indiceFilaFormulario+"' value='"+ campo.acuse +"' maxlength='120' size='8' class='moneda' onkeypress='return chk_num(this.id,event)' onchange='calculaDemas2(this,this.value)'/><input type='hidden' id='txtAcuse_"+indiceFilaFormulario+"' name='txtAcuse_"+indiceFilaFormulario+"' value='"+ campo.acuse +"' />";    
					
					myNewCell=myNewRow.insertCell(-1);  

					var importe=formatoMoneda(campo.importe);  
					myNewCell.innerHTML="<input name='txtImporteF_"+indiceFilaFormulario+"' type='text' id='txtImporteF_"+indiceFilaFormulario+"' value='"+ importe +"' size='10' class='moneda' onkeypress='return chk_num(this.id,event)'/><input name='txtImporte_"+indiceFilaFormulario+"' type='hidden' id='txtImporte_"+indiceFilaFormulario+"' value='"+ campo.importe +"'/>";  
					
					myNewCell=myNewRow.insertCell(-1);  
					var iva=formatoMoneda(campo.iva);  
					myNewCell.innerHTML="<input type='text' id='txtIvaF_"+indiceFilaFormulario+"' name='txtIvaF_"+indiceFilaFormulario+"' value='"+ iva +"' size='10' class='moneda'onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtIva_"+indiceFilaFormulario+"' name='txtIva_"+indiceFilaFormulario+"' value='"+ campo.iva +"'/>";  
					 
					myNewCell=myNewRow.insertCell(-1);  
					var subtotal=formatoMoneda(campo.subtotal);  
					myNewCell.innerHTML="<input type='text' id='txtSubtotalF_"+indiceFilaFormulario+"' name='txtSubtotalF_"+indiceFilaFormulario+"' value='"+ subtotal +"' size='10' class='moneda' onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtSubtotal_"+indiceFilaFormulario+"' name='txtSubtotal_"+indiceFilaFormulario+"' value='"+ campo.subtotal +"'/>";  
					 
					myNewCell=myNewRow.insertCell(-1);  
		 
					if($("txtValorD").value=="")
						porRet=0;
					else
						porRet=$("txtValorD").value;
					var retencion=parseFloat(porRet*campo.importe/100);  
					var retencionb=formatoMoneda(retencion);  
									 
					myNewCell.innerHTML="<input type='text' id='txtRetencionIvaF_"+indiceFilaFormulario+"' name='txtRetencionIvaF_"+indiceFilaFormulario+"' value='"+ retencionb +"' maxlength='120' size='15' class='moneda' onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtRetencionIva_"+indiceFilaFormulario+"' name='txtRetencionIva_"+indiceFilaFormulario+"' value='"+ retencion +"' />";  
					myNewCell=myNewRow.insertCell(-1); 
					
					
					var subtotal=formatoMoneda(campo.subtotal); 
					var totalP=campo.subtotal-retencion; 		 
					var totalPr=formatoMoneda(totalP); 
					 
					myNewCell.innerHTML="<input type='text' id='txtTotalF_"+indiceFilaFormulario+"' name='txtTotalF_"+indiceFilaFormulario+"' maxlength='120' value='"+ totalPr +"' size='10' class='moneda' onkeypress='return chk_num(this.id,event)'/><input type='hidden' id='txtTotal_"+indiceFilaFormulario+"' name='txtTotal_"+indiceFilaFormulario+"' value='"+ totalP +"' />";  
					 
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='text' id='txtObservacionB_"+indiceFilaFormulario+"' name='txtObservacionB_"+indiceFilaFormulario+"' value='"+ campo.obsGuia +"'  maxlength='120' />";  
					myNewCell=myNewRow.insertCell(-1);  
					myNewCell.innerHTML="<input type='hidden' id='hdnCargoM_"+indiceFilaFormulario+"' name='hdnCargoM_"+indiceFilaFormulario+"' value='"+ campo.cargoMinimo +"' /><input type='hidden' id='cveIva_"+indiceFilaFormulario+"' name='cveIva_"+indiceFilaFormulario+"' value='"+ campo.cveIva +"' /><input type='button'  value='Eliminar' onclick='removePerson(this)'>";  
					 
					calculaTotales();   
					if(campo.valorDeclarado>0)  
					{      
						var valorD=parseInt(campo.valorDeclarado);  
						var porcentajeSeguro=parseInt($("txtPorcentajeSeguro").value)/100;   
						var seguro=valorD * porcentajeSeguro;  
						$("txtSeguro_" + indiceFilaFormulario).value=redondeo2decimales(seguro);  
						$("txtSeguroF_" + indiceFilaFormulario).value=formatoMoneda(redondeo2decimales(seguro));   
					} 
				
					indiceFilaFormulario++;  
					$('hdnContador').value=indiceFilaFormulario;                
					$("btnImprimir").disabled=false;
					$("btnImprimir").onclick=guardarEnvio;
				} 
			} 
			else { alert('La gu\u00EDa '+ campo.cveGuia+' ya se encuentra agregada.'); } 
		}  
	
		if($("txtFolioFactura").disabled==false){
			$("btnGuardar").onclick=guardar;
			$("btnGuardar").disabled=false;
			$("btnModificar").disabled=true;
		}  
	}   
}  
 
 
function modificar()
{
	 //Antes que nada checar, si el formato es abierto, que se hayan ingresado correctamente los vales 
    formato=$$("input[name='rdoFormato']:checked"); 
    valor_formato=formato[0].value; 
     
    if(valor_formato==3){ 
        if($('txaAVales').value!="") 
        { 
            //Checar que no se repita el valor 
            var cadena=$('txaAVales').value;    
            arreglo=cadena.split(","); 
            longitud1=arreglo.length; 
            arreglo2=arreglo.uniq();         //Traerá arreglo sin repeticiones 
            longitud2=arreglo2.length; 
             
            if(longitud1 != longitud2)        //Valores repetidos 
            { 
                alert("Existe un error en los datos para la factura abierta,debe haber un valor repetido (o comas seguidas)."); 
                return false; 
            } 
        }else { alert("Debe ingresar datos, para su factura abierta."); 
                return false; 
		} 
        vales=$('txaAVales').value; 		
    }else vales=""; 
	  
	if(checarGuias()){ 
		 elementos=$$('input[name^=txtImporte_])'); 
		 var totalguias=elementos.length; 
		  
		 var valores="";  
					
		 for (var j=2;j<22;j++)  
		 {  
			 if($('condicion_'+j).checked) 
				 valores+="&condicion_"+j+"=on";  
			else 
				 valores+="&condicion_"+j+"=";  
		 }  
		  
		valores+="&formato="+valor_formato+"&valesAbierta="+vales+"&txtcveCliente="+$("hdncveCliente").value;  
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;
	 
		valores=valores +usuario;   
		$Ajax("scripts/actualizarFacturas.php?opcion=1&factura="+$('txtFolioFactura').value, {metodo: $metodo.POST, onfinish: msjAct, parametros: valores, avisoCargando:"loading"});  
	}


}

function guardar(){  
   
     //Antes que nada checar, si el formato es abierto, que se hayan ingresado correctamente los vales 
    formato=$$("input[name='rdoFormato']:checked"); 
    valor_formato=formato[0].value; 
     
    if(valor_formato==3){ 
        if($('txaAVales').value!="") 
        { 
            //Checar que no se repita el valor 
            var cadena=$('txaAVales').value;    
            arreglo=cadena.split(","); 
            longitud1=arreglo.length; 
            arreglo2=arreglo.uniq();         //Traerá arreglo sin repeticiones 
            longitud2=arreglo2.length; 
             
            if(longitud1 != longitud2)        //Valores repetidos 
            { 
                alert("Existe un error en los datos para la factura abierta,debe haber un valor repetido (o comas seguidas)."); 
                return false; 
            } 
        }else { alert("Debe ingresar datos, para su factura abierta."); 
                return false; 
		} 
        vales=$('txaAVales').value; 
    }else vales=""; 
	
    if($('txtFolioFactura').value=="")
	{ alert("Es necesario el folio de la factura.");}
    else{ 
        if(checarGuias()){ 		
			 var GuiasOrden="";
             elementos=$$('input[name^=txtImporte_])'); 
             var totalguias=elementos.length; 
              
             var valores="";  
                        
             for (var j=2;j<22;j++)  
             {  
                 if($('condicion_'+j).checked) 
                     valores+="&condicion_"+j+"=on";  
                else 
                     valores+="&condicion_"+j+"=";  
             }  
              
             //Antes de tomar los valores se renombrarna todos los elementos de la Tabla, ya que al gaurdarlos no hay forma de saber que numero tienen 
             elementos=$$("#tablaFormulario tr"); 
             nombres=new Array('txtFecha_','txtGuia_','txtFactura_','txtFolio_','txtDestino_','txtDestinatario_','txtObservacion_','txtValorD_', 
    'txtpiezas_','txtPeso_','txtTarifa_','txtFleteF_','txtSeguroF_','txtAcuseF_','txtImporteF_','txtIvaF_','txtSubtotalF_','txtRetencionIvaF_', 
    'txtTotalF_','txtObservacionB_','txtFlete_','txtSeguro_','txtAcuse_','txtImporte_','txtIva_','txtSubtotal_','txtRetencionIva_','txtTotal_', 
    'hdnCargoM_','cveIva_'); 
              
             for(i=1;i<(elementos.length);i++) 
             { 
                 renglon=elementos[i].id; 
                 inputs=$$("#"+renglon+" input[type=text],#"+renglon+" input[type=hidden]"); 
                 for(j=0;j<inputs.length;j++) 
                 {  
                    inputs[j].name=nombres[j]+i; 
                    inputs[j].id=nombres[j]+i; 
                 } 
             } 
              
             elementos=$$('input[name^=txtImporte_])'); 
             for(var i=0; i<elementos.length; i++)  
             {  
                nombre=elementos[i].id; 
                numero=nombre.substr(11,nombre.length); 
     
                valores+="&txtFecha_"+numero+"="+$('txtFecha_'+numero).value    +"&txtGuia_"+numero+"="+$('txtGuia_'+numero).value    +"&txtFactura_"+numero+"="+$('txtFactura_'+numero).value    +"&txtFolio_"+numero+"="+$('txtFolio_'+numero).value    +"&txtDestino_"+numero+"="+$('txtDestino_'+numero).value    +"&txtDestinatario_"+numero+"="+$('txtDestinatario_'+numero).value    +"&txtObservacion_"+numero+"="+$('txtObservacion_'+numero).value    +"&txtValorD_"+numero+"="+$('txtValorD_'+numero).value    +"&txtpiezas_"+numero+"="+$('txtpiezas_'+numero).value    +"&txtPeso_"+numero+"="+$('txtPeso_'+numero).value    +"&txtTarifa_"+numero+"="+$('txtTarifa_'+numero).value    +"&txtFlete_"+numero+"="+$('txtFlete_'+numero).value    +"&txtSeguro_"+numero+"="+$('txtSeguro_'+numero).value    +"&txtAcuse_"+numero+"="+$('txtAcuse_'+numero).value    +"&txtImporte_"+numero+"="+$('txtImporte_'+numero).value+    "&txtIva_"+numero+"="+$('txtIva_'+numero).value    +"&txtSubtotal_"+numero+"="+$('txtSubtotal_'+numero).value    +"&txtRetencionIva_"+numero+"="+$('txtRetencionIva_'+numero).value+"&txtTotal_"+numero+"="+$('txtTotal_'+numero).value+"&txtObservacionB_"+numero+"="+$('txtObservacionB_'+numero).value;
                valores+="&cveIva_"+numero+"="+$('cveIva_'+numero).value;  

				GuiasOrden+=numero+".  "+$('txtGuia_'+numero).value+"\n";                   
             }  
              
              
            valores+="&valesAbierta="+vales+"&txtFolios="+$('hdnFolios').value    +"&txttotalImporte="+$('totalImporte').value    +"&txttotalIva="+$('totalIva').value    +"&txttotalSubtotal="+$('totalSubtotal').value    +"&txttotalRetencion="+$('totalRetencion').value    +"&txttotalTotal="+$('totalTotal').value    +"&txtFolioFactura="+$('txtFolioFactura').value+"&txtcveCliente="+$("hdncveCliente").value;
			
			//Checar valores principales
			if($("txtValorD").value=="") retencion=0;
			else 						 retencion=$("txtValorD").value;
			
			if($("txtPorcentajeSeguro").value=="") seguro=0;
			else 								   seguro=$("txtPorcentajeSeguro").value;
			
			if($("txthValorD").value=="") impuesto=0;
			else 						  impuesto=$("txthValorD").value;
			
			valores+="&retencion="+retencion+"&impuesto="+impuesto+"&seguro="+seguro;  			   			
            var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value+"&txtTope="+$("txtTope").value+"&cveRetencion="+retencion;               
            valores=valores +usuario;   

			
			//Preguntar por el orden de las Guías,antes de guardar
			if(confirm("El orden de las gu\u00EDas es el siguiente:\n"+GuiasOrden+"\r\u00BFDesea continuar?"))
				$Ajax("scripts/guardarFacturas.php?contador="+totalguias, {metodo: $metodo.POST, onfinish: imprimir, parametros: valores, avisoCargando:"loading"});  
        }
    } 
}  
 
function checarGuias() 
{ 
   //Checar que el seguro, retencion, acuse y tope sean numéricos
    if($("txtValorD").value!="" && isNaN($("txtValorD").value)) 
    { 
   	 alert("La retenci\u00F3n debe ser un n\u00FAmero"); return false;
    }
    if($("txtPorcentajeSeguro").value!="" && isNaN($("txtPorcentajeSeguro").value)) 
    { 
   	 alert("El % de seguro debe ser un n\u00FAmero"); return false;
    }
    if($("txtPrecioA").value!="" && isNaN($("txtPrecioA").value)) 
    { 
   	 alert("El acuse debe ser un n\u00FAmero"); return false;
    }
    if($("txtTope").value!="" && isNaN($("txtTope").value)) 
    { 
   	 alert("El tope debe ser un n\u00FAmero"); return false;
    }
    

    var tope=parseInt($("txtTope").value); 
     
    var totalguias=$('hdnContador').value;  
    elementos=$$('input[name^=txtImporte_])'); 
    for(var i=0; i<elementos.length; i++){         
         
        nombre=elementos[i].id; 
        numero=nombre.substr(11,nombre.length); 
        num_guia=$('txtGuia_'+numero).value;    
        totp=parseInt($('txtTotal_'+numero).value); 
        if((totp>tope)||(totp<=0)) 
        { 
            if(totp<=0) 
                alert("La gu\u00EDa '"+ num_guia+"' tiene un importe igual o inferior a cero."); 
            else 
                alert("La gu\u00EDa '"+ num_guia+"' sobrepasa el tope de la factura."); 
            return false; 
        } 
    } 
    return true; 
} 
 
function guardarEnvio(){ 
	//Antes que nada checar, si el formato es abierto, que se hayan ingresado correctamente los vales
	formato=$$("input[name='rdoFormato']:checked");
	valor_formato=formato[0].value;
	
	if(valor_formato==3){
		if($('txaAVales').value!="")
		{
			//Checar que no se repita el valor
			var cadena=$('txaAVales').value;   
			arreglo=cadena.split(",");
			longitud1=arreglo.length;
			arreglo2=arreglo.uniq();         //Traerá arreglo sin repeticiones
			longitud2=arreglo2.length;
			
			if(longitud1 != longitud2)		//Valores repetidos
			{
				alert("Existe un error en los datos para la factura abierat,debe haber un valor repetido (o hay comas seguidas).");
				return false;
			}
		}else { alert("Debe ingresar datos, para su factura abierta.");
				return false; }
		vales=$('txaAVales').value;
	}else vales="";
		
	if(checarGuias()){
		 elementos=$$('input[name^=txtImporte_])');
		 var totalguias=elementos.length;
		 
		 var valores=""; 
				   
		 for (var j=2;j<22;j++) 
		 { 
			 if($('condicion_'+j).checked)
				 valores+="&condicion_"+j+"=on"; 
			else
				 valores+="&condicion_"+j+"="; 
		 } 
		 
		 //Antes de tomar los valores se renombrarna todos los elementos de la Tabla, ya que al gaurdarlos no hay forma de saber que numero tienen
		 elementos=$$("#tablaFormulario tr");
		 nombres=new Array('txtFecha_','txtGuia_','txtFactura_','txtFolio_','txtDestino_','txtDestinatario_','txtObservacion_','txtValorD_',
'txtpiezas_','txtPeso_','txtTarifa_','txtFleteF_','txtSeguroF_','txtAcuseF_','txtImporteF_','txtIvaF_','txtSubtotalF_','txtRetencionIvaF_',
'txtTotalF_','txtObservacionB_','txtFlete_','txtSeguro_','txtAcuse_','txtImporte_','txtIva_','txtSubtotal_','txtRetencionIva_','txtTotal_',
'hdnCargoM_','cveIva_');
		 
		 for(i=1;i<(elementos.length);i++)
		 {
			 renglon=elementos[i].id;
			 inputs=$$("#"+renglon+" input[type=text],#"+renglon+" input[type=hidden]");
			 for(j=0;j<inputs.length;j++)
			 { 
				inputs[j].name=nombres[j]+i;
				inputs[j].id=nombres[j]+i;
			 }
		 }
		 
		 elementos=$$('input[name^=txtImporte_])');
		 for(var i=0; i<elementos.length; i++) 
		 { 
			nombre=elementos[i].id;
			numero=nombre.substr(11,nombre.length);
	
			valores+="&txtFecha_"+numero+"="+$('txtFecha_'+numero).value    +"&txtGuia_"+numero+"="+$('txtGuia_'+numero).value    +"&txtFactura_"+numero+"="+$('txtFactura_'+numero).value    +"&txtFolio_"+numero+"="+$('txtFolio_'+numero).value    +"&txtDestino_"+numero+"="+$('txtDestino_'+numero).value    +"&txtDestinatario_"+numero+"="+$('txtDestinatario_'+numero).value    +"&txtObservacion_"+numero+"="+$('txtObservacion_'+numero).value    +"&txtValorD_"+numero+"="+$('txtValorD_'+numero).value    +"&txtpiezas_"+numero+"="+$('txtpiezas_'+numero).value    +"&txtPeso_"+numero+"="+$('txtPeso_'+numero).value    +"&txtTarifa_"+numero+"="+$('txtTarifa_'+numero).value    +"&txtFlete_"+numero+"="+$('txtFlete_'+numero).value    +"&txtSeguro_"+numero+"="+$('txtSeguro_'+numero).value    +"&txtAcuse_"+numero+"="+$('txtAcuse_'+numero).value    +"&txtImporte_"+numero+"="+$('txtImporte_'+numero).value+    "&txtIva_"+numero+"="+$('txtIva_'+numero).value    +"&txtSubtotal_"+numero+"="+$('txtSubtotal_'+numero).value    +"&txtRetencionIva_"+numero+"="+$('txtRetencionIva_'+numero).value+"&txtTotal_"+numero+"="+$('txtTotal_'+numero).value+"&txtObservacionB_"+numero+"="+$('txtObservacionB_'+numero).value;
			valores+="&cveIva_"+numero+"="+$('cveIva_'+numero).value; 
			  
		 } 
		 
		 
		valores+="&valesAbierta="+vales+"&txtFolios="+$('hdnFolios').value    +"&txttotalImporte="+$('totalImporte').value    +"&txttotalIva="+$('totalIva').value    +"&txttotalSubtotal="+$('totalSubtotal').value    +"&txttotalRetencion="+$('totalRetencion').value    +"&txttotalTotal="+$('totalTotal').value    +"&txtFolioFactura="+$('txtFolioFactura').value+"&txtcveCliente="+$("hdncveCliente").value+"&retencion="+$("txtValorD").value; 

			//Checar valores principales
		if($("txtValorD").value=="") retencion=0;
		else 						 retencion=$("txtValorD").value;
		
		if($("txthValorD").value=="") impuesto=0;
		else 						  impuesto=$("txthValorD").value;
		
			
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value+"&txtTope="+$("txtTope").value+"&cveRetencion="+retencion+"&impuesto="+impuesto;     
		valores=valores +usuario;  
		$Ajax("scripts/guardarEnvios.php?contador="+totalguias, {metodo: $metodo.POST, onfinish: imprimirEnvio, parametros: valores, avisoCargando:"loading"}); 
	}
}   
  
function removePerson(obj){   
    var oTr = obj;  
    while(oTr.nodeName.toLowerCase()!='tr'){  
        oTr=oTr.parentNode;  
    }  
    var root = oTr.parentNode;  
    root.removeChild(oTr); 
    calculaTotales(); 
    //Si eliminamos todo, quitar la tabla 
    renglones = document.getElementById("tablaFormulario").rows.length;       
    if(renglones==1) 
    { 
        $("datos").className="oculto";  
        $("totalImporte").value=0; 
        $("totalIva").value=0; 
        $("totalSubtotal").value=0; 
        $("totalRetencion").value=0; 
        $("totalTotal").value=0; 
        $("btnGuardar").disabled=true; 
    } 
}  

function calculaDemas2(obj,val) 
{ 

	var valorD=$("txtValorD").value;  
    var numero= obj.id.substring(obj.id.lastIndexOf("_"),obj.id.length);  
     
    var porcentajeIva=parseInt($("cveIva" + numero ).value)/100;      
    var flete=parseFloat($("txtFleteF" + numero).value);  
     
    $("txtFlete" + numero).value=redondeo2decimales(flete);  

	var importe = redondeo2decimales(parseFloat(flete) + parseFloat(val));
	$("txtAcuse" + numero).value=parseFloat(val); 
	$("txtAcuseF" + numero).value=parseFloat(val); 

    $("txtImporte" + numero).value=redondeo2decimales(importe);  
    $("txtImporteF" + numero).value=formatoMoneda(redondeo2decimales(importe));  
	
    var iva=redondeo2decimales(parseFloat(importe)*porcentajeIva);   	
    $("txtIva" + numero).value=redondeo2decimales(iva);  
    $("txtIvaF" + numero).value=formatoMoneda(redondeo2decimales(iva));  	

    var retencion=redondeo2decimales(valorD*importe/100);  
    $("txtRetencionIva" + numero).value=redondeo2decimales(retencion);  
    $("txtRetencionIvaF" + numero).value=formatoMoneda(redondeo2decimales(retencion));  
   
	var subtotal=redondeo2decimales(parseFloat(importe) + parseFloat(iva));      
    $("txtSubtotal" + numero).value=redondeo2decimales(subtotal);  
    $("txtSubtotalF" + numero).value=formatoMoneda(redondeo2decimales(subtotal));  
    
	var total=($("txtSubtotal" + numero).value)-($("txtRetencionIva" + numero).value);
	$("txtTotal" + numero).value=redondeo2decimales(total);  
    $("txtTotalF" + numero).value=formatoMoneda(redondeo2decimales(total));  
    calculaTotalesb();
}
 
 
function calculaDemas(obj) 
{ 
    var valorD=$("txtValorD").value;  
    var numero= obj.id.substring(obj.id.lastIndexOf("_"),obj.id.length);  
     
    var porcentajeIva=redondeo2decimales($("cveIva" + numero ).value)/100;      
    var flete=redondeo2decimales($("txtFleteF" + numero).value);  
     
    $("txtFlete" + numero).value=redondeo2decimales(flete);  

    var importe = redondeo2decimales(parseFloat(flete) + parseFloat($("txtAcuse" + numero).value));
    $("txtImporte" + numero).value=redondeo2decimales(importe);  
    $("txtImporteF" + numero).value=formatoMoneda(redondeo2decimales(importe));  
	
    var iva=redondeo2decimales(parseFloat(importe)*porcentajeIva);   	
    $("txtIva" + numero).value=redondeo2decimales(iva);  
    $("txtIvaF" + numero).value=formatoMoneda(redondeo2decimales(iva));  
   
   	var retencion=redondeo2decimales(valorD*importe/100);  
    $("txtRetencionIva" + numero).value=redondeo2decimales(retencion);  
    $("txtRetencionIvaF" + numero).value=formatoMoneda(redondeo2decimales(retencion));  
   
	var subtotal=redondeo2decimales(parseFloat(importe) + parseFloat(iva));      
    $("txtSubtotal" + numero).value=redondeo2decimales(subtotal);  
    $("txtSubtotalF" + numero).value=formatoMoneda(redondeo2decimales(subtotal));  
     
	var total=($("txtSubtotal" + numero).value)-($("txtRetencionIva" + numero).value);
	$("txtTotal" + numero).value=redondeo2decimales(total);  
    $("txtTotalF" + numero).value=formatoMoneda(redondeo2decimales(total));  
    calculaTotalesb();  
}  
  
function sumaFlete(obj){  

    var valorD=$("txtValorD").value;  
    var numero= obj.id.substring(obj.id.lastIndexOf("_"),obj.id.length);  
    var porcentajeIva=parseInt($("cveIva" + numero ).value)/100;
    var flete=redondeo2decimales($("txtFlete" + numero).value);   
     
          
    var acuse= redondeo2decimales($("txtPrecioA").value);
    $("txtAcuse" + numero).value=redondeo2decimales(acuse);  
    $("txtAcuseF" + numero).value=redondeo2decimales(acuse); 
     
    $("txtFlete" + numero).value=redondeo2decimales(flete);  
    $("txtFleteF" + numero).value=redondeo2decimales(flete);  
     
    var importe = redondeo2decimales(parseFloat(flete)+parseFloat(acuse));	
    $("txtImporte" + numero).value=redondeo2decimales(importe);  
    $("txtImporteF" + numero).value=formatoMoneda(redondeo2decimales(importe));  
	
    var iva=redondeo2decimales(parseFloat(importe)*porcentajeIva);
    $("txtIva" + numero).value=redondeo2decimales(iva);  
    $("txtIvaF" + numero).value=formatoMoneda(redondeo2decimales(iva));  
   
    var retencion=redondeo2decimales(valorD*importe/100);  
    $("txtRetencionIva" + numero).value=redondeo2decimales(retencion);  
    $("txtRetencionIvaF" + numero).value=formatoMoneda(redondeo2decimales(retencion));  
   
	var subtotal=redondeo2decimales(parseFloat(importe) + parseFloat(iva));      
    $("txtSubtotal" + numero).value=redondeo2decimales(subtotal);  
    $("txtSubtotalF" + numero).value=formatoMoneda(redondeo2decimales(subtotal));  
  
	var total=($("txtSubtotal" + numero).value)-($("txtRetencionIva" + numero).value);
    $("txtTotal" + numero).value=redondeo2decimales(total);  
    $("txtTotalF" + numero).value=formatoMoneda(redondeo2decimales(total));  
    calculaTotalesb();  
}  
 
function calculaFlete(obj){  
      
    var valorD=$("txtValorD").value;  
	
    var numero= obj.id.substring(obj.id.lastIndexOf("_"),obj.id.length);  
     
    var peso=parseInt($("txtPeso" + numero ).value).toFixed(2);  
    var tarifa=parseFloat($("txtTarifa" + numero ).value).toFixed(2);  
    var cargoMinimo=parseFloat($("hdnCargoM" + numero ).value).toFixed(2);  
    var porcentajeIva=(parseInt($("cveIva" + numero ).value)/100).toFixed(2);    
     
    
    var flete=(peso * tarifa); 
	
    if(flete>cargoMinimo)  
    { flete=flete;       }  
    else  
    { flete=cargoMinimo; }     
	
    $("txtFlete" + numero).value=redondeo2decimales(flete);  
    $("txtFleteF" + numero).value=redondeo2decimales(flete);//formatoMoneda(redondeo2decimales(flete));  
	
	var importe = redondeo2decimales(parseFloat(flete)+parseFloat($("txtAcuse" + numero).value));	
    $("txtImporte" + numero).value=redondeo2decimales(importe);  
    $("txtImporteF" + numero).value=formatoMoneda(redondeo2decimales(importe));  
	
	var iva=redondeo2decimales(parseFloat(importe)*porcentajeIva);
    $("txtIva" + numero).value=redondeo2decimales(iva);  
    $("txtIvaF" + numero).value=formatoMoneda(redondeo2decimales(iva));  
    
	var retencion=redondeo2decimales(valorD*importe/100);  
    $("txtRetencionIva" + numero).value=redondeo2decimales(retencion);  
    $("txtRetencionIvaF" + numero).value=formatoMoneda(redondeo2decimales(retencion));  
    
	var subtotal=redondeo2decimales(parseFloat(importe) + parseFloat(iva));      
    $("txtSubtotal" + numero).value=redondeo2decimales(subtotal);  
    $("txtSubtotalF" + numero).value=formatoMoneda(redondeo2decimales(subtotal));  
    

	var total=($("txtSubtotal" + numero).value)-($("txtRetencionIva" + numero).value);
    $("txtTotal" + numero).value=redondeo2decimales(total);  
    $("txtTotalF" + numero).value=formatoMoneda(redondeo2decimales(total));  
    calculaTotalesb();  
}  
   
function totalSeguro(obj){
      
    var valorD=$("txtValorD").value;  
    var numero= obj.id.substring(obj.id.lastIndexOf("_"),obj.id.length);  
     
    var valorD=parseInt($("txtValorD" + numero ).value);  
    var porcentajeSeguro=parseInt($("txtPorcentajeSeguro").value)/100;  
     
    var seguro=valorD * porcentajeSeguro;  
    $("txtSeguro" + numero).value=redondeo2decimales(seguro);  
    $("txtSeguroF" + numero).value=formatoMoneda(redondeo2decimales(seguro));   
} 
 
function calculaTotalesb()  
{  
    var totalImporte=0;  
    var totalIva=0;  
    var totalSubtotal=0;  
    var totalRetencion=0;  
    var totalTotal=0;  

	elementos=$$('input[name^=txtImporte_])'); 	 
    for (var i=0; i<elementos.length; i++){  

	 	nombre=elementos[i].id; 
        numero=nombre.substr(11,nombre.length);
		//Convertir cantidades
		importeParcial   = parseFloat($("txtImporte_" + numero ).value).toFixed(2);
		ivaParcial       = parseFloat($("txtIva_" +  numero).value).toFixed(2);  
		subtotalParcial  = parseFloat($("txtSubtotal_" + numero ).value).toFixed(2);  
		retencionParcial = parseFloat($("txtRetencionIva_" + numero ).value).toFixed(2);  
		totalParcial     = parseFloat($("txtTotal_" + numero ).value).toFixed(2); 
		
        totalImporte=parseFloat(totalImporte) + parseFloat(importeParcial);
        totalIva=parseFloat(totalIva) + parseFloat(ivaParcial);
        totalSubtotal=parseFloat(totalSubtotal) + parseFloat(subtotalParcial);
        totalRetencion=parseFloat(totalRetencion) + parseFloat(retencionParcial);
        totalTotal=parseFloat(totalTotal) + parseFloat(totalParcial);     
    }  
    
	//Los totales serán calculados sobre el total del importe, no sobre la suma de los parciales de cada rubro
	var iva=0;
	var subtotal=0;
	var retencion=0;
	var total=0;
	
	var importe=parseFloat(totalImporte).toFixed(2);

	var porcentajeRet=$("txtValorD").value/100;  
	var porcentajeIva=($("txthValorD").value)/100;     
	
	var retencion=importe*porcentajeRet;
	var iva=importe*porcentajeIva;
	
	var retencionFinal=Math.round((parseFloat(retencion))*100)/100 ;
	var ivaFinal=Math.round((parseFloat(iva))*100)/100 ;
	
	var iva=ivaFinal;
	var subtotal=parseFloat(importe)+parseFloat(iva);
	var retencion=retencionFinal;
	var total=parseFloat(subtotal) - parseFloat(retencion);
	
 	$("totalImporte").value   = importe;
	$("totalIva").value       = parseFloat(iva).toFixed(2);
	$("totalSubtotal").value  = parseFloat(subtotal).toFixed(2);
	$("totalRetencion").value = parseFloat(retencion).toFixed(2);
	$("totalTotal").value     = parseFloat(total).toFixed(2);
}  
 
function calculaTotales()  
{  
    var totalImporte=0;  
    var totalIva=0;  
    var totalSubtotal=0;  
    var totalRetencion=0;  
    var totalTotal=0;  
     
    elementos=$$('input[name^=txtImporte_])'); 
    for(var i=0;i<elementos.length;i++) 
    { 
        nombre=elementos[i].id; 
        numero=nombre.substr(11,nombre.length);

		//Convertir cantidades
		importeParcial   = (parseFloat($("txtImporte_" + numero ).value)).toFixed(2);
		ivaParcial       = (parseFloat($("txtIva_" + numero ).value)).toFixed(2);  
		subtotalParcial  = (parseFloat($("txtSubtotal_" + numero ).value)).toFixed(2);  
		retencionParcial = (parseFloat($("txtRetencionIva_" + numero ).value)).toFixed(2);  
		totalParcial     = (parseFloat($("txtTotal_" + numero ).value)).toFixed(2);  
		
        totalImporte=parseFloat(totalImporte) + parseFloat(importeParcial);
        totalIva=parseFloat(totalIva) + parseFloat(ivaParcial);
        totalSubtotal=parseFloat(totalSubtotal) + parseFloat(subtotalParcial);
        totalRetencion=parseFloat(totalRetencion) + parseFloat(retencionParcial);
        totalTotal=parseFloat(totalTotal) + parseFloat(totalParcial);         
		
		//alert("aaa"+totalImporte);
    } 
    
	var contador=$('hdnContador').value;  
 
	//Los totales serán calculados sobre el total del importe, no sobre la suma de los parciales de cada rubro
	var iva=0;
	var subtotal=0;
	var retencion=0;
	var total=0;
	
	var importe=parseFloat(totalImporte).toFixed(2);

	var porcentajeRet=$("txtValorD").value/100;  
	var porcentajeIva=($("txthValorD").value)/100;     
	
	var retencion=importe*porcentajeRet;
	var iva=importe*porcentajeIva;
	
	var retencionFinal=Math.round((parseFloat(retencion))*100)/100 ;
	var ivaFinal=Math.round((parseFloat(iva))*100)/100 ;

	var iva=ivaFinal;
	var subtotal=parseFloat(importe) + parseFloat(iva);
	var retencion=retencionFinal;
	var total=parseFloat(subtotal) - parseFloat(retencion);
	
 	$("totalImporte").value   = importe;
	$("totalIva").value       = parseFloat(iva).toFixed(2);
	$("totalSubtotal").value  = parseFloat(subtotal).toFixed(2);
	$("totalRetencion").value = parseFloat(retencion).toFixed(2);
	$("totalTotal").value     = parseFloat(total).toFixed(2);
}  

function calculaRetencion()  
{  
    var tarifas=$$('[id^=txtTarifa_]'); 
 
    for (var i=0; i<tarifas.length; i++){      
        nombre=tarifas[i].id; 
        numero=nombre.substr(10,nombre.length); 
	 	calculaFlete($("txtTarifa_" + numero )); 
	}  
}  
 
function calculaSeguro()  
{  
    var seguros=$$('[id^=txtValorD_]'); 
 
    for (var i=0; i<seguros.length; i++){      
        nombre=seguros[i].id; 
        numero=nombre.substr(10,nombre.length);
		totalSeguro($("txtValorD_" + numero ));  
	}  
}  
 
function calculamasFlete()  
{  
    var seguros=$$('[id^=txtValorD_]'); 
 
    for (var i=0; i<seguros.length; i++){      
        nombre=seguros[i].id; 
        numero=nombre.substr(10,nombre.length); 
        sumaFlete($("txtValorD_" + numero ));  
	}  
}  
 
function fin(res){  
    alert(res);  
    $("status").innerHTML="";  
}  
  
  
function imprimir(res){
	formato=$$("input[name='rdoFormato']:checked"); 
    valor_formato=formato[0].value;     
 
    if (confirm(res+",\u00BFDesea imprimirla ahora?")){          
        var win = new Window({className: "mac_os_x", title: "Reporte del soporte de facturas", top:70, left:100, width:300, height:200, url: "scripts/pdfFacturas.php?cveFactura="+$('txtFolioFactura').value, showEffectOptions: {duration:1.5}});  
        win.show();           
		var win = new Window({className: "mac_os_x", title: "Reporte del soporte de facturas", top:70, left:500, width:300, height:200, url: "scripts/facturas.php?cveFactura="+$('txtFolioFactura').value+"&formato="+valor_formato, showEffectOptions: {duration:1.5}});  
		win.show();
    }  
         
    finalizar(); 
}  

function finalizar(){
	//Bloquear todos los input después del de Razon Social 
    bloquear(); 
    titulos(); 
	$("form2").reset();
	if(document.getElementById("tablaFormulario").rows.length>1){
		var ultima = document.getElementById("tablaFormulario").rows.length;
		for(var j=ultima; j>1; j--){				
	        document.getElementById("tablaFormulario").deleteRow(1);				 		
		}
  	}
	//CSS
	//Si eliminamos todo, quitar la tabla 
    renglones = document.getElementById("tablaFormulario").rows.length;       
    if(renglones==1) 
    { 
        $("datos").className="oculto";  
        $("totalImporte").value=0; 
        $("totalIva").value=0; 
        $("totalSubtotal").value=0; 
        $("totalRetencion").value=0; 
        $("totalTotal").value=0; 
        $("btnGuardar").disabled=true; 
    } 
	//El de la Factura neuvamente calcularlo
	 $Ajax("scripts/creaFactura.php?operacion=1", {onfinish: creaFolio, tipoRespuesta: $tipo.JSON});  
	$("btnImprimir").disabled=true;
	$("btnCancelar").disabled=true;
	$("btnGuardar").disabled=true;  
	$("btnFactura").disabled=true;
	$("btnModificar").disabled=true; 
	$("btnLiberar").disabled=true; 
	$("txtRazonS").disabled=false;  
}
 
function imprimirb(){               
    var formato=0;  
    for (var i=0; i<form2.rdoFormato.length; i++)  
    { if(form2.rdoFormato[i].checked){ formato=form2.rdoFormato[i].value;}     }  
     
    var win = new Window({className: "mac_os_x", title: "Reporte del soporte de facturas", top:70, left:100, width:300, height:200, url: "scripts/pdfFacturas.php?cveFactura="+$('txtFolioFactura').value, showEffectOptions: {duration:1.5}});  
    win.show();           
    var win = new Window({className: "mac_os_x", title: "Reporte del soporte de facturas", top:70, left:500, width:300, height:200, url: "scripts/facturas.php?cveFactura="+$('txtFolioFactura').value+"&formato="+formato+"&iva="+$('cveIva_1').value, showEffectOptions: {duration:1.5}});  
    win.show();      
     
    $("form2").reset();  
} 
         
function imprimirEnvio(res){                 
    
	respuesta=res.split("-");
	alert(respuesta[0]);	 
	if(respuesta[1]==1) //No hubo error
	{
		var win = new Window({className: "mac_os_x", title: "Reporte del soporte de facturas", top:70, left:160, width:950, height:500, url: "scripts/reporteEnvios.php?cveFactura="+$('txtFolioFactura').value, showEffectOptions: {duration:1.5}}); 
		win.show(); 
	 }
}  

function imprimirFac(){                 
    
	var formato=0; 
	forma=document.getElementById("form2");
    for (var i=0; i<forma.rdoFormato.length; i++) 
	{ 
		if(forma.rdoFormato[i].checked){ formato=forma.rdoFormato[i].value;} 
	} 
	factura=$('txtFolioFactura').value;

	var win = new Window({className: "mac_os_x", title: "Reporte del soporte de facturas", top:70, left:100, width:300, height:200, url: "scripts/pdfFacturas.php?cveFactura="+factura, showEffectOptions: {duration:1.5}}); 
	win.show();          
	var win = new Window({className: "mac_os_x", title: "Reporte del soporte de facturas", top:70, left:500, width:300, height:200, url: "scripts/facturas.php?cveFactura="+factura+"&formato="+formato+"&iva="+$('txthValorD').value, showEffectOptions: {duration:1.5}}); 

	win.show();     
} 
 
  
function valoresNulos(obj){  
    var nombres = new Array();  
    nombres['condicion_2']='txtFecha_';  
    nombres['condicion_3']='txtGuia_';     
    nombres['condicion_4']='txtFactura_';    
    nombres['condicion_5']='txtFolio_';    
    nombres['condicion_6']='txtDestino_';     
    nombres['condicion_7']='txtDestinatario_';    
    nombres['condicion_8']='txtObservacion_';        
    nombres['condicion_9']='txtValorD_';   
    nombres['condicion_10']= 'txtpiezas_';     
    nombres['condicion_11']='txtPeso_' ;  
    nombres['condicion_12']= 'txtTarifa_';    
    nombres['condicion_13']='txtFleteF_';     
    nombres['condicion_14']='txtSeguroF_';   
    nombres['condicion_15']='txtAcuseF_';  
    nombres['condicion_16']='txtImporteF_';   
    nombres['condicion_17']='txtIvaF_';    
    nombres['condicion_18']= 'txtSubtotalF_';   
    nombres['condicion_19']= 'txtRetencionIvaF_';   
    nombres['condicion_20']='txtTotalF_';   
    nombres['condicion_21']= 'txtObservacionB_';  
  
  
    var id_obj = obj.id;  
    var numero = obj.id.substring(obj.id.lastIndexOf("_")+1,obj.id.length);  
     
    if($(id_obj).checked){ 
        habilitar=true; 
        $(obj).title="Quitar Concepto"; 
    } 
    else{ 
        habilitar=false; 
        $(obj).title="Agregar Concepto"; 
    } 
     
 	elementos=$$('input[name^=txtImporte_])'); 

	for (var j=0; j<elementos.length; j++){  
		nombre=elementos[j].id;
        numero=nombre.substr(11,nombre.length); 
		nombreFin=nombres[id_obj]+numero;
		valorBlanco(nombreFin,habilitar); 
	}    
}  
 
function valorCero(obj){                   
    $(obj).value=0;            
}  
function valorBlanco(obj,habilitar){                   
    //$(obj).value='';               //Se quitaban los valores de esas columnas 
    $(obj).disabled=!(habilitar);    //Ahora sólo se desactivan 
} 
 
function mostrarArea(opc) 
{ 
    if(opc==1) 
        $("fldAbierta").removeClassName("fldDatosAbiertaInvisible"); 
    else 
        $("fldAbierta").addClassName("fldDatosAbiertaInvisible"); 
}
