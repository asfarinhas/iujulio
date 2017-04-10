<?php
//Comprueba si el usuario inició sesión y si tiene permisos antes de cargar la página.
if((isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0) || (isset($_SESSION['permisos']) && in_array('Linea_Factura',$_SESSION['permisos']))){ 

//Include de la función para conectarse a la BD.
include_once('conectarBD.php');

class linea{
	
	var $factura;
	var $linea;
	var $servicio; 
	var $importe;
	var $descripcion;
	var $mysqli;
	
	//Constructor. 
	function __construct($factura,$linea=null,$servicio=null, $importe=null, $descripcion=null){
		
		$this->factura = $factura;
		$this->linea = $linea;
		$this->servicio = $servicio;
		$this->importe = $importe;
		$this->descripcion = $descripcion;
	}
	
	//Crea la linea de la factura si no existe una para ese servicio, con o sin descripción. Actualiza el total de la factura con su importe.
	function crear(){
		
		$this->mysqli = conectarBD();
		
		$sql = "SELECT * FROM Linea_Factura WHERE Id_Factura='".$this->factura."' AND Id_Servicio='".$this->servicio."' AND Borrado='0';";
		$resultado = $this->mysqli->query($sql);	
		if ($resultado->num_rows == 0){
			$sql = "SELECT Precio FROM Servicio WHERE Id_Servicio='".$this->servicio."' AND Borrado='0';";
			$resultado = $this->mysqli->query($sql);
			$row = $resultado->fetch_array();
			if($this->descripcion == null){
				$sql = "INSERT INTO Linea_Factura (Id_Factura,Id_Servicio,Importe) VALUES ('".$this->factura."','".$this->servicio."','".$row['Precio']."');";
				$this->mysqli->query($sql);
			}else{
				$sql = "INSERT INTO Linea_Factura (Id_Factura,Id_Servicio,Importe,Descripcion) VALUES ('".$this->factura."','".$this->servicio."','".$row['Precio']."','".$this->descripcion."');";
				$this->mysqli->query($sql);
			}
			$sql = "UPDATE Factura SET Total=Total+(".$row['Precio'].") WHERE Id_Factura='".$this->factura."';";
			$this->mysqli->query($sql);
			return "añadido exito";	
		}else{
			return "ya existe";	
		}
	}
	
	//Devuelve array con todas las lineas de una factura.
	function consultar(){
		
		$this->mysqli = conectarBD();
		
		$sql = "SELECT Linea_Factura.Id_Linea_Factura,Linea_Factura.Id_Factura,Servicio.Nombre,Linea_Factura.Importe,Linea_Factura.Descripcion FROM Linea_Factura INNER JOIN Servicio WHERE Linea_Factura.Id_Factura='".$this->factura."' AND Linea_Factura.Id_Servicio=Servicio.Id_Servicio AND Linea_Factura.Borrado='0';";
		$resultado = $this->mysqli->query($sql);	
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()){
				$array[] = $row;
			}
			return $array;
		}
	}
	
	//Devuelve array con una linea de factura.
	function consultarLinea(){
		
		$this->mysqli = conectarBD();
		
		$sql = "SELECT Linea_Factura.Id_Linea_Factura,Linea_Factura.Id_Factura,Servicio.Nombre,Linea_Factura.Importe,Linea_Factura.Descripcion FROM Linea_Factura INNER JOIN Servicio WHERE Linea_Factura.Id_Linea_Factura='".$this->linea."' AND Linea_Factura.Id_Servicio=Servicio.Id_Servicio AND Linea_Factura.Borrado='0';";
		$resultado = $this->mysqli->query($sql);	
		if ($resultado->num_rows == 1){
			$row = $resultado->fetch_array();
			return $row;
		}
	}
	
	//Borra la linea seleccionada y resta su importe al total de la factura.
	function borrar(){
		
		$this->mysqli = conectarBD();
		
		$sql = "UPDATE Linea_Factura SET Borrado='1' WHERE Id_Linea_Factura='".$this->linea."';";
		$this->mysqli->query($sql);
		$sql = "SELECT Importe FROM Linea_Factura WHERE Id_Linea_Factura='".$this->linea."';";
		$resultado = $this->mysqli->query($sql);
		$row = $resultado->fetch_array();
		$sql = "UPDATE Factura SET Total=Total-(".$row['Importe'].") WHERE Id_Factura='".$this->factura."';";
		$this->mysqli->query($sql);
		
		return "borrado exito";
	}
	
	//Modifica los datos de la linea seleccionada. Primero resta el importe al total de la factura y luego añade el nuevo importe.
	function modificar(){
		
		$this->mysqli = conectarBD();
		
		$sql = "SELECT Importe FROM Linea_Factura WHERE Id_Linea_Factura='".$this->linea."';";
		$resultado = $this->mysqli->query($sql);
		$row = $resultado->fetch_array();
		$sql = "UPDATE Factura SET Total=Total-(".$row['Importe'].") WHERE Id_Factura='".$this->factura."';";
		$this->mysqli->query($sql);
		$sql = "UPDATE Linea_Factura SET Importe='".$this->importe."',Descripcion='".$this->descripcion."' WHERE Id_Linea_Factura='".$this->linea."';";
		$this->mysqli->query($sql);
		$sql = "UPDATE Factura SET Total=Total+(".$this->importe.") WHERE Id_Factura='".$this->factura."';";
		$this->mysqli->query($sql);
		
		return "modificacion exito";
	}
}

//Muestra los servicios cuyo nombre contenga la cadena pasada por parámetro.
function verServicios($nombre){
	
	$bd = conectarBD();
	
	$sql = "SELECT Nombre,Precio,Id_Servicio FROM Servicio WHERE Nombre LIKE '%".$nombre."%' AND Borrado='0';";
	$resultado = $bd->query($sql);	
	if ($resultado->num_rows > 0){
		$row = $resultado->fetch_array();
		echo "<label class='radio-inline'><input type='radio' name='servicio' value='".$row['Id_Servicio']."' checked>".$row['Nombre']." (".$row['Precio']."€)</label><br>";
		while($row = $resultado->fetch_array()) {
			echo "<label class='radio-inline'><input type='radio' name='servicio' value='".$row['Id_Servicio']."'>".$row['Nombre']." (".$row['Precio']."€)</label><br>";
		}
	}else return "no hay";
}

}else echo "Permiso denegado.";
?>