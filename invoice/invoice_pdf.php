<?php
require('../fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // print_r($_POST);
    // Get and sanitize inputs
    $full_name = $_POST['full_name'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $city = $_POST['city'] ?? '';
    $date = $_POST['date'] ?? '';
    $estimate_no = $_POST['estimate_no'] ?? '';
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;

    // Product arrays
    $product_ids = $_POST['product_id'] ?? [];
    $product_names = $_POST['product_name'] ?? [];
    $product_rates = $_POST['product_rate'] ?? [];
    $product_units = $_POST['unit'] ?? [];
    $product_qtys = $_POST['product_qty'] ?? [];

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetAutoPageBreak(false);

    // Title
    $pdf->Cell(0, 10, "Estimate Preview", 1, 1, 'C');
    $start_y = $pdf->GetY();
    // Customer info
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, "Full Name:");
    $pdf->Cell(50, 10, $full_name, 0, 0);
    $pdf->Cell(20, 10, "City:");
    $pdf->Cell(0, 10, $city, 0, 1);
    $pdf->Cell(40, 10, "Mobile:");
    $pdf->Cell(50, 10, $mobile, 0, 0);
    $pdf->Cell(20, 10, "Date:");
    $pdf->Cell(0, 10, $date, 0, 1);
    $pdf->Cell(40, 10, "Estimate #:");
    $pdf->Cell(0, 10, $estimate_no, 0, 1);
    $end_y = $pdf->GetY();

    $pdf->SetY($start_y);

    $pdf->cell(0, $end_y-$start_y, 1,1,'C');
    $pdf->Line(100,$start_y,100,$end_y);
    // Table header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, "Sno", 1, 0, 'C');
    $pdf->Cell(70, 10, "Product Name", 1, 0, 'C');
    $pdf->Cell(25, 10, "Rate", 1, 0, 'C');
    $pdf->Cell(25, 10, "Unit", 1, 0, 'C');
    $pdf->Cell(20, 10, "Qty", 1, 0, 'C');
    $pdf->Cell(30, 10, "Amount", 1, 1, 'C');

    $pdf->SetFont('Arial', '', 12);


    $total_amount = 0;
        
    for ($i = 0; $i < count($product_ids); $i++) {
        if($pdf->GetY() > 250) {
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetX(10);
            $pdf->Cell(160, 8, 'Total Balance', 1, 0, 'R', 0);
            $pdf->Cell(30, 8, number_format($total_amount,2), 1, 1, 'R');
            $next_page = $pdf->PageNo() +1;
            $pdf->Cell(0,5,'Continued to Page Number '.$next_page,1,1,'R',0);
            $pdf->SetFont('Arial','I',7);
            $pdf->SetY(285);
            $pdf->SetX(10);
            $pdf->Cell(190,3,'Page No : '.$pdf->PageNo().' / {nb}',0,0,'R');
            $pdf->AddPage();

             $pdf->SetFont('Arial', 'B', 16);

            // Title
            $pdf->Cell(0, 10, "Estimate Preview", 1, 1, 'C');
            $start_y = $pdf->GetY();
            // Customer info
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(40, 10, "Full Name:");
            $pdf->Cell(50, 10, $full_name, 0, 0);
            $pdf->Cell(20, 10, "City:");
            $pdf->Cell(0, 10, $city, 0, 1);
            $pdf->Cell(40, 10, "Mobile:");
            $pdf->Cell(50, 10, $mobile, 0, 0);
            $pdf->Cell(20, 10, "Date:");
            $pdf->Cell(0, 10, $date, 0, 1);
            $pdf->Cell(40, 10, "Estimate #:");
            $pdf->Cell(0, 10, $estimate_no, 0, 1);
            $end_y = $pdf->GetY();

            $pdf->SetY($start_y);

            $pdf->cell(0, $end_y-$start_y, 1,1,'C');
            $pdf->Line(100,$start_y,100,$end_y);
            // Table header
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(20, 10, "Sno", 1, 0, 'C');
            $pdf->Cell(70, 10, "Product Name", 1, 0, 'C');
            $pdf->Cell(25, 10, "Rate", 1, 0, 'C');
            $pdf->Cell(25, 10, "Unit", 1, 0, 'C');
            $pdf->Cell(20, 10, "Qty", 1, 0, 'C');
            $pdf->Cell(30, 10, "Amount", 1, 1, 'C');

            $pdf->SetFont('Arial', '', 12);

            $pdf->Cell(160, 8, 'Open Balance', 1, 0, 'R', 0);
            $pdf->Cell(30, 8, number_format($total_amount,2), 1, 1, 'R');

        }
        $id = $product_ids[$i];
        $name = $product_names[$i];
        $rate = floatval($product_rates[$i]);
        $unit = $product_units[$i];
        $qty = intval($product_qtys[$i]);
        $amount = $rate * $qty;
        $total_amount += $amount;

        $pdf->Cell(20, 10, $i+1, 1, 0, 'C');
        $pdf->Cell(70, 10, $name, 1, 0, 'C');
        $pdf->Cell(25, 10, number_format($rate, 2), 1, 0, 'C');
        $pdf->Cell(25, 10, $unit, 1, 0, 'C');
        $pdf->Cell(20, 10, $qty, 1, 0, 'C');
        $pdf->Cell(30, 10, number_format($amount, 2), 1, 1, 'R');
    }

    // Discount and totals
    $discount_amount = ($total_amount * $discount) / 100;
    $final_amount = $total_amount - $discount_amount;

    $pdf->Cell(160, 10, "Subtotal:", 1, 0, 'R');
    $pdf->Cell(30, 10, number_format($total_amount, 2), 1, 1, 'R');
    $pdf->Cell(160, 10, "Discount ({$discount}%):", 1, 0, 'R');
    $pdf->Cell(30, 10, "-" . number_format($discount_amount, 2), 1, 1, 'R');
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(160, 10, "Total:", 1, 0, 'R');
    $pdf->Cell(30, 10, number_format($final_amount, 2), 1, 1, 'R');

    $pdf->Output();
} else {
    echo "Invalid request method.";
}
