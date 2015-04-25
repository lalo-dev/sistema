<?php

	/**
	 * @author miguel
	 * @copyright 2009
	 */
	require_once("direccionamiento.php");
	if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario") && ($_SESSION["permiso"]!="Guias")))
	{
		header("Location: login.php");
	}
	$usuario=$_SESSION["usuario_valido"];
	$empresa=$_SESSION["cveEmpresa"];
	$sucursal=$_SESSION["cveSucursal"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="scripts/ajaxAcuses.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
<script type="text/javascript" src="scripts/window.js"> </script>
<link href="themes/default.css" rel="stylesheet" type="text/css"/> <!-- Add this to have a specific theme--> <link href="themes/mac_os_x.css" rel="stylesheet" type="text/css"/> 
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Reporte de Acuses</title>
<link href="estilos/MenuGeneral.css" rel="stylesheet" type="text/css" />
<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
<!--Calendario-->
<link rel="stylesheet" href="estilos/calendar.css" type="text/css"/>
<script type="text/javascript" language="JavaScript" src="jscripts/calendar_es2.js"></script>

</head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
         
    <div id="derecho">
        <div class="cabecera">
            <div id="ruta">Control de Env&iacute;os | Acuses</div>
            <h2 id="titulo">Acuses</h2>
        </div> <br />             
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
            <span id="aviso" style="display: none">Cargando...</span>
            <span id="status"></span>
        </div>
    </div>  <br /><br />
    <div id="pnlDatosGenerales">
        <form id= "form2" name="form2" action="reportepdf.php" method="post">              
            <fieldset>
                <legend>Cliente</legend>
                <table>
                    <tr>
                        <td>Razon Social </td>
                        <td>
                            <input type="text" id="txtRazonS" name="txtRazonS" size="50" maxlength="50" value = "Razon Social" onclick = "value = ''" class="mayuscula"/>
                            <input type="hidden" name="hdncveCliente" id="hdncveCliente" />
                            <div id="autoCliente" ></div>
                        </td>
                    </tr>
                </table>		
            </fieldset>  
            &nbsp;&nbsp;&nbsp;&nbsp;                  
            <fieldset class="folios">
                <legend>Folio</legend>
                <table>	
                    <tr>
                        <td>Folio:</td> 
                        <td>
                            <input type="text"  name="txtFolio" id="txtFolio" />
                            <div id="autoFolio" ></div>
                            <input type="hidden" value="" id="hdnContador" name="hdnContador" />
                            <input type="hidden" value="" id="hdnContadorv" name="hdnContadorv" />
                            <input type="hidden" value="" id="hdnCompletas" name="hdnCompletas" />
                            <input type="hidden" value="" id="hdnPartes" name="hdnPartes" />
                            <input type="hidden" value="" id="hdncveDireccion" name="hdncveDireccion" />
                            <input type="hidden" value="0" id="hdnFolios" name="hdnFolios" />
                        </td>
                  </tr>
                </table>
            </fieldset> 
            &nbsp;&nbsp;&nbsp;&nbsp;
	    <!--[if IE]> 
	    	<br />
	    <![endif]--> 
	    <fieldset class="acuses">
		<legend>Fecha Acuse</legend>
		<table>
		    <tr>
		        <td>Fecha:</td> 
		        <td>
	  			<input name="txtFechadelAcuse" type="text" id="txtFechadelAcuse" class="calendar" size="10" />
				<script type="text/javascript" language="JavaScript"> new tcal ({'controlname': 'txtFechadelAcuse'}); </script>
		        </td>
		  </tr>
		</table>
	    </fieldset>
	    <div class="infoGuia" id="divInfoGuiaAc">
	    </div>         
            <div align="center">	
              <fieldset>
                    <legend>Acuses</legend>	
                    <table class="gridView" align="center">
                        <tr>
                            <th class="th">Fecha</th>											
                            <th>Guia</th>
                            <th>Destino</th>
                            <th>Recibio</th>
                            <th>Folio / Entrega / Factura</th>
                        </tr>
                        <tr>	
                            <td><input name="txtFecha" type="text" id="txtFecha"  size="10" readonly="readonly" /></td>
                            <td><input type="text" id="txtGuia" value = "Guia" onclick = "value = ''" size="6" /><div id="autoGuia"></div></td>
                            <td><input type="text" id="txtDestino" readonly="readonly"/></td>
                            <td><input type="text" id="txtRecibio" maxlength="120" size="52" readonly="readonly" /></td>
                            <td><input type="text" id="txtFacturas" maxlength="120" size="52" readonly="readonly"/></td>
                        </tr>                                      					
                    </table>		
                </fieldset>
            </div>
            <div align="center" style="width: 950px;"> 
                <input type="button" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="cancelar('1');" />
                <input type="button" name="btnAgregar" id="btnAgregar" value="Agregar Guia" />
                <input type="button" name="bntModificar" id="bntModificar" value="Modificar Acuse" onclick="modificarAcuse();"/>
                <input type="button" name="btnGuardar" id="btnGuardar" value="Guardar Acuse" />
                <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir Acuse" />
            </div>	
            <div align="center" id="visible" class="oculto">	
                <fieldset>
                    <legend>Acuses Agregados</legend>	
                    <table class="gridView" align="center" id="tablaFormulario">
                        <tr>
                            <th class="th">Fecha</th>											
                            <th>Guia</th>
                            <th>Destino</th>
                            <th>Recibio</th>
                            <th>Folio / Entrega / Factura</th>
                            <th>Editar</th>
                            <th>Borrar</th>
                        </tr>  
                    </table>		
                </fieldset>
            </div>			
		</form>
     </div>
</div>
</body>
</html>
