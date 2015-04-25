window.onload = inicia;

function inicia() {
		$("btnEnviar").onclick=imprimir;
}
    
function imprimir(){			
	var win = new Window({className: "mac_os_x", title: "Reporte de Operaciones", top:70, left:100, width:1200, height:500, url: "scripts/administraOperaciones.php?fechaI="+$('txtFechaI').value+"&fechaF="+$('txtFechaF').value, showEffectOptions: {duration:1.5}});
	 win.show(); 		
}