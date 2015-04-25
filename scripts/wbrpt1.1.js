window.onload = function(){
$Ajax("scripts/branch.php", {onfinish: cargarairl, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
$Ajax("scripts/branch.php", {onfinish: cargarairl1, tipoRespuesta: $tipo.JSON, avisoCargando:"loading"});
var campos = document.getElementsByClassName("field");
for(var i = 0; i < 6; i++){
	$("field"+i).onfocus = function(){
		this.value = "";
	}
}
}

function cargarairl(airls){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("lstDestFrom").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("De destino", "");
$("lstDestFrom").options[$("lstDestFrom").options.length] = opcion;

	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("lstDestFrom").options[$("lstDestFrom").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}
function cargarairl1(airls){
// borrando lista anterior para que la pagina no necesite refrescar si se agregaron nuevos elementos a la bd
$("lstDestTo").options.length = 0;
//empieza la carga de la lista
var opcion = new Option("Hasta", "");
$("lstDestTo").options[$("lstDestTo").options.length] = opcion;

	for (var i=0; i<airls.length; i++){
	var airl = airls[i];
	var opcion = new Option(airl.desc, airl.id);

		try {
		$("lstDestTo").options[$("lstDestTo").options.length]=opcion;
 }catch (e){alert("Error interno");}
	}
	
	}