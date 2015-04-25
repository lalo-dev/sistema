window.onload = inicia;

function inicia() {
	$("btnGo").onclick=consulta;//al hacer click se llama funcion para ver si el registro existe

	//ocultando campos que no son llave primaria y boton borrar
	//$("divContent").className="oculto";
	$("from").disabled = true;
	$("to").disabled = true;
	$("one").checked = true;
	$("one").className = "oculto";

	$("fromTo").onclick = function(){
		if($("fromTo").checked == true){
			$("wbNbr").disabled = true;
			$("from").disabled = false;
			$("to").disabled = false;
			$("one").checked = false;
			$("one").className = "";
			$("fromTo").className = "oculto";
		}
	}

	$("one").onclick = function(){
		if($("one").checked == true){
			$("from").disabled = true;
			$("to").disabled = true;
			$("wbNbr").disabled = false;
			$("one").className = "oculto";
			$("fromTo").checked = false;
			$("fromTo"). className = "";
		
		}
   }

}

function consulta() {
		
		//Limpiar la tabla primero
		tabla=document.getElementById("contenido");
		if(tabla){
			if(document.getElementById("contenido").rows.length>1){	
				var ultima = document.getElementById("contenido").rows.length;			
				for(var j=ultima; j>=1; j--){				
							 document.getElementById("contenido").deleteRow(0);	
									
				}
			}	
		}

		var wrong = 0;
		if ($("one").checked == true){
			if($("wbNbr").value == "" || isNaN($("wbNbr").value)){wrong ++;}
				}
		else{
		if($("from").value == "" || $("to").value == "" || isNaN($("from").value) || isNaN($("to").value)){
			wrong ++;
			}
		}
		
		if(wrong == 0 && $("one").checked == true)
		{		
			var query ="SELECT cveGuia , recepcionCYE , nombreRemitente ,   piezas , kg , volumen , recibio , sello , fechaEntrega , status , firma,"+ 
			"cconsignatarios.nombre AS nombreDestinatario, cestados.nombre AS estadoDestinatario"+
			" FROM cguias"+
			" LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario"+
			" INNER JOIN cestados ON cconsignatarios.estado = cestados.cveEstado"+
			" WHERE  cveGuia = "+$("wbNbr").value+" AND cveCliente="+$("hdnCliente").value;

			var parametro = "query=" + query;

			$Ajax("scripts/consultaGuias.php?opc=0", {metodo: $metodo.POST, onfinish: carga_campos, parametros: parametro, avisoCargando:"loading"});
		}
		
		if(wrong == 0 && $("fromTo").checked == true){
		
			//Checar que el número del rango "hasta" sea mayor al de "de"
			rangoinicio=parseInt($("from").value);
			rangofin=parseInt($("to").value);

			if(rangofin<rangoinicio){
				alert("El rango final(hasta) es menor al rango inicial(de).");
				return; //NO necesario
			}
			else if(rangofin==rangoinicio)
			{
				alert("El rango final(hasta) es igual al rango inicial(de), puede emplear la primera opci\u00F3n en caso de que desee buscar \u00FAnicamente una gu\u00EDa.");
				return; //NO necesario
			}
			else{
				var query = "SELECT cveGuia , recepcionCYE , nombreRemitente ,   piezas , kg , volumen , recibio , sello , fechaEntrega , status , firma,"+ 
				"cconsignatarios.nombre AS nombreDestinatario, cestados.nombre AS estadoDestinatario"+
				" FROM cguias"+
				" LEFT JOIN cconsignatarios ON cguias.cveConsignatario = cconsignatarios.cveConsignatario"+
				" INNER JOIN cestados ON cconsignatarios.estado = cestados.cveEstado"+ 
				" WHERE cveGuia >= '"+$("from").value + "' AND cveGuia <= '" + $("to").value + "' AND cveCliente="+$("hdnCliente").value + " ORDER BY cveGuia";
				
								
				var parametro = "query=" + query;
	
				$Ajax("scripts/consultaGuias.php?opc=1", {metodo: $metodo.POST, onfinish: carga_campos, parametros: parametro, avisoCargando:"loading"});
			}

		}
	
}
		function carga_campos(respuesta){
		$("divContent").innerHTML = respuesta;
		
		}

		function fin(res){
		alert(res);
		$("divContent").className="oculto";
		//$("txtWbId").disabled=false;
		
		$("btnGo").disabled=false;
		//$("txtWbId").value = "";
		
		$("status").innerHTML="";
		
		}
		
		function allgood(){
		var notGood = 0;
		
				
						
		if($("lstStatus").value == "" || $("lstStatus").value == "ended"){$("lstStatus").addClassName("invalid"); notGood ++;} else {$("lstStatus").className = "";}
		
		if($("lstStatus").value == "dlvd" && ($("txtDlvdDate").value == "" || $("txtRcbdBy").value == "")){ notGood ++}
		
		if(($("txtDlvdDate").value != "" && $("txtRcbdBy").value == "") || ($("txtRcbdBy").value != "" && $("txtDlvdDate").value == "")){notGood ++;};
		
		if(notGood > 0){alert("Hay Informacion erronea");return false;} else{return true;}
		}
		
		
		
		
