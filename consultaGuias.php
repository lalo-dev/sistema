<?php
/**
 * @author miguel
 * @copyright 2009
 */
 	session_start();
if ((!isset($_SESSION["usuario_valido"]))||($_SESSION["permiso"]!="Cliente"))
{
    header("Location: login.php");
}
$usuario=$_SESSION["usuario_valido"];
$empresa=$_SESSION["cveEmpresa"];
$sucursal=$_SESSION["cveSucursal"];
$cliente =$_SESSION["cvecliente"];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <script type="text/javascript" src="scripts/ajaxConsulta.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/AjaxLib.js"></script>
    <script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
    <!--<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>-->
    <title>Consulta de guias</title>
</head>
<body>
 <div id="contenedor">
        <div id="encabezado">
            <img id="logotipo" src="imagenes/e-webfac.jpg" alt="e-webfac" height="45" width="120"/>
            <div id="menuayuda">
              <a href='logout.php' title="Salir" style="cursor:pointer">Salir</a>
      </div>
            <img id="cabecera" src="imagenes/Cabecera.jpg" alt="cabecera" height="15" width="944"/>
        </div>
        <div id="cuerpo">
<div id="derecho">
                <div class="cabecera">
                    <div id="ruta">
                    	Consulta  de Guias
                    	<a href="impresionCliente.php"><input type="button" value="Impresi&oacute;n Gu&iacute;as" /></a>
                    </div>
                    <h2 id="titulo">Consulta  de Guias</h2>
                </div>
<form id = "form2">
  <span id="loading" style="display: none">Por favor espere...</span>
  <span id="aviso" style="display: none">Cargando...</span>
                    <span id="status"></span>
              <div align="center">
                        <fieldset>
                            <legend>Datos generales</legend>
                            <table width="724">                            
                                <tr>	
                                    <td>No Guia:  
                                    	<input type="hidden" id="hdnCliente" value="<?php echo $cliente; ?>" />       
                                       <input id="wbNbr" type='text' value='' size="15" />
    	                                <input type = "checkbox" id = "one" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    	De Guia:
	                                    <input id = "from" type='text' value='' size="15" />
    	                                Hasta:
        	                            <input id = "to" type='text' value='' size="15" />
            	                        <input type = "checkbox" id = "fromTo" />       				
                	                    <input class = "button" type="button" name="btnGo" id="btnGo" value= "Continuar" />							
					<table>
						<tr>
							<td>
								<label class="letrero">(Los n&uacute;meros de gu&iacute;a son considerados como letras, es decir, en un rango de 1-200, la gu&iacute;a '22' no ser&iacute;a considerada)</label>	
							</td>
						</tr>
					</table>
                                     </td>
                                </tr>
                            </table>	                            
                      <p align="center">CALLE ORIENTE 162 No. 337, COL. MOCTEZUMA 2da. SECCION, C.P. 15530, MEXICO, D.F.</p>
                            <p align="center">TEL. 5571-7755 MULTILINEA, FAX. Ext. 102, E-MAIL: cye@cargayexpress.com.mx</p>
                            <p align="center">WEB: <span class="Estilo19">www.cargayexpress.com.mx</span></p>
                         </fieldset>
                    </div>
                    <div id = "divContent" align = "center">                    
                    </div>
                </form> 

</body>
</html>
