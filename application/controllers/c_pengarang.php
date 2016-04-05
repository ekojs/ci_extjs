<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: C_pengarang
 * 
 * Table	: pengarang
 *  
 * @author Eko Junaidi Salam
 *
 */
class C_pengarang extends CI_Controller {

	public function __construct(){
		parent::__construct();	
		$this->load->model('m_pengarang', '', TRUE);
	}
	
	function getAll(){
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 15);
		//$filters 	= ($this->input->post('filter', TRUE) ? $this->input->post('filter', TRUE) : null);
		
		/*
		 * Processing Data
		 */
		$result = $this->m_pengarang->getAll($start, $page, $limit);
		echo json_encode($result);
	}
	
	function save(){
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_pengarang->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_pengarang->delete($data);
		echo json_encode($result);
	}
	
	function export2Excel(){
		$data = json_decode($this->input->post('data',TRUE));
		
		$this->load->library('excel');		
		$this->excel->setActiveSheetIndex(0);		
		$this->excel->getActiveSheet()->setTitle('Export Result');
		
		$row = 1; // 1-based index
		foreach ($data as $datar) {
			$col = 0;
			foreach($datar as $key=>$value) {
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $key);
				$this->excel->getActiveSheet()->getStyleByColumnAndRow($col, 1)->getFont()->setBold(true);
				$col++;
			}
			$row++;
		}
		
		$row = 2;
		foreach($data as $record)
		{
			$col = 0;
			foreach ($data[0] as $key => $value)
			{
				$cellvalue = $record->$key;
				
				if($key == strtolower('pengarang')){
					$this->excel->getActiveSheet()->getCell(chr($col).$row)->setValueExplicit($cellvalue, PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $cellvalue);
				}
				
				$col++;
			}
		
			$row++;
		}	
		
		$filename='pengarang.xlsx';
		//header('Content-Type: application/vnd.ms-excel'); //mime type for Excel5
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type for Excel2007
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save(APPPATH.'../temp/'.$filename);
		echo $filename;
	}
	
	function printRecords(){
		$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		$data["table"] = "pengarang";
		$print_view=$this->load->view("p_pengarang.php",$data,TRUE);
		if(!file_exists("temp")){
			mkdir("temp");
		}
		$print_file=fopen("temp/pengarang.html","w+");
		fwrite($print_file, $print_view);
		echo '1';
	}	
}