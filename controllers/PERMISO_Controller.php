<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){ 
	
	//Requires del modelo, otros controladores y las vistas.
	require_once('../models/PERMISO_Model.php'); 

	require_once('CONTROLADOR_Controller.php');
	require_once('GRUPO_Controller.php');
	
	require_once('../views/PERMISO_ADD_Vista.php');
	require_once('../views/PERMISO_DELETE_Vista.php');
	require_once('../views/PERMISO_EDIT_Vista.php');
	require_once('../views/PERMISO_LIST_Vista.php');
	require_once('../views/PERMISO_SHOW_Vista.php');
	
	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php"); 
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php"); 
	}else{
		include("../locates/Strings_CASTELLANO.php"); 
	}
	
	//Recoge los datos del formulario de la vista en un objeto 'Permiso'.
	function datosFormPermiso(){
		
		if(isset($_POST['accion'])){
			$accion = $_POST['accion'];
		}else{
			$accion = null;
		}
		$controlador = $_POST['controlador'];
		$grupo = $_POST['grupo'];
		
		$permiso = new permiso($grupo,$controlador,$accion);
		return $permiso;
	}
	
	//Recoge del formulario los datos nuevos para modificar un permiso.
	function datosFormPermisoModificar(){
		
		$grupo2 = $_POST['grupoN'];
		$accion2 = $_POST['accionN'];
		$controlador2 = $_POST['controladorN'];
		
		$permiso2 = new permiso($grupo2,$controlador2, $accion2);
		return $permiso2;
	}
	
	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			
			case 'altaPermiso':
				if(!isset($_POST['grupo'])){
					new Permiso_Crear();
				}
				else if(!isset($_POST['accion']) && !isset($_GET['añadir'])){
					new Permiso_Crear();
				}
				else{
					$permiso = datosFormPermiso();
					$mensaje = $permiso->crear(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['grupo']);
					new Permiso_Crear();
				}
				break;
				
			case 'bajaPermiso':
				if(!isset($_POST['grupo'])){
					new Permiso_Borrar();
				}
				else if(!isset($_POST['accion']) && !isset($_GET['borrar'])){
					new Permiso_Borrar();
				}
				else{
					$permiso = datosFormPermiso();
					$mensaje = $permiso->borrar(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['grupo']);
					new Permiso_Borrar();
				}
				break;
				
			case 'modificarPermiso':
				if(!isset($_POST['grupo'])){
					new Permiso_Editar();
				}
				else if(!isset($_POST['accionN'])){
					new Permiso_Editar();
				}
				else{
					$permiso = datosFormPermiso();
					$permiso2 = datosFormPermisoModificar();
					$mensaje = $permiso->modificar($permiso2); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['grupo']);
					new Permiso_Editar();
				}
				break;
				
			case 'consultarPermiso':
				new Permiso_Listar();
				break;
				
			case 'buscarPermiso':
				new Permiso_Buscar();
				break;
		}
	}
}else echo "Permiso denegado.";
?>