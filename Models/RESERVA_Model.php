<?php
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 || strcmp($_SESSION['grupo'],'Secretario')==0) {

//Include de la función de conexión a la base de datos.
    include_once('conectarBD.php');

    class reserva
    {
        var $telefono;
        var $idEspacio;
        var $horaIni;
        var $horaFin;
        var $fecha;
        var $descripcion;
        var $dniReserva;
        var $mysqli;
        var $sqlReserva;

        //Constructor. La acción puede ser null.
        function __construct($telefono,$idEspacio, $horaIni, $horaFin, $fecha, $descripcion, $dniReserva)
        {
            $this->telefono= $telefono;
            $this->idEspacio = $idEspacio;
            $this->horaIni = $horaIni;
            $this->horaFin = $horaFin;
            $this->fecha = $fecha;
            $this->descripcion = $descripcion;
            $this->dniReserva = $dniReserva;
        }

        //Getters
        function getIdEspacio()
        {
            return $this->idEspacio;
        }

        public function getIdReserva()
        {
            return $this->idReserva;
        }

        public function getHoraIni()
        {
            return $this->horaIni;
        }

        public function getHoraFin()
        {
            return $this->horaFin;
        }

        public function getFecha()
        {
            return $this->fecha;
        }

        public function getDescripcion()
        {
            return $this->descripcion;
        }

        public function getDniReserva()
        {
            return $this->dniReserva;
        }

        public function getTelefono(){
            return $this->telefono;
        }

        function reservar(){
            $noSeCrea=false;
            $this->mysqli = conectarBD();
            $busquedaAula= "SELECT * From Reserva_Espacio WHERE Id_Espacio='". $this->getIdEspacio()."';";
            $resultado=$this->mysqli->query($busquedaAula);
            if($resultado->num_rows == 0){
                $sql = "INSERT INTO Reserva_Espacio (Telefono,Id_Espacio,Hora_Inicio,Hora_Fin,Fecha,Descripcion,DNI_Reserva) VALUES ('".$this->telefono."','" . $this->idEspacio . "','" . $this->horaIni . "','" . $this->horaFin . "','" . $this->fecha . "','" . $this->descripcion . "','" . $this->dniReserva . "');";
                $this->mysqli->query($sql);
            }else{

                $sqlTodasLasFilas="SELECT * from Reserva_Espacio";

                if($result = $this->mysqli->query($sqlTodasLasFilas)) {

                    while ($row = mysqli_fetch_assoc($result)) {

                        if ($row["Fecha"] == $this->getFecha()) {
                            $string = $row["Hora_Inicio"];
                            $n = explode(':', $string);
                            $rowFechaIni = $n[0] . ":" . $n[1];

                            $string1 = $row["Hora_Fin"];
                            $n2 = explode(':', $string1);
                            $rowFechaFin = $n2[0] . ":" . $n2[1];

                            if ($this->getHoraIni() >= $rowFechaIni && $this->getHoraIni() <= $rowFechaFin || $this->getHoraFin() >= $rowFechaIni && $this->getHoraFin() <= $rowFechaFin) {

                                 $noSeCrea = true;
                                break;
                            }
                        } else {
                            $noSeCrea = false;
                        }
                    }
                    if ($noSeCrea == false) {
                        $sql = "INSERT INTO Reserva_Espacio (Telefono,Id_Espacio,Hora_Inicio,Hora_Fin,Fecha,Descripcion,DNI_Reserva) VALUES ('".$this->telefono."','" . $this->idEspacio . "','" . $this->horaIni . "','" . $this->horaFin . "','" . $this->fecha . "','" . $this->descripcion . "','" . $this->dniReserva . "');";
                        $this->mysqli->query($sql);
                        return "reserva con exito";
                    }else {
                        return"no se puede realizar reserva";
                        }
                }
            }
        }

        function modificarReserva(){
            $noSeCrea=false;
            $this->mysqli = conectarBD();
            $busquedaAula= "SELECT * From Reserva_Espacio WHERE Id_Reserva='". $_POST['reservaN']."';";
            $resultado=$this->mysqli->query($busquedaAula);
            if($resultado->num_rows == 1){
                $sqlTodasLasFilas="SELECT * from Reserva_Espacio WHERE Id_Reserva!='".$_POST['reservaN']."';" ;

                if($result = $this->mysqli->query($sqlTodasLasFilas)) {

                    while ($row = mysqli_fetch_assoc($result)) {

                        if ($row["Fecha"] == $this->getFecha()) {
                            $string = $row["Hora_Inicio"];
                            $n = explode(':', $string);
                            $rowFechaIni = $n[0] . ":" . $n[1];

                            $string1 = $row["Hora_Fin"];
                            $n2 = explode(':', $string1);
                            $rowFechaFin = $n2[0] . ":" . $n2[1];

                            if ($this->getHoraIni() >= $rowFechaIni && $this->getHoraIni() < $rowFechaFin || $this->getHoraFin() >= $rowFechaIni && $this->getHoraFin() < $rowFechaFin) {
                                echo "ente";
                                $noSeCrea = true;
                                break;
                            }
                        } else {
                            $noSeCrea = false;
                        }
                    }
                    if ($noSeCrea == false) {
                        $sql = "UPDATE  Reserva_Espacio SET Telefono='".$this->telefono."',Id_Espacio='" . $this->idEspacio . "',Hora_Inicio='" . $this->horaIni . "',Hora_Fin='" . $this->horaFin . "',Fecha='" . $this->fecha . "',Descripcion='" . $this->descripcion . "',DNI_Reserva='" . $this->dniReserva . "'WHERE Id_Reserva='". $_POST['reservaN']."';";
                        echo $sql;
                        $this->mysqli->query($sql);
                        return "Reserva modificado con exito";
                    }else {
                        return"Lo sentimos la reserva no se ha podido modificar";
                    }
                }
            }
        }


    }
    function eliminarReserva($Id_ReservaABorrar){
        $db = conectarBD();
        //$sql1= "SELECT Id_Rserva FROM Reserva_Espacio WHERE Hora_Inicio='".$this->getHoraIni()."'AND Hora_Fin='".$this->getHoraFin()."';";
        $sql = "UPDATE Reserva_Espacio SET Borrado='1' WHERE Id_Reserva='" . $Id_ReservaABorrar . "';";
        if ($db->query($sql) === TRUE) {
            return "borrado exito";
        } else
            return "error borrado";
    }

    function listarReservas(){
        $db = conectarBD();
        $sql = "SELECT * FROM Reserva_Espacio WHERE Borrado='0'ORDER BY Id_Reserva;";
        $resultado = $db->query($sql);
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_array()) {
                echo "<option value='" . $row['Id_Reserva'] . "'>"."ID del espacio: ". $row['Id_Espacio']. " Desde: ".$row['Hora_Inicio']." Hasta: ".$row['Hora_Fin']." El día ".$row['Fecha']."</option>";
            }
        }
    }

    function verReservas(){

        $db = conectarBD();

        $sql = "SELECT * FROM Reserva_Espacio WHERE Borrado='0' ORDER BY Id_Reserva;";
        $resultado = $db->query($sql);
        $db->close();
        if ($resultado->num_rows > 0){
            while($row = $resultado->fetch_array()) {
                echo "<tr> <td>".$row['Telefono']."</td><td>".$row['Id_Reserva']."</td> <td>".$row['Id_Espacio']."</td> <td>".$row['Hora_Inicio']."</td><td>".$row['Hora_Fin']."</td><td>".$row['Fecha']."</td><td>".$row['Descripcion']."</td><td>".$row['DNI_Reserva']."</td></tr>";
            }
        }
    }
}



?>