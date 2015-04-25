<?php
   session_start ();

   if (isset($_SESSION["usuario_valido"]))
   {
      session_destroy ();
	  header("Location:login.php");
   }
	
?>


