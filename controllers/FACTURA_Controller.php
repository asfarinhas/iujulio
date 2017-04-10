<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si tiene permisos antes de cargar la página.
if((isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0) || (isset($_SESSION['permisos']) && in_array('Factura',$_SESSION['permisos']))){ 
	
	//Requires del modelo y las vistas.
	require_once('../models/FACTURA_Model.php'); 
	
	require_once('LINEA_FACTURA_Controller.php');
	require_once('../views/mensaje_Vista.php');
	require_once('../views/FACTURA_ADD_Vista.php');
	require_once('../views/FACTURA_SHOW_Vista.php');
	require_once('../views/FACTURA_LIST_Vista.php');
	require_once('../views/FACTURA_LIST_ALL_Vista.php');
	require_once('../views/FACTURA_EDIT_Vista.php');
	require_once('../views/FACTURA_EDIT_COBRO_Vista.php');
	
	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php"); 
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php"); 
	}else{
		include("../locates/Strings_CASTELLANO.php"); 
	}
	
	//Recoge los datos del cliente del formulario en un objeto 'Factura'.
	function datosFormFactura(){
		
		$cliente = $_POST['cliente'];
		
		$factura = new factura($cliente,null,null,null);
		return $factura;
	}
	
	//Recoge los datos del formulario con todos los atributos para crear un objeto Factura.
	function datosFormFacturaID(){
		
		$id = $_POST['factura'];
		
		if(isset($_POST['total'])){
			$total = $_POST['total'];
		}else{
			$total = null;
		}
		if(isset($_POST['pagada'])){
			$pagada = $_POST['pagada'];
		}else{
			$pagada = null;
		}
		if(isset($_POST['fecha'])){
			$fecha = date("Y-m-d",strtotime($_POST['fecha']));
		}else{
			$fecha = null;
		}
		
		$factura = new factura(null,$id,$fecha,$total,$pagada);
		return $factura;
	}
	
	//Recoge los datos del formulario con todos los atributos y un objeto Factura con los datos a editar.
	function datosFormFacturaEdit(){
		
		$id = $_POST['factura'];
		
		if(isset($_POST['totalN'])){
			$total = $_POST['totalN'];
		}else{
			$total = null;
		}
		if($_POST['fechaN'] != null && $_POST['fechaN'] != ''){
			$fecha = date("Y-m-d",strtotime($_POST['fechaN']));
		}else{
			$fecha = null;
		}
		
		$factura = new factura(null,$id,$fecha,$total,null);
		return $factura;
	}
	
	//Recoge los datos del formulario con todos los atributos y un objeto Factura con los datos a buscar.
	function datosFormFacturaBuscar(){
		
		if(isset($_POST['cliente'])){
			$cliente = $_POST['cliente'];
		}else{
			$cliente = null;
		}
		if(strcmp($_POST['pagada'],'todas') == 0){
			$pagada = null;
		}else{
			$pagada = $_POST['pagada'];
		}
		if(strcmp($_POST['fecha'],'todas') == 0){
			$fecha = 'todas';
		}else{
			if($_POST['fecha2'] != ''){
				$fecha = date("Y-m-d",strtotime($_POST['fecha2']));
			}else $fecha = null;
		}
		
		$factura = new factura($cliente,null,$fecha,null,$pagada);
		return $factura;
	}
	
	//Si se pulsa la flecha para volver atrás redirige a la vista anterior.
	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista anterior.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			
			//Crea una nueva factura.
			case 'altaFactura':	
				if(!isset($_POST['cliente'])){
					new Factura_Crear();			
				}else{
					$factura = datosFormFactura();
					$mensaje = $factura->crear();
					if($mensaje != "factura sin cerrar"){
						new LineaFactura_Crear($mensaje); //Crea una linea de factura cada vez que se crea una factura.
					}else{
						?><script>
							window.alert('<?php echo $strings[$mensaje]; ?>');
						</script><?php
						unset($_POST['client']);
						new Factura_Crear();
					}
				}
				break;
			
			//Busca todas las facturas de un cliente.
			case 'buscarFactura':
			buscar:
				if(!isset($_POST['cliente'])){
					new Factura_Buscar();
				}else{
					$factura = datosFormFacturaBuscar();
					$array = $factura->buscar();
					if($array != "no hay"){
						new Factura_Buscar($array);
					}else{
						?><script>
							window.alert('<?php echo $strings[$mensaje]; ?>');
						</script><?php
						unset($_POST['cliente']);
						new Factura_Buscar();
					}
				}
				break;	
			
			//Muestra los datos de una factura y de sus lineas.
			case 'consultarFactura':
			consultar:
				if(!isset($_POST['factura'])){
					new Factura_Buscar();
				}else{
					$factura = datosFormFacturaID();
					$arrayF = $factura->consultar();
					$linea = datosFormLinea();
					$arrayL = $linea->consultar();
					
					new Factura_Consultar($arrayF);
					new Linea_Factura_Consultar($arrayL,$arrayF);
				}
				break;
			
			//Lista todas las facturas cobradas por mes.
			case 'listarFacturas':
				if(!isset($_POST['fecha'])){
					$array = listarCobradas(date("Y-m-d"));
					if($array == "no hay"){
						new Factura_Listar();
					}else{
						new Factura_Listar($array);
					}
				}else{
					$array = listarCobradas($_POST['fecha']);
					if($array == "no hay"){
						new Factura_Listar();
					}else{
						new Factura_Listar($array);
					}
				}

				break;
			
			//Borra una factura.
			case 'bajaFactura':
			if(in_array('Factura_Delete',$_SESSION['permisos'])){
				$factura = datosFormFacturaID();
				$mensaje = $factura->borrar();
				?><script>
					window.alert('<?php echo $strings[$mensaje]; ?>');
				</script><?php
				goto buscar;
				break;	
			}else echo "Permiso denegado.";
			
			//Modificar fecha de cierre de la factura, así como el total.
			case 'modificarFactura':
				if(!isset($_POST['factura'])){
					goto consultar;
				}else if(!isset($_POST['totalN'])){
					new Factura_Editar();
				}else{
					$factura = datosFormFacturaEdit();
					$mensaje = $factura->editar();
					?><script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					goto consultar;
				}
				break;
			
			//Modifica el estado de cobro con el tipo de pago recibido.
			case 'cobrarFactura':
				if(!isset($_POST['total'])){
					goto consultar;
				}else if(!isset($_POST['pagada'])){
					new Factura_Cobrar();
				}else{
					$factura = datosFormFacturaID();
					$factura->cobrar();
					goto consultar;
				}
				break;	
			
			//Genera el pdf de la factura.			
			case 'generarFactura':
				$factura = datosFormFacturaID();
				$arrayF = $factura->consultar();
				$linea = datosFormLinea();
				$arrayL = $linea->consultar();
				include_once("../facturas/facturas.php");
				break;
		}
	}
}else echo "Permiso denegado.";
?>