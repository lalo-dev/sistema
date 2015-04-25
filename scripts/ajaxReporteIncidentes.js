window.onload = inicia;

function inicia() 
{
	//Inicializando autocomplete
	$("autoGuia").className = "autocomplete";
	new Ajax.Autocompleter("txtGuia","autoGuia","scripts/catalogoGuias.php?operacion=5",{paramName: "caracteres",afterUpdateElement:existe});
	
	campo=document.getElementById("txtGuia");
	campo.focus();

	fecha=new Date();
	dia=fecha.getDate();
	mes=fecha.getMonth()+1;
	anyo=fecha.getFullYear();
	if(dia<10) dia="0"+dia;
	if(mes<10) mes="0"+mes;
	fecha_hoy=dia+"/"+mes+"/"+anyo;
	$("txtFechaReporte").value=fecha_hoy;
	
	$('txtReporte').disabled=true;
	
	//Se calculará por default el número siguiente de Reporte
	$Ajax("scripts/catalogoTotales.php?operacion=10", {onfinish: cargarClave, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	//Vamos a checar si la pagina ya trae una guía por default, para cargar sus datos
	if($("txtGuia").value!='')
		existe();

	controlChks(0);
	controles(2);
	
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

function cargarClave(valores)
{
	valor=valores[0];
	$("txtReporte").value=valor.clave;
}

function existe() 
{	
	if ($("txtGuia").value!="" )
	{
		//Separamos la clave de la guía del Nombre del Remitente
		valor_guia=$("txtGuia").value.split(" - ",1);
		$("txtGuia").value=valor_guia;
		var url = "scripts/existe.php?keys=1&table=cguias&field1=cveGuia&f1value="+$("txtGuia").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	}
}

function next_existe(ex)
{
	var exx=ex[0];
	var exists = exx.existe;
	
	if (exists > 0) //Si el valor es mayor que cero, entonces el registro existe
	{
		//Sólo permitirá visualizar los reporte de esa guía	
		var url="scripts/catalogoGeneral.php?operacion=13&guia="+exx.cve;
		$("autoReporte").className = "autocomplete";
		new Ajax.Autocompleter("txtReporte","autoReporte", url, {paramName:"caracteres",afterUpdateElement:datosReporte});
		
		var url="scripts/datosGuias.php?cveguia="+exx.cve;
		$Ajax(url,{onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
		//Controles	
		pausecomp(1100);
		$('txtReporte').disabled=false;
		
		//Mostraremos la lista de los reportes asociados a esa guía
		var url="scripts/datosReportesInc.php?guia="+exx.cve;
		$Ajax(url,{onfinish: addReportes, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		controles(0);
		
	}
	else		  //Si la funcion devolvio cero, no existe el registro
	{	
		alert('La gu\u00EDa '+$("txtGuia").value+' no existe.');
		$("txtGuia").value='';
		$('txtReporte').disabled=true;
	}
}

function datosReporte()
{
	var noReporte=$("txtReporte").value;
	var url="scripts/datosIncidentes.php?opc=1&valor="+noReporte;
	$Ajax(url,{onfinish: llenaReporte, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function llenaDatos(campos)
{
	$('txtNGuia').value=$('txtGuia').value;
	//Tomamos el primer objeto del json, ya que siempre devolvera un unico registro
	var campo = campos[0];
	
	$('txtDestino').value=campo.sucursalDestino;

	$('txtRemitente').value=campo.nombreRemitente;
	
	$('txtLineaA').value=campo.lineaAerea;
	$('txtGuiaA').value=campo.guiaArea;
	$('txtVueloA').value=campo.noVuelo;

	$('txtConsignado').value=campo.nombreD;
	$('txtPzasEnviadas').value=campo.piezas;
	$('txtKgEnviados').value=campo.kg;
	
	//Consultar el destino
	var url= "scripts/catalogoCP.php?operacion=7&municipio=" +campo.municipioDestinatario+"&estado="+campo.estadoDestinatario;
	$Ajax(url,{onfinish: function (datos)
					     {
						 	$('txtMunicipio').value=datos[0].nombreMun;
						 },tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

}

function llenaReporte(datos)
{
	quitarinvalidos(0);
	dato=datos[0];
	
	$("txtGuia").value=dato.cveG;
	$('txtReporte').disabled=true;
		
	if(dato.tipoInc==0)
		$("rdBExtemporanea").checked=true;
	else
		$("rdBFaltantes").checked=true;

	controlChks(dato.tipoInc);
	
	fecha=cambioFecha(dato.fecReporte,0);
	$("txtFechaReporte").value=fecha;
	
	$("txtNGuia").value=dato.cveG;
	$("txtDestino").value=dato.destino;
	$("txtMunicipio").value=dato.municipio;
	$("txtRemitente").value=dato.remitente;
	$("txtConsignado").value=dato.consignado;
	$("txtLineaA").value=dato.lAerea;
	$("txtGuiaA").value=dato.gAerea;
	$("txtVueloA").value=dato.noVuelo;
	$("txtPzasEnviadas").value=dato.pEnviadas;
	$("txtPzasEntregadas").value=dato.pEntregadas;
	$("txtKgEnviados").value=dato.kEnviados;
	$("txtKgEntregados").value=dato.kEntregados;	

	valoresChk=dato.incidentes.split(",");
	$$("input[name='chkFalla']").each(
									  function (nombre,index)
									  	{
											if(valoresChk[index]==1)
												$(nombre).checked=true;
										}
									 );
	//Ahora sustituiremos los <br> por un salto de línea		
	var regX = /\<br\>/g;
	var replaceString = '\n';

	cadena=dato.dProblema;	
	cadenaFin=cadena.replace(regX,replaceString);
	$("txtaDescripcion").value=cadenaFin;
	 
	$("txtElaboro").value=dato.elabora;
	$("txtCorroboro").value=dato.corrobora;
	
	if(dato.dProblemaSol=='')
		$("txtaDesProblemaSol").value=cadenaFin;	
	else
	{
		cadena=dato.dProblemaSol;	
		cadenaFin=cadena.replace(regX,replaceString);	
		$("txtaDesProblemaSol").value=cadenaFin;	
	}
	
	if(dato.fecDeteccion=="0000-00-00")
		fecha="";
	else
		fecha=cambioFecha(dato.fecDeteccion,0)
	$("txtFechaDet").value=fecha;
	$("txtPersonaDet").value=dato.perDetecta;
	$("txtTecnica").value=dato.tecSol;
	
	if(dato.fecDeteccion=="0000-00-00")
		fecha="";
	else
		fecha=cambioFecha(dato.fecSolucion,0);
		
	$("txtFechaSol").value=fecha;
	
	$("txtPersonaSol").value=dato.perSol;	
	cadena=dato.desSol;
	cadenaFin=cadena.replace(regX,replaceString);	
	$("txtaDesSol").value=cadenaFin;
	
	controles(1);
}

function limpiar()
{
	quitarinvalidos(1);		
	campo=document.getElementById("txtGuia");
	campo.focus();
	//Se calculará por default el número siguiente de Reporte
	$Ajax("scripts/catalogoTotales.php?operacion=10", {onfinish: cargarClave, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	//Limpiar la tabla
	if(document.getElementById("tblReporte").rows.length>1){
		var ultima = document.getElementById("tblReporte").rows.length;
		for(var j=ultima; j>1; j--){				
			document.getElementById("tblReporte").deleteRow(1);				 		
		}
	}
	controles(2);
	//Controles de check
	controlChks(0);
	$("rdBExtemporanea").checked=true;	
}

function quitarinvalidos(opc)
{
	$$("#form2 input[type=text],#form2 select,#form2 textarea,#form2 input[type=password]").each(
																								 	function (name,index)
																									{
																										$(name).removeClassName("invalid");
																										if(opc==1)
																											$(name).value='';
																									}
																								);
}

function controles(opcion)
{
	if(opcion==0)
	{
		$("btnImprimir").disabled=true;
		$("btnGuardar").disabled=false;
		$("btnModificar").disabled=true;
		$("btnCancelar").disabled=false;
	}
	else if(opcion==1)
	{
		$("btnImprimir").disabled=false;
		$("btnGuardar").disabled=true;
		$("btnModificar").disabled=false;
		$("btnCancelar").disabled=false;
	}
	else if(opcion==2)
	{
		$("btnImprimir").disabled=true;
		$("btnGuardar").disabled=true;
		$("btnModificar").disabled=true;
		$("btnCancelar").disabled=true;
	}
}

function controlChks(valor)
{	
	if(valor==0)        //Entrega extemporánea
	{
		var condicion1=false;
		var condicion2=true;		
	}
	else{					   //Daños y Faltantes
		var condicion1=true;
		var condicion2=false;		
	}
	$$("input[name='chkFalla']").each(
									  	function (nombre,index)
								  		{
											if(index==0)
												$(nombre).disabled=condicion1;
											else
												$(nombre).disabled=condicion2;
											$(nombre).checked=false;
										}
									 );	
	if(valor==0)	
		$("chkExtemporaneo").checked=true;
}

function cambioFecha(fecha,opc)
{
	fechaFinal='';
	
	if(fecha!='')
	{
		var signo   =(opc==0)?'-':'/';
		var signoFin=(opc==0)?'/':'-';
		
		datos=fecha.split(signo);
		fechaFinal=datos[2]+signoFin+datos[1]+signoFin+datos[0];
	}
	
	return fechaFinal;
}

function pausecomp(millis) 
{
	var date = new Date();
	var curDate = null;
	
	do { curDate = new Date(); } 
	while(curDate-date < millis);
}

function insertar()
{	
	if(allgood())
	{
		valores=tomar_valores();
		$Ajax("scripts/administraReporteInc.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
	}
}
		
function actualizar()
{
	if(allgood())
	{
		valores=tomar_valores();
		valores+="&reporte="+$("txtReporte").value;
		$Ajax("scripts/administraReporteInc.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});		
	}
}

function imprimir()
{
	var url="scripts/reporteIncidentes.php?reporte="+$('txtReporte').value;	
	var win = new Window({className: "mac_os_x", title: "Reporte de Incidentes", top:420, left:100, width:1200, height:500, url: url, showEffectOptions: {duration:.5}});
	win.show();
}

function tomar_valores()
{
	//Seleccionasmos el tipo de Incidente
	seleccionado=$$("input[name='rBbTipoIncidente']:checked");
	tipoInc=seleccionado[0].value;
	fechaReporte=cambioFecha($("txtFechaReporte").value,1);
	
	valores="guia="+$("txtGuia").value+"&tipoInc="+tipoInc+"&fechaRep="+fechaReporte+"&destino="+$("txtDestino").value+"&municipio="+$("txtMunicipio").value+
			"&remitente="+$("txtRemitente").value+"&consignado="+$("txtConsignado").value+"&lineaA="+$("txtLineaA").value+"&guiaA="+$("txtGuiaA").value+
			"&vueloA="+$("txtVueloA").value+"&piezasEnv="+$("txtPzasEnviadas").value+"&piezasEnt="+$("txtPzasEntregadas").value+"&kgEnv="+$("txtKgEnviados").value+
			"&kgEnt="+$("txtKgEntregados").value;
	
	fallas="";
	$$("input[name='chkFalla']").each(
									  function (nombre,index)
									  	{
											if($(nombre).checked==true)
												fallas+="1";
											else
												fallas+="0";
											if(index!=7)
												fallas+=",";
										}
									 );
	
	fechaDet=cambioFecha($("txtFechaDet").value,1);
	fechaSol=cambioFecha($("txtFechaSol").value,1);
	
	valores+="&incidentes="+fallas+"&elabora="+$("txtElaboro").value+"&corrobora="+$("txtCorroboro").value+
			 "&desProb="+$("txtaDescripcion").value+"&desProbSol="+$("txtaDesProblemaSol").value+"&fechaDet="+fechaDet+
		     "&perDet="+$("txtPersonaDet").value+"&tecnica="+$("txtTecnica").value+"&fechaSol="+fechaSol+"&perSol="+$("txtPersonaSol").value+
			 "&descSol="+$("txtaDesSol").value;

	var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
	valores=valores +usuario;
	
	return valores;
}

function fin(res)
{
	var valores=res.split("-");
	//Ocurrió un error
	if(valores[1]==1)
		alert(valores[0]);
	else
	{
		alert(valores[0]+"\u00BFDesea imprimirlo ahora?");
		imprimir();
	}	
	limpiar();
}
		
function allgood()
{
	var notGood = 0;	
	
	//Expresion para verificar que las fechas sean correctas
	var expresion = /^\s*(\d{2,2})\/(\d{2,2})\/(\d{4,4})\s*$/;	
	
	//Fecha de reporte
	if($("txtFechaReporte").value=='')
	{
		$("txtFechaReporte").addClassName("invalid"); 
		notGood ++; 
	}
	else
	{
		$("txtFechaReporte").removeClassName("invalid");
		
		if(!($("txtFechaReporte").value.match(expresion)))
		{$("txtFechaReporte").addClassName("invalid"); alert("El formato de la fecha del Reporte es incorrecto (dd/mm/yyyy)."); return false;} 
		else
		{
			//Obtener datos de la fecha
			valores=$('txtFechaReporte').value.split("/");
			
			$("txtFechaReporte").addClassName("invalid");
			if (valores[1] < 1 || valores[1] > 12){
				alert ("Valor de mes no v\u00E1lido: '" + valores[1] + "'.\nEl rango permitido es de 01 a 12.");
				return false;
			}
			else{
				var d_numdays = new Date(valores[2], valores[1], 0);
				if (valores[0] > d_numdays.getDate()){
					alert("D\u00EDa de mes no v\u00E1lido: '" + valores[0] + "'.\nEl rango permitido para el mes seleccionado es de 01 a " + d_numdays.getDate() + ".");
					return false;
				}
				else
					$("txtFechaReporte").removeClassName("invalid");
			}
		}
	}

	
	//Revisaremos que se haya seleccionado por lo menos una falla
	var chks=8;
	$$("input[name='chkFalla']").each(
									  	function (nombre,index)
										{
											if($(nombre).checked==true)
												chks--;
										}
									  );
								  
	if(chks==8)		//Significa que no seleccionaron ningún check
	{
		$("error").innerHTML="<label style='font-size:11px;font-weight:bold;font-family:Verdana, Geneva, sans-serif;color:#F90;'>Seleccione alguna de las opciones</label>";					
		notGood++;
							
	}else
		$("error").innerHTML="";
		
	//Que se indiquen las piezas entregadas al igual que kilogramos y la descripción del problema	
	if(($("txtPzasEntregadas").value=='') || isNaN($("txtPzasEntregadas").value) || ($("txtPzasEntregadas").value<0))
	{$("txtPzasEntregadas").addClassName("invalid"); notGood ++;} else{$("txtPzasEntregadas").removeClassName("invalid");}
	if(($("txtKgEntregados").value=='') || isNaN($("txtKgEntregados").value) || ($("txtKgEntregados").value<0))
	{$("txtKgEntregados").addClassName("invalid"); notGood ++;} else{$("txtKgEntregados").removeClassName("invalid");}
	if($("txtaDescripcion").value=='' || $("txtaDescripcion").value.length<5)
	{$("txtaDescripcion").addClassName("invalid"); notGood ++;} else{$("txtaDescripcion").removeClassName("invalid");}
	
	//En caso de haber ingresado más fechas; las rectifica
	$("txtFechaDet").removeClassName("invalid");
	if($("txtFechaDet").value!='')
	{
		$("txtFechaDet").removeClassName("invalid");
		
		if(!($("txtFechaDet").value.match(expresion)))
		{$("txtFechaDet").addClassName("invalid"); alert("El formato de la fecha de detecci\u00F3n es incorrecto (dd/mm/yyyy)."); return false;} 
		else
		{
			//Obtener datos de la fecha
			valores=$('txtFechaDet').value.split("/");
			
			$("txtFechaDet").addClassName("invalid");
			if (valores[1] < 1 || valores[1] > 12){
				alert ("Valor de mes no v\u00E1lido: '" + valores[1] + "'.\nEl rango permitido es de 01 a 12.");
				return false;
			}
			else{
				var d_numdays = new Date(valores[2], valores[1], 0);
				if (valores[0] > d_numdays.getDate()){
					alert("D\u00EDa de mes no v\u00E1lido: '" + valores[0] + "'.\nEl rango permitido para el mes seleccionado es de 01 a " + d_numdays.getDate() + ".");
					return false;
				}
				else
					$("txtFechaDet").removeClassName("invalid");
			}
		}
	}
	
	$("txtFechaSol").removeClassName("invalid");
	if($("txtFechaSol").value!='')
	{		
		if(!($("txtFechaSol").value.match(expresion)))
		{$("txtFechaSol").addClassName("invalid"); alert("El formato de la fecha del soluci\u00F3n es incorrecto (dd/mm/yyyy)."); return false;} 
		else
		{
			//Obtener datos de la fecha
			valores=$('txtFechaSol').value.split("/");
			
			$("txtFechaSol").addClassName("invalid");
			if (valores[1] < 1 || valores[1] > 12){
				alert ("Valor de mes no v\u00E1lido: '" + valores[1] + "'.\nEl rango permitido es de 01 a 12.");
				return false;
			}
			else{
				var d_numdays = new Date(valores[2], valores[1], 0);
				if (valores[0] > d_numdays.getDate()){
					alert("D\u00EDa de mes no v\u00E1lido: '" + valores[0] + "'.\nEl rango permitido para el mes seleccionado es de 01 a " + d_numdays.getDate() + ".");
					return false;
				}
				else
					$("txtFechaSol").removeClassName("invalid");
			}
		}
	}
	
	//Envíar mensaje de errores
	if(notGood > 0){
		alert("\u00A1Hay Informaci\u00F3n err\u00F3nea que ha sido resaltada en color!");
		return false;
	} else {return true;}
		
}

var indiceFilaFormulario=1;
function addReportes(datos)
{	
	total=datos[0].total;
	if(document.getElementById("tblReporte").rows.length>1){
		var ultima = document.getElementById("tblReporte").rows.length;
		for(var j=ultima; j>1; j--){				
			document.getElementById("tblReporte").deleteRow(1);				 		
		}
	}
	
 	if(total==0) 	$("tblReporte").className="oculto";	
  	else if(total!=0)
	{
		//Mostramos los reportes que se han generado para las guias
		if(document.getElementById("tblReporte").rows.length>1){
			var ultima = document.getElementById("tblReporte").rows.length;
			for(var j=ultima; j>1; j--){
				document.getElementById("tblReporte").deleteRow(1);				 		
			}
		}
		
		for (var i=0; i<datos.length; i++)
		{
			 var dato = datos[i];
			 myNewRow = document.getElementById("tblReporte").insertRow(-1);
			 myNewRow.id=indiceFilaFormulario;
			 
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=dato.cveRep;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=dato.fechaRep;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=dato.incidente;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=dato.estacion;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=dato.municipio;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=dato.lAerea;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=dato.gAerea;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=dato.noVuelo;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML=dato.descripcion;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<input type='button' value='Editar' onclick='editarDetalle("+dato.cveRep+")'>";
		}
	}	
}

function editarDetalle(noReporte)
{
	var url="scripts/datosIncidentes.php?opc=1&valor="+noReporte;
	$Ajax(url,{onfinish: llenaReporte, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$("txtReporte").value=noReporte;
}

