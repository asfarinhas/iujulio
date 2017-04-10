<?php
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){ 

//Include de la función de conexión a la base de datos.
include_once('conectarBD.php');

class grupo{
	
	var $grupo;
	var $mysqli;
	
	//Constructor. 
	function __construct($grupo){
		
		$this->grupo = $grupo;
	}
	
	//Getter.
	function getGrupo(){
		return $this->grupo;
	}
	
	//Añade un grupo a la BD en base al grupo recibidos del controller. Controla que no exista ya.
	//Si se había realizado un borrado lógico recupera el grupo.
	function crear(){
		
		$this->mysqli = conectarBD();
		
		$sql = "SELECT * FROM Grupo WHERE Nombre_Grupo='".$this->grupo."';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
			$sql = "INSERT INTO Grupo (Nombre_Grupo) VALUES ('".$this->grupo."');";
			$this->mysqli->query($sql);		
			return "añadido exito";
		}else{
			$sql = "SELECT * FROM Grupo WHERE Nombre_Grupo='".$this->grupo."' AND Borrado='1';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows == 1){
				$sql = "UPDATE Grupo SET Borrado='0' WHERE Nombre_Grupo='".$this->grupo."';";
				$this->mysqli->query($sql);	
				return "añadido exito";				
			}else return "ya existe";
		}
	}
	
	//Borrado lógico del grupo seleccionado en el controller. Si hay permisos sobre ese grupo se borran.
	function borrar(){

		$this->mysqli = conectarBD();
		
		$sql = "UPDATE Grupo SET Borrado='1' WHERE Nombre_Grupo='".$this->grupo."';";
		if($this->mysqli->query($sql) === TRUE) {
			$sql = "SELECT * FROM Permisos WHERE Nombre_Grupo='".$this->grupo."';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows > 0){
				$sql = "DELETE FROM Permisos WHERE Nombre_Grupo='".$this->grupo."';";
				$this->mysqli->query($sql);
			}
			$sql = "SELECT * FROM Usuario WHERE Nombre_Grupo='".$this->grupo."';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows > 0){
				$sql = "UPDATE Usuario SET Nombre_Grupo=NULL WHERE Nombre_Grupo='".$this->grupo."';";
				$this->mysqli->query($sql);
			}
			return "borrado exito";
		}else
			return "error borrado";
	}
	
	//Modifica el grupo recibido del controller. Controla que no exista el nuevo grupo.
	//Si tenía borrado lógico lo sobreescribe y borra el viejo.
	function modificar($grupoNuevo){

		$this->mysqli = conectarBD();
		
		$sql = "SELECT * FROM Grupo WHERE Nombre_Grupo='".$grupoNuevo->getGrupo()."';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
			$sql= "UPDATE Grupo SET Nombre_Grupo='".$grupoNuevo->getGrupo()."' WHERE Nombre_Grupo='".$this->grupo."';";
			if($this->mysqli->query($sql) === TRUE) {
				return "modificacion exito";
			}else{
				return "error modificacion";
			}
		}else{ 
			$sql = "SELECT * FROM Grupo WHERE Nombre_Grupo='".$grupoNuevo->getGrupo()."' AND Borrado='1';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows == 1){
				$sql = "DELETE FROM Grupo WHERE Nombre_Grupo='".$this->getGrupo()."';";
				$this->mysqli->query($sql);
				$sql = "UPDATE Grupo SET Borrado='0' WHERE Nombre_Grupo='".$grupoNuevo->getGrupo()."';";
				$this->mysqli->query($sql);	
				return "añadido exito";				
			}else return "ya existe";
			return "ya existe";
		}	
	}	
}

//Lista todos los grupos registrados en un select. Oculta el grupo admin.
function listarGrupos(){
	
	$db = conectarBD();
	
	$sql = "SELECT * FROM Grupo WHERE Borrado='0' ORDER BY Nombre_Grupo;";
	$resultado = $db->query($sql);
	if ($resultado->num_rows > 0){
		echo "<option value=''></option>";
		while($row = $resultado->fetch_array()) {
			if(strcmp($row['Nombre_Grupo'],'Admin')!= 0 )
				echo "<option value='".$row['Nombre_Grupo']."'>".$row['Nombre_Grupo']."</option>";
		}
	}
}

//Lista todos los grupos registrados en formato tabla.
function verGrupos(){
	
	$db = conectarBD();
	
	$sql = "SELECT * FROM Grupo WHERE Borrado='0' ORDER BY Nombre_Grupo;";
	$resultado = $db->query($sql);
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			echo "<tr><td>".$row['Nombre_Grupo']."</td> </tr>";
		}
	}
}

}else echo "Permiso denegado.";
?>