window.onload = inicia;

function inicia() {

var respuesta= "scripts/estados.php?pais=156";
$Ajax(respuesta, {onfinish: cargaEstados, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
$("btnCancelar").onclick=function(){
        	$("btnModificar").disabled=false;
            $("txtNombredo").disabled=false;
            $("btnGuardar").disabled=false;
            $("status").innerHTML="";
		$("form2").reset();
		};
        	$("txtNombredo").onchange=traerdatos;
        	
}
function cargaEstados(estados){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("txtNombredo").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Seleccione", "");
$("txtNombredo").options[$("txtNombredo").options.length] = opcion;

	for (var i=0; i<estados.length; i++){
	var estado = estados[i];
	var opcion = new Option(estado.desc, estado.id);

		try {
		$("txtNombredo").options[$("txtNombredo").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}

	}
 function traerdatos(){
    var url="scripts/catalogoGeneral.php?operacion=3&tabla=cmunicipios&campo=nombre&campo2=cveEntidadFederativa&valor2="+$("txtNombredo").value;
$("divDescripcion").className = "autocomplete";
new Ajax.Autocompleter("txtDescripcion", "divDescripcion", url, {paramName: "caracteres",afterUpdateElement:existe});
$("txtDescripcion").onchange=existe;	
 }
function existe() {
	
		if ($("txtDescripcion").value!=""){
    		var url = "scripts/existe.php?keys=2&table=cmunicipios&field1=nombre&f1value=" + $("txtDescripcion").value+"&field2=cveEntidadFederativa&f2value=" + $("txtNombredo").value;
    		$Ajax(url, {onfinish: next_existe, tipoRespuesta: $tipo.JSON});
		}
	}
function insertar(){
			
		if(allgood()){
		 var valores = valores + "&txtDescripcion="		+$("txtDescripcion").value		+"&txtNombredo="		+$("txtNombredo").value	+"&txtCodigoP="+$("txtCodigoP").value;
         var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
valores=valores +usuario;   
            $Ajax("scripts/administraMunicipios.php?operacion=1", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
							
		}
		
		}
		
		function actualizar(){
			if(allgood()){
			    
		 var valores = valores + "&hdnClave="		+$("hdnClave").value		+"&txtDescripcion="		+$("txtDescripcion").value		+"&txtNombredo="		+$("txtNombredo").value+"&txtCodigoP="+$("txtCodigoP").value;
		var usuario="&usuario="+$("hdnUsuario").value  +  "&empresa="+$("hdnEmpresaS").value;	
valores=valores +usuario;
        $Ajax("scripts/administraMunicipios.php?operacion=2", {metodo: $metodo.POST, onfinish: fin, parametros: valores, avisoCargando:"loading"});
				
		}
		
			}
		
	
		function llenaDatos(campos){
	
		//tomamos el primer objeto del json, ya que siempre devolvera un unico registro
			var campo = campos[0];
		//asignamos los valores a los campos del formulario para que el usuario pueda visualizarlos
       	$("hdnClave").value = campo.cveMunicipio;
		$("txtDescripcion").value = campo.nombre;
        $("txtCodigoP").value=campo.codigoPostal;
		$("txtNombredo").value = campo.cveEntidadFederativa;	
       	$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
		}

		function fin(res){
		alert(res);
		//$("contenedor").className="oculto";				
		$("form2").reset();
			$("txtNombredo").disabled=false;		
		$("status").innerHTML="";
		
		}
		
		function allgood(){
		var notGood = 0;		
		
		if(notGood > 0){
			alert("Hay Informacion erronea que ha sido resaltada en color!");
			return false;
			} else
			{
				return true;
			}
			
		}
	function next_existe(ex){
		$("txtNombredo").disabled=true;
		//extraemos el valor retornado por el servidor en un objeto json
		var exx=ex[0];
		var exists = exx.existe;
		//si el valor es mayor que cero, entonces el registro existe
		if (exists > 0){			
		
			$Ajax("scripts/datosGenerales.php?operacion=3&valor="+	$("txtDescripcion").value, {onfinish: llenaDatos, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
			//cambiamos el manejador del boton actualizar para que apunte a la funcion
			//que actualiza el registro
			$("btnModificar").disabled=false;
			$("btnModificar").onclick=actualizar;
			//agregamos un manejado de evento al boton borrar para que llame a su funcion borrar
			//$("btnBorrar").onclick=borrar;
			//mostramos el boton de borrar
			//el boton guardar no es útil aqui, por lo tanto lo ocultamos
			$("btnGuardar").disabled=true;
			//imprimimos un mensaje de actualizando
			$("status").innerHTML="<label class='message' for='element_3'>Actualizando registro</label>";
		}
		else{	//si la funcion devolvio cero, no existe el registro
			//limpiamos los campos del form	
            $("btnGuardar").onclick=insertar;
			//imprimimos un aviso de que se trata de un registro nuevo
			$("status").innerHTML="<label class='message' for='element_3'>Agregando nuevo registro</label>";
			//el boton borrar y modificar no es útil aqui, por lo tanto lo ocultamos
			$("btnModificar").disabled=true;
			}
		}
			