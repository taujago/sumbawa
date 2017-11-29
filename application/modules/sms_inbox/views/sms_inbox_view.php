 <link href="<?php echo base_url("assets") ?>/css/jquery.dataTables.css" rel="stylesheet">

 
<script src="<?php echo base_url("assets") ?>/js/jquery.dataTables.min.js"></script>
 <link href="<?php echo base_url("assets") ?>/css/eblokir.css" rel="stylesheet">

 
<style>

.green {
color:green;
 }
.red {
	color:red;
 	
}
</style>
 
 
<br><br>



<div class="row">
  <div id="salah" class="col-lg-12" style="display:none">
            <div class="alert alert-danger" role="alert" id="message">
              
            </div>
        </div>
    </div>
    
  <div class="row">
  <div id="benar" class="col-lg-12" style="display:none">
            <div class="alert alert-success" role="alert" id="message2">
              
            </div>
        </div>
    </div> 

 
<table id="leasing" class="display" cellspacing="0" width="100%">
<thead>
	<tr style="background-color:#CCC">
        <th width="2%">NO. </th>
        <th width="5%">PENGIRIM</th>
        <th width="7%">WAKTU PENGIRIMAN</th>
        <th width="50%">ISI PESAN</th>  
        <th width="5%">BALAS</th>
        
    </tr>
	
</thead>
 
</table>
<?php $this->load->view("sms_inbox_view_js") ?>
