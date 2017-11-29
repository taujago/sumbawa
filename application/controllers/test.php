<?php 
class test extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->helper("tanggal");
	}




	function index(){

		// $desa = "BAHARI I";
		$desa = "BAHARI DIRGAHAYU";

		echo nama_desa($desa); 

	}
}
?>