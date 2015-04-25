<?php

/**
 * @author Jose Miguel Pantaleon
 * @copyright 2010
 */
 
// Iniciar sesión
   session_start();

// Si se ha enviado el formulario
   $usuario = @$_REQUEST['usuario'];
   $clave = @$_REQUEST['contrasena'];
    
   if (isset($usuario) && isset($clave))
   {


   // Comprobar que el usuario está autorizado a entrar
      $conexion = mysql_connect ("localhost", "webcom", "webcom")  or die ("No se puede conectar con el servidor");
	  //$conexion = mysql_connect ("localhost", "webcom", "webcom") or die ("No se puede conectar con el servidor");
      mysql_select_db ("cargayex") or die ("No se puede seleccionar la base de datos");
	  $clave=md5($clave);
	  $instruccion = "select cveUsuario,nick, password,permiso,cveEmpresa,cveSucursal,estacion from cusuarios where nick = '$usuario'" .
         " and password = '$clave' and estatus=1";
		  
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
      $nfilas = mysql_num_rows ($consulta);
      $reg = mysql_fetch_array($consulta);
      mysql_close ($conexion);

   // Los datos introducidos son correctos
      if ($nfilas > 0)
      {
	$usuario_valido = $usuario;
         
         $_SESSION["usuario_valido"] = $reg['cveUsuario'];
         $_SESSION["permiso"]        = $reg['permiso'];
         $_SESSION["cveEmpresa"]     = $reg['cveEmpresa'];
         $_SESSION["cveSucursal"]    = $reg['cveSucursal'];


		 $usuario_valido=$_SESSION["usuario_valido"];
		 $permiso=trim($_SESSION["permiso"]);			//Quitamos espacios en blanco

		 if($permiso=='Administrador')	  
		 {		
		 	header("Location: guia.php");	
		 }
		 else if($permiso=='Corresponsal')	
		 {
			$_SESSION["estacion"]=$reg['estacion'];
			header("Location: guiaCorresponsal.php");
		 }
		else if($permiso=='Cliente')
		 {
			$_SESSION["cvecliente"]=$reg['estacion'];
			header("Location: consultaGuias.php");
		 }
		 else if($permiso=='Usuario')
		 {
			header("Location: guia.php");	
		 }
		 else if($permiso=='Facturacion')
		 {
			header("Location: envios.php");	
		 }
		 else if($permiso=='Guias')
		 {
			header("Location: guia.php");	
		 }
		 
	  }
   }
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type"  content="text/html; charset=UTF-8" />
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE" />
    <meta http-equiv="PRAGMA" content="NO-CACHE" />
    <meta http-equiv="EXPIRES" content="0" />
    <link href="estilos/acceso.css" rel="stylesheet" type="text/css" />
	 
    <title>Sistema CyE - Acceso</title>
</head>
<body>
  <div id="contenedor">
        <div id="encabezado">
            <img id="logotipo" src="imagenes/e-webfac.jpg" alt="e-webfac" height="45" width="120"/>
            <div id="menuayuda">
                
            </div>
            <img id="cabecera" src="imagenes/Cabecera.jpg" alt="cabecera" height="15" width="950"/>
        </div>
     <div id='cuerpo'>
         <div align='center' id="pnlAcceso">
           	<form name='login' action='login.php' method='post'>
                 <table>
              
  				    <tr>
                          <td><label>Usuario:</label></td>
                          <td><input type='text' id='usuario' name='usuario' class='campol' /></td>
                      </tr>
                      <tr>
                          <td><label>Password:</label></td>
                          <td><input type='password' id='contrasena' name='contrasena' class='campol' /></td>
                      </tr>
                       
                      <tr>
                          <td colspan='2' align='center'><input type='submit' value='Accesar' /></td>
                      </tr>
					
                  </table>
                 </form>	
          </div>
       </div>
       <div id='piepagina'>
<?php  if (isset ($usuario)) { ?>
           <span class="textoRojo">Tu contrase&ntilde;a o usuario son incorrectos, por favor intenta de nuevo</span><br />
       
<?php  }?>
	    Copyright &copy; Todos los derechos reservados CARGA Y EXPRESS S.A. de C.V. M&eacute;xico 2011
       </div>
    </div>
  </body>
</html>

