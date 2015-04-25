var currentFilter = "";
var currChkRadio = "";
var query = "";
window.onload = function(){
$Ajax("scripts/branch.php", {onfinish: cargarairl, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
//$("formConsul").onsubmit = return consulta();
//$("btnGo").onclick=consulta;
$("btnGo").disabled = true;
var radios = document.getElementsByClassName("radio");

for(var i = 0; i < radios.length; i++){
	var radioId = "r"+i;
	
	$("div"+radioId).hide();
	$(radioId).onclick = function(){check(this, radios.length); $("btnGo").disabled = false;}
	}
}
function check(radio, radios){
currentFilter = "field"+radio.id;
currChkRadio  = radio.id;
$("div"+radio.id).show();
$("div"+radio.id).className += " shown";
radio.className += " clicked";

		oneChecked(radio.className, $("div"+radio.id).className, radios);
		radio.className = "radio";
		$("div"+radio.id).className = "div";
}

function cargarairl(airls){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("fieldr2").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("fieldr2").options[$("fieldr2").options.length] = opcion;

	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("fieldr2").options[$("fieldr2").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}

function oneChecked(classClicked, divClass, radios){

for(var i = 0; i < radios; i++){
	var radioId = "r"+i;
	var divId = "divr"+i;
	if($(radioId).className != classClicked){
	$(radioId).checked = false;
	}
	if($(divId).className != divClass){
	$(divId).hide();
	}
	}
}

function consulta(){
	var pseudo = "";	
	//var pseudo = "select wb_id, branch_desc, airline_name, wb_flightnum, wb_airwb, wb_flightdate, wb_rcye, wb_cName,wb_cAdd, wb_cPhone, wb_cExt, wb_cRfc, wb_consignee, wb_pieces, wb_kg, wb_vol, wb_validity, wb_destbranch, wb_destzp, wb_destadd, wb_status, wb_delivdate, wb_receivedby, wb_dlvnbr, wb_vlnbr, wb_invnbr, wb_receiptdate, wb_rmks from waybill_mstr inner join airline_mstr on wb_airline = airline_id inner join branch_mstr on wb_destbranch = branch_id";
	switch(currChkRadio){
	case "r0":
	//filtro por numero de guia
	if($(currentFilter).value != ""){
	query = pseudo + " WHERE wb_id = '" + $(currentFilter).value+"'";
	$("hQuery").value = query;
	return true;
	} else{return false;}
	break;
	
	case "r1":
	//filtro por cliente
	if($(currentFilter).value != ""){
	query = pseudo + " WHERE wb_cname = '" + $(currentFilter).value+"'";
	$("hQuery").value = query;
	return true;
	}else{return false;}
	break;
	
	case "r2":
	if($(currentFilter).value != ""){
	//filtro por destino
	query = pseudo + " WHERE wb_destbranch = '" + $(currentFilter).value+"'";
	$("hQuery").value = query;
	return true;
	}else{return false;}
	break;
	
	case "r3":
	if($(currentFilter).value != ""){
	query = pseudo + " WHERE wb_consignee = '" + $(currentFilter).value+"'";
	$("hQuery").value = query;
	return true;
	}else{return false;}
	break;
	
	case "r4":
	if($(currentFilter).value != "" && $("fieldr5").value != ""){
	query = pseudo + " WHERE wb_rcye >= '" + $(currentFilter).value+"' and wb_rcye <= '"+ $("fieldr5").value+"'";
	$("hQuery").value = query;
	return true;
	}else{return false;}
	break;
	
	case "r5":
	if($("fieldr6").value != ""){
	query = pseudo + " WHERE wb_status = '" + $("fieldr6").value+"'";
	$("hQuery").value = query;
	return true;
	}else{return false;}
	break;
	
	case "r6":
	if($("fieldr7").value != "" && $("fieldr8").value != ""){
	query = pseudo + " WHERE wb_id >= " + $("fieldr7").value+" and wb_id <= "+ $("fieldr8").value;
	$("hQuery").value = query;
	return true;
	}else{return false;}
	break;
	
	default:
	alert("No se ha definido una accion para éste filtro - Error de la aplicacion -");
	return false;
	break;
	}
	
}