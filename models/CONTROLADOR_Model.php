<?php
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){ 

//Include de la función de conexión a la base de datos.
include_once('conectarBD.php');

class controlador{
	
	var $accion;
	var $controlador;
	var $mysqli;
	
	//Constructor. La acción puede ser null.
	function __construct($controlador,$accion=null){
		
		$this->accion = $accion;
		$this->controlador = $controlador;
	}
	//Getters
	function getControlador(){
		return $this->controlador;
	}
	
	function getAccion(){
		return $this->accion;
	}
	
	//Añade un controlador a la BD en base al controlador recibido del controller. Controla que no exista el controlador.
	//Añade las acciones por defecto: add, delete, edit, show y list.
	//Si se había realizado un borrado lógico recupera el controlador.
	function crearControlador(){
		
		$this->mysqli = conectarBD();
		
		$sql = "SELECT * FROM Controlador WHERE Nombre_Controlador='".$this->controlador."';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
			$sql = "INSERT INTO Controlador (Nombre_Controlador,Accion) VALUES ('".$this->controlador."','Add');";
			$this->mysqli->query($sql);	
			$sql = "INSERT INTO Controlador (Nombre_Controlador,Accion) VALUES ('".$this->controlador."','Delete');";
			$this->mysqli->query($sql);	
			$sql = "INSERT INTO Controlador (Nombre_Controlador,Accion) VALUES ('".$this->controlador."','Edit');";
			$this->mysqli->query($sql);	
			$sql = "INSERT INTO Controlador (Nombre_Controlador,Accion) VALUES ('".$this->controlador."','Show');";
			$this->mysqli->query($sql);	
			$sql = "INSERT INTO Controlador (Nombre_Controlador,Accion) VALUES ('".$this->controlador."','List');";
			$this->mysqli->query($sql);				
			return "añadido exito";
		}else{
			$sql = "SELECT * FROM Controlador WHERE Nombre_Controlador='".$this->controlador."' AND Borrado='1';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows > 0){
				$sql = "UPDATE Controlador SET Borrado='0' WHERE Nombre_Controlador='".$this->controlador."' AND Accion='Add';";
				$this->mysqli->query($sql);	
				$sql = "UPDATE Controlador SET Borrado='0' WHERE Nombre_Controlador='".$this->controlador."' AND Accion='Delete';";
				$this->mysqli->query($sql);	
				$sql = "UPDATE Controlador SET Borrado='0' WHERE Nombre_Controlador='".$this->controlador."' AND Accion='Edit';";
				$this->mysqli->query($sql);	
				$sql = "UPDATE Controlador SET Borrado='0' WHERE Nombre_Controlador='".$this->controlador."' AND Accion='Show';";
				$this->mysqli->query($sql);	
				$sql = "UPDATE Controlador SET Borrado='0' WHERE Nombre_Controlador='".$this->controlador."' AND Accion='List';";
				$this->mysqli->query($sql);	
				return "añadido exito";				
			}else return "ya existe";
		}
	}
	
	//Añade una acción al controlador. Si estaba con borrado lógico se recupera.
	function crearAccion(){
		
		$this->mysqli = conectarBD();
		
		$sql = "SELECT * FROM Controlador WHERE Nombre_Controlador='".$this->controlador."' AND Accion='".$this->accion."';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
			$sql = "INSERT INTO Controlador (Nombre_Controlador,Accion) VALUES ('".$this->controlador."','".$this->accion."');";
			$this->mysqli->query($sql);		
			return "añadido exito";
		}else{
			$sql = "SELECT * FROM Controlador WHERE Nombre_Controlador='".$this->controlador."' AND Accion='".$this->accion."' AND Borrado='1';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows == 1){
				$sql = "UPDATE Controlador SET Borrado='0' WHERE Nombre_Controlador='".$this->controlador."' AND Accion='".$this->accion."';";
				$this->mysqli->query($sql);	
				return "añadido exito";				
			}else return "ya existe";
		}
	}
	
	//Borrado lógico de todas las tuplas de un mismo controlador si no se pasa acción, o bien controlador y acción de una tupla.
	//Borra las tuplas de permisos sobre el controlador.
	function borrar(){

		$this->mysqli = conectarBD();
		
		if($this->accion != null){
			$sql = "UPDATE Controlador SET Borrado='1' WHERE Nombre_Controlador='".$this->controlador."' AND Accion='".$this->accion."';";
			if($this->mysqli->query($sql) === TRUE) {
				$sql = "SELECT * FROM Permisos WHERE Nombre_Controlador='".$this->controlador."' AND Accion='".$this->accion."';";
				$resultado = $this->mysqli->query($sql);
				if ($resultado->num_rows > 0){
					$sql = "DELETE FROM Permisos WHERE Nombre_Controlador='".$this->controlador."' AND Accion='".$this->accion."';";
					$this->mysqli->query($sql);
				}
				return "borrado exito";
			}else
				return "error borrado";
		}else{
			$sql = "UPDATE Controlador SET Borrado='1' WHERE Nombre_Controlador='".$this->controlador."';";
			if($this->mysqli->query($sql) === TRUE) {
				$sql = "SELECT * FROM Permisos WHERE Nombre_Controlador='".$this->controlador."';";
				$resultado = $this->mysqli->query($sql);
				if ($resultado->num_rows > 0){
					$sql = "DELETE FROM Permisos WHERE Nombre_Controlador='".$this->controlador."';";
					$this->mysqli->query($sql);
				}
				return "borrado exito";
			}else
				return "error borrado";
		}	
	}
	
	//Modifica el controlador-acción recibido del controller. Controla que no exista el nuevo controlador.
	//Si tenía borrado lógico lo sobreescribe y borra el viejo.
	function modificar($controladorNuevo){

		$this->mysqli = conectarBD();
		
		$sql = "SELECT * FROM Controlador WHERE Nombre_Controlador='".$controladorNuevo->getControlador()."' AND Accion='".$controladorNuevo->getAccion()."';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
			$sql= "UPDATE Controlador SET Nombre_Controlador='".$controladorNuevo->getControlador()."',Accion='".$controladorNuevo->getAccion()."' WHERE Nombre_Controlador='".$this->controlador."' AND Accion='".$this->accion."';";
			if($this->mysqli->query($sql) === TRUE) {
				return "modificacion exito";
			}else{
				return "error modificacion";
			}
		}else {
			$sql = "SELECT * FROM Controlador WHERE Nombre_Controlador='".$controladorNuevo->getControlador()."' AND Accion='".$controladorNuevo->getAccion()."' AND Borrado='1';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows == 1){
				$sql = "DELETE FROM Controlador WHERE Nombre_Controlador='".$this->getControlador()."' AND Accion='".$this->getAccion()."';";
				$this->mysqli->query($sql);
				$sql = "UPDATE Controlador SET Borrado='0' WHERE Nombre_Controlador='".$controladorNuevo->getControlador()."' AND Accion='".$controladorNuevo->getAccion()."';";
				$this->mysqli->query($sql);	
				return "añadido exito";				
			}else return "ya existe";
		}
	}	
}

//Lista en un select todos los controladores registrados.
function listarControlador(){
	
		$db = conectarBD();
		
		$sql = "SELECT DISTINCT Nombre_Controlador FROM Controlador WHERE Borrado='0' ORDER BY Nombre_Controlador;";
		$resultado = $db->query($sql);
		$db->close();
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()) {
				echo "<option value='".$row['Nombre_Controlador']."'>".$row['Nombre_Controlador']."</option><tr>";
			}
		}
		$db->close();
}

//Lista en un select todas las acciones del controlador pasado como parámetro.	
function listarAccion($contr){
	
	$db = conectarBD();
	
	$sql = "SELECT Accion FROM Controlador WHERE Borrado='0' AND Nombre_Controlador='".$contr."' ORDER BY Accion;";
	$resultado = $db->query($sql);
	$db->close();
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			echo "<option value='".$row['Accion']."'>".$row['Accion']."</option><tr>";
		}
	}
	$db->close();
}

//Lista todos los controladores con sus acciones con formato tabla.
function listarFuncionalidades(){
	
	$db = conectarBD();
	
	$sql = "SELECT * FROM Controlador WHERE Borrado='0' ORDER BY Nombre_Controlador,Accion;";
	$resultado = $db->query($sql);
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			echo "<tr><td>".$row['Nombre_Controlador']."</td> <td>".$row['Accion']."<td></tr>";
		}
	}
}

//Muestra las acciones de un controlador concreto pasado por parámetro en formato tabla.
function consultarControlador($controlador){
		
		$db = conectarBD();
	
	$sql = "SELECT * FROM Controlador WHERE Borrado='0' AND Nombre_Controlador='".$controlador."' ORDER BY Nombre_Controlador,Accion;";
	$resultado = $db->query($sql);
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			echo "<tr><td>".$row['Nombre_Controlador']."</td> <td>".$row['Accion']."<td></tr>";
		}
	}
}

}else echo "Permiso denegado.";

?>