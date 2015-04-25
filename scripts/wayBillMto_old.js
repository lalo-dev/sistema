window.onload = inicia;

function inicia() {
	$("btnGo").onclick=existe;//al hacer click se llama funcion para ver si el registro existe
//Cargando listas


	
//inicializando autocomplete
$("autoGuia").className = "autocomplete";
new Ajax.Autocompleter("txtWbId", "autoGuia", "scripts/waybill_auto.php", {paramName: "caracteres"});

//ocultando campos que no son llave primaria y boton borrar
$("divContent").className="oculto";
$("btnBorrar").className="oculto";

}

function cargarLineas(aeros){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("lstAirLine").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("lstAirLine").options[$("lstAirLine").options.length] = opcion;

	for (var i=0; i<aeros.length; i++){
	var aero = aeros[i];
	var opcion = new Option(aero.desc, aero.id);

		try {
		$("lstAirLine").options[$("lstAirLine").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}

function cargarairl(airls){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("lstDestBranch").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("lstDestBranch").options[$("lstDestBranch").options.length] = opcion;

	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("lstDestBranch").options[$("lstDestBranch").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}



//funcion que verifica si existe un registro que tenga como llave el valor capturado
//hace una peticion que devuelve un único valor
function existe() {
		
		$Ajax("scripts/branch.php", {onfinish: cargarairl, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		$Ajax("scripts/airline.php", {onfinish: cargarLineas, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
		//estableciendo clases iniciales, para que se vean normal
		/*
		$("txtDestDesc").className = "element text large";
		$("lstDestVendor").className = "";
		$("lstDestPick").className = "";
		$("txtDestAdutCostPer").className = "element text currency";
		$("txtDestAdutCost").className = "element text currency";
		$("txtDestKidCostPer").className = "element text currency";
		$("txtDestKidCost").className = "element text currency";
		$("txtDestAdultPrice").className = "element text currency";
		$("txtDestKidPrice").className = "element text currency";
		*/
		
		$("status").innerHTML="";
		if ($("txtWbId").value!=""){
		var url = "scripts/existe.php?keys=1&table=waybill_mstr&field1=wb_id&f1value=" + $("txtWbId").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
		}
	}
//esta funcion recibe el valor de la función anterior y lo evalúa	
	function next_existe(ex){
		
		//agregamos un manejador de evento al boton cancelar
		$("btnCancelar").onclick=function(){
		
		//ocultando campos que no sean llave
		$("divContent").className="oculto";
		$("txtWbId").disabled=false;
		$("btnGo").disabled=false;
		$("txtWbId").value = "";
		
		};
		//deshabilitamos la llave primaria y el boton btnGo
		$("txtWbId").disabled=true;
		$("btnGo").disabled=true;
		//extraemos el valor retornado por el servidor en un objeto json
		var exx=ex[0];
		var exists = exx.existe;
		//si el valor es mayor que cero, entonces el registro existe
		if (exists > 0){
	
			
			//se piden los datos
			var url2 = "scripts/campos_wb.php?wb_id="+$("txtWbId").value;
			$Ajax(url2, {onfinish: carga_campos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			//cambiamos el manejador del boton actualizar para que apunte a la funcion
			//que actualiza el registro
			$("btnContinuar").onclick=actualizar;
			//agregamos un manejado de evento al boton borrar para que llame a su funcion borrar
			$("btnBorrar").onclick=borrar;
			//Mostramos todos los campos
			$("divContent").className="";
			//mostramos el boton de borrar
			$("btnBorrar").className="button";
			//imprimimos un mensaje de actualizando
			$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
		}
		else{	//si la funcion devolvio cero, no existe el registro
			//limpiamos los campos del form
			
			var tmp = $("txtWbId").value;
			$("form2").reset();
			$("txtWbId").value = tmp;
			//agregamos un manejador al boton continuar para que apunte a la funcion
			//que inserta un registro nuevo
			$("btnContinuar").onclick=insertar;
			//imprimimos un aviso de que se trata de un registro nuevo
			$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
			//mostramos los campos para captura de informacion
			$("divContent").className="";
			//el boton borrar no es útil aqui, por lo tanto lo ocultamos
			$("btnBorrar").className="oculto";
			
			}
		}
		
		
			
		
		function insertar(){
		if(allgood()){
              var valores = "wb_id="		+$("txtWbId").value		+"&wb_airline="		+$("lstAirLine").value		+"&wb_flightnum="	+$("txtFlNbr").value;
	valores = valores + "&wb_airwb="	+$("txtAirWb").value		+"&wb_flightdate="	+$("txtFlDate").value		+"&wb_rcye="		+$("rcye").value;
valores = valores + "&wb_cName="		+$("txtCName").value		+"&wb_cAdd="		+$("txtCAdd").value		+"&wb_cPhone="		+$("txtCPhone").value;
valores = valores + "&wb_cRfc="			+$("txtCRfc").value		+"&wb_consignee="	+$("txtCsnName").value		+"&wb_pieces="		+$("txtPieces").value;
valores = valores + "&wb_kg="			+$("txtKg").value		+"&wb_vol="		+$("txtVol").value		+"&wb_validity="	+$("txtValidity").value;
valores = valores + "&wb_destbranch="		+$("lstDestBranch").value	+"&wb_destzp="		+$("txtCsnZC").value		+"&wb_destadd="		+$("txtCsnAdd").value;
valores = valores + "&wb_status="		+$("lstStatus").value		+"&wb_delivdate="	+$("txtDlvdDate").value		+"&wb_receivedby="	+$("txtRcbdBy").value;
valores = valores + "&wb_dlvnbr="		+$("txtDlvNbr").value		+"&wb_vlnbr="		+$("txtVlNbr").value		+"&wb_invnbr="		+$("txtInvNbr").value	+"&wb_receiptdate="	+$("txtRcptDate").value		+"&wb_rmks="		+$("txtRmks").value;
	
		//var valores = $("form1").serialize();
		$Ajax("scripts/inwb.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		$("divContent").className="oculto";
		}
		
		}
		
		function actualizar(){
		if (allgood()){
		var valores = "wb_id="		+$("txtWbId").value		+"&wb_airline="		+$("lstAirLine").value		+"&wb_flightnum="	+$("txtFlNbr").value;
	valores = valores + "&wb_airwb="	+$("txtAirWb").value		+"&wb_flightdate="	+$("txtFlDate").value		+"&wb_rcye="		+$("rcye").value;
valores = valores + "&wb_cName="		+$("txtCName").value		+"&wb_cAdd="		+$("txtCAdd").value		+"&wb_cPhone="		+$("txtCPhone").value;
valores = valores + "&wb_cRfc="			+$("txtCRfc").value		+"&wb_consignee="	+$("txtCsnName").value		+"&wb_pieces="		+$("txtPieces").value;
valores = valores + "&wb_kg="			+$("txtKg").value		+"&wb_vol="		+$("txtVol").value		+"&wb_validity="	+$("txtValidity").value;
valores = valores + "&wb_destbranch="		+$("lstDestBranch").value	+"&wb_destzp="		+$("txtCsnZC").value		+"&wb_destadd="		+$("txtCsnAdd").value;
valores = valores + "&wb_status="		+$("lstStatus").value		+"&wb_delivdate="	+$("txtDlvdDate").value		+"&wb_receivedby="	+$("txtRcbdBy").value;
valores = valores + "&wb_dlvnbr="		+$("txtDlvNbr").value		+"&wb_vlnbr="		+$("txtVlNbr").value		+"&wb_invnbr="		+$("txtVlNbr").value	+"&wb_receiptdate="	+$("txtRcptDate").value		+"&wb_rmks="		+$("txtRmks").value;
		$Ajax("scripts/upwb.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		
		
		$("divContent").className="oculto";
		}
		
			}
		
		function borrar(){
		if (confirm("Confirme que desea borrar")){
		
		var hid ="wb_id=" + $("txtWbId").value;
		$Ajax("scripts/delwb.php", {metodo: $metodo.POST, onfinish: fin, parametros: hid, avisoCargando:"loading"});
		
		
		$("divContent").className="oculto";
		
		}
		
		
		}
		
		function carga_campos(campos){
		//tomamos el primer objeto del json, ya que siempre devolvera un unico registro
		var campo = campos[0];
		//asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
		
		
		for(var idx = 0; idx < $("lstAirLine").options.length; idx ++){
		if($("lstAirLine").options[idx].value == campo.wb_airline){
			$("lstAirLine").selectedIndex = idx;
			$("lstAirLine").options[idx].selected=true;
			}
			
		}
		
		$("txtAirWb").value = campo.wb_airwb;
		$("txtFlNbr").value = campo.wb_flightnum;
		$("txtFlDate").value = campo.wb_flightdate;
		$("rcye").value = campo.wb_rcye;
		$("txtCName").value = campo.wb_cName;
		$("txtCAdd").value = campo.wb_cAdd;
		$("txtCPhone").value = campo.wb_cPhone;
		$("txtCRfc").value = campo.wb_cRfc;
		
		for(var idx1 = 0; idx1 < $("lstDestBranch").options.length; idx1 ++){
		if($("lstDestBranch").options[idx1].value == campo.wb_destbranch){
			$("lstDestBranch").selectedIndex = idx1;
			$("lstDestBranch").options[idx1].selected=true;
			}
		}
		
		$("txtCsnName").value = campo.wb_consignee;
		$("txtCsnAdd"). value = campo.wb_destadd;
		$("txtCsnZC").value = campo.wb_destzp;
		$("txtPieces").value = campo.wb_pieces;
		$("txtKg").value = campo.wb_kg;
		$("txtVol").value = campo.wb_vol;
		$("txtValidity").value = campo.wb_validity;
		
		for(var idx1 = 0; idx1 < $("lstStatus").options.length; idx1 ++){
		if($("lstStatus").options[idx1].value == campo.wb_status){
			$("lstStatus").selectedIndex = idx1;
			$("lstStatus").options[idx1].selected=true;
			}
		}
		$("txtDlvdDate").value = campo.wb_delivdate;
		$("txtRcbdBy").value = campo.wb_receivedby;
		
		$("txtDlvNbr").value = campo.wb_dlvnbr;
		$("txtVlNbr").value = campo.wb_vlnbr;
		$("txtInvNbr").value = campo.wb_invnbr;
		$("txtRcptDate").value = campo.wb_receiptdate;
		$("txtRmks").value = campo.wb_rmks;
		
		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
		}

		function fin(res){
		alert(res);
		$("divContent").className="oculto";
		$("txtWbId").disabled=false;
		
		$("btnGo").disabled=false;
		$("txtWbId").value = "";
		
		$("status").innerHTML="";
		
		}
		
		function allgood(){
		var notGood = 0;
		
		if($("lstAirLine").value == ""){$("lstAirLine").className += " invalid"; notGood ++;} else{$("lstAirLine").className = "";}
		if($("txtAirWb").value.length < 3){$("txtAirWb").className += " invalid"; notGood ++;} else{$("txtAirWb").className = "";}
		if($("txtFlNbr").value.length < 3){$("txtFlNbr").className += " invalid"; notGood ++;} else{$("txtFlNbr").className = "";}
		if($("txtFlDate").value == ""){$("txtFlDate").className += " invalid"; notGood ++;} else{$("txtFlDate").className = "";}
		if($("rcye").value.length < 3){$("rcye").className += " invalid"; notGood ++;} else{$("rcye").className = "";}
		if($("txtCName").value.length < 3){$("txtCName").className += " invalid"; notGood ++;} else{$("txtCName").className = "";}
		if($("txtCAdd").value.length < 3){$("txtCAdd").className += " invalid"; notGood ++;} else{$("txtCAdd").className = "";}
		if($("lstDestBranch").value == ""){$("lstDestBranch").className += " invalid"; notGood ++;} else{$("lstDestBranch").className = "";}
		if($("txtCsnName").value.length < 3){$("txtCsnName").className += " invalid"; notGood ++;} else{$("txtCsnName").className = "";}
		if($("txtCsnAdd").value.length < 3){$("txtCsnAdd").className += " invalid"; notGood ++;} else{$("txtCsnAdd").className = "";}		
		if($("txtCsnZC").value.length < 5 || isNaN($("txtCsnZC").value)){$("txtCsnZC").className += " invalid"; notGood ++;} else{$("txtCsnZC").className = "";}		
		if($("txtPieces").value == "" || isNaN($("txtPieces").value)){$("txtPieces").className += " invalid"; notGood ++;} else{$("txtPieces").className = "";}		
		if($("txtKg").value == "" || isNaN($("txtKg").value)){$("txtKg").className += " invalid"; notGood ++;} else{$("txtKg").className = "";}	
		if($("txtValidity").value != ""){
			if(isNaN($("txtValidity").value)){$("txtValidity").className += " invalid"; notGood ++;} else{$("txtValidity").className = "";}	
		}	
		
						
		if($("lstStatus").value == ""){$("lstStatus").className += " invalid"; notGood ++;} else {$("lstStatus").className += "";}
		
		
		
		
		if(notGood > 0){alert("Hay Informacion erronea que ha sido resaltada en color!");return false;} else{return true;}
		}
		
		
		
		