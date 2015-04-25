//Documento javascript
function imprimirCatalogo(opc,titulo)
{
	var win = new Window({className: "mac_os_x", title: "Reporte de "+titulo, top:70, left:100, width:1200, height:500, url: "scripts/imprCatalogos.php?opc="+opc, showEffectOptions: {duration:1.5}});
	win.show();
}

function InsertaNuevaLinea(sender,e,evento){

	//if(event.keyCode != 13 || Trim(sender.value) == '')return;
	k = (document.all) ? evento.keyCode : evento.which;
	
	if(k == 39){

		var textArea01 = document.getElementById(e);
		
		if(textArea01.value != '')
		{				
			textArea01.value += ',';
		}
		
		textArea01.value += Trim(sender.value);
		textArea01.scrollTop=100;
		sender.value='';
	}
}

function Trim(cadena){
	var len = cadena.length;
	var index = Number();
	var resultado = String();
	
	for(index=0; index<len; index++){
		if(cadena.substring(index,index+1) != ' ' && resultado == ''){
			resultado += cadena.substring(index,len);
			break;
		}
	}
	
	len = resultado.length;
	while(resultado.substring(len-1,len) == ' '){
		resultado = resultado.substring(0,len-1);
		len = resultado.length;
	}
	return resultado;
}
function quitar_invalidos()
{
	elementos=$$("#form2 input[type=text],#form2 select,#form2 textarea,#form2 input[type=password]");
    for(i=0;i<elementos.length;i++)
	{ 	
		id=elementos[i].id; 
		if(id!="totalReg"){
			$(id).removeClassName("invalid");
			$(id).value=""; 
		}
	}
}
function cargarTotales(valores)
{
	valor=valores[0];
	$("totalReg").value="TOTAL: "+valor.total_reg;
}
function cargarTotales2(valores)
{
	valor=valores[0];
	$("totalReg").value="Gu\u00EDas faltantes de fac.: "+valor.total_reg;
	document.getElementById("totalReg").style.fontSize='14px';
	document.getElementById("totalReg").style.textAlign='left';
	document.getElementById("totalReg").style.width='200px';

}

function reset_form()
{
	inputs=$$('input[type=text]');
	selects=$$('select');
	for(i=0;i<inputs.length;i++)
	{  elemento=document.getElementById(inputs[i].id);	elemento.style.backgroundColor="#FFFFFF"; }
	for(i=0;i<selects.length;i++)
	{  elemento=document.getElementById(selects[i].id);	elemento.style.backgroundColor="#FFFFFF"; }
}
function chk_val(id,evento)
{

	var elemento=document.getElementById(id);
	//Se obtiene código de tecla presionada
	k = (document.all) ? evento.keyCode : evento.which;
	longitud=elemento.value.length;
	/*Serán excepciones:
	8:backspace 9:tabulador 13:enter 16:shift 17:ctrl 18:alt 20:capslock 35:fin 36:home 37:<- 39:-> 46:delete 144:num lock*/
	if( (k==8)||(k==9)||(k==16)||(k==17)||(k==18)||(k==20)||(k==35)||(k==36)||(k==37)||(k==39)||(k==46)||(k==110)||(k==188)||(k==190)||(k==144)||(k>=48 && k<=57)||(k>=96 && k<=105) )
	{ return true;  }
	else 
	{ return false;	}
}

function chk_num(id,evento)
{
	var elemento=document.getElementById(id);
	//Se obtiene código de tecla presionada
	k = (document.all) ? evento.keyCode : evento.which;
	longitud=elemento.value.length;
	/*Serán excepciones:
	8:backspace 9:tabulador 13:enter 16:shift 17:ctrl 18:alt 20:capslock 35:fin 36:home 37:<- 39:-> 46:delete 144:num lock*/
	if( (k==8)||(k==9)||(k==16)||(k==17)||(k==18)||(k==20)||(k==35)||(k==36)||(k==37)||(k==39)||(k==46)||(k==110)||(k==188)||(k==190)||(k==144)||(k>=48 && k<=57))
	{ 	return true; }
	else { return false; }
}
//Cambia la Leyenda de Activar/Desactivar según valor del check en Aerolineas
function Cambia(valor)
{
	caja=document.getElementById('lblActivado');
	if(valor) caja.value="Activar";
	else	  caja.value="Desactivar";
}
/*
Valida que la propiedad value del elemento no se encuentre vacía.
    La función regresa verdadero si su propiedad value es diferente de nada y falso si es vacía.
    */
function ValidarCampoTexto(argElemento,argMensaje){
    if(argElemento.value == ""){
        alert(argMensaje);
        argElemento.focus();
        return false;
    }
    return true;
}

/*
Valida que la propiedad checked del elemento sea verdadera.
    Si esta es falsa, la función regresa un valor boleano negativo.
    */
function ValidarCheckbox(argElemento,argMensaje){
    if(!argElemento.checked){
        alert(argMensaje);
        argElemento.focus();
        return false;
    }
    return true;
}

/*
Valida que la propiedad checked de cualquier elemento del grupo argElementos sea verdadera,
    de no ser así, la función devuelve un valor falso.
    */
function ValidarGrupo(argElementos,argMensaje){
    var indice = Number(0);
    var longitud = argElementos.length;
    
    for(indice=0; indice<longitud; indice++){
        if(argElementos[indice].checked)return true;
    }
    
    alert(argMensaje);
    argElementos[0].focus();
    return false;
}

/*
Si el argumento argMensaje tiene valor se muestra al usuario 
    un cuadro de aviso con esta información.
    */
//function MuestraAviso(argMensaje){
//    if(argMensaje!="")alert(argMensaje);
//}

/*
Esta función está diseñada para abrir popups que se comportan de la misma manera 
    que la pantalla de BuscadorClientes.aspx
    
    argOpenerUrl: Es la dirección de la ventana principal.
    argOpenerParams: Son los parámetros con los que trabaja actualmente la pantalla principal.
    argPopUpUrl: Es la dirección del popup.
    argPupUpNombre: El nombre del popup para evitar más instancias.
    argFlgByGetUrl: Es verdadera si la función debe regresar los resultados por URL.
    argIdElements: En el caso de que el argumento argFlgByGetUrl sea falso la función enviará los id de los elementos que necesita modificar el popup.
*/
function AbrirPoUp(argOpenerUrl,argOpenerParams,argPopUpUrl,argPupUpNombre,argFlgByGetUrl,argIdElements){
    var popup = Object();
    var top = (screen.height - 200) / 2;
    var left = (screen.width - 300) / 2;
    var features = "width=300,height=200,scrollbars=1,resizable=1,menubar=0,status=0,directories=0,location=0,toolbar=0,left=" + left + ",top=" + top;
    var nombre = argPupUpNombre;
    var params = String();
    var popupurl = String();
    
    argOpenerUrl = escape(argOpenerUrl);
    argOpenerParams = escape(argOpenerParams);
    
    params = "openerurl=" + argOpenerUrl + "&openerparams=" + argOpenerParams + "&bygeturl=" + argFlgByGetUrl + "&idelements=" + argIdElements;
    popupurl = argPopUpUrl + "?" + params;
    
    popup = window.open(popupurl, nombre, features);
    popup.focus();
}
/*
Validación de campo de teléfono y fax.
*/
function ValidarTelefono(textbox,argMaxLen) {
    var codenum;
    var currentChar;
    var maxLen = (!argMaxLen) ? 24 : argMaxLen;
    var valor = String();
    var numberCheckPattern = /\d/;
    
    codenum = (document.all) ? event.keyCode : event.which;
    currentChar = String.fromCharCode(codenum);
    
    if(!numberCheckPattern.test(currentChar))return false;
    
    valor = textbox.value + currentChar;
    
    textbox.value = FormatoTelefono(valor,maxLen);
    setCaretPosition(textbox,textbox.value.length);
    return false;
}

function FormatoTelefono(valor,argMaxLen){
    var numberCheckPattern = /\d/;
    var arrChars = valor.split(".");
    var indice = Number(0);
    var resultado = String();
    var lastChars = String();
    var posicion = Number(0);
    var maxLen = (!argMaxLen) ? 24 : argMaxLen;
    
    for(indice=0; indice<arrChars.length; indice++){
        lastChars += arrChars[indice];
    }
    
    for(indice=0; indice<lastChars.length; indice++){
        if(numberCheckPattern.test(lastChars.charAt(indice))){
            if(posicion > 0 && posicion%4 == 0)resultado += ".";
            resultado += lastChars.charAt(indice);
            posicion++;
        }
    }
    
    return resultado.substring(0,maxLen);
}

/*
    Sólo permite la captura de números.
*/
function SoloNumeros(textbox) {
    var codenum;
    var currentChar;
    var numberCheckPattern = /\d/;
    
    codenum = (document.all) ? event.keyCode : event.which;
    
    currentChar = String.fromCharCode(codenum);

    if(!numberCheckPattern.test(currentChar))return false;
}

/*
    Quita los caracteres alfabéticos.
*/
function EliminarTexto(valor,argMaxLen){
    var numberCheckPattern = /\d/;
    var indice = Number(0);
    var resultado = String();
    var maxLen = (!argMaxLen) ? 24 : argMaxLen;
    
    for(indice=0; indice<valor.length; indice++){
        if(numberCheckPattern.test(valor.charAt(indice))){
            resultado += valor.charAt(indice);
        }
    }
    
    return resultado.substring(0,maxLen);
}

/*
    Seleccionar rango de texto.
*/
function setCaretPosition(elem, caretPos) {

    if(elem != null) {
        if(elem.createTextRange) {
            var range = elem.createTextRange();
            range.move('character', caretPos);  //El tipo de unidades a mover pueden ser: character, word, sentence, textedit.
            range.select();
        }
        else {
            if(elem.selectionStart) {
                elem.focus();
                elem.setSelectionRange(caretPos, caretPos);
            }
            else
                elem.focus();
        }
    }
}

