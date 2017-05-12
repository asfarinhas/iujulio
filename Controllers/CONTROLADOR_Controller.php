<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){ 
	
	//Requires del modelo y las vistas.
	require_once('../Models/CONTROLADOR_Model.php'); 

	require_once('../views/CONTROLADOR_ADD_Vista.php');
	require_once('../views/CONTROLADOR_ADD_ACTION_Vista.php');
	require_once('../views/CONTROLADOR_DELETE_Vista.php'); 
	require_once('../views/CONTROLADOR_EDIT_Vista.php');
	require_once('../views/CONTROLADOR_LIST_Vista.php');
	require_once('../views/CONTROLADOR_SHOW_Vista.php');
	
	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php"); 
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php"); 
	}else{
		include("../locates/Strings_CASTELLANO.php"); 
	}
	
	//Recoge los datos del formulario de la vista en un objeto 'Controlador'.
	function datosFormControlador(){
		
		if(isset($_POST['accion'])){
			$accion = $_POST['accion'];
		}else{
			$accion = null;
		}
		$controlador = $_POST['controlador'];
		
		$cont = new Controlador($controlador, $accion);
		return $cont;
	}
	
	//Recoge del formulario los datos nuevos para modificar un controlador.
	function datosFormControladorModificar(){

		$accion2 = $_POST['accionN'];
		$controlador2 = $_POST['controladorN'];
		
		$cont2 = new Controlador($controlador2, $accion2);
		return $cont2;
	}
	
	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			
			case 'altaControlador':
				if(!isset($_POST['controlador'])){
					new Controlador_Crear();
				}else{
					$cont = datosFormControlador();
					$mensaje = $cont->crearControlador(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script> <?php
					unset($_POST['controlador']);
					new Controlador_Crear();
				}
				break;
			
			case 'altaAccion':
				if(!isset($_POST['controlador'])){
					new Accion_Crear();
				}else{
					$cont = datosFormControlador();
					$mensaje = $cont->crearAccion(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script> <?php
					unset($_POST['controlador']);
					new Accion_Crear();
				}
				break;
			
			case 'bajaControlador':
				if(!isset($_POST['controlador'])){
					new Controlador_Borrar();
				}
				else if(!isset($_POST['accion']) && !isset($_GET['borrar'])){
					new Controlador_Borrar();
				}
				else{
					$cont = datosFormControlador();
					$mensaje = $cont->borrar(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script> <?php
					unset($_POST['controlador']);
					new Controlador_Borrar();;
				}
				break;
				
			case 'modificarControlador':
				if(!isset($_POST['controlador'])){
					new Controlador_Editar();
				}
				else if(!isset($_POST['accionN'])){
					new Controlador_Editar();
				}
				else{
					$cont = datosFormControlador();
					$cont2 = datosFormControladorModificar();
					$mensaje = $cont->modificar($cont2); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script> <?php
					unset($_POST['controlador']);
					new Controlador_Editar();
				}
				break;
				
			case 'consultarControlador':
				new Controlador_Listar();
				break;
				
			case 'buscarControlador':
				new Controlador_Buscar();
				break;	
		}
	}
}else echo "Permiso denegado.";
?>