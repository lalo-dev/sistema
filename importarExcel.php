<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
require_once("direccionamiento.php");
if ((!isset($_SESSION["usuario_valido"]))||(($_SESSION["permiso"]!="Administrador") && ($_SESSION["permiso"]!="Usuario")&& ($_SESSION["permiso"]!="Guias")))
{
    header("Location: login.php");
}
$usuario=$_SESSION["usuario_valido"];
$empresa=$_SESSION["cveEmpresa"];
$sucursal=$_SESSION["cveSucursal"];


?>

  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="estilos/ewebfac1.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jscripts/globalscripts.js"></script>
<link rel="stylesheet" type="text/css" href="css/mtoForm.css" media="all"/>
<title>EWebFac - Importar</title>

</head>
<div id="contenedor">
<?php
if($menu!="")
require_once($menu);
?>
     <div id="derecho">
        <div class="cabecera">
            <div id="ruta">Datos Base | Importar </div>
            <h2 id="titulo">Importar</h2>
        </div><br /> 
        <div class="izquierda">
            <span id="loading" style="display: none">Por favor espere...</span>
            <span id="aviso" style="display: none">Cargando...</span>
            <span id="status"></span>
        </div>
      </div>  
      <br />
      <br />
      <form id="form2" action="scripts/administraExcel.php" method="post" enctype="multipart/form-data">
          <fieldset>
                <legend>
                Datos Generales
                </legend>
                <table width="479" border="0">
                    <tr>
                      <td width="267" height="6">              
                        Extensi&oacute;n de Archivo v&aacute;lido; '.xls'
                       </td>
                    </tr>
                    <tr>
                      <td>
                        <input type="file" name="fleArchivo" />
                        <input type="submit" value="Subir Gu&iacute;as" id='btnSubir'/>
                       </td>
                        <td width="202" align="right">
                            <a href="FormatoGuia.xls">Ver Formato</a>
                        </td>
                     </tr>
                </table>              
            </fieldset>
    </form>
    </div>
</body>
<script language="javascript" type="text/javascript">
	//Evaluaremos según usuario, las acciones que podrá realizar
	numP=document.getElementById("txthPer").value;
	if(numP==6)
	{
		document.getElementById("btnSubir").style.visibility="hidden";
		
		document.getElementById("btnSubir").style.display="none";
	}
	
</script>
</html>

