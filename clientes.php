<?php

/**
 * @author miguel
 * @copyright 2009
 */
require_once("direccionamiento.php");
if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario")&& ($_SESSION["permiso"]!="Guias")&& ($_SESSION["permiso"]!="Facturacion")))
{
    header("Location: login.php");
}
include("scripts/bd.php");
$usuario=$_SESSION["usuario_valido"];
$empresa=$_SESSION["cveEmpresa"];
$sucursal=$_SESSION["cveSucursal"];
$razon = $_GET["razon"];
$cliente = $_GET["codigo"];
if($cliente==""){$cliente=0;}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/ajaxClientes.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxLib.js"></script>
<script type="text/javascript" src="jscripts/globalscripts.js"></script>
<script type="text/javascript" src="scripts/scriptaculous.js?load=builder,effects,controls"></script>
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
    <script language="javascript" type="text/javascript">
    function Enviar(){
		var numP=document.getElementById("txthPer").value;
		var cveCliente= document.getElementById("txtCodigo").value;
		var razon= document.getElementById("txtRazonSocial").value;		    	
        location = "direcciones.php?nP="+numP+"&razon=" + razon + "&cveCliente=" + cveCliente+"&tabla=cliente" ;
    }
    function nospaces(object) {
    
	   text = object.value;
	   object.value = object.value.replace("  "," ");
	   while (object.value.search(/(\r\n\r\n)|(\n\n)/) != -1) {
	    object.value = object.value.replace(/\r\n\r\n/g, " ");
	    object.value = object.value.replace(/\n\n/g, " ");
	    object.value = object.value.replace(/\r/g, " ");
	   }
   }


</script>
    <title>
	EWebFac - Clientes

</title></head>
<body>
 <div id="contenedor">
<?php
	if($menu!="")	
		require_once($menu);
?>
            <div id="derecho">
                <div class="cabecera">
                    <div id="ruta">Datos Base | Clientes</div>
                    <h2 id="titulo">Clientes</h2>
                </div><br /><br />
	</div>
  <form id="form2" name="form2">
                <div>
                	<span id="loading" style="display: none">Por favor espere...</span>
								<span id="aviso" style="display: none">Cargando...</span>
								<span id="status"></span>
   <div id="pnlDatosGenerales" >
	<fieldset>
		<legend>
			Datos Generales
		</legend>
                        <table border="0" cellpadding="2" cellspacing="2">
                           
                            <tr>
                                <td>*C&oacute;digo
				    <input type="hidden" id="hdnCliente" value="<?php echo $cliente; ?>" />
				</td>
				<td colspan="3">
                                    <div id="autoClienteC"></div>
                                    <input name="txtCodigo" type="text" maxlength="20" id="txtCodigo" size="65" onclick = "value = ''"/>
                                </td>
                            </tr>
                            <tr>
                                <td>*Raz&oacute;n social</td>

                                <td colspan="3">
								<div id="autoCliente"></div>
								<input name="txtRazonSocial" type="text" maxlength="80" id="txtRazonSocial" value = "<?php echo $razon; ?>"  size="65" onblur="this.value=this.value.toUpperCase();"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Nombre comercial</td>
                                <td colspan="3">
                                    <input name="txtNombreComenrcial" type="text" maxlength="80" id="txtNombreComenrcial" size="65" onblur="this.value=this.value.toUpperCase();"/>

                                </td>
                            </tr>
                            <tr>
                                <td>R.F.C.</td>
                                <td>
                                    <input name="txtRfc" type="text" maxlength="20" id="txtRfc"  />
                                </td>
                                <td>C.U.R.P.</td>
                                <td>
                                    <input name="txtCurp" type="text" maxlength="30" id="txtCurp" />

                                </td>
                            </tr>
                            <tr>
                            	  <td>Lada</td>

                                <td>
                                    <input name="txtLada" type="text" maxlength="10" id="txtLada" onkeydown="return chk_val(this.id,event);"  />
                                </td>	
                                
                                <td>*Telefono</td>
                                <td>
                                    <input name="txtTelefono" type="text" maxlength="20" id="txtTelefono" />
                                </td>                             
                                                                
                            </tr>
                            <tr>
                                <td>Fax</td>
                                <td>
                                    <input name="txtFax" type="text" maxlength="20" id="txtFax" onkeydown="return chk_val(this.id,event);" />
                                </td>
                                 <td >P&aacute;gina WEB</td>
								<td><input name="txtPaginaWeb" type="text" maxlength="60" id="txtPaginaWeb"  /></td>
                            </tr>
                            <tr>
                            <td>Dias a Presentar la Factura</td>
                                <td>
                                    <input name="txtdiasFactura" type="text" maxlength="20" id="txtdiasFactura" />
                                </td>
                                
								<td>Dias de Cobro</td>
                                <td>
                                    <input name="txtDiasCobro" type="text" maxlength="20" id="txtDiasCobro" />
                                </td>
                            </tr>
                            <tr>
                                <td>Plazo del Cobro</td>
                                <td>
                                    <input name="txtPlazo" type="text" maxlength="20" id="txtPlazo" />
                                </td>
                                
                                <td >Condiciones de pago</td>
                                <td>   <input name="txtCondicionesP" type="text" maxlength="60" id="txtCondicionesP"  />
                                </td>
                            </tr>
                            <tr>
                            		<td>*Impuesto:</td>
                                <td><input name="txtImpuesto" type="text" maxlength="60" id="txtImpuesto"  /></td>
                                
                                <td>Tipo de moneda</td>		
                                <td>    <select name="slcMonedas" id="slcMonedas">
											
                                </td>
                            </tr>
                            
                            <tr>
                            <?php
	
    if($cliente!=0)
    {
    $sql="SELECT cveCliente ,razonSocial ,nombreComercial ,rfc ,paginaWeb ,tipoCliente ,cveImpuesto ,cveMoneda ,condicionesPago ,estatus ,lada ,telefono ,fax ,curp,diasFactura,diasCobro,plazoCobro,requisitosCobro,noProveedor,revicionFactura FROM ccliente WHERE cveCliente='$cliente'";
    //echo $sql;
        	$campos = $bd->Execute($sql);
				//$campos = $bd->Execute("SELECT cveCliente ,razonSocial ,nombreComercial ,rfc ,paginaWeb ,tipoCliente ,cveImpuesto ,cveMoneda ,condicionesPago ,estatus ,lada ,telefono ,fax ,curp,diasFactura,diasCobro,plazoCobro,noProveedor,cveTipoC FROM ccliente WHERE cveCliente='$codigo'");
									
				foreach($campos as $campo){
		  $cobro= str_replace("\r"," ",$campo["requisitosCobro"] );
		  
          $factura=$campo["revicionFactura"] ;
				}
			
				}
    
?>
                                <td >Requisitos para Revisi&oacute;n de Factura</td>
                                <td colspan="3"><textarea rows="4" id="txaFactura" name="txaFactura" cols="80"  ><?php echo $factura;?></textarea></td>
                            </tr>
                            <tr>
                            		<td >Requisitos a Efectuar Cobro</td>
                                   	<td colspan="3"><textarea rows="4" id="txaRequisitosC" name="txaRequisitosC" cols="80"  ><?php echo $cobro;?></textarea></td>
                                   	
                            </tr>
                            <tr>
                                <td >*No. de Proveedor</td>
                                <td>   <input name="txtProveedor" type="text" maxlength="60" id="txtProveedor"/>
                                </td>
                                 <td >*Tipo de cliente</td>
                                <td>   <select name="tipoCliente" id="tipoCliente">    </td>
                             </tr>   
                             <tr>
				<td >*Folio</td>
                                <td>
					<input name="txtFolio" type="text" maxlength="60" id="txtFolio"/>
                                </td>                             
                            </tr>  
                          <tr id="act_des">
                                <td align="right"><input type="checkbox" id="chkActivado" name="chkActivado" onchange="Cambia(this.checked);" /></td>
                                <td >
                                    <input type="text" id="lblActivado" name="lblActivado" readonly="readonly" style="border:0" value="" />
                                </td>                               
                            </tr> 
                        </table>

                    
	</fieldset>
</div>
            </div>
                <div class="derecha" style="position:absolute;left:1130px;top:137px;">
                    <div id="pnlTipoCliente">
						<fieldset>
					
							<legend>
								Tipo de Cliente
							</legend>
					                        <input id="rdoMoral" type="radio" name="TipoCliente" value="1" checked="checked" /><label for="rdoMoral">F&iacute;sica</label>
					                        <span class="requerido">*</span><br />
					                        <input id="rdoMoral" type="radio" name="TipoCliente" value="2" /><label for="rdoMoral">Moral</label><br />
					                    	<input id="rdoMoral" type="radio" name="TipoCliente" value="3" /><label for="rdoMoral">F&iacute;sica y Moral</label>
						</fieldset>
					</div>
				</div>
                <div class="controles">
					<input type="button" name="btnbuscar" id="btnbuscar" value= "Buscar" />	
                    <input type="button" name="btnGuardar" id="btnGuardar" value="Guardar"/>
                    <input type="button" name="btnModificar" id="btnModificar" value="Modificar" id="btnModificar" />
                    <input type="button" name="btnCancelar" id="btnCancelar" value="Cancelar" id="btnCancelar" />            
					<input type="button" name="btnDirecciones" id="btnDirecciones" value="Direcciones" onclick="javascript:Enviar();" />
</div>
				<div style="position:absolute;left:1130px;top: 228px;">
					<table class="gridView" id="tablaFormulario">
					<caption>Contactos</caption>
					<tr>	
						<th>Nombre</th>
						<th>Telefono</th>
						<th>Sucursal</th>
						<th>Direcciones</th>
					</tr>
					</table>
				</div>
                <div class="controles">
                
<div id="divDirecciones" class="oculto">
						<table class="gridView" id="tblDirecciones">
							<caption>Direcciones</caption>
							<tr>	
								<th width="130">Tipo</th>
								<th width="130">Calle</th>
								<th width="130">Colonia</th>
								<th width="130">Estado</th>
								<th width="130">Municipio / Del</th>
								<th width="130">C.P</th>
								<th width="130">Editar</th>
							</tr>
					</table>
					</div>
                </div>
                <br />

                <br />
            </div>
        </div>
    </div>
    </form>
</div>
</body>
</html>
