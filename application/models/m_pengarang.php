<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_pengarang
 * 
 * Table	: pengarang
 *  
 * @author Eko Junaidi Salam
 *
 */
class M_pengarang extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data
	 * 
	 * @param number $start
	 * @param number $page
	 * @param number $limit
	 * @return json
	 */
	function getAll($start, $page, $limit){
		//if($filters == null){
			$query  = $this->db->limit($limit, $start)->order_by('id', 'DESC')->get('pengarang')->result();
			$total  = $this->db->get('pengarang')->num_rows();
			
			$data   = array();
			foreach($query as $result){
				$data[] = $result;
			}
			
			$json	= array(
							'success'   => TRUE,
							'message'   => "Loaded data",
							'total'     => $total,
							'data'      => $data
			);
			
			return $json;
		/*}
		else
		{
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = " 0=0 ";
			$qs = '';

			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];
					if ($encoded) {
						$field = isset($filter->field) ? $filter->field : null;
						$value = isset($filter->value) ? $filter->value : null;
						if($field == null || $value == null){
							$query  = $this->db->limit($limit, $start)->order_by('id', 'ASC')->get('pengarang')->result();
							$total  = $this->db->get('pengarang')->num_rows();
							
							$data   = array();
							foreach($query as $result){
								$data[] = $result;
							}
							
							$json	= array(
								'success'   => TRUE,
								'message'   => "Loaded data",
								'total'     => $total,
								'data'      => $data
							);
							
							return $json;
						}
						$field = $filter->field;
						$value = $filter->value;
						$compare = isset($filter->comparison) ? $filter->comparison : null;
						$filterType = $filter->type;
					} else {
						$field = $filter['field'];
						$value = $filter['data']['value'];
						$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
						$filterType = $filter['data']['type'];
					}

					switch($filterType){
						case 'string' : $qs .= " AND ".$field." LIKE '%".$value."%'"; Break;
						case 'list' :
							if (strstr($value,',')){
								$fi = explode(',',$value);
								for ($q=0;$q<count($fi);$q++){
									$fi[$q] = "'".$fi[$q]."'";
								}
								$value = implode(',',$fi);
								$qs .= " AND ".$field." IN (".$value.")";
							}else{
								$qs .= " AND ".$field." = '".$value."'";
							}
						Break;
						case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
						case 'numeric' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
								case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
								case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
							}
						Break;
						case 'date' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
							}
						Break;
						case 'datetime' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
							}
						Break;
					}
				}
				$where .= $qs;
			}
			
			$sql = "SELECT * FROM pengarang
			WHERE ".$where;
			$sql .= " ORDER BY id ASC";
			$sql .= " LIMIT ".$start.",".$limit;
			$query = $this->db->query($sql)->result();
			
			$total  = $this->db->query("SELECT count(id) as total
			FROM pengarang
			WHERE ".$where)->result();
			
			$data   = array();
			foreach($query as $result){
				$data[] = $result;
			}
			
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total[0]->total,
				'data'      => $data
			);
			
			return $json;
		}*/
	}
	
	/**
	 * Fungsi	: save
	 * 
	 * Untuk menambah data baru atau mengubah data lama
	 * 
	 * @param array $data
	 * @return json
	 */
	function save($data){
		$last   = NULL;
		
		$pkey = array('id'=>$data->id);
		
		if($this->db->get_where('pengarang', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array('nama'=>$data->nama,'email'=>$data->email,'telp'=>$data->telp,'foto'=>$data->foto);
			 
			$this->db->where($pkey)->update('pengarang', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array('id'=>$data->id,'nama'=>$data->nama,'email'=>$data->email,'telp'=>$data->telp,'foto'=>$data->foto);
			 
			$this->db->insert('pengarang', $arrdatac);
			$last   = $this->db->where($pkey)->get('pengarang')->row();
			
		}
		
		$total  = $this->db->get('pengarang')->num_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						'total'     => $total,
						"data"      => $last
		);
		
		return $json;
	}
	
	/**
	 * Fungsi	: delete
	 * 
	 * Untuk menghapus satu data
	 * 
	 * @param array $data
	 * @return json
	 */
	function delete($data){
		$pkey = array('id'=>$data->id);
		
		$this->db->where($pkey)->delete('pengarang');
		
		$total  = $this->db->get('pengarang')->num_rows();
		$last = $this->db->get('pengarang')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						'total'     => $total,
						"data"      => $last
		);				
		return $json;
	}
}
?>