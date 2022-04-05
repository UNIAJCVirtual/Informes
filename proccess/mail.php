<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require 'lib/PHPMailer/src/Exception.php';
	require 'lib/PHPMailer/src/PHPMailer.php';
	require 'lib/PHPMailer/src/SMTP.php';
	function send_mail($address, $subject, $body,$parm1,$parm2,$parm3,$parm4,$parm5,$parm6){

		$mail = new PHPMailer(true);    
		$mail->setLanguage('es', 'lib/PHPMailer/language/');                          // Passing `true` enables exceptions
		try {
		    //Server settings
		    // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
		    $mail->isSMTP();                                      // Set mailer to use SMTP
		    $mail->SMTPAuth = true;                               // Enable SMTP authentication
		    $mail->SMTPSecure = $parm3;                            // Enable TLS encryption, `ssl` also accepted
		    $mail->Host = $parm4;  // Specify main and backup SMTP servers
		    $mail->Port = $parm5;                                    // TCP port to connect to
		    $mail->Username = $parm1;                 // SMTP username
		    $mail->Password = $parm2;                           // SMTP password

		    //Recipients
		    $mail->setFrom($parm6);
			$mail->addAddress($address);
		    // $mail->addAddress('ellen@example.com');               // Name is optional
		    // $mail->addReplyTo('info@example.com', 'Information');
		    // $mail->addCC('cc@example.com');
		    // $mail->addBCC('bcc@example.com');

		    //Attachments
		    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $subject;
		    $mail->Body    = $body;
		    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		    $mail->send();
		    return 'Message has been sent';
		} catch (Exception $e) {
		    return 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
		}
	}

	if (isset($_POST["mails"])) {
		$teachers = explode(",", $_POST["mails"]);
		$percentage = explode(",", $_POST["percentage"]);
		$courses = explode(",", $_POST["course"]);
		if (count($teachers) > 0) {
			//Se lee archivo de configuracion de correo de envio
			$archivo = 'conf';
			$abrir = fopen($archivo,'r+');
			$content = fread($abrir,filesize($archivo));
			fclose($abrir);
			 
			// Separar linea por linea
			$content = explode("\n",$content);
			// var_dump($content);
			if ($_POST["maximum_percentage"] == "" AND $_POST["minimum_percentage"] == "") {
				for ($i=0; $i < count($teachers); $i++){ 
	    			if ($percentage[$i] == "Sin Datos"){
	    				send_mail($teachers[$i] ,$_POST["subject"], "Curso:".$courses[$i]."<br>".$_POST["body"], $content[0], $content[1], $content[2], $content[3], $content[4] ,$content[5]);
	    			}

	    		}
    		}else{
    			for ($i=0; $i < count($teachers); $i++){ 
    				if ($percentage[$i] >= $_POST["minimum_percentage"] AND $percentage[$i] <= $_POST["maximum_percentage"] ) {
	    				send_mail($teachers[$i],$_POST["subject"],"Curso:".$courses[$i]."<br>".$_POST["body"], $content[0],$content[1],$content[2],$content[3],$content[4],$content[5]);
    				}
	    		}
    		}		
			echo "<script languaje='javascript' type='text/javascript'>alert('mensajes enviados')</script>";
		}else{
			echo "<script languaje='javascript' type='text/javascript'>alert('no hay mensajes para enviar')</script>";
		}
	}
	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
?>