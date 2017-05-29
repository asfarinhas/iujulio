<?php
    echo "Iniciando creación de vistas...";//Mostramos mensaje para saber cuando empieza
function conectarBD(){//Creamos una funcion para conectarnos a la BD

    $bd = new mysqli("localhost", "iu2016", "iu2016", "IU2016");
    if (mysqli_connect_errno()){
        echo "Fallo al conectar MySQL: " . $mysqli->connect_error();
    }
    return $bd;
}


function listarTablas() //Creamos una funcion para que nos devuelva todas las tablas de la BD
{
    $mysqli2 = conectarBD();
    $sql = 'show full tables from IU2016';
    if (!($resultado = $mysqli2->query($sql))) {
        return 'Error en la consulta sobre la base de datos';
    } else {
        $tables = array();
        while($tabla = $resultado->fetch_array(MYSQLI_ASSOC)){
            array_push($tables,$tabla['Tables_in_IU2016']);
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
    $file=fopen("/var/www/html/iujulio/Views/" . strtoupper($tabla) . "_ADD_Vista.php","w+");
    $atributos = listarAtributos($tabla);//Cogemos los atributos de la tabla y los pasamos a un array
    var_dump($atributos);
   // exit;
    $str='<?php class '. strtoupper($tabla) . '_ADD { 
          function __construct(){ 
                $this->render();
                }
          function render(){ 
    include \'../Locates/Strings_\'.$_SESSION[\'IDIOMA\'].\'.php\';           
        include \'../Functions/' . strtoupper($tabla) . '_DefForm.php\';
        $lista = array(';
        $i=0;
       foreach($atributos as $valor){
           if($i==0){
               $str .= '\'' . $valor->name.'\'';
           }else{
               $str .= ',\'' . $valor->name. '\'';
           }
           $i++;
        }
    $str .= ');
    ?>
    <title>Añadir></title>
    	</h2>
		</p>
		<p>
			<h1>
			<span class="form-title">
			<?php echo	$strings[\'Insertar Actividad\'] ?><br>
			</h1>
            <h3>
					<form id="form" action="../Controllers/' . strtoupper($tabla) . '_Controller.php?"
					 method="POST" enctype="multipart/form-data" >
                     <ul class="form-style-1">
                    <?php
                    createForm($lista,$DefForm,$strings,\'\',true,false);
?>
                    <input type="submit" name="accion" onclick="return valida_envia4()" value="Continuar">
				    </form>
				    <?php
				echo \'<a class="form-link" href=\"../Controllers/' . strtoupper($tabla) . '_Controller.php'.'\\\'>\' . $strings[\'Volver\'] . " </a>";
				?>
				<br>

			</h3>
		</p>

	</div>

<?php
} //fin metodo render
}
?>';
    fwrite($file,$str);
    
    crearArrayFormulario($tabla,$atributos);//Llamamos a la funcion crear el array del formulario, y le pasamos la tabla y los atributos
}

function crearArrayFormulario($tabla, $atributos){
    $file = fopen("/var/www/html/iujulio/Functions/" . strtoupper($tabla) . "_DefForm.php","w+");
        $str = '
        <?php
        //Formulario para cada vista.
        $Form = array(' ;
        $i=0;
            foreach ($atributos as $clave) {
                if($i==0) {
                    $str .='
                   '.$i . '=>array(
                   \'name\' => \'' . $clave->name . '\',
                   \'type\' => \'' . calcularType($clave->type) . '\',';
                  /* \'value\' => \'' . $clave->value . '\',
                   \'min\' => \'' . $clave->min . '\',
                   \'max\' => \'' . $clave->max . '\',
                   \'size\' => \'' . $clave->size . '\',
                   \'required\' => \'' . $clave->required . '\',
                   \'pattern\' => \'' . $clave->pattern . '\',
                   \'validation\' => \'' . $clave->validation . '\',
                   \'readonly\' => \'' . $clave->readonly . '\'  */
                   $str.= '
                   )';
                }else{
                    $str .=',
                   '.$i.'=>array(
                   \'name\' => \'' . $clave->name . '\',
                   \'type\' => \'' . calcularType($clave->type) . '\',';
                   /* \'value\' => \'' . $clave->value . '\',
                   \'min\' => \'' . $clave->min . '\',
                   \'max\' => \'' . $clave->max . '\',
                   \'size\' => \'' . $clave->size . '\',
                   \'required\' => \'' . $clave->required . '\',
                   \'pattern\' => \'' . $clave->pattern . '\',
                   \'validation\' => \'' . $clave->validation . '\',
                   \'readonly\' => \'' . $clave->readonly . '\'*/
                   $str.= '
                   )';
                }
                   $i++;
                }
                $str.='
                );';


            fwrite($file,$str);



}

function calcularType($tipo){
    switch ($tipo){
        case 16:
            $toret='BIT';
            break;
        case 1:
            $toret='BOOL';
            break;
        case 253:
            $toret='VARCHAR';
            break;
        case 246:
            $toret='DECIMAL';
            break;
        case 3:
            $toret='INTEGER';
            break;
        case 10:
            $toret='DATE';
            break;
        case 252:
            $toret='TEXT';
            break;
        default:
            echo "Rodeiro no nos putes MÁS anda!!";
    }
    return $toret;
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


function createForm($listFields, $fieldsDef, $strings, $values, $required, $noedit) {

    foreach ($listFields as $field) { //miro todos los campos que me piden en su orden
        for ($i = 0; $i < count($fieldsDef); $i++) { //recorro todos los campos de la definición de formulario para encontrarlo
            //echo $field . ':' . $fieldsDef[$i]['required'] . '<br>';
            if ($field == $fieldsDef[$i]['name'] || $field == $fieldsDef[$i]['value']) { //si es el que busco
                switch ($fieldsDef[$i]['type']) {
                    case 'text':
                        if (isset($fieldsDef[$i]['texto'])) {
                            $str = "<li>" . $strings[$fieldsDef[$i]['texto']];
                        } else {
                            $str = "<li><label>" . $strings[$fieldsDef[$i]['name']] . "</label>";
                        }
                        $str .= "<input type = '" . $fieldsDef[$i]['type'] . "'";
                        $str .= " name = '" . $fieldsDef[$i]['name'] . "'";
                        $str .= " id = '" . $fieldsDef[$i]['name'] . "'";
                        $str .= " size = '" . $fieldsDef[$i]['size'] . "'";
                        if (isset($values[$fieldsDef[$i]['name']])) {
                            $str .= " value = '" . $values[$fieldsDef[$i]['name']] . "'";
                        } else {
                            $str .= " value = '" . $fieldsDef[$i]['value'] . "'";
                        }
                        if ($fieldsDef[$i]['pattern'] <> '') {
                            $str .= " pattern = '" . $fieldsDef[$i]['pattern'] . "'";
                        }
                        if ($fieldsDef[$i]['validation'] <> '') {
                            $str .= " " . $fieldsDef[$i]['validation'];
                        }
                        if (is_bool($required)) {
                            if (!$required) {
                                $str .= ' ';
                            } else {
                                $str .= ' required ';
                            }
                        } else {
                            if (!isset($required[$field])) {
                                $str .= 'required';
                            } else {
                                $str .= '';
                            }
                        }
                        if (is_bool($noedit)) {
                            if ($noedit) {
                                $str .= ' readonly ';
                            }
                        } else {
                            if (isset($noedit[$field])) {
                                if ($noedit[$field]) {
                                    $str .= ' readonly ';
                                }
                            }
                        }
                        $str .= " ></li>";
                        echo $str;
                        break;
                    case 'date':
                        $str = "<li><label>" . $strings[$fieldsDef[$i]['name']] . "</label>";
                        $str .= "<input type = '" . $fieldsDef[$i]['type'] . "'";
                        $str .= " name = '" . $fieldsDef[$i]['name'] . "'";
                        $str .= " min = '" . $fieldsDef[$i]['min'] . "'";
                        $str .= " max = '" . $fieldsDef[$i]['max'] . "'";
                        if (isset($values[$fieldsDef[$i]['name']])) {
                            $str .= " value = '" . ($values[$fieldsDef[$i]['name']]) . "'";
                        } else {
                            $str .= " value = '" . $fieldsDef[$i]['value'] . "'";
                        }
                        if ($fieldsDef[$i]['pattern'] <> '') {
                            $str .= " pattern = '" . $fieldsDef[$i]['pattern'] . "'";
                        }
                        if ($fieldsDef[$i]['validation'] <> '') {
                            $str .= " " . $fieldsDef[$i]['validation'];
                        }
                        if (is_bool($required)) {
                            if (!$required) {
                                $str .= ' ';
                            } else {
                                $str .= ' required ';
                            }
                        } else {
                            if (isset($required[$field])) {
                                if (!$required[$field]) {
                                    $str .= ' ';
                                } else {
                                    $str -= ' required ';
                                }
                            }
                        }
                        if (is_bool($noedit)) {
                            if ($noedit) {
                                $str .= ' readonly ';
                            }
                        } else {
                            if (isset($noedit[$field])) {
                                if ($noedit[$field]) {
                                    $str .= ' readonly ';
                                }
                            }
                        }
                if($fieldsDef[$i]['name']!=='BLOQUE_FECHA'){
                        $str .= "required" . " ></li>";
                }else{
             $str.=" ></li>";
                }
                        echo $str;
                        break;
                    case 'email':
                        $str = "<li><label>" . $strings[$fieldsDef[$i]['name']] . "</label>";
                        $str .= "<input type = '" . $fieldsDef[$i]['type'] . "'";
                        $str .= " name = '" . $fieldsDef[$i]['name'] . "'";
                        $str .= " size = '" . $fieldsDef[$i]['size'] . "'";
                        if (isset($values[$fieldsDef[$i]['name']])) {
                            $str .= " value = '" . $values[$fieldsDef[$i]['name']] . "'";
                        } else {
                            $str .= " value = '" . $fieldsDef[$i]['value'] . "'";
                        }
                        if ($fieldsDef[$i]['pattern'] <> '') {
                            $str .= " pattern = '" . $fieldsDef[$i]['pattern'] . "'";
                        }
                        if ($fieldsDef[$i]['validation'] <> '') {
                            $str .= " " . $fieldsDef[$i]['validation'];
                        }
                        if (is_bool($required)) {
                            if (!$required) {
                                $str .= ' ';
                            } else {
                                $str .= ' required ';
                            }
                        } else {
                            if (isset($required[$field])) {
                                if (!$required[$field]) {
                                    $str .= ' ';
                                } else {
                                    $str -= ' required ';
                                }
                            }
                        }
                        if (is_bool($noedit)) {
                            if ($noedit) {
                                $str .= ' readonly ';
                            }
                        } else {
                            if (isset($noedit[$field])) {
                                if ($noedit[$field]) {
                                    $str .= ' readonly ';
                                }
                            }
                        }
                        $str .= "required" . " ></li>";
                        echo $str;
                        break;
                    case 'time':
                        $str = "<li><label>" . $strings[$fieldsDef[$i]['name']] . "</label>";
                        $str .= "<input type = '" . $fieldsDef[$i]['type'] . "'";
                        $str .= " name = '" . $fieldsDef[$i]['name'] . "'";
                        if (isset($values[$fieldsDef[$i]['name']])) {
                            $str .= " value = '" . ($values[$fieldsDef[$i]['name']]) . "'";
                        } else {
                            $str .= " value = '" . $fieldsDef[$i]['value'] . "'";
                        }
                        if ($fieldsDef[$i]['pattern'] <> '') {
                            $str .= " pattern = '" . $fieldsDef[$i]['pattern'] . "'";
                        }
                        if ($fieldsDef[$i]['validation'] <> '') {
                            $str .= " " . $fieldsDef[$i]['validation'];
                        }
                        if (is_bool($required)) {
                            if (!$required) {
                                $str .= ' ';
                            } else {
                                $str .= ' required ';
                            }
                        } else {
                            if (isset($required[$field])) {
                                if (!$required[$field]) {
                                    $str .= ' ';
                                } else {
                                    $str -= ' required ';
                                }
                            }
                        }
                        if (is_bool($noedit)) {
                            if ($noedit) {
                                $str .= ' readonly ';
                            }
                        } else {
                            if (isset($noedit[$field])) {
                                if ($noedit[$field]) {
                                    $str .= ' readonly ';
                                }
                            }
                        }
                        $str .= "required" . " ></li>";
                        echo $str;
                        break;
                    case 'url':

                        if (isset($values[$fieldsDef[$i]['name']])) {
                            $str = "<li><label>" . $strings[$fieldsDef[$i]['name']] . "</label>";
                            $str .= "<a target='_blank' href='" . $values[$fieldsDef[$i]['name']] . "'>Ver</a>";
                            $str .= " <br>\n";
                            echo $str;
                        }
                        break;
                    case 'tel':
                        break;
                    case 'password':
                        $str = "<li><label>" . $strings[$fieldsDef[$i]['name']] . "</label>";
                        $str .= "<input type = '" . $fieldsDef[$i]['type'] . "'";
                        $str .= " name = '" . $fieldsDef[$i]['name'] . "'";
                        $str .= " size = '" . $fieldsDef[$i]['size'] . "'";
                        if (isset($values[$fieldsDef[$i]['name']])) {
                            $str .= " value = '" . $values[$fieldsDef[$i]['name']] . "'";
                        } else {
                            $str .= " value = '" . $fieldsDef[$i]['value'] . "'";
                        }
                        if ($fieldsDef[$i]['pattern'] <> '') {
                            $str .= " pattern = '" . $fieldsDef[$i]['pattern'] . "'";
                        }
                        if ($fieldsDef[$i]['validation'] <> '') {
                            $str .= " " . $fieldsDef[$i]['validation'];
                        }
                        if (is_bool($required)) {
                            if (!$required) {
                                $str .= ' ';
                            } else {
                                $str .= ' required ';
                            }
                        } else {
                            if (isset($required[$field])) {
                                if (!$required[$field]) {
                                    $str .= ' ';
                                } else {
                                    $str -= ' required ';
                                }
                            }
                        }
                        if (is_bool($noedit)) {
                            if ($noedit) {
                                $str .= ' readonly ';
                            }
                        } else {
                            if (isset($noedit[$field])) {
                                if ($noedit[$field]) {
                                    $str .= ' readonly ';
                                }
                            }
                        }
                        $str .= "required" . " ></li>";
                        echo $str;
                        break;
                    case 'number':
                        $str = "<li><label>" . $strings[$fieldsDef[$i]['name']] . "</label>";
                        $str .= "<input type = '" . $fieldsDef[$i]['type'] . "'";
                        $str .= " name = '" . $fieldsDef[$i]['name'] . "'";
                        $str .= " min = '" . $fieldsDef[$i]['min'] . "'";
                        $str .= " max = '" . $fieldsDef[$i]['max'] . "'";
                        if (isset($values[$fieldsDef[$i]['name']])) {
                            $str .= " value = '" . $values[$fieldsDef[$i]['name']] . "'";
                        } else {
                            $str .= " value = '" . $fieldsDef[$i]['value'] . "'";
                        }
                        if ($fieldsDef[$i]['pattern'] <> '') {
                            $str .= " pattern = '" . $fieldsDef[$i]['pattern'] . "'";
                        }
                        if ($fieldsDef[$i]['validation'] <> '') {
                            $str .= " " . $fieldsDef[$i]['validation'];
                        }
                        if (is_bool($required)) {
                            if (!$required) {
                                $str .= ' ';
                            } else {
                                $str .= ' required ';
                            }
                        } else {
                            if (isset($required[$field])) {
                                if (!$required[$field]) {
                                    $str .= ' ';
                                } else {
                                    $str -= ' required ';
                                }
                            }
                        }
                        if (is_bool($noedit)) {
                            if ($noedit) {
                                $str .= ' readonly ';
                            }
                        } else {
                            if (isset($noedit[$field])) {
                                if ($noedit[$field]) {
                                    $str .= ' readonly ';
                                }
                            }
                        }
                        $str .= " ></li>";
                        echo $str;
                        break;
                    case 'checkbox':

                        if (isset($strings[$fieldsDef[$i]['value']])) {
                            $str = "<li><label>" . $strings[$fieldsDef[$i]['value']] . "</label>";
                        } else {
                            $str = "<li><label>" . $fieldsDef[$i]['value'] . "</label>";
                        }
                        $str .= "<input type = '" . $fieldsDef[$i]['type'] . "'";
                        $str .= " name = '" . $fieldsDef[$i]['name'] . "'";
                        $str .= " size = '" . $fieldsDef[$i]['size'] . "'";
                        if (isset($values[$fieldsDef[$i]['name']])) {
                            $str .= " value = '" . $values[$fieldsDef[$i]['name']] . "'";
                        } else {
                            $str .= " value = '" . $fieldsDef[$i]['value'] . "'";
                        }
                        if ($fieldsDef[$i]['pattern'] <> '') {
                            $str .= " pattern = '" . $fieldsDef[$i]['pattern'] . "'";
                        }
                        if ($fieldsDef[$i]['validation'] <> '') {
                            $str .= " " . $fieldsDef[$i]['validation'];
                        }
                        if (is_bool($noedit)) {
                            if ($noedit) {
                                $str .= ' readonly ';
                            }
                        } else {
                            if (isset($noedit[$field])) {
                                if ($noedit[$field]) {
                                    $str .= ' readonly ';
                                }
                            }
                        }
                        $str .= " ></li>";
                        echo $str;
                        break;
                    case 'radio':
                        break;
                    case 'file':
                        $str = "<li><label>" . $strings[$fieldsDef[$i]['name']] . "</label>";
                        $str .= "<input type = '" . $fieldsDef[$i]['type'] . "'";
                        $str .= " name = '" . $fieldsDef[$i]['name'] . "'";
                        if (isset($values[$fieldsDef[$i]['name']])) {
                            $str .= " value = '" . $values[$fieldsDef[$i]['name']] . "'";
                        } else {
                            $str .= " value = '" . $fieldsDef[$i]['value'] . "'";
                        }
                        if ($fieldsDef[$i]['pattern'] <> '') {
                            $str .= " pattern = '" . $fieldsDef[$i]['pattern'] . "'";
                        }
                        if ($fieldsDef[$i]['validation'] <> '') {
                            $str .= " " . $fieldsDef[$i]['validation'];
                        }
                        if (is_bool($required)) {
                            if (!$required) {
                                $str .= ' ';
                            } else {
                                $str .= ' required ';
                            }
                        } else {
                            if (isset($required[$field])) {
                                if (!$required[$field]) {
                                    $str .= ' ';
                                } else {
                                    $str -= ' required ';
                                }
                            }
                        }
                        if (is_bool($noedit)) {
                            if ($noedit) {
                                $str .= ' readonly ';
                            }
                        } else {
                            if (isset($noedit[$field])) {
                                if ($noedit[$field]) {
                                    $str .= ' readonly ';
                                }
                            }
                        }
                        $str .= " ></li>";
                        echo $str;
                        break;
                        ;
                    case 'select':
                        $str = "<li><label>" . $strings[$fieldsDef[$i]['name']] . "</label>" . "<select name='" . $fieldsDef[$i]['name'] . "'";
                        if ($noedit || $noedit[$field]) {
                            $str .= ' readonly ';
                        }
                        if ($fieldsDef[$i]['multiple'] == 'true') {
                            $str = $str . " multiple ";
                        }
                        $str = $str . " >";
                        foreach ($fieldsDef[$i]['options'] as $value) {
                            $str1 = "<option value = '" . $value . "'";
                            if (isset($values[$fieldsDef[$i]['name']])) {
                                if ($values[$fieldsDef[$i]['name']] == $value) {
                                    $str1 .= " selected ";
                                }
                            }
                            $str1 .= ">" . $value . "</option>";
                            $str = $str . $str1;
                        }
                        $str = $str . "</select></li>";
                        echo $str;
                        break;
                    case 'textarea':
                        break;
                    default:
                }
            }
        }
    }
}
?>
