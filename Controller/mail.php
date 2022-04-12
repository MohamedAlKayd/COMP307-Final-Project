#!/usr/bin/php
<?php
require '/usr/share/php/libphp-phpmailer/PHPMailerAutoload.php';
$mail = new PHPMailer(false); // Passing `true` enables exceptions

    //Server settings
    $mail->SMTPDebug = 1;//Enable verbose debug output
    $mail->isSMTP();//Set mailer to use SMTP
    $mail->Host = 'mail.cs.mcgill.ca';//Specify main and backup SMTP servers
    $mail->SMTPAuth = true;//Enable SMTP authentication
    $mail->Username = getenv('SMTP_USERNAME');//SMTP username
    $mail->Password = getenv('SMTP_PASSWORD');//SMTP password
    $mail->SMTPSecure = 'tls';//Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;//TCP port to connect to


    //Recipients
    $mail->setFrom('atia.islam@mail.mcgill.ca');
    $mail->addAddress('atia.islam@mail.mcgill.ca');//Add a recipient



    //Content
    $mail->isHTML(true);//Set email format to HTML
    $mail->Subject = 'test';

    $mail->Body    = 'this is a test';


    $mail->send();
?>
