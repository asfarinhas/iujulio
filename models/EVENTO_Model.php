<?php	
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){

//Include de la función de conexión a la base de datos.
include_once('conectarBD.php');

class evento{
	
	var $id_evento;
	var $descripcion;
	var $nombre;
	var $mysqli;

	//Contructor
	function__construct($id_evento,$descripcion=null,$nombre=null){
		$this->id_evento=$id_evento;
		$this->descripcion=$descripcion;
		$this->nombre=$nombre;
	}
	
	//Getters
	
	function getId_evento(){
		return $this->id_evento;
	}
	
	function getDescripcion(){
		return $this->descripcion;
	}
	
	function getNombre(){
		return $this->nombre;
	}
	
	//Añade un evento a la BD. Controla que no exista ya el servicio.
	//Si se había realizado un borrado lógico recupera el servicio.
	
	function crear(){

		$this->mysqli = conectarBD();

		$sql = "SELECT * FROM Evento WHERE Id_Evento='".$this->id_evento."' AND Borrado='0';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
			$sql = "SELECT * FROM Evento WHERE Id_Evento='".$this->id_evento."' AND Borrado='1';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows == 0){
				$sql = "INSERT INTO Evento (Id_Evento, Descripcion, Nombre) VALUES ('".$this->id_evento."','".$this->descripcion."','".$this->nombre."');";
				$this->mysqli->query($sql);
				return "añadido exito";
			}else{
				$sql = "UPDATE Evento SET Borrado='0',Id_Evento='".$this->id_evento."',Descripcion='".$this->descripcion."',Nombre='".$this->nombre."';";
				$this->mysqli->query($sql);
			  return "añadido exito";
			}
		}else return "ya existe";
	}
	
	//Realiza el borrado lógico de un Evento.
	function borrar(){

		$this->mysqli = conectarBD();

		$sql = "UPDATE Evento SET Borrado='1' WHERE Id_Evento='".$this->id_evento."';";
		if($this->mysqli->query($sql) === TRUE) {
			return "borrado exito";
		}else
			return "error borrado";
	}
	//Modifica los datos de un Evento.
	//Si tenía borrado lógico lo sobreescribe.
	function modificar($eventoNuevo){

			  $this->mysqli = conectarBD();
			  $aux=false;
				  if($eventoNuevo->getDescripcion() != null){
					  $sql= "UPDATE Evento SET descripcion='".$eventoNuevo->getDescripcion()."' WHERE Id_Evento='".$this->id_evento."';";
					  $this->mysqli->query($sql);
					  $aux=true;
				  }
				  if($eventoNuevo->getNombre() != null){
					  $sql= "UPDATE Evento SET Nombre='".$eventoNuevo->getNombre()."' WHERE Id_Evento='".$this->id_evento."';";
					  $this->mysqli->query($sql);
					  $aux=true;
				  }
				   if($aux){
					  return 'modificacion exito';
				  }

	}

}
//Lista en un select todos los servicios para borrar. Controla que no se muestre el usuario logeado o admin para no borrarlo.
function listarEventosBorrar(){

		$db = conectarBD();

		$sql = "SELECT Id_Evento FROM Evento WHERE Borrado='0' ORDER BY Id_Evento;";
		$resultado = $db->query($sql);
		$db->close();
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()) {
				if(strcmp($row['Usuario'],$_SESSION['user']) != 0 && strcmp($row['Nombre_Grupo'],"Admin") != 0)
					echo "<option value='".$row['Id_Evento']."'>".$row['Id_Evento']."</option><tr>";
			}
		}
		$db->close();
}
//Lista en un select todos los Eventos para modificar.
function listarEventosModificar(){

		$db = conectarBD();

		$sql = "SELECT Id_Evento FROM Evento WHERE Borrado='0' ORDER BY Id_Evento;";
		$resultado = $db->query($sql);
		$db->close();
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()) {
				echo "<option value='".$row['Id_Evento']."'>".$row['Id_Evento']."</option><tr>";
			}
		}
		$db->close();
}

//Lista todos los servicios en una tabla.
function verEventos(){

	$db = conectarBD();

	$sql = "SELECT * FROM Evento WHERE Borrado='0' ORDER BY Id_Evento;";
	$resultado = $db->query($sql);
	$db->close();
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			echo "<tr> <td>".$row['Id_Evento']."</td> <td>".$row['Descripcion']."</td> <td>".$row['Nombre']."</td> <td>";
		}
	}
}
//Recupera los datos de un evento en un array.
function mostrarEvento($id_evento){

	$db = conectarBD();

	$sql = "SELECT * FROM Evento WHERE Id_Evento='".$id_evento."' AND Borrado='0';";
	$resultado = $db->query($sql);
	if ($resultado->num_rows == 1){
		$row = $resultado->fetch_assoc();
		return $row;
	}
}
//Muestra los datos de un servicio concreto pasado por parámetro en formato tabla.
function consultarEvento($id_evento){

	$db = conectarBD();

	$sql = "SELECT * FROM Evento WHERE Id_Evento='".$id_evento."' AND Borrado='0';";
	$resultado = $db->query($sql);
	if ($resultado->num_rows > 0){
		$row = $resultado->fetch_array();
		echo "<tr> <td>".$row['Id_Evento']."</td> <td>".$row['Descripcion']."</td> <td>".$row['Nombre']."</td> <td>";
	}
}
//Muestra los datos de un evento concreto pasado por parámetro en formato tabla
function consultarEventoBorrar($serv){

  $db = conectarBD();
  if($serv->getIdEvento() !=null){
	  $sql = "SELECT * FROM Evento WHERE Id_Evento='".$serv->getIdEvento()."' AND Borrado='0';";
	  $resultado = $db->query($sql);
	  if ($resultado->num_rows > 0){
		  $row = $resultado->fetch_array();
		  echo "<tr> <td>".$row['Id_Evento']."</td> <td>".$row['Nombre']."</td> <td>".$row['Descripcion']."</td> <td>";
	  }
  }
 
  if($serv->getNombre() != null){
	  $sql = "SELECT * FROM Evento WHERE Nombre='".$serv->getNombre()."' AND Borrado='0';";
	  $resultado = $db->query($sql);
	  if ($resultado->num_rows > 0){
		  $row = $resultado->fetch_array();
		  echo "<tr> <td>".$row['Id_Evento']."</td> <td>".$row['Nombre']."</td> <td>".$row['Descripcion']."</td> <td>";
	  }
  }
  
  if($serv->getDescripcion() != null){
	  $sql = "SELECT * FROM Evento WHERE Descripcion='".$serv->getDescripcion()."' AND Borrado='0';";
	  $resultado = $db->query($sql);
	  if ($resultado->num_rows > 0){
		  $row = $resultado->fetch_array();
		  echo "<tr> <td>".$row['Id_Evento']."</td> <td>".$row['Nombre']."</td> <td>".$row['Descripcion']."</td> <td>";
	  }
  }
}
}else echo "Permiso denegado.";
?>
	
	





























?>