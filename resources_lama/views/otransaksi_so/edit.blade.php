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
    <div class="content-header">
    </div>

    <div class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-body">
  
					<form action="{{($tipx=='new')? url('/so/store?golz='.$golz.'') : url('/so/update/'.$header->NO_ID.'&golz='.$golz.'' ) }}" method="POST" name ="entri" id="entri" >
  
                        @csrf

                        <div class="tab-content mt-3">
        
                            <div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="NO_SO" class="form-label">Bukti#</label>
                                </div>
								
                                <input type="text" class="form-control NO_ID" id="NO_ID" name="NO_ID"
                                    value="{{$header->NO_ID ?? ''}}" hidden readonly>
								<input name="tipx" class="form-control tipx" id="tipx" value="{{$tipx}}" hidden >
								<input name="golz" class="form-control golz" id="golz" value="{{$golz}}" hidden >
								<input name="searchx" class="form-control searchx" id="searchx" value="{{$searchx ?? ''}}" hidden >

								
                                <div class="col-md-2">
                                    <input type="text" class="form-control NO_SO" id="NO_SO" name="NO_SO"
                                    placeholder="Masukkan Nomor Bukti" value="{{$header->NO_SO}}" readonly style="width:140px">
                                </div>
								
								
                                <div class="col-md-1"  align="left">
                                    <label for="TGL" class="form-label">Tanggal</label>
                                </div>
                                <div class="col-md-2">
								  <input class="form-control date" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->TGL))}}" style="width:140px">
                                </div>
								
								<div class="col-md-1"  align="right">
                                    <label for="JTEMPO" class="form-label">Jtempo</label>
                                </div>
                                <div class="col-md-2">
								  <input class="form-control date" id="JTEMPO" name="JTEMPO" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->JTEMPO))}}" style="width:140px">
                                </div>
								
								<div class="col-md-3"></div>
					
								<div class="col-md-3 input-group">

									<input type="text" hidden class="form-control CARI" id="CARI" name="CARI"
                                    placeholder="Cari Bukti#" value="" >
									<button type="button" hidden id='SEARCHX'  onclick="CariBukti()" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>

								</div> 
								
                            </div>
 							
							<div class="form-group row">
                                <div class="col-md-1" align="right">
									<label style="color:red;font-size:20px">* </label>
                                    <label for="KODEC" class="form-label">Cust#</label>
                                </div>
                               	<div class="col-md-2 input-group" >
                                  <input type="text" class="form-control KODEC" id="KODEC" name="KODEC" placeholder="Masukkan Customer"value="{{$header->KODEC}}" style="text-align: left" readonly >
        						  <button type="button" class="btn btn-primary" onclick="browseCustomer()"><i class="fa fa-search"></i></button>
         							
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control NAMAC" id="NAMAC" name="NAMAC" placeholder="Nama Customer" value="{{$header->NAMAC}}" readonly>
                                </div>
								
								<div class="col-md-1" align="right">
									<label style="color:red;font-size:20px">* </label>
                                    <label for="KD_BRG" class="form-label">Barang#</label>
                                </div>
                               	<div class="col-md-2 input-group" >
                                  <input type="text" class="form-control KD_BRG" id="KD_BRG" name="KD_BRG" placeholder="Masukkan Barang"value="{{$header->KD_BRG}}" style="text-align: left" readonly >
        						  <button type="button" class="btn btn-primary" onclick="browseBarang()"><i class="fa fa-search"></i></button>
         							
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control NA_BRG" id="NA_BRG" name="NA_BRG" placeholder="Masukkan Nama Barang" value="{{$header->NA_BRG}}" readonly>
                                </div>
                            </div>
							
							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="ALAMAT" class="form-label">Alamat</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control ALAMAT" id="ALAMAT" name="ALAMAT" placeholder="Alamat Customer" value="{{$header->ALAMAT}}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KOTA" id="KOTA" name="KOTA" placeholder="Kota Customer"  value="{{$header->KOTA}}" readonly>
                                </div>
								
								<div class="col-md-1" align="right">
                                    <label for="KG" class="form-label">Kg</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control KG" id="KG" name="KG" placeholder="Masukkan KG" value="{{ number_format( $header->KG, 2, '.', ',') }}" style="text-align: right; width:140px">
                                </div>
                            </div>
							
							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="NO_ORDER" class="form-label">Order</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control NO_ORDER" id="NO_ORDER" name="NO_ORDER" placeholder="" value="{{$header->NO_ORDER}}" >
                                </div>
								
								<div class="col-md-1" align="right">
                                    <label for="PO" class="form-label">PO</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control PO" id="PO" name="PO" placeholder="" value="{{$header->PO}}" >
                                </div>
								
                                <div class="col-md-1" align="right">
                                    <label for="HARGA" class="form-label">Harga</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control HARGA" id="HARGA" name="HARGA" placeholder="Masukkan Harga"
									value="{{ number_format( $header->HARGA, 2, '.', ',') }}" style="text-align: right; width:140px">
                                </div>
                            </div>
							
							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="NOTES" class="form-label">Notes</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NOTES" id="NOTES" name="NOTES" placeholder="Masukkan Notes" value="{{$header->NOTES}}" >
                                </div>
								
                                <div class="col-md-2" align="right">
                                    <label for="TOTAL" class="form-label">Total</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control TOTAL" id="TOTAL" name="TOTAL" placeholder="Masukkan Total" 
									value="{{ number_format( $header->TOTAL, 2, '.', ',') }}" style="text-align: right; width:140px" readonly>
                                </div>
                            </div>
							
                        </div>


						        
						<div class="mt-3 col-md-12 form-group row">
							<div class="col-md-4">
								<button type="button" id='TOPX'  onclick="location.href='{{url('/so/edit/?idx=' .$idx. '&tipx=top&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Top</button>
								<button type="button" id='PREVX' onclick="location.href='{{url('/so/edit/?idx='.$header->NO_ID.'&tipx=prev&golz='.$golz.'&buktix='.$header->NO_SO )}}'" class="btn btn-outline-primary">Prev</button>
								<button type="button" id='NEXTX' onclick="location.href='{{url('/so/edit/?idx='.$header->NO_ID.'&tipx=next&golz='.$golz.'&buktix='.$header->NO_SO )}}'" class="btn btn-outline-primary">Next</button>
								<button type="button" id='BOTTOMX' onclick="location.href='{{url('/so/edit/?idx=' .$idx. '&tipx=bottom&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Bottom</button>
							</div>
							<div class="col-md-5">
								<button type="button" id='NEWX' onclick="location.href='{{url('/so/edit/?idx=0&tipx=new&golz='.$golz.'' )}}'" class="btn btn-warning">New</button>
								<button type="button" id='EDITX' onclick='hidup()' class="btn btn-secondary">Edit</button>                    
								<button type="button" id='UNDOX' onclick="location.href='{{url('/so/edit/?idx=' .$idx. '&tipx=undo&golz='.$golz.'' )}}'" class="btn btn-info">Undo</button>  
								<button type="button" id='SAVEX' onclick='simpan()'   class="btn btn-success" class="fa fa-save"></i>Save</button>

							</div>
							<div class="col-md-3">
								<button type="button" id='HAPUSX'  onclick="hapusTrans()" class="btn btn-outline-danger">Hapus</button>
								<button type="button" id='CLOSEX'  onclick="location.href='{{url('/so?golz='.$golz.'' )}}'" class="btn btn-outline-secondary">Close</button>
							</div>
						</div>
						
						

                    </form>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
	
	
	<div class="modal fade" id="browseCustomerModal" tabindex="-1" role="dialog" aria-labelledby="browseCustomerModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
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
						<th>Customer#</th>
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
	  <div class="modal-dialog" role="document">
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
						<th>Item#</th>
						<th>Nama</th>
	
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
<script src="{{ asset('js/autoNumerics/autoNumeric.min.js') }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="{{asset('foxie_js_css/bootstrap.bundle.min.js')}}"></script>

<script>
    function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

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
		
 		var dTableBCustomer;
		loadDataBCustomer = function(){
			$.ajax(
			{
				type: 'GET',    
				url: '{{url('cust/browse')}}',
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
			$("#ALAMAT").val(ALAMAT);
			$("#KOTA").val(KOTA);
			$("#browseCustomerModal").modal("hide");

		}
		
		$("#KODEC").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseCustomer();
			}
		}); 
		
		///////////////////////////////////////////////////////////
		
 		var dTableBBarang;
		var rowidBarang;
		loadDataBBarang = function(){
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('brg/browse')}}",
				data: {
					'GOL': 'A2',
				},
				success: function( response )
				{
					resp = response;
					if(dTableBBarang){
						dTableBBarang.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBBarang.row.add([
							'<a href="javascript:void(0);" onclick="chooseBarang(\''+resp[i].KD_BRG+'\',\''+resp[i].NA_BRG+'\')">'+resp[i].KD_BRG+'</a>',
							resp[i].NA_BRG,
						]);
					}
					dTableBBarang.draw();
				}
			});
		}
		
		dTableBBarang = $("#table-bbarang").DataTable({
			
		});
		
		browseBarang = function(rid){
			rowidBarang = rid;
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
				browseBarang(0);
			}
		}); 
		
	

	});

		//////////////////////////////////////////////////////////////////

 	function simpan() {
		hitung();
		
		var tgl = $('#TGL').val();
		var bulanPer = {{session()->get('periode')['bulan']}};
		var tahunPer = {{session()->get('periode')['tahun']}};
		
        var check = '0';
		
		
			if ( $('#KODEC').val()=='' ) 
            {			
			    check = '1';
				alert("Customer# Harus diisi.");
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
		
		// alert($header[0]->NO_SO);
		
		 //$('#NO_SO').val($header[0]->NO_SO);	
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
		   
			$("#NO_SO").attr("readonly", true);		   
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
		
	    $(".NO_SO").attr("readonly", true);	
		
		$("#TGL").attr("readonly", true);
		$("#KODEC").attr("readonly", true);
		$("#NAMAC").attr("readonly", true);
		$("#ALAMAT").attr("readonly", true);
		$("#KOTA").attr("readonly", true);		
		$("#KD_BRG").attr("readonly", true);
		$("#NA_BRG").attr("readonly", true);		
		$("#KG").attr("readonly", true);
		$("#HARGA").attr("readonly", true);		
		$("#TOTAL").attr("readonly", true);		
				
		$("#NOTES").attr("readonly", true);

		

		
	}


	function kosong() {
				
		 $('#NO_SO').val("+");	
	//	 $('#TGL').val("");	
		 $('#KODEC').val("");	
		 $('#NAMAC').val("");	
		 $('#ALAMAT').val("");	
		 $('#KOTA').val("");
		 $('#KD_BRG').val("");	
		 $('#NA_BRG').val("");
		 $('#KG').val("0");
		 $('#HARGA').val("0");	
		 $('#TOTAL').val("0");
		 $('#NOTES').val("");	

		
	}
	
	function hapusTrans() {
		let text = "Hapus Transaksi "+$('#NO_SO').val()+"?";
		if (confirm(text) == true) 
		{
			window.location ="{{url('/so/delete/'.$header->NO_ID .'/?&golz=' .$golz.'' )}}";
			//return true;
		} 
		return false;
	}
	

	function CariBukti() {
		
		var golz = "{{ $golz }}";
		var cari = $("#CARI").val();
		var loc = "{{ url('/so/edit/') }}" + '?idx={{ $header->NO_ID}}&tipx=search&golz=' + encodeURIComponent(goz) + '&buktix=' +encodeURIComponent(cari);
		window.location = loc;
		
	}
	
</script>
@endsection