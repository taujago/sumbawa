<?php
class bd_model extends CI_Model {
	function __construct(){
		parent::__construct();
	}




 function data($param)
	{		

		// show_array($param);
		// exit;

		 extract($param);

		 
		 $userdata = $this->session->userdata("userdata");


		 $this->db->select('*')->from("inbox");
		 $res = $this->db->get();

		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');
	 
 		return $res;
	}

 


}
?>