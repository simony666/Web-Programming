<?php 
    include('../../_base.php');
    // fetch record from database
    // if(is_post()){
        $order_id = req("order_id");
        $user_id = $user->id;

        // order details 
        $stm = $db->prepare(
            "SELECT oi.*,o.*,
            DATE_FORMAT(o.order_date, '%d-%m-%Y') AS order_date
            FROM order_items oi, orders o 
            WHERE o.order_id = oi.order_id 
            AND o.order_id = ?;
        ");

        $stm->execute([$order_id]);
        $order_details = $stm->fetchAll();


        // get shipping address
        $stm = $db->prepare(
            "SELECT s.*, u.name
            FROM shipping_address AS s, user AS u
            WHERE s.user_id = u.id 
            AND s.order_id = ?
        ");

        $stm->execute([$order_id]);
        $user_details = $stm->fetch();

        $stm->execute([$order_id]); 
        $addr = $stm->fetchAll();
        //$address = $addr->address.", ".$addr->postal." ".$_states[$addr->state];

        $user_name = $user_details->name;

    // }else{
        //  redirect("../order_history.php");
    // }
    

    // generate PDF
    require('../../lib/fpdf/fpdf.php');

    $pdf = new FPDF('P', 'mm',"A4");

    $pdf->AddPage();

    
    //-- Title --//
    $pdf->SetFont('Arial','B',20);
    // Cell(width, height, text, cell border, create new line, text-align);
    $pdf->Cell(71,10,'',0,0);
    $pdf->Cell(59,5,'RECEIPT',0,0);
    $pdf->Cell(59,10,'',0,1);


    $pdf->SetFont('Arial','B',10);
    //-- Order Details (Heading) --//
    $pdf->Cell(71,5,'Details:',0,0);
    $pdf->Cell(59,5,'',0,0);
    $pdf->Cell(59,5,'Address:',0,1);

    $pdf->SetFont('Arial','',10);
    
    
    foreach($addr as $a){
        foreach($order_details as $o){
            //-- Order Details --//
            $pdf->Cell(130,5,'Customer Name: '.$user_name,0,0);
            $pdf->Cell(30,5,$a->address,0,1);
            $pdf->Cell(130,5,'Order Date: '.$o->order_date,0,0);
            $pdf->Cell(34,5,$a->postal.', ' .$_states[$a->state],0,1);
            

            $pdf->Ln(10);
            
            $pdf->SetFont('Arial','B',15);
            $pdf->Cell(130,5,'Product Details',0,1);
            $pdf->Cell(59,5,'',0,0);
            $pdf->SetFont('Arial','B',15);
            $pdf->Cell(189,5,'',0,1);


            $pdf->SetFont('Arial','B',10);
            //--Heading of the Table--//
            $pdf->Cell(80,6,'Product Name',1,0,'C');
            $pdf->Cell(30,6,'Unit Price (RM)',1,0,'C');
            $pdf->Cell(23,6,'Qty',1,0,'C');
            $pdf->Cell(25,6,'Subtotal (RM)',1,1,'C'); 

            $pdf->SetFont('Arial','',10);
            $total_cost = 0;
            
            //--Table Body--//
            $p = get_product($o->product_id);
            $pdf->Cell(80,6,$p->product_name,1,0);
            $pdf->Cell(30,6,$p->product_price,1,0,'R');
            $pdf->Cell(23,6,$o->unit,1,0,'R');
            $pdf->Cell(25,6,$o->subtotal,1,0,'R');
            
            // Output total cost after processing all order details
            $total_cost += $o->total_cost;   
        }
        
        //--Display total cost--//
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(135, 6, 'Total(RM): ', 0, 0, 'R');
        $pdf->Cell(22, 6, $total_cost, 1, 1, 'R');
    }
    

    // Output
    $pdfContent = $pdf->Output('','S');

    //send email
    $email = $user->email;
    $subject = "Unique Receipt";
    $body = "Thank you for your order. Please find the receipt attached.";

     $m = get_mail();
     $m->addAddress($email);
     $m->Subject = $subject;
     $m->Body = $body;
     $m->isHTML(true);
     //$m->addAttachment('generatePDF.php');
     // Attach the PDF content as an inline attachment
    $m->addStringAttachment($pdfContent, 'Receipt.pdf', 'base64', 'application/pdf');
    
    // send email 
    $m->send();
    //echo 'Email sent successfully';
    temp('info', 'Email sent');
    redirect('../order_history.php');
    
?>