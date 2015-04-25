
function imprimir(){
	if(allgood()){
		var valor=document.getElementById("txthAccion").value;
		var permitidos=(valor==0)?31:33;
		fechaIn=valoresI[2]+"-"+valoresI[1]+"-"+valoresI[0];
		fechaFin=valoresF[2]+"-"+valoresF[1]+"-"+valoresF[0];
		var url="scripts/usoFunciones.php?opc=1&fechaI="+fechaIn+"&fechaF="+fechaFin;
		$Ajax(url, {tipoRespuesta: $tipo.JSON,onfinish: function(datos)
							{							
								if(datos[0].total>permitidos)
								{
									alert("NO puede elegir un periodo mayor a "+permitidos+" d\u00EDas.")
								}
								else{
									if(valor==0){
									var win = new Window({className: "mac_os_x", title: "Reporte de Ventas por Cliente", top:70, left:100, width:1200, height:500, url: "scripts/reporteVentas.php?fechaI="+$('txtFechaI').value+"&fechaF="+$('txtFechaF').value, showEffectOptions: {duration:.5}});
								}
									else{
									var win = new Window({className: "mac_os_x", title: "Reporte de Ventas por Destino", top:70, left:100, width:1200, height:500, url: "scripts/reporteDestinos.php?fechaI="+$('txtFechaI').value+"&fechaF="+$('txtFechaF').value, showEffectOptions: {duration:.5}});
									}
									win.show();
								}
							}
		});
	}
}

function allgood()
{
	//Checar que las fechas ya se de Entrega o la de Acuse esten correctas
	var expresion = /^\s*(\d{2,2})\/(\d{2,2})\/(\d{4,4})\s*$/;
	if($("txtFechaI").value=="" || $("txtFechaF").value=="")
	{
		alert("Debe ingresar la fecha Incial y Final."); 
		return false;
	}
	else
	{

		//Obtener datos de la fecha
		valoresI=$('txtFechaI').value.split("/");
		valoresF=$('txtFechaF').value.split("/");
		if(!($("txtFechaI").value.match(expresion)))
			{$("txtFechaI").addClassName("invalid"); alert("El formato de la fecha Incial es incorrecto (dd/mm/yyyy)."); return false;} 
		else
		{
			$("txtFechaI").addClassName("invalid");
			if (valoresI[1] < 1 || valoresI[1] > 12){
				alert ("Valor de mes no v\u00E1lido: '" + valoresI[1] + "'.\nEl rango permitido es de 01 a 12.");
				return false;
			}
			else{
				var d_numdays = new Date(valoresI[2], valoresI[1], 0);
				if (valoresI[0] > d_numdays.getDate()){
					alert("D\u00EDa de mes no v\u00E1lido: '" + valoresI[0] + "'.\nEl rango permitido para el mes seleccionado es de 01 a " + d_numdays.getDate() + ".");
					return false;
				}
				else
					$("txtFechaI").removeClassName("invalid");
			}
		}
		
		if(!($("txtFechaF").value.match(expresion)))
			{$("txtFechaF").addClassName("invalid"); alert("El formato de la fecha Final es incorrecto (dd/mm/yyyy)."); return false;} 
		else
		{
			$("txtFechaF").addClassName("invalid");

			if (valoresF[1] < 1 || valoresF[1] > 12){
				alert ("Valor de mes no v\u00E1lido: '" + valoresI[1] + "'.\nEl rango permitido es de 01 a 12.");
				return false;
			}
			else{
				var d_numdays = new Date(valoresF[2], valoresF[1], 0);
				if (valoresF[0] > d_numdays.getDate()){
					alert("D\u00EDa de mes no v\u00E1lido: '" + valoresF[0] + "'.\nEl rango permitido para el mes seleccionado es de 01 a " + d_numdays.getDate() + ".");
					return false;
				}
				else
					$("txtFechaF").removeClassName("invalid");
			}
		}
		
		
		//Checar que la fecha de 2 no sea inferior a la 1
		valores=$('txtFechaI').value.split("/");
		inicio = new Date(valores[2],valores[1],valores[0]).getTime();
		
		valores=$('txtFechaF').value.split("/");
		fin = new Date(valores[2],valores[1],valores[0]).getTime();
		if(fin<inicio)
		{
			alert("La fecha final es mayor a la incial.");
			return false;				
		}	
	}
	return true;
}

