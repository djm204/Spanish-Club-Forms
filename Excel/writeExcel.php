<?php
/** Error reporting */
error_reporting(E_ALL);

/** Include path **/
ini_set('include_path', ini_get('include_path').';../Classes/');

/** PHPExcel */
include 'PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include 'PHPExcel/Writer/Excel2007.php';

// Create new PHPExcel object

$objPHPExcel = new PHPExcel();

// Set properties

$objPHPExcel->getProperties()->setCreator("Spanish Club");
$objPHPExcel->getProperties()->setLastModifiedBy("Spanish Club");
$objPHPExcel->getProperties()->setTitle("Payments");
$objPHPExcel->getProperties()->setSubject("Spanish Club Payments");
$objPHPExcel->getProperties()->setDescription("Contains all current payment data");

global $wpdb;
$table_name = $wpdb->prefix . "form_payment_record";
$query_payments = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' ORDER BY time DESC');

// Add some data

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Address');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Postal Code');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Phone Number');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Email');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Program');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Amount');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Date');
//time, name, address, postal_code, ph_number, email, program, amount
foreach ( $query_payments as $key=>$data )
{
    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($key + 2), $data->name)
                                  ->setCellValue('B' . ($key + 2), $data->address)
                                  ->setCellValue('C' . ($key + 2), $data->postal_code)
                                  ->setCellValue('D' . ($key + 2), $data->ph_number)
                                  ->setCellValue('E' . ($key + 2), $data->email)
                                  ->setCellValue('F' . ($key + 2), $data->program)
                                  ->setCellValue('G' . ($key + 2), $data->amount)
                                  ->setCellValue('H' . ($key + 2), $data->time);

}

// Rename sheet

$objPHPExcel->getActiveSheet()->setTitle('Simple');

        
// Save Excel 2007 file

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));


?>