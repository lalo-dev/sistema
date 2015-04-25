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
                | <a href='logout.php' title='Salir'>Salir</a> |
            </div>
            <img id="cabecera" src="imagenes/Cabecera.jpg" alt="cabecera" height="15" width="1100"/><!-- 944 -->
        </div>
        <div id="cuerpo">
       		<div id="menu">
											
						<ul>
								<li><h1>Catalogos</h1>
								<ul>
                           <li><a href="aerolineas.php">Aerolineas</a></li>
									<li><a href="empresas.php">Empresas</a></li>
                           <li><a href="destinos.php">Estaciones</a></li>
									<li><a href="guia.php">Guias</a></li>
									<li><a href="monedas.php">Monedas</a></li>
									<li><a href="sucursales.php">Sucursales</a></li>									
									<li><a href="tiposEnvio.php">Tipos de Envio</a></li>									
									<li><a href="tiposDoc.php">Tipos de Documentos</a></li>
                                    <li><a href="usuarios.php">Usuarios</a></li>
<li><a href="codigos.php">C&oacute;digos Postal</a></li>
<li><a href="bancos.php">Bancos</a></li>
								</ul>
							</li>
							<li><h1>Datos Base</h1>
								<ul>
									<li><a href="clientes.php">Clientes</a></li>
									<li><a href="corresponsales.php">Corresponsales</a></li>
									<li><a href="tarifasDestinos.php">Tarifas Destinos</a></li>
									<li><a href="tarifasCorresponsales.php">Tarifas Corresponsales</a></li>
									<li><a href="importarExcel.php">Importar Datos</a></li>
								</ul>
							</li>
							<li><h1>Control de Envios</h1>
								<ul>
									<li><a href="acuses.php">Reporte de Acuses</a></li>
									<li><a href="envios.php">Relacion de Envios</a></li>
								</ul>
							</li>
							<li><h1>Cuentas por Cobrar</h1>
								<ul>
									<li><a href="pagos.php?dato=cliente">Pagos Clientes</a></li>
									<li><a href="EstadoCta.php?dato=cliente">Estado de Cuenta</a></li>
									<li><a href="reporteContra.php">Contra Recibo</a></li>
								</ul>
							</li>
							<li><h1>Cuentas por Pagar</h1>
								<ul>
									<li><a href="pagosCorresponsales.php">Facturas Corresponsales</a></li>
									<li><a href="pagos.php?dato=corresponsal">Pagos Corresponsales</a></li>
									<li><a href="notasCredito.php">Notas de Cr&eacute;dito</a></li>
                                    <li><a href="EstadoCta.php?dato=corresponsal">Estado de Cuenta</a></li>
								</ul>
							</li>
							<li><h1>Reportes</h1>
								<ul>
									<li><a href="reporteVentas.php">Ventas por Cliente</a></li>
									<li><a href="reporteDestinos.php">Ventas por Destino</a></li>
									<li><a href="reporteUtilidad.php">Reporte de Utilidad Bruta</a></li>
									<li><a href="reporteOperaciones.php">Reporte de Operaciones</a></li>
									<li><a href="reporteIncidentes.php">Reporte de Incidentes</a></li>                                    
									<li><a href="busquedaGuias.php">Buscador de Gu&iacute;as</a></li>
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
