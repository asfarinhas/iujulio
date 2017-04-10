<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){ 
	
	//Requires del modelo, otros controladores y las vistas.
	require_once('../models/TRABAJADOR_Model.php'); 
	require_once('../views/TRABAJADOR_ADD_Vista.php');
	require_once('../views/TRABAJADOR_ADD_CONFIRMAR_Vista.php');
	require_once('../views/TRABAJADOR_DELETE_Vista.php');
	require_once('../views/TRABAJADOR_DELETE_CONFIRMAR_Vista.php');
	require_once('../views/TRABAJADOR_EDIT_Vista.php'); 
	require_once('../views/TRABAJADOR_EDIT_CONFIRMAR_Vista.php');
	require_once('../views/TRABAJADOR_LIST_Vista.php');
	require_once('../views/TRABAJADOR_SHOW_Vista.php');
	
	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php"); 
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php"); 
	}else{
		include("../locates/Strings_CASTELLANO.php"); 
	}

	//Recoge los datos del formulario de la vista en un objeto 'trabajador'.
	function datosFormTrabajador(){
		
		$dni = $_POST['dni']; 
		if(isset($_POST['nombre'])){
			$nombre = $_POST['nombre'];
		}else{
			$nombre = null;
		}
		if(isset($_POST['apellidos'])){
			$apellidos = $_POST['apellidos'];
		}else{
			$apellidos = null;
		}
		if(isset($_POST['direccion'])){
			$direccion = $_POST['direccion'];
		}else{
			$direccion = null;
		}
		if(isset($_POST['email'])){
			$email = $_POST['email'];
		}else{
			$email = null;
		}
		if(isset($_POST['fechanac'])){
			$fechaNac = $_POST['fechanac'];
		}else{
			$fechaNac = null;
		}
		if(isset($_POST['tipoemp'])){
			$tipoEmp = $_POST['tipoemp'];
		}else{
			$tipoEmp = null;
		}
		if(isset($_POST['observaciones'])){
			$observaciones = $_POST['observaciones'];
		}else{
			$observaciones = null;
		}
		if(isset($_POST['numerocuenta'])){
			$numeroCuenta = $_POST['numerocuenta'];
		}else{
			$numeroCuenta = null;
		}
		
		if(isset($_POST['telefono'])){
			$telefono= $_POST['telefono'];
		}else{
			$telefono = null;
		}
		if(isset($_POST['externo'])){
			$externo= $_POST['externo'];
		}else{
			$externo = null;
		}
		if(isset($_FILES['fotoperfil']['name'])){
			$nombreFoto = $_FILES['fotoperfil']['name'];
		}else{
			$nombreFoto = null;
		}
		if(isset($_FILES['fotoperfil']['type'])){
			$tipoFoto = $_FILES['fotoperfil']['type'];
		}else{
			$tipoFoto = null;
		}
		if(isset($_FILES['fotoperfil']['tmp_name'])){
			$nombreTempFoto = $_FILES['fotoperfil']['tmp_name'];
		}else{
			$nombreTempFoto = null;
		}
		if(isset($_FILES['fotoperfil']['size'])){
			$tamanhoFoto = $_FILES['fotoperfil']['size']; 
		}else{
			$tamanhoFoto = null;
		}
		if(isset($_FILES['fotoperfil']['error'])){
			$errorFoto = $_FILES['fotoperfil']['error'];
		}else{
			$errorFoto = null;
		}

		
		

		if($nombreFoto != null){

			$dir_subida = '../images/';
			$extension = substr($tipoFoto, 6);
			$ruta = $dir_subida . $dni . ".". $extension;
			move_uploaded_file($nombreTempFoto, $ruta);
			
		}else{
			if(isset($_POST['imagen'])){
				$ruta=$_POST['imagen'];
			}else{

			$ruta=null;
		}
		}
		$trabajador = new Trabajador($apellidos,$nombre,$ruta,$direccion,$email,$fechaNac,$observaciones,$numeroCuenta,$dni,$tipoEmp,$telefono,$externo);
		return $trabajador;
	}

	

	function datosFormTrabajadorModificar($array){
		
		$dni = $_POST['dniN'];
		$nombre = $_POST['nombreN'];
		$apellidos = $_POST['apellidosN'];
		$direccion = $_POST['direccionN'];
		$email = $_POST['emailN'];
		$fechaNac = $_POST['fechanacN'];
		$tipoEmp = $_POST['tipoempN'];
		$observaciones = $_POST['observacionesN'];
		$numeroCuenta = $_POST['numerocuentaN'];
		$telefono = $_POST['telefonoN'];
	


       	if(isset($_POST['externoN'])){
			$externo= $_POST['externoN'];
		}else{
			$externo = null;
		}

		if(isset($_FILES['fotoperfilN']['name'])){
			$nombreFoto = $_FILES['fotoperfilN']['name'];
		}else{
			$nombreFoto = null;
		}
		if(isset($_FILES['fotoperfilN']['type'])){
			$tipoFoto = $_FILES['fotoperfilN']['type'];
		}else{
			$tipoFoto = null;
		}
		if(isset($_FILES['fotoperfilN']['tmp_name'])){
			$nombreTempFoto = $_FILES['fotoperfilN']['tmp_name'];
		}else{
			$nombreTempFoto = null;
		}
		if(isset($_FILES['fotoperfilN']['size'])){
			$tamanhoFoto = $_FILES['fotoperfilN']['size']; 
		}else{
			$tamanhoFoto = null;
		}
		if(isset($_FILES['fotoperfilN']['error'])){
			$errorFoto = $_FILES['fotoperfilN']['error'];
		}else{
			$errorFoto = null;
		}


		if($nombreTempFoto != null){

			$dir_subida = '../images/';
			$extension = substr($tipoFoto, 6);
			$ruta = $dir_subida . $dni . ".". $extension;
			move_uploaded_file($nombreTempFoto, $ruta);
		}else{

			$ruta=$array['Url_Foto'];
		}

		

		$trabajador2 = new Trabajador($apellidos,$nombre,$ruta,$direccion,$email,$fechaNac,$observaciones,$numeroCuenta,$dni,$tipoEmp,$telefono,$externo);

		return $trabajador2;
	}
	
	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			
			case 'altaTrabajador':
			if(!isset($_POST['dni'])){
				new Trabajador_Crear();
			}else if(!isset($_GET['confirm'])){
				$trabajador = datosFormTrabajador();
				new Trabajador_Alta_Confirmar($trabajador);
			}else{
				$trabajador = datosFormTrabajador();
				
				$mensaje = $trabajador->crear(); 
				?>
				<script>
					window.alert('<?php echo $strings[$mensaje]; ?>');
				</script><?php
				unset($_POST['dni']);
				new Trabajador_Crear();
			}
			break;
			
			case 'bajaTrabajador':
			if(!isset($_GET['id2'])){
				new Trabajador_Borrar();
			}else if(!isset($_GET['confirm'])){
				$array = mostrarTrabajadorDni($_GET['id2']);
				new Trabajador_Borrar_Confirmar($array);
			}else{
				$trabajador = datosFormTrabajador();
				$mensaje = $trabajador->borrar(); ?>
				<script>
					window.alert('<?php echo $strings[$mensaje]; ?>');
				</script><?php
				unset($_GET['id2']);
				new Trabajador_Borrar();
			}
			break;
			case 'modificarTrabajador':
				if(!isset($_GET['id2'])){
					new Trabajador_Editar();
				}
				else if(!isset($_POST['dniN'])){
					$array = mostrarTrabajadorDni($_GET['id2']);
					new Trabajador_Editar($array);
				}else if(!isset($_GET['confirm'])){
					$array = mostrarTrabajadorDni($_GET['id2']);
					$trabajador2 = datosFormTrabajadorModificar($array);
					new Trabajador_Editar_Confirmar($trabajador2,$_GET['id2']);
				}else{
					$array = mostrarTrabajadorDni($_GET['id2']);
					
					$trabajador = datosFormTrabajador();

					$trabajador2 = datosFormTrabajadorModificar($array);
					
					$mensaje = $trabajador->modificar($trabajador2); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_GET['id2']);

					new Trabajador_Editar();
				}
				break;
				
				case 'consultarTrabajador':
				new Trabajador_Listar('activos');
				break;
				
				case 'consultarTrabajadorBorrado':
				new Trabajador_Listar('noActivos');
				break;

				case 'buscarTrabajador':
				if(!isset($_GET['buscar'])){
					new Trabajador_Buscar();
				}else{
					$trabajador = datosFormTrabajador();
					$array = consultarTrabajador($trabajador->dni);
					new Trabajador_Buscar($array);
				}
				break;
				case 'buscarTrabajadorListar':

				$id2 = $_GET['id2'];
				$array = mostrarTrabajadorDni($id2);
				new Trabajador_Buscar($array);
				break;
				case 'buscarTrabajadorListarBorrado':

				$id2 = $_GET['id2'];
				$array = mostrarTrabajadorBorrados($id2);
				new Trabajador_Buscar($array);
				break;	

				
			}
			
		}
	}else echo "Permiso denegado.";
	?>