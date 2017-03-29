<?php

session_start();

//Destruye la sesión del usuario actual y regresa al index.
session_destroy();
header("location: index.php");

?>