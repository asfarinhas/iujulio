<html>
	<head>	
		<title> Login</title>
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="bootstrap/login.css" rel="stylesheet">
	</head>	
	
    <body>	 	
	<!-- Formulario que recoge los datos de login -->
	<div class="container">
        <div class="card card-container">
			<form class="form-signin" accept-charset="UTF-8" method="POST" action="login.php">
				<fieldset>
					<h1 class="form-signin-heading text-muted">Login</h1>
					
					<!-- Campo que recoge el user del usuario -->
					<div class="form-group">
					<h2><small>Usuario:</h2></small>
					<input type="text" name="usuario" class="form-control" value="admin" pattern="([A-Za-z0-9]{4,30})" title="solo caracteres alfanuméricos" required autofocus>
					</div>
					
					<!-- Campo que recoge el password del usuario -->
					<div class="form-group">
					<h2><small>Password:</h2></small>
					<input type="password" name="password" value="admin" pattern="([A-Za-z0-9]{4,30})" title="solo caracteres alfanuméricos" class="form-control" required><br>
					</div>
					
					<!-- Sumbit que envía user y password -->
					<button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">ENTRAR</button>
				</fieldset>
			</form>
		</div>
    </div>
	</body>	
</html>	

<?php

include_once('models/conectarBD.php');

//Carga en una variable de sesión los permisos del usuario.
function cargarPermisos($user){
	
	$db = conectarBD();
	
	$sql = "SELECT Nombre_Controlador,Accion FROM Permisos P, Usuario U WHERE U.Usuario='".$user."' AND P.Nombre_Grupo=U.Nombre_Grupo;";
	$result=$db->query($sql);
	if ($result->num_rows > 0){
		$permisos = array();
		while($row = $result->fetch_array()) {
			if(!in_array($row['Nombre_Controlador'],$permisos)){
				array_push($permisos,$row['Nombre_Controlador']);
			}
			array_push($permisos,$row['Nombre_Controlador']."_".$row['Accion']);
		}
		return $permisos;
	}else{
		return null;
	}
}

//Comprueba si existe la base de datos y muestra un error. 
//Busca al usuario en la BD y vuelve al index si existe, si no muestra un error.
if(isset($_POST['usuario'])){
	
	$db = conectarBD();	

	$user=$_POST['usuario'];
	$password=$_POST['password'];
	$password=md5($password); // Encriptamos el password
	$sql= "SELECT Nombre_Grupo FROM Usuario WHERE Usuario='$user' and Password='$password'";
	$result=$db->query($sql);	
	
	//Se crean variables de sesión para el usuario (user), grupo al que pertenece y permisos.
	if($result->num_rows == 1){
		session_start();
		$_SESSION['user'] = $user;
		$array = $result->fetch_array();
		$_SESSION['grupo'] = $array['Nombre_Grupo'];
		$_SESSION['permisos'] = cargarPermisos($user);
		header("location: index.php");
	}else{
		echo "<div class='col-sm-4 text-left'></div><div class='col-sm-4 text-left'><div class='alert alert-danger'>¡ERROR! Usuario no encontrado.</div></div>";
	}
}
?>
