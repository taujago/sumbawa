<?php 
class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
		//$this->load->helper("serviceurl");
		
	}
	
	function index(){
		$this->load->view("login_view");
	}
	
	
	function logout(){
		$this->session->unset_userdata("login",true);
		redirect("login");
	}
	
	function ceklogin(){

		$data = $this->input->post();
		$this->db->select("*");
		
		$this->db->where("user_name",$data['username']);
		$this->db->where("user_password",$data['password']);
		$this->db->from("t_user u"); 
	 
		$res = $this->db->get();
		 

		if($res->num_rows() == 1 ) {
			 


			$userdata = $res->row_array();
			$this->session->set_userdata('login',true);
			 
			$this->session->set_userdata("userdata",$userdata);
			$_SESSION['userdata'] = $userdata;
			$ret = array("error"=>false,"message"=>"Login berhasil");
		}
		else {
			$ret = array("error"=>true,"message"=>"Login gagal");
		}

		echo json_encode($ret);

 
		 
		 
	}
}

?>