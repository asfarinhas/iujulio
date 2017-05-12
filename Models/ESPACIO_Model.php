<?php
//Comprueba si el usuario inició sesión y si es admin antes de cargar la página.
if(isset($_SESSION['grupo']) && strcmp($_SESSION['grupo'],"Admin") == 0 || strcmp($_SESSION['grupo'],'Secretario')==0) {

//Include de la función de conexión a la base de datos.
    include_once('conectarBD.php');

    class espacio
    {

        var $idEspacio;
        var $espacio;
        var $mysqli;

        //Constructor. La acción puede ser null.
        function __construct($idEspacio, $espacio)
        {
            $this->idEspacio = $idEspacio;
            $this->espacio = $espacio;
        }


        //Getters
        function getIdEspacio()
        {
            return $this->idEspacio;
        }

        function getEspacio()
        {
            return $this->espacio;
        }


        function crearEspacio()
        {
            $this->mysqli = conectarBD();
            $sql = "SELECT * FROM Espacio WHERE Nombre='" . $this->getEspacio() . "';";
            $resultado = $this->mysqli->query($sql);
            if ($resultado->num_rows == 0) {
                $sql = "INSERT INTO Espacio (Id_Espacio,Nombre) VALUES ('" . $this->idEspacio . "','" . $this->getEspacio() . "');";
                $this->mysqli->query($sql);
                return "añadido exito";
            } else {
                $sql = "SELECT * FROM Espacio WHERE Nombre='" . $this->getEspacio() . "' AND Borrado='1';";
                $resultado = $this->mysqli->query($sql);
                if ($resultado->num_rows == 1) {
                    $sql = "UPDATE Espacio SET Borrado='0' WHERE Nombre='" . $this->getEspacio() . "';";
                    $this->mysqli->query($sql);
                    return "añadido exito";
                } else return "ya existe";
            }

        }

        function borrar()

        {
            $this->mysqli = conectarBD();

            $sql = "UPDATE Espacio SET Borrado='1' WHERE Nombre='" . $this->getEspacio() . "';";
            if ($this->mysqli->query($sql) === TRUE) {
                return "borrado exito";
            } else
                return "error borrado";
        }

        function modificar($espacioNuevo)
        {
            $this->mysqli = conectarBD();

            $sql = "SELECT * FROM Espacio WHERE Nombre='" . $this->getEspacio() . ";'";
            $resultado = $this->mysqli->query($sql);
            if ($resultado->num_rows == 0) {
                $sql = "UPDATE Espacio SET Nombre='" . $espacioNuevo->getEspacio() . "',Id_Espacio='" . $espacioNuevo->getIdEspacio() . "' WHERE Nombre='" . $this->getEspacio() . "';";
                $this->mysqli->query($sql);
                return "El espacio ha sido modificado";
            }else{
                return "El espacio no se ha podido modificar";
            }
        }
        function obtenerTupla(){
            $this->mysqli = conectarBD();

            $sql = "SELECT * FROM Espacio WHERE Nombre='" . $this->getEspacio() . "';";
            $resultado = $this->mysqli->query($sql);
          $fila= mysqli_fetch_assoc($resultado);
          return $fila["Id_Espacio"];
        }
    }


    function listarEspacios()
    {
        $db = conectarBD();
        $sql = "SELECT * FROM Espacio WHERE Borrado='0'ORDER BY Nombre;";
        $resultado = $db->query($sql);
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_array()) {
                echo "<option value='" . $row['Nombre'] . "'>" . $row['Nombre'] . "</option>";
            }
        }
    }
    function verEspacios(){

        $db = conectarBD();

        $sql = "SELECT * FROM Espacio WHERE Borrado='0' ORDER BY Nombre;";
        $resultado = $db->query($sql);
        $db->close();
        if ($resultado->num_rows > 0){
            while($row = $resultado->fetch_array()) {
                echo "<tr> <td>".$row['Nombre']."</td> <td>".$row['Id_Espacio']."</td> </tr>";
            }
        }
    }
}



?>