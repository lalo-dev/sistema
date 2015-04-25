window.onload = inicia;

function inicia() {

	var respuesta= "scripts/estados.php?pais=156";
	$Ajax(respuesta, {onfinish: cargaEstados, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	var respuesta= "scripts/estados.php?pais=156";
	$Ajax(respuesta, {onfinish: cargaEstadosD, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	$("slcEstados").onchange=llenaMunicipios;
	$("slcEstadosD").onchange=llenaMunicipiosD;	
	$("btnGuardar").onclick=existe;
	$Ajax("scripts/envios.php", {onfinish: cargarEnvios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		
	$("autoCliente").className = "autocomplete";
	new Ajax.Autocompleter("txtRazonSocial", "autoCliente", "scripts/catalogoClientes.php?operacion=5", {paramName: "caracteres", afterUpdateElement:datosCorresponsales1});
	$("autoClienteC").className = "autocomplete";
	new Ajax.Autocompleter("txtCodigo", "autoClienteC", "scripts/catalogoClientes.php?operacion=5", {paramName: "caracteres",afterUpdateElement:datosCorresponsales2});
	$("txtCodigo").focus();
	$("btnCrear").disabled=true;
	$("btnModificar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnGuardar").style.visibility="hidden";
	$("btnCancelar").onclick=limpia;
	$("slcEstados").value=9;
	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=$("txthPer").value;
	if(numP==6)
	{
		$("btnCrear").style.visibility="hidden";
		$("btnModificar").style.visibility="hidden";
		$("btnGuardar").style.visibility="hidden";
		$("btnModificarD").style.visibility="hidden";
		
		$("btnCrear").style.display="none";
		$("btnModificar").style.display="none";		
		$("btnGuardar").style.display="none";
		$("btnModificarD").style.display="none";
	}


}

function opcDefault()
{
	$("slcEstados").value=9;
	llenaMunicipios();
	setTimeout("opcDefault2()",100);
}

function opcDefault2()
{
	$("slcMunicipios").value=17;
}

function limpia()
{
	$("form2").reset();
	
	$("slcMunicipios").options.length = 0;
	$("slcMunicipiosD").options.length = 0;
	
	$("txtCodigo").disabled=false;
	$("txtRazonSocial").disabled=false;
	$("txtcveTarifa").disabled=false;
	$("slcEstados").disabled=false;
	$("slcMunicipios").disabled=false;
	$("slcEstadosD").disabled=false;				
	$("slcMunicipiosD").disabled=false;
	
	$("visible").className="oculto";				
	$("divRangos").className="oculto";				
	
	$("btnCrear").disabled=true;
	$("btnModificar").disabled=true;
	$("btnGuardar").disabled=true;
	$("btnGuardar").style.visibility="hidden";
	$("btnModificarD").style.visibility="hidden";
	$("btnCancelarD").style.visibility="hidden";
	$("slcTipoe").disabled=false;
	
	$("status").innerHTML="";
	 quitar_invalidos();
	 $("txtCodigo").focus();
	 
	 
}
function cargarEnvios(envios){
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcTipoe").options.length = 0;
	//empieza la carga de la lista
	
	for (var i=0; i<envios.length; i++){
		var envio = envios[i];
		var opcion = new Option(envio.desc, envio.id);
		
		try {
		$("slcTipoe").options[$("slcTipoe").options.length]=opcion;
		}catch (e){alert("Error interno");}
	}
	
}

function datosCorresponsales1() {	
	var url2 = "opc=1&codigo="+$("txtRazonSocial").value;
	$Ajax("scripts/datosCorresponsales.php?operacion=3&"+url2, {onfinish: cveCorresponsal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
function datosCorresponsales2() {	
	var url2 = "opc=0&codigo="+$("txtCodigo").value;
	$Ajax("scripts/datosCorresponsales.php?operacion=3&"+url2, {onfinish: cveCorresponsal, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
}
		
function cveCorresponsal(campos) {

			var campo = campos[0];
			$("txtCodigo").value=campo.cveCorresponsal;
			$("txtRazonSocial").value=campo.razonSocial;
			$Ajax("scripts/catalogoTotales.php?operacion=1&valor1="+$('txtCodigo').value, {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
            $("autoTarifa").className = "autocomplete";
            new Ajax.Autocompleter("txtcveTarifa", "autoTarifa", "scripts/catalogoTarifasC.php?operacion=5&corresponsal=" + campo.cveCorresponsal, {paramName: "caracteres",afterUpdateElement:existe});
			$("btnBuscar").onclick=existe;	
			setTimeout("opcDefault()",50);
		}

function editarDetalle(obj,cveDetalle){
	
	  var url = "scripts/datosTarifasC.php?operacion=4&tarifa="+ cveDetalle;
      $Ajax(url, {onfinish: editaDetalle, tipoRespuesta: $tipo.JSON});
      $("btnModificarD").className = "";
      $("btnCancelarD").className = "";
	  $("btnModificarD").style.visibility="visible";
      $("btnCancelarD").style.visibility="visible";
      $("btnModificarD").onclick=modificarDetalle;	
      $("btnCancelarD").onclick=limpiarDetalle2;	
      $("btnGuardar").disabled=true;
	  elementos=$$("#divRangos input[type=text]");

	   for(i=0;i<elementos.length;i++)
	  { 	
		id=elementos[i].id; 
		if(id!="totalReg"){
			$(id).removeClassName("invalid");
			$(id).value=""; 
		}
	  }
      //eliminamos la fila una vez que se cargan los datos
      var oTr = obj;
         while(oTr.nodeName.toLowerCase()!='tr'){
          oTr=oTr.parentNode;
         }
         var root = oTr.parentNode;
         root.removeChild(oTr);
         
	}
	


function cargaEstados(estados){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("slcEstados").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("slcEstados").options[$("slcEstados").options.length] = opcion;

	for (var i=0; i<estados.length; i++){
	var estado = estados[i];
	var opcion = new Option(estado.desc, estado.id);

		try {
		$("slcEstados").options[$("slcEstados").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}
function cargaEstadosD(estados){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("slcEstadosD").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("slcEstadosD").options[$("slcEstadosD").options.length] = opcion;

	for (var i=0; i<estados.length; i++){
	var estado = estados[i];
	var opcion = new Option(estado.desc, estado.id);

		try {
		$("slcEstadosD").options[$("slcEstadosD").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}
function llenaMunicipios(){
	
	var respuesta= "scripts/municipios.php?municipio=" + $("slcEstados").value;
	$Ajax(respuesta, {onfinish: cargaMunicipios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	}
	function cargaMunicipios(airls){	    
	// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
	$("slcMunicipios").options.length = 0;
	//empieza la carga de la lista
	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("slcMunicipios").options[$("slcMunicipios").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}	
	function llenaMunicipiosD(){
	
	var respuesta= "scripts/municipios.php?municipio=" + $("slcEstadosD").value;
	$Ajax(respuesta, {onfinish: cargaMunicipiosD, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});

	}
	function cargaMunicipiosD(airls){	  
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("slcMunicipiosD").options.length = 0;
//empieza la carga de la lista
	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("slcMunicipiosD").options[$("slcMunicipiosD").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}	
	
var indiceFilaFormulario=1;	
	
function addTarifa(tarifas){	


	var indicador=tarifas[0];

	if(indicador.indice!=0)	
	{
			$("visible").className="oculto";
	}	
   else{
	$("visible").className="";	
	if(document.getElementById("tablaFormulario").rows.length>1){
		var ultima = document.getElementById("tablaFormulario").rows.length;
        for(var j=ultima; j>1; j--){
			document.getElementById("tablaFormulario").deleteRow(1);				 		
    	}
    }
	
	for (var i=0; i<tarifas.length; i++){
				
			var tarifa = tarifas[i];
			 myNewRow = document.getElementById("tablaFormulario").insertRow(-1); 
			 myNewRow.id=indiceFilaFormulario;
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td>" + tarifa.tipoEnvio +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.primerRango) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.segundoRango) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.Tercerrango) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.cuartoRango) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.sobrepeso) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.costoSobrepeso) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.distancia) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
 			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.costoDistancia) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.costoEntrega) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.costoEspecial) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.costoViaticos) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'>" + formatoMoneda(tarifa.cargoMinimo) +"</td>";
			 myNewCell=myNewRow.insertCell(-1);
			 myNewCell.innerHTML="<td class='moneda'><input type='hidden' id='cveDetalle_"+indiceFilaFormulario+"' value='" + tarifa.cveDetalle +"'/><input type='button' name='Editar["+indiceFilaFormulario+"]'  value='Editar' onclick='editarDetalle(this," + tarifa.cveDetalle +")'></td>";
			 indiceFilaFormulario++;

	}
}	
}


//funcion que verifica si existe un registro que tenga como llave el valor capturado
//hace una peticion que devuelve un único valor$("slcEstados").value,$("slcMunicipios").value,$("slcEstadosD").value,$("slcMunicipiosD").valuehdnTipoTarifa
function existe() {		
    	if ($("txtcveTarifa").value!=""){
				var cve_tarifa=($("txtcveTarifa").value).split("-");
				$("txtcveTarifa").value=cve_tarifa[0];
    	  		var url = "scripts/datosTarifasC.php?operacion=2&corresponsal=" + $("txtCodigo").value+"&tarifa="+ $("txtcveTarifa").value;
				$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	    }
	}
	function existe2() {		
		if ($("txtcveTarifa").value!=""){
    	  		var url = "scripts/datosTarifasC.php?operacion=2&corresponsal=" + $("txtCodigo").value+"&tarifa="+ $("txtcveTarifa").value;
				$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
	    }
	}
//esta funcion recibe el valor de la función existe y lo evalúa	
	function next_existe(ex){	
	   		//extraemos el valor retornado por el servidor en un objeto json
		var exx=ex[0];
		var exists = exx.existe;
		//si el valor es mayor que cero, entonces el registro existe
		if (exists > 0){	
				//se piden los datos para rellenar el formulario
				$("txthEdoO").value=exx.estadoOrigen;
				$("txthEdoD").value=exx.estadoDestino;
				var respuesta= "scripts/municipios.php?municipio=" +  exx.estadoOrigen;
				$Ajax(respuesta, {onfinish: cargaMunicipios, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
				respuesta= "scripts/municipios.php?municipio=" +  exx.estadoDestino;
				$Ajax(respuesta, {onfinish: cargaMunicipiosD, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
				$("txtcveTarifa").disabled=true;
				$("txtCodigo").disabled=true;
				$("txtRazonSocial").disabled=true;
				$("btnModificar").disabled=false;
				$("btnModificar").onclick=modificarTarifa;
				$("btnCrear").disabled=true;
				$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
				setTimeout("datos()",1000);
            }
		else{	//si la funcion devolvio cero, no existe el registro
				//limpiamos los campos del form		            	
				$("btnCrear").disabled=false;
				$("btnCrear").onclick=guardarTarifa;
				$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
				$("txtcveTarifa").disabled=true;
				//Asignará el valor siguiente a la Clave de la Tarifa
				respuesta= "scripts/datosTarifasC.php?operacion=7&corresponsal=" + $("txtCodigo").value;
				$Ajax(respuesta, {onfinish: asigna, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			
			}
		}
	function datos()
	{	
		respuesta= "scripts/datosTarifasC.php?operacion=1&corresponsal=" + $("txtCodigo").value+"&tarifa="+ $("txtcveTarifa").value;
		$Ajax(respuesta, {onfinish: llenaRangos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	}
	function asigna(datos)
	{
		dato=datos[0];
		$("txtcveTarifa").value=dato.cve;
	
	}
    function llenaRangos(campos){
        		var campo = campos[0];                
                $("slcEstados").value = campo.estadoOrigen;
		$("slcEstadosD").value = campo.estadoDestino;
                $("txtprimerRango").value = campo.primerRango;
		$("txtsegundoRango").value = campo.segundoRango;
		$("txttercerRango").value = campo.tercerRango;
		$("txtcuartoRango").value = campo.cuartoRango;
		$("slcMunicipios").value = campo.municipioOrigen;
		$("slcMunicipiosD").value = campo.municipioDestino;
		$("txthMunO").value = campo.municipioOrigen;
		$("txthMunD").value = campo.municipioDestino;
                $("divRangos").className="";
                $("thPrimero").innerHTML= campo.primerRango;
                $("thSegundo").innerHTML= campo.segundoRango ;
                $("thTercero").innerHTML=campo.tercerRango ;
                $("thCuarto").innerHTML= campo.cuartoRango ;
                $("thPrimero2").innerHTML= campo.primerRango;
                $("thSegundo2").innerHTML= campo.segundoRango ;
                $("thTercero2").innerHTML=campo.tercerRango ;
                $("thCuarto2").innerHTML= campo.cuartoRango ;
                $("btnGuardar").disabled=false;
				$("btnGuardar").style.visibility="visible";
                $("btnGuardar").onclick=guardaDetalle;
                
				
                var url = "scripts/datosTarifasC.php?operacion=3&corresponsal=" + $("txtCodigo").value+"&tarifa="+ $("txtcveTarifa").value;
                $Ajax(url, {onfinish: addTarifa, tipoRespuesta: $tipo.JSON});
    }
	function guardarTarifa(){				
			if(allgood())
            {				       
				 var valores = "&txtcveTarifa="		+$("txtcveTarifa").value		+"&txtCodigo="		+$("txtCodigo").value		+"&slcEstados="		+$("slcEstados").value;
                 valores = valores + "&slcEstadosD="			+$("slcEstadosD").value		+"&txtprimerRango="	+$("txtprimerRango").value		+"&txtsegundoRango="		+$("txtsegundoRango").value		+"&txttercerRango="		+$("txttercerRango").value		+"&txtcuartoRango="		+$("txtcuartoRango").value		+"&slcMunicipios="		+$("slcMunicipios").value		+"&slcMunicipiosD=" + $("slcMunicipiosD").value;
				 

                 var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
valores=valores +usuario;
                 $Ajax("scripts/guardarTarifasC.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
				setTimeout("existe2()",50);
				 
			}		
		}
    function modificarTarifa(){				
			if(allgood())
            {				       
				 var valores = "&txthEdoO="+$("txthEdoO").value+"&txthEdoD="+$("txthEdoD").value+"&txthMunO="+$("txthMunO").value
				               +"&txthMunD="+$("txthMunD").value+"&txtcveTarifa="+$("txtcveTarifa").value+"&txtCodigo="+	
					       $("txtCodigo").value+"&slcEstados="+$("slcEstados").value;

                 valores += "&slcEstadosD="+$("slcEstadosD").value+"&txtprimerRango="	+$("txtprimerRango").value		
			    +"&txtsegundoRango="+$("txtsegundoRango").value+"&txttercerRango="+$("txttercerRango").value		
			    +"&txtcuartoRango="+$("txtcuartoRango").value+"&slcMunicipios="+$("slcMunicipios").value+"&slcMunicipiosD=" + 	
			    $("slcMunicipiosD").value;

                 var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
valores=valores +usuario;
                 
				$Ajax("scripts/guardarTarifasC.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
				limpiarDetalle();
				setTimeout("tarifas()",50);
            
			}		
		}
		
	function guardaDetalle(){
            if(verificarCampos())
            {				       
				var valores = "&txtcveTarifa="		+$("txtcveTarifa").value		+"&txtCodigo="		+$("txtCodigo").value		+"&slcTipoe="		+$("slcTipoe").value;
				
                 //En caso de no tener valores se insertará un cero
				 inputs=$$("#divRangos input");
				 for(i=0;i<inputs.length;i++)
				 {
					 if(inputs[i].value=="")
					 	inputs[i].value=0;
				 }

                 valores = valores + "&txtRango1="			+$("txtRango1").value		+"&txtRango2="	+$("txtRango2").value		+"&txtRango3="		+$("txtRango3").value		+"&txtRango4="		+$("txtRango4").value		+"&txtSobrepeso="		+$("txtSobrepeso").value		+"&txtDistancia="		+$("txtDistancia").value		+"&txtTarifaMin=" + $("txtTarifaMin").value+"&txtCSobrepeso=" + $("txtCSobrepeso").value+"&txtCDistancia=" + $("txtCDistancia").value+"&txtCEntrega=" + $("txtCEntrega").value+"&txtCEspecial=" + $("txtCEspecial").value+"&txtCViaticos=" + $("txtCViaticos").value;

				 
                 var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
valores=valores +usuario;
                $Ajax("scripts/guardarTarifasC.php?operacion=4", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
			    
				setTimeout("limpiarDetalle2()",50);

            }	
	   
	}
    function modificarDetalle(){
            if(verificarCampos())
            {				       
				 var valores = "&txtcveTarifa="		+$("txtcveTarifa").value		+"&txtCodigo="		+$("txtCodigo").value		+"&slcTipoe="		+$("slcTipoe").value;
                 //En caso de no tener valores se insertará un cero
				 inputs=$$("#divRangos input");
				 for(i=0;i<inputs.length;i++)
				 {
					 if(inputs[i].value=="")
					 	inputs[i].value=0;
				 }
				 
				 valores = valores + "&txtRango1="			+$("txtRango1").value		+"&txtRango2="	+$("txtRango2").value		+"&txtRango3="		+$("txtRango3").value		+"&txtRango4="		+$("txtRango4").value		+"&txtSobrepeso="		+$("txtSobrepeso").value		+"&txtDistancia="		+$("txtDistancia").value		+"&txtTarifaMin=" + $("txtTarifaMin").value+"&txtCSobrepeso=" + $("txtCSobrepeso").value+"&txtCDistancia=" + $("txtCDistancia").value+"&txtCEntrega=" + $("txtCEntrega").value+"&txtCEspecial=" + $("txtCEspecial").value+"&txtCViaticos=" + $("txtCViaticos").value;
                 var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
valores=valores +usuario;

                $Ajax("scripts/guardarTarifasC.php?operacion=5", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});

				setTimeout("limpiarDetalle2()",50);
			     
                 
            }	
	   
	}
	function tarifas_detalle()
	{
		var url = "scripts/datosTarifasC.php?operacion=3&corresponsal=" + $("txtCodigo").value+"&tarifa="+ $("txtcveTarifa").value;
        $Ajax(url, {onfinish: addTarifa, tipoRespuesta: $tipo.JSON});
	}
	function tarifas(){ 
		respuesta= "scripts/datosTarifasC.php?operacion=1&corresponsal=" + $("txtCodigo").value+"&tarifa="+ $("txtcveTarifa").value;
		$Ajax(respuesta, {onfinish: llenaRangos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	}

	function fin(res){	
		alert(res);	
		$Ajax("scripts/catalogoTotales.php?operacion=1&valor1="+$('txtCodigo').value, {onfinish: cargarTotales, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
	}
		
		
	function allgood(){
		var notGood = 0;
        if($("slcEstados").value == ""){$("slcEstados").addClassName("invalid"); notGood ++;} else{$("slcEstados").className = "";}
		if($("slcEstadosD").value == ""){$("slcEstadosD").addClassName("invalid"); notGood ++;} else{$("slcEstadosD").className = "";}
		if($("slcMunicipios").value == ""){$("slcMunicipios").addClassName("invalid"); notGood ++;} else{$("slcMunicipios").className = "";}
		if($("slcMunicipiosD").value == ""){$("slcMunicipiosD").addClassName("invalid"); notGood ++;} else{$("slcMunicipiosD").className = "";}
		if($("txtprimerRango").value == ""){$("txtprimerRango").addClassName("invalid"); notGood ++;} else{$("txtprimerRango").className = "";}
		if($("txtsegundoRango").value == ""){$("txtsegundoRango").addClassName("invalid"); notGood ++;} else{$("txtsegundoRango").className = "";}
		if($("txttercerRango").value == ""){$("txttercerRango").addClassName("invalid"); notGood ++;} else{$("txttercerRango").className = "";}
		if($("txtcuartoRango").value == ""){$("txtcuartoRango").addClassName("invalid"); notGood ++;} else{$("txtcuartoRango").className = "";}

		if(notGood > 0){
			alert("Hay Informacion erronea que ha sido resaltada en color!");
			return false;
			} else
			{
				return true;
			}
			
		}

	function verificarCampos(){
		var notGood = 0;
        if($("slcTipoe").value == ""){$("slcTipoe").addClassName("invalid"); notGood ++;} else{$("slcTipoe").className = "";}
		if(isNaN($("txtRango1").value) || $("txtRango1").value == ""){$("txtRango1").addClassName("invalid"); notGood ++;} else{$("txtRango1").className = "";}
		if(isNaN($("txtRango2").value) || $("txtRango2").value == ""){$("txtRango2").addClassName("invalid"); notGood ++;} else{$("txtRango2").className = "";}
		if(isNaN($("txtRango3").value) || $("txtRango3").value == ""){$("txtRango3").addClassName("invalid"); notGood ++;} else{$("txtRango3").className = "";}
		if(isNaN($("txtRango4").value) || $("txtRango4").value == ""){$("txtRango4").addClassName("invalid"); notGood ++;} else{$("txtRango4").className = "";}
		
		if(notGood > 0){
			alert("Hay Informacion erronea que ha sido resaltada en color.\n(Los datos deben ser num\u00E9ricos)");
			return false;
			} else
			{
				return true;
			}
			
		}
        
  function editaDetalle(campos){
        		var campo = campos[0];                
                $("slcTipoe").value = campo.tipoEnvio;
                $("slcTipoe").disabled = true;
				$("txtRango1").value = campo.primerRango;
                $("txtRango2").value = campo.segundoRango;
				$("txtRango3").value = campo.Tercerrango;
				$("txtRango4").value = campo.cuartoRango;
				$("txtSobrepeso").value = campo.sobrepeso;
				$("txtDistancia").value = campo.distancia;
				$("txtTarifaMin").value = campo.cargoMinimo;
				$("txtCSobrepeso").value = campo.costoSobrepeso;
				$("txtCDistancia").value = campo.costoDistancia;
				$("txtCEntrega").value = campo.costoEntrega;
				$("txtCEspecial").value = campo.costoEspecial;
				$("txtCViaticos").value = campo.costoViaticos;
                
    }
  function limpiarDetalle(){
				
                $("slcTipoe").disabled = false;
                $("txtRango1").value = "";
                $("txtRango2").value = "";
				$("txtRango3").value = "";
				$("txtRango4").value = "";
				$("txtSobrepeso").value = "";
				$("txtDistancia").value = "";
				$("txtTarifaMin").value = "";				
				$("txtCSobrepeso").value = "";
				$("txtCDistancia").value = "";
				$("txtCEntrega").value = "";
				$("txtCEspecial").value = "";
				$("txtCViaticos").value = "";
				$("slcTipoe").focus();				
    
  }
   function limpiarDetalle2(){
			   
		var url = "scripts/datosTarifasC.php?operacion=3&corresponsal=" + $("txtCodigo").value+"&tarifa="+ $("txtcveTarifa").value;
		$Ajax(url, {onfinish: addTarifa, tipoRespuesta: $tipo.JSON});
			
		$("btnModificarD").style.visibility="hidden";
		$("btnCancelarD").style.visibility="hidden";
		$("btnGuardar").disabled=false;
		$("slcTipoe").disabled = false;
		$("txtRango1").value = "";
		$("txtRango2").value = "";
		$("txtRango3").value = "";
		$("txtRango4").value = "";
		$("txtSobrepeso").value = "";
		$("txtDistancia").value = "";
		$("txtTarifaMin").value = "";
		$("txtCSobrepeso").value = "";
		$("txtCDistancia").value = "";
		$("txtCEntrega").value = "";
		$("txtCEspecial").value = "";
		$("txtCViaticos").value = "";
		$("slcTipoe").focus();
    
  }
  
  function formatoMoneda(num)
        {
            num = Math.round(parseFloat(num)*Math.pow(10,2))/Math.pow(10,2)
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
  
  
