<?php
/**
 * @author miguel
 * @copyright 2010
 */
require_once("direccionamiento.php");
if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario")&& ($_SESSION["permiso"]!="Facturacion")))
{
    header("Location: login.php");
}
$dato=$_GET['dato'];

if($dato=='cliente')
{
    $etqRuta='Clientes';
    $etqNombre='Cliente';
}
else
{
    $etqRuta='Corresponsales';
    $etqNombre='Corresponsal';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="scripts/ajaxPagos.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
<script type="text/javascript" src="scripts/window.js"> </script>
<link href="themes/default.css" rel="stylesheet" type="text/css"/> 
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
<title>EWebFac - Pagos</title>
<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div id="contenedor">
	<?php
        include_once ($menu);
    ?>
    <form id= "form2" name="form2" action="reporteprueva.php" method="post">
        <div id="completo">
            <div class="cabecera">
                <div id="ruta">Cuentas por Cobrar | Pagos <?php echo $etqRuta; ?>  </div>
                <h2 id="titulo">Pagos <?php echo $etqNombre;?></h2>
            </div><br />
            <div class="izquierda">
                <span id="loading" style="display: none">Por favor espere...</span>
                <span id="aviso" style="display: none">aver aque horas...</span>
                <span id="status"></span>
            </div>
            <br /><br />
            <fieldset>
                <legend>Datos Generales</legend>
                <table>
                    <tr>
                        <td>C&oacute;digo del <?php echo $etqNombre; ?></td>
                        <td>
                            <input name="txtCodigoCliente" type="text" id="txtCodigoCliente" size="60"  /><input type="hidden" name="hdnTabla" id="hdnTabla" value="<?php echo $dato; ?>" />
                             <div id="autoClienteC"></div>
                        </td>
                        <td colspan="2">                       
                        </td>
                    </tr>
                    <tr>
                        <td>Raz&oacute;n Social</td>
                        <td colspan="3"> 
                            <input name="txtRazonSocial" type="text" id="txtRazonSocial" onkeyup="this.value=this.value.toUpperCase();" size="60" />  
                             <div id="autoClienteR"></div>                              
                        </td>
                    </tr>
                    <tr>
                        <td>Monto del Pago</td>
                        <td colspan="2" ><input name="txtMonto" type="text" id="txtMonto"  /></td>
                    </tr>
                    <tr>
                        <td>Moneda</td>
                        <td>
                            <select name="slcMoneda" id="slcMoneda">
                            </select>
                        </td>
                        <td>Tipo de Pago</td>
                        <td>
                            <select name="slcTipoPago" id="slcTipoPago" >
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Banco</td>
                        <td>
                            <select name="slcBanco" id="slcBanco" >	
                            </select>
                        </td>
                        <td>No. de Documento</td>
                        <td><input name="txtDocumento" type="text" id="txtDocumento" /></td>
                    </tr>
                </table>       
            </fieldset>
        </div>
        <div class="controles">
            <table>
                <tr>
                    <td>
                        <input type="button" id="btnAplicar" name="btnAplicar" value="Aplicar" />
                        <input type="hidden" id="hdnContador" name="hdnContador" value="" />
                    </td>           
                    <td>
                        <input type="button" id="btnCancelar" name="btnCancelar" value="Cancelar" />
                    </td>            
                </tr>
            </table>
        </div>
    </form>
    <div id="datos" class="oculto">
        <table class="gridView" cellspacing="2" width="700" border="1" id="gvwFacturas">
            <tr>
                <th colspan="7">Detalle de Pagos</th>
            </tr>
            <tr>
                <th align="center">Fecha</th>
                <th align="center">Documento</th>
                <th align="center">Tipo de Doc</th>
                <th align="center">Monto Del Doc</th>
                <th align="center">Moneda</th>
                <th align="center">Saldo</th>
                <th align="center">Pago</th>
            </tr>
        </table>
    </div>		
  </div>
</body>
</html>
