var fechaGeneral="";                //Fecha elegida
var fechasAgregadas = new Array();	//Fechas que han sido elegidas
var guiasAgregadas = new Array();	//Guias
var cveDireccionGeneral="";			//Clave de dirección

window.onload = inicia;

function inicia() {		
	
	
	//Inicializando autocomplete
	$("autoCliente").className = "autocomplete";
	new Ajax.Autocompleter("txtRazonS", "autoCliente", "scripts/catalogoClientes.php?operacion=4", {paramName: "caracteres", afterUpdateElement:datosClientes});
	
	$("autoGuia").className = "autocomplete";
	$("btnAgregar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnImprimir").disabled=true;
	$("bntModificar").disabled=true;
	$("btnCancelar").disabled=true;	
	$("txtFechadelAcuse").disabled=true; 
	
	document.getElementById("tcalico_0").style.visibility="hidden";	

  
	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("btnGuardar").style.visibility="hidden";
		$("bntModificar").style.visibility="hidden";
		$("btnAgregar").style.visibility="hidden";
		
		$("btnGuardar").style.display="none";
		$("bntModificar").style.display="none";		
		$("btnAgregar").style.display="none";	
	}
}

function datosClientes() 
{	
	var url2 = $("txtRazonS").value;		
	$Ajax("scripts/datosClientes.php?operacion=3&cveCliente="+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});		
}

function cveCliente(campos) {	
		
	var campo = campos[0];

	var cliente=campo.cveCliente;
    $("autoGuia").className = "autocomplete";
	$("autoFolio").className = "autocomplete";
	complementos(cliente);
		
	$("txtRazonS").value=campo.razonSocial;
	$("hdncveCliente").value=campo.cveCliente;
		
}

function complementos(cliente)
{
	var urlG="scripts/catalogoGuias.php?operacion=3&cliente="+cliente;
	new Ajax.Autocompleter("txtGuia", "autoGuia",urlG , {paramName: "caracteres", afterUpdateElement:existe});
	
	new Ajax.Autocompleter("txtFolio", "autoFolio","scripts/catalogoAcuses.php?operacion=1&cliente="+cliente , {paramName: "caracteres", afterUpdateElement:traerFolios});
	
	pausecomp(800);
	
	var url="scripts/creaFolio.php?cveCliente="+cliente;
	$Ajax(url,{onfinish: function (folios)
					    {
													    
							$("txtGuia").disabled=true;						   														
							alert('Cargando datos.');							
														
							//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
							var folio = folios[0];
							//Asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
							$("txtFolio").value = folio.folio;
							
							document.getElementById("tcalico_0").style.visibility="visible";	
							$("txtRazonS").disabled=true;
							$("btnCancelar").disabled=false;
							$("txtFechadelAcuse").disabled=false;
							$("txtGuia").disabled=false;
	
						}, tipoRespuesta: $tipo.JSON});	
}

function existe() 
{		
	
	if ($("txtGuia").value!="")
	{
		
		var url = "scripts/existe.php?keys=11&table=cguias&field1=cveGuia&f1value=" + $("txtGuia").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});		
	}
}


function next_existe(ex){						
	//Extraemos el valor retornado por el servidor en un objeto json
	var exx=ex[0];
	var exists = exx.existe;
	//si el valor es mayor que cero, entonces el registro existe
	if (exists > 0)
	{	
	
		var url = "scripts/existe.php?keys=29&f1value="+$("txtGuia").value+"&f2value="+$("hdncveCliente").value;
		$Ajax(url, {onfinish: function (datos)
							  {
								if(datos[0].existe==0) 
									alert('Ocurri\u00F3 un error; la gu\u00EDa no pertenece al cliente.');
								else
								{
								  	//Vamos a checar si la guía ya ha sido agregada a otro acuse
									var url = "scripts/datosGenerales.php?operacion=14&valor="+$('hdncveCliente').value+"&guia="+$("txtGuia").value;
									$Ajax(url,{onfinish: function (datos)
									{
										var existe=datos[0].existe;
										if(existe==1)
										{															  
											$('divInfoGuiaAc').innerHTML="La gu&iacute;a '"+$("txtGuia").value+"' está registrada en el folio '"+datos[0].folAcuse+"'";
											$("txtGuia").value='';
										}
										else
										{
											//Se piden los datos
											$("btnAgregar").disabled=false;
											$("btnAgregar").onclick=validaGuia;
											$('divInfoGuiaAc').innerHTML=" ";
											var url2 = "scripts/datosAcuse.php?cveguia="+$("txtGuia").value;
											$Ajax(url2,{onfinish: llenaDatos2, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
										}
									}, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
								}

							  }, tipoRespuesta: $tipo.JSON});
	}
	else{	
		//Si la funcion devolvio cero, no existe el registro
		//limpiamos los campos del form					
		$("form2").reset();		
		alert("La guia que busca no existe");
	}
}

function creaFolio(folios){

	//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var folio = folios[0];
	//Asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
	$("txtFolio").value = folio.folio;
}
	
function llenaDatos2(campos){

	//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var campo = campos[0];
	//asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
			
	$("txtFecha").value = campo.llegadaacuse;
	$("txtRecibio").value = campo.recibio;
	$("txtDestino").value = campo.branch_municip;
	$("txtFacturas").value = campo.facturas;
	$("hdncveDireccion").value = campo.cvedireccion;

}

function creaFolio(folios){

	//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var folio = folios[0];
	//Asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
	$("txtFolio").value = folio.folio;
}


function fin(res){
	alert(res);
	$("txtGuia").disabled=false;	
	$("btnbuscar").disabled=false;	
	$("txtGuia").value = "";	
	$("status").innerHTML="";
}
		
function imprimirAcuse(){

	guardar=true;
	//Si hay un valor aun en la Guía advertir al usuario que ésta no será agregada, al menos claro que la agregue con el botón de "Agregar guía"

	if(($("txtGuia").value!="")&&($("txtGuia").value!="Guia"))
	{
		if(confirm("A\u00FAn hay una gu\u00EDa en los Acuses. \n\u00BFDesea guardar el Acuse (la gu\u00EDa: "+$("txtGuia").value+" no ser\u00E1 guardada)?"))
			 guardar=true;
		else guardar=false;
	
	}
	if(guardar){	
		var valores="";
		
		contador=$$('[id^=txtGuiab_]').length;
		guias=$$('[id^=txtGuiab_]');
		
		for(var idx = 0; idx < contador; idx ++)
		{		
			id=parseInt(idx+1);
			valores = valores + "&guia_"+id+"="+guias[idx].value;
		}
		$("hdnContadorv").value=contador;
			
		valores+="&cliente="+$("hdncveCliente").value+"&hdnContador="+$("hdnContadorv").value+"&fecha="+$("hdnContador").value+"&folio="+$("txtFolio").value;
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
		valores=valores +usuario; 

		$Ajax("guardarAcuse.php", {metodo: $metodo.POST, onfinish: imprimir, parametros: valores, avisoCargando:"loading"});
	}
	
}

function modificarAcuse(){			
	
	
	renglones=document.getElementById("tablaFormulario").rows.length;
		
	guardar=true;
	//Si hay un valor aun en la Guía advertir al usuario que ésta no será agregada, al menos claro que la agregue con el botón de "Agregar guía"

	if(($("txtGuia").value!="")&&($("txtGuia").value!="Guia"))
	{
		if(confirm("A\u00FAn hay una gu\u00EDa en los Acuses. \n\u00BFDesea guardar el Acuse (la gu\u00EDa: "+$("txtGuia").value+" no ser\u00E1 guardada)?"))
			 guardar=true;
		else guardar=false;
	
	}
	if(guardar){
		var valores="";
		contador=$$('[id^=txtGuiab_]').length;
		guias=$$('[id^=txtGuiab_]');
		
		for(var idx = 0; idx < contador; idx ++)
		{		
			id=parseInt(idx+1);
			valores = valores + "&guia_"+id+"="+guias[idx].value;
		}
				
		$("hdnContadorv").value=contador;
						
		valores+="&cliente="+$("hdncveCliente").value+"&hdnContador="+$("hdnContadorv").value+"&fecha="+$("hdnContador").value+"&folio="+$("txtFolio").value;
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
		valores=valores +usuario; 

		$Ajax("scripts/modificarAcuse.php", {metodo: $metodo.POST, onfinish: finBorrado, parametros: valores, avisoCargando:"loading"});

	}

}

function finBorrado(res)
{
	cancelar(0);
	alert("El folio ser\u00E1 eliminado (no hay gu\u00Edas registradas)");
}
		
function allgood(){
	var notGood = 0;
		
	if(notGood > 0){
		alert("Hay Informacion erronea que ha sido resaltada en color!");
		return false;
	} else	{ return true; }
	
}
		
function traerFolios()
{
	
	var url2 = +$("txtFolio").value + "&cliente=" +$("hdncveCliente").value;
	$Ajax("scripts/datosAcuse.php?folio="+url2, {onfinish: llenaFolios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$("hdnFolios").value=1;	
	$("bntModificar").disabled=false;
	$("btnImprimir").disabled=false;
	$("btnImprimir").onclick=imprimirb;
	$("txtFolio").disabled=true;		
}

function traerFoliosFecha(){
	var url2 = "scripts/datosAcuse.php?fechaAcuse="+$("txtFechadelAcuse").value + "&cliente=" +$("hdncveCliente").value;
	$Ajax(url2, {onfinish: llenaguiasFecha, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	//setTimeout("checarTabla()",50);
}



function checarTabla()
{
	//Significa que si hubo datos; por tanto activar botón de guardar Acuse
	if(document.getElementById("tablaFormulario").rows.length>1)
	{ $("btnGuardar").disabled=false; }
	else 
	{ $("btnGuardar").disabled=true; }
}

	
var indiceFilaFormulario=1;
function llenaFolios(campos){
	if(document.getElementById("tablaFormulario").rows.length>1){
		var ultima = document.getElementById("tablaFormulario").rows.length;
		for(var j=ultima; j>1; j--){				
			document.getElementById("tablaFormulario").deleteRow(1);				 		
		}
	}
	
	$('hdnContadorv').value=campos.length;
	
	for (var i=0; i<campos.length; i++){
		
		var campo = campos[i];
		$("visible").className=""				
		$("hdncveDireccion").value = campo.cvedireccion;
		myNewRow = document.getElementById("tablaFormulario").insertRow(-1); 
		myNewRow.id=indiceFilaFormulario;
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input name='txtFechab_"+indiceFilaFormulario+"' id='txtFechab_"+indiceFilaFormulario+"' type='text' size='8' value='"+campo.llegadaacuse +"' onFocus='blur()'/>";
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' name='txtGuiab_"+indiceFilaFormulario+"' id='txtGuiab_"+indiceFilaFormulario+"'  size='6' value='"+campo.cveguia+"' onFocus='blur()'/>";
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' name='txtDestinob_"+indiceFilaFormulario+"' id='txtDestinob_"+indiceFilaFormulario+"' value='"+ campo.branch_municip +"' onFocus='blur()'/>";
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' name='txtRecibiob_"+indiceFilaFormulario+"' id='txtRecibiob_"+indiceFilaFormulario+"' maxlength='120' size='50' value='"+ campo.recibio + "' onFocus='blur()'/>";
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='text' name='txtFacturasb_"+indiceFilaFormulario+"' id='txtFacturasb_"+indiceFilaFormulario+"' maxlength='120' size='50' value='"+ campo.facturas +"' onFocus='blur()'/>";
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='button'  value='Editar' onclick='editaGuia(" + indiceFilaFormulario + ")'>";
		myNewCell=myNewRow.insertCell(-1);
		myNewCell.innerHTML="<input type='button'  value='Eliminar' onclick='removePersona(this," + indiceFilaFormulario + ")'>";
		
		
		var momentoActual = new Date() ;
		var hora = momentoActual.getHours();
		var minuto = momentoActual.getMinutes();
		var mes = momentoActual.getMonth() +1;
		var anio=momentoActual.getYear() ;
		var dia = momentoActual.getDate() ;
		
		document.getElementById('hdnContador').value = dia+"-"+mes+"-"+anio+" "+hora+":"+minuto; 
		indiceFilaFormulario++;
	}

}

function removePersona(obj,indice){

	var oTr = obj;
	var indice= indice;
	var borrar=true;
	renglones=document.getElementById("tablaFormulario").rows.length;
	if(renglones==2)
	{
		alert("El acuse no puede quedar vac\u00EDo.");
	}
	else
	{
		//Si es una modificación entonces borrará registro en BD
		if($("txtFolio").disabled){
			if(confirm("\u00BFEst\u00E1 seguro que desea eliminar la gu\u00EDa del Acuse?")){
				var url2 ="opc=0&tabla=cacuse&condicion=folio="+$("txtFolio").value + " AND cveCliente=" +$("hdncveCliente").value+" AND cveGuia="+$("txtGuiab_"+indice ).value;
				$Ajax("scripts/eliminarDatos.php?"+url2, {onfinish: fin, avisoCargando:"loading"});
			}
			else 
				borrar=false;
			}
			if(borrar){
				while(oTr.nodeName.toLowerCase()!='tr'){
				oTr=oTr.parentNode;
			}
			var root = oTr.parentNode;
			root.removeChild(oTr);
		}
	}

}
													
function validaGuia(){
	
	contador=$$('[id^=txtGuiab_]').length;
	guias=$$('[id^=txtGuiab_]');

	var guia=$('txtGuia').value;
	
	if(guia=="")
	{ alert("Debe seleccionar una gu\u00EDa v\u00E1lida");}
	else{
		
		if(contador==0){
			checarDirecciones(guia);
		}
		else{
			var coincide=0;	
			for (var i=0; i<contador; i++){
				var guiaGuardada=guias[i].value;
				if(guia==guiaGuardada)
				{  coincide=1;	}
			}
			
			if(coincide>0){ restaurar(); alert("La gu\u00EDa no se agreg\u00F3 porque ya existe en el acuse.");}
			else{
				//Ahora checaremos que la dirección de la guía a Agregar sea igual a la(s) ya agregada(s)
				checarDirecciones(guia);
			}
		}
	}
}

function checarExistencia()
{
	var url = "scripts/existe.php?keys=11&table=cguias&field1=cveGuia&f1value=" + $("txtGuia").value;
	$Ajax(url, {tipoRespuesta: $tipo.JSON, onfinish: function (ex)
												     {
														var exx=ex[0];
														var exists = exx.existe;
														if (exists <= 0){	
															alert("La gu\u00EDa no existe.");
															return false;
														}
													 }
		  
		  });

	return false;
}



function checarDirecciones(guia)
{
	var url = "scripts/existe.php?keys=11&table=cguias&field1=cveGuia&f1value="+guia;
	$Ajax(url, {tipoRespuesta: $tipo.JSON, onfinish: function (ex)
												     {
														var exx=ex[0];
														var exists = exx.existe;
														if (exists <= 0){	
															alert("La gu\u00EDa no existe.");
															restaurar();
															return false;
														}else
														{
															//Tomará el valor de la Dirección del primer renglon
															contador=$$('[id^=txtGuiab_]').length;
															guias=$$('[id^=txtGuiab_]');
															if(guias.length>0){	
																claveGuia=guias[0].value;
																var url = "scripts/existe.php?keys=18&table=cguias&f1value=" +claveGuia+"&f2value=" +guia;
																$Ajax(url, {tipoRespuesta: $tipo.JSON,onfinish: function(datos)
																							{
																									
																								dat=datos[0];
																								if(dat.iguales==0){
																									restaurar(); 
																									alert("La gu\u00EDa no se agreg\u00F3.\n Tiene una direcci\u00F3n distinta a la(s) gu\u00EDa agregada(s.)");
																								}
																								else{
																									addGuia(guia);	
																									$("btnAgregar").disabled=true;
																								}
													  
																							}
																			});	
															}else { 
																addGuia(guia);	
																$("btnAgregar").disabled=true;
															}
															
														}
													 }
		  
		  });

	return true;
}	

function chkDir(datos)
{
	dat=datos[0];
	if(dat.iguales==0){
		restaurar(); 
		alert("La gu\u00EDa no se agreg\u00F3.\n Tiene una direcci\u00F3n distinta a la(s) gu\u00EDa agregada(s.)");
	}
	else{
		addGuia();	
		$("btnAgregar").disabled=true;
	}
}

function restaurar()
{
	$('visible').className="";
	document.getElementById('txtFecha').value='';
	document.getElementById('txtGuia').value='';
	document.getElementById('txtDestino').value='';
	document.getElementById('txtRecibio').value='';
	document.getElementById('txtFacturas').value='';

}

var indiceFilaFormulario2=1;
function addGuia(guia){

	
	
	if($("txtFolio").disabled){
		$("btnGuardar").disabled=true;
	}
	else
	{
		$("btnGuardar").disabled=false;
		$("btnGuardar").onclick=imprimirAcuse;
	}
	document.getElementById("txtFolio").readOnly=true;
	
	var fecha=document.getElementById('txtFecha').value;
	var destino=document.getElementById('txtDestino').value;
	var recibio=document.getElementById('txtRecibio').value;
	var facturas=document.getElementById('txtFacturas').value;
	
	myNewRow = document.getElementById("tablaFormulario").insertRow(-1); 
	myNewRow.id=indiceFilaFormulario2;
	myNewCell=myNewRow.insertCell(-1);
	myNewCell.innerHTML="<input name='txtFechab_"+indiceFilaFormulario2+"' id='txtFechab_"+indiceFilaFormulario2+"' type='text' size='8' value='"+fecha+"' onFocus='blur()'/>";
	myNewCell=myNewRow.insertCell(-1);
	myNewCell.innerHTML="<input type='text' name='txtGuiab_"+indiceFilaFormulario2+"' id='txtGuiab_"+indiceFilaFormulario2+"'  size='6' value='"+guia+"' onFocus='blur()'/>";
	myNewCell=myNewRow.insertCell(-1);
	myNewCell.innerHTML="<input type='text' name='txtDestinob_"+indiceFilaFormulario2+"' id='txtDestinob_"+indiceFilaFormulario2+"' value='"+ destino +"' onFocus='blur()'/>";
	myNewCell=myNewRow.insertCell(-1);
	myNewCell.innerHTML="<input type='text' name='txtRecibiob_"+indiceFilaFormulario2+"' id='txtRecibiob_"+indiceFilaFormulario2+"' maxlength='120' size='45' value='"+ recibio + "' onFocus='blur()'/>";
	myNewCell=myNewRow.insertCell(-1);
	myNewCell.innerHTML="<input type='text' name='txtFacturasb_"+indiceFilaFormulario2+"' id='txtFacturasb_"+indiceFilaFormulario2+"' maxlength='120' size='45' value='"+ facturas +"' onFocus='blur()'/>";
	myNewCell=myNewRow.insertCell(-1);
	myNewCell.innerHTML="<input type='button'  value='Editar' onclick='editaGuia(" + indiceFilaFormulario2 + ")'>";
	myNewCell=myNewRow.insertCell(-1);
	myNewCell.innerHTML="<input type='button'  value='Eliminar' onclick='removePersona(this," + indiceFilaFormulario2 + ")'>";
	
	$("hdnContadorv").value=document.getElementById("tablaFormulario").rows.length-1;

	var momentoActual = new Date() ;
	var hora = momentoActual.getHours();
	var minuto = momentoActual.getMinutes();
	var mes = momentoActual.getMonth() +1;
	var anio=momentoActual.getYear() ;
	var dia = momentoActual.getDate() ;
	
	$("hdnContador").value=dia+"-"+mes+"-"+anio+" "+hora+":"+minuto; 		

	indiceFilaFormulario2++;
	$('visible').className="";
	document.getElementById('txtFecha').value='';
	document.getElementById('txtGuia').value='';
	document.getElementById('txtDestino').value='';
	document.getElementById('txtRecibio').value='';
	document.getElementById('txtFacturas').value='';
}

function llenaguiasFecha(campos){

	//Checar que no se haya agregado la fecha anteriormente
	fechaaAgregar=$("txtFechadelAcuse").value;
	indice=fechasAgregadas.indexOf(fechaaAgregar);
	var direccionPrincipal="";
	
	contador=$$('[id^=txtGuiab_]').length;
	guias=$$('[id^=txtGuiab_]');
	if(contador!=0) 
	{
		var url = "scripts/existe.php?keys=24&f1value="+guias[0].value;
		$Ajax(url, {tipoRespuesta: $tipo.JSON,onfinish: function(datos)
			{
				direccionPrincipal=datos[0].cve;
				llenaDatosGuiasFecha(direccionPrincipal,campos);
			}
		});
	}else
	{
		direccionParcial=campos[0].cvedireccion;		
		direccionPrincipal=direccionParcial;
		llenaDatosGuiasFecha(direccionPrincipal,campos);	
	}
	
	
}

function llenaDatosGuiasFecha(dirPrincipal,campos){
	
	$("btnGuardar").onclick=imprimirAcuse;		
	fechasAgregadas.push(fechaaAgregar);		
	//Primero se checará si ya hay más renglones
	for (var i=0; i<campos.length; i++){			

			var campo = campos[i];	
		
			contador=$$('[id^=txtGuiab_]').length;

			guias=$$('[id^=txtGuiab_]');

			var guia=campo.cveguia;

			//Datos de la guía
			fechaGuia=campo.llegadaacuse; 
			munGuia=campo.branch_municip;
			recibioGuia=campo.recibio;
			facturasGuia=campo.facturas; 
			direccionParcial=campo.cvedireccion;								

			if(guia=="")
			{ alert("Debe seleccionar una gu\u00EDa v\u00E1lida");}
			else{
				if(contador==0){

					if(dirPrincipal!=direccionParcial)
					{
						alert("La gu\u00EDa "+guiaConsultada+" no se agreg\u00F3.\n Tiene una direcci\u00F3n distinta a la(s) gu\u00EDa agregada(s.)");			
					}
					else
					{
						$("visible").className="";									
						myNewRow = document.getElementById("tablaFormulario").insertRow(-1); 							myNewRow.id=indiceFilaFormulario2;
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input name='txtFechab_"+indiceFilaFormulario2+"' id='txtFechab_"+indiceFilaFormulario2+"' type='text' size='8' value='"+fechaGuia+"' onFocus='blur()'/>";
						myNewCell=myNewRow.insertCell(-1);						
						myNewCell.innerHTML="<input type='text' name='txtGuiab_"+indiceFilaFormulario2+"' id='txtGuiab_"+indiceFilaFormulario2+"'  size='6' value='"+guia+"' onFocus='blur()'/>";
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input type='text' name='txtDestinob_"+indiceFilaFormulario2+"' id='txtDestinob_"+indiceFilaFormulario2+"' value='"+ munGuia+"' onFocus='blur()'/>";									
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input type='text' name='txtRecibiob_"+indiceFilaFormulario2+"' id='txtRecibiob_"+indiceFilaFormulario2+"' maxlength='120' size='50' value='"+ recibioGuia + "' onFocus='blur()'/>";
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input type='text' name='txtFacturasb_"+indiceFilaFormulario2+"' id='txtFacturasb_"+indiceFilaFormulario2+"' maxlength='120' size='50' value='"+ facturasGuia +"' onFocus='blur()'/>";
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input type='button'  value='Editar' onclick='editaGuia(" + indiceFilaFormulario2 + ")'>";
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input type='button'  value='Eliminar' onclick='removePersona(this," + indiceFilaFormulario2 + ")'>";
						$("hdnContadorv").value=document.getElementById("tablaFormulario").rows.length-1;
													
															
						var momentoActual = new Date() ;
															
						var hora = momentoActual.getHours();
															
						var minuto = momentoActual.getMinutes();
															
						var mes = momentoActual.getMonth() +1;
															
						var anio=momentoActual.getYear() ;
															
						var dia = momentoActual.getDate() ; 
						$("hdnContador").value=dia+"-"+mes+"-"+anio+" "+hora+":"+minuto; 		
						indiceFilaFormulario2++;
													
						if($("btnGuardar").disabled && !($("txtFolio").disabled))		
						{
							$("btnGuardar").disabled=false;
						}					
					}

				}
				else{
					var coincide=0;	
					for (var j=0; j<contador; j++){
						var guiaGuardada=guias[j].value;
						if(guia==guiaGuardada)
						{  coincide=1;	}
					}

					if(coincide>0){ restaurar(); alert("La gu\u00EDa "+guia+" no se agreg\u00F3 porque ya existe en el acuse");}
					else if(dirPrincipal!=direccionParcial)
					{
						alert("La gu\u00EDa "+guia+" no se agreg\u00F3.\n Tiene una direcci\u00F3n distinta a la(s) gu\u00EDa agregada(s.)");			
					}
					else{
						$("visible").className="";									
						myNewRow = document.getElementById("tablaFormulario").insertRow(-1); 							myNewRow.id=indiceFilaFormulario2;
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input name='txtFechab_"+indiceFilaFormulario2+"' id='txtFechab_"+indiceFilaFormulario2+"' type='text' size='8' value='"+fechaGuia+"' onFocus='blur()'/>";
						myNewCell=myNewRow.insertCell(-1);						
						myNewCell.innerHTML="<input type='text' name='txtGuiab_"+indiceFilaFormulario2+"' id='txtGuiab_"+indiceFilaFormulario2+"'  size='6' value='"+guia+"' onFocus='blur()'/>";
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input type='text' name='txtDestinob_"+indiceFilaFormulario2+"' id='txtDestinob_"+indiceFilaFormulario2+"' value='"+ munGuia+"' onFocus='blur()'/>";									
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input type='text' name='txtRecibiob_"+indiceFilaFormulario2+"' id='txtRecibiob_"+indiceFilaFormulario2+"' maxlength='120' size='50' value='"+ recibioGuia + "' onFocus='blur()'/>";
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input type='text' name='txtFacturasb_"+indiceFilaFormulario2+"' id='txtFacturasb_"+indiceFilaFormulario2+"' maxlength='120' size='50' value='"+ facturasGuia +"' onFocus='blur()'/>";
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input type='button'  value='Editar' onclick='editaGuia(" + indiceFilaFormulario2 + ")'>";
						myNewCell=myNewRow.insertCell(-1);
						myNewCell.innerHTML="<input type='button'  value='Eliminar' onclick='removePersona(this," + indiceFilaFormulario2 + ")'>";
						$("hdnContadorv").value=document.getElementById("tablaFormulario").rows.length-1;


						var momentoActual = new Date() ;

						var hora = momentoActual.getHours();

						var minuto = momentoActual.getMinutes();

						var mes = momentoActual.getMonth() +1;

						var anio=momentoActual.getYear() ;

						var dia = momentoActual.getDate() ; 
						$("hdnContador").value=dia+"-"+mes+"-"+anio+" "+hora+":"+minuto; 		
						indiceFilaFormulario2++;

						if($("btnGuardar").disabled && !($("txtFolio").disabled))		
						{
							$("btnGuardar").disabled=false;
						} 
					}
				}
			}
			pausecomp(100);
	}
}



function pausecomp(millis) 
{
	var date = new Date();
	var curDate = null;
	
	do { curDate = new Date(); } 
	while(curDate-date < millis);
} 

function addGuia2(guia)
{	
	var url2 = "scripts/datosAcuse.php?cveguia="+guia;
	$Ajax(url2,{tipoRespuesta: $tipo.JSON, avisoCargando:"loading",onfinish: function(campos)
																  {

																	campo=campos[0];																		
																	
																	$("visible").className="";
																	myNewRow = document.getElementById("tablaFormulario").insertRow(-1); 
																	myNewRow.id=indiceFilaFormulario2;
																	myNewCell=myNewRow.insertCell(-1);
																	myNewCell.innerHTML="<input name='txtFechab_"+indiceFilaFormulario2+"' id='txtFechab_"+indiceFilaFormulario2+"' type='text' size='8' value='"+campo.llegadaacuse +"' onFocus='blur()'/>";
																	myNewCell=myNewRow.insertCell(-1);
																	myNewCell.innerHTML="<input type='text' name='txtGuiab_"+indiceFilaFormulario2+"' id='txtGuiab_"+indiceFilaFormulario2+"'  size='6' value='"+campo.cveguia+"' onFocus='blur()'/>";
																	myNewCell=myNewRow.insertCell(-1);
																	myNewCell.innerHTML="<input type='text' name='txtDestinob_"+indiceFilaFormulario2+"' id='txtDestinob_"+indiceFilaFormulario2+"' value='"+ campo.branch_municip +"' onFocus='blur()'/>";
																	myNewCell=myNewRow.insertCell(-1);
																	myNewCell.innerHTML="<input type='text' name='txtRecibiob_"+indiceFilaFormulario2+"' id='txtRecibiob_"+indiceFilaFormulario2+"' maxlength='120' size='50' value='"+ campo.recibio + "' onFocus='blur()'/>";
																	myNewCell=myNewRow.insertCell(-1);
																	myNewCell.innerHTML="<input type='text' name='txtFacturasb_"+indiceFilaFormulario2+"' id='txtFacturasb_"+indiceFilaFormulario2+"' maxlength='120' size='50' value='"+ campo.facturas +"' onFocus='blur()'/>";
																	myNewCell=myNewRow.insertCell(-1);
																	myNewCell.innerHTML="<input type='button'  value='Editar' onclick='editaGuia(" + indiceFilaFormulario2 + ")'>";
																	myNewCell=myNewRow.insertCell(-1);
																	myNewCell.innerHTML="<input type='button'  value='Eliminar' onclick='removePersona(this," + indiceFilaFormulario2 + ")'>";
																	
																	
																	$("hdnContadorv").value=document.getElementById("tablaFormulario").rows.length-1;
															
																	var momentoActual = new Date() ;
																	var hora = momentoActual.getHours();
																	var minuto = momentoActual.getMinutes();
																	var mes = momentoActual.getMonth() +1;
																	var anio=momentoActual.getYear() ;
																	var dia = momentoActual.getDate() ;
																	
																	$("hdnContador").value=dia+"-"+mes+"-"+anio+" "+hora+":"+minuto; 		
																
																	indiceFilaFormulario2++;
															
																	if($("btnGuardar").disabled && !($("txtFolio").disabled))
																	{
																		$("btnGuardar").disabled=false;
																	}
																	
																	
																  }
			
				});
		
}


function removePerson(obj){ 
	var oTr = obj;
	while(oTr.nodeName.toLowerCase()!='tr'){
		oTr=oTr.parentNode;
	}
	var root = oTr.parentNode;
	root.removeChild(oTr);
}


function editaGuia(indice)
{
	var indice= indice;
	 	var url2 ="cveGuia="+$("txtGuiab_"+indice ).value;	  
		location = "guia.php?"+url2;
 }

function imprimir(res){				

	var respuesta=res.split("-");
	if(respuesta[1]==0)
		alert(respuesta[0]);	
	else
	{
	
		if (confirm(respuesta[0] + "\u00BFDesea imprimirla ahora?")){		
			var win = new Window({className: "mac_os_x", title: "Reporte del soporte de facturas", top:70, left:100, width:1000, height:500, url: "scripts/reporteAcuses.php?razon=" + $("txtRazonS").value + "&cveCliente=" + $("hdncveCliente").value + "&folio=" + $("txtFolio").value , showEffectOptions: {duration:1.5}});
			win.show(); 		
		}
	}
	cancelar(0);	
}

function cancelar(opcion)
{

	$('divInfoGuiaAc').innerHTML=" ";
	$('autoGuia').innerHTML="";
	$("autoGuia").className=""	
	
	if(document.getElementById("tablaFormulario").rows.length>1){		
		var ultima = document.getElementById("tablaFormulario").rows.length;
		for(var j=ultima; j>1; j--){	
			document.getElementById("tablaFormulario").deleteRow(1);			
		}	
	}
	
	$("btnAgregar").disabled   = true;
	$("btnGuardar").disabled   = true;
	$("btnImprimir").disabled  = true;
	$("bntModificar").disabled = true;
	$("btnCancelar").disabled  = true;	
	$("txtFolio").disabled     = false;		
	$("txtRazonS").disabled    = false;
	$("txtFechadelAcuse").disabled     = true;			
	$("form2").reset();
	$('visible').className     = "oculto";
	$('hdncveCliente').value=0;
	indiceFilaFormulario=1;
	indiceFilaFormulario2=1;
	document.getElementById("txtFolio").readOnly=false;
	document.getElementById("tcalico_0").style.visibility="hidden";	
	fechaGeneral="";
	//Independientemente de la opción, borrar iniciales para aregar fechas
	
	fechaGeneral="";                //Fecha elegida
	fechasAgregadas = new Array();	//Fechas que han sido elegidas
	guiasAgregadas = new Array();	//Guias
	cveDireccionGeneral="";			//Clave de dirección
	
}

function imprimirb(){				
	
	 var win = new Window({className: "mac_os_x", title: "Reporte del soporte de facturas", top:70, left:100, width:1000, height:500, url: "scripts/reporteAcuses.php?razon=" + $("txtRazonS").value + "&cveCliente=" + $("hdncveCliente").value +"&hdnContador="+$("hdnContadorv").value+ "&folio=" + $("txtFolio").value , showEffectOptions: {duration:.5}});
	 win.show(); 		
		
	
}
