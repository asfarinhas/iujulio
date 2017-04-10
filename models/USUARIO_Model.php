<?php
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){ 

//Include de la función de conexión a la base de datos.
include_once('conectarBD.php');

class usuario{
	
	var $id;
	var $password;
	var $grupo;
	var $usuario;
	var $mysqli;
	
	//Constructor.
	function __construct($password=null,$grupo=null,$usuario,$dni=null){
		
		$this->dni = $dni;
		$this->password = md5($password);
		$this->usuario = $usuario;
		$this->grupo = $grupo;
	}
	//Getters.
	function getDni(){
		return $this->dni;
	}
	function getPassword(){
		return $this->password;
	}
	function getGrupo(){
		return $this->grupo;
	}
	function getUsuario(){
		return $this->usuario;
	}
	
	//Añade un usuario a la BD. Controla que no exista ya el usuario. Si se introduce dni del trabajador se comprueba que exista o que el dni esté asignado.
	//Si se había realizado un borrado lógico recupera el usuario.
	function crear(){
		
		$this->mysqli = conectarBD();
		
		$sql = "SELECT * FROM Usuario WHERE DNI='".$this->dni."';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
			$sql = "SELECT * FROM Usuario WHERE Usuario='".$this->usuario."';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows == 0){
				if($this->dni == null){
					$sql = "INSERT INTO Usuario (Nombre_Grupo,Password,Usuario) VALUES ('".$this->grupo."','".$this->password."','".$this->usuario."');";
					$this->mysqli->query($sql);		
					return "añadido exito";
				}else{
					$sql = "SELECT * FROM Trabajador WHERE DNI='".$this->dni."';";
					$result = $this->mysqli->query($sql);	
					if($result->num_rows == 1){
						$sql = "INSERT INTO Usuario (Nombre_Grupo,Password,Usuario,DNI) VALUES ('".$this->grupo."','".$this->password."','".$this->usuario."','".$this->dni."');";
						$this->mysqli->query($sql);	
						return "añadido exito";
					}else{
						return "dni invalido";
					}
				}
			}else{
				$sql = "SELECT * FROM Usuario WHERE Usuario='".$this->usuario."' AND Borrado='1';";
				$resultado = $this->mysqli->query($sql);
				if ($resultado->num_rows == 1){
					if($this->dni == null){
						$sql = "UPDATE Usuario SET Borrado='0',Password='".$this->password."',Nombre_Grupo='".$this->grupo."',DNI=NULL WHERE Usuario='".$this->usuario."';";
						$this->mysqli->query($sql);
					}else{
						$sql = "UPDATE Usuario SET Borrado='0',Password='".$this->password."',Nombre_Grupo='".$this->grupo."',DNI='".$this->dni."' WHERE Usuario='".$this->usuario."';";
						$this->mysqli->query($sql);
					}
					return "añadido exito";				
				}else return "ya existe";
			}
		}else return "dni asignado";
	}
	
	//Realiza el borrado lógico de un usuario.
	function borrar(){

		$this->mysqli = conectarBD();
		
		$sql = "UPDATE Usuario SET Borrado='1' WHERE Usuario='".$this->usuario."';";
		if($this->mysqli->query($sql) === TRUE) {
			return "borrado exito";
		}else
			return "error borrado";
	}
	
	//Modifica los datos de un usuario. Controla que no exista ya el nuevo usuario o el dni ya esté asignado.
	//Si tenía borrado lógico lo sobreescribe y borra el viejo.
	function modificar($usuarioNuevo){

		$this->mysqli = conectarBD();
		
		$sql = "SELECT * FROM Usuario WHERE DNI='".$usuarioNuevo->getDni()."' AND Usuario<>'".$usuarioNuevo->getUsuario()."';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
			if($usuarioNuevo->getPassword() != null){
				$sql= "UPDATE Usuario SET Password='".$usuarioNuevo->getPassword()."' WHERE Usuario='".$this->usuario."';";
				$this->mysqli->query($sql);
			}
			if($usuarioNuevo->getGrupo() != null){
				$sql= "UPDATE Usuario SET Nombre_Grupo='".$usuarioNuevo->getGrupo()."' WHERE Usuario='".$this->usuario."';";
				$this->mysqli->query($sql);
			}
			if($usuarioNuevo->getDni() == null){
				$sql= "UPDATE Usuario SET DNI=NULL WHERE Usuario='".$this->usuario."';";
				$this->mysqli->query($sql);
				return "modificacion exito";
			}else{
				$sql = "SELECT * FROM Trabajador WHERE DNI='".$usuarioNuevo->getDni()."';";
				$resultado = $this->mysqli->query($sql);
				if ($resultado->num_rows == 1){
					$sql= "UPDATE Usuario SET DNI='".$usuarioNuevo->getDni()."' WHERE Usuario='".$this->usuario."';";
					if($this->mysqli->query($sql) === TRUE) {
						return "modificacion exito";
					}else
						return "error modificacion";
				}else return "dni invalido";
			}
		}else return "dni asignado";
	}

}

//Lista en un select todos los usuarios para borrar. Controla que no se muestre el usuario logeado o admin para no borrarlo.
function listarUsuariosBorrar(){
	
		$db = conectarBD();
		
		$sql = "SELECT Usuario FROM Usuario WHERE Borrado='0' ORDER BY Usuario;";
		$resultado = $db->query($sql);
		$db->close();
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()) {
				if(strcmp($row['Usuario'],$_SESSION['user']) != 0 && strcmp($row['Nombre_Grupo'],"Admin") != 0)
					echo "<option value='".$row['Usuario']."'>".$row['Usuario']."</option><tr>";
			}
		}
		$db->close();
}

//Lista en un select todos los usuarios para modificar. 
function listarUsuariosModificar(){
	
		$db = conectarBD();
		
		$sql = "SELECT Usuario FROM Usuario WHERE Borrado='0' ORDER BY Usuario;";
		$resultado = $db->query($sql);
		$db->close();
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()) {
				echo "<option value='".$row['Usuario']."'>".$row['Usuario']."</option><tr>";
			}
		}
		$db->close();
}

//Lista todos los usuarios en una tabla.
function verUsuarios(){
	
	$db = conectarBD();
		
	$sql = "SELECT * FROM Usuario WHERE Borrado='0' ORDER BY Usuario;";
	$resultado = $db->query($sql);
	$db->close();
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			if($row['DNI'] == null) $row['DNI'] = "-";
			echo "<tr> <td>".$row['Usuario']."</td> <td>".$row['Nombre_Grupo']."</td> <td>".$row['DNI']."</td> </tr>";
		}
	}
}

//Recupera los datos de un usuario en un array.
function mostrarUsuario($user){
		
	$db = conectarBD();
	
	$sql = "SELECT * FROM Usuario WHERE Usuario='".$user."' AND Borrado='0';";
	$resultado = $db->query($sql);
	if ($resultado->num_rows == 1){
		$row = $resultado->fetch_assoc();
		return $row;
	}
}

//Muestra los datos de un usuario concreto pasado por parámetro en formato tabla.
function consultarUsuario($user){
		
	$db = conectarBD();
	
	$sql = "SELECT * FROM Usuario WHERE Usuario='".$user."' AND Borrado='0';";
	$resultado = $db->query($sql);
	if ($resultado->num_rows > 0){
		$row = $resultado->fetch_array();
		if($row['DNI'] == null) $row['DNI'] = "-";
		echo "<tr> <td>".$row['Usuario']."</td> <td>".$row['Nombre_Grupo']."</td> <td>".$row['DNI']."</td> </tr>";	
	}
}

}else echo "Permiso denegado.";
?>