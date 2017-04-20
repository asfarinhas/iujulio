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

    fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_ADD_View.php","w+");
    fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_SEARCH_View.php","w+");
    fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_EDIT_View.php","w+");
    fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_DELETE_View.php","w+");
    fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_SHOWALL_View.php","w+");
    fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_SHOWCURRENT_View.php","w+");


}

function crearADD($tabla){
    $file = fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_ADD_View.php","w+");
    fwrite($file, "<?php" . PHP_EOL);
    fwrite($file, "class ". strtoupper($tabla) . "_ADD {" . PHP_EOL);
    fwrite($file, 'function __construct(){' . PHP_EOL);
    fwrite($file, '$this->render();' . PHP_EOL);
    fwrite($file, '}' . PHP_EOL);
    fwrite($file, 'function render(){' . PHP_EOL);
    fwrite($file, 'require_once(\'../header.php\'); ' . PHP_EOL);
    fwrite($file, "?>" . PHP_EOL);
    fwrite($file, "<title>Añadir></title>" . PHP_EOL);
    fwrite($file, "<body>" . PHP_EOL);
    fwrite($file, "<div class=\"row-fluid\">" . PHP_EOL);
    fwrite($file, "<?php include_once('menu.php'); ?>" . PHP_EOL);
    fwrite($file, "<div class=\"col-sm-10 text-left\">" . PHP_EOL);
    fwrite($file, "<div class=\"section-fluid\">" . PHP_EOL);
    fwrite($file, "<div class=\"container-fluid\">" . PHP_EOL);
    fwrite($file, "include '../Functions/ACTIVIDAD2DefForm.php';" . PHP_EOL);
    crearArrayFormulario($tabla);
}

function crearSEARCH($tabla){

    $file = fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_SEARCH_View.php","w+");
    fwrite($file, "<?php" . PHP_EOL);
    fwrite($file, "class ". strtoupper($tabla) . "_SEARCH {" . PHP_EOL);
    fwrite($file, 'function __construct(){' . PHP_EOL);
    fwrite($file, '$this->render();' . PHP_EOL);
    fwrite($file, '}' . PHP_EOL);
    fwrite($file, 'function render(){' . PHP_EOL);
    fwrite($file, 'require_once(\'../header.php\'); ' . PHP_EOL);
    fwrite($file, "?>" . PHP_EOL);
    fwrite($file, "<title>Buscar></title>" . PHP_EOL);
    fwrite($file, "<body>" . PHP_EOL);
    fwrite($file, "<div class=\"row-fluid\">" . PHP_EOL);
    fwrite($file, "<?php include_once('menu.php'); ?>" . PHP_EOL);
    fwrite($file, "<div class=\"col-sm-10 text-left\">" . PHP_EOL);
    fwrite($file, "<div class=\"section-fluid\">" . PHP_EOL);
    fwrite($file, "<div class=\"container-fluid\">" . PHP_EOL);

}


function crearEDIT($tabla){
    $file = fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_EDIT_View.php","w+");
    fwrite($file, "<?php" . PHP_EOL);
    fwrite($file, "class ". strtoupper($tabla) . "_EDIT {" . PHP_EOL);
    fwrite($file, 'function __construct(){' . PHP_EOL);
    fwrite($file, '$this->render();' . PHP_EOL);
    fwrite($file, '}' . PHP_EOL);
    fwrite($file, 'function render(){' . PHP_EOL);
    fwrite($file, 'require_once(\'../header.php\'); ' . PHP_EOL);
    fwrite($file, "?>" . PHP_EOL);
    fwrite($file, "<title>Modificar></title>" . PHP_EOL);
    fwrite($file, "<body>" . PHP_EOL);
    fwrite($file, "<div class=\"row-fluid\">" . PHP_EOL);
    fwrite($file, "<?php include_once('menu.php'); ?>" . PHP_EOL);
    fwrite($file, "<div class=\"col-sm-10 text-left\">" . PHP_EOL);
    fwrite($file, "<div class=\"section-fluid\">" . PHP_EOL);
    fwrite($file, "<div class=\"container-fluid\">" . PHP_EOL);

}

function crearDELETE($tabla){
    $file = fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_DELETE_View.php","w+");
    fwrite($file, "<?php" . PHP_EOL);
    fwrite($file, "class ". strtoupper($tabla) . "_DELETE {" . PHP_EOL);
    fwrite($file, 'function __construct(){' . PHP_EOL);
    fwrite($file, '$this->render();' . PHP_EOL);
    fwrite($file, '}' . PHP_EOL);
    fwrite($file, 'function render(){' . PHP_EOL);
    fwrite($file, 'require_once(\'../header.php\'); ' . PHP_EOL);
    fwrite($file, "?>" . PHP_EOL);
    fwrite($file, "<title>Borrar></title>" . PHP_EOL);
    fwrite($file, "<body>" . PHP_EOL);
    fwrite($file, "<div class=\"row-fluid\">" . PHP_EOL);
    fwrite($file, "<?php include_once('menu.php'); ?>" . PHP_EOL);
    fwrite($file, "<div class=\"col-sm-10 text-left\">" . PHP_EOL);
    fwrite($file, "<div class=\"section-fluid\">" . PHP_EOL);
    fwrite($file, "<div class=\"container-fluid\">" . PHP_EOL);

}

function crearSHOWALL($tabla){
    $file = fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_SHOWALL_View.php","w+");
    fwrite($file, "<?php" . PHP_EOL);
    fwrite($file, "class ". strtoupper($tabla) . "_SHOWALL {" . PHP_EOL);
    fwrite($file, 'function __construct(){' . PHP_EOL);
    fwrite($file, '$this->render();' . PHP_EOL);
    fwrite($file, '}' . PHP_EOL);
    fwrite($file, 'function render(){' . PHP_EOL);
    fwrite($file, 'require_once(\'../header.php\'); ' . PHP_EOL);
    fwrite($file, "?>" . PHP_EOL);
    fwrite($file, "<title>Listar></title>" . PHP_EOL);
    fwrite($file, "<body>" . PHP_EOL);
    fwrite($file, "<div class=\"row-fluid\">" . PHP_EOL);
    fwrite($file, "<?php include_once('menu.php'); ?>" . PHP_EOL);
    fwrite($file, "<div class=\"col-sm-10 text-left\">" . PHP_EOL);
    fwrite($file, "<div class=\"section-fluid\">" . PHP_EOL);
    fwrite($file, "<div class=\"container-fluid\">" . PHP_EOL);

}

function crearSHOWCURRENT($tabla){
    $file = fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_SHOWCURRENT_View.php","w+");
    fwrite($file, "<?php" . PHP_EOL);
    fwrite($file, "class ". strtoupper($tabla) . "_SHOWCURRENT{" . PHP_EOL);
    fwrite($file, 'function __construct(){' . PHP_EOL);
    fwrite($file, '$this->render();' . PHP_EOL);
    fwrite($file, '}' . PHP_EOL);
    fwrite($file, 'function render(){' . PHP_EOL);
    fwrite($file, 'require_once(\'../header.php\'); ' . PHP_EOL);
    fwrite($file, "?>" . PHP_EOL);
    fwrite($file, "<title>Consultar></title>" . PHP_EOL);
    fwrite($file, "<body>" . PHP_EOL);
    fwrite($file, "<div class=\"row-fluid\">" . PHP_EOL);
    fwrite($file, "<?php include_once('menu.php'); ?>" . PHP_EOL);
    fwrite($file, "<div class=\"col-sm-10 text-left\">" . PHP_EOL);
    fwrite($file, "<div class=\"section-fluid\">" . PHP_EOL);
    fwrite($file, "<div class=\"container-fluid\">" . PHP_EOL);


}

function crearArrayFormulario($tabla){
    fopen("/var/www/html/iujulio/Functions/" . strtoupper($tabla) . "_DefForm.php","w+");
}
?>
