<?php

//Si no hay una sesión iniciada la inicia.
if(!isset($_SESSION)){
    session_start();
}
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 || strcmp($_SESSION['grupo'],'Secretario')==0){

    //Requires del modelo y las vistas.
    require_once('../models/ESPACIO_Model.php');
    require_once('../views/ESPACIO_EDIT_Vista.php');
    require_once('../views/ESPACIO_DELETE_Vista.php');
    require_once('../views/ESPACIO_ADD_Vista.php');
    require_once ('../views/ESPACIO_LIST_Vista.php');
    require_once('../views/RESERVAR_ADD_Vista.php');
    require_once ('../models/RESERVA_Model.php');
    require_once ('../views/RESERVA_DELETE_Vista.php');
    require_once ('../views/RESERVA_LIST_Vista.php');
    require_once ('../views/RESERVA_EDIT_Vista.php');



    //Include del idioma elegido, o español por defecto.
    if(isset($_SESSION['lang'])){
        if(strcmp($_SESSION['lang'],'gal')==0)
            include("../locates/Strings_GALEGO.php");
        else if(strcmp($_SESSION['lang'],'esp')==0)
            include("../locates/Strings_CASTELLANO.php");
    }else{
        include("../locates/Strings_CASTELLANO.php");
    }

    //Recoge los datos del formulario de la vista en un objeto "ESPACIO".
    function datosFormEspacio(){

        $espacio = $_POST['espacio'];
        if(isset($_POST['idEspacio'])){
            $idEspacio= $_POST['idEspacio'];
        }else{
            $idEspacio = null;
        }
        $esp = new espacio($idEspacio,$espacio);
        return $esp;

    }

    //Recoge del formulario los datos nuevos para modificar un "ESPACIO".
    function datosFormEspacioAModificar(){

        $espacio2 = $_POST['espacioN'];
        if(isset($_POST['idEspacioN'])){
            $idEspacio2= $_POST['idEspacioN'];
        }else{
            $idEspacio2 = null;
        }
        $esp2 = new espacio($idEspacio2,$espacio2);
        return $esp2;
    }



    //Primero invoca a la vista correspondiente según la opción elegida en el menú y pasada por get.
    //Después invoca un método del modelo con los datos recibidos vía post de la vista.
    //Finalmente muestra un mensaje de error o confirmación tras acceder a la BD y redirige a la vista.

    if(isset($_GET['id'])) {
        $accion = $_GET['id'];
        if ($accion == 'altaEspacio') {
            if (!isset($_POST['espacio'])) {
                new Espacio_Crear();
            } else {
                $espacio = datosFormEspacio();
                $mensaje = $espacio->crearEspacio(); ?>
                <script>
                    window.alert('<?php echo $strings[$mensaje]; ?>');
                </script><?php
                unset($_POST['espacio']);
                new Espacio_Crear();
            }
        }
        if ($accion == 'bajaEspacio') {
            if (!isset($_POST['espacio'])) {
                new Espacio_Borrar();
            } else {
                $espacio = datosFormEspacio();
                $mensaje = $espacio->borrar(); ?>
                <script>
                    window.alert('<?php echo $strings[$mensaje]; ?>');
                </script><?php
                unset($_POST['espacio']);
                new Espacio_Borrar();
            }
        }
        if ($accion == 'modificarEspacio') {
            if (!isset($_POST['espacioN'])) {
                new Espacio_Editar();
            } else {
                $espacio = datosFormEspacio();
                $espacioNuevoModificado = datosFormEspacioAModificar();
                $mensaje = $espacio->modificar($espacioNuevoModificado); ?>
                <script>
                    window.alert('<?php echo $strings[$mensaje]; ?>');
                </script><?php
                unset($_POST['espacio']);
                new Espacio_Editar();
            }
        }
        if($accion == 'consultarEspacio')new Espacio_Listar();

        if($accion=='reservarEspacio'){
            if (!isset($_POST['espacio'])) {
                new Reserva_crear();
            } else {

                $espacio = new espacio(null, $_POST['espacio']);
                $idEspacioAReservar=$espacio->obtenerTupla();
                $reserva1 = new reserva($_POST['Telefono'],$idEspacioAReservar, $_POST['Hora_Inicio'], $_POST['Hora_Fin'], $_POST['Fecha'], $_POST['Descripcion'], $_POST['DNI_Reserva']);
                $mensaje=$reserva1->reservar(); ?>
                    <script>
                        window.alert('<?php echo $strings[$mensaje]; ?>');
                    </script><?php
                unset($_POST['espacio']);
                new Reserva_crear();
            }
        }

        if($accion=='bajaReserva'){
            if (!isset($_POST['reservaABorrar'])) {
                new Reserva_Borrar();
            } else {
                eliminarReserva($_POST['reservaABorrar']);
                unset($_POST['espacio']);
                new Reserva_Borrar();
            }
        }
        if($accion == 'consultarReserva')new Reserva_Listar();
        }

        if($accion == 'modificarReserva'){
            if (!isset($_POST['reservaN'])) {
                new Reserva_Editar();
            } else {
                $espacioN= new espacio(null,$_POST['espacioN']);
                $idEspacioCambiadoAReservar=$espacioN->obtenerTupla();
                $modifReserva= new reserva($_POST['TelefonoN'],$idEspacioCambiadoAReservar,$_POST['Hora_InicioN'],$_POST['Hora_FinN'],$_POST['FechaN'],$_POST['DescripcionN'],$_POST['DNI_ReservaN']);
                $mensaje=$modifReserva->modificarReserva();?>
                <script>
                    window.alert('<?php echo $strings[$mensaje]; ?>');
                </script><?php
                unset($_POST['espacio']);
                new Reserva_Editar();
            }        }
}else echo "Permiso denegado.";
?>