<?php
require('../fpdf/fpdf.php');

// Retrieve data from GET parameters
$orderNumber = 'RH' . uniqid(); // You can generate a unique order number or use another logic
$customerName = isset($_GET['customer_name']) ? $_GET['customer_name'] : 'Unknown';
$salonName = isset($_GET['shop_name']) ? $_GET['shop_name'] : 'Unknown Salon';
$service = isset($_GET['item_name']) ? $_GET['item_name'] : 'Service Name';
$location = 'Dhaka, Bangladesh'; // You can customize this based on your data
$serviceDate = isset($_GET['selected_date']) ? $_GET['selected_date'] : date("Y-m-d");
$serviceTime = isset($_GET['selected_time']) ? $_GET['selected_time'] : '10:00 AM';
$totalAmount = isset($_GET['item_price']) ? $_GET['item_price'] : '0';

// Create instance of FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Set Title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'RadiantHub Order Receipt', 0, 1, 'C');

// Add some space
$pdf->Ln(10);

// Order Number
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Order No:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, $orderNumber, 0, 1);

// Customer Name
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Customer:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, $customerName, 0, 1);

// Salon Name
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Salon:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, $salonName, 0, 1);

// Service Details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Service:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, $service, 0, 1);

// Location
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Location:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, $location, 0, 1);

// Service Date and Time
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Service Date:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, $serviceDate, 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Service Time:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, $serviceTime, 0, 1);

// Total Amount
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Total Amount:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, 'BDT ' . $totalAmount, 0, 1);

// Add some space before footer
$pdf->Ln(10);

// Footer
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Thank you for choosing RadiantHub!', 0, 1, 'C');

// Output the PDF (D = download)
$pdf->Output('D', 'RadiantHub_Receipt_' . $orderNumber . '.pdf');
?>
