<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si tiene permisos antes de cargar la página.
if((isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0) || (isset($_SESSION['permisos']) && in_array('Linea_Factura',$_SESSION['permisos']))){
	
	//Requires del modelo y las vistas.
	require_once('../models/LINEA_FACTURA_Model.php'); 
	
	require_once('../views/LINEA_FACTURA_ADD_Vista.php');
	require_once('../views/LINEA_FACTURA_SHOW_Vista.php');
	require_once('../views/LINEA_FACTURA_EDIT_Vista.php');
	
	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php"); 
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php"); 
	}else{
		include("../locates/Strings_CASTELLANO.php"); 
	}
	
	//Recoge los datos del formulario de la vista en un objeto 'Linea factura'.
	function datosFormLinea(){
		
		$factura = $_POST['factura'];
		if(isset($_POST['servicio'])){
			$servicio = $_POST['servicio'];
		}else{
			$servicio = null;
		}
		if(isset($_POST['descripcion'])){
			$descripcion = $_POST['descripcion'];
		}else{
			$descripcion = null;
		}
	
		$linea = new linea($factura,null,$servicio, null, $descripcion);
		return $linea;
	}
	
	//Recoge del formulario los datos nuevos para modificar una linea de factura.
	function datosFormLineaModificar(){
		
		$factura = $_POST['factura'];
		$linea = $_POST['linea'];
		if(isset($_POST['importeN'])){
			$importe = $_POST['importeN'];
		}else{
			$importe = null;
		}
		if(isset($_POST['descripcionN'])){
			$descripcion = $_POST['descripcionN'];
		}else{
			$descripcion = null;
		}
	
		$linea = new linea($factura, $linea, null, $importe, $descripcion);
		return $linea;
	}
	
	//Si se pulsa la flecha para volver atrás redirige a la vista anterior.
	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista anterior.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			
			//Crea una nueva linea.
			case 'altaLinea':
				if(!isset($_POST['factura'])){
					$_GET['id'] = "buscarFactura";
					require_once('FACTURA_Controller.php');		
				}else if(!isset($_POST['servicio'])){
					new LineaFactura_Crear($_POST['factura']);
				}else{
					$linea = datosFormLinea();
					$mensaje = $linea->crear();
					?><script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					$_GET['id'] = "consultarFactura";
					require_once('FACTURA_Controller.php');
				}
				break;
			
			//Borra una linea.
			case 'bajaLinea':
			if(in_array('Linea_Factura_Delete',$_SESSION['permisos'])){
				$linea = datosFormLineaModificar();
				$mensaje = $linea->borrar();
				?><script>
					window.alert('<?php echo $strings[$mensaje]; ?>');
				</script><?php
				$_GET['id'] = "consultarFactura";
				require_once('FACTURA_Controller.php');
				break;
			}else echo "Permiso denegado.";
			
			//Modifica una linea.
			case 'modificarLinea':
				if(!isset($_POST['factura'])){
					$_GET['id'] = "buscarFactura";
					require_once('FACTURA_Controller.php');	
				}else if(!isset($_POST['importeN'])){
					$linea = datosFormLineaModificar();
					$array = $linea->consultarLinea();
					new LineaFactura_Editar($array);
				}else{
					$linea2 = datosFormLineaModificar();
					$mensaje = $linea2->modificar();
					?><script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					$_GET['id'] = "consultarFactura";
					require_once('FACTURA_Controller.php');
				}
				break;

		}
	}
}else echo "Permiso denegado.";
?>