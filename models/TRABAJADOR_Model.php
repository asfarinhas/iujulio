<?php
//Comprueba si el usuario inició sesión y si es admin o secretaría antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 ){ 

class Trabajador{
	
	var $dni;
	var $nombre;
	var $url_Foto;
	var $apellidos;
	var $direccion;
	var $email;
	var $fechaNac;
	var $observaciones;
	var $numeroCuenta;
	var $tipoEmp;
	var $externo;
	var $telefono;
	var $mysqli;
	
	//Constructor.
	function __construct($apellidos=null,$nombre=null,$url_Foto=null,$direccion=null, $email=null,$fechaNac=null,$observaciones=null,$numeroCuenta=null,$dni=null,$tipoEmp=null,$telefono=null,$externo=null){
		
		$this->dni = $dni;
		$this->nombre = $nombre;
		$this->url_Foto = $url_Foto;
		$this->apellidos = $apellidos;
		$this->direccion = $direccion;
		$this->email = $email;
		$this->fechaNac = $fechaNac;
		$this->observaciones = $observaciones;
		$this->numeroCuenta = $numeroCuenta;
		$this->tipoEmp = $tipoEmp;
		$this->telefono = $telefono;
		$this->externo = $externo;
		
	}


	//Getters.
	function getDni(){
		return $this->dni;
	}
	function getNombre(){
		return $this->nombre;
	}
	function getUrl_Foto(){
		return $this->url_Foto;
	}
	function getApellidos(){
		return $this->apellidos;
	}
	function getDireccion(){
		return $this->direccion;
	}
	function getEmail(){
		return $this->email;
	}
	function getFechaNac(){
		return $this->fechaNac;
	}
	function getObservaciones(){
		return $this->observaciones;
	}
	function getNumeroCuenta(){
		return $this->numeroCuenta;
	}
	function getTipoEmp(){
		return $this->tipoEmp;
	}
	function getTelefono(){
		return $this->telefono;
	}
	function getExterno(){
		return $this->externo;
	}
	


//Crea una conexión con la BD.
	function conectarBD(){
		
		$this->mysqli = new mysqli("localhost", "root", "iu", "MOOVETT");
		if (mysqli_connect_errno()){
			echo "Fallo al conectar MySQL: " . $this->mysqli->connect_error();
		}
	}



	function crear(){
		
		$this->conectarBD();
		
		$sql = "SELECT * FROM Trabajador WHERE DNI='".$this->dni."';";
		$resultado = $this->mysqli->query($sql);
		if ($resultado->num_rows == 0){
				
					$sql = "INSERT INTO Trabajador (DNI,Apellidos,Nombre,Url_Foto,Direccion,Email,Fecha_Nacimiento,Observaciones,Numero_Cuenta,Telefono,Tipo_Empleado,Externo) VALUES ('".$this->dni."','".$this->apellidos."','".$this->nombre."','".$this->url_Foto."','".$this->direccion."','".$this->email."','".$this->fechaNac."','".$this->observaciones."','".$this->numeroCuenta."','".$this->telefono."','".$this->tipoEmp."','".$this->externo. "');";
					
					$this->mysqli->query($sql);		
					return "añadido exito";
		}else{
				$sql = "UPDATE Trabajador SET Borrado='0' WHERE Dni='" . $this->dni . "' ;";
				$this->mysqli->query($sql);	
				return 'añadido existe';
			}
					
		}


	function borrar(){

		$this->conectarBD();
		
		$sql = "UPDATE Trabajador SET Borrado='1' WHERE Dni='".$this->dni."';";
		if($this->mysqli->query($sql) === TRUE) {
			return "borrado exito";
		}else{
			return "error borrado";
		}
	}
	

	//Modifica los datos de un usuario. Controla que no exista ya el nuevo usuario o el dni ya esté asignado.
	//Si tenía borrado lógico lo sobreescribe y borra el viejo.
	function modificar($TrabajadorNuevo){
			$this->conectarBD();

			$sql = "SELECT * FROM Trabajador WHERE DNI='" . $TrabajadorNuevo->getDni() . "' AND DNI<>'" . $this->getDni() ."';";
			$resultado = $this->mysqli->query($sql);
			if ($resultado->num_rows == 1){
				return "dni asignado";
			}else{
				
				$sql= "UPDATE Trabajador SET DNI='" . $TrabajadorNuevo->getDni() . "',Apellidos='" . $TrabajadorNuevo->getApellidos() . "',Nombre='" . $TrabajadorNuevo->getNombre() . "',Fecha_Nacimiento='" . $TrabajadorNuevo->getFechaNac() . "',Email='".  $TrabajadorNuevo->getEmail() . "',Direccion='" .  $TrabajadorNuevo->getDireccion() . "',Tipo_Empleado='" . $TrabajadorNuevo->getTipoEmp() . "',Url_Foto='". $TrabajadorNuevo->getUrl_Foto() . "',Telefono='" . $TrabajadorNuevo->getTelefono() . "',Observaciones='" . $TrabajadorNuevo->getObservaciones() . "',Numero_Cuenta='" . $TrabajadorNuevo->getNumeroCuenta() . "',Externo='" . $TrabajadorNuevo->getExterno() . "'  WHERE Dni='".$this->dni."';";
					

					$resultado2 = $this->mysqli->query($sql);
			}
				
			

			if($resultado2){
				return "modificacion exito";
			}else{
				return "error modificacion";
			}
		}

}
			



	function listarTrabajadores(){
	
		$db = new mysqli("localhost", "root", "iu", "MOOVETT");
		
		$sql = "SELECT * FROM Trabajador WHERE Borrado='0' ORDER BY Dni;";
		$resultado = $db->query($sql);
		$db->close();
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()) {
					echo "<option value='".$row['DNI']."'>".$row['DNI']." - " . $row['Nombre'] . " ". $row['Apellidos'] . "</option><tr>";
			}
		}
	}


//Lista todos los trabajadores en una tabla.
function verTrabajadores(){
	
	$db = new mysqli("localhost", "root", "iu", "MOOVETT");
		
	$sql = "SELECT * FROM Trabajador WHERE Borrado='0' ORDER BY DNI;";
	$resultado = $db->query($sql);
	$db->close();
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			if($row['DNI'] == null) $row['DNI'] = "-";
			if($row['Tipo_Empleado'] == 'monitor'){
				echo "<tr> <td>".$row['DNI']."</td> <td>".$row['Nombre']."</td> <td>".$row['Apellidos'] . "</td> <td>".$row['Tipo_Empleado'] . "</td> <td> <a href='../controllers/TRABAJADOR_Controller.php?id=buscarTrabajadorListar&buscar=1&id2=" . $row['DNI'] . "'  class='btn btn-primary' >Ver perfil</a></td><td> <a href='../controllers/LESION_Controller.php?id=verLesiones&id2=" . $row['DNI'] . "'  class='btn btn-primary' >Ver Lesiones</a></td></tr>";
			}else{
			echo "<tr> <td>".$row['DNI']."</td> <td>".$row['Nombre']."</td> <td>".$row['Apellidos'] . "</td> <td>".$row['Tipo_Empleado'] . "</td> <td> <a href='../controllers/TRABAJADOR_Controller.php?id=buscarTrabajadorListar&buscar=1&id2=" . $row['DNI'] . "'  class='btn btn-primary' >Ver perfil</a></td><td></td></tr>";
		}
		}
	}
}

function verTrabajadoresModificar(){
	
	$db = new mysqli("localhost", "root", "iu", "MOOVETT");
		
	$sql = "SELECT * FROM Trabajador WHERE Borrado='0' ORDER BY DNI;";
	$resultado = $db->query($sql);
	$db->close();
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			if($row['DNI'] == null) $row['DNI'] = "-";
			if($row['Tipo_Empleado'] == 'monitor'){
			echo "<tr> <td>".$row['DNI']."</td> <td>".$row['Nombre']."</td> <td>".$row['Apellidos'] . "</td> <td>".$row['Tipo_Empleado'] . "</td> <td> <a href='../controllers/TRABAJADOR_Controller.php?id=modificarTrabajador&id2=" . $row['DNI'] . "'  class='btn btn-primary' >Modificar</a></td><td> <a href='../controllers/LESION_Controller.php?id=altaLesion&id2=" . $row['DNI'] . "'  class='btn btn-primary' >Añadir Lesión</a></td> <td> <a href='../controllers/LESION_Controller.php?id=modificarLesion&id2=" . $row['DNI'] . "'  class='btn btn-primary' >Modificar Lesión</a></td><td> <a href='../controllers/LESION_Controller.php?id=bajaLesion&id2=" . $row['DNI'] . "'  class='btn btn-primary' >Borrar Lesión</a></td></tr>";
		}else{
			echo "<tr> <td>".$row['DNI']."</td> <td>".$row['Nombre']."</td> <td>".$row['Apellidos'] . "</td> <td>".$row['Tipo_Empleado'] . "</td> <td> <a href='../controllers/TRABAJADOR_Controller.php?id=modificarTrabajador&id2=" . $row['DNI'] . "'  class='btn btn-primary' >Modificar</a></td><td></td></tr>";
		}
		}
	}
}


function verTrabajadoresBorrar(){
	
	$db = new mysqli("localhost", "root", "iu", "MOOVETT");
		
	$sql = "SELECT * FROM Trabajador WHERE Borrado='0' ORDER BY DNI;";
	$resultado = $db->query($sql);
	$db->close();
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			if($row['DNI'] == null) $row['DNI'] = "-";
			if($row['Tipo_Empleado'] == 'monitor'){
			echo "<tr> <td>".$row['DNI']."</td> <td>".$row['Nombre']."</td> <td>".$row['Apellidos'] . "</td> <td>".$row['Tipo_Empleado'] . "</td> <td> <a href='../controllers/TRABAJADOR_Controller.php?id=bajaTrabajador&id2=" . $row['DNI'] . "'  class='btn btn-primary' >Borrar</a></td></tr>";
		}else{
			echo "<tr> <td>".$row['DNI']."</td> <td>".$row['Nombre']."</td> <td>".$row['Apellidos'] . "</td> <td>".$row['Tipo_Empleado'] . "</td> <td> <a href='../controllers/TRABAJADOR_Controller.php?id=bajaTrabajador&id2=" . $row['DNI'] . "'  class='btn btn-primary' >Borrar</a></td></tr>";
		}
		}
	}
}


//Lista todos los trabajadores en una tabla.
function verTrabajadoresBorrados(){
	
	$db = new mysqli("localhost", "root", "iu", "MOOVETT");
		
	$sql = "SELECT * FROM Trabajador WHERE Borrado='1' ORDER BY DNI;";
	$resultado = $db->query($sql);
	$db->close();
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()) {
			if($row['DNI'] == null) $row['DNI'] = "-";
			echo "<tr> <td>".$row['DNI']."</td> <td>".$row['Nombre']."</td> <td>".$row['Apellidos']. "</td> <td>".$row['Tipo_Empleado'] ."</td> <td> <a href='../controllers/TRABAJADOR_Controller.php?id=buscarTrabajadorListarBorrados&id2=" . $row['DNI'] . "'  class='btn btn-primary' >Ver perfil</a></td></tr>";
		}
	}
}

//Recupera los datos de un trabajador en un array.
function mostrarTrabajadorDni($dni){
		
	$db = new mysqli("localhost", "root", "iu", "MOOVETT");
	
	$sql = "SELECT * FROM Trabajador WHERE Dni='". $dni ."' AND Borrado='0';";
	$resultado = $db->query($sql);


	if ($resultado->num_rows !=0){
		$row = $resultado->fetch_assoc();	
		return $row;
	}
}


function mostrarTrabajadorBorrados($dni){
		
	$db = new mysqli("localhost", "root", "iu", "MOOVETT");
	
	$sql = "SELECT * FROM Trabajador WHERE Dni='".$dni."' AND Borrado='1';";
	$resultado = $db->query($sql);
	if ($resultado->num_rows !=0){
		$row = $resultado->fetch_assoc();
		return $row;
	}
}

//Muestra los datos de un trabajador concreto pasado por parámetro en formato tabla.
function consultarTrabajador($dni){
		
	$db = new mysqli("localhost", "root", "iu", "MOOVETT");
	
	$sql = "SELECT * FROM Trabajador WHERE Dni='".$dni."';";
	$resultado = $db->query($sql);
	if ($resultado->num_rows > 0){
		$row = $resultado->fetch_array();
		return $row;	
	}
}

} else {
	echo "Permiso denegado.";
}
?>
