<?php

class actividad
{

	var $ACTIVIDAD_ID;
	var $ACTIVIDAD_NOMBRE;
	var $ACTIVIDAD_PRECIO;
	var $ACTIVIDAD_DESCRIPCION;
	var $CATEGORIA_ID;
	var $ACTIVO;
    var $ACTIVIDAD_LUGAR;
    var $ACTIVIDAD_PROFESORES;
    var $ACTIVIDAD_BLOQUE;
    var $ACTIVIDAD_HORARIO;
    var $ACTIVIDAD_DIA;



	function __construct($ACTIVIDAD_NOMBRE, $ACTIVIDAD_PRECIO, $ACTIVIDAD_DESCRIPCION, $CATEGORIA_ID,$ACTIVO, $ACTIVIDAD_LUGAR, $ACTIVIDAD_PROFESORES, $ACTIVIDAD_BLOQUE, $ACTIVIDAD_HORARIO, $ACTIVIDAD_DIA)
	{include '../Locates/Strings_'.$_SESSION['IDIOMA'].'.php';
	    $semana=array($strings['Domingo'],$strings['Lunes'],$strings['Martes'],$strings['Miercoles'],$strings['Jueves'],$strings['Viernes'], $strings['Sabado']);


    if (isset($ACTIVIDAD_BLOQUE)) {

        $toret=array();
        for($i=0;$i<count($ACTIVIDAD_BLOQUE);$i++) {
        $horas = explode("-", $ACTIVIDAD_BLOQUE[$i]);

            for($u=0;$u<count(consultarBloques($ACTIVIDAD_HORARIO, array_search($ACTIVIDAD_DIA, $semana), $horas[0], $horas[1]));$u++){
                array_push($toret, consultarBloques($ACTIVIDAD_HORARIO, array_search($ACTIVIDAD_DIA, $semana), $horas[0], $horas[1])[$u]);
            }

    }
        $this->ACTIVIDAD_BLOQUE=$toret;
}
        else {
            $this->ACTIVIDAD_BLOQUE=$ACTIVIDAD_BLOQUE;
        }
		$this->ACTIVIDAD_NOMBRE = $ACTIVIDAD_NOMBRE;
		$this->ACTIVIDAD_PRECIO= $ACTIVIDAD_PRECIO;
		$this->ACTIVIDAD_DESCRIPCION = $ACTIVIDAD_DESCRIPCION;
		$this->CATEGORIA_ID = $CATEGORIA_ID;
		$this->ACTIVO = $ACTIVO;
        $this->ACTIVIDAD_LUGAR=$ACTIVIDAD_LUGAR;
        $this->ACTIVIDAD_PROFESORES=$ACTIVIDAD_PROFESORES;

        $this->ACTIVIDAD_DIA=$ACTIVIDAD_DIA;
        $this->ACTIVIDAD_HORARIO=$ACTIVIDAD_HORARIO;

	}

	//Conectarse a la BD
	function ConectarBD()
	{
		$this->mysqli = new mysqli("localhost", "iu2016", "iu2016", "IU2016");
		if ($this->mysqli->connect_errno) {
			echo "Fallo al conectar a MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
		}
	}

	//Anadir una actividad
	function insert_actividad()
	{
		$this->ConectarBD();
		$sql = "SELECT * FROM ACTIVIDAD WHERE ACTIVIDAD_NOMBRE = '".$this->ACTIVIDAD_NOMBRE."'";
		$result = $this->mysqli->query($sql);
		if($result->num_rows == 1){
				return 'La actividad ya existe en la base de datos';
			}else{
					if ($result->num_rows == 0){
						$sql = "INSERT INTO ACTIVIDAD (ACTIVIDAD_NOMBRE, ACTIVIDAD_PRECIO, ACTIVIDAD_DESCRIPCION, CATEGORIA_ID,ACTIVO) VALUES ('". $this->ACTIVIDAD_NOMBRE ."','". $this->ACTIVIDAD_PRECIO ."','". $this->ACTIVIDAD_DESCRIPCION ."','". $this->CATEGORIA_ID ."','". $this->ACTIVO ."')";

                        $this->mysqli->query($sql);
                        $sql = "SELECT ACTIVIDAD_ID FROM ACTIVIDAD WHERE ACTIVIDAD_NOMBRE = '".$this->ACTIVIDAD_NOMBRE."'";
                        $result= $this->mysqli->query($sql);
                        $ID=$result->fetch_array();

                    	//crearActividad($this->ACTIVIDAD_NOMBRE);
                        for($i=0;$i<count($this->ACTIVIDAD_PROFESORES);$i++){
                            $sql="INSERT INTO EMPLEADOS_IMPARTE_ACTIVIDAD (ACTIVIDAD_ID, EMP_USER) VALUES ('".$ID['ACTIVIDAD_ID']."','".$this->ACTIVIDAD_PROFESORES[$i]."')";

                            $this->mysqli->query($sql);
                        }


                            $sql="INSERT INTO ACTIVIDAD_ALBERGA_LUGAR (ACTIVIDAD_ID, LUGAR_ID) VALUES ('".$ID['ACTIVIDAD_ID']."','".$this->ACTIVIDAD_LUGAR."')";

                            $this->mysqli->query($sql);

                        for($i=0;$i<count($this->ACTIVIDAD_BLOQUE);$i++) {
                            $sql = "INSERT INTO CALENDARIO (CALENDARIO_ACTIVIDAD,CALENDARIO_BLOQUE) VALUES ('" . $ID['ACTIVIDAD_ID'] . "','" . $this->ACTIVIDAD_BLOQUE[$i] . "')";

                            $this->mysqli->query($sql);
                        }return 'Añadida con exito';
					}
			}
	}





	//Funcion de destruccion del objeto: se ejecuta automaticamente
	function __destruct()
	{

	}

	//Consultar una actividad
	function select_actividad()
	{

		$this->ConectarBD();
		$sql = "SELECT * FROM ACTIVIDAD WHERE ACTIVIDAD_NOMBRE = '".$this->ACTIVIDAD_NOMBRE."' OR CATEGORIA_ID = '". $this->CATEGORIA_ID . "'";
        $resultado=$this->mysqli->query($sql);

		if (!($resultado = $this->mysqli->query($sql))){
            return 'Error en la consulta sobre la base de datos';
        }
        else{

            $toret=array();
            $i=0;

            while ($fila= $resultado->fetch_array()) {


                $toret[$i]=$fila;
                $i++;


            }


            return $toret;

        }


	}


	//Realiza el borrado lógico de un usuario.
	function delete_actividad(){

		$this->ConectarBD();

		$sql = "UPDATE ACTIVIDAD SET ACTIVO='1' WHERE ACTIVIDAD_NOMBRE='".$this->ACTIVIDAD_NOMBRE."';";
		if($this->mysqli->query($sql) === TRUE) {
			$sql1="SELECT ACTIVIDAD_ID FROM ACTIVIDAD WHERE ACTIVIDAD_NOMBRE = '" . $this->ACTIVIDAD_NOMBRE."';";
			$resultado = $this->mysqli->query($sql1)->fetch_array();
			$sql = "DELETE FROM CALENDARIO WHERE CALENDARIO_ACTIVIDAD	= '".$resultado['ACTIVIDAD_ID']."'";
			$this->mysqli->query($sql);
			return "La actividad ha sido borrada correctamente";
		}else
			return "La actividad no existe";
	}


	//Devuelve la información correspondiente a una actividad
	function RellenaDatos()
	{
		$this->ConectarBD();
		$sql = "select * from ACTIVIDAD where ACTIVIDAD_NOMBRE = '".$this->ACTIVIDAD_NOMBRE."'";
		if (!($resultado = $this->mysqli->query($sql))){
			return 'Error en la consulta sobre la base de datos';
		}
		else{
			$result = $resultado->fetch_array();


			return $result;
		}
	}

	//Devuelve la información correspondiente a una actividad
	function RellenaDatosCalendarioActividad()
	{
		$this->ConectarBD();
		$sql1="SELECT ACTIVIDAD_ID FROM ACTIVIDAD WHERE ACTIVIDAD_NOMBRE = '" . $this->ACTIVIDAD_NOMBRE."';";
		$resultado = $this->mysqli->query($sql1)->fetch_array();
		$sql = "select * from CALENDARIO where CALENDARIO_ACTIVIDAD = '".$resultado['ACTIVIDAD_ID']."'";
		if (!($resultado2 = $this->mysqli->query($sql))){
			return 'Error en la consulta sobre la base de datos';
		}
		else{
			$result = $resultado2->fetch_array();


			return $result;
		}
	}


	//Modificar la actividad
	function update_actividad($ACTIVIDAD_ID)
	{
		$this->ConectarBD();
		$sql = "select * from ACTIVIDAD where ACTIVIDAD_ID = '".$ACTIVIDAD_ID."'";
		$result = $this->mysqli->query($sql);
		if ($result->num_rows == 1)
		{
            $sql ="SELECT ACTIVIDAD_PRECIO FROM ACTIVIDAD WHERE ACTIVIDAD_ID=".$ACTIVIDAD_ID;
            $result = $this->mysqli->query($sql)->fetch_array();
			if($this->ACTIVO=='Activo'){
				$this->ACTIVO=0;
			}else{
				$this->ACTIVO=1;
			}
				$sql = "UPDATE ACTIVIDAD SET ACTIVIDAD_NOMBRE ='".$this->ACTIVIDAD_NOMBRE."', ACTIVIDAD_PRECIO ='".$this->ACTIVIDAD_PRECIO."',ACTIVIDAD_DESCRIPCION ='".$this->ACTIVIDAD_DESCRIPCION."',CATEGORIA_ID ='".$this->CATEGORIA_ID."',ACTIVO ='".$this->ACTIVO."' WHERE ACTIVIDAD_ID ='".$ACTIVIDAD_ID."'";
			//echo $sql;
			if (!($resultado = $this->mysqli->query($sql))){
				return "Error en la consulta sobre la base de datos";
			}
			else{
				return "La actividad se ha modificado con exito";
			}
		}
		else
			return "La actividad no existe";
	}

	//Listar todas las actividads
    function ConsultarTodo()
    {
        $this->ConectarBD();
        $sql = "SELECT ACTIVIDAD.ACTIVIDAD_NOMBRE,ACTIVIDAD.ACTIVIDAD_PRECIO,ACTIVIDAD.ACTIVIDAD_DESCRIPCION,CATEGORIA.CATEGORIA_NOMBRE,LUGAR.LUGAR_NOMBRE FROM LUGAR ,ACTIVIDAD_ALBERGA_LUGAR ,ACTIVIDAD, CATEGORIA WHERE LUGAR.LUGAR_ID =ACTIVIDAD_ALBERGA_LUGAR.LUGAR_ID AND ACTIVIDAD_ALBERGA_LUGAR.ACTIVIDAD_ID = ACTIVIDAD.ACTIVIDAD_ID AND ACTIVIDAD.CATEGORIA_ID = CATEGORIA.CATEGORIA_ID AND ACTIVIDAD.ACTIVO = '0'";

        if (!($resultado = $this->mysqli->query($sql))){
            return 'Error en la consulta sobre la base de datos';
        }
        else{

            $toret=array();
            $i=0;

            while ($fila= $resultado->fetch_array()) {


                $toret[$i]=$fila;
                $i++;

            }


            return $toret;

        }
    }



	//Listar todas las actividades inactivas
    function ConsultarBorradas()
    {
        $this->ConectarBD();
        $sql = "SELECT ACTIVIDAD.ACTIVIDAD_NOMBRE,ACTIVIDAD.ACTIVIDAD_PRECIO,ACTIVIDAD.ACTIVIDAD_DESCRIPCION,CATEGORIA.CATEGORIA_NOMBRE,LUGAR.LUGAR_NOMBRE FROM LUGAR ,ACTIVIDAD_ALBERGA_LUGAR ,ACTIVIDAD, CATEGORIA WHERE LUGAR.LUGAR_ID =ACTIVIDAD_ALBERGA_LUGAR.LUGAR_ID AND ACTIVIDAD_ALBERGA_LUGAR.ACTIVIDAD_ID = ACTIVIDAD.ACTIVIDAD_ID AND ACTIVIDAD.CATEGORIA_ID = CATEGORIA.CATEGORIA_ID AND ACTIVIDAD.ACTIVO = 1";
        if (!($resultado = $this->mysqli->query($sql))){
            return 'Error en la consulta sobre la base de datos';
        }
        else{

            $toret=array();
            $i=0;

            while ($fila= $resultado->fetch_array()) {


                $toret[$i]=$fila;
                $i++;


            }


            return $toret;

        }
    }

        function ConsultarClientesActividad(){
        $this->ConectarBD();
        $sql = "SELECT CLIENTE_ID, CLIENTE_NOMBRE, CLIENTE_APELLIDOS, CLIENTE_CORREO FROM CLIENTE WHERE CLIENTE_ID IN (SELECT CLIENTE_ID FROM CLIENTE_INSCRIPCION_ACTIVIDAD WHERE ACTIVIDAD_ID = '" . $this->ACTIVIDAD_ID . "')";
        if (!($resultado = $this->mysqli->query($sql))) {
            return 'Error en la consulta sobre la base de datos';
        } else {

            $toret = array();
            $i = 0;

            while ($fila = $resultado->fetch_array()) {


                $toret[$i] = $fila;
                $i++;
            }

        }
            return $toret;
    }
}
