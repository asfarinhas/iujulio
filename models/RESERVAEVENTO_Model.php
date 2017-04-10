<?php
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){

//Include de la función de conexión a la base de datos.
include_once('conectarBD.php');

class reservaEvento{

	var $id_Evento;
	var $id_Reserva;
	var $mysqli;

	//Constructor.
	function __construct($id_Evento,$id_Reserva){

		$this->id_Evento = $id_Evento;
		$this->id_Reserva = $id_Reserva;
	}
	//Getters.
	function getIdEvento(){
		return $this->id_Evento;
	}
	function getIdReserva(){
		return $this->id_Reserva;
	}
	
	//Añade una reserva de evento a la BD. Controla que no exista la reserva.
	//Si se había realizado un borrado lógico recupera el servicio.
	function crear(){

		$this->mysqli = conectarBD();

		$sql = "SELECT * FROM Reserva_Evento WHERE Id_Evento='".$this->id_Evento."' AND Borrado='0';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
			$sql = "SELECT * FROM Reserva_Evento WHERE Id_Evento='".$this->id_Evento."' AND Borrado='1';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows == 0){
				$sql = "INSERT INTO Reserva_Evento (Id_Evento,Id_Reserva) VALUES ('".$this->id_Evento."','".$this->id_Reserva."');";
				$this->mysqli->query($sql);
				return "añadido exito";
			}else{
				$sql = "UPDATE Reserva_Evento SET Borrado='0',Id_Evento='".$this->id_Evento."',Id_Reserva='".$this->id_Reserva."';";
				$this->mysqli->query($sql);
			  return "añadido exito";
			}
		}else return "ya existe";
	}

	//Realiza el borrado lógico de una reserva Espacio.
	function borrar(){

		$this->mysqli = conectarBD();

		$sql = "UPDATE Reserva_Evento SET Borrado='1' WHERE Id_Evento='".$this->id_Evento."';";
		if($this->mysqli->query($sql) === TRUE) {
			return "borrado exito";
		}else
			return "error borrado";
	}

	//Modifica los datos de una reserva de evento.
	//Si tenía borrado lógico lo sobreescribe y borra el viejo.
	function modificar($reservaEventoNueva){

			  $this->mysqli = conectarBD();
			  $aux=false;
				  if($reservaEventoNueva->getIdReserva() != null){
					  $sql= "UPDATE Reserva_Evento SET Id_Reserva='".$reservaEventoNueva->getIdReserva()."' WHERE Id_Evento='".$this->id_Evento."';";
					  $this->mysqli->query($sql);
					  $aux=true;
				  }
				  if($aux){
					  return 'modificacion exito';
				  }

	}

}

//Lista en un select todas las reservas de eventos para borrar. Controla que no se muestre el usuario logeado o admin para no borrarlo.
function listarReservaEventosBorrar(){

		$db = conectarBD();

		$sql = "SELECT Id_Evento FROM Reserva_Evento WHERE Borrado='0' ORDER BY Id_Evento;";
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

//Lista en un select todos las reservas de eventos para modificar.
function listarReservaEventosModificar(){

		$db = conectarBD();

		$sql = "SELECT Id_Evento FROM Reserva_Evento WHERE Borrado='0' ORDER BY Id_Evento;";
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
function verReservaEventos(){

	$db = conectarBD();

	$sql = "SELECT * FROM Reserva_Evento WHERE Borrado='0' ORDER BY Id_Evento;";
	$resultado = $db->query($sql);
	$db->close();
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			echo "<tr> <td>".$row['Id_Evento']."</td> <td>".$row['Id_Reserva']."</td> <td>";
		}
	}
}

//Recupera los datos de una reserva de evento en un array.
function mostrarReservaEvento($idEvento){

	$db = conectarBD();

	$sql = "SELECT * FROM Reserva_Evento WHERE Id_Evento='".$id_Evento."' AND Borrado='0';";
	$resultado = $db->query($sql);
	if ($resultado->num_rows == 1){
		$row = $resultado->fetch_assoc();
		return $row;
	}
}

//Muestra los datos de una reserva de evento concreta pasado por parámetro en formato tabla.
function consultarReservaEvento($idEvento){

	$db = conectarBD();

	$sql = "SELECT * FROM Reserva_Evento WHERE Id_Evento='".$id_Evento."' AND Borrado='0';";
	$resultado = $db->query($sql);
	if ($resultado->num_rows > 0){
		$row = $resultado->fetch_array();
		echo "<tr> <td>".$row['Id_Evento']."</td> <td>".$row['Id_Reserva']."</td> <td>";
	}
}

//Muestra los datos de una reserva de evento concreta pasado por parámetro en formato tabla
function consultarReservaEventoBorrar($serv){

  $db = conectarBD();
  if($serv->getIdEvento() !=null){
	  $sql = "SELECT * FROM Reserva_Evento WHERE Id_Evento='".$serv->getIdEvento()."' AND Borrado='0';";
	  $resultado = $db->query($sql);
	  if ($resultado->num_rows > 0){
		  $row = $resultado->fetch_array();
		  echo "<tr> <td>".$row['Id_Evento']."</td> <td>".$row['Id_Reserva']."</td> <td>";
	  }
  }
  if($serv->getIdReserva() != null){
	  $sql = "SELECT * FROM Reserva_Evento WHERE Id_Reserva='".$serv->getIdReserva()."' AND Borrado='0';";
	  $resultado = $db->query($sql);
	  if ($resultado->num_rows > 0){
		  $row = $resultado->fetch_array();
		  echo "<tr> <td>".$row['Id_Evento']."</td> <td>".$row['Id_Reserva']."</td> <td>";
	  }
  }
  

}else echo "Permiso denegado.";
?>