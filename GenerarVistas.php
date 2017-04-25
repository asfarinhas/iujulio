<?php
    echo "Iniciando creación de vistas...";//Mostramos mensaje para saber cuando empieza



function conectarBD(){//Creamos una funcion para conectarnos a la BD
    $bd = new mysqli("localhost", "root", "iu", "MOOVETT");
    if (mysqli_connect_errno()){
        echo "Fallo al conectar MySQL: " . $mysqli->connect_error();
    }
    return $bd;
}

function listarTablas() //Creamos una funcion para que nos devuelva todas las tablas de la BD
{
    $mysqli2 = conectarBD();
    $sql = 'show full tables from MOOVETT';
    if (!($resultado = $mysqli2->query($sql))) {
        return 'Error en la consulta sobre la base de datos';
    } else {
        $tables = array();
        while($tabla = $resultado->fetch_array(MYSQLI_ASSOC)){
            array_push($tables,$tabla['Tables_in_MOOVETT']);
        }
        return $tables;
    }

}

$arrayTablas = listarTablas();//Llamamos a la funcion listarTablas() para que nos devuelva todas las tablas. Le llamamos $arrayTablas
foreach($arrayTablas as $tabla){//Recorremos el array con las vistas
    crearVistas($tabla);//Llamamos a la funcion crear vistas
    crearADD($tabla);
    crearSEARCH($tabla);
    crearEDIT($tabla);
    crearDELETE($tabla);
    crearSHOWALL($tabla);
    crearSHOWCURRENT($tabla);

}
echo "Vistas creadas";

function crearVistas($tabla){


    fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_SEARCH_View.php","w+");
    fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_EDIT_View.php","w+");
    fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_DELETE_View.php","w+");
    fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_SHOWALL_View.php","w+");
    fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_SHOWCURRENT_View.php","w+");



}

function crearADD($tabla){
    $file=fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_ADD_View.php","w+");
    $str='<?php class'. strtoupper($tabla) . '_ADD { 
          function __construct(){ 
                $this->render();
                }
          function render(){ 
    require_once(\'../header.php\'); 
?>
    
    <title>Añadir></title>
    <body>
    <div class=\"row-fluid\">
    <body>
		<!-- Include del menú-->
		<div class=\"row-fluid\">
			<?php include_once(\'menu.php\'); ?>

			<div class=\"col-sm-10 text-left\">
				<div class=\"section-fluid\">
					<div class=\"container-fluid\">
					<form class=\"form-horizontal\" role=\"form\" action=\"../controllers/TRABAJADOR_Controller.php?id=altaTrabajador\"
					 method=\"POST\" enctype=\"multipart/form-data\" >';

    fwrite($file,$str);

}



function crearArrayFormulario($tabla){
    $file = fopen("/var/www/html/iujulio/Functions/" . strtoupper($tabla) . "_DefForm.php","w+");

        <?php

        include 'gen_form_class.php';

        $form = array(
                array("action","procesaform.php"), //action, nombre fichero action
                array("input","text","usergit","Usuario en git"),//etiqueta input, type, name, texto introducción
                array("input","email","emailgit","Email del usuario git"),//etiqueta input, type, name, texto introducción
                array("input","date","fechnacuser","Fecha nacimiento usuario"),//etiqueta input, type, name, texto introducción
                array("input","text","grupopracticas","Grupo Prácticas"),//etiqueta input, type, name, texto introducción
                array("input","text","nombreuser","Nombre del usuario"),//etiqueta input, type, name, texto introducción
                array("input","text","apellidosuser","Apellidos del usuario"),//etiqueta input, type, name, texto introducción
                array("input","text","cursoacademicouser","Curso académico mas alto"),//etiqueta input, type, name, texto introducción
                array("input","text","titulacionuser","Titulación del usuario"),//etiqueta input, type, name, texto introducción
                array("method","get"), //method, valor de method
                array("input","submit","enviar") //etiqueta input, valor de type, value
        );

        $form5 = array(
                 array("action","procesaform.php"), //action, nombre fichero action
                 array("input","text","usergit","Usuario en git"),//etiqueta input, type, name, texto introducción
                 array("input","email","emailgit","Email del usuario git"),//etiqueta input, type, name, texto introducción
                 array("input","date","fechnacuser","Fecha nacimiento usuario"),//etiqueta input, type, name, texto introducción
                 array("input","text","grupopracticas","Grupo Prácticas"),//etiqueta input, type, name, texto introducción
                 array("input","text","nombreuser","Nombre del usuario"),//etiqueta input, type, name, texto introducción
                 array("input","text","apellidosuser","Apellidos del usuario"),//etiqueta input, type, name, texto introducción
                 array("input","number","cursoacademicouser","Curso académico mas alto"),//etiqueta input, type, name, texto introducción
                 array("input","text","titulacionuser","Titulación del usuario"),//etiqueta input, type, name, texto introducción
                 array("method","get"), //method, valor de method
                 array("input","submit","enviar") //etiqueta input, valor de type, value
        );

        //new gen_form($form);
        //echo '<br>';
        new gen_form($form5);

?>

}


function listarAtributos($tabla){
    $mysqli2 = conectarBD();
    $sql = 'SELECT * FROM ' . $tabla . ';';

    if (!($resultado = $mysqli2->query($sql))) {
        return 'Error en la consulta sobre la base de datos';
    } else {
        $finfo = mysqli_fetch_fields($resultado);


        return $finfo;
    }

}
?>
