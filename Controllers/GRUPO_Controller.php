<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){ 
	
	//Requires del modelo y las vistas.
	require_once('../Models/GRUPO_Model.php'); 

	require_once('../views/GRUPO_ADD_Vista.php'); 
	require_once('../views/GRUPO_DELETE_Vista.php'); 
	require_once('../views/GRUPO_EDIT_Vista.php');
	require_once('../views/GRUPO_LIST_Vista.php');
	
	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php"); 
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php"); 
	}else{
		include("../locates/Strings_CASTELLANO.php"); 
	}
	
	//Recoge los datos del formulario de la vista en un objeto 'Grupo'.
	function datosFormGrupo(){

		$grupo = $_POST['grupo'];
		
		$grup = new grupo($grupo);
		return $grup;
	}
	
	//Recoge del formulario los datos nuevos para modificar un grupo.
	function datosFormGrupoModificar(){
		
		$grupo = $_POST['grupoN'];
		
		$grupo2 = new grupo($grupo);
		return $grupo2;
	}
	
	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			
			case 'altaGrupo':
				if(!isset($_POST['grupo'])){
					new Grupo_Crear();
				}else{
					$grupo = datosFormGrupo();
					$mensaje = $grupo->crear(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['grupo']);
					new Grupo_Crear();
				}
				break;
				
			case 'bajaGrupo':
				if(!isset($_POST['grupo'])){
					new Grupo_Borrar();
				}else{
					$grupo = datosFormGrupo();
					$mensaje = $grupo->borrar(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['grupo']);
					new Grupo_Borrar();
				}
				break;
				
			case 'modificarGrupo':
				if(!isset($_POST['grupo'])){
					new Grupo_Editar();
				}else{
					$grupo = datosFormGrupo();
					$grupo2 = datosFormGrupoModificar();
					$mensaje = $grupo->modificar($grupo2); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['grupo']);
					new Grupo_Editar();
				}
				break;
				
			case 'consultarGrupo':
				new Grupo_Listar();
				break;
		}
	}
}else echo "Permiso denegado.";
?>