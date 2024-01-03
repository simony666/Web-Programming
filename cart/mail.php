<?php 
    include('../_/_base.php');

    $email = $user->email;
    $subject = "Unique Receipt";
    $body = "Thank you for your order. Please find the receipt attached.";

     $m = get_mail();
     $m->addAddress($email);
     $m->Subject = $subject;
     $m->Body = $body;
     $m->isHTML(true);
    // $m->addAttachment('generatePDF.php');
     $m->send();

     temp('info', 'Email sent');
     redirect();
?>