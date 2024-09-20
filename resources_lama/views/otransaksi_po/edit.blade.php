@extends('layouts.main')

<style>
    .card {

    }

    .form-control:focus {
        background-color: #E0FFFF !important;
    }
</style>


@section('content')


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0">Purchase Order {{$header->NO_PO}}</h1>	
            </div>

        </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{($tipx=='new')? url('/po/store?golz='.$golz.'') : url('/po/update/'.$header->NO_ID.'&golz='.$golz.'' ) }}" method="POST" name ="entri" id="entri" >
  
                        @csrf

        
                        <div class="tab-content mt-3">
        
                            <div class="form-group row">
                                <div class="col-md-1">
                                    <label for="NO_PO" class="form-label">PO</label>
                                </div>
								
                                <input type="text" class="form-control NO_ID" id="NO_ID" name="NO_ID"
                                    value="{{$header->NO_ID ?? ''}}" hidden readonly>
								<input name="tipx" class="form-control tipx" id="tipx" value="{{$tipx}}" hidden >
								<input name="golz" class="form-control golz" id="golz" value="{{$golz}}" hidden >
								<input name="searchx" class="form-control searchx" id="searchx" value="{{$searchx ?? ''}}" hidden >
								
                                <div class="col-md-2">
                                    <input type="text" class="form-control NO_PO" id="NO_PO" name="NO_PO"
                                    placeholder="Masukkan Bukti#" value="{{$header->NO_PO}}" readonly>
                                </div>

								<div class="col-md-1">
                                    <label for="TGL" class="form-label">Tgl</label>
                                </div>
                                <div class="col-md-2">
 
								  <input class="form-control date" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->TGL))}}">
								
								</div>
								
								<div class="col-md-1">
                                    <label for="JTEMPO" class="form-label">Jtempo</label>
                                </div>
                                <div class="col-md-2">
									<input class="form-control date" id="JTEMPO" name="JTEMPO" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->JTEMPO))}}">
								</div>
								
								<div class="col-md-1"></div>
					
								<div class="col-md-3 input-group">

									<input type="text" hidden class="form-control CARI" id="CARI" name="CARI"
                                    placeholder="Cari Bukti#" value="" >
									<button type="button" hidden id='SEARCHX'  onclick="CariBukti()" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>

								</div> 
								
                            </div>
							
							     
 							
							<div class="form-group row">
                                <div class="col-md-1">
									<label style="color:red">*</label>									
                                    <label for="KODES" class="form-label">Suplier</label>
                                </div>
                               	<div class="col-md-2 input-group" >
                                  <input type="text" class="form-control KODES" id="KODES" name="KODES" placeholder="Masukkan Suplier"value="{{$header->KODES}}" style="text-align: left" readonly >
        						  <button type="button" class="btn btn-primary" onclick="browseSuplier()"><i class="fa fa-search"></i></button>
                                </div>
        
                                <div class="col-md-4">
                                    <input type="text" class="form-control NAMAS" id="NAMAS" name="NAMAS" placeholder="Nama" value="{{$header->NAMAS}}" readonly>
                                </div>
                            </div>
							
							
							 <div class="form-group row">
                                <div class="col-md-1">
                                    <label for="ALAMAT" class="form-label">Alamat</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control ALAMAT" id="ALAMAT" name="ALAMAT" placeholder="Masukkan Alamat" value="{{$header->ALAMAT}}" readonly>
                                </div>
        

                                <div class="col-md-2">
                                    <input type="text" class="form-control KOTA" id="KOTA" name="KOTA" placeholder="Kota" value="{{$header->KOTA}}" readonly>
                                </div>
                            </div>

        
                            <div class="form-group row">
                                <div class="col-md-1">
									<label style="color:red">*</label>									
                                    <label for="KD_BRG" class="form-label">Barang</label>
                                </div>
                               	<div class="col-md-2 input-group" >
                                  <input type="text" class="form-control KD_BRG" id="KD_BRG" name="KD_BRG" placeholder="Masukkan Barang"value="{{$header->KD_BRG}}" style="text-align: left" readonly >
        						  <button type="button" class="btn btn-primary" onclick="browseBarang()"><i class="fa fa-search"></i></button>
                                </div>
        
                                <div class="col-md-4">
                                    <input type="text" class="form-control NA_BRG" id="NA_BRG" name="NA_BRG" placeholder="Masukkan Nama Barang" value="{{$header->NA_BRG}}" readonly>
                                </div>
                            </div>
							
                            <div class="form-group row">
                                <div class="col-md-1">
                                    <label for="KG" class="form-label">Kg</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control KG" id="KG" name="KG" placeholder="Masukkan KG" value="{{ number_format( $header->KG, 0, '.', ',') }}" style="text-align: right" >
                                </div>
        
                                <div class="col-md-1">
                                    <label for="HARGA" class="form-label">Harga</label>
                                </div>
                                <!-- <div class="col-md-2">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control HARGA" id="HARGA" name="HARGA" placeholder="Masukkan Harga"
									value="{{ number_format( $header->HARGA, 5, '.', ',') }}" style="text-align: right" >
                                </div> -->
								<div class="col-md-2">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control HARGA" id="HARGA" name="HARGA" placeholder="Masukkan Harga"
									value="{{ number_format( $header->HARGA, 0, '.', ',') }}" style="text-align: right" >
                                </div>
                            </div>
							
							<div class="form-group row">

                                <div class="col-md-1">
                                    <label for="TOTAL" class="form-label">Total</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control TOTAL" id="TOTAL" name="TOTAL" placeholder="Masukkan Total" 
									value="{{ number_format( $header->TOTAL, 0, '.', ',') }}" style="text-align: right" readonly >
                                </div>
                            </div>
							
							<div class="form-group row">
                            </div>
							
							<div class="form-group row">
                                <div class="col-md-1">
									<label style="color:red">*</label>									
                                    <label for="KODEC" class="form-label">Customer</label>
                                </div>
                               	<div class="col-md-2 input-group" >
                                  <input type="text" class="form-control KODEC" id="KODEC" name="KODEC" placeholder="Masukkan customer"value="{{$header->KODEC}}" style="text-align: left" readonly >
        						  <button type="button" class="btn btn-primary" onclick="browseCustomer()"><i class="fa fa-search"></i></button>
                                </div>
        
                                <div class="col-md-4">
                                    <input type="text" class="form-control NAMAC" id="NAMAC" name="NAMAC" placeholder="Nama" value="{{$header->NAMAC}}" readonly>
                                </div>
                            </div>
							
							
							 <div class="form-group row">
                                <div class="col-md-1">
                                    <label for="ALAMAT2" class="form-label">Alamat</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control ALAMAT2" id="ALAMAT2" name="ALAMAT2" placeholder="Masukkan Alamat" value="{{$header->ALAMAT2}}" readonly>
                                </div>
        

                                <div class="col-md-2">
                                    <input type="text" class="form-control KOTA2" id="KOTA2" name="KOTA2" placeholder="Kota" value="{{$header->KOTA2}}" readonly>
                                </div>
                            </div>
							
							<div class="form-group row">
                                <div class="col-md-1">
									<label style="color:red">*</label>									
                                    <label for="NO_SO" class="form-label">SO</label>
                                </div>
                               	<div class="col-md-2 input-group" >
                                  <input type="text" class="form-control NO_SO" id="NO_SO" name="NO_SO" placeholder="Masukkan So"value="{{$header->NO_SO}}" style="text-align: left" readonly >
        						  <button type="button" class="btn btn-primary" onclick="browseSo()"><i class="fa fa-search"></i></button>
                                </div>
        
                                <div class="col-md-2">
                                    <input type="text" class="form-control TGL_SO" id="TGL_SO" name="TGL_SO" placeholder="Masukkan Tgl" value="{{$header->TGL_SO}}" readonly>
                                </div>
                            </div>
							
                            <div class="form-group row">
                                <div class="col-md-1">
                                    <label for="KD_BRG2" class="form-label">Barang</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KD_BRG2" id="KD_BRG2" name="KD_BRG2" placeholder="Masukkan Barang" value="{{$header->KD_BRG2}}" readonly>
                                </div>
								
								<div class="col-md-3">
                                    <input type="text" class="form-control NA_BRG2" id="NA_BRG2" name="NA_BRG2" placeholder="Masukkan Nama" value="{{$header->NA_BRG2}}" readonly>
                                </div>
                            </div>
							
							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="SISA" class="form-label">Sisa</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control SISA" id="SISA" name="SISA" placeholder="Masukkan Sisa"
									value="{{ number_format( $header->SISA, 2, '.', ',') }}" style="text-align: right" >
                                </div>
                            </div>
							
							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="NOTES" class="form-label">Notes</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NOTES" id="NOTES" name="NOTES" placeholder="Masukkan Notes" value="{{$header->NOTES}}" >
                                </div>
                            </div>

                         				
                        </div>


						        
						<div class="mt-3 col-md-12 form-group row">
							<div class="col-md-4">
								<button type="button"  id='TOPX'  onclick="location.href='{{url('/po/edit/?idx=' .$idx. '&tipx=top&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Top</button>
								<button type="button"  id='PREVX' onclick="location.href='{{url('/po/edit/?idx='.$header->NO_ID.'&tipx=prev&golz='.$golz.'&buktix='.$header->NO_PO )}}'" class="btn btn-outline-primary">Prev</button>
								<button type="button"  id='NEXTX' onclick="location.href='{{url('/po/edit/?idx='.$header->NO_ID.'&tipx=next&golz='.$golz.'&buktix='.$header->NO_PO )}}'" class="btn btn-outline-primary">Next</button>
								<button type="button"  id='BOTTOMX' onclick="location.href='{{url('/po/edit/?idx=' .$idx. '&tipx=bottom&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Bottom</button>
							</div>
							<div class="col-md-5">
								<button type="button"  id='NEWX' onclick="location.href='{{url('/po/edit/?idx=0&tipx=new&golz='.$golz.'' )}}'" class="btn btn-warning">New</button>
								<button type="button"  id='EDITX' onclick='hidup()' class="btn btn-secondary">Edit</button>                    
								<button type="button"  id='UNDOX' onclick="location.href='{{url('/po/edit/?idx=' .$idx. '&tipx=undo&golz='.$golz.'' )}}'" class="btn btn-info">Undo</button>  
								<button type="button" id='SAVEX' onclick='simpan()'   class="btn btn-success"<i class="fa fa-save"></i>Save</button>

							</div>
							<div class="col-md-3">
								<button type="button"  id='HAPUSX'  onclick="hapusTrans()" class="btn btn-outline-danger">Hapus</button>
								<button type="button" id='CLOSEX'  onclick="location.href='{{url('/po?golz='.$golz.'' )}}'" class="btn btn-outline-secondary">Close</button>
							</div>
						</div>
						


                    </form>
                </div>
            </div>
            <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
	
	
	
	<div class="modal fade" id="browseSuplierModal" tabindex="-1" role="dialog" aria-labelledby="browseSuplierModalLabel" aria-hidden="true">
	 <div class="modal-dialog mw-100 w-75" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseSuplierModalLabel">Cari Suplier</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bsuplier">
				<thead>
					<tr>
						<th>Suplier</th>
						<th>Nama</th>
						<th>Alamat</th>
						<th>Kota</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<div class="modal fade" id="browseCustomerModal" tabindex="-1" role="dialog" aria-labelledby="browseCustomerModalLabel" aria-hidden="true">
	 <div class="modal-dialog mw-100 w-75" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseCustomerModalLabel">Cari Customer</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bcustomer">
				<thead>
					<tr>
						<th>Customer</th>
						<th>Nama</th>
						<th>Alamat</th>
						<th>Kota</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>


	<div class="modal fade" id="browseBarangModal" tabindex="-1" role="dialog" aria-labelledby="browseBarangModalLabel" aria-hidden="true">
	 <div class="modal-dialog mw-100 w-75" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseBarangModalLabel">Cari Item</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bbarang">
				<thead>
					<tr>
						<th>Item</th>
						<th>Nama</th>
						<th>Satuan</th>						
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	  
	  
	</div><div class="modal fade" id="browseSoModal" tabindex="-1" role="dialog" aria-labelledby="browseSoModalLabel" aria-hidden="true">
	 <div class="modal-dialog mw-100 w-75" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseSoModalLabel">Cari Item</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bso">
				<thead>
					<tr>
						<th>SO</th>
						<th>Tgl SO</th>
						<th>Barang</th>
						<th>Nama</th>
						<th>Sisa</th>							
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	


	
@endsection

@section('footer-scripts')
<!-- TAMBAH 1 -->

<script src="{{ asset('js/autoNumerics/autoNumeric.min.js') }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="{{asset('foxie_js_css/bootstrap.bundle.min.js')}}"></script>

<script>


    function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

// TAMBAH HITUNG
	$(document).ready(function() {

		$tipx = $('#tipx').val();
		$searchx = $('#CARI').val();
		
		
        if ( $tipx == 'new' )
		{
			 baru();			
		}

        if ( $tipx != 'new' )
		{
			 ganti();			
		}    
		
	
	
		$("#KG").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		// $("#HARGA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.9999'});
		$("#HARGA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#TOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});


		$('body').on('click', '.btn-delete', function() {
			var val = $(this).parents("tr").remove();
			idrow--;
			nomor();
		});
		
		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		})
		
		hitung=function() {



			var KGX = parseFloat($('#KG').val().replace(/,/g, ''));
			var HARGAX = parseFloat($('#HARGA').val().replace(/,/g, ''));
		
            var TOTALX = HARGAX * KGX;
			$('#TOTAL').val(numberWithCommas(TOTALX));	
		    $("#TOTAL").autoNumeric('update');	
		
		
		}	
		
///////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		
		
		//CHOOSE Supplier
 		var dTableBSuplier;
		loadDataBSuplier = function(){
			$.ajax(
			{
				type: 'GET',    
				url: '{{url('sup/browse')}}',
				data: {
					'GOL': "{{$golz}}",
				},
				success: function( response )
				{
			
					resp = response;
					if(dTableBSuplier){
						dTableBSuplier.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBSuplier.row.add([
							'<a href="javascript:void(0);" onclick="chooseSuplier(\''+resp[i].KODES+'\',  \''+resp[i].NAMAS+'\', \''+resp[i].ALAMAT+'\',  \''+resp[i].KOTA+'\')">'+resp[i].KODES+'</a>',
							resp[i].NAMAS,
							resp[i].ALAMAT,
							resp[i].KOTA,
						]);
					}
					dTableBSuplier.draw();
				}
			});
		}
		
		dTableBSuplier = $("#table-bsuplier").DataTable({
			
		});
		
		browseSuplier = function(){
			loadDataBSuplier();
			$("#browseSuplierModal").modal("show");
		}
		
		chooseSuplier = function(KODES,NAMAS, ALAMAT, KOTA){
			$("#KODES").val(KODES);
			$("#NAMAS").val(NAMAS);
			$("#ALAMAT").val(ALAMAT);
			$("#KOTA").val(KOTA);
			$("#browseSuplierModal").modal("hide");
		}
		
		$("#KODES").keypress(function(e){

			if(e.keyCode == 46){
				e.preventDefault();
				browseSuplier();
			}
		}); 
		
		
		//////////////////////////////////////////////////////////////////////////////////////////////////
		
		//CHOOSE Customer
 		var dTableBCustomer;
		loadDataBCustomer = function(){
			$.ajax(
			{
				type: 'GET',    
				url: '{{url('cust/browse')}}',
				data: {
					'GOL': "{{$golz}}",
				},
				success: function( response )
				{
			
					resp = response;
					if(dTableBCustomer){
						dTableBCustomer.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBCustomer.row.add([
							'<a href="javascript:void(0);" onclick="chooseCustomer(\''+resp[i].KODEC+'\',  \''+resp[i].NAMAC+'\', \''+resp[i].ALAMAT+'\',  \''+resp[i].KOTA+'\')">'+resp[i].KODEC+'</a>',
							resp[i].NAMAC,
							resp[i].ALAMAT,
							resp[i].KOTA,
						]);
					}
					dTableBCustomer.draw();
				}
			});
		}
		
		dTableBCustomer = $("#table-bcustomer").DataTable({
			
		});
		
		browseCustomer = function(){
			loadDataBCustomer();
			$("#browseCustomerModal").modal("show");
		}
		
		chooseCustomer = function(KODEC,NAMAC, ALAMAT, KOTA){
			$("#KODEC").val(KODEC);
			$("#NAMAC").val(NAMAC);
			$("#ALAMAT2").val(ALAMAT);
			$("#KOTA2").val(KOTA);
			$("#browseCustomerModal").modal("hide");
		}
		
		$("#KODEC").keypress(function(e){

			if(e.keyCode == 46){
				e.preventDefault();
				browseCustomer();
			}
		}); 
		
		
		//////////////////////////////////////////////////////////////////////////////////////////////////
		



		
//////////////////////////////////////////////////////////////////////
		
 		var dTableBBarang;
		var rowidBarang;
		loadDataBBarang = function(){
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('brg/browse')}}",
				data: {
					'GOL': "{{$golz}}",
				},
				success: function( response )
				{
					resp = response;
					if(dTableBBarang){
						dTableBBarang.clear();
					}
					for(i=0; i<resp.length; i++){
						
					dTableBBarang.row.add([
							'<a href="javascript:void(0);" onclick="chooseBarang(\''+resp[i].KD_BRG+'\',  \''+resp[i].NA_BRG+'\',   \''+resp[i].SATUAN+'\')">'+resp[i].KD_BRG+'</a>',
							resp[i].NA_BRG,
							resp[i].SATUAN,
						]);
						
					}
					dTableBBarang.draw();
				}
			});
		}
		
		dTableBBarang = $("#table-bbarang").DataTable({
			
		});
		
		browseBarang = function(){
			loadDataBBarang();
			$("#browseBarangModal").modal("show");
		}
		
		chooseBarang = function(KD_BRG,NA_BRG){
			$("#KD_BRG").val(KD_BRG);
			$("#NA_BRG").val(NA_BRG);			
			$("#browseBarangModal").modal("hide");
		}
		
		
		$("#KD_BRG").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseBarang();
			}
		}); 

//////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
		
 		var dTableBSo;
		var rowidSo;
		loadDataBSo = function(){
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('so/browse')}}",
				data: {
					'GOL': "{{$golz}}",
				},
				success: function( response )
				{
					resp = response;
					if(dTableBSo){
						dTableBSo.clear();
					}
					for(i=0; i<resp.length; i++){
						
					dTableBSo.row.add([
							'<a href="javascript:void(0);" onclick="chooseSo(\''+resp[i].NO_PO+'\',  \''+resp[i].TGL+'\',   \''+resp[i].KD_BRG+'\',  \''+resp[i].NA_BRG+'\',   \''+resp[i].SISA+'\')">'+resp[i].NO_PO+'</a>',
							resp[i].TGL,
							resp[i].KD_BRG,
							resp[i].NA_BRG,
							resp[i].SISA,
						]);
						
					}
					dTableBSo.draw();
				}
			});
		}
		
		dTableBSo = $("#table-bso").DataTable({
			
		});
		
		browseSo = function(){
			loadDataBSo();
			$("#browseSoModal").modal("show");
		}
		
		chooseSo = function(NO_PO,TGL,KD_BRG,NA_BRG,SISA){
			$("#NO_SO").val(NO_PO);
			$("#TGL_SO").val(TGL);
			$("#KD_BRG2").val(KD_BRG);
			$("#NA_BRG2").val(NA_BRG);		
			$("#SISA").val(SISA);					
			$("#browseSoModal").modal("hide");
		}
		
		
		$("#NO_SO").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseSo();
			}
		}); 

//////////////////////////////////////////////
	





	});




//////////////////////////////////////////////////////////////////



 function simpan() {
	 
		hitung();
		
		var tgl = $('#TGL').val();
		var bulanPer = {{session()->get('periode')['bulan']}};
		var tahunPer = {{session()->get('periode')['tahun']}};
		
        var check = '0';
		
		
			if ( $('#KODES').val()=='' ) 
            {			
			    check = '1';
				alert("Suplier# Harus diisi.");
			}
			
			if ( $('#KD_BRG').val()=='' ) 
            {			
			    check = '1';
				alert("Barang# Harus diisi.");
			}
			
			if ( tgl.substring(3,5) != bulanPer ) 
			{
				
				check = '1';
				alert("Bulan tidak sama dengan Periode");
			}	
			

			if ( tgl.substring(tgl.length-4) != tahunPer )
			{
				check = '1';
				alert("Tahun tidak sama dengan Periode");
				
		    }	 

        
		(check==0) ? document.getElementById("entri").submit() : alert('Masih ada kesalahan');

			
	      
	}



	function baru() {
		
		 kosong();
		 hidup();
	
	}
	
	function ganti() {
		
		 mati();
		 //hidup();
	
	}
	
	function batal() {
		
		// alert($header[0]->NO_PO);
		
		 //$('#NO_PO').val($header[0]->NO_PO);	
		 mati();
	
	}
	
 

	
	
	function hidup() {

		
		$("#TOPX").attr("disabled", true);
	    $("#PREVX").attr("disabled", true);
	    $("#NEXTX").attr("disabled", true);
	    $("#BOTTOMX").attr("disabled", true);

	    $("#NEWX").attr("disabled", true);
	    $("#EDITX").attr("disabled", true);
	    $("#UNDOX").attr("disabled", false);
	    $("#SAVEX").attr("disabled", false);
		
	    $("#HAPUSX").attr("disabled", true);
	    //$("#CLOSEX").attr("disabled", true);


		$("#CARI").attr("readonly", true);	
	    $("#SEARCHX").attr("disabled", true);
		
	    $("#PLUSX").attr("hidden", false)
		   
			$("#NO_PO").attr("readonly", true);		   
			$("#TGL").attr("readonly", false);
			$("#KODES").attr("readonly", true);
			$("#NAMAS").attr("readonly", true);
			$("#ALAMAT").attr("readonly", true);
			$("#KOTA").attr("readonly", true);
			$("#KD_BRG").attr("readonly", true);
			$("#NA_BRG").attr("readonly", true);
			$("#KG").attr("readonly", false);
			$("#HARGA").attr("readonly", false);
			$("#TOTAL").attr("readonly", true);
								
			$("#NOTES").attr("readonly", false);
			
			$("#KODEC").attr("readonly", true);
			$("#NAMAC").attr("readonly", true);
			$("#ALAMAT2").attr("readonly", true);
			$("#KOTA2").attr("readonly", true);
			$("#NO_SO").attr("readonly", true);
			$("#TGL_SO").attr("readonly", true);
			$("#KD_BRG2").attr("readonly", false);
			$("#NA_BRG2").attr("readonly", false);
			$("#SISA").attr("readonly", false);
		

		
	}


	function mati() {

		
	    $("#TOPX").attr("disabled", false);
	    $("#PREVX").attr("disabled", false);
	    $("#NEXTX").attr("disabled", false);
	    $("#BOTTOMX").attr("disabled", false);


	    $("#NEWX").attr("disabled", false);
	    $("#EDITX").attr("disabled", false);
	    $("#UNDOX").attr("disabled", true);
	    $("#SAVEX").attr("disabled", true);
	    $("#HAPUSX").attr("disabled", false);
	    $("#CLOSEX").attr("disabled", false);

		$("#CARI").attr("readonly", false);	
	    $("#SEARCHX").attr("disabled", false);
		
	    $("#PLUSX").attr("hidden", true)
		
	    $(".NO_PO").attr("readonly", true);	
		
		$("#TGL").attr("readonly", true);
		$("#KODES").attr("readonly", true);
		$("#NAMAS").attr("readonly", true);
		$("#ALAMAT").attr("readonly", true);
		$("#KOTA").attr("readonly", true);		
		$("#KD_BRG").attr("readonly", true);
		$("#NA_BRG").attr("readonly", true);		
		$("#KG").attr("readonly", true);
		$("#HARGA").attr("readonly", true);		
		$("#TOTAL").attr("readonly", true);		
				
		$("#NOTES").attr("readonly", true);
		
		$("#KODEC").attr("readonly", true);
		$("#NAMAC").attr("readonly", true);
		$("#ALAMAT2").attr("readonly", true);
		$("#KOTA2").attr("readonly", true);		
		$("#NO_SO").attr("readonly", true);
		$("#TGL_SO").attr("readonly", true);		
		$("#KD_BRG2").attr("readonly", true);		
		$("#NA_BRG2").attr("readonly", true);
		$("#SISA").attr("readonly", true);	
		
	}


	function kosong() {
				
		 $('#NO_PO').val("+");	
	//	 $('#TGL').val("");	
		 $('#KODES').val("");	
		 $('#NAMAS').val("");	
		 $('#ALAMAT').val("");	
		 $('#KOTA').val("");
		 $('#KD_BRG').val("");	
		 $('#NA_BRG').val("");
		 $('#KG').val("0.00");
		 $('#HARGA').val("0.00");	
		 $('#TOTAL').val("0.00");	
		 $('#SISA').val("0.00");
		 $('#NOTES').val("");	
		 
		 $('#KODEC').val("");	
		 $('#NAMAC').val("");	
		 $('#ALAMAT2').val("");	
		 $('#KOTA2').val("");
		 $('#NO_SO').val("");	
		 $('#TGL_SO').val("");	
		 $('#KD_BRG2').val("");	
		 $('#NA_BRG2').val("");

		$golz = $('#golz').val();
		
        if ( $golz == 'Y' )
		{
		   $('#KD_BRG').val("KDL0001");	
		   $('#NA_BRG').val("Kedelai");			
		}


		
	}
	
	function hapusTrans() {
		let text = "Hapus Transaksi "+$('#NO_PO').val()+"?";
		if (confirm(text) == true) 
		{
			window.location ="{{url('/po/delete/'.$header->NO_ID .'/?golz='.$golz.'' )}}";
			//return true;
		} 
		return false;
	}
	

	function CariBukti() {
		
		var golz = "{{ $golz }}";
		var cari = $("#CARI").val();
		var loc = "{{ url('/po/edit/') }}" + '?idx={{ $header->NO_ID}}&tipx=search&golz=' + encodeURIComponent(goz) + '&buktix=' +encodeURIComponent(cari);
		window.location = loc;
		
	}

  
</script>


<script src="autonumeric.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
<script src="https://unpkg.com/autonumeric"></script>

@endsection
