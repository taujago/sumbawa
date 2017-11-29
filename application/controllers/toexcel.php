<?php 
class toexcel extends CI_Controller {
	function __construct() {
		parent::__construct(); 
		$this->load->model("excelmodel","em");
		$this->load->helper("tanggal");
	}




function index() {

		$post = $this->input->get();
		// show_array($post);

		$res = $this->em->getdatalaporan($post);


		// cari data leasing 
		$this->db->where("leasing_id",$post['leasing_id']); 
		$data_leasing = $this->db->get("m_leasing")->row();




		$this->load->library("Excel");

		$this->load->library('Excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('LaporanBLokir');

         $arr_kolom = array('a','b','c','d','e','f','g');

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);  // nomor kk  
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);  // nik 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(31); // nama 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);  // umur 
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25); // tmp lahir 
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);  // tgl lahir 
         $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);  // tgl lahir 
          $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(18);  // tgl lahir 

        $baris=1;
      	$this->excel->getActiveSheet()->mergeCells('a'.$baris.':i'.$baris);
      	$this->excel->getActiveSheet()
                ->setCellValue('A' . $baris, "LAPORAN BLOKIR KENDARAAN");

        $this->excel->getActiveSheet()->getStyle('a'.$baris.':i'.$baris)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


       // $baris++; 
       // $this->excel->getActiveSheet()->mergeCells('a'.$baris.':b'.$baris);
       // $this->excel->getActiveSheet()
       //          ->setCellValue('A' . $baris, "LEASING ")
       //          ->setCellValue('C' . $baris, ": $data_leasing->leasing_nama"); 

       // $baris++; 
       // $this->excel->getActiveSheet()->mergeCells('a'.$baris.':b'.$baris);
       // if(empty($post['tanggal_awal'])) {
       // 	$periode = "KESELURUHAN";
       // }
       // else {
       // 	$periode = flipdate($post['tanggal_awal']). " s.d ". flipdate($post['tanggal_akhir']);
       // }
       // $this->excel->getActiveSheet()
       //          ->setCellValue('A' . $baris, "PERIODE  ")
       //          ->setCellValue('C' . $baris, ": $periode"); 


       // $baris++; 
       // $this->excel->getActiveSheet()->mergeCells('a'.$baris.':b'.$baris);
       
       // if($post['id_polda']=="x") {
       // 		$nama_polda = "SEMUA POLDA " ;
       // }
       // else {
       // 		$this->db->where("id_polda",$post['id_polda']);
       // 		$polda = $this->db->get("m_polda")->row();
       // 		$nama_polda = $polda->nama_polda;
       // }

       // $this->excel->getActiveSheet()
       //          ->setCellValue('A' . $baris, "POLDA  ")
       //          ->setCellValue('C' . $baris, ": $nama_polda"); 



       // $baris++; 
       // $this->excel->getActiveSheet()->mergeCells('a'.$baris.':b'.$baris);
        
       // $permohonan = ($post['jenis_permohonan']=="B")?"KENDARAAN BARU":"KENDARAAN LAMA";

       // $this->excel->getActiveSheet()
       //          ->setCellValue('A' . $baris, "JENIS PERMOHONAN   ")
       //          ->setCellValue('C' . $baris, ": $permohonan"); 






       //  $baris +=2; 


       //  $this->excel->getActiveSheet()
       //          ->setCellValue('A' . $baris, "NO.")
       //          ->setCellValue('B' . $baris, "TGL. DAFTAR")
       //          ->setCellValue('C' . $baris, "NO. SURAT")
       //          ->setCellValue('D' . $baris, "CABANG")
       //          ->setCellValue('E' . $baris, "NO RANGKA")      
       //          ->setCellValue('F' . $baris, "NO. BPKB")
       //          ->setCellValue('G' . $baris, "NAMA PEMOHON")
       //          ->setCellValue('H' . $baris, "STATUS BLOKIR")
       //          ->setCellValue('I' . $baris, "STATUS BAYAR");

                   

       //  $baris++;

        // $nomor = 0; 
        // foreach($res->result() as $row ) : 
        // 	$nomor++;

        // 	$status_bayar = ($row->bayar==1)?"LUNAS":"BELUM BAYAR";

        // 	$this->excel->getActiveSheet()
        //         ->setCellValue('A' . $baris, "$nomor")
        //         ->setCellValue('B' . $baris, flipdate($row->daft_date) )
        //         ->setCellValue('C' . $baris, $row->no_surat)
        //         ->setCellValue('D' . $baris, $row->cabang_nama)
        //         ->setCellValue('E' . $baris, $row->no_rangka)     
        //         ->setCellValue('F' . $baris, $row->no_rangka)  
        //         ->setCellValue('G' . $baris, $row->nama_pengajuan_leasing)
        //         ->setCellValue('H' . $baris, $row->status2." ". $row->approved2 )
        //         ->setCellValue('I' . $baris, $status_bayar); 
                
        // 	$baris++;
        // endforeach;


        $filename = "LAPORAN BLOKIR KENDARAAN.XLSX";

        //exit;

        header('Content-Type: text/csv; charset=utf-8'); //mime type
        header('Content-Disposition: attachment; filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');

}

}
?>