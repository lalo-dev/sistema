<?php

/**
 * @author miguel
 * @copyright 2010
 */

if (!isset($_SESSION["usuario_valido"]))
{
    header("Location: login.php");
}
$usuario=$_SESSION["usuario_valido"];
$empresa=$_SESSION["cveEmpresa"];
$sucursal=$_SESSION["cveSucursal"];


?>
 
        <div id="encabezado">
            <img id="logotipo" src="imagenes/e-webfac.jpg" alt="e-webfac" height="45" width="120"/>
            <div id="menuayuda">
                | <a href='logout.php'>Salir</a> |
            </div>
            <img id="cabecera" src="imagenes/Cabecera.jpg" alt="cabecera" height="15" width="944"/>
        </div>
        <div id="cuerpo">
       		   <div id="menu">
											
						<ul>
							<li><h1>Catalogos</h1>
								<ul>
									<li><a href="guia.php">Guias</a></li>
                                    <li><a href="codigos.php">C&oacute;digos Postal</a></li>
								</ul>
							</li>
							<li><h1>Datos Base</h1>
								<ul>
									<li><a href="clientes.php">Clientes</a></li>
									<li><a href="corresponsales.php">Corresponsales</a></li>
									<li><a href="importarExcel.php">Importar Datos</a></li>
								</ul>
							</li>
							<li><h1>Control de Envios</h1>
								<ul>
									<li><a href="acuses.php">Reporte de Acuses</a></li>
								</ul>
							</li>
							<li><h1>Reportes</h1>
								<ul>
									<li><a href="reporteOperaciones.php">Reporte de Operaciones</a></li>
								</ul>
							</li>
							<li><h1>Asignaci&oacute;n e Impresi&oacute;n</h1>
								<ul>
									<li><a href="guiaImpresion.php">Multiples Guias</a></li>
									<li><a href="guiaReImpresion.php">Re.impresi&oacute;n de Guia</a></li>
								</ul>
							</li>							
						</ul>
                        <input type="hidden" id="hdnUsuario" value="<?php echo $usuario;?>" />
         				<input type="hidden" id="hdnEmpresaS" value="<?php echo "-".$empresa."-,-".$sucursal."-";?>" />
<input type="hidden" id="txthPer" name="txthPer" value=<?php echo $permisoNum;?> />
				</div>
		</div>
