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
    $atributo = listarAtributos($tabla);//Cogemos los atributos de la tabla y los pasamos a un array
    crearArrayFormulario($tabla,$atributos);//Llamamos a la funcion crear el array del formulario, y le pasamos la tabla y los atributos
}

function crearArrayFormulario($tabla, $atributos){
    $file = fopen("/var/www/html/iujulio/Functions/" . strtoupper($tabla) . "_DefForm.php","w+");
        $str = '
        include \'gen_form_class.php\';
        $form = array(
                array("action","'. $tabla. '"_CONTROLLER.php"), //action, nombre fichero action
                ';

                //Ahora lo que tenemos que hacer es concatenar lo siguiente, para que nos imprima la linea de cada atributo de la tabla.
                foreach ($atributos as $clave => $valor) {
                   $str += 'array("input","'. $atributos->type . '","'. $atributos->name . '","Usuario en git"),//etiqueta input, type, name, texto introducción';
                }

                $str += 'array("method","get"), //method, valor de method
                array("input","submit","enviar") //etiqueta input, valor de type, value
        );
        //new gen_form($form);
        //echo \'<br>\';
        new gen_form($form);';

            fwrite($file,$str);



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
