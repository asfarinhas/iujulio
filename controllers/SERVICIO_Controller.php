<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si es admin o secretario antes de cargar la página.
if(isset($_SESSION['grupo']) && (strcmp($_SESSION['grupo'],"Admin") == 0 OR strcmp($_SESSION['grupo'],"Secretario") == 0) ){

	//Requires del modelo, otros controladores y las vistas.
	require_once('../models/SERVICIO_Model.php');
	require_once('TRABAJADOR_Controller.php');
	require_once('../views/SERVICIO_ADD_Vista.php');
	require_once('../views/SERVICIO_DELETE_Vista.php');
	require_once('../views/SERVICIO_EDIT_Vista.php');
	require_once('../views/SERVICIO_LIST_Vista.php');
	require_once('../views/SERVICIO_SHOW_Vista.php');

	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php");
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php");
	}else{
		include("../locates/Strings_CASTELLANO.php");
	}

	//Recoge los datos del formulario de la vista en un objeto 'servicio'.
	function datosFormServicio(){

		$idS = $_POST['id_servicio'];
		if(isset($_POST['id_trabajador'])){
			$idT = $_POST['id_trabajador'];
		}else{
			$idT = null;
		}
		if(isset($_POST['nombre'])){
			$nombre = $_POST['nombre'];
		}else{
			$nombre = null;
		}
		if(isset($_POST['precio'])){
			$precio = $_POST['precio'];
		}else{
			$precio = null;
		}
		if(isset($_POST['descripcion'])){
			$descripcion = $_POST['descripcion'];
		}else{
			$descripcion = null;
		}

		$servicio = new servicio($idS,$idT,$nombre,$precio, $descripcion);
		return $servicio;
	}


	//Recoge del formulario los datos nuevos para modificar un servicio.
	function datosFormServicioModificar(){

		$idS = $_POST['id_servicioN'];
		$idT = $_POST['id_trabajadorN'];
		if(isset($_POST['nombreN'])){
			$nombre = $_POST['nombreN'];
		}else{
			$nombre = null;
		}
		if(isset($_POST['precioN'])){
			$precio = $_POST['precioN'];
		}else{
			$precio = null;
		}
		if(isset($_POST['descripcionN'])){
			$descripcion = $_POST['descripcionN'];
		}else{
			$descripcion = null;
		}

		$servicio = new servicio($idS,$idT,$nombre,$precio, $descripcion);
		return $servicio;
	}

	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			case 'altaServicio':
				if(!isset($_POST['id_servicio'])){
					new Servicio_Crear();
				}else{
					$servicio = datosFormServicio();
					$mensaje = $servicio->crear(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['id_servicio']);
					new Servicio_Crear();
				}
				break;

			case 'bajaServicio':
				if(!isset($_POST['id_servicio'])){
					new Servicio_Borrar();
				}else{
					$servicio = datosFormServicio();
					$mensaje = $servicio->borrar(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['serv']);
					new Servicio_Borrar();
				}
				break;

			case 'modificarServicio':
				if(!isset($_POST['id_servicio'])){
					new Servicio_Editar();
				}
				else if(!isset($_POST['id_servicioN'])){
					$array = mostrarServicio($_POST['id_servicio']);
					new Servicio_Editar($array);
				}
				else{
					$servicio = datosFormServicio();
					$servicio2 = datosFormServicioModificar();
					$mensaje = $servicio->modificar($servicio2); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['id_servicio']);
					new Servicio_Editar();
				}
				break;

			case 'consultarServicio':
				new Servicio_Listar();
				break;

			case 'buscarServicio':
				new Servicio_Buscar();
				break;
		}
	}
}else echo "Permiso denegado.";
?>
