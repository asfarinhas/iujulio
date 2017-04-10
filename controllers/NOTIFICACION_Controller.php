<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si es admin o secretario antes de cargar la página.
if(isset($_SESSION['grupo']) && (strcmp($_SESSION['grupo'],"Admin") == 0 || strcmp($_SESSION['grupo'],"Secretario") == 0) ){ 
	
	//Requires del modelo, otros controladores y las vistas.
	include('../models/NOTIFICACION_Model.php'); 
	
	require_once('../views/NOTIFICACION_Vista.php');
        
        require_once('../PHPMailer/class.phpmailer.php');

	
	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php"); 
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php"); 
	}else{
		include("../locates/Strings_CASTELLANO.php"); 
	}
	
	//Recoge los datos del formulario de la vista en un objeto 'alumno'.
	
	//Recoge del formulario los datos nuevos para modificar un alumno.
	function datosFormNotificacion(){
		
		if(isset($_POST['asunto'])){
			$asunto = $_POST['asunto'];
		}else{
			$asunto = null;
		}
		if(isset($_POST['cuerpo'])){
			$cuerpo = utf8_encode($_POST['cuerpo']);
		}else{
			$cuerpo = null;
		}
		
		$notificacion = new notificacion($asunto,$cuerpo);
		return $notificacion;
	}
        
        if(!isset($_POST['notificacion'])){
                new Notificacion_Enviar();
        }
        else{        
                $notificacion = datosFormNotificacion();
                $mensaje = $notificacion->enviar(); ?>
                <script>
                        window.alert('<?php echo $strings[$mensaje]; ?>');
                </script><?php
                unset($_POST['notificacion']);
                new Notificacion_Enviar();
        }

}else echo "Permiso denegado.";
?>