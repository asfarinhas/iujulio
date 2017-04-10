<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si es admin o secretario antes de cargar la página.
if(isset($_SESSION['grupo']) && (strcmp($_SESSION['grupo'],"Admin") == 0 OR strcmp($_SESSION['grupo'],"Secretario") == 0) ){

	//Requires del modelo, otros controladores y las vistas.
	require_once('../models/RESERVAEVENTO_Model.php');
	require_once('../views/RESERVAEVENTO_ADD_Vista.php');
	require_once('../views/RESERVAEVENTO_DELETE_Vista.php');
	require_once('../views/RESERVAEVENTO_EDIT_Vista.php');
	require_once('../views/RESERVAEVENTO_LIST_Vista.php');
	require_once('../views/RESERVAEVENTO_SHOW_Vista.php');

	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php");
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php");
	}else{
		include("../locates/Strings_CASTELLANO.php");
	}

	//Recoge los datos del formulario de la vista en un objeto 'reservaEvento'.
	function datosFormReservaEvento(){

		$idE = $_POST['id_evento'];
		$idR = $_POST['id_reserva'];
		
		$reservaEvento = new reservaEvento($id_evento,$id_reserva);
		return $reservaEvento;
	}


	//Recoge del formulario los datos nuevos para modificar una reserva de evento.
	function datosFormReservaEventoModificar(){

		$idS = $_POST['id_eventoN'];
		$idT = $_POST['id_reservaN'];
		
		$reservaEvento = new reservaEvento($id_evento,$id_reserva);
		return $reservaEvento;
	}

	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			case 'altaReservaEvento':
				if(!isset($_POST['id_evento'])){
					new ReservaEvento_Crear();
				}else{
					$reservaEvento = datosFormReservaEvento();
					$mensaje = $reservaEvento->crear(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['id_evento']);
					new ReservaEvento_Crear();
				}
				break;

			case 'bajaReservaEvento':
				if(!isset($_POST['id_evento'])){
					new ReservaEvento_Borrar();
				}else{
					$reservaEvento = datosFormReservaEvento();
					$mensaje = $servicio->borrar(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['reseven']);
					new ReservaEvento_Borrar();
				}
				break;

			case 'modificarReservaEvento':
				if(!isset($_POST['id_evento'])){
					new ReservaEvento_Editar();
				}
				else if(!isset($_POST['id_eventoN'])){
					$array = mostrarReservaEvento($_POST['id_evento']);
					new ReservaEvento_Editar($array);
				}
				else{
					$reservaEvento = datosFormReservaEvento();
					$reservaEvento2 = datosFormReservaEventoModificar();
					$mensaje = $reservaEvento->modificar($reservaEvento2); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['id_evento']);
					new ReservaEvento_Editar();
				}
				break;

			case 'consultarReservaEvento':
				new ReservaEvento_Listar();
				break;

			case 'buscarReservaEvento':
				new ReservaEvento_Buscar();
				break;
		}
	}
}else echo "Permiso denegado.";
?>
