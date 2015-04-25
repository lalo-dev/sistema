<?php

/* funciona como un incluido se anexa usando: include(url de éste archivo);
   ejemplo 		<?php include('../conexiones/conectar.php');
						$link = conectarse(); ?>
						
						
						
						para cerrar la conexion basta con la sig instruccion
						mysql_close($link); 
						*/


function conectarse()
{

	$db_host="localhost"; // Host al que conectar, casi siempre localhost
	$db_nombre="cargayex"; // Nombre de la Base de Datos que se va usar
	$db_user="root"; // Nombre del usuario con permisos para acceder
	$db_pass=""; // Contraseña del usuario


// se realiza la conexion y se encapsula en $link

$link=mysql_connect($db_host, $db_user, $db_pass) or die ("Error conectando a la base de datos.");


// Se se lecciona la base de datos que se va a usar

mysql_select_db($db_nombre ,$link) or die("Error seleccionando la base de datos."); 


// Se devuelve la conexion ya lista en la variable $link.

return $link;

}

?>