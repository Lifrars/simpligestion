<?php 
    // Inicializamos variables de sesión y cadena de con
    session_start();
    include "../assets/config.php";
    $emailu=$_POST['emailu'];
    // Destinatarios
    $stringDestinatariosClientes = $emailu;
    
    // Serializo los destinatarios
    $serializadoStringDestinatariosClientes = explode(';', $stringDestinatariosClientes);

    // Exporto Librerias PhpMailer
    include('config-smtp.php');
    include('../assets/phpMailer/class.phpmailer.php');
    include('../assets/phpMailer/class.smtp.php');
    $mail = new PHPMailer(true);
    try {
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        // $mail->SMTPSecure = "ssl";
        $mail->SMTPSecure = "tsl";
        
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        // VALORES A MODIFICAR //
        $mail->Host = $smtpHost;
        $mail->Port = $smtpPort; 
        $mail->Username = $smtpUsuario; 
        $mail->Password = $smtpClave;
        $mail->IsHTML(true); 
        $mail->CharSet = "utf-8";


        $DesdeNumero = 0;
        $HastaNumero = 9;
        
        $numeroAleatorio = rand($DesdeNumero, $HastaNumero);
        
        $n1 = rand($DesdeNumero, $HastaNumero);
        $n2 = rand($DesdeNumero, $HastaNumero);
        $n3 = rand($DesdeNumero, $HastaNumero);
        $n4 = rand($DesdeNumero, $HastaNumero);
        $n5 = rand($DesdeNumero, $HastaNumero);
        $n6 = rand($DesdeNumero, $HastaNumero);
        $n = $n1 . $n2 . $n3 . $n4 . $n5 . $n6;
            
        $nombre="";
        $queryup ="SELECT email,full_name FROM usertbl where email='$emailu'";
        $result_task=mysqli_query($con,$queryup);
        
        while($row=mysqli_fetch_assoc($result_task)) {
            $arrayData[] = $row;
            if($row['email']!=""){
                $nombre = $row['full_name'];
                $queryup ="UPDATE usertbl 
                SET password = '". md5($n) ."' WHERE email='$emailu'";
                $result_taskup=mysqli_query($con,$queryup);
                
                for($i = 0 ; $i < COUNT($serializadoStringDestinatariosClientes) ; $i++) {
                    $mail->Subject = "Código de Acceso Temporal -> 📆App-Reservas"; // Este es el titulo del email.
                    // Cuerpo del correo
                    $mail->Body = "
                        <html> 
                            <body>                                    
                                <h3>🔐 Tu nuevo código de acesso es:</h3>
                                <h3>". $n ."</h3>
                                <h4>Felices Reservas.</h4>
                                <p>🖥Equipo Kubico TI</p>
                                <img  src='cid:my-attach' width='230'>
                            </body> 
                        </html>                            
                        <br />"; //
                    // $mail->setFrom('+CREDIMÁS - Notificación Electronica');
                    $mail->setFrom($smtpUsuario, 'Soporte Kubico TI');
                    // Verifico si llega cadena de string con nombre algun archivo a enviar
                
                    // Anexo Documentos Externos
                        $mail->AddEmbeddedImage("../imgs/credimasti.png", "my-attach", "../imgs/credimasti.png");
                    // Anexo archivo a enviar en el email
                    // $mail->AddAttachment('Certificación NewYork.png'); // attachment
                    // $mail->AddAttachment('Certificaciones que nos respaldan.png'); // attachment
                    // $mail->AddAttachment('Nuestros servicios.png'); // attachment
                    // $mail->AddAttachment('Quienes somos.png'); // attachment
                    // Anexo imagen de firma
                    // $mail->Body .= '<img alt="PHPMailer" src="cid:my-attach">';
                    
                    // Limpio destinatarios
                    $mail->ClearAddresses();
    
                    // Email de destinatarios
                    $mail->AddAddress($serializadoStringDestinatariosClientes[$i]);
    
                    // Envia email
                    $mail->Send();
                }
            }else{
                
            }
        }
        
        // Envia email
        // $mail->Send();
        if($nombre!=''){

            echo json_encode(array("respuesta" => "ok", "nombre" => $nombre));
            die();

        }else{

            echo json_encode(array("respuesta" => "no", "nombre" => ""));
            die();
        }
        // JSON de respuesta
        // $arrayDatos = array('sucess' => true, 'msg' => 'El email fue enviado exitosamente al cliente.', 'msgEliminacionArchivo' => $msgEliminacionFichero);
    }catch (phpmailerException $e) {
        // JSON de respuesta
        // $arrayDatos = array('sucess' => 'false', 'msg' => $e->errorMessage() );        
        echo json_encode(array("respuesta" => "no", "nombre" => ""));
        die();
    } catch (Exception $e) {
        // JSON de respuesta
        // $arrayDatos = array('sucess' => 'false', 'msg' => $e->getMessage() );
        echo json_encode(array("respuesta" => "no", "nombre" => ""));
        die();
    }
?>