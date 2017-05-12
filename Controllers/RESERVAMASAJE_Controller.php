<?php
session_start();
require_once('conexion.php');

//ESTE ARCHIVO MUESTRA LO QUE REALIZAN LOS BOTONES DE AÑADIR,MODIFICAR Y BORRAR RESERVA
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>

<?php


	
//Han pulsado el boton de eliminar una reserva de un masaje ----------------------------------------------------------------------------------------------------

 if (isset($_POST['Eliminar'])){

			$_SESSION['Id_Reserva']=$_POST['clave'];
			$sql = "UPDATE reserva_masaje
				SET Borrado = 1
				WHERE Id_Reserva ='".$_POST['clave']."'";
			
			$res = mysqli_query($conexion,$sql) or die(mysqli_error()."<br>".$sql."<hr>");
			
			echo "<p>";
				echo "<h2><center>RESERVA BORRADA</h2>";
				echo "<br>";
				echo 'Click para volver atrás <a href="DNI.php">Volver</a>.';
			echo "</p>";
			
			$sql1= "SELECT *
					FROM reserva_masaje
					WHERE DNI_Alumno ='".$_SESSION['DNI']."'";
		
		$res1 = mysqli_query($conexion,$sql1) or die(mysqli_error()."<br>".$sql1."<hr>");
			
		echo "<form name = tabla method=POST action=''>";
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
								echo "<input type='hidden' name = clave value = ".$registro1['Id_Masaje'].">";
								echo '<input  type = "submit" name = "Eliminar" value="Borrar"/>';
								echo '<input  type = "submit" name = "Cambiar" value="Modificar"/>';
							echo"</td>";
							
					echo "</tr>";
				}
			}	
				echo "</table>";
			echo "</form>";
				echo '<br> <center> <input  type = "submit" name = "Crear" value="Añadir nuevo masaje"/>';	
		}
 	
		
		
		
		
//Han pulsado el boton de modificar una reserva de un masaje ----------------------------------------------------------------------------------------------------		
		
		if (isset($_POST['Cambiar'])){
			
			$sql = "SELECT DISTINCT Nombre,Id_Masaje
					FROM masaje";
			
			$res = mysqli_query($conexion,$sql) or die(mysqli_error()."<br>".$sql."<hr>");
			
			$sql1 = "SELECT Nombre,Apellidos,DNI
					FROM trabajador";
			
			$res1 = mysqli_query($conexion,$sql1) or die(mysqli_error()."<br>".$sql1."<hr>");
			
		 echo "<form method=POST action='ModificarReserva.php'>";
		 echo "<p>DNI alumno: ".$_SESSION['DNI'] ."</p>";
		
		echo "Tipo de masaje: ";
		echo "<select name=NombreMasaje>";
			while($registro=mysqli_fetch_assoc($res)){
				echo '<option value="'.$registro["Id_Masaje"].'">"'.$registro["Nombre"].'"</option>';
			}
		echo "</select>";
		echo "<br>";
		echo "<br>";
		echo "Trabajador: ";
		echo "<select name=NombreTrabajador>";
			while($registro1=mysqli_fetch_assoc($res1)){
				echo '<p>Trabajador: <option value="'.$registro1["DNI"].'">"'.$registro1["Nombre"].' '.$registro1["Apellidos"].'"</option></p>';
			}
			
		echo "</select>";
		
		echo "<p>Hora inicio masaje: <input name=Hora_Inicio type='time' min='07:00' max='23:00' required> </p>";
		echo "<p>Hora fin masaje: <input name=Hora_Fin type='time' min='07:00' max='23:00' required> </p>";
		echo "<p>Fecha: <input name=Fecha type=date required></p>";
		
		
		echo "<input type='hidden' name = clave value = ".$_POST['clave'].">";
		echo '<input type = "submit" name = "Enviar1" value="Realizar cambios"/>';
		
		
		echo "</form>";
		
		
		}
		
		
		
		
		
//Han pulsado el boton de añadir una nueva reserva de masaje ----------------------------------------------------------------------------------------------------

		if (isset($_POST['Crear'])){
			
			$sql = "SELECT DISTINCT Nombre,Id_Masaje
					FROM masaje";
			
			$res = mysqli_query($conexion,$sql) or die(mysqli_error()."<br>".$sql."<hr>");
			
			$sql1 = "SELECT Nombre,Apellidos,DNI
					FROM trabajador";
			
			$res1 = mysqli_query($conexion,$sql1) or die(mysqli_error()."<br>".$sql1."<hr>");
			
		 echo "<form method=POST action='AltaReserva.php'>";
		 echo "<p>DNI alumno: ".$_SESSION['DNI'] ."</p>";
		
		echo "Tipo de masaje: ";
		echo "<select name=NombreMasaje>";
			while($registro=mysqli_fetch_assoc($res)){
				echo '<option value="'.$registro["Id_Masaje"].'">"'.$registro["Nombre"].'"</option>';
			
			}
		echo "</select>";
		echo "<br>";
		echo "<br>";
		echo "Trabajador: ";
		echo "<select name=NombreTrabajador>";
			while($registro1=mysqli_fetch_assoc($res1)){
				echo '<p>Trabajador: <option value="'.$registro1["DNI"].'">"'.$registro1["Nombre"].' '.$registro1["Apellidos"].'"</option></p>';
			}
			
		echo "</select>";
		
		echo "<p>Hora inicio masaje: <input name=Hora_Inicio type='time' min='07:00' max='23:00' required> </p>";
		echo "<p>Hora fin masaje: <input name=Hora_Fin type='time' min='07:00' max='23:00' required> </p>";
		echo "<p>Fecha: <input name=Fecha type=date required></p>";
		
		
		
		
		echo '<input type = "submit" name = "Enviar" value="Añadir nuevo masaje"/>';
		
		
		echo "</form>";

		
	}
				
?>
</html>