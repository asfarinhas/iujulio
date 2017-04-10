<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si es admin o secretario antes de cargar la página.
if(isset($_SESSION['grupo']) && (strcmp($_SESSION['grupo'],"Admin") == 0 || strcmp($_SESSION['grupo'],"Secretario") == 0) ){ 
	
	//Requires del modelo, otros controladores y las vistas.
	include('../models/ALUMNO_Model.php'); 
	
	require_once('../views/ALUMNO_ADD_Vista.php');
	require_once('../views/ALUMNO_DELETE_Vista.php');
	require_once('../views/ALUMNO_EDIT_Vista.php'); 
	require_once('../views/ALUMNO_LIST_Vista.php');
	require_once('../views/ALUMNO_SHOW_Vista.php');
	
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
	function datosFormAlumno(){
		
                $dni = utf8_encode($_POST["dni"]);
                $apellidos = utf8_encode($_POST["apellidos"]);
                $nombre = utf8_encode($_POST["nombre"]);
                $direccion = utf8_encode($_POST["direccion"]);
                $email = utf8_encode($_POST["email"]);
                $nacimiento = utf8_encode($_POST["nacimiento"]);
                $observaciones = utf8_encode($_POST["observaciones"]);
                $profesion = utf8_encode($_POST["profesion"]);      
                        
                $alumno = new alumno($dni,$apellidos,$nombre,$direccion,$email,$nacimiento,$observaciones,$profesion);
		return $alumno;
	}
        
        function datosFormAlumnoBorrar(){
		
                $dni = $_POST["alumnoB"];         
                $apellidos = null;
                $nombre = null;
                $direccion = null;
                $email = null;
                $nacimiento = null;
                $observaciones = null;
                $profesion = null;
                          
                $alumno = new alumno($dni,$apellidos,$nombre,$direccion,$email,$nacimiento,$observaciones,$profesion);
		return $alumno;               
	}
	
	//Recoge del formulario los datos nuevos para modificar un alumno.
	function datosFormAlumnoModificar(){
		
		$dni = $_POST['alumno'];
                
		if(isset($_POST['apellidosN'])){
			$apellidos = utf8_encode($_POST['apellidosN']);
		}else{
			$apellidos = null;
		}
		if(isset($_POST['nombreN'])){
			$nombre = utf8_encode($_POST['nombreN']);
		}else{
			$nombre = null;
		}
		if(isset($_POST['direccionN'])){
			$direccion = utf8_encode($_POST['direccionN']);
		}else{
			$direccion = null;
		}
                if(isset($_POST['emailN'])){
			$email = utf8_encode($_POST['emailN']);
		}else{
			$email = null;
		}
                if(isset($_POST['nacimientoN'])){
			$nacimiento = utf8_encode($_POST['nacimientoN']);
		}else{
			$nacimiento = null;
		}
                if(isset($_POST['observacionesN'])){
			$observaciones = utf8_encode($_POST['observacionesN']);
		}else{
			$observaciones = null;
		}
                if(isset($_POST['profesionN'])){
			$profesion = utf8_encode($_POST['profesionN']);
		}else{
			$profesion = null;
		}
		
		$alumno = new alumno($dni,$apellidos,$nombre,$direccion,$email,$nacimiento,$observaciones,$profesion);
		return $alumno;
	}
	
	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			
			case 'altaAlumno':
				if($_SERVER['REQUEST_METHOD'] != "POST"){
					new Alumno_Crear();
				}else{
                                    
					$alumno = datosFormAlumno();
					$mensaje = $alumno->crear(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['alumno']);
					new Alumno_Crear();
				}
				break;
				
			case 'bajaAlumno':
				if(!isset($_POST['alumno'])){
					new Alumno_Borrar();
				}else{
                                        $alumno = datosFormAlumnoBorrar();
					$mensaje = $alumno->borrar(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['alumnoB']);
					new Alumno_Borrar();
				}
				break;
				
			case 'modificarAlumno':
				if(!isset($_POST['alumno'])){
					new Alumno_Editar();
				}
				else if(!isset($_POST['alumnoN'])){
                                    
					$array = mostrarAlumno($_POST['alumno']);
					new Alumno_Editar($array);
				}
				else{
                                    
					$alumno = datosFormAlumno();
					$alumno2 = datosFormAlumnoModificar();
					$mensaje = $alumno->modificar($alumno2); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['alumno']);
					new Alumno_Editar();
				}
				break;
				
			case 'consultarAlumno':
				new Alumno_Listar();
				break;
				
			case 'buscarAlumno':
				new Alumno_Buscar();
				break;
		}
	}
}else echo "Permiso denegado.";
?>