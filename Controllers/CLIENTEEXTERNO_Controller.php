<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si es admin o secretario antes de cargar la página.
if(isset($_SESSION['grupo']) && (strcmp($_SESSION['grupo'],"Admin") == 0 OR strcmp($_SESSION['grupo'],"Secretario") == 0) ){

	//Requires del modelo, otros controladores y las vistas.
	require_once('../Models/CLIENTEEXTERNO_Model.php');

	require_once('../views/CLIENTEEXTERNO_ADD_Vista.php');
	require_once('../views/CLIENTEEXTERNO_DELETE_Vista.php');
	require_once('../views/CLIENTEEXTERNO_EDIT_Vista.php');
	require_once('../views/CLIENTEEXTERNO_LIST_Vista.php');
	require_once('../views/CLIENTEEXTERNO_SHOW_Vista.php');

	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php");
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php");
	}else{
		include("../locates/Strings_CASTELLANO.php");
	}

	//Recoge los datos del formulario de la vista en un objeto 'cliente'.
	function datosFormCliente(){

		$id = $_POST['id_cliente'];
		if(isset($_POST['dni'])){
			$dni = $_POST['dni'];
		}else{
			$dni = null;
		}
		if(isset($_POST['nombre'])){
			$nombre = $_POST['nombre'];
		}else{
			$nombre = null;
		}
		if(isset($_POST['tlf'])){
			$tlf = $_POST['tlf'];
		}else{
			$tlf = null;
		}
		if(isset($_POST['email'])){
			$email = $_POST['email'];
		}else{
			$email = null;
		}
    	if(isset($_POST['direccion'])){
			$direccion = $_POST['direccion'];
		}else{
			$direccion = null;
		}
		$cliente = new clienteExterno($id,$nombre,$dni, $tlf, $email, $direccion);
		return $cliente;
	}


	//Recoge del formulario los datos nuevos para modificar un cliente.
	function datosFormClienteModificar(){

		$id = $_POST['id_clienteN'];
		if(isset($_POST['dniN'])){
			$dni = $_POST['dniN'];
		}else{
			$dni = null;
		}
		if(isset($_POST['nombreN'])){
			$nombre = $_POST['nombreN'];
		}else{
			$nombre = null;
		}
		if(isset($_POST['tlfN'])){
			$tlf = $_POST['tlfN'];
		}else{
			$tlf = null;
		}
		if(isset($_POST['emailN'])){
			$email = $_POST['emailN'];
		}else{
			$email = null;
		}
    	if(isset($_POST['direccionN'])){
			$direccion = $_POST['direccionN'];
		}else{
			$direccion = null;
		}
		$cliente = new clienteExterno($id,$nombre,$dni, $tlf, $email, $direccion);
		return $cliente;
	}

	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			case 'altaCliente':
				if(!isset($_POST['id_cliente'])){
					new Cliente_Crear();
				}else{
					$cliente = datosFormCliente();
					$mensaje = $cliente->crear(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['id_cliente']);
					new Cliente_Crear();
				}
				break;

			case 'bajaCliente':
				if(!isset($_POST['id_cliente'])){
					new Cliente_Borrar();
				}else{
					$cliente = datosFormCliente();
					$mensaje = $cliente->borrar(); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['client']);
					new Cliente_Borrar();
				}
				break;

			case 'modificarCliente':
				if(!isset($_POST['id_cliente'])){
					new Cliente_Editar();
				}
				else if(!isset($_POST['id_clienteN'])){
					$array = mostrarCliente($_POST['id_cliente']);
					new Cliente_Editar($array);
				}
				else{
					$cliente = datosFormCliente();
					$cliente2 = datosFormClienteModificar();
					$mensaje = $cliente->modificar($cliente2); ?>
					<script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['id_cliente']);
					new Cliente_Editar();
				}
				break;

			case 'consultarCliente':
				new Cliente_Listar();
				break;

			case 'buscarCliente':
				new Cliente_Buscar();
				break;
		}
	}
}else echo "Permiso denegado.";
?>
