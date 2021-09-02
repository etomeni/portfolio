<?php
    require_once 'authController/fpdf/fpdf.php';

    
    $pdf->AddPage();

    // $pdfPageWith = $pdf->GetPageWidth();
    // $pdfPageHeight = $pdf->GetPageHeight();
    // $pdf->SetFont('Arial','',11);
    // $pdf->Cell(190, 0, $pdfPageWith, 0, 1 ,'L');
    // $pdf->Ln(7);
    // $pdf->Cell(190, 0, $pdfPageHeight, 0, 1 ,'L');

    // HEADER LOGO IMAGE
        $pdf->SetFont('Arial','',11);
        $pdf->Image('assets/img/pdflogo.png', 95);
        $pdf->Ln(7);
        $pdf->Cell(190, 0, 'University of Uyo Transport Management System', 0, 1 ,'C');
    // END

    $pdf->Ln(20);
    //width, height, text content, border{1=border; 0=no border}, new line {1=new line; 0=continue}, align{L, C, R}
        $pdf->SetFont('Arial','U',12);
        $pdf->Cell(190, 0, $reservationResult['ReservedTime'], 0, 1 ,'C');
        // $pdf->Line(85,55,125,55);
    // END

    $pdf->Ln(12);
    // BUS ID
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(27, 7, 'Bus:', 0, 0 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(68, 7, 'BUS_00'.$driverDetails['Bid'], 0, 0 ,'L');

    // PASSENGER'S NAME
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(27, 7, 'Passenger:', 0, 0 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(68, 7, $passengerDetails['Fname']." ".$passengerDetails['Lname'], 0, 1 ,'L');
    // END ROW

    $pdf->Ln(1);
    // DRIVER'S NAME
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(27, 7, 'Driver:', 0, 0 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(68, 7, $driverDetails['Fname']." ".$driverDetails['Lname'], 0, 0 ,'L');

    // PASSENGER'S REG. NUMBER
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(27, 7, 'Reg. No.:', 0, 0 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(68, 7, $passengerDetails['Username'], 0, 1 ,'L');
    // END ROW

    $pdf->Ln(1);
    // DRIVER'S PHONE NUMBER
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(27, 7, 'Phone No.:', 0, 0 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(68, 7, $driverDetails['PhoneNumber'], 0, 0 ,'L');

    // PASSENGER'S LOCATION
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(27, 7, 'Location:', 0, 0 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(68, 7, $reservationResult['pickUPlocation'], 0, 1 ,'L');

    // END ROW

    // TRANSACTION TYPE
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(190, 5, 'Transaction Type:', 0, 1 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(190, 8, 'Bus Reservation', 0, 1 ,'L');
    // END 
    
    // ROUTE
        $pdf->Ln(5);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(190, 5, 'Route:', 0, 1 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(190, 8, $reservationResult['pickUPlocation']." to ".$reservationResult['dropOFFlocation'], 0, 1 ,'L');
    // END

    // STATUS
        $pdf->Ln(5);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(190, 5, 'Status:', 0, 1 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(190, 8, $reservationResult['Status'], 0, 1 ,'L');
    //END

    // PAYMENT METHOD
        $pdf->Ln(5);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(190, 5, 'Payment Method:', 0, 1 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(190, 8, $reservationResult['Payment'], 0, 1 ,'L');
    // END

    // AMOUNT
        $pdf->Ln(5);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(190, 5, 'Amount:', 0, 1 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(190, 8, $reservationResult['Price'].' NGN', 0, 1 ,'L');
    // END

    // RESERVATION ID
        $pdf->Ln(5);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(190, 5, 'Reservation ID:', 0, 1 ,'L');
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(190, 8, 'RES_00'.$reservationResult['Rid'], 0, 1 ,'L');
    // END

    // PASSENGER IMAGE
        $pdf->Image('assets/img/passenger/'.$passengerDetails['Image'], 155, 95, 40, 40);
    // END


    // FOOTER TEXT
        $pdf->Ln(15);
        $pdf->SetFont('Arial','',24);
        $pdf->Cell(190, 8, $reservationResult['pickUPlocation']." to ".$reservationResult['dropOFFlocation'], 0, 1 ,'C');
    // END
    
    // FOOTER IMAGE
        $pdf->Ln(20);
        $pdf->SetFont('Arial','',11);
        $pdf->Image('assets/img/pdflogo.png',95);
        $pdf->Ln(7);
        $pdf->Cell(190, 0, 'University of Uyo Transport Management System', 0, 1 ,'C');
    // END

    $pdf->Output('D', 'RES00'.$reservationResult['Rid'].'_Receipt.pdf', true);


?>