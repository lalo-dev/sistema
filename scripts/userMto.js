var upswd = 1;
window.onload = inicia;

function inicia() {
	$("btnGo").onclick=existe;//al hacer click se llama funcion para ver si el registro existe
//Cargando listas


	
//inicializando autocomplete
$("autoUser").className = "autocomplete";
new Ajax.Autocompleter("txtUserId", "autoUser", "scripts/user_auto.php", {paramName: "caracteres"});

//ocultando campos que no son llave primaria y boton borrar
$("divContent").className="oculto";
$("btnBorrar").className="oculto";

}

function cargarProveedores(proveedores){
var opcion = new Option("Seleccione proveedor", "");
$("lstDestVendor").options[$("lstDestVendor").options.length] = opcion;

	for (var i=0; i<proveedores.length; i++){
	var proveedor = proveedores[i];
	var opcion = new Option(proveedor.desc, proveedor.id);

		try {
		$("lstDestVendor").options[$("lstDestVendor").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}

function cargarairl(airls){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("lstUserBranch").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("lstUserBranch").options[$("lstUserBranch").options.length] = opcion;

	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("lstUserBranch").options[$("lstUserBranch").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}



//funcion que verifica si existe un registro que tenga como llave el valor capturado
//hace una peticion que devuelve un único valor
function existe() {
		
		$Ajax("scripts/branch.php", {onfinish: cargarairl, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
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
		if ($("txtUserId").value!=""){
		var url = "scripts/existe.php?keys=1&table=user_mstr&field1=user_id&f1value=" + $("txtUserId").value;
		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
		}
	}
//esta funcion recibe el valor de la función anterior y lo evalúa	
	function next_existe(ex){
		
		//agregamos un manejador de evento al boton cancelar
		$("btnCancelar").onclick=function(){
		
		//ocultando campos que no sean llave
		$("divContent").className="oculto";
		$("txtUserId").disabled=false;
		$("btnGo").disabled=false;
		$("txtUserId").value = "";
		
		};
		//deshabilitamos la llave primaria y el boton btnGo
		$("txtUserId").disabled=true;
		$("btnGo").disabled=true;
		//extraemos el valor retornado por el servidor en un objeto json
		var exx=ex[0];
		var exists = exx.existe;
		//si el valor es mayor que cero, entonces el registro existe
		if (exists > 0){
			if(!confirm("Desea actualizar tambien el password?")){$("txtUserPswd").disabled = true; $("txtUserPswd2").disabled = true; upswd = 0;}else{$("txtUserPswd").disabled = false; $("txtUserPswd2").disabled = false;}
			//alert(upswd);
			
			//se piden los datos
			var url2 = "scripts/campos_user.php?user_id="+$("txtUserId").value;
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
			$("txtUserPswd").disabled = false;
			$("txtUserPswd2").disabled = false;
			var tmp = $("txtUserId").value;
			$("form1").reset();
			$("txtUserId").value = tmp;
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
              var valores = "user_id="		+$("txtUserId").value		+"&user_name="		+$("txtUserName").value		+"&user_pswd="		+$("txtUserPswd").value;
	valores = valores + "&user_type="	+$("lstUserType").value		+"&user_branch="	+$("lstUserBranch").value	+"&user_add="	+$("txtUserAdd").value;
valores = valores + "&user_phone="		+$("txtUserPhone").value	+"&user_email="		+$("txtUserEmail").value;
	
		//var valores = $("form1").serialize();
		$Ajax("scripts/inuser.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		$("divContent").className="oculto";
		}
		
		}
		
		function actualizar(){
		if (allgood()){
		 var valores = "user_id="		+$("txtUserId").value		+"&user_name="		+$("txtUserName").value		+"&user_pswd="		+$("txtUserPswd").value;
	valores = valores + "&user_type="	+$("lstUserType").value		+"&user_branch="	+$("lstUserBranch").value	+"&user_add="	+$("txtUserAdd").value;
valores = valores + "&user_phone="		+$("txtUserPhone").value	+"&user_email="		+$("txtUserEmail").value	+"&upswd="	+upswd;
		$Ajax("scripts/upuser.php", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
		
		
		$("divContent").className="oculto";
		}
		
			}
		
		function borrar(){
		if (confirm("Confirme que desea borrar")){
		
		var hid ="user_id=" + $("txtUserId").value;
		$Ajax("scripts/deluser.php", {metodo: $metodo.POST, onfinish: fin, parametros: hid, avisoCargando:"loading"});
		
		
		$("divContent").className="oculto";
		
		}
		
		
		}
		
		function carga_campos(campos){
		//tomamos el primer objeto del json, ya que siempre devolvera un unico registro
		var campo = campos[0];
		//asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
		$("txtUserName").value = campo.user_name;
		
		for(var idx = 0; idx < $("lstUserType").options.length; idx ++){
		if($("lstUserType").options[idx].value == campo.user_type){
			$("lstUserType").selectedIndex = idx;
			$("lstUserType").options[idx].selected=true;
			}
		}
		for(var idx1 = 0; idx1 < $("lstUserBranch").options.length; idx1 ++){
		if($("lstUserBranch").options[idx1].value == campo.user_branch){
			$("lstUserBranch").selectedIndex = idx1;
			$("lstUserBranch").options[idx1].selected=true;
			}
		}
		$("txtUserPswd").value = "";
		$("txtUserPswd2").value = "";
		$("txtUserAdd").value = campo.user_add;
		$("txtUserPhone").value = campo.user_phone;
		$("txtUserEmail").value = campo.user_email;
		
		
		


		//imprimimos un mensaje de actualizando
		$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
		}

		function fin(res){
		alert(res);
		$("divContent").className="oculto";
		$("txtUserId").disabled=false;
		
		$("btnGo").disabled=false;
		$("txtUserId").value = "";
		
		$("status").innerHTML="";
		upswd = 1;
		}
		
		function allgood(){
		var notGood = 0;
		if($("txtUserName").value.length < 3){$("txtUserName").className += " invalid"; notGood ++;} else{$("txtUserName").className = "campo";}
		
		if(upswd == 1){
		if($("txtUserPswd").value.length < 3){$("txtUserPswd").className += " invalid"; notGood ++;} else{$("txtUserPswd").className = "campo";}
		if($("txtUserPswd2").value.length < 3){$("txtUserPswd2").className += " invalid"; notGood ++;} else{$("txtUserPswd2").className = "campo";}
		if($("txtUserPswd").value != $("txtUserPswd2").value){alert("las contraseñas no coinciden"); notGood ++;}
		}
		
		if($("lstUserType").value == ""){$("lstUserType").className += " invalid"; notGood ++;} else {$("lstUserType").className += "campo";}
		if($("lstUserBranch").value == ""){$("lstUserBranch").className += " invalid"; notGood ++;} else {$("lstUserBranch").className += "campo";}
		
		
		
		if(notGood > 0){return false;} else{return true;}
		}
		
		
		
		