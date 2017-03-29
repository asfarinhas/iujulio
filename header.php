<html>
	<head>

	<!-- Se incluyen todos los elementos del framework -->
    <meta charset="utf-8">  
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../bootstrap/jquery.min.js"></script>
    <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
    <link href="../bootstrap/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<script src="../bootstrap/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script> 
	
	<?php 
	//Carga el idioma guardado en la variable de sesión o el Español por defecto
	if(isset($_SESSION['lang'])){
		if(strcmp($_SESSION['lang'],'gal')==0)
			include("../locates/Strings_GALEGO.php"); 
		else if(strcmp($_SESSION['lang'],'esp')==0)
			include("../locates/Strings_CASTELLANO.php"); 
	}else{
		include("../locates/Strings_CASTELLANO.php"); 
	}
	?>
	
    <style>
      /* Remove the navbar's default margin-bottom and rounded borders */
	  .navbar {
		margin-bottom: 0;
		border-radius: 0;
	  }
    </style>

	<!-- Menú de la cabecera -->
	<nav class="navbar navbar-inverse">
	<div class="container-fluid">
	  <div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		  <span class="sr-only"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</button>
		<a class="navbar-header"><img src="../images/logo.png"></a>
	  </div>
	  <div id="navbar" class="navbar-collapse collapse">
	  
		<!-- Menú de la cabecera: regreso a la pantalla inicial y cambio de idioma -->
		<ul class="nav navbar-nav">
			<li><a class="active" href="../views/default_Vista.php">
				<span style="font-size:20px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-home"></span></a>
			</li>
			<a href="../models/cambiaIdioma.php?lang=esp">
			<img  src="../images/castellano.png"></a>
			<a href="../models/cambiaIdioma.php?lang=gal">
			<img  src="../images/gallego.png"></a>
		</ul>
		
		<!-- Menú de la cabecera: indica el usuario logueado y permite hacer logout -->
		<ul class="nav navbar-nav navbar-right">
			<li>
				<a style="font-size:14px;"><?php echo $strings['logueado'].": ".$_SESSION['user']; ?></a>
			</li>
			<a href="../logout.php"><button class="btn btn-danger navbar-btn" style="font-size:13px;">
			<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-off"></span></button></a>	
		</ul>
	  </div>
	</div>
	</nav>

</head></html>