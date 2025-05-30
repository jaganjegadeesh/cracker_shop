<?php
    require_once('fpdf.php');
    class PDF extends FPDF
    {
        function Footer()
        {
            // Go to 1.5 cm from bottom
            $this->SetY(-15);
            // Select Arial italic 8
            $this->SetFont('Arial','I',8);
            // Print centered page number
            $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
        }
    }
?>