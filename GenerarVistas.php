<?php
    echo "Iniciando creacción de vistas...";

function conectarBD(){

    $bd = new mysqli("localhost", "root", "iu", "MOOVETT");
    if (mysqli_connect_errno()){
        echo "Fallo al conectar MySQL: " . $this->mysqli->connect_error();
    }
    return $bd;
}
function listarTablas()
{
    $this->mysqli = conectarBD();

    $sql = 'Sshow full tables from MOOVETT';
    if (!($resultado = $this->mysqli->query($sql))) {
        return 'Error en la consulta sobre la base de datos';
    } else {
        $result = $resultado->fetch_array();
        return $result;
    }
}
$arrayTablas = listarTablas();
foreach($arrayTablas as $vista){
    crearVistas($vista);
}

function crearVistas($vista){
    fopen("../Views/" . strtoupper($vista). "_ADD_View.php","w+");
    fopen("../Views/" . strtoupper($vista)_ "SEARCH_View.php","w+");
    fopen("../Views/" . strtoupper($vista)_ "EDIT_View.php","w+");
    fopen("../Views/" . strtoupper($vista)_ "DELETE_View.php","w+");
    fopen("../Views/" . strtoupper($vista)_ "SHOWALL_View.php","w+");
    fopen("../Views/" . strtoupper($vista)_ "SHOWCURRENT_View.php","w+");
}
?>