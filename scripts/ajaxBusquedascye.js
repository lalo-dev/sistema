window.onload = inicia;

function inicia() {

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
	
	//Cargar Destinos y Status
	$Ajax("scripts/Sucursales.php", {onfinish: cargaSucursal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	cargaEstados();
	
	selects=$$("select");
	for(i=0;i<selects.length;i++)
	{
		selects[i].onchange=checar_valorSlt;
	}
	
	$("autoClienteC").className = "autocomplete";
	document.getElementById("autoClienteC2").style.width="155px";
	$("autoClienteC2").className = "autocomplete";
	document.getElementById("autoClienteC2").style.width="155px";
	new Ajax.Autocompleter("txtClienteD", "autoClienteC", "scripts/catalogoClientes.php?operacion=4", {paramName: "caracteres",afterUpdateElement:datosClientes});
	new Ajax.Autocompleter("txtClienteH", "autoClienteC2", "scripts/catalogoClientes.php?operacion=4", {paramName: "caracteres",afterUpdateElement:datosClientes2});

	$("autoDestinatarioD").className = "autocomplete";
	document.getElementById("autoDestinatarioD").style.width="155px";
	$("autoDestinatarioH").className = "autocomplete";
	document.getElementById("autoDestinatarioH").style.width="155px";

	new Ajax.Autocompleter("txtDestinatarioD", "autoDestinatarioD", "scripts/catalogoConsignatarios.php?operacion=3", {paramName: "caracteres",afterUpdateElement:existeDestinatario});
	new Ajax.Autocompleter("txtDestinatarioH", "autoDestinatarioH", "scripts/catalogoConsignatarios.php?operacion=3", {paramName: "caracteres",afterUpdateElement:existeDestinatario2});

	
	document.getElementById("txtGuiaD").focus();

}

function existeDestinatario() {
	$("status").innerHTML="";
	if ($("txtDestinatarioD").value!="" ){
		//Separamos la clave del Consignatario
		valores=$("txtDestinatarioD").value.split("/");
		var url = "scripts/existe.php?keys=19&f1value="+valores[0];
		$Ajax(url, {tipoRespuesta: $tipo.JSON,onfinish: function(ex)
								{
									existe=ex[0];
									if(existe.existe>0){
										$("txtDestinatarioD").value=valores[1];
									}
								}
		 
		      });
	}
}

function existeDestinatario2() {
	$("status").innerHTML="";
	if ($("txtDestinatarioH").value!="" ){
		//Separamos la clave del Consignatario
		valores=$("txtDestinatarioH").value.split("/");
		var url = "scripts/existe.php?keys=19&f1value="+valores[0];
		$Ajax(url, {tipoRespuesta: $tipo.JSON,onfinish: function(ex)
								{
									existe=ex[0];
									if(existe.existe>0){
										$("txtDestinatarioH").value=valores[1];
									}
								 }
		 
		 });
	}
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

function datosClientes() {	
	var url2 = "opc=0&codigo="+$("txtClienteD").value;
	$Ajax("scripts/datosClientes.php?operacion=4&cveCliente="+url2, {onfinish: cveCliente, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}

function datosClientes2() {	
	var url2 = "opc=0&codigo="+$("txtClienteH").value;
	$Ajax("scripts/datosClientes.php?operacion=4&cveCliente="+url2, {onfinish: cveCliente2, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
		
function cveCliente(campos) {
	var campo = campos[0];
	$("txtClienteD").value=campo.cveCliente;
}

function cveCliente2(campos) {
	var campo = campos[0];
	$("txtClienteH").value=campo.cveCliente;
}

function checar_valor()
{
	if(this.value=="")
	{
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
	if((this.value=="Desde")||(this.value=="Hasta"))
	{
		this.value="";
		this.className="busqueda1";
	}
}

function cargaSucursal(airls){
 
	//Borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("sltDestinoD").options.length = 1;
	
	//Empieza la carga de la lista
	$("sltDestinoD").options[$("sltDestinoD").options.length] = opcion;
	for (var i=0; i<airls.length; i++){
		var airl = airls[i];
		var opcion = new Option(airl.desc, airl.id);		
		try 
		{
			$("sltDestinoD").options[$("sltDestinoD").options.length]=opcion;
		}
		catch (e){alert("Error interno");}
	}	
	cargaSucursal2();
	
	for(i=1;i<$("sltDestinoD").options.length;i++){
		$("sltDestinoD").options[i].style.color="#000000";
		$("sltDestinoD").options[i].style.color="#000000";
	}
}

function cargaSucursal2()
{
	$("sltDestinoH").options.length = $("sltDestinoD").options.length;

	for(i=0;i<$("sltDestinoD").options.length;i++){
		$("sltDestinoH").options[i].value = $("sltDestinoD").options[i].value;
		$("sltDestinoH").options[i].text = $("sltDestinoD").options[i].text;
	}
	
	for(i=1;i<$("sltDestinoH").options.length;i++){
		$("sltDestinoH").options[i].style.color="#000000";
		$("sltDestinoH").options[i].style.color="#000000";
	}
}

function cargaEstados(){
 
	 var selecEdoD = $("sltStatusD");  
	 var selecEdoH = $("sltStatusH");  
	 
     selecEdoD.options.length = 1;  
     selecEdoH.options.length = 1;  
	 
	 selecEdoD.options.add(new Option("Carga Documentandose","Carga Documentandose"));
	 selecEdoD.options.add(new Option("Enviada A destino","Enviada A destino"));
	 selecEdoD.options.add(new Option("En proceso de Entrega","En proceso de Entrega"));
	 selecEdoD.options.add(new Option("Sin localizar destinatario","Sin localizar destinatario"));
	 selecEdoD.options.add(new Option("Dirrecion erronea","Dirrecion erronea"));
	 selecEdoD.options.add(new Option("Recabando Sello","Recabando Sello"));
	 selecEdoD.options.add(new Option("Faltan documentos","Faltan documentos"));
	 selecEdoD.options.add(new Option("Con cita para entrega","Con cita para entrega"));
	 selecEdoD.options.add(new Option("Entrega Rechazada","Entrega Rechazada"));
	 selecEdoD.options.add(new Option("Cancelada","Cancelada"));
	 selecEdoD.options.add(new Option("Entregada","Entregada"));
	 selecEdoD.options.add(new Option("Concluida","Concluida"));
	 
	 
	 selecEdoH.options.add(new Option("Carga Documentandose","Carga Documentandose"));
	 selecEdoH.options.add(new Option("Enviada A destino","Enviada A destino"));
	 selecEdoH.options.add(new Option("En proceso de Entrega","En proceso de Entrega"));
	 selecEdoH.options.add(new Option("Sin localizar destinatario","Sin localizar destinatario"));
	 selecEdoH.options.add(new Option("Dirrecion erronea","Dirrecion erronea"));
	 selecEdoH.options.add(new Option("Recabando Sello","Recabando Sello"));
	 selecEdoH.options.add(new Option("Faltan documentos","Faltan documentos"));
	 selecEdoH.options.add(new Option("Con cita para entrega","Con cita para entrega"));
	 selecEdoH.options.add(new Option("Entrega Rechazada","Entrega Rechazada"));
	 selecEdoH.options.add(new Option("Cancelada","Cancelada"));
	 selecEdoH.options.add(new Option("Entregada","Entregada"));
	 selecEdoH.options.add(new Option("Concluida","Concluida"));

	for(i=1;i<selecEdoD.options.length;i++){
		selecEdoH.options[i].style.color="#000000";
		selecEdoD.options[i].style.color="#000000";
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

	valor=0;
	//Checar que se haya ingresado por lo menos un valor en los input
	cajas=$$("#form2 input[type=text],#form2 select");
	for(i=0;i<cajas.length;i++)
	{
		if(cajas[i].value!="Desde" && cajas[i].value!="Hasta" && cajas[i].value!=0)
			valor++;
	}
	if(valor==0) {alert("Debe ingresar por lo menos un valor, en los criterios de búqueda."); return false;}
	
	destinoDesde=document.getElementById("sltDestinoD").selectedIndex;
	destinoHasta=document.getElementById("sltDestinoH").selectedIndex;
	if(destinoDesde>0 && destinoHasta>0){
		if(destinoDesde>destinoHasta){
			alert("El destino de 'Desde' es superior al de 'Hasta'.");
			return false;
		}
	}
	clienteDesde=document.getElementById("txtClienteD").value;
	clienteHasta=document.getElementById("txtClienteH").value;
	if(clienteDesde>0 && clienteHasta>0){
		if(clienteDesde>clienteHasta){
			alert("El cliente de 'Desde' es superior al de 'Hasta'.");
			return false;
		}
	}

	destinatarioDesde=document.getElementById("txtDestinatarioD").value;
	destinatarioHasta=document.getElementById("txtDestinatarioH").value;
	if(destinatarioDesde!="" && destinatarioHasta!=""){
		if(destinatarioDesde>destinatarioHasta)
		{
			alert("El destinatario de 'Desde' es superior al de 'Hasta'.");
			return false;
		}
	}	

	//Checar que las fechas esten correctas
	var expresion = /^\s*(\d{2,2})\/(\d{2,2})\/(\d{4,4})\s*$/;	

	if(($("txtRecepcionD").value!="Desde") && ($("txtRecepcionD").value!="") && (!($("txtRecepcionD").value.match(expresion))))
	{$("txtRecepcionD").addClassName("invalid"); alert("El formato de la fecha de recepci\u00F3n (desde) es incorrecto (dd/mm/yyyy)."); return false;} 
	else $("txtRecepcionD").removeClassName("invalid"); 
	
	if(($("txtRecepcionH").value!="Hasta") && ($("txtRecepcionH").value!="") && (!($("txtRecepcionH").value.match(expresion))))
	{$("txtRecepcionH").addClassName("invalid"); alert("El formato de la fecha de recepci\u00F3n (hasta) es incorrecto (dd/mm/yyyy)."); return false;} 
	else $("txtRecepcionH").removeClassName("invalid"); 	

	if( $("txtRecepcionD").value!="Desde" && $("txtRecepcionD").value!="" && $("txtRecepcionH").value!="Desde" && $("txtRecepcionH").value!="")	
	{	
		//Checar que la fecha de desde no sea superior a la fecha de hasta 
		valores=$('txtRecepcionD').value.split("/");
		desde = new Date(valores[2],valores[1],valores[0]).getTime();
		valores=$('txtRecepcionH').value.split("/");
		hasta = new Date(valores[2],valores[1],valores[0]).getTime();
		if(desde>hasta)
		{
			alert("La fecha de Recepci\u00F3n 'Desde' es mayor a la de 'Hasta'.");
			return false;
		}
	}

	if(($("txtFechaEntregaD").value!="Desde") && ($("txtFechaEntregaD").value!="") && (!($("txtFechaEntregaD").value.match(expresion))))
	{$("txtFechaEntregaD").addClassName("invalid"); alert("El formato de la fecha de entrega (desde) es incorrecto (dd/mm/yyyy)."); return false;} 
	else $("txtFechaEntregaD").removeClassName("invalid"); 	
	
	if(($("txtFechaEntregaH").value!="Hasta") && ($("txtFechaEntregaH").value!="") && (!($("txtFechaEntregaH").value.match(expresion))))
	{$("txtFechaEntregaH").addClassName("invalid"); alert("El formato de la fecha de entrega (hasta) es incorrecto (dd/mm/yyyy)."); return false;} 
	else $("txtFechaEntregaH").removeClassName("invalid"); 

	if( $("txtFechaEntregaD").value!="Desde" && $("txtFechaEntregaD").value!="" && $("txtFechaEntregaH").value!="Desde" && $("txtFechaEntregaH").value!="")	
	{
		//Checar que la fecha de desde no sea superior a la fecha de hasta 
		valores=$('txtFechaEntregaD').value.split("/");
		desde = new Date(valores[2],valores[1],valores[0]).getTime();
		valores=$('txtFechaEntregaH').value.split("/");
		hasta = new Date(valores[2],valores[1],valores[0]).getTime();
		if(desde>hasta)
		{
			alert("La fecha de Entrega 'Desde' es mayor a la de 'Hasta'.");
			return false;
	}	}
	
	return true;
}

