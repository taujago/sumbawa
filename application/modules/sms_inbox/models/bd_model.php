<?php
class bd_model extends CI_Model {
	function __construct(){
		parent::__construct();
	}




 function data($param)
	{		

		// show_array($param);
		// exit;


		$sortby = array(
"UpdatedInDB",
"SenderNumber",
"ReceivingDateTime",
"TextDecoded"


		);


		 extract($param);

		 
		 $userdata = $this->session->userdata("userdata");


		 $this->db->select('*')->from("inbox");
		 

		($param['limit'] != null ? $this->db->limit($param['limit']['end'], $param['limit']['start']) : '');

		($param['sort_by'] != null) ? $this->db->order_by($sortby[$param['sort_by']], $param['sort_direction']) :'';
	 	$res = $this->db->get();
 		return $res;
	}

 


}
?>