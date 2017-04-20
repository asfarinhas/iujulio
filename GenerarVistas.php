<?php
    echo "Iniciando creacción de vistas...";//Mostramos mensaje para saber cuando empieza


function conectarBD(){//Creamos una funcion para conectarnos a la BD
    $bd = new mysqli("localhost", "root", "iu", "MOOVETT");
    if (mysqli_connect_errno()){
        echo "Fallo al conectar MySQL: " . $this->mysqli->connect_error();
    }
    return $bd;
}
function listarTablas() //Creamos una funcion para que nos devuelva todas las tablas de la BD
{
    $this->mysqli = conectarBD();
    $sql = 'show full tables from MOOVETT';
    if (!($resultado = $this->mysqli->query($sql))) {
        return 'Error en la consulta sobre la base de datos';
    } else {
        $result = $resultado->fetch_array(); //Convertimos el resultado de la consulta a un array asociativo
        return $result;
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

function crearVistas($tabla){
    fopen("../Views/" . strtoupper($tabla) . "_ADD_View.php","w+");
    fopen("../Views/" . strtoupper($tabla) . "SEARCH_View.php","w+");
    fopen("../Views/" . strtoupper($tabla) . "EDIT_View.php","w+");
    fopen("../Views/" . strtoupper($tabla) . "DELETE_View.php","w+");
    fopen("../Views/" . strtoupper($tabla) . "SHOWALL_View.php","w+");
    fopen("../Views/" . strtoupper($tabla) . "SHOWCURRENT_View.php","w+");
}

function crearADD($tabla){

}

function crearSEARCH($tabla){

}


function crearEDIT($tabla){

}

function crearDELETE($tabla){

}

function crearSHOWALL($tabla){

}

function crearSHOWCURRENT($tabla){


}
?>
