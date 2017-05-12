<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){ 
	
	//Requires del modelo, otros controladores y las vistas.
	require_once('../Models/USUARIO_Model.php'); 

	require_once('GRUPO_Controller.php');
	
	require_once('../views/USUARIO_ADD_Vista.php');
	require_once('../views/USUARIO_DELETE_Vista.php');
	require_once('../views/USUARIO_EDIT_Vista.php'); 
	require_once('../views/USUARIO_LIST_Vista.php');
	require_once('../views/USUARIO_SHOW_Vista.php');
	
	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php"); 
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php"); 
	}else{
		include("../locates/Strings_CASTELLANO.php"); 
	}
	
	//Recoge los datos del formulario de la vista en un objeto 'usuario'.
	function datosFormUsuario(){
		
		$usuario = $_POST['usuario'];
		if(isset($_POST['password'])){
			$password = $_POST['password'];
		}else{
			$password = null;
		}
		if(isset($_POST['grupo'])){
			$grupo = $_POST['grupo'];
		}else{
			$grupo = null;
		}
		if(isset($_POST['dni'])){
			$dni = $_POST['dni'];
		}else{
			$dni = null;
		}
		
		$usuario = new usuario($password,$grupo,$usuario,$dni);
		return $usuario;
	}
	
	//Recoge del formulario los datos nuevos para modificar un usuario.
	function datosFormUsuarioModificar(){
		
		$usuario = $_POST['usuarioN'];
		if(isset($_POST['passwordN'])){
			$password = $_POST['passwordN'];
		}else{
			$password = null;
		}
		if(isset($_POST['grupoN'])){
			$grupo = $_POST['grupoN'];
		}else{
			$grupo = null;
		}
		if(isset($_POST['dniN'])){
			$dni = $_POST['dniN'];
		}else{
			$dni = null;
		}
		
		$usuario = new usuario($password,$grupo,$usuario,$dni);
		return $usuario;
	}
	
	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			
			case 'altaUsuario':
				if(!isset($_POST['usuario'])){
					new Usuario_Crear();
				}else{
					$usuario = datosFormUsuario();
					$mensaje = $usuario->crear(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['usuario']);
					new Usuario_Crear();
				}
				break;
				
			case 'bajaUsuario':
				if(!isset($_POST['usuario'])){
					new Usuario_Borrar();
				}else{
					$usuario = datosFormUsuario();
					$mensaje = $usuario->borrar(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['user']);
					new Usuario_Borrar();
				}
				break;
				
			case 'modificarUsuario':
				if(!isset($_POST['usuario'])){
					new Usuario_Editar();
				}
				else if(!isset($_POST['usuarioN'])){
					$array = mostrarUsuario($_POST['usuario']);
					new Usuario_Editar($array);
				}
				else{
					$usuario = datosFormUsuario();
					$usuario2 = datosFormUsuarioModificar();
					$mensaje = $usuario->modificar($usuario2); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['usuario']);
					new Usuario_Editar();
				}
				break;
				
			case 'consultarUsuario':
				new Usuario_Listar();
				break;
				
			case 'buscarUsuario':
				new Usuario_Buscar();
				break;
		}
	}
}else echo "Permiso denegado.";
?>