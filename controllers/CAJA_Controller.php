<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
	session_start();
}
//Comprueba si el usuario inició sesión y si tiene permisos antes de cargar la página.
if((isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0) || (isset($_SESSION['permisos']) && in_array('Caja',$_SESSION['permisos']))){ 
	
	//Requires del modelo y las vistas.
	require_once('../models/CAJA_Model.php'); 
	
	require_once('../views/CAJA_ADD_Vista.php'); 
	require_once('../views/CAJA_SHOW_Vista.php'); 
	require_once('../views/CAJA_EDIT_Vista.php'); 
	require_once('../views/CAJA_LIST_Vista.php'); 
	
	//Include del idioma elegido, o español por defecto.
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php"); 
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php"); 
	}else{
		include("../locates/Strings_CASTELLANO.php"); 
	}
	
	//Recoge los datos del formulario de la vista en un objeto 'Caja'.
	function datosFormCaja(){
		
		$movimiento = $_POST['movimiento'];
		$importe = $_POST['importe'];
		$fecha = $_POST['fecha'];
		
		if(isset($_POST['id'])){
			$id = $_POST['id'];
		}else{
			$id = null;
		}
		
		if(isset($_POST['comentario'])){
			$comentario = $_POST['comentario'];
		}else{
			$comentario = null;
		}
		
		$caja = new caja($id,$movimiento,$importe,$fecha,$comentario);
		return $caja;
	}
	
	//Recoge los datos del formulario de la vista en un objeto 'Caja' para realizar la búsqueda.
	function datosFormCajaBuscar(){
		
		if(isset($_POST['fecha']) && $_POST['fecha'] != ''){
			$fecha = date("Y-m-d",strtotime($_POST['fecha']));
		}else $fecha = null;

		if(isset($_POST['movimiento'])){
		$movimiento = $_POST['movimiento'];
		}else $movimiento = null;
		
		$caja = new caja(null,$movimiento,null,$fecha,null);
		return $caja;
	}
	
	//Recoge los datos del formulario de la vista en un objeto 'Caja'. para borrar.
	function datosFormCajaBorra(){
		
		$id = $_POST['id'];
		$movimiento = $_POST['movimiento'];
		$importe = $_POST['importe'];
		
		$caja = new caja($id,$movimiento,$importe,null,null);
		return $caja;
	}
	
	//Recoge los datos del formulario de la vista en un objeto 'Caja' para editar.
	function datosFormCajaEdit(){
		
		$id = $_POST['id'];
		$movimiento = $_POST['movimiento'];
		$importe = $_POST['importeN'];
		$comentario = $_POST['comentarioN'];
		
		$caja = new caja($id,$movimiento,$importe,null,$comentario);
		return $caja;
	}
	
	//Si se pulsa la flecha para volver atrás redirige a la vista anterior.
	//Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
	//Después invoca un método del modelo con los datos recibidos vía post de la vista.
	//Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista anterior.
	if(isset($_GET['id'])){
		$action = $_GET['id'];
		switch($action){
			
			//Crea un movimiento de caja.
			case 'altaCaja':	
				if(!isset($_POST['importe'])){
					new Caja_Crear();
				}else{
					$caja = datosFormCaja();
					$mensaje = $caja->crear();
					?><script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					unset($_POST['importe']);
					new Caja_Crear();
				}
				break;
			
			//Consulta los movimientos de uuna fecha dada.
			case 'consultarCaja':
			consultar:	
				if(!isset($_POST['movimiento'])){
					new Caja_Consultar();
				}else{
					$caja = datosFormCajaBuscar();
					$array = $caja->buscar();
					new Caja_Consultar($array);
				}
				break;	
			
			//Borra un movimiento.		
			case 'bajaCaja':
			if(in_array('Caja_Delete',$_SESSION['permisos'])){
				$caja = datosFormCajaBorra();
				$mensaje = $caja->borrar();
				?><script>
					window.alert('<?php echo $strings[$mensaje]; ?>');
				</script><?php
				unset($_POST['movimiento']);
				goto consultar;
				break;	
			}else echo "Permiso denegado.";
			
			//Edita un movimiento.
			case 'modificarCaja':
				if(!isset($_POST['movimiento'])){
					goto consultar;
				}else if(!isset($_POST['importeN'])){
					new Caja_Editar();
				}else{
					$caja = datosFormCajaEdit();
					$mensaje = $caja->editar();
					?><script>
						window.alert('<?php echo $strings[$mensaje]; ?>');
					</script><?php
					
					goto consultar;
				}
				break;
			
			//Muestra los movimientos de caja de un mes dado.
			case 'verCaja':
				if(!isset($_POST['fecha'])){
					$array = listarTodas(date("Y-m-d"));
					if($array == "no hay"){
						new Caja_Ver();
					}else{
						new Caja_Ver($array);
					}
				}else{
					$array = listarTodas($_POST['fecha']);
					if($array == "no hay"){
						new Caja_Ver();
					}else{
						new Caja_Ver($array);
					}
				}
				break;
		}
	}
}else echo "Permiso denegado.";
?>