<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si es admin o secretario antes de cargar la página.
if(isset($_SESSION['grupo']) && (strcmp($_SESSION['grupo'],"Admin") == 0 OR strcmp($_SESSION['grupo'],"Secretario") == 0) ){
	//Requires del modelo, otros controladores y las vistas.
	require_once('../models/EVENTO_Model.php');

	require_once('../views/EVENTO_ADD_Vista.php');
	require_once('../views/EVENTO_DELETE_Vista.php');
	require_once('../views/EVENTO_EDIT_Vista.php');
	require_once('../views/EVENTO_LIST_Vista.php');
	require_once('../views/EVENTO_SHOW_Vista.php');
	
	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php");
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php");
	}else{
		include("../locates/Strings_CASTELLANO.php");
	}
	
	//Recoge los datos del formulario de la vista en un objeto 'evento'.
	function datosFormEvento(){

		$idE = $_POST['id_evento'];
		if(isset($_POST['descripcion'])){
			$descripcion = $_POST['descripcion'];
		}else{
			$descripcion = null;
		}
		if(isset($_POST['nombre'])){
			$nombre = $_POST['nombre'];
		}else{
			$nombre = null;
		}

		$evento = new evento($id_evento, $descripcion, $nombre);
		return $evento;
	}

	//Recoge del formulario los datos nuevos para modificar un evento.
	function datosFormEventoModificar(){

		$idE = $_POST['id_eventoN'];
	
		if(isset($_POST['descripcionN'])){
			$descripcion = $_POST['descripcionN'];
		}else{
			$descripcion = null;
		}
		if(isset($_POST['nombreN'])){
			$nombre = $_POST['nombreN'];
		}else{
			$nombre = null;
		}

		$evento = new evento($id_evento, $descripcion, $nombre);
		return $evento;
	}
	
	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			case 'altaEvento':
				if(!isset($_POST['id_evento'])){
					new Evento_Crear();
				}else{
					$evento = datosFormEvento();
					$mensaje = $evento->crear(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['id_evento']);
					new Evento_Crear();
				}
				break;

			case 'bajaEvento':
				if(!isset($_POST['id_evento'])){
					new Evento_Borrar();
				}else{
					$evento = datosFormEvento();
					$mensaje = $evento->borrar(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['even']);
					new Evento_Borrar();
				}
				break;

			case 'modificarEvento':
				if(!isset($_POST['id_Evento'])){
					new Evento_Editar();
				}
				else if(!isset($_POST['id_eventoN'])){
					$array = mostrarEvento($_POST['id_evento']);
					new Evento_Editar($array);
				}
				else{
					$evento = datosFormEvento();
					$evento2 = datosFormEventoModificar();
					$mensaje = $evento->modificar($evento2); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['id_evento']);
					new Evento_Editar();
				}
				break;

			case 'consultarEvento':
				new Evento_Listar();
				break;

			case 'buscarEvento':
				new Evento_Buscar();
				break;
		}
	}
}else echo "Permiso denegado.";
?>
	
