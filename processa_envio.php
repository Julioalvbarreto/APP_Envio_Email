<?php
    
    require "./bibliotecas/phpmailer/Exception.php";
    require "./bibliotecas/phpmailer/OAuth.php";
    require "./bibliotecas/phpmailer/PHPMailer.php";
    require "./bibliotecas/phpmailer/POP3.php";
    require "./bibliotecas/phpmailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    
    // echo "<pre>";
    // print_r($_POST);
    // echo "<pre>";

    class Mensagem {
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = ['codigo_status' => null, 'descricao_stauts' => ''];

        public function __get($atributo)
        {
            return $this->$atributo;
        }

        public function __set($atributo, $valor)
        {
            $this->$atributo = $valor;
        }

        public function mensagemValida()
        {
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
                return false;
            }
            else{
                return true;
            }
        }
    }
    

    $mensagem = new Mensagem();

    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);


    // echo "<pre>";
    // print_r($mensagem);
    // echo "<pre>";
    
    if(!$mensagem->mensagemValida())
    {
        echo 'Dados preenchidos invalidos!';
        header('Location: index.php');
    } 

    $mail = new PHPMailer(true);

    try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER; //MOSTRA LOG DE DEBUG                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'seuemail@dominio.com';                     //SMTP username
    $mail->Password   = 'password';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('seuemail@dominio.com', 'APP Envio');
    $mail->addAddress($mensagem->__get('para'));     //Add a recipient
    //$mail->addAddress('ellen@example.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com'); email de copia
    //$mail->addBCC('bcc@example.com'); email de copia oculta

    //Attachments ANEXOS
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    //$mail->AltBody = 'Texto sem tags html';

    $mail->send();

    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'E-mail enviado!';
} catch (Exception $e) {
    $mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = "E-mail não enviado!. Error: {$mail->ErrorInfo}";
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>App Envio</title>
</head>
<body>
    
    <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <?php 
            if($mensagem->status['codigo_status'] == 1) { ?>
                <div class="container">
                    <h1 class="display-4 text-success">Sucesso</h1>
                    <p><?= $mensagem->status['descricao_status']?></p>
                    <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                </div>
            <?php } ?>

            <?php 
            if($mensagem->status['codigo_status'] == 2) { ?>
                <div class="container">
                    <h1 class="display-4 text-danger">Falha no envio!</h1>
                    <p><?= $mensagem->status['descricao_status']?></p>
                    <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                </div>
            <?php } ?>

        </div>
    </div>




</body>
</html>