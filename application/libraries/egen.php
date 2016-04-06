<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * EGen library
 *
 * @author	Eko Junaidi Salam 2013
 */
class Egen{
	var $CI = NULL;
	function __construct()
	{
		$this->CI =& get_instance();
	}

	private function dirToArray($dir) {
		$result = array();

		$cdir = scandir($dir);
		foreach ($cdir as $key => $value)
		{
			if (!in_array($value,array(".","..")))
			{
				if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
				{
					$result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value);
				}
				else
					$result[] = $value;
			}
		}

		return $result;
	}
	
	function SingleGrid($path,$nfile,$tbl,$data){
		$this->CController($path,$nfile,$tbl,$data);
		$this->CModel($path,$nfile,$tbl,$data);
		$this->CPrint($path,$nfile,$tbl,$data);
		$this->CControllerExtjs($path,$nfile,$tbl,$data);
		$this->CModelExtjs($path,$nfile,$tbl,$data);
		$this->CStoreExtjs($path,$nfile,$tbl,$data);
		$this->CViewExtjs($path,$nfile,$tbl,$data);
		$this->CViewport($path,$nfile,$tbl,$data);
		$this->tulisNav();
		$this->tulisApp();
		echo "<br /><a href='".base_url('welcome/gce')."'>Kembali</a>";
		$this->CI->load->view('preview',array('path'=>$tbl));
	}
	
	function CController($path,$nfile,$tbl,$data){
		$tulis = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: C_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
class C_".$nfile." extends CI_Controller {

	public function __construct(){
		parent::__construct();		
		\$this->load->model('m_".$nfile."', '', TRUE);
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		\$start  =   (\$this->input->post('start', TRUE) ? \$this->input->post('start', TRUE) : 0);
		\$page   =   (\$this->input->post('page', TRUE) ? \$this->input->post('page', TRUE) : 1);
		\$limit  =   (\$this->input->post('limit', TRUE) ? \$this->input->post('limit', TRUE) : 15);
		//\$filters 	= (\$this->input->post('filter', TRUE) ? \$this->input->post('filter', TRUE) : null);
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->getAll(\$start, \$page, \$limit);
		echo json_encode(\$result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.".$nfile."]
		 */
		\$data   = json_decode(\$this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->save(\$data);
		echo json_encode(\$result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.".$nfile."]
		 */
		\$data   = json_decode(\$this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->delete(\$data);
		echo json_encode(\$result);
	}
	
	function export2Excel(){
		\$data = json_decode(\$this->input->post('data',TRUE));
		
		\$this->load->library('excel');		
		\$this->excel->setActiveSheetIndex(0);		
		\$this->excel->getActiveSheet()->setTitle('Export Result');
		
		\$row = 1; // 1-based index
		foreach (\$data as \$datar) {
			\$col = 0;
			foreach(\$datar as \$key=>\$value) {
				\$this->excel->getActiveSheet()->setCellValueByColumnAndRow(\$col, \$row, \$key);
				\$this->excel->getActiveSheet()->getStyleByColumnAndRow(\$col, 1)->getFont()->setBold(true);
				\$col++;
			}
			\$row++;
		}
		
		\$row = 2;
		foreach(\$data as \$record)
		{
			\$col = 0;
			foreach (\$data[0] as \$key => \$value)
			{
				\$cellvalue = \$record->\$key;
				
				if(\$key == strtolower('".$nfile."')){
					\$this->excel->getActiveSheet()->getCell(chr(\$col).\$row)->setValueExplicit(\$cellvalue, PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					\$this->excel->getActiveSheet()->setCellValueByColumnAndRow(\$col, \$row, \$cellvalue);
				}
				
				\$col++;
			}
		
			\$row++;
		}	
		
		\$filename='".$nfile.".xlsx';
		//header('Content-Type: application/vnd.ms-excel'); //mime type for Excel5
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type for Excel2007
		header('Content-Disposition: attachment;filename=\"'.\$filename.'\"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		\$objWriter = PHPExcel_IOFactory::createWriter(\$this->excel, 'Excel2007');
		//force user to download the Excel file without writing it to server's HD
		\$objWriter->save(APPPATH.'../temp/'.\$filename);
		echo \$filename;
	}
	
	function printRecords(){
		\$getdata = json_decode(\$this->input->post('data',TRUE));
		\$data[\"records\"] = \$getdata;
		\$data[\"table\"] = \"".$tbl."\";
		\$print_view=\$this->load->view(\"p_".$nfile.".php\",\$data,TRUE);
		if(!file_exists(\"temp\")){
			mkdir(\"temp\");
		}
		\$print_file=fopen(\"temp/".$nfile.".html\",\"w+\");
		fwrite(\$print_file, \$print_view);
		echo '1';
	}	
}";
		
		if ( ! write_file($path."/controllers/c_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Controller telah digenerate...!!!<br /> Lokasi : ".$path."/controllers/c_".$nfile.".php </strong><br />";			
			return 1;
		}
	}
	
	
	function CModel($path,$nfile,$tbl,$data){
		$tulis = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
class M_".$nfile." extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data
	 * 
	 * @param number \$start
	 * @param number \$page
	 * @param number \$limit
	 * @return json
	 */
	function getAll(\$start, \$page, \$limit){";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$key = $field->name;
			}
		}
		$tulis .= "
		//if(\$filters == null){
			\$query  = \$this->db->limit(\$limit, \$start)->order_by('".$key."', 'DESC')->get('".$tbl."')->result();
			\$total  = \$this->db->get('".$tbl."')->num_rows();
			
			\$data   = array();
			foreach(\$query as \$result){
				\$data[] = \$result;
			}
			
			\$json	= array(
							'success'   => TRUE,
							'message'   => \"Loaded data\",
							'total'     => \$total,
							'data'      => \$data
			);
			
			return \$json;
		/*}
		else
		{
			if (is_array(\$filters)) {
				\$encoded = false;
			} else {
				\$encoded = true;
				\$filters = json_decode(\$filters);
			}

			\$where = \" 0=0 \";
			\$qs = '';

			if (is_array(\$filters)) {
				for (\$i=0;\$i<count(\$filters);\$i++){
					\$filter = \$filters[\$i];
					if (\$encoded) {
						\$field = isset(\$filter->field) ? \$filter->field : null;
						\$value = isset(\$filter->value) ? \$filter->value : null;
						if(\$field == null || \$value == null){
							\$query  = \$this->db->limit(\$limit, \$start)->order_by('".$key."', 'ASC')->get('".$tbl."')->result();
							\$total  = \$this->db->get('".$tbl."')->num_rows();
							
							\$data   = array();
							foreach(\$query as \$result){
								\$data[] = \$result;
							}
							
							\$json	= array(
								'success'   => TRUE,
								'message'   => \"Loaded data\",
								'total'     => \$total,
								'data'      => \$data
							);
							
							return \$json;
						}
						\$field = \$filter->field;
						\$value = \$filter->value;
						\$compare = isset(\$filter->comparison) ? \$filter->comparison : null;
						\$filterType = \$filter->type;
					} else {
						\$field = \$filter['field'];
						\$value = \$filter['data']['value'];
						\$compare = isset(\$filter['data']['comparison']) ? \$filter['data']['comparison'] : null;
						\$filterType = \$filter['data']['type'];
					}

					switch(\$filterType){
						case 'string' : \$qs .= \" AND \".\$field.\" LIKE '%\".\$value.\"%'\"; Break;
						case 'list' :
							if (strstr(\$value,',')){
								\$fi = explode(',',\$value);
								for (\$q=0;\$q<count(\$fi);\$q++){
									\$fi[\$q] = \"'\".\$fi[\$q].\"'\";
								}
								\$value = implode(',',\$fi);
								\$qs .= \" AND \".\$field.\" IN (\".\$value.\")\";
							}else{
								\$qs .= \" AND \".\$field.\" = '\".\$value.\"'\";
							}
						Break;
						case 'boolean' : \$qs .= \" AND \".\$field.\" = \".(\$value); Break;
						case 'numeric' :
							switch (\$compare) {
								case 'eq' : \$qs .= \" AND \".\$field.\" = \".\$value; Break;
								case 'lt' : \$qs .= \" AND \".\$field.\" < \".\$value; Break;
								case 'gt' : \$qs .= \" AND \".\$field.\" > \".\$value; Break;
							}
						Break;
						case 'date' :
							switch (\$compare) {
								case 'eq' : \$qs .= \" AND \".\$field.\" = '\".date('Y-m-d',strtotime(\$value)).\"'\"; Break;
								case 'lt' : \$qs .= \" AND \".\$field.\" < '\".date('Y-m-d',strtotime(\$value)).\"'\"; Break;
								case 'gt' : \$qs .= \" AND \".\$field.\" > '\".date('Y-m-d',strtotime(\$value)).\"'\"; Break;
							}
						Break;
						case 'datetime' :
							switch (\$compare) {
								case 'eq' : \$qs .= \" AND \".\$field.\" = '\".date('Y-m-d H:i:s',strtotime(\$value)).\"'\"; Break;
								case 'lt' : \$qs .= \" AND \".\$field.\" < '\".date('Y-m-d H:i:s',strtotime(\$value)).\"'\"; Break;
								case 'gt' : \$qs .= \" AND \".\$field.\" > '\".date('Y-m-d H:i:s',strtotime(\$value)).\"'\"; Break;
							}
						Break;
					}
				}
				\$where .= \$qs;
			}
			
			\$sql = \"SELECT * FROM ".$tbl."
			WHERE \".\$where;
			\$sql .= \" ORDER BY ".$key." ASC\";
			\$sql .= \" LIMIT \".\$start.\",\".\$limit;
			\$query = \$this->db->query(\$sql)->result();
			
			\$total  = \$this->db->query(\"SELECT count(".$key.") as total
			FROM ".$tbl."
			WHERE \".\$where)->result();
			
			\$data   = array();
			foreach(\$query as \$result){
				\$data[] = \$result;
			}
			
			\$json	= array(
				'success'   => TRUE,
				'message'   => \"Loaded data\",
				'total'     => \$total[0]->total,
				'data'      => \$data
			);
			
			return \$json;
		}*/
	}
	
	/**
	 * Fungsi	: save
	 * 
	 * Untuk menambah data baru atau mengubah data lama
	 * 
	 * @param array \$data
	 * @return json
	 */
	function save(\$data){
		\$last   = NULL;
		
		\$pkey = array(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d', strtotime(\$data->".$field->name.")),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
		
		if(\$this->db->get_where('".$tbl."', \$pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			\$arrdatau = array(";
		foreach($data['fields'] as $field)
		{
			if(! $field->primary_key == "1")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d', strtotime(\$data->".$field->name.")) : NULL),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")) : NULL),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
			 
			\$this->db->where(\$pkey)->update('".$tbl."', \$arrdatau);
			\$last   = \$data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			\$arrdatac = array(";
		foreach($data['fields'] as $field)
		{
			if($field->type == "date")
			{
				$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d', strtotime(\$data->".$field->name.")) : NULL),";
			}
			elseif($field->type == "datetime")
			{
				$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")) : NULL),";
			}
			else
				$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
			 
			\$this->db->insert('".$tbl."', \$arrdatac);
			\$last   = \$this->db->where(\$pkey)->get('".$tbl."')->row();
			
		}
		
		\$total  = \$this->db->get('".$tbl."')->num_rows();
		
		\$json   = array(
						\"success\"   => TRUE,
						\"message\"   => 'Data berhasil disimpan',
						\"total\"     => \$total,
						\"data\"      => \$last
		);
		
		return \$json;
	}
	
	/**
	 * Fungsi	: delete
	 * 
	 * Untuk menghapus satu data
	 * 
	 * @param array \$data
	 * @return json
	 */
	function delete(\$data){
		\$pkey = array(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d', strtotime(\$data->".$field->name.")),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
		
		\$this->db->where(\$pkey)->delete('".$tbl."');
		
		\$total  = \$this->db->get('".$tbl."')->num_rows();
		\$last = \$this->db->get('".$tbl."')->result();
		
		\$json   = array(
						\"success\"   => TRUE,
						\"message\"   => 'Data berhasil dihapus',
						\"total\"     => \$total,
						\"data\"      => \$last
		);				
		return \$json;
	}
}
?>";
		
		if ( ! write_file($path."/models/m_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Model telah digenerate...!!!<br /> Lokasi : ".$path."/models/m_".$nfile.".php </strong><br />";
			return 1;
		}
	}
	
	
	function CControllerExtjs($path,$nfile,$tbl,$data){
		$tulis = "/**
 * Class	: C_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.controller.".strtolower($nfile)."',{
	extend: 'Ext.app.Controller',
	views: ['".$data['pathjs'].".v_".$nfile."'],
	models: ['m_".$nfile."'],
	stores: ['s_".$nfile."'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'List".$nfile."',
		selector: 'List".$nfile."'
	}],


	init: function(){
		this.control({
			'List".$nfile."': {
				'afterrender': this.".$nfile."AfterRender,
				'selectionchange': this.enableDelete
			},
			'List".$nfile." button[action=create]': {
				click: this.createRecord
			},
			'List".$nfile." button[action=delete]': {
				click: this.deleteRecord
			},
			'List".$nfile." button[action=export]': {
				click: this.export2Excel
			},
			'List".$nfile." button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	".$nfile."AfterRender: function(){
		var ".$nfile."Store = this.getList".$nfile."().getStore();
		".$nfile."Store.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('EJS.model.m_".$nfile."');
		var r = Ext.ModelManager.create({
		";
		foreach($data['fields'] as $field)
		{
			$tulis .= "".$field->name."		: '',";
			if($field->primary_key == "1")
			{
				$key = $field->name;
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= "}, model);
		this.getList".$nfile."().getStore().insert(0, r);
		this.getList".$nfile."().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getList".$nfile."().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getList".$nfile."().getStore();
		var selection = this.getList".$nfile."().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: ".$key." = \"'+selection.data.".$key."+'\"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getList".$nfile."().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_".$nfile."/export2Excel',
			params: {data: jsonData},
			timeout: 600000,
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getList".$nfile."().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_".$nfile."/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/".$nfile.".html','".$nfile."_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
					break;
				default:
					Ext.MessageBox.show({
						title: 'Warning',
						msg: 'Unable to print the grid!',
						buttons: Ext.MessageBox.OK,
						animEl: 'save',
						icon: Ext.MessageBox.WARNING
					});
					break;
				}  
			}
		});
	}
	
});";
		
		if ( ! write_file("./app/controller/".strtolower($nfile).".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Controller Extjs telah digenerate...!!!<br /> Lokasi : "."./app/controller/".strtolower($nfile).".js </strong><br />";
			return 1;
		}
	}
	
	function CPrint($path,$nfile,$tbl,$data){
		$tulis = "<!DOCTYPE html>
<html lang=\"en\">
<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
	<title>CSS Tables</title>
	
	<style type=\"text/css\">
	@media screen{
		body {
			font: normal 11px auto \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
			color: #4f6b72;
			background: #E6EAE9;
		}
	}
	@media print{
		body {
			font: normal 11px auto \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		}
	}
	
	a {
		color: #c75f3e;
	}
	
	#mytable {
		width: 700px;
		padding: 0;
		margin: 0;
	}
	
	caption {
		padding: 0 0 5px 0;
		width: 700px;	 
		font: italic 11px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		text-align: right;
	}
	
	th {
		font: bold 11px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		color: #4f6b72;
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		border-top: 1px solid #C1DAD7;
		letter-spacing: 2px;
		text-transform: uppercase;
		text-align: left;
		padding: 6px 6px 6px 12px;
		background: #CAE8EA url(./assets/images/bg_header.jpg) no-repeat;
	}
	
	th.nobg {
		border-top: 0;
		border-left: 0;
		border-right: 1px solid #C1DAD7;
		background: none;
	}
	
	td {
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		background: #fff;
		padding: 6px 6px 6px 12px;
		color: #4f6b72;
		font: normal 12px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
	}
	
	td.alt {
		background: #F5FAFA;
		color: #797268;
	}
	
	th.spec {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #fff url(./assets/images/bullet1.gif) no-repeat;
		font: bold 10px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
	}
	
	th.specalt {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #f5fafa url(./assets/images/bullet2.gif) no-repeat;
		font: bold 10px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		color: #797268;
	}
	</style>
</head>

<body>
<table id=\"mytable\" cellspacing=\"0\" summary=\"EJS - ".$nfile."\">
<caption>Table: ".$tbl." </caption>
  <tr>
	<?php 
	\$i = 0;
	foreach (\$records[0] as \$key => \$value){
		if(\$i==0){
			echo '<th scope=\"col\" abbr=\"'.\$key.'\" class=\"nobg\">'.\$key.'</th>';
		}else {
			echo '<th scope=\"col\" abbr=\"'.\$key.'\">'.\$key.'</th>';
		}
		
		\$i++;
	}
	?>
  </tr>
  <?php 
	\$i = 0;
	for(\$i=0; \$i<(sizeof(\$records)); \$i++){
		echo '<tr>';
		\$j = 0;
		foreach (\$records[\$i] as \$key => \$value){
			if((\$j==0) && (\$i%2 == 0)){
				echo '<th scope=\"row\" abbr=\"'.\$value.'\" class=\"specalt\">'.\$value.'</th>';
			}elseif((\$j==0) && (\$i%2 != 0)){
				echo '<th scope=\"row\" abbr=\"'.\$value.'\" class=\"spec\">'.\$value.'</th>';
			}else{
				if(\$i%2 == 0){
					echo '<td class=\"alt\">'.\$value.'</td>';
				}else{
					echo '<td>'.\$value.'</td>';
				}
			}
			\$j++;
		}
		echo '<tr/>';
	}
  ?>
</table>
</body>
</html>
";
		
		if ( ! write_file($path."/views/p_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Print Preview telah digenerate...!!!<br /> Lokasi : ".$path."/views/p_".$nfile.".php </strong><br />";
			return 1;
		}
	}
	
	
	function CModelExtjs($path,$nfile,$tbl,$data){
		$tulis = "/**
 * Class	: M_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.model.m_".$nfile."', {
	extend: 'Ext.data.Model',
	alias		: 'widget.".$nfile."Model',
	fields		: [";
	foreach($data['fields'] as $field)
	{
		$tulis .= "'".$field->name."',";		
	}
	
$tulis = substr($tulis,0,strlen($tulis) -1);
$tulis .= "],";
	$i=0;
	$idProperty = "";
	foreach($data['fields'] as $field)
	{
		if($field->primary_key == "1")
		{
			$idProperty = "
	idProperty	: '".$field->name."'";
			$i++;
		}
	}
	if($i == 1)
	{
		$tulis .= $idProperty;
	}else{
		$tulis = substr($tulis,0,strlen($tulis) -1);
	}
	
$tulis .= "	
});";
		
		if ( ! write_file("./app/model/m_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Model Extjs telah digenerate...!!!<br /> Lokasi : "."./app/model/m_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	
	function CStoreExtjs($path,$nfile,$tbl,$data){
		$tulis = "/**
 * Class	: S_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.store.s_".$nfile."', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.".$nfile."Store',
	model	: 'EJS.model.m_".$nfile."',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: '".$nfile."',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_".$nfile."/getAll',
			create	: 'c_".$nfile."/save',
			update	: 'c_".$nfile."/save',
			destroy	: 'c_".$nfile."/delete'
		},
		actionMethods: {
			read    : 'POST',
			create	: 'POST',
			update	: 'POST',
			destroy	: 'POST'
		},
		reader: {
			type            : 'json',
			root            : 'data',
			rootProperty    : 'data',
			successProperty : 'success',
			messageProperty : 'message'
		},
		writer: {
			type            : 'json',
			writeAllFields  : true,
			root            : 'data',
			encode          : true
		},
		listeners: {
			exception: function(proxy, response, operation){
				Ext.MessageBox.show({
					title: 'REMOTE EXCEPTION',
					msg: operation.getError(),
					icon: Ext.MessageBox.ERROR,
					buttons: Ext.Msg.OK
				});
			}
		}
	},
	
	constructor: function(){
		this.callParent(arguments);
	}
	
});";
		
		if ( ! write_file("./app/store/s_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Store Extjs telah digenerate...!!!<br /> Lokasi : "."./app/store/s_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	
	function CViewExtjs($path,$nfile,$tbl,$data){
		$tulis = "/**
 * Class	: CVE_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.view.".$data['pathjs'].".v_".$nfile."', {
	extend: 'Ext.grid.Panel',
	requires: ['EJS.store.s_".$nfile."'],
	
	//title		: '".$nfile."',
	itemId		: 'List".$nfile."',
	alias       : 'widget.List".$nfile."',
	store 		: 's_".$nfile."',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;
		var plug = Ext.create('Ext.ux.ProgressBarPager');
		var docktool = Ext.create('Ext.toolbar.Paging', {
			store: 's_".$nfile."',
			dock: 'bottom',
			displayInfo: true,
            plugins: plug
		});
	";
	
	foreach($data['fields'] as $field)
	{
		if($field->primary_key == "1")
		{
			if($field->type == "date" || $field->type == "datetime")
			{
				$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d'
		});";
			}
			elseif($field->type == "int" || $field->type == "decimal")
			{
				$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false";
			if(isset($field->max_length)){
				$tulis .= ",
			maxLength: ".$field->max_length;
			}
		$tulis .= "
		});";
			}
			else
			{
				if($field->max_length > 200)
				{
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.TextArea', {
			allowBlank : false,
			maxLength: ".(isset($field->max_length)?$field->max_length:255)."
		});";
				}
				else
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: ".(isset($field->max_length)?$field->max_length:100)."
		});";
			}
		}
	}

		$tulis .= "
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "! (/^\s*$/).test(e.record.data.".$field->name.") || ";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -3);
					$tulis .= "){
					";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "	
						".$field->name."_field.setReadOnly(true);";
			}
		}
		$tulis .= "
					}else{
						";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "
						".$field->name."_field.setReadOnly(false);";
			}
		}
		$tulis .= "
					}
					
				},
				'canceledit': function(editor, e){
					if(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "(/^\s*$/).test(e.record.data.".$field->name.") || ";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -3);
					$tulis .= "){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "(/^\s*$/).test(e.record.data.".$field->name.") || ";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -3);
					$tulis .= "){
						Ext.Msg.alert('Peringatan', 'Kolom ";
						foreach($data['fields'] as $field)
						{
							if($field->primary_key == "1")
							{
								$tulis .= "\"".$field->name."\",";
							}
						}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= " tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_".$nfile."/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (";
											foreach($data['fields'] as $field)
											{
												if($field->primary_key == "1")
												{
													if($field->type == "date")
													{
														$tulis .= "(new Date(record.get('".$field->name."'))).format('yyyy-mm-dd') === (new Date(e.record.data.".$field->name.")).format('yyyy-mm-dd') && ";
													}
													elseif($field->type == "datetime")
													{
														$tulis .= "(new Date(record.get('".$field->name."'))).format('yyyy-mm-dd hh:nn:ss') === (new Date(e.record.data.".$field->name.")).format('yyyy-mm-dd hh:nn:ss') && ";
													}
													elseif($field->type == "int" || $field->type == "decimal")
													{
														$tulis .= "parseFloat(record.get('".$field->name."')) === e.record.data.".$field->name." && ";
													}
													else
														$tulis .= "record.get('".$field->name."') === e.record.data.".$field->name." && ";
												}
											}
											$tulis = substr($tulis,0,strlen($tulis) - 4);
											$tulis .= ") {
												return true;
											}
											return false;
										}
									);
									/* me.grid.getView().select(recordIndex); */
									me.grid.getSelectionModel().select(newRecordIndex);
								}
							});
						}
					});
					return true;
				}
			}
		});
		
		this.columns = [
			";
foreach($data['fields'] as $field)
{
	if(! $field->primary_key == "1")
	{
		if($field->type == "date" || $field->type == "datetime")
		{
			$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: {xtype: 'datefield',format: 'm-d-Y'}
			},";
		}
		elseif($field->type == "int")
		{
			$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				field: {xtype: 'numberfield'}
			},";
		}
		elseif($field->type == "decimal")
		{
			$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				},
				field: {xtype: 'numberfield'}
			},";
		}
		else
		{
			if($field->max_length > 200)
			{
				$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				field: {xtype: 'textarea'}
			},";
			}
			else
				$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				field: {xtype: 'textfield'}
			},";
		}
	}
	else
	{
		if($field->type == "date" || $field->type == "datetime")
		{
			$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: ".$field->name."_field
			},";
		}
		elseif($field->type == "int" || $field->type == "decimal")
		{
			$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				field: ".$field->name."_field
			},";
		}
		else
		{
			if($field->max_length > 200)
			{
				$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				field: ".$field->name."_field
			},";
			}
			else
				$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				field: ".$field->name."_field
			},";
		}
			
	}		
}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= "];
		this.features = [{
				ftype: 'filters',
				autoReload: true,
				encode: false,
				local: true
			}
		];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						text	: 'Add',
						iconCls	: 'icon-add',
						action	: 'create'
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btndelete',
						text	: 'Delete',
						iconCls	: 'icon-remove',
						action	: 'delete',
						disabled: true
					}]
				}, '-', {
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						iconCls: 'icon-excel',
						itemId: 'export',
						text: 'Export',
						action	: 'export'
					},{
						xtype: 'splitter'
					},{
						text	: 'Cetak',
						iconCls	: 'icon-print',
						action	: 'print'
					}]
				}]
			}),docktool
		];
		
		docktool.add([{
				text: 'Clear Filter Data',
				handler: function () {
					me.filters.clearFilters();
				}
			}
		]);
		
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }

});";
		
		if ( ! write_file("./app/view/".$data['pathjs']."/v_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>View Extjs telah digenerate...!!!<br /> Lokasi : "."./app/view/".$data['pathjs']."/v_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	
	function CViewport($path,$nfile,$tbl,$data){
		$tulis = "/**
 * Class	: V_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.view.".$data['pathjs'].".".strtolower($nfile)."', {
	extend: 'Ext.form.Panel',
	
	bodyPadding: 0,
	layout: 'border',
	initComponent: function(){
		this.items = [{
			region: 'center',
			layout: {
				type : 'hbox',
				align: 'stretch'
			},
			items: [{
				xtype	: 'List".$nfile."',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});";
		
		if ( ! write_file("./app/view/".$data['pathjs']."/".strtolower($nfile).".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Viewport Extjs telah digenerate...!!!<br /> Lokasi : "."./app/view/".$data['pathjs']."/".strtolower($nfile).".js </strong><br />";
			return 1;
		}
	}
	
	
	function SingleGridSF($path,$nfile,$tbl,$data){
		$this->CControllerSF($path,$nfile,$tbl,$data);
		$this->CModelSF($path,$nfile,$tbl,$data);
		$this->CPrintSF($path,$nfile,$tbl,$data);
		$this->CControllerExtjsSF($path,$nfile,$tbl,$data);
		$this->CModelExtjsSF($path,$nfile,$tbl,$data);
		$this->CStoreExtjsSF($path,$nfile,$tbl,$data);
		$this->CViewExtjsSF($path,$nfile,$tbl,$data);
		$this->CFormExtjs($path,$nfile,$tbl,$data);
		$this->CViewportSF($path,$nfile,$tbl,$data);
		$this->tulisNav();
		$this->tulisApp();
		echo "<br /><a href='".base_url('welcome/gce')."'>Kembali</a>";
		$this->CI->load->view('preview',array('path'=>$tbl));
	}
	
	
	function CControllerSF($path,$nfile,$tbl,$data){
		$tulis = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: C_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
class C_".$nfile." extends CI_Controller {

	public function __construct(){
		parent::__construct();	
		\$this->load->model('m_".$nfile."', '', TRUE);
	}
	
	function getAll(){
		\$start  =   (\$this->input->post('start', TRUE) ? \$this->input->post('start', TRUE) : 0);
		\$page   =   (\$this->input->post('page', TRUE) ? \$this->input->post('page', TRUE) : 1);
		\$limit  =   (\$this->input->post('limit', TRUE) ? \$this->input->post('limit', TRUE) : 15);
		//\$filters 	= (\$this->input->post('filter', TRUE) ? \$this->input->post('filter', TRUE) : null);
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->getAll(\$start, \$page, \$limit);
		echo json_encode(\$result);
	}
	
	function save(){
		\$data   = json_decode(\$this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->save(\$data);
		echo json_encode(\$result);
	}
	
	function delete(){
		\$data   = json_decode(\$this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->delete(\$data);
		echo json_encode(\$result);
	}
	
	function export2Excel(){
		\$data = json_decode(\$this->input->post('data',TRUE));
		
		\$this->load->library('excel');		
		\$this->excel->setActiveSheetIndex(0);		
		\$this->excel->getActiveSheet()->setTitle('Export Result');
		
		\$row = 1; // 1-based index
		foreach (\$data as \$datar) {
			\$col = 0;
			foreach(\$datar as \$key=>\$value) {
				\$this->excel->getActiveSheet()->setCellValueByColumnAndRow(\$col, \$row, \$key);
				\$this->excel->getActiveSheet()->getStyleByColumnAndRow(\$col, 1)->getFont()->setBold(true);
				\$col++;
			}
			\$row++;
		}
		
		\$row = 2;
		foreach(\$data as \$record)
		{
			\$col = 0;
			foreach (\$data[0] as \$key => \$value)
			{
				\$cellvalue = \$record->\$key;
				
				if(\$key == strtolower('".$nfile."')){
					\$this->excel->getActiveSheet()->getCell(chr(\$col).\$row)->setValueExplicit(\$cellvalue, PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					\$this->excel->getActiveSheet()->setCellValueByColumnAndRow(\$col, \$row, \$cellvalue);
				}
				
				\$col++;
			}
		
			\$row++;
		}	
		
		\$filename='".$nfile.".xlsx';
		//header('Content-Type: application/vnd.ms-excel'); //mime type for Excel5
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type for Excel2007
		header('Content-Disposition: attachment;filename=\"'.\$filename.'\"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		\$objWriter = PHPExcel_IOFactory::createWriter(\$this->excel, 'Excel2007');
		//force user to download the Excel file without writing it to server's HD
		\$objWriter->save(APPPATH.'../temp/'.\$filename);
		echo \$filename;
	}
	
	function printRecords(){
		\$getdata = json_decode(\$this->input->post('data',TRUE));
		\$data[\"records\"] = \$getdata;
		\$data[\"table\"] = \"".$tbl."\";
		\$print_view=\$this->load->view(\"p_".$nfile.".php\",\$data,TRUE);
		if(!file_exists(\"temp\")){
			mkdir(\"temp\");
		}
		\$print_file=fopen(\"temp/".$nfile.".html\",\"w+\");
		fwrite(\$print_file, \$print_view);
		echo '1';
	}	
}";
		
		if ( ! write_file($path."/controllers/c_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Controller telah digenerate...!!!<br /> Lokasi : ".$path."/controllers/c_".$nfile.".php </strong><br />";			
			return 1;
		}
	}
	
	
	function CModelSF($path,$nfile,$tbl,$data){
		$tulis = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
class M_".$nfile." extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data
	 * 
	 * @param number \$start
	 * @param number \$page
	 * @param number \$limit
	 * @return json
	 */
	function getAll(\$start, \$page, \$limit){";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$key = $field->name;
			}
		}
		$tulis .= "
		//if(\$filters == null){
			\$query  = \$this->db->limit(\$limit, \$start)->order_by('".$key."', 'DESC')->get('".$tbl."')->result();
			\$total  = \$this->db->get('".$tbl."')->num_rows();
			
			\$data   = array();
			foreach(\$query as \$result){
				\$data[] = \$result;
			}
			
			\$json	= array(
							'success'   => TRUE,
							'message'   => \"Loaded data\",
							'total'     => \$total,
							'data'      => \$data
			);
			
			return \$json;
		/*}
		else
		{
			if (is_array(\$filters)) {
				\$encoded = false;
			} else {
				\$encoded = true;
				\$filters = json_decode(\$filters);
			}

			\$where = \" 0=0 \";
			\$qs = '';

			if (is_array(\$filters)) {
				for (\$i=0;\$i<count(\$filters);\$i++){
					\$filter = \$filters[\$i];
					if (\$encoded) {
						\$field = isset(\$filter->field) ? \$filter->field : null;
						\$value = isset(\$filter->value) ? \$filter->value : null;
						if(\$field == null || \$value == null){
							\$query  = \$this->db->limit(\$limit, \$start)->order_by('".$key."', 'ASC')->get('".$tbl."')->result();
							\$total  = \$this->db->get('".$tbl."')->num_rows();
							
							\$data   = array();
							foreach(\$query as \$result){
								\$data[] = \$result;
							}
							
							\$json	= array(
								'success'   => TRUE,
								'message'   => \"Loaded data\",
								'total'     => \$total,
								'data'      => \$data
							);
							
							return \$json;
						}
						\$field = \$filter->field;
						\$value = \$filter->value;
						\$compare = isset(\$filter->comparison) ? \$filter->comparison : null;
						\$filterType = \$filter->type;
					} else {
						\$field = \$filter['field'];
						\$value = \$filter['data']['value'];
						\$compare = isset(\$filter['data']['comparison']) ? \$filter['data']['comparison'] : null;
						\$filterType = \$filter['data']['type'];
					}

					switch(\$filterType){
						case 'string' : \$qs .= \" AND \".\$field.\" LIKE '%\".\$value.\"%'\"; Break;
						case 'list' :
							if (strstr(\$value,',')){
								\$fi = explode(',',\$value);
								for (\$q=0;\$q<count(\$fi);\$q++){
									\$fi[\$q] = \"'\".\$fi[\$q].\"'\";
								}
								\$value = implode(',',\$fi);
								\$qs .= \" AND \".\$field.\" IN (\".\$value.\")\";
							}else{
								\$qs .= \" AND \".\$field.\" = '\".\$value.\"'\";
							}
						Break;
						case 'boolean' : \$qs .= \" AND \".\$field.\" = \".(\$value); Break;
						case 'numeric' :
							switch (\$compare) {
								case 'eq' : \$qs .= \" AND \".\$field.\" = \".\$value; Break;
								case 'lt' : \$qs .= \" AND \".\$field.\" < \".\$value; Break;
								case 'gt' : \$qs .= \" AND \".\$field.\" > \".\$value; Break;
							}
						Break;
						case 'date' :
							switch (\$compare) {
								case 'eq' : \$qs .= \" AND \".\$field.\" = '\".date('Y-m-d',strtotime(\$value)).\"'\"; Break;
								case 'lt' : \$qs .= \" AND \".\$field.\" < '\".date('Y-m-d',strtotime(\$value)).\"'\"; Break;
								case 'gt' : \$qs .= \" AND \".\$field.\" > '\".date('Y-m-d',strtotime(\$value)).\"'\"; Break;
							}
						Break;
						case 'datetime' :
							switch (\$compare) {
								case 'eq' : \$qs .= \" AND \".\$field.\" = '\".date('Y-m-d H:i:s',strtotime(\$value)).\"'\"; Break;
								case 'lt' : \$qs .= \" AND \".\$field.\" < '\".date('Y-m-d H:i:s',strtotime(\$value)).\"'\"; Break;
								case 'gt' : \$qs .= \" AND \".\$field.\" > '\".date('Y-m-d H:i:s',strtotime(\$value)).\"'\"; Break;
							}
						Break;
					}
				}
				\$where .= \$qs;
			}
			
			\$sql = \"SELECT * FROM ".$tbl."
			WHERE \".\$where;
			\$sql .= \" ORDER BY ".$key." ASC\";
			\$sql .= \" LIMIT \".\$start.\",\".\$limit;
			\$query = \$this->db->query(\$sql)->result();
			
			\$total  = \$this->db->query(\"SELECT count(".$key.") as total
			FROM ".$tbl."
			WHERE \".\$where)->result();
			
			\$data   = array();
			foreach(\$query as \$result){
				\$data[] = \$result;
			}
			
			\$json	= array(
				'success'   => TRUE,
				'message'   => \"Loaded data\",
				'total'     => \$total[0]->total,
				'data'      => \$data
			);
			
			return \$json;
		}*/
	}
	
	/**
	 * Fungsi	: save
	 * 
	 * Untuk menambah data baru atau mengubah data lama
	 * 
	 * @param array \$data
	 * @return json
	 */
	function save(\$data){
		\$last   = NULL;
		
		\$pkey = array(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1" && $field->name != "ID")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d', strtotime(\$data->".$field->name.")),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
		
		if(\$this->db->get_where('".$tbl."', \$pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
			";
		foreach($data['fields'] as $field)
		{
			if($field->type == "decimal")
			{
				$tulis .= "\$tmp = substr(\$data->".$field->name.",3,strlen(\$data->".$field->name."));
			\$tmp = str_replace('.','',\$tmp);
			\$tmp = str_replace(',','.',\$tmp);
			\$data->".$field->name." = \$tmp;";
			}
		}
		$tulis .= "	
			 
			\$arrdatau = array(";
		foreach($data['fields'] as $field)
		{
			if(! $field->primary_key == "1")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d', strtotime(\$data->".$field->name.")) : NULL),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")) : NULL),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
			 
			\$this->db->where(\$pkey)->update('".$tbl."', \$arrdatau);
			\$last   = \$data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 ";
		foreach($data['fields'] as $field)
		{
			if($field->type == "decimal")
			{
				$tulis .= "\$tmp = substr(\$data->".$field->name.",3,strlen(\$data->".$field->name."));
			\$tmp = str_replace('.','',\$tmp);
			\$tmp = str_replace(',','.',\$tmp);
			\$data->".$field->name." = \$tmp;";
			}
		}
		$tulis .= "
			\$arrdatac = array(";
			 
		foreach($data['fields'] as $field)
		{
			if($field->type == "date")
			{
				$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d', strtotime(\$data->".$field->name.")) : NULL),";
			}
			elseif($field->type == "datetime")
			{
				$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")) : NULL),";
			}
			else
				$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
			 
			\$this->db->insert('".$tbl."', \$arrdatac);
			\$last   = \$this->db->where(\$pkey)->get('".$tbl."')->row();
			
		}
		
		\$total  = \$this->db->get('".$tbl."')->num_rows();
		
		\$json   = array(
						\"success\"   => TRUE,
						\"message\"   => 'Data berhasil disimpan',
						'total'     => \$total,
						\"data\"      => \$last
		);
		
		return \$json;
	}
	
	/**
	 * Fungsi	: delete
	 * 
	 * Untuk menghapus satu data
	 * 
	 * @param array \$data
	 * @return json
	 */
	function delete(\$data){
		\$pkey = array(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1" && $field->name != "ID")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d', strtotime(\$data->".$field->name.")),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}	
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
		
		\$this->db->where(\$pkey)->delete('".$tbl."');
		
		\$total  = \$this->db->get('".$tbl."')->num_rows();
		\$last = \$this->db->get('".$tbl."')->result();
		
		\$json   = array(
						\"success\"   => TRUE,
						\"message\"   => 'Data berhasil dihapus',
						'total'     => \$total,
						\"data\"      => \$last
		);				
		return \$json;
	}
}
?>";
		
		if ( ! write_file($path."/models/m_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Model telah digenerate...!!!<br /> Lokasi : ".$path."/models/m_".$nfile.".php </strong><br />";
			return 1;
		}
	}
	
	
	function CControllerExtjsSF($path,$nfile,$tbl,$data){
		$tulis = "/**
 * Class	: CE_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.controller.".strtolower($nfile)."',{
	extend: 'Ext.app.Controller',
	views: ['".$data['pathjs'].".v_".$nfile."','".$data['pathjs'].".v_".$nfile."_form'],
	models: ['m_".$nfile."'],
	stores: ['s_".$nfile."'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'List".$nfile."',
		selector: 'List".$nfile."'
	}, {
		ref: 'v_".$nfile."_form',
		selector: 'v_".$nfile."_form'
	}, {
		ref: 'Save".$nfile."',
		selector: 'v_".$nfile."_form #save'
	}, {
		ref: 'Create".$nfile."',
		selector: 'v_".$nfile."_form #create'
	}, {
		ref: '".strtolower($nfile)."',
		selector: '".strtolower($nfile)."'
	}],


	init: function(){
		this.control({
			'".strtolower($nfile)."': {
				'afterrender': this.".$nfile."AfterRender
			},
			'v_".$nfile."_form': {
				'afterlayout': this.".$nfile."AfterLayout
			},
			'List".$nfile."': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateList".$nfile."
			},
			'List".$nfile." button[action=create]': {
				click: this.createRecord
			},
			'List".$nfile." button[action=delete]': {
				click: this.deleteRecord
			},
			'List".$nfile." button[action=export]': {
				click: this.export2Excel
			},
			'List".$nfile." button[action=print]': {
				click: this.printRecords
			},
			'v_".$nfile."_form button[action=save]': {
				click: this.saveV_".$nfile."_form
			},
			'v_".$nfile."_form button[action=create]': {
				click: this.saveV_".$nfile."_form
			},
			'v_".$nfile."_form button[action=cancel]': {
				click: this.cancelV_".$nfile."_form
			}
		});
	},
	
	".$nfile."AfterRender: function(){
		var ".$nfile."Store = this.getList".$nfile."().getStore();
		".$nfile."Store.load();
		var view= this.getList".$nfile."().getView();
		var tip = Ext.create('Ext.tip.ToolTip', {
			target: view.el,
			delegate: view.itemSelector,
			trackMouse: true,
			renderTo: Ext.getBody(),
			listeners: {
				beforeshow: function updateTipBody(tip) {";
	
	$tulis .= "tip.update(";
	foreach($data['fields'] as $field)
	{
		$tulis .= "' ".$field->name." : ' + view.getRecord(tip.triggerElement).get('".$field->name."')+'<br />'+";
	}
	$tulis = substr($tulis,0,strlen($tulis) -1);
				$tulis .= ");}
			}
		});
	},
	
	".$nfile."AfterLayout: function(){";
		
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1" && $field->name != "ID")
			{
				$tulis .= "this.getV_".$nfile."_form().down('#".$field->name."_field').focus(false, true);";
			}
		}
		
		$tulis .= "
	},
	
	export2Excel: function(){
		var getstore = this.getList".$nfile."().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_".$nfile."/export2Excel',
			params: {data: jsonData},
			timeout: 600000,
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	createRecord: function(){
		var getList".$nfile."	= this.getList".$nfile."();
		var getV_".$nfile."_form= this.getV_".$nfile."_form(),
			form			= getV_".$nfile."_form.getForm();
		var getSave".$nfile."	= this.getSave".$nfile."();
		var getCreate".$nfile."	= this.getCreate".$nfile."();
		
		/* grid-panel */
		getList".$nfile.".setDisabled(true);
        
		/* form-panel */
		form.reset();
		";
		
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1" && $field->name != "ID")
			{
				$tulis .= "getV_".$nfile."_form.down('#".$field->name."_field').setReadOnly(false);";
			}
		}
		
		$tulis .= "
		getSave".$nfile.".setDisabled(true);
		getCreate".$nfile.".setDisabled(false);
		getV_".$nfile."_form.setDisabled(false);
		
		this.get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile))."().setActiveTab(getV_".$nfile."_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getList".$nfile."().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateList".$nfile.": function(me, record, item, index, e){
		var get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile))."		= this.get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile))."();
		var getList".$nfile."	= this.getList".$nfile."();
		var getV_".$nfile."_form= this.getV_".$nfile."_form(),
			form			= getV_".$nfile."_form.getForm();
		var getSave".$nfile."	= this.getSave".$nfile."();
		var getCreate".$nfile."	= this.getCreate".$nfile."();
		
		getSave".$nfile.".setDisabled(false);
		getCreate".$nfile.".setDisabled(true);
		";
		
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1" && $field->name != "ID")
			{
				$tulis .= "getV_".$nfile."_form.down('#".$field->name."_field').setReadOnly(true);";
			}
		}
		$tulis .= "		
		getV_".$nfile."_form.loadRecord(record);
		
		getList".$nfile.".setDisabled(true);
		getV_".$nfile."_form.setDisabled(false);
		get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile)).".setActiveTab(getV_".$nfile."_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getList".$nfile."().getStore();
		var selection = this.getList".$nfile."().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: ";
						foreach($data['fields'] as $field)
						{
							if($field->primary_key == "1")
							{
								$tulis .= "\"".$field->name."\" = \"'+selection.data.".$field->name."+'\",";
							}
						}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= "?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	printRecords: function(){
		var getstore = this.getList".$nfile."().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_".$nfile."/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/".$nfile.".html','".$nfile."_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
					break;
				default:
					Ext.MessageBox.show({
						title: 'Warning',
						msg: 'Unable to print the grid!',
						buttons: Ext.MessageBox.OK,
						animEl: 'save',
						icon: Ext.MessageBox.WARNING
					});
					break;
				}  
			}
		});
	},
	
	saveV_".$nfile."_form: function(){
		var get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile))."		= this.get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile))."();
		var getList".$nfile." 	= this.getList".$nfile."();
		var getV_".$nfile."_form= this.getV_".$nfile."_form(),
			form			= getV_".$nfile."_form.getForm(),
			values			= getV_".$nfile."_form.getValues();
		var store 			= this.getStore('s_".$nfile."');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_".$nfile."/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (";
									foreach($data['fields'] as $field)
									{
										if($field->primary_key == "1" && $field->name != "ID")
										{
											if($field->type == "date")
											{
												$tulis .= "(new Date(record.get('".$field->name."'))).format('yyyy-mm-dd') === (new Date(values.".$field->name.")).format('yyyy-mm-dd') && ";
											}
											elseif($field->type == "datetime")
											{
												$tulis .= "(new Date(record.get('".$field->name."'))).format('yyyy-mm-dd hh:nn:ss') === (new Date(values.".$field->name.")).format('yyyy-mm-dd hh:nn:ss') && ";
											}
											else
												$tulis .= "record.get('".$field->name."') === values.".$field->name." && ";
										}
									}
									$tulis = substr($tulis,0,strlen($tulis) - 4);
									$tulis .= ") {
										return true;
									}
									return false;
								}
							);
							/* getList".$nfile.".getView().select(recordIndex); */
							getList".$nfile.".getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_".$nfile."_form.setDisabled(true);
					getList".$nfile.".setDisabled(false);
					get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile)).".setActiveTab(getList".$nfile.");
				}
			});
		}
	},
	
	createV_".$nfile."_form: function(){
		var get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile))."		= this.get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile))."();
		var getList".$nfile." 	= this.getList".$nfile."();
		var getV_".$nfile."_form= this.getV_".$nfile."_form(),
			form			= getV_".$nfile."_form.getForm(),
			values			= getV_".$nfile."_form.getValues();
		var store 			= this.getStore('s_".$nfile."');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_".$nfile."/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_".$nfile."_form.setDisabled(true);
					getList".$nfile.".setDisabled(false);
					get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile)).".setActiveTab(getList".$nfile.");
				}
			});
		}
	},
	
	cancelV_".$nfile."_form: function(){
		var get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile))."		= this.get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile))."();
		var getList".$nfile."	= this.getList".$nfile."();
		var getV_".$nfile."_form= this.getV_".$nfile."_form(),
			form			= getV_".$nfile."_form.getForm();
			
		form.reset();
		getV_".$nfile."_form.setDisabled(true);
		getList".$nfile.".setDisabled(false);
		get".strtoupper(substr($nfile,0,1)).substr($nfile,1,strlen($nfile)).".setActiveTab(getList".$nfile.");
	}
	
});";
		
		if ( ! write_file("./app/controller/".strtolower($nfile).".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Controller Extjs telah digenerate...!!!<br /> Lokasi : "."./app/controller/".strtolower($nfile).".js </strong><br />";
			return 1;
		}
	}
	
	function CPrintSF($path,$nfile,$tbl,$data){
		$tulis = "<!DOCTYPE html>
<html lang=\"en\">
<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
	<title>CSS Tables</title>
	
	<style type=\"text/css\">
	@media screen{
		body {
			font: normal 11px auto \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
			color: #4f6b72;
			background: #E6EAE9;
		}
	}
	@media print{
		body {
			font: normal 11px auto \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		}
	}
	
	a {
		color: #c75f3e;
	}
	
	#mytable {
		width: 700px;
		padding: 0;
		margin: 0;
	}
	
	caption {
		padding: 0 0 5px 0;
		width: 700px;	 
		font: italic 11px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		text-align: right;
	}
	
	th {
		font: bold 11px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		color: #4f6b72;
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		border-top: 1px solid #C1DAD7;
		letter-spacing: 2px;
		text-transform: uppercase;
		text-align: left;
		padding: 6px 6px 6px 12px;
		background: #CAE8EA url(./assets/images/bg_header.jpg) no-repeat;
	}
	
	th.nobg {
		border-top: 0;
		border-left: 0;
		border-right: 1px solid #C1DAD7;
		background: none;
	}
	
	td {
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		background: #fff;
		padding: 6px 6px 6px 12px;
		color: #4f6b72;
		font: normal 12px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
	}
	
	td.alt {
		background: #F5FAFA;
		color: #797268;
	}
	
	th.spec {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #fff url(./assets/images/bullet1.gif) no-repeat;
		font: bold 10px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
	}
	
	th.specalt {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #f5fafa url(./assets/images/bullet2.gif) no-repeat;
		font: bold 10px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		color: #797268;
	}
	</style>
</head>

<body>
<table id=\"mytable\" cellspacing=\"0\" summary=\"EJS - ".$nfile."\">
<caption>Table: ".$tbl." </caption>
  <tr>
	<?php 
	\$i = 0;
	foreach (\$records[0] as \$key => \$value){
		if(\$i==0){
			echo '<th scope=\"col\" abbr=\"'.\$key.'\" class=\"nobg\">'.\$key.'</th>';
		}else {
			echo '<th scope=\"col\" abbr=\"'.\$key.'\">'.\$key.'</th>';
		}
		
		\$i++;
	}
	?>
  </tr>
  <?php 
	\$i = 0;
	for(\$i=0; \$i<(sizeof(\$records)); \$i++){
		echo '<tr>';
		\$j = 0;
		foreach (\$records[\$i] as \$key => \$value){
			if((\$j==0) && (\$i%2 == 0)){
				echo '<th scope=\"row\" abbr=\"'.\$value.'\" class=\"specalt\">'.\$value.'</th>';
			}elseif((\$j==0) && (\$i%2 != 0)){
				echo '<th scope=\"row\" abbr=\"'.\$value.'\" class=\"spec\">'.\$value.'</th>';
			}else{
				if(\$i%2 == 0){
					echo '<td class=\"alt\">'.\$value.'</td>';
				}else{
					echo '<td>'.\$value.'</td>';
				}
			}
			\$j++;
		}
		echo '<tr/>';
	}
  ?>
</table>
</body>
</html>
";
		
		if ( ! write_file($path."/views/p_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Print Preview telah digenerate...!!!<br /> Lokasi : ".$path."/views/p_".$nfile.".php </strong><br />";
			return 1;
		}
	}
	
	
	function CModelExtjsSF($path,$nfile,$tbl,$data){
		$tulis = "/**
 * Class	: CME_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.model.m_".$nfile."', {
	extend: 'Ext.data.Model',
	alias		: 'widget.".$nfile."Model',
	fields		: [";
	foreach($data['fields'] as $field)
	{
		if($field->type == "date"){
			$tulis .= "{
        name: '".$field->name."',
        type: 'date',
        dateFormat: 'Y-m-d'
    },";
		}
		elseif($field->type == "int"){
			$tulis .= "{name: '".$field->name."',type:'int'},";
		}
		else
			$tulis .= "'".$field->name."',";		
	}
	
$tulis = substr($tulis,0,strlen($tulis) -1);
$tulis .= "],";
	$i=0;
	$idProperty = "";
	foreach($data['fields'] as $field)
	{
		if($field->primary_key == "1")
		{
			$idProperty = "
	idProperty	: '".$field->name."'";
			$i++;
		}
	}
	if($i == 1)
	{
		$tulis .= $idProperty;
	}else{
		$tulis = substr($tulis,0,strlen($tulis) -1);
	}
	
$tulis .= "	
});";
		
		if ( ! write_file("./app/model/m_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Model Extjs telah digenerate...!!!<br /> Lokasi : "."./app/model/m_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	
	function CStoreExtjsSF($path,$nfile,$tbl,$data){
		$tulis = "/**
 * Class	: CSE_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.store.s_".$nfile."', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.".$nfile."Store',
	model	: 'EJS.model.m_".$nfile."',
	
	autoLoad	: false,
	autoSync	: false,
	
	storeId		: '".$nfile."',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_".$nfile."/getAll',
			create	: 'c_".$nfile."/save',
			update	: 'c_".$nfile."/save',
			destroy	: 'c_".$nfile."/delete'
		},
		actionMethods: {
			read    : 'POST',
			create	: 'POST',
			update	: 'POST',
			destroy	: 'POST'
		},
		reader: {
			type            : 'json',
			root            : 'data',
			rootProperty    : 'data',
			successProperty : 'success',
			messageProperty : 'message'
		},
		writer: {
			type            : 'json',
			writeAllFields  : true,
			root            : 'data',
			encode          : true
		},
		listeners: {
			exception: function(proxy, response, operation){
				Ext.MessageBox.show({
					title: 'REMOTE EXCEPTION',
					msg: operation.getError(),
					icon: Ext.MessageBox.ERROR,
					buttons: Ext.Msg.OK
				});
			}
		}
	},
	
	constructor: function(){
		this.callParent(arguments);
	}
	
});";
		
		if ( ! write_file("./app/store/s_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Store Extjs telah digenerate...!!!<br /> Lokasi : "."./app/store/s_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	
	function CViewExtjsSF($path,$nfile,$tbl,$data){
		$tulis = "/**
 * Class	: CVE_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.view.".$data['pathjs'].".v_".$nfile."', {
	extend: 'Ext.grid.Panel',
	requires: ['EJS.store.s_".$nfile."'],
	
	title		: '".$nfile."',
	itemId		: 'List".$nfile."',
	alias       : 'widget.List".$nfile."',
	store 		: 's_".$nfile."',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){
		var me = this;
		var plug = Ext.create('Ext.ux.ProgressBarPager');
		var docktool = Ext.create('Ext.toolbar.Paging', {
			store: 's_".$nfile."',
			dock: 'bottom',
			displayInfo: true,
            plugins: plug
		});		
		this.columns = [
			";
foreach($data['fields'] as $field)
{
	if(! $field->primary_key == "1")
	{
		if($field->type == "date" || $field->type == "datetime")
		{
			$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},";
		}
		elseif($field->type == "int")
		{
			$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."'
			},";
		}
		elseif($field->type == "decimal")
		{
			$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				}
			},";
		}
		else
		{
			if($field->max_length > 200)
			{
				$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."'
			},";
			}
			else
				$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."'
			},";
		}
	}
	else
	{
		if($field->type == "date" || $field->type == "datetime")
		{
			$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},";
		}
		elseif($field->type == "int" || $field->type == "decimal")
		{
			$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."'
			},";
		}
		else
		{
			if($field->max_length > 200)
			{
				$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."'
			},";
			}
			else
				$tulis .= "{
				header: '".$field->name."',filterable:true,
				dataIndex: '".$field->name."'
			},";
		}
			
	}		
}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= "];
		this.features = [{
				ftype: 'filters',
				autoReload: true,
				encode: false,
				local: true
			}
		];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						itemId	: 'btncreate',
						text	: 'Add',
						iconCls	: 'icon-add',
						action	: 'create'
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btndelete',
						text	: 'Delete',
						iconCls	: 'icon-remove',
						action	: 'delete',
						disabled: true
					}]
				}, '-', {
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						iconCls: 'icon-excel',
						itemId: 'export',
						text: 'Export',
						action	: 'export'
					},{
						xtype: 'splitter'
					},{
						itemId	: 'btnprint',
						text	: 'Cetak',
						iconCls	: 'icon-print',
						action	: 'print'
					}]
				}]
			}),docktool
		];
		
		docktool.add([{
				text: 'Clear Filter Data',
				handler: function () {
					me.filters.clearFilters();
				}
			}
		]);
		
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},	
	
	gridSelection: function(me, record, item, index, e, eOpts){
		//me.getSelectionModel().select(index);
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);   /*Ext.defer(this.setScrollTop, 30, this, [this.getView().scrollState.top]);*/
    }

});";
		
		if ( ! write_file("./app/view/".$data['pathjs']."/v_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>View Extjs telah digenerate...!!!<br /> Lokasi : "."./app/view/".$data['pathjs']."/v_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	
	function CFormExtjs($path,$nfile,$tbl,$data){
		$tulis = "/**
 * Class	: CFE_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.view.".$data['pathjs'].".v_".$nfile."_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_".$nfile."_form',
	
	//region:'east',
	//id: 'east-region-container',
	
	title		: 'Create/Update ".$nfile."',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/*
		 * Deklarasi variable setiap field
		 */
		 ";
		foreach($data['fields'] as $field)
		{
			if(! $field->primary_key == "1")
			{
				if($field->type == "date" || $field->type == "datetime")
				{
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Date', {
			name: '".$field->name."', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: '".str_replace('_',' ',$field->name)."'
		});";
				}
				elseif($field->type == "int")
				{
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Number', {
			name: '".$field->name."', /* column name of table */
			fieldLabel: '".str_replace('_',' ',$field->name)."'";
			if(isset($field->max_length)){
				$tulis .= ",
			maxLength: ".$field->max_length;
			}
		$tulis .= "
		});";
				}
				elseif($field->type == "decimal")
				{
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.ux.form.NumericField', {
			name: '".$field->name."', /* column name of table */
			fieldLabel: '".str_replace('_',' ',$field->name)."',
			useThousandSeparator: true,
			decimalPrecision: 2,
			alwaysDisplayDecimals: true,
			currencySymbol: 'Rp',
			thousandSeparator: '.',
			decimalSeparator: ','
		});";
				}
				else
				{
					if($field->max_length > 200)
					{
						$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.TextArea', {
			name: '".$field->name."',
			fieldLabel: '".str_replace('_',' ',$field->name)."',";
			if(isset($field->max_length)){
				$tulis .= "maxLength: ".$field->max_length;
			}
		$tulis .= "
		});";
					}
					else
					{
						$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Text', {
			name: '".$field->name."',
			fieldLabel: '".str_replace('_',' ',$field->name)."',";
			if(isset($field->max_length)){
				$tulis .= "maxLength: ".$field->max_length;
			}
		$tulis .= "
		});";
					}
				}
			}
			else
			{
				if($field->name != 'ID') {
					if($field->type == "date" || $field->type == "datetime")
					{
						$tulis .= "
			var ".$field->name."_field = Ext.create('Ext.form.field.Date', {
				itemId: '".$field->name."_field',
				name: '".$field->name."', /* column name of table */
				fieldLabel: '".str_replace('_',' ',$field->name)."',
				format: 'Y-m-d',
				allowBlank: false
			});";
					}
					elseif($field->type == "int")
					{
						$tulis .= "
			var ".$field->name."_field = Ext.create('Ext.form.field.Number', {
				itemId: '".$field->name."_field',
				name: '".$field->name."', /* column name of table */
				fieldLabel: '".str_replace('_',' ',$field->name)."',
				allowBlank: false /* jika primary_key */";
				if(isset($field->max_length)){
					$tulis .= ",
				maxLength: ".$field->max_length;
				}
			$tulis .= "});";
					}
					elseif($field->type == "decimal")
					{
						$tulis .= "
			var ".$field->name."_field = Ext.create('Ext.ux.form.NumericField', {
				itemId: '".$field->name."_field',
				name: '".$field->name."', /* column name of table */
				fieldLabel: '".str_replace('_',' ',$field->name)."',
				useThousandSeparator: true,
				decimalPrecision: 2,
				alwaysDisplayDecimals: true,
				currencySymbol: 'Rp',
				thousandSeparator: '.',
				decimalSeparator: ','
			});";
					}
					else
					{
						if($field->max_length > 200){
							$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.TextArea', {
			itemId: '".$field->name."_field',
			name: '".$field->name."',
			fieldLabel: '".str_replace('_',' ',$field->name)."',
			allowBlank: false,
			maxLength: ".(isset($field->max_length)?$field->max_length:255)."
		});";
						}
						else
							$tulis .= "
			var ".$field->name."_field = Ext.create('Ext.form.field.Text', {
				itemId: '".$field->name."_field',
				name: '".$field->name."', /* column name of table */
				fieldLabel: '".str_replace('_',' ',$field->name)."',
				allowBlank: false,
				maxLength: ".(isset($field->max_length)?$field->max_length:100)."
			});";
					}
				}
			}
		}
		 
		$tulis .= "		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [";
			foreach($data['fields'] as $field)
			{
				if($field->name != 'ID') {
					$tulis .= "".$field->name."_field,";
				}
			}
			
			$tulis = substr($tulis,0,strlen($tulis) -1);
			$tulis .= "],
			
	        buttons: [{
                iconCls: 'icon-save',
                itemId: 'save',
                text: 'Save',
                disabled: true,
                action: 'save'
            }, {
                iconCls: 'icon-add',
				itemId: 'create',
                text: 'Create',
                action: 'create'
            }, {
                iconCls: 'icon-reset',
                text: 'Cancel',
                action: 'cancel'
            }]
        });
        
        this.callParent();
    }
});";
		
		if ( ! write_file("./app/view/".$data['pathjs']."/v_".$nfile."_form.js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Form Extjs telah digenerate...!!!<br /> Lokasi : "."./app/view/".$data['pathjs']."/v_".$nfile."_form.js </strong><br />";
			return 1;
		}
	}
	
	
	function CViewportSF($path,$nfile,$tbl,$data){
		$tulis = "/**
 * Class	: VE_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author Eko Junaidi Salam
 *
 */
 Ext.define('EJS.view.".$data['pathjs'].".".strtolower($nfile)."', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.".strtolower($nfile)."',
	
	//title	: '".$nfile."',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'List".$nfile."'
			}, {
				xtype: 'v_".$nfile."_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});";
		
		if ( ! write_file("./app/view/".$data['pathjs']."/".strtolower($nfile).".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Viewport Extjs telah digenerate...!!!<br /> Lokasi : "."./app/view/".$data['pathjs']."/".strtolower($nfile).".js </strong><br />";
			return 1;
		}
	}
	
	function tulisNav(){
		$tulis = "Ext.define('EJS.view.Navigation', {
					extend: 'Ext.tree.Panel',
					xtype: 'navigation',
					title: 'List Menu',
					rootVisible: false,
					lines: false,
					useArrows: true,					
					root: {
						expanded: true,
						children: ";
		
		$files = $this->dirToArray("./app/view");
		$menu = [];
		$obj = new StdClass();
		foreach($files as $k=>$v){
			if(is_array($v)){
				$obj->text = strtoupper($k);
				$obj->expanded = true;
				$obj->children = array();
				foreach($v as $k1=>$v1){
					if(!preg_match("/^(v_)/",$v1)){
						$v1 = preg_replace("(.js)", null, $v1);
						$child = new StdClass();
						$child->id = $v1;
						$child->text = ucwords($k." ".$v1);
						$child->leaf = true;
						
						array_push($obj->children,$child);
					}
				}
				array_push($menu,$obj);
				$obj = new StdClass();
			}
		}		
						$tulis .= json_encode($menu)."
					}
				});";
		if ( ! write_file("./app/view/Navigation.js", $tulis)){
			return 0;
		}
		else
		{
			echo "<strong>Navigation Extjs telah digenerate...!!!<br /> Lokasi : "."./app/view/Navigation.js </strong><br />";
			return 1;
		}
	}
	
	function tulisApp(){
		$tulis = "Ext.define('EJS.Application', {
				name: 'EJS',

				extend: 'Ext.app.Application',

				requires: [
					'Ext.state.CookieProvider',
					'Ext.ModelManager',
					'Ext.tip.ToolTip',
					'EJS.view.Navigation',
					'Ext.ux.ProgressBarPager',
					'Ext.form.FieldContainer',
					'Ext.toolbar.Paging',
					'Ext.grid.plugin.RowEditing',
					'Ext.ux.grid.FiltersFeature',
					'Ext.data.proxy.Rest'
					/* 'Ext.ux.DataTip',
					'Ext.util.*',
					'Ext.form.*',
					'Ext.window.*' */
				],

				views: [
					/* TODO : Tambahkan hasil generator view dibawah ini... */
					";
		$files = $this->dirToArray("./app/view");
		$rw = "";
		foreach($files as $k=>$v){
			if(is_array($v)){
				foreach($v as $k1=>$v1){
					if(!preg_match("/^(v_)/",$v1)){
						$v1 = preg_replace("(.js)", null, $v1);
						$rq = "'EJS.view.".$k.".".$v1."'";
						$rw .= $rq.",";
					}
				}
			}
		}
		$rw = substr($rw,0,strlen($rw) -1);
					
		$tulis .= $rw."],

				controllers: [
					/* TODO : Tambahkan hasil generator controller di baris dibawah... */
					";
		$files = $this->dirToArray("./app/controller");
		$rw = "";
		foreach($files as $v){
			$v = preg_replace("(.js)", null, $v);
			$rq = "'".$v."'";
			$rw .= $rq.",";
		}
		$rw = substr($rw,0,strlen($rw) -1);
		$tulis .= $rw."
				],

				stores: [
					/* TODO: add stores here */
				]
			});";
		
		if ( ! write_file("./app/Application.js", $tulis)){
			return 0;
		}
		else
		{
			echo "<strong>Application Extjs telah digenerate...!!!<br /> Lokasi : "."./app/Application.js </strong><br />";
			return 1;
		}
	}
}
