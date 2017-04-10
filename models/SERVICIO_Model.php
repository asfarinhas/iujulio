<?php
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){

//Include de la función de conexión a la base de datos.
include_once('conectarBD.php');

class servicio{

	var $idServicio;
	var $idTrabajador;
	var $nombre;
	var $precio;
	var $descripcion;
	var $mysqli;

	//Constructor.
	function __construct($idServicio,$idTrabajador,$nombre=null,$precio=null,$descripcion=null){

		$this->idServicio = $idServicio;
		$this->idTrabajador = $idTrabajador;
		$this->nombre = $nombre;
		$this->precio = $precio;
		$this->descripcion = $descripcion;
	}
	//Getters.
	function getIdServicio(){
		return $this->idServicio;
	}
	function getIdTrabajador(){
		return $this->idTrabajador;
	}
	function getNombre(){
		return $this->nombre;
	}
	function getPrecio(){
		return $this->precio;
	}
	function getDescripcion(){
		return $this->descripcion;
	}


	//Añade un servicio a la BD. Controla que no exista ya el servicio.
	//Si se había realizado un borrado lógico recupera el servicio.
	function crear(){

		$this->mysqli = conectarBD();

		$sql = "SELECT * FROM Servicio WHERE Id_Servicio='".$this->idServicio."' AND Borrado='0';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
			$sql = "SELECT * FROM Servicio WHERE Id_Servicio='".$this->idServicio."' AND Borrado='1';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows == 0){
				$sql = "INSERT INTO Servicio (Id_Servicio,Id_Trabajador,Nombre,Precio,Descripcion) VALUES ('".$this->idServicio."','".$this->idTrabajador."','".$this->nombre."','".$this->precio."','".$this->descripcion."');";
				$this->mysqli->query($sql);
				return "añadido exito";
			}else{
				$sql = "UPDATE Servicio SET Borrado='0',Id_Servicio='".$this->idServicio."',Id_Trabajador='".$this->idTrabajador."',Nombre='".$this->nombre."',Precio='".$this->precio."',Descripcion='".$this->descripcion."';";
				$this->mysqli->query($sql);
			  return "añadido exito";
			}
		}else return "ya existe";
	}

	//Realiza el borrado lógico de un Servicio.
	function borrar(){

		$this->mysqli = conectarBD();

		$sql = "UPDATE Servicio SET Borrado='1' WHERE Id_Servicio='".$this->idServicio."';";
		if($this->mysqli->query($sql) === TRUE) {
			return "borrado exito";
		}else
			return "error borrado";
	}

	//Modifica los datos de un Servicio.
	//Si tenía borrado lógico lo sobreescribe y borra el viejo.
	function modificar($servicioNuevo){

			  $this->mysqli = conectarBD();
			  $aux=false;
				  if($servicioNuevo->getIdTrabajador() != null){
					  $sql= "UPDATE Servicio SET Id_Trabajador='".$servicioNuevo->getIdTrabajador()."' WHERE Id_Servicio='".$this->idServicio."';";
					  $this->mysqli->query($sql);
					  $aux=true;
				  }
				  if($servicioNuevo->getPrecio() != null){
					  $sql= "UPDATE Servicio SET Precio='".$servicioNuevo->getPrecio()."' WHERE Id_Servicio='".$this->idServicio."';";
					  $this->mysqli->query($sql);
					  $aux=true;
				  }
				  if($servicioNuevo->getNombre() != null){
					  $sql= "UPDATE Servicio SET Nombre='".$servicioNuevo->getNombre()."' WHERE Id_Servicio='".$this->idServicio."';";
					  $this->mysqli->query($sql);
					  $aux=true;
				  }
				  if($servicioNuevo->getDescripcion() != null){
					  $sql= "UPDATE Servicio SET Descripcion='".$servicioNuevo->getDescripcion()."' WHERE Id_Servicio='".$this->idServicio."';";
					  $this->mysqli->query($sql);
					  $aux=true;
				  }
				  if($aux){
					  return 'modificacion exito';
				  }

	}

}

//Lista en un select todos los servicios para borrar. Controla que no se muestre el usuario logeado o admin para no borrarlo.
function listarServiciosBorrar(){

		$db = conectarBD();

		$sql = "SELECT Id_Servicio FROM Servicio WHERE Borrado='0' ORDER BY Id_Servicio;";
		$resultado = $db->query($sql);
		$db->close();
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()) {
				if(strcmp($row['Usuario'],$_SESSION['user']) != 0 && strcmp($row['Nombre_Grupo'],"Admin") != 0)
					echo "<option value='".$row['Id_Servicio']."'>".$row['Id_Servicio']."</option><tr>";
			}
		}
		$db->close();
}

//Lista en un select todos los Servicios para modificar.
function listarServiciosModificar(){

		$db = conectarBD();

		$sql = "SELECT Id_Servicio FROM Servicio WHERE Borrado='0' ORDER BY Id_Servicio;";
		$resultado = $db->query($sql);
		$db->close();
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()) {
				echo "<option value='".$row['Id_Servicio']."'>".$row['Id_Servicio']."</option><tr>";
			}
		}
		$db->close();
}

//Lista todos los servicios en una tabla.
function verServicios(){

	$db = conectarBD();

	$sql = "SELECT * FROM Servicio WHERE Borrado='0' ORDER BY Id_Servicio;";
	$resultado = $db->query($sql);
	$db->close();
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			echo "<tr> <td>".$row['Id_Servicio']."</td> <td>".$row['Id_Trabajador']."</td> <td>".$row['Nombre']."</td> <td>".$row['Precio']."</td> <td>".$row['Descripcion']."</td> <td>";
		}
	}
}

//Recupera los datos de un servicio en un array.
function mostrarServicio($idServicio){

	$db = conectarBD();

	$sql = "SELECT * FROM Servicio WHERE Id_Servicio='".$idServicio."' AND Borrado='0';";
	$resultado = $db->query($sql);
	if ($resultado->num_rows == 1){
		$row = $resultado->fetch_assoc();
		return $row;
	}
}

//Muestra los datos de un servicio concreto pasado por parámetro en formato tabla.
function consultarServicio($idServicio){

	$db = conectarBD();

	$sql = "SELECT * FROM Servicio WHERE Id_Servicio='".$idServicio."' AND Borrado='0';";
	$resultado = $db->query($sql);
	if ($resultado->num_rows > 0){
		$row = $resultado->fetch_array();
		echo "<tr> <td>".$row['Id_Servicio']."</td> <td>".$row['Id_Trabajador']."</td> <td>".$row['Nombre']."</td> <td>".$row['Precio']."</td> <td>".$row['Descripcion']."</td> <td>";
	}
}

//Muestra los datos de un servicio concreto pasado por parámetro en formato tabla
function consultarServicioBorrar($serv){

  $db = conectarBD();
  if($serv->getIdServicio() !=null){
	  $sql = "SELECT * FROM Servicio WHERE Id_Servicio='".$serv->getIdServicio()."' AND Borrado='0';";
	  $resultado = $db->query($sql);
	  if ($resultado->num_rows > 0){
		  while($row = $resultado->fetch_array()) {
		  echo "<tr> <td>".$row['Id_Servicio']."</td> <td>".$row['Id_Trabajador']."</td> <td>".$row['Nombre']."</td> <td>".$row['Precio']."</td> <td>".$row['Descripcion']."</td> <td>";
	  		}
	  }
  }
  if($serv->getIdTrabajador() != null){
	  $sql = "SELECT * FROM Servicio WHERE Id_Trabajador='".$serv->getIdTrabajador()."' AND Borrado='0';";
	  $resultado = $db->query($sql);
	  if ($resultado->num_rows > 0){
		  while($row = $resultado->fetch_array()) {
		  echo "<tr> <td>".$row['Id_Servicio']."</td> <td>".$row['Id_Trabajador']."</td> <td>".$row['Nombre']."</td> <td>".$row['Precio']."</td> <td>".$row['Descripcion']."</td> <td>";
	  		}
	  }
  }
  if($serv->getNombre() != null){
	  $sql = "SELECT * FROM Servicio WHERE Nombre='".$serv->getNombre()."' AND Borrado='0';";
	  $resultado = $db->query($sql);
	  if ($resultado->num_rows > 0){
		  while($row = $resultado->fetch_array()) {
		  echo "<tr> <td>".$row['Id_Servicio']."</td> <td>".$row['Id_Trabajador']."</td> <td>".$row['Nombre']."</td> <td>".$row['Precio']."</td> <td>".$row['Descripcion']."</td> <td>";
	  		}
	  }
  }
  if($serv->getPrecio() != null){
	  $sql = "SELECT * FROM Servicio WHERE Precio='".$serv->getPrecio()."' AND Borrado='0';";
	  $resultado = $db->query($sql);
	  if ($resultado->num_rows > 0){
		  while($row = $resultado->fetch_array()) {
		  echo "<tr> <td>".$row['Id_Servicio']."</td> <td>".$row['Id_Trabajador']."</td> <td>".$row['Nombre']."</td> <td>".$row['Precio']."</td> <td>".$row['Descripcion']."</td> <td>";
	  	}
	  }
  }
  if($serv->getDescripcion() != null){
	  $sql = "SELECT * FROM Servicio WHERE Descripcion='".$serv->getDescripcion()."' AND Borrado='0';";
	  $resultado = $db->query($sql);
	  if ($resultado->num_rows > 0){
		  while($row = $resultado->fetch_array()) {
		  echo "<tr> <td>".$row['Id_Servicio']."</td> <td>".$row['Id_trabajador']."</td> <td>".$row['Nombre']."</td> <td>".$row['Precio']."</td> <td>".$row['Descripcion']."</td> <td>";
	  	}
	  }
  }
}

}else echo "Permiso denegado.";
?>
