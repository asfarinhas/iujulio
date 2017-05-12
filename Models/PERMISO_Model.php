<?php
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){ 

//Include de la función de conexión a la base de datos.
include_once('conectarBD.php');

class permiso{
	
	var $grupo;
	var $accion;
	var $controlador;
	var $mysqli;
	
	//Constructor. La acción puede ser null.
	function __construct($grupo,$controlador,$accion=null){
		
		$this->grupo = $grupo;
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
	
	function getGrupo(){
		return $this->grupo;
	}
	
	//Añade un permiso a la BD en base al grupo, controlador y acción recibidos del controller. Controla que no exista el permiso.
	function crear(){
		
		$this->mysqli = conectarBD();
		
		//Añade al grupo acción y controlador.
		if($this->accion != null){
			$sql = "SELECT * FROM Permisos WHERE Nombre_Grupo='".$this->grupo."' AND Nombre_Controlador='".$this->controlador."' AND Accion='".$this->accion."';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows == 0){
				$sql = "INSERT INTO Permisos (Nombre_Grupo,Nombre_Controlador,Accion) VALUES ('".$this->grupo."','".$this->controlador."','".$this->accion."');";
				$this->mysqli->query($sql);		
				return "añadido exito";
			}else{
				return "ya existe";
			}
		}else{
			//Si solo pasamos el controlador se controla que las acciones no estén ya añadidas a los permisos del grupo.
			$sql = "SELECT Accion FROM Controlador WHERE Nombre_Controlador='".$this->controlador."';";
			$resultado = $this->mysqli->query($sql);
			$cont = 0;
			$cont2=0;
			while($row = $resultado->fetch_array()) {
				$cont++;
				$sql = "SELECT * FROM Permisos WHERE Nombre_Grupo='".$this->grupo."' AND Nombre_Controlador='".$this->controlador."' AND Accion='".$row['Accion']."';";
				$result = $this->mysqli->query($sql);
				if($result->num_rows == 1){
					$cont2++;
				}else{
					$sql = "INSERT INTO Permisos (Nombre_Grupo,Nombre_Controlador,Accion) VALUES ('".$this->grupo."','".$this->controlador."','".$row['Accion']."');";
					$this->mysqli->query($sql);	
				}		
			}
			if($cont == $cont2) return "ya existe";
			else return "añadido exito";
		}
	}
	
	//Borra todas las tuplas para un grupo de un mismo controlador si no se pasa acción, o bien grupo, controlador y acción de una tupla.
	//No es borrado lógico dado que es una tabla derivada de otras y no perdemos datos.
	function borrar(){

		$this->mysqli = conectarBD();
		
		if($this->accion != null){
			$sql = "DELETE FROM Permisos WHERE Nombre_Grupo='".$this->grupo."' AND Nombre_Controlador='".$this->controlador."' AND Accion='".$this->accion."';";				$this->mysqli->query($sql);		
			if($this->mysqli->query($sql) === TRUE) {
				return "borrado exito";
			}else
				return "error borrado";
		}else{
			//Si solo pasamos el controlador se borran todas las acciones de ese controlador.
			$sql = "SELECT Accion FROM Permisos WHERE Nombre_Grupo='".$this->grupo."' AND Nombre_Controlador='".$this->controlador."';";
			$resultado = $this->mysqli->query($sql);
			while($row = $resultado->fetch_array()) {
				$sql = "DELETE FROM Permisos WHERE Nombre_Grupo='".$this->grupo."' AND Nombre_Controlador='".$this->controlador."' AND Accion='".$row['Accion']."';";
				$result = $this->mysqli->query($sql);
			}
			return "borrado exito";
		}	
	}
	
	//Modifica el grupo-controlador-acción recibido del controller. Controla que no exista ya el nuevo permiso y si no existe lo crea en controladores.
	function modificar($permisoNuevo){

		$this->mysqli = conectarBD();
		
		$sql = "SELECT * FROM Permisos WHERE Nombre_Grupo='".$permisoNuevo->getGrupo()."' AND Nombre_Controlador='".$permisoNuevo->getControlador()."' AND Accion='".$permisoNuevo->getAccion()."';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
			$sql= "UPDATE Permisos SET Nombre_Grupo='".$this->grupo."',Nombre_Controlador='".$permisoNuevo->getControlador()."',Accion='".$permisoNuevo->getAccion()."' WHERE Nombre_Grupo='".$this->grupo."' AND Nombre_Controlador='".$this->controlador."' AND Accion='".$this->accion."';";
			if($this->mysqli->query($sql) === TRUE) {
				return "modificacion exito";
			}else{
				return "error modificacion";
			}
		}else 
			return "ya existe";
	}	
}

//Lista en un select todos los controladores registrados del grupo seleccionado.
function listarControladorGrupo($grupo){
	
	$db = conectarBD();
	
	$sql = "SELECT DISTINCT Nombre_Controlador FROM Permisos WHERE Borrado='0' AND Nombre_Grupo='".$grupo."' ORDER BY Nombre_Controlador;";
	$resultado = $db->query($sql);
	$db->close();
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			echo "<option value='".$row['Nombre_Controlador']."'>".$row['Nombre_Controlador']."</option><tr>";
		}
	}
	$db->close();
}

//Lista en un select todas las acciones del controlador y grupo pasados como parámetro.	
function listarAccionGrupo($grupo,$contr){
	
	$db = conectarBD();
	
	$sql = "SELECT Accion FROM Permisos WHERE Borrado='0' AND Nombre_Controlador='".$contr."' AND Nombre_Grupo='".$grupo."' ORDER BY Accion;";
	$resultado = $db->query($sql);
	$db->close();
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			echo "<option value='".$row['Accion']."'>".$row['Accion']."</option><tr>";
		}
	}
	$db->close();
}

//Lista todos los permisos por grupo en formato tabla.
function listarPermisos(){
	
	$db = conectarBD();
	
	$sql = "SELECT * FROM Permisos ORDER BY Nombre_Grupo,Nombre_Controlador,Accion;";
	$resultado = $db->query($sql);
	echo "<tr> <td>Admin</td> <td>Todos</td> <td>Todas<td></tr>";
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			echo "<tr> <td>".$row['Nombre_Grupo']."</td> <td>".$row['Nombre_Controlador']."</td> <td>".$row['Accion']."<td></tr>";
		}
	}
}

//Muestra los permisos de un grupo concreto pasado por parámetro en formato tabla.
function consultarPermisos($grupo){
		
	$db = conectarBD();
	
	$sql = "SELECT * FROM Permisos WHERE Nombre_Grupo='".$grupo."' ORDER BY Nombre_Grupo,Nombre_Controlador,Accion;";
	$resultado = $db->query($sql);
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			echo "<tr> <td>".$row['Nombre_Grupo']."</td> <td>".$row['Nombre_Controlador']."</td> <td>".$row['Accion']."<td></tr>";
		}
	}
}

//Lista todos los grupos con permisos en un select. Oculta el grupo admin.
function listarGruposPermisos(){
	
	$db = conectarBD();
	
	$sql = "SELECT DISTINCT Nombre_Grupo FROM Permisos WHERE Borrado='0' ORDER BY Nombre_Grupo;";
	$resultado = $db->query($sql);
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			if(strcmp($row['Nombre_Grupo'],'Admin')!= 0 )
				echo "<option value='".$row['Nombre_Grupo']."'>".$row['Nombre_Grupo']."</option>";
		}
	}
}

}else echo "Permiso denegado.";

?>