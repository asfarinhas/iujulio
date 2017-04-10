<?php
//Comprueba si el usuario inició sesión y si es admin o secretario antes de cargar la página.
if(isset($_SESSION['grupo']) && (strcmp($_SESSION['grupo'],"Admin") == 0 || strcmp($_SESSION['grupo'],"Secretario") == 0) ){ 

    //Include de la función de conexión a la base de datos.
    include_once('conectarBD.php');

    class alumno{

            var $dni;
            var $apellidos;
            var $nombre;
            var $direccion;
            var $email;
            var $nacimiento;
            var $observaciones;
            var $profesion;
            
            var $mysqli;

            //Constructor.
            function __construct($dni=null,$apellidos=null,$nombre=null,$direccion=null,$email=null,$nacimiento=null,$observaciones=null,$profesion=null){

                $this->dni = $dni;
                $this->apellidos = $apellidos;
                $this->nombre  = $nombre;
                $this->direccion  = $direccion;
                $this->email  = $email;
                $this->nacimiento  = $nacimiento;
                $this->observaciones  = $observaciones;
                $this->profesion  = $profesion;
            }
            //Getters.
            function getDni(){
                    return $this->dni;
            }
            function getApellidos(){
                    return $this->apellidos;
            }
            function getNombre(){
                    return $this->nombre;
            }
            function getDireccion(){
                    return $this->direccion;
            }
            function getEmail(){
                    return $this->email;
            }
            function getNacimiento(){
                    return $this->nacimiento;
            }
            function getObservaciones(){
                    return $this->observaciones;
            }
            function getProfesion(){
                    return $this->profesion;
            }

            //Añade un alumno a la BD. Controla que no exista ya el alumno.
            //Si se había realizado un borrado lógico recupera el alumno.
            function crear(){

                    $this->mysqli = conectarBD();

                    $sql = "SELECT * FROM Alumno WHERE DNI='".$this->dni."';";
                    $resultado = $this->mysqli->query($sql);
                    
                    if ($resultado->num_rows == 0){

                        $sql = "INSERT INTO Alumno (DNI,Apellidos,Nombre,Direccion,Email,Fecha_Nacimiento,Observaciones,Profesion) VALUES ('".$this->dni."','".$this->apellidos."','".$this->nombre."','".$this->direccion."','".$this->email."','".$this->nacimiento."','".$this->observaciones."','".$this->profesion."');";
                        $this->mysqli->query($sql);		
                        return "añadido exito";
                                   
                    }else{
                        $sql = "SELECT * FROM Alumno WHERE DNI='".$this->dni."' AND Borrado='1';";
                        $resultado = $this->mysqli->query($sql);
                        if ($resultado->num_rows == 1){
                                        $sql = "UPDATE Alumno SET Borrado='0' WHERE DNI='".$this->dni."';";
                                        $this->mysqli->query($sql);

                                return "añadido exito";				
                        }else{ 
                            return "dni ocupado";
                        }
                        
                    }
            }

            //Realiza el borrado lógico de un alumno.
            function borrar(){

                $this->mysqli = conectarBD();
                $sql = "UPDATE Alumno SET Borrado='1' WHERE DNI='".$this->dni."';";
                if($this->mysqli->query($sql) === TRUE) {
                        return "borrado exito";
                }else
                        return "error borrado";
            }


            //Modifica los datos de un alumno.
            function modificar($alumnoNuevo){

                $this->mysqli = conectarBD(); 
                if($alumnoNuevo->getApellidos() != null){
                        $sql= "UPDATE Alumno SET Apellidos='".$alumnoNuevo->getApellidos()."' WHERE DNI='".$alumnoNuevo->getDni()."';";
                        $this->mysqli->query($sql);
                }
                if($alumnoNuevo->getNombre() != null){
                        $sql= "UPDATE Alumno SET Nombre='".$alumnoNuevo->getNombre()."' WHERE DNI='".$alumnoNuevo->getDni()."';";
                        $this->mysqli->query($sql);
                }

                if($alumnoNuevo->getDireccion() != null){
                        $sql= "UPDATE Alumno SET Direccion='".$alumnoNuevo->getDireccion()."' WHERE DNI='".$alumnoNuevo->getDni()."';";
                        $this->mysqli->query($sql);
                }
                if($alumnoNuevo->getEmail() != null){
                        $sql= "UPDATE Alumno SET Email='".$alumnoNuevo->getEmail()."' WHERE DNI='".$alumnoNuevo->getDni()."';";
                        $this->mysqli->query($sql);
                }
                if($alumnoNuevo->getNacimiento() != null){
                        $sql= "UPDATE Alumno SET Fecha_Nacimiento='".$alumnoNuevo->getNacimiento()."' WHERE DNI='".$alumnoNuevo->getDni()."';";
                        $this->mysqli->query($sql);
                }
                if($alumnoNuevo->getProfesion() != null){
                        $sql= "UPDATE Alumno SET Profesion='".$alumnoNuevo->getProfesion()."' WHERE DNI='".$alumnoNuevo->getDni()."';";
                        $this->mysqli->query($sql);
                }
                if($alumnoNuevo->getObservaciones() != null){
                        $sql= "UPDATE Alumno SET Observaciones='".$alumnoNuevo->getObservaciones()."' WHERE DNI='".$alumnoNuevo->getDni()."';";
                        $this->mysqli->query($sql);
                }

                return "modificacion exito";
            }

    }

    //Lista en un select todos los alumnos para borrar.
    function listarAlumnosBorrar(){

        $db = conectarBD();

        $sql = "SELECT DNI FROM Alumno WHERE Borrado='0' ORDER BY DNI;";
        $resultado = $db->query($sql);
        $db->close();
        if ($resultado->num_rows > 0){
            while($row = $resultado->fetch_array()) {
                echo "<option value='".$row['DNI']."'>".$row['DNI']."</option><tr>";
            }
        }
        $db->close();
    }

    //Lista en un select todos los alumnos para modificar. 
    function listarAlumnosModificar(){

        $db = conectarBD();
        $sql = "SELECT DNI FROM Alumno WHERE Borrado='0' ORDER BY DNI;";
        $resultado = $db->query($sql);
        $db->close();
        
        if ($resultado->num_rows > 0){
                while($row = $resultado->fetch_array()) {
                        echo "<input type='radio' name='alumno' value='".$row['DNI']."'>".$row['DNI']."</option><tr>";
                        echo "<br>";
                }
        }
    }

    //Lista todos los alumnos en una tabla.
    function verAlumnos(){

        $db = conectarBD();
        $sql = "SELECT * FROM Alumno WHERE Borrado='0' ORDER BY Apellidos;";
        $resultado = $db->query($sql);
        $db->close();
        
        if ($resultado->num_rows > 0){
                while($row = $resultado->fetch_array()) {
                         echo "<tr> <td>".$row['DNI']."</td> <td>".utf8_decode($row['Apellidos'])."</td> <td>".utf8_decode($row['Nombre'])."</td> <td>".utf8_decode($row['Direccion'])."</td> <td>".$row['Email']."</td> <td>".$row['Fecha_Nacimiento']."</td> <td>".utf8_decode($row['Profesion'])."</td> <td>".utf8_decode($row['Observaciones'])."</td> </tr>";
                }
        }
    }

    //Recupera los datos de un alumno en un array.
    function mostrarAlumno($alumn){

        $db = conectarBD();
        $sql = "SELECT * FROM Alumno WHERE DNI='".$alumn."' AND Borrado='0';";
        $resultado = $db->query($sql);
        
        if ($resultado->num_rows == 1){
                $row = $resultado->fetch_assoc();
                return $row;
        }
    }

    //Muestra los datos de un alumno concreto pasado por parámetro en formato tabla.
    function consultarAlumno($dniB){

        $db = conectarBD();
        $sql = "SELECT * FROM Alumno WHERE DNI='".$dniB."' AND Borrado='0' ORDER BY Apellidos;";
        $resultado = $db->query($sql);
        
        if ($resultado->num_rows > 0){
                $row = $resultado->fetch_array();
                    echo "<tr> <td>".$row['DNI']."</td> <td>".utf8_decode($row['Apellidos'])."</td> <td>".utf8_decode($row['Nombre'])."</td> <td>".utf8_decode($row['Direccion'])."</td> <td>".$row['Email']."</td> <td>".$row['Fecha_Nacimiento']."</td> <td>".utf8_decode($row['Profesion'])."</td> <td>".utf8_decode($row['Observaciones'])."</td> </tr>";
            }
    }

}else echo "Permiso denegado.";
?>