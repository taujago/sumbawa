<?php  

class general extends CI_Controller {

	function __construct(){
		parent::__construct();
	}


	function  get_polres(){
		$id_polda = $this->input->post('id_polda');
		$this->db->where("id_polda",$id_polda);
		$this->db->order_by("nama_polres");
		$res = $this->db->get("m_polres");
		$html = "<option value='x'>== PILIH POLRES ==</option> ";
		foreach($res->result() as $row): 
			$html .= "<option value=$row->id_polres> $row->nama_polres </option> ";
		endforeach;
		echo $html;
	}


	function  get_polsek(){
		$id_polres = $this->input->post('id_polres');
		$this->db->where("id_polres",$id_polres);
		$this->db->order_by("nama_polsek");
		$res = $this->db->get("m_polsek");
		$html = "<option value='x'>== PILIH POLSEK ==</option> ";
		foreach($res->result() as $row): 
			$html .= "<option value=$row->id_polsek> $row->nama_polsek </option> ";
		endforeach;
		echo $html;
	}

	


	function get_polres_polda(){
		$id_polda = $this->input->post('id_polda');
		$id_polres = $this->input->post('id_polres');
		$this->db->where("id_polda",$id_polda);
		$this->db->order_by("nama_polres");
		$res = $this->db->get("m_polres");

		// echo $this->db->last_query(); 
		$html = "<option value='x'>== PILIH POLRES ==</option> ";
		foreach($res->result() as $row): 
			$sel = ($row->id_polres == $id_polres)?"selected":"";
			$html .= "<option value=$row->id_polres $sel> $row->nama_polres</option> ";
		endforeach;
		echo $html;
	}

	function get_polres_polsek(){
		$id_polsek = $this->input->post('id_polsek');
		$id_polres = $this->input->post('id_polres');
		$this->db->where("id_polres",$id_polres);
		$this->db->order_by("nama_polsek");
		$res = $this->db->get("m_polsek");

		// echo $this->db->last_query(); 
		$html = "<option value='x'>== PILIH POLSEK ==</option> ";
		foreach($res->result() as $row): 
			$sel = ($row->id_polsek == $id_polsek)?"selected":"";
			$html .= "<option value=$row->id_polsek $sel> $row->nama_polsek</option> ";
		endforeach;
		echo $html;
	}


function update_blokir(){

	// echo "vangkeeh.. " ; exit;



	$sql = 	"select date_format(max(daft_date),'%Y%m%d') as a, date_format(min(daft_date),'%Y%m%d') b from t_pendaftaran where blokir_type = 'bbn' and approved='0'  "; 

	// echo $sql;


	// exit;

	$res = $this->db->query($sql);



	$dtanggal = $res->row();

	// echo "tanggal a ".$dtanggal->a ; 

	if(!empty($dtanggal->a)) { // data ada 

		// echo "masuk sini kan ya ? " ;



	$aut_data = $this->get_auth_data();

	$url = $this->session->userdata("url");
	$data_service = array("LoginInfo"=>array(
							"LoginName" => $aut_data->service_user,
							"Salt"		=> $aut_data->service_salt,
							"AuthHash" 	=> md5($aut_data->service_salt . md5($aut_data->service_user.$aut_data->service_pass))
							),
							 
							"FromDate" => $dtanggal->b,
							"ToDate" => $dtanggal->a
							 
							);
	$json_data = json_encode($data_service);

	// echo json_encode($json_data);
		 

	$ret_service = $this->execute_service($url,"RanruGetReblokirEntryByDate",$json_data);
	// $ret_service = $this->execute_service($url,"RanRuGetBlokirEntryByDate",$json_data);



	// show_array($ret_service); 




		if(!$ret_service['error']){

			foreach($ret_service['data']['RequestReblokirEntryList'] as $entry) :

				// echo $entry->NoRangka;


				if(!empty($entry->NoBlokir)) {

					$this->db->where("no_rangka",$entry->NoRangka); 
					$this->db->where("blokir_type","bbn");
					$this->db->where("buka_blokir","0");
					 

					$dt['nama_pemilik'] 	= $entry->NamaPemilik; 
					$dt['alamat_pemilik'] 	= $entry->AlamatPemilik; 
					$dt['no_blokir']		= $entry->NoBlokir; 
					$dt['tgl_blokir'] 		= $this->tanggal($entry->TglBlokir);
					$dt['tgl_bpkb']  		= $this->tanggal($entry->TglBpkb);
					$dt['tgl_blokir2']  	= $this->tanggal2_tahun($entry->TglBlokir);

					$this->db->update("t_pendaftaran",$dt);


				}

				
			endforeach; 

			// $x = 000; 

			$arr_return['error'] = false;
			$arr_return['message'] = "Update status kendaraan selesai. ";



		}
		else { // koneksi ke service error
			$arr_return['error'] = true;
			$arr_return['message'] = "Koneksi ke server polda timeout";
		}





	} // data tidak ada 
	else {
		$arr_return['error'] = true;
		$arr_return['message'] = "Tidak ada data yang dicek";
	}

	echo json_encode($arr_return);


// 	atau jika tanpa Session ID
// { "LoginInfo": {
// "SID":<string>, "FromDate":<string>, /* yyyymmdd */
// "ToDate":<string>
//  yyyymmdd 
// "LoginName":<string>, "AuthHash":<string>, "Salt":<string>
// }, "FromDate":<string>, "ToDate":<string>




}




function execute_service($url,$method,$json_data) {

	// echo $json_data; exit;
	$req_url = $url."/".$method;
	// echo $req_url;  exit;
 	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $req_url);
	curl_setopt($ch,CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $json_data);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

	//execute post
	$result = curl_exec($ch);
	// echo $result;  

	$obj  = json_decode($result);
	$array = (array) $obj;

	$info = curl_getinfo($ch);

	$error = ($info['http_code']=="200")?false:true;
	// show_array($array); exit;
	curl_close($ch);
	return array("data"=>$array,"error"=>$error);
}


function get_auth_data() {
	$userdata = $this->session->userdata("userdata");
	$leasing_id = $userdata['leasing_id'];
	$id_polda = $this->session->userdata("id_polda");

	$this->db->where("id_polda",$id_polda);
	$this->db->where("leasing_id",$leasing_id);
	$data = $this->db->get("polda_leasing")->row();
	return $data;
}


}