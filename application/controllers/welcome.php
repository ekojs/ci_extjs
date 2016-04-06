<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	// private $username;
	// private $gid;

	public function __construct(){
		parent::__construct();
		// if($this->auth->is_logged_in() == false){
			// redirect(base_url(),'refresh');
		// }
		// $this->gid = $this->session->userdata('group_id');
		//$this->username = $this->session->userdata('user_name');
	}

	public function index(){
		$this->load->view('welcome');
	}

	public function gce(){
		$this->load->view('welcome_message');
	}
	
	function generate() {
		$tipe = intval($this->input->post("type"));
		$pathjs = $this->input->post("pathjs");
		$table = $this->input->post("table");
		if($tipe == '0'){
			$this->GenSG($pathjs, $table);
		}
		else {
			$this->GenSGSF($pathjs, $table);
		}
		
	}
	
	private function GenSG($pathjs="", $table="")
	{
		$path = './application'; //ini adalah path application CI
		$nfile = $table; // ini adalah namafile bisa berdasar nama tabel
		//$tbl = 'cutitahunan';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data($table);
		$data['pathjs'] = $pathjs;		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		$this->egen->SingleGrid($path,$nfile,$table,$data);  // ini adalah eksekusi Utama Generator
	}
	
	private function GenSGSF($pathjs="", $table="")
	{
		$path = './application'; //ini adalah path application CI
		$nfile = $table; // ini adalah namafile bisa berdasar nama tabel
		//$tbl = 'cutitahunan';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data($table);
		$data['pathjs'] = $pathjs;		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		$this->egen->SingleGridSF($path,$nfile,$table,$data);  // ini adalah eksekusi Utama Generator
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */