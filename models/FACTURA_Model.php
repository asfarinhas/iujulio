<?php
//Comprueba si el usuario inició sesión y si tiene permisos antes de cargar la página.
if((isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0) || (isset($_SESSION['permisos']) && in_array('Factura',$_SESSION['permisos']))){ 

//Include de la función para conectarse a la BD.
include_once('conectarBD.php');

class factura{
	
	var $idFactura;
	var $cliente;
	var $total;
	var $pagada;
	var $fecha;
	var $mysqli;
	
	//Constructor. 
	function __construct($cliente=null,$idFactura=null,$fecha=null,$total=null,$pagada=null){
		
		$this->idFactura = $idFactura;
		$this->total = $total;
		$this->pagada = $pagada;
		$this->cliente = $cliente;
		$this->fecha = $fecha;
	}
	
	function getFecha(){
		echo $this->fecha;
	}
	
	//Crea una factura para un cliente y devuelve su id. Si ya tiene una sin cerrar no la crea.
	function crear(){
		
		$this->mysqli = conectarBD();
			
		$sql = "SELECT * FROM Factura WHERE Id_Cliente='".$this->cliente."' AND Borrado='0' AND Fecha IS NULL;";
		$resultado = $this->mysqli->query($sql);	
		if ($resultado->num_rows == 0){
			$sql = "INSERT INTO Factura (Id_Cliente) VALUES ('".$this->cliente."');";
			$this->mysqli->query($sql);
			$sql = "SELECT Id_Factura FROM Factura WHERE Id_Cliente='".$this->cliente."' AND Borrado='0' AND Fecha IS NULL;";
			$resultado = $this->mysqli->query($sql);
			$row = $resultado->fetch_array();
			
			return $row['Id_Factura'];	
		}else{
			return "factura sin cerrar";	
		}
	}
	
	//Recupera todas las facturas de los datos a buscar en un array.
	function buscar(){
		
		$this->mysqli = conectarBD();
		
		if($this->pagada == 'No'){
			$pagado = "No";
		}else if($this->pagada == 'Si'){
			$pagado = "___%";
		}else $pagado = "%";
		
		if($this->fecha != null && $this->fecha != 'todas'){
			$sql = "SELECT * FROM Factura INNER JOIN Cliente_Externo WHERE Factura.Fecha='".$this->fecha."' AND Factura.Pagada LIKE'".$pagado."' AND Cliente_Externo.Nombre LIKE'%".$this->cliente."%' AND Factura.Id_Cliente=Cliente_Externo.Id_Cliente AND Factura.Borrado='0' ORDER BY Factura.Id_Factura DESC;";
		}else if($this->fecha == null){
			$sql = "SELECT * FROM Factura INNER JOIN Cliente_Externo WHERE Factura.Fecha IS NULL AND Factura.Pagada LIKE'".$pagado."' AND Cliente_Externo.Nombre LIKE'%".$this->cliente."%' AND Factura.Id_Cliente=Cliente_Externo.Id_Cliente AND Factura.Borrado='0' ORDER BY Factura.Id_Factura DESC;";
		}else{
			$sql = "SELECT * FROM Factura INNER JOIN Cliente_Externo WHERE Factura.Pagada LIKE'".$pagado."' AND Cliente_Externo.Nombre LIKE'%".$this->cliente."%' AND Factura.Id_Cliente=Cliente_Externo.Id_Cliente AND Factura.Borrado='0' ORDER BY Factura.Id_Factura DESC;";
		}

		$resultado = $this->mysqli->query($sql);	
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()){
				$array[] = $row;
			}
			return $array;
		}
	}
	
	//Devuelve un array con los datos de la factura seleccionada de un cliente.
	function consultar(){
		
		$this->mysqli = conectarBD();
		
		$sql = "SELECT Factura.Id_Factura,Cliente_Externo.Nombre,Factura.Total,Factura.Fecha,Factura.Pagada,Factura.Id_Cliente,Factura.Fecha_Cobro,Cliente_Externo.Direccion,Cliente_Externo.Tlf,Cliente_Externo.Email FROM Factura INNER JOIN Cliente_Externo WHERE Factura.Id_Factura='".$this->idFactura."' AND Factura.Id_Cliente=Cliente_Externo.Id_Cliente AND Factura.Borrado='0';";
		$resultado = $this->mysqli->query($sql);	
		if ($resultado->num_rows == 1){
			$row = $resultado->fetch_array();
			return $row;
		}
	}
	
	//Borra una factura y todas sus lineas si tiene.
	function borrar(){
		
		$this->mysqli = conectarBD();
		
		$sql = "UPDATE Factura SET Borrado='1' WHERE Id_Factura='".$this->idFactura."';";
		$this->mysqli->query($sql);
		$sql = "UPDATE Linea_Factura SET Borrado='1' WHERE Id_Factura='".$this->idFactura."';";
		$this->mysqli->query($sql);
		return "borrado exito";
	}
	
	//Modifica una factura asignándole una fecha de cierre y total.
	function editar(){
		
		$this->mysqli = conectarBD();
		if($this->fecha != null){
			$sql = "UPDATE Factura SET Fecha='".$this->fecha."',Total='".$this->total."' WHERE Id_Factura='".$this->idFactura."';";
			$this->mysqli->query($sql);
		}else{
			$sql = "UPDATE Factura SET Fecha=NULL,Total='".$this->total."' WHERE Id_Factura='".$this->idFactura."';";
			$this->mysqli->query($sql);
		}
		return "modificacion exito";
	}
	
	//Marca una factura como cobrada y asigna un tipo de pago y fecha de cobro.
	function cobrar(){
		$this->mysqli = conectarBD();
		
		$sql = "UPDATE Factura SET Pagada='".$this->pagada."',Fecha_Cobro='".$this->fecha."' WHERE Id_Factura='".$this->idFactura."';";
		$this->mysqli->query($sql);
		if($this->pagada == 'Efectivo'){
			$sql = "INSERT INTO Caja (Fecha,Tipo,Importe,Comentario) VALUES ('".$this->fecha."','Ingreso','".$this->total."','Factura cobrada con fecha ".$this->fecha."');";
			$this->mysqli->query($sql);
		}
		
	}
}

//Recupera todas las facturas cobradas en un mes en un array.
	function listarCobradas($fecha){
		
		$bd = conectarBD();
		
		$inicio = date('Y-m-01',strtotime($fecha));
		$fin = date('Y-m-t',strtotime($fecha));
		
		$sql = "SELECT * FROM Factura INNER JOIN Cliente_Externo WHERE Factura.Pagada NOT IN('No') AND (Factura.Fecha_Cobro BETWEEN '".$inicio."' AND '".$fin."') AND Factura.Id_Cliente=Cliente_Externo.Id_Cliente AND Factura.Borrado='0' ORDER BY Factura.Fecha_Cobro;";
		$resultado = $bd->query($sql);	
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()){
				$array[] = $row;
			}
			return $array;
		}
	}

//Muestra los clientes cuyo nombre contenga la cadena pasada por parámetro. Si no hay devuelve el select vacío.
function verClientes($nombre){
	
	$bd = conectarBD();
	
	$sql = "SELECT Nombre,DNI,Id_Cliente FROM Cliente_Externo WHERE Nombre LIKE '%".$nombre."%' AND Borrado='0';";
	$resultado = $bd->query($sql);	
	if ($resultado->num_rows > 0){
		$row = $resultado->fetch_array();
		echo "<label class='radio-inline'><input type='radio' name='cliente' value='".$row['Id_Cliente']."' checked>".$row['Nombre']." (".$row['DNI'].")</label><br>";
		while($row = $resultado->fetch_array()) {
			echo "<label class='radio-inline'><input type='radio' name='cliente' value='".$row['Id_Cliente']."'>".$row['Nombre']." (".$row['DNI'].")</label><br>";
		}
	}else return "no hay";
}

}else echo "Permiso denegado.";
?>