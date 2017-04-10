<?php
//Comprueba si el usuario inició sesión y si es admin o secretario antes de cargar la página.
if(isset($_SESSION['grupo']) && (strcmp($_SESSION['grupo'],"Admin") == 0 || strcmp($_SESSION['grupo'],"Secretario") == 0) ){ 

    //Include de la función de conexión a la base de datos.
    include_once('conectarBD.php');

    class notificacion{

            var $asunto;
            var $cuerpo;
            
            var $mysqli;

            //Constructor.
            function __construct($asunto=null,$cuerpo=null){
                
                $this->asunto = $asunto;
                $this->cuerpo = $cuerpo;
            }
            //Getters.
            function getAsunto(){
                    return $this->$asunto;
            }
            function getCuerpo(){
                    return $this->cuerpo;
            }        

            //Envía la notificación con el correo 'moovettIU@gmail.com'
            function enviar(){

                //Crear una instancia de PHPMailer
                $mail = new PHPMailer();
                //Definir que vamos a usar SMTP
                $mail->IsSMTP();
                //Esto es para activar el modo depuración en producción
                $mail->SMTPDebug  = 0;
                //Ahora definimos gmail como servidor que aloja nuestro SMTP
                $mail->Host       = 'smtp.gmail.com';
                //El puerto será el 587 ya que usamos encriptación TLS
                $mail->Port       = 587;
                //Definmos la seguridad como TLS
                $mail->SMTPSecure = 'tls';
                //Tenemos que usar gmail autenticados, así que esto a TRUE
                $mail->SMTPAuth   = true;
                //Definimos la cuenta que vamos a usar. Dirección completa de la misma
                $mail->Username   = "moovettIU@gmail.com";
                //Introducimos nuestra contraseña de gmail
                $mail->Password   = "interfaz";
                //Definimos el remitente (dirección y nombre)
                $mail->SetFrom('moovettIU@gmail.com', 'MOOVETT');
                //Definimos el tema del email
                $mail->Subject = $this->asunto;
                //Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.
                $mail->MsgHTML(utf8_decode($this->cuerpo));
                //Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
                $mail->AltBody = 'This is a plain-text message body';
                
                //Cogemos los correos de los alumnos
                $correos = obtenerCorreos();
                
                foreach ($correos as &$address) {

                    //indico destinatario
                    $mail->addAddress($address);              
                }
                
                //Enviamos el correo                 
                if(!$mail->Send()) {
                    
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }
                
                
                
                return "notificacion exito";
                
            }
        
    }
    
    // Obtiene los correos de todos los alumnos
    function obtenerCorreos(){

                $db = conectarBD();

                $sql = "SELECT Email FROM Alumno WHERE Borrado='0';";
                $resultado = $db->query($sql);
                $array = array();
                if ($resultado->num_rows > 0){
                        while($row = $resultado->fetch_array()) {
                            array_push($array, $row['Email']);
                        }
                }
                return $array;
    }
    
    
    
}else echo "Permiso denegado.";
?>