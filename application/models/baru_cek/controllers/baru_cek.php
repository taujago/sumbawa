<?php 
class baru_cek extends master_controller {
	function baru_cek(){
		parent::__construct();
		// $this->load->model("core_model","cm");
		$this->load->model("coremodel","cm");
		$this->load->helper("tanggal");
		$this->load->model("cek_model","dm");

	}

	function index(){



		$userdata = $this->session->userdata("userdata");
		$data_array['data'] = $this->dm->get_data($userdata['leasing_id']);
		$jenis = ($this->pilihan=="B")?"BARU":"LAMA"; 
		//show_array($data_array); exit;
		$data_array['controller'] = get_class($this);
		$data_array['jenis'] = array(1=>"NOMOR RANGKA","NOMOR BPKB");
		
		$content = $this->load->view($data_array['controller']."_view",$data_array,true);
		
		$this->set_subtitle("CEK STATUS BLOKIR KENDARAAN");
		$this->set_title("CEK STATUS BLOKIR KENDARAAN");
		$this->set_content($content);
		$this->render_baru();
	}

	 
	

function cek_status(){
	$data = $this->input->post();
	// show_array($data); exit;
	$aut_data = $this->get_auth_data();

	$url = $this->session->userdata("url");
	$data_service = array("LoginInfo"=>array(
							"LoginName" => $aut_data->service_user,
							"Salt"		=> $aut_data->service_salt,
							"AuthHash" 	=> md5($aut_data->service_salt . md5($aut_data->service_user.$aut_data->service_pass))
							),
							"Criteria"=>array(
								"Param" => $data['no_rangka'],
								"ParamKind" => $data['jenis']
							)
							 
							);
			$json_data = json_encode($data_service);
		
			// echo "$aut_data->service_user   $aut_data->service_pass <br />";

			// echo "$url <pre>". $json_data . "</pre>";  

			$ret_service = $this->execute_service($url,"ComplGetBerkasCheckPoint",$json_data);
			
			// show_array($ret_service); exit;


			if(!$ret_service){
				$ret = array("error"=>true,
					"message"=>"GAGAL MENGHUBUNGI SERVER");
			}
			else {
				if($ret_service['Result'] == false) {
					$ret = array("error"=>true,
					"message"=>"NOMOR BPKB TIDAK DITEMUKAN");
				}
				else {
					extract($ret_service);

					$ran_data = (array) $Data;
					// show_array($ran_data);

					$ret['error'] = false;
					 
					$ran_data['TglDaftar'] = todate($ran_data['TglDaftar']);
					$ran_data['TglCetakBpkb'] = todate($ran_data['TglCetakBpkb']);
					$ran_data['TglPenyerahan'] = todate($ran_data['TglPenyerahan']);
					$ran_data['TglVerifikasi'] = todate($ran_data['TglVerifikasi']);

					$ran_data['TglCetakKartuInduk'] = todate($ran_data['TglCetakKartuInduk']);
					$ran_data['TglFaktur'] = todate($ran_data['TglFaktur']);
					$ran_data['TglEntri'] = todate($ran_data['TglEntri']);
					

					$ret['message'] = "DATA DITEMUKAN";
					$ret['data'] = $ran_data;

				}
			}
			echo json_encode($ret);
}



}
?>