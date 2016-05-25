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
$objPHPExcel->getProperties()->setTitle("In Person Payments");
$objPHPExcel->getProperties()->setSubject("Spanish Club Payments");
$objPHPExcel->getProperties()->setDescription("Contains all current payment in person data");

global $wpdb;
$table_name = $wpdb->prefix . "form_payment_record";
$query_payments = $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' ORDER BY time DESC');

$pay_in_person_array = array();


foreach ( $query_payments as $key=>$data )
{
	if($data->payment_type == 'In Person')
	{
		array_push($pay_in_person_array, $data);
	}
}

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Address');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Postal Code');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Phone Number');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Email');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Program');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Amount');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Date');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Payment Type');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Status');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(22);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
//time, name, address, postal_code, ph_number, email, program, amount

/*
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
*/

$array_start = 2;

$objPHPExcel->getActiveSheet()->setCellValue('A' . $array_start, 'Memberships');
$objPHPExcel->getActiveSheet()->getStyle('A' . $array_start)->getFont()->setSize(18);

foreach ( $pay_in_person_array as $data )
{
	$array_start ++;
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $array_start, $data->name)
                                  ->setCellValue('B' . $array_start, $data->address)
                                  ->setCellValue('C' . $array_start, $data->postal_code)
                                  ->setCellValue('D' . $array_start, $data->ph_number)
                                  ->setCellValue('E' . $array_start, $data->email)
                                  ->setCellValue('F' . $array_start, $data->program)
                                  ->setCellValue('G' . $array_start, $data->amount)
                                  ->setCellValue('H' . $array_start, $data->time)
                                  ->setCellValue('I' . $array_start, $data->payment_type)
                                  ->setCellValue('J' . $array_start, $data->status);
}

// Rename sheet

$objPHPExcel->getActiveSheet()->setTitle('Yet to be paid');

        
// Save Excel 2007 file

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));


?>