<?php 
class sms_terkirim extends master_controller {
	function __construct(){
		parent::__construct();
		// $this->load->model("core_model","cm");
		$this->load->model("coremodel","cm");
		$this->load->helper("tanggal");
		$this->load->model("bd_model","dm");



	}

	function index(){ 
		// echo "<h1>Fuckoff</h1>"; exit;
		
		$userdata = $this->session->userdata("userdata");

		$data_array['controller'] = get_class($this);
		 
		$content = $this->load->view($data_array['controller']."_view",$data_array,true);

		 


		$this->set_subtitle("PESAN TERKIRIM  ");
		$this->set_title("PESAN TERKIRIM ");
		$this->set_content($content);
		$this->render_baru();
	}



function get_data(){
	 	$userdata = $this->session->userdata("userdata");
		$status = ($userdata['user_level'] == 1) ? 0:$userdata['user_level'] ;
		//$data_array['data'] = $this->dm->get_data($userdata['leasing_id'],$status);

		// show_array($_REQUEST); 

    	$draw = $_REQUEST['draw']; // get the requested page 
    	$start = $_REQUEST['start'];
        $limit = $_REQUEST['length']; // get how many rows we want to have into the grid 
        $sidx = isset($_REQUEST['order'][0]['column'])?$_REQUEST['order'][0]['column']:"2"; // get index row - i.e. user click to sort 
        $sord = isset($_REQUEST['order'][0]['dir'])?$_REQUEST['order'][0]['dir']:"desc"; // get the direction if(!$sidx) $sidx =1;  
        
        


      //  order[0][column]
        $req_param = array (
				"sort_by" => $sidx,
				"sort_direction" => $sord,
				"limit" => null 
		);     
           
        $row = $this->dm->data($req_param)->result_array();
		
        $count = count($row); 
       
        
        $req_param['limit'] = array(
                    'start' => $start,
                    'end' => $limit
        );
          
        
        $result = $this->dm->data($req_param)->result_array();
        

       
        $arr_data = array();
        $no = 0;
        foreach($result as $row) : 
        	$no++;
        	 
        	$arr_data[] = array(
				 
				 $no, 
				 $row['DestinationNumber'], 
				 $row['SendingDateTime'], 
				  $row['TextDecoded'],
				  "<a href='#' class='btn btn-primary'>BALAS </a>"

			 
				 
				);
        endforeach;

         $responce = array('draw' => $draw, // ($start==0)?1:$start,
        				  'recordsTotal' => $count, 
        				  'recordsFiltered' => $count,
        				  'data'=>$arr_data
        	);
         
        echo json_encode($responce); 
    }



	function kirim(){
		 
		$data_array['controller'] = get_class($this);
		 
		$content = $this->load->view("sms_inbox_form",$data_array,true);
		 
		 

		
		$this->set_subtitle("KIRIM PESAN ");
		$this->set_title("KIRIM PESAN ");
		$this->set_content($content);
		$this->render_baru();
	}

 
	function kirimpesan(){

		//sleep(2);

		$userdata = $this->session->userdata("userdata");
	 
		$data=$this->input->post();
		
		$this->load->library('form_validation');
  		$this->form_validation->set_rules('DestinationNumber','Nomor tujuan','required');
  		$this->form_validation->set_rules('TextDecoded','Isi pesan','required');
		
		 
		$this->form_validation->set_message('required', ' %s Harus diisi ');
		
 		$this->form_validation->set_error_delimiters('', '<br>');
		if($this->form_validation->run() == TRUE ) { 
			
			 
			 $res = $this->db->insert("outbox",$data);
			 
 			

 			if($res){
				$ret = array("error"=>false,"message"=>"PESAN BERHASIL DIPROSES");
 			}
 			else {
 				$ret = array("error"=>true,"message"=>"PESAN GAGAL DIPROSES".mysql_error());
 			}
			 
			 
		}
		else {
			$ret = array("error"=>true,"message"=>validation_errors());
		}
		
		echo json_encode($ret);
	}

function simpan_lama(){

		//sleep(2);

		$userdata = $this->session->userdata("userdata");
		//show_array($userdata); exit;
		$data=$this->input->post();
		
		$this->load->library('form_validation');
  		$this->form_validation->set_rules('no_rangka','Nomor Rangka','callback_cek_no_rangka');
  		$this->form_validation->set_rules('daft_date','Tanggal Pendafataran','required');
		
		 
		$this->form_validation->set_message('required', ' %s Harus diisi ');
		
 		$this->form_validation->set_error_delimiters('', '<br>');
		if($this->form_validation->run() == TRUE ) { 
			
			 
			$data['leasing_id'] = $userdata['leasing_id'];
			$data['jenis_permohonan'] =  $this->pilihan;		 
			$data['import']  = "1";
			$data['daft_date'] = flipdate($data['daft_date']); 			 
 			unset($data['daft_id']);
 			unset($data['mode']);
 			unset($data['saa']);






 			$data['id_polda'] = $this->session->userdata("id_polda");
 			$data['status'] = '2';

 			$this->db->where("polda_id",$data['id_polda']); 
			$this->db->where("aktif","1");

			$d_ttd  = $this->db->get("m_ttd")->row_array(); 

			$data['ttd_id'] = $d_ttd['ttd_id'];

			// show_array($data);

 			$res = $this->db->insert("t_pendaftaran",$data);
 			$this->nomor_surat($this->db->insert_id(),$data);
 			

 			if($res){
				$ret = array("error"=>false,"message"=>"PENDAFTARAN BERHASIL DISIMPAN");
 			}
 			else {
 				$ret = array("error"=>true,"message"=>"PENDAFTARAN GAGAL DISIMPAN ".mysql_error());
 			}
			 
			 
		}
		else {
			$ret = array("error"=>true,"message"=>validation_errors());
		}
		
		echo json_encode($ret);
	}


	function update(){
		$userdata = $this->session->userdata("userdata");
		//show_array($userdata); exit;
		$data=$this->input->post();
		
		$this->load->library('form_validation');
  		$this->form_validation->set_rules('no_rangka','Nomor Rangka','required');
  		$this->form_validation->set_rules('daft_date','Nomor Rangka','required');
		
		 
		$this->form_validation->set_message('required', ' %s Harus diisi ');
		
 		$this->form_validation->set_error_delimiters('', '<br>');
		if($this->form_validation->run() == TRUE ) { 
			
			 
 			$data['merk_id'] = ($data['merk_id']=="x")?NULL:$data['merk_id'];
			$data['jenis_id'] = ($data['jenis_id']=="x")?NULL:$data['jenis_id'];
			$data['warna_id'] = ($data['warna_id']=="x")?NULL:$data['warna_id'];
			$data['daft_date'] = flipdate($data['daft_date']);		// unset($data['DAFT_ID']);
 			unset($data['mode']);
 			unset($data['saa']);
 			$this->db->where("daft_id",$data['daft_id']);
 			$res = $this->db->update("t_pendaftaran",$data);
 			// $this->nomor_surat($this->db->insert_id(),$data);
 			

 			if($res){
				$ret = array("error"=>false,"message"=>"PENDAFTARAN BERHASIL DIUPDATE");
 			}
 			else {
 				$ret = array("error"=>true,"message"=>"PENDAFTARAN GAGAL DIUPATE ".mysql_error());
 			}
			 
			 
		}
		else {
			$ret = array("error"=>true,"message"=>validation_errors());
		}
		
		echo json_encode($ret);
	}



function cek_no_rangka_service($no_rangka) {

	$userdata = $this->session->userdata("userdata");
	$aut_data = $this->get_auth_data();
	

	 $url = $this->session->userdata("url");

	 $data_service = array("LoginInfo"=>array(
							"LoginName" => $aut_data->service_user,
							"Salt"		=> $aut_data->service_salt,
							"AuthHash" 	=> md5($aut_data->service_salt . md5($aut_data->service_user.$aut_data->service_pass))
							),
							"Criteria"=>array(
								"Param" => $no_rangka,
								"ParamKind" => 1
							)
							 
							);
	$json_data = json_encode($data_service);
		
	$ret_service = $this->execute_service($url,"ComplGetBerkasCheckPoint",$json_data);
	 
	if($ret_service['error'] == true){ 
		$this->form_validation->set_message('cek_no_rangka_service', 'gagal mengontak server polda');
		return false;
	}
	else {
		if($ret_service['data']['Result'] == false){
			$this->form_validation->set_message('cek_no_rangka_service', '%s TIDAK DITEMUKAN DI SERVER POLDA');
			return false;
		}
	}
	 

}



	function update_lama(){
		$userdata = $this->session->userdata("userdata");
		//show_array($userdata); exit;
		$data=$this->input->post();
		
		$this->load->library('form_validation');
  		$this->form_validation->set_rules('no_rangka','NOMOR RANGKA','callback_cek_no_rangka_service');
  		$this->form_validation->set_rules('daft_date','Tanggal daftar','required');
		
		 
		$this->form_validation->set_message('required', ' %s Harus diisi ');
		
 		$this->form_validation->set_error_delimiters('', '<br>');
		if($this->form_validation->run() == TRUE ) { 
			
			 
 		// 	$data['merk_id'] = ($data['merk_id']=="x")?NULL:$data['merk_id'];
			// $data['jenis_id'] = ($data['jenis_id']=="x")?NULL:$data['jenis_id'];
			// $data['warna_id'] = ($data['warna_id']=="x")?NULL:$data['warna_id'];
			$data['daft_date'] = flipdate($data['daft_date']);		// unset($data['DAFT_ID']);
 			$data['tgl_bpkb'] = flipdate($data['tgl_bpkb']);
 			unset($data['mode']);
 			$this->db->where("daft_id",$data['daft_id']);
 			$res = $this->db->update("t_pendaftaran",$data);
 			// $this->nomor_surat($this->db->insert_id(),$data);
 			

 			if($res){
				$ret = array("error"=>false,"message"=>"PENDAFTARAN BERHASIL DIUPDATE");
 			}
 			else {
 				$ret = array("error"=>true,"message"=>"PENDAFTARAN GAGAL DIUPATE ".mysql_error());
 			}
			 
			 
		}
		else {
			$ret = array("error"=>true,"message"=>validation_errors());
		}
		
		echo json_encode($ret);
	}

function hapus($daft_id) {
	$this->db->where("daft_id",$daft_id);
	$data = $this->db->get("t_pendaftaran")->row();

	if($data->STATUS > 0 ) {
		$ret = array("error"=>true,"message"=>"TELAH TERVERIFIKASI. TIDAK DAPAT DIHAPUS");

	}
	else { 
	$this->db->where("daft_id",$daft_id);
	$this->db->delete("t_pendaftaran");

 		$ret = array("error"=>false,"message"=>"BERHASIL DIHAPUS");


	}
	echo json_encode($ret);
}


function get_detail_kendaraan(){
	$data = $this->input->post();
	$aut_data = $this->get_auth_data();

	$url = $this->session->userdata("url");
	$data_service = array("LoginInfo"=>array(
							"LoginName" => $aut_data->service_user,
							"Salt"		=> $aut_data->service_salt,
							"AuthHash" 	=> md5($aut_data->service_salt . md5($aut_data->service_user.$aut_data->service_pass))
							),
							"NoBPKB"=> $data['no_bpkb']
							 
							);
			$json_data = json_encode($data_service);
			

			$ret_service = $this->execute_service($url,"RanMaGetDataRanmor",$json_data);
			

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
					$ret['error'] = false;
					$ran_data['no_rangka'] = $DataRanmor->NoRangka;
					$ran_data['no_mesin'] = $DataRanmor->NoMesin;
					$ran_data['no_bpkb'] = $DataRanmor->NoBPKB;
					$ran_data['tgl_bpkb'] = $DataRanmor->TglBPKB;
					$ran_data['no_polisi'] = $DataRanmor->NoPolisi;
					$ran_data['tahun_kendaraan'] = $DataRanmor->ThnBuat;
					$ran_data['jenis_nama'] = $DataRanmor->Jenis;
					$ran_data['merk_nama'] = $DataRanmor->Merk;
					$ran_data['warna_nama'] = $DataRanmor->Warna;
					$ran_data['nama_pemilik'] = $DataRanmor->NamaPemilik;
					$ran_data['pemilik_alamat'] = $DataRanmor->Alamat;
					// $ran_data['no_rangka'] = $DataRanmor->NoRangka;
					// $ran_data['no_rangka'] = $DataRanmor->NoRangka;
					// show_array($ret_service);

					// show_array($ran_data);
					$ret['message'] = $ran_data;

				}
			}
			echo json_encode($ret);
}


function get_list_daftar(){
		$data = $this->input->post();
		$userdata = $this->session->userdata("userdata");
		$data['leasing_id'] = $userdata['leasing_id'];
		// show_array($data); exit;
		$arr = $this->dm->get_list_daftar($data);
		echo json_encode($arr);
}


function get_atpm(){
	$data = $this->input->post();
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
			
			// $ret_service = $this->execute_service($url,"ComplGetDataAtpmSub",$json_data);

			$ret_service = $this->execute_service($url,"ComplGetDataAtpmSub",$json_data);
			// echo $ret_service; exit;
			// show_array($ret_service); 
			//exit;


			if($ret_service['error'] == true){
				$ret = array("error"=>true,
					"message"=>"GAGAL MENGHUBUNGI SERVER","debug"=>$ret_service);
			}
			else {
				if($ret_service['data']['Result'] == false ) {
					$ret = array("error"=>true,
					"message"=>"DATA TIDAK DITEMUKAN.<br /> Silahkan input data secara manual ","debug"=>$ret_service);
				}
				else {
					extract($ret_service);
					$ret['error'] = false;
					// echo "<hr /> data ";
					// show_array($data);
					// exit;
					$ran_data['no_rangka'] = $data['Data']->NoRangka;
					$ran_data['no_mesin'] = $data['Data']->NoMesin;
					// $ran_data['no_bpkb'] = $Data->NoBPKB;
					// $ran_data['tgl_bpkb'] = $Data->TglBPKB;
					// $ran_data['no_polisi'] = $Data->NoPolisi;
					$ran_data['tahun_kendaraan'] = $data['Data']->ThnBuat;
					$ran_data['jenis_nama'] = $data['Data']->Jenis;
					$ran_data['merk_nama'] = $data['Data']->Merk;
					// $ran_data['warna_nama'] = $data['Data']->Warna;
					$ran_data['pemilik_nama'] = $data['Data']->Pemilik;
					$ran_data['pemilik_alamat'] = $data['Data']->Alamat;
					$ran_data['pemilik_ktp'] = $data['Data']->NoIdentitas;

					
					// $ran_data['alamat_pemilik'] = $Data->Alamat;
					// $ran_data['no_rangka'] = $Data->NoRangka;
					// $ran_data['no_rangka'] = $Data->NoRangka;
					// show_array($ret_service);
					// echo "ran data";
					// show_array($ran_data);
					$ret['message'] = "DATA APM DITEMUKAN";
					$ret['data'] = $ran_data;
					//$ret['debug'] = $ret_service;

				}
			}
			echo json_encode($ret);
}


function get_data_kendaraan(){
	$data = $this->input->post();
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
			
			// $ret_service = $this->execute_service($url,"ComplGetDataAtpmSub",$json_data);

			$ret_service = $this->execute_service($url,"ComplGetBerkasCheckPoint",$json_data);
			// echo $ret_service; exit;
			// show_array($ret_service); 
			//exit;


			if($ret_service['error'] == true){
				$ret = array("error"=>true,
					"message"=>"GAGAL MENGHUBUNGI SERVER","debug"=>$ret_service);
			}
			else {
				if($ret_service['data']['Result'] == false ) {
					$ret = array("error"=>true,
					"message"=>"DATA TIDAK DITEMUKAN.<br /> Silahkan input data secara manual ","debug"=>$ret_service);
				}
				else {
					extract($ret_service);
					$ret['error'] = false;
					// echo "<hr /> data ";
					// show_array($data);
					// exit;
					$ran_data['no_rangka'] = $data['Data']->NoRangka;
					$ran_data['no_mesin'] = $data['Data']->NoMesin;
					// $ran_data['no_bpkb'] = $Data->NoBPKB;
					// $ran_data['tgl_bpkb'] = $Data->TglBPKB;
					// $ran_data['no_polisi'] = $Data->NoPolisi;
					$ran_data['tahun_kendaraan'] = $data['Data']->ThnBuat;
					$ran_data['jenis_nama'] = $data['Data']->Jenis;
					$ran_data['merk_nama'] = $data['Data']->Merk;
					$ran_data['warna_nama'] = $data['Data']->Warna;
					$ran_data['pemilik_nama'] = $data['Data']->Pemilik;
					$ran_data['pemilik_alamat'] = $data['Data']->Alamat;
					$ran_data['pemilik_ktp'] = $data['Data']->NoIdentitas;

					
					// $ran_data['alamat_pemilik'] = $Data->Alamat;
					// $ran_data['no_rangka'] = $Data->NoRangka;
					// $ran_data['no_rangka'] = $Data->NoRangka;
					// show_array($ret_service);
					// echo "ran data";
					// show_array($ran_data);
					$ret['message'] = "DATA APM DITEMUKAN";
					$ret['data'] = $ran_data;
					//$ret['debug'] = $ret_service;

				}
			}
			echo json_encode($ret);
}



}
?>