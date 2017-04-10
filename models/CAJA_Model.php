<?php
//Comprueba si el usuario inició sesión y si tiene permisos antes de cargar la página.
if((isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0) || (isset($_SESSION['permisos']) && in_array('Caja',$_SESSION['permisos']))){ 

//Include de la función para conectarse a la BD.
include_once('conectarBD.php');

class caja{
	
	var $idCaja;
	var $importe;
	var $movimiento;
	var $comentario;
	var $fecha;
	var $mysqli;
	
	//Constructor. 
	function __construct($idCaja=null,$movimiento=null,$importe=null,$fecha=null,$comentario=null){
		
		$this->idCaja = $idCaja;
		$this->movimiento = $movimiento;
		$this->importe = $importe;
		$this->fecha = $fecha;
		$this->comentario = $comentario;
	}
	
	//Crea un movimiento de caja. Si el pago es superior a los ingresos de la caja da error.
	function crear(){
		
		$this->mysqli = conectarBD();
		
		if($this->movimiento == 'Ingreso' || ($this->movimiento == 'Pago' && ($this->calcularEntradas()-$this->calcularSalidas()-$this->importe >= 0))){
			$sql = "INSERT INTO Caja (Fecha,Tipo,Importe,Comentario) VALUES ('".$this->fecha."','".$this->movimiento."','".$this->importe."','".$this->comentario."');";
			$this->mysqli->query($sql);
		
			return "añadido exito";	
		}else return "error insertar pago";
	}
	
	//Busca los movimietos de caja de una fecha concreta o de un tipo de movimiento concreto.
	function buscar(){
		
		$this->mysqli = conectarBD();
		
		if($this->movimiento == "todos"){
			$movimiento = "%";
		}else{
			$movimiento = $this->movimiento;
		}
		
		if($this->fecha != null){
			$sql = "SELECT * FROM Caja WHERE Fecha='".$this->fecha."' AND Tipo LIKE '".$movimiento."' AND Borrado='0' ORDER BY Fecha DESC,Tipo;";
		}else{
			$sql = "SELECT * FROM Caja WHERE Tipo LIKE '".$movimiento."' AND Borrado='0' ORDER BY Fecha DESC,Tipo;";
		}
		
		$resultado = $this->mysqli->query($sql);	
		if ($resultado->num_rows > 0){
			while($row = $resultado->fetch_array()){
				$array[] = $row;
			}
			return $array;
		}
	}
	
	
	//Borra un movimiento de caja. Si el balance de caja que queda es negativo da error.
	function borrar(){
		
		$this->mysqli = conectarBD();
		
		if($this->movimiento == 'Ingreso' && ($this->calcularEntradas()-$this->importe-$this->calcularSalidas() < 0)){
			return "error caja";
		}else{
			$sql = "UPDATE Caja SET Borrado='1' WHERE Id_Caja='".$this->idCaja."';";
			$this->mysqli->query($sql);
			return "borrado exito";
		}
	}
	
	//Modifica un movimiento de caja. Comprueba que al modificar el balance de caja no quede negativo.
	function editar(){
		
		$this->mysqli = conectarBD();
		
		if($this->movimiento == "Ingreso"){
			$sql = "SELECT Importe FROM Caja WHERE Id_Caja='".$this->idCaja."' AND Borrado='0';";
			$resultado = $this->mysqli->query($sql);	
			$row = $resultado->fetch_array();
			if(($this->calcularEntradas()-$row['Importe']+$this->importe) - $this->calcularSalidas() >= 0){
				$sql = "UPDATE Caja SET Importe='".$this->importe."',Comentario='".$this->comentario."' WHERE Id_Caja='".$this->idCaja."';";
				$this->mysqli->query($sql);	
				
				return "modificacion exito";
			}else{
				return "error caja";
			}
		}
		else if($this->movimiento == "Pago"){
			$sql = "SELECT Importe FROM Caja WHERE Id_Caja='".$this->idCaja."' AND Borrado='0';";
			$resultado = $this->mysqli->query($sql);	
			$row = $resultado->fetch_array();
			if($this->calcularEntradas() - ($this->calcularSalidas()-$row['Importe']+$this->importe) >= 0){
				$sql = "UPDATE Caja SET Importe='".$this->importe."',Comentario='".$this->comentario."' WHERE Id_Caja='".$this->idCaja."';";
				$this->mysqli->query($sql);	
				
				return "modificacion exito";
			}else{
				return "error caja";
			}
		}
	}
	
	//Calcula el total de ingresos en caja.
	function calcularEntradas(){
	
		$this->mysqli = conectarBD();
		
		$sql = "SELECT SUM(Importe) AS Entradas FROM Caja WHERE Borrado='0' AND Tipo='Ingreso';";
		$resultado = $this->mysqli->query($sql);	
		if ($resultado->num_rows == 1){
			$row = $resultado->fetch_array();
			return $row['Entradas'];
		}
	}
	
	//Calcula el total de pagos en caja.
	function calcularSalidas(){
		
		$this->mysqli = conectarBD();
		
		$sql = "SELECT SUM(Importe) AS Salidas FROM Caja WHERE Borrado='0' AND Tipo='Pago';";
		$resultado = $this->mysqli->query($sql);	
		if ($resultado->num_rows == 1){
			$row = $resultado->fetch_array();
			return $row['Salidas'];
		}
	}
	
}

//Recupera todos los movimientos de caja de un mes en un array.
function listarTodas($fecha){
	
	$bd = conectarBD();
	
	$inicio = date('Y-m-01',strtotime($fecha));
	$fin = date('Y-m-t',strtotime($fecha));
	
	$sql = "SELECT * FROM Caja WHERE Borrado='0' AND (Fecha BETWEEN '".$inicio."' AND '".$fin."') ORDER BY Fecha,Tipo;";
	$resultado = $bd->query($sql);	
	if ($resultado->num_rows > 0){
		while($row = $resultado->fetch_array()){
			$array[] = $row;
		}
		return $array;
	}
}

//Devuelve el efectivo total que hay en la caja.
function calcularTotal(){
		
		$bd = conectarBD();
		$pagos = 0;
		$ingresos = 0;
		
		$sql = "SELECT SUM(Importe) AS Salidas FROM Caja WHERE Borrado='0' AND Tipo='Pago';";
		$resultado = $bd->query($sql);	
		if ($resultado->num_rows == 1){
			$row = $resultado->fetch_array();
			$pagos = $row['Salidas'];
		}
		$sql = "SELECT SUM(Importe) AS Entradas FROM Caja WHERE Borrado='0' AND Tipo='Ingreso';";
		$resultado = $bd->query($sql);	
		if ($resultado->num_rows == 1){
			$row = $resultado->fetch_array();
			$ingresos = $row['Entradas'];
		}
		
		$total = $ingresos - $pagos;
		return round($total, 2);
	}

}else echo "Permiso denegado.";
?>