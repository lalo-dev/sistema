window.onload = inicia;

function inicia() 
{
	//Se asignará una clase y dos eventos a todos los input text del formulario
	inputs=$$("input[type=text]");

	for(i=0;i<inputs.length;i++)
	{
		id=inputs[i].id;
		inputs[i].className="busqueda";
		numero=i%2;
		if(numero==0)
			inputs[i].onblur=checar_valor;
		else
			inputs[i].onblur=checar_valor2;
		inputs[i].onfocus=quitarTexto;
	}
	
	//Cargar Destinos 
	$Ajax("scripts/Sucursales.php", {onfinish: cargaSucursal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	
	selects=$$("select");
	for(i=0;i<selects.length;i++)
	{
		selects[i].onchange=checar_valorSlt;
	}
	
	$("autoClienteC").className = "autocomplete";	
	var url="scripts/catalogoClientes.php?operacion=4&tabla=ccliente";
	new Ajax.Autocompleter("txtCliente", "autoClienteC", url , {paramName: "caracteres",afterUpdateElement:datosClientes});
	document.getElementById("autoClienteC").style.width="255px";
	document.getElementById("txtCliente").style.width="255px";
	document.getElementById("txtCliente").focus();
}

function limpiar()
{
	$("form2").reset();
	cajas=$$("#form2 input[type=text],#form2 select");

	for(i=0;i<cajas.length;i++)
	{
		id=cajas[i].id;
		cajas[i].className="busqueda";
	}
}

function datosClientes() 
{	
	var url2 = "opc=0&codigo="+$("txtCliente").value;
	$Ajax("scripts/datosClientes.php?operacion=4&cveCliente="+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
	
function cveCliente(campos) 
{
	var campo = campos[0];
	$("txtCliente").value=campo.cveCliente;
}

function checar_valor()
{
	if(this.value=="")
	{
		if(this.id=="txtCliente")
			var texto="Cliente";
		else
			var texto="Desde";
		this.className="busqueda";
		this.value=texto;
	}
}

function checar_valor2()
{
	if(this.value=="")
	{
		var texto="Hasta";
		this.className="busqueda";
		this.value=texto;
	}
}

function quitarTexto()
{
	if((this.value=="Desde")||(this.value=="Hasta")||(this.value=="Cliente"))
	{
		this.value="";
		this.className="busqueda1";
	}
}

function cargaSucursal(airls){
 
	//Borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("sltDestino").options.length = 1;
	
	//Empieza la carga de la lista
	$("sltDestino").options[$("sltDestino").options.length] = opcion;
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);		
		try 
		{
			$("sltDestino").options[$("sltDestino").options.length]=opcion;
		}
		catch (e){alert("Error interno");}
	}	
	
	for(i=1;i<$("sltDestino").options.length;i++){
		$("sltDestino").options[i].style.color="#000000";
		$("sltDestino").options[i].style.color="#000000";
	}
}

function checar_valorSlt()
{
	if((this.value!="")&&(this.value!=0))
	{
		this.className="busqueda1";
	}
	else{
		this.className="busqueda";}
}

function chkForm()
{	
	//Checar que las fechas esten correctas
	var expresion = /^\s*(\d{2,2})\/(\d{2,2})\/(\d{4,4})\s*$/;	

		//Checamos los datos de la primera fecha
	valores=$('txtPeriodoD').value.split("/");
	var d_numdays = new Date(valores[2],valores[1],0);
	
	if(($("txtPeriodoD").value!="Desde") && ($("txtPeriodoD").value!="") && (!($("txtPeriodoD").value.match(expresion))))
	{$("txtPeriodoD").addClassName("invalid"); alert("El formato de la fecha de recepci\u00F3n (desde) es incorrecto (dd/mm/yyyy)."); return false;} 
	else if($("txtPeriodoD").value=="Desde" || $("txtPeriodoD").value=="")
	{$("txtPeriodoD").addClassName("invalid"); alert("Debe ingresar la fecha inicial del periodo."); return false;} 
	else if (valores[1] < 1 || valores[1] > 12)
	{ alert ("Valor de mes no v\u00E1lido: '" + valores[1] + "'.\nEl rango permitido es de 01 a 12.");
	  return false;
	}else if (valores[0] > d_numdays.getDate())
	{ alert("D\u00EDa de mes no v\u00E1lido: '" + valores[0] + "'.\nEl rango permitido para el mes seleccionado es de 01 a " + d_numdays.getDate() + ".");
	  return false;
	}else $("txtPeriodoD").removeClassName("invalid"); 	
	
	mes1=valores[1]; 
	anyo1=valores[2]; 
	
		//Checamos los datos de la segunda fecha
	valores=$('txtPeriodoH').value.split("/");
	var d_numdays = new Date(valores[2],valores[1],0);
	
	if(($("txtPeriodoH").value!="Hasta") && ($("txtPeriodoH").value!="") && (!($("txtPeriodoH").value.match(expresion))))
	{$("txtPeriodoH").addClassName("invalid"); alert("El formato de la fecha de recepci\u00F3n (hasta) es incorrecto (dd/mm/yyyy)."); return false;} 
	else if($("txtPeriodoH").value=="Hasta" || $("txtPeriodoH").value=="")
	{$("txtPeriodoH").addClassName("invalid"); alert("Debe ingresar la fecha final del periodo."); return false;} 
	if (valores[1] < 1 || valores[1] > 12)
	{ alert ("Valor de mes no v\u00E1lido: '" + valores[1] + "'.\nEl rango permitido es de 01 a 12.");
	  return false;
	}else if (valores[0] > d_numdays.getDate())
	{ alert("D\u00EDa de mes no v\u00E1lido: '" + valores[0] + "'.\nEl rango permitido para el mes seleccionado es de 01 a " + d_numdays.getDate() + ".");
	  return false;
	}	
	else $("txtPeriodoH").removeClassName("invalid"); 	

	mes2=valores[1]; 
	anyo2=valores[2]; 
	
		//Checar que la fecha de desde no sea superior a la fecha de hasta 
	valores=$('txtPeriodoD').value.split("/");
	desde = new Date(valores[2],valores[1],valores[0]).getTime();
	valores=$('txtPeriodoH').value.split("/");
	hasta = new Date(valores[2],valores[1],valores[0]).getTime();
	if(desde>hasta)
	{
		alert("La fecha incial del periodo es mayor a la de fin.");
		return false;
	}
	else if(mes1!=mes2 || anyo1!=anyo2)
	{
		alert("Solo puede elegir periodos del mismo mes en el mismo año.");
		return false;
	}
		
	return true;
}

