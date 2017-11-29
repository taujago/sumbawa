<?php
class depan_baru extends master_controller  {
	function __construct(){
		parent::__construct();
		// echo "pilihan ".$this->session->userdata("pilihan"); exit;
	}
	
	
	function index(){



		$content = $this->load->view("depan_view",array(),true);
		$this->set_subtitle("DASHBOARD");
		$this->set_title("DASHBOARD");
		$this->set_content($content);
		$this->render_baru();
	}
}
?>