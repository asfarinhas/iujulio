<html>

<?php
session_start(); 

//Comprueba si hay una sesión iniciada, si no lleva a login. Si existe lleva al menú principal.
if(!isset($_SESSION['user'])){
	header("location: login.php");
}else{
	header("location: views/default_Vista.php");
}
?>

</html>