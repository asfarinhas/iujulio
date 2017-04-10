<?php
session_start();
require_once('conexion.php');
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>
<body>

<!--FORMULARIO PARA COMPROBAR QUE ESTA REGISTRADO EN EL SISTEMA COMO ALUMNO-->
<form method=POST action=''>
  DNI:<br>
  <input type="text" name="DNI">
  <br>
  <input type = "submit" name = "Enviar" value="Validar"/>

</form>

<?php

if (isset($_POST['Enviar'])){
	$_SESSION['DNI']=$_POST['DNI'];
	
	
	
	$sql = "SELECT *
				FROM alumno
				WHERE DNI ='".$_POST['DNI']."'";
				
	$res = mysqli_query($conexion,$sql) or die(mysqli_error()."<br>".$sql."<hr>");
	
if($registro = mysqli_fetch_assoc($res)){
			
			$sql1= "SELECT *
					FROM reserva_masaje
					WHERE DNI_Alumno ='".$_POST['DNI']."'";
		
		$res1 = mysqli_query($conexion,$sql1) or die(mysqli_error()."<br>".$sql1."<hr>");
		
		echo "<form name = tabla method=POST action='Eliminar.php'>";
				echo "<table>";
				echo "<tr>";
							echo "<th>IdMasaje</th>";
							echo "<th>IdReserva</th>";
							echo "<th>DNI_Alumno</th>";
							echo "<th>DNI_Trabajador</th>";
							echo "<th>Tipo</th>";
							echo "<th>HoraInicio</th>";
							echo "<th>HoraFin</th>";
							echo "<th>Fecha</th>";
						echo "</tr>";
				
				while($registro1 = mysqli_fetch_assoc($res1)){
						if($registro1['Borrado']==0){
						
						echo "<tr>";
							echo"<td>";
								echo $registro1['Id_Masaje']."<br>";
							echo "</td>";
							echo"<td>";
								echo $registro1['Id_Reserva']."<br>";
							echo "</td>";
							echo"<td>";
								echo $registro1['DNI_Alumno']."<br>";
							echo"</td>";
							echo"<td>";
								echo $registro1['DNI_Trabajador']."<br>";
							echo"</td>";
							echo"<td>";	
								echo $registro1['Tipo']."<br>";
							echo"</td>";
							echo"<td>";
								echo $registro1['Hora_Inicio']."<br>";
							echo"</td>";
							echo"<td>";
								echo $registro1['Hora_Fin']."<br>";
							echo"</td>";
							echo"<td>";
								echo $registro1['Fecha']."<br>";
							echo"</td>";
							echo"<td class = 'Botones'>";
							
								echo "<input type='hidden' name = clave value = ".$registro1['Id_Reserva'].">";
								
				
								echo '<input  type = "submit" name = "Eliminar" value="Borrar"/>';
								echo '<input  type = "submit" name = "Cambiar" value="Modificar"/>';
							echo"</td>";
							
					echo "</tr>";
				}
			}	
				echo "</table>";
				echo '<br> <center> <input  type = "submit" name = "Crear" value="Añadir nueva reserva"/>';
			echo "</form>";
				
				
		
		
		/*else{
			echo "Este alumno no tiene masajes";
			echo '<br> <center> <input  type = "submit" name = "Crear" value="Añadir nuevo masaje"/>';
		}*/
		
		
		

}
	else{
		echo "El usuario no está registrado";
	}
}
?>
</body>
</html>
