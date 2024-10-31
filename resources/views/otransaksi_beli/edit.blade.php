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
        <div class="container-fluid">
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-body">

																	
                    <form action="{{($tipx=='new')? url('/beli/store?flagz='.$flagz.'&golz='.$golz.'') : url('/beli/update/'.$header->NO_ID.'&flagz='.$flagz.'&golz='.$golz.'' ) }}" method="POST" name ="entri" id="entri" >
  
                        @csrf
                        
        
                        <div class="tab-content mt-3">
        
                            <div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="NO_BUKTI" class="form-label">Bukti#</label>
                                </div>
								
                                <input type="text" class="form-control NO_ID" id="NO_ID" name="NO_ID"
                                    value="{{$header->NO_ID ?? ''}}" hidden readonly>
								<input name="tipx" class="form-control tipx" id="tipx" value="{{$tipx}}" hidden >
								<input name="flagz" class="form-control flagz" id="flagz" value="{{$flagz}}" hidden >
								<input name="golz" class="form-control golz" id="golz" value="{{$golz}}" hidden >

								<input name="searchx" class="form-control searchx" id="searchx" value="{{$searchx ?? ''}}" hidden >
								
                                <div class="col-md-2">
                                    <input type="text" class="form-control NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI"
                                    placeholder="Masukkan Bukti#" value="{{$header->NO_BUKTI}}" readonly>
                                </div>

                                <div class="col-md-1" align="right">
                                    <label for="TGL" class="form-label">Tgl</label>
                                </div>
                                <div class="col-md-2">
									<input class="form-control date" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->TGL))}}">
                                </div>


								<div class="col-md-2"></div>
					
								<div class="col-md-3 input-group">

									<input type="text" hidden class="form-control CARI" id="CARI" name="CARI"
                                    placeholder="Cari Bukti#" value="" >
									<button type="button" hidden id='SEARCHX'  onclick="CariBukti()" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>

								</div> 
								
                            </div>
        
                            <div class="form-group row">
								<div class="col-md-1" align="right">
									<label style="color:red">*</label>									
                                    <label for="NO_PO" class="form-label">PO#</label>
                                </div>
                                <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control NO_PO" id="NO_PO" name="NO_PO" placeholder="Masukkan PO"value="{{$header->NO_PO}}" style="text-align: left" readonly >
        							
                                </div>
                            </div>

							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="KODES" class="form-label">Suplier#</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KODES" id="KODES" name="KODES" placeholder="Masukkan Suplier#" value="{{$header->KODES}}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <input type="text" class="form-control NAMAS" id="NAMAS" name="NAMAS" placeholder="Nama" value="{{$header->NAMAS}}" readonly>
                                </div>
                            </div>
							
							 <div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="ALAMAT" class="form-label">Alamat</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control ALAMAT" id="ALAMAT" name="ALAMAT" placeholder="Masukkan Alamat" value="{{$header->ALAMAT}}" readonly>
                                </div>
								
                                <div class="col-md-2">
                                    <input type="text" class="form-control KOTA" id="KOTA" name="KOTA" placeholder="Kota" value="{{$header->KOTA}}" readonly>
                                </div>
                            </div>

							<div {{($flagz == 'BL') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="KD_BRG" class="form-label">Barang</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KD_BRG" id="KD_BRG" name="KD_BRG" placeholder="Masukkan Barang" value="{{$header->KD_BRG}}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NA_BRG" id="NA_BRG" name="NA_BRG" placeholder="-" value="{{$header->NA_BRG}}" readonly>
                                </div>
                            </div>
                            
                            
							<div {{($flagz == 'BL') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1"align="right">
                                    <label for="KG" class="form-label">Kg</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control KG" id="KG" name="KG" placeholder="Masukkan KG" value="{{ number_format( $header->KG, 0, '.', ',') }}" style="text-align: right" >
                                </div>
        
                                <div class="col-md-1"align="right">
                                    <label for="HARGA" class="form-label">Harga</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control HARGA" id="HARGA" name="HARGA" placeholder="Masukkan Harga"
									value="{{ number_format( $header->HARGA, 5, '.', ',') }}" style="text-align: right" >
                                </div>
                            </div>
							
							<div class="form-group row">

								<div {{($flagz == 'BL') ? '' : 'hidden' }} class="col-md-1" align="right">
                                    <label for="LAIN" class="form-label">Lain</label>
                                </div>
                                <div {{($flagz == 'BL') ? '' : 'hidden' }} class="col-md-2" align="left">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control LAIN" id="LAIN" name="LAIN" placeholder="Masukkan Lain" value="{{ number_format($header->LAIN, 2, '.', ',') }}" style="text-align: right; width:140px" >
                                </div>
								
                                <div class="col-md-1" align="right">
                                    <label for="TOTAL" class="form-label">Total</label>
                                </div>
                                <div class="col-md-2" align="left">
                                    <input type="text" class="form-control TOTAL" id="TOTAL" name="TOTAL" placeholder="TOTAL" value="{{ number_format($header->TOTAL, 2, '.', ',') }}" style="text-align: right; width:140px" readonly>
                                </div>

                            </div>



							<div {{($flagz == 'BL') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="RPRATE" class="form-label">RpRate</label>
                                </div>
                                <div class="col-md-2" align="left">
                                    <input type="text" onblur="hitung()" class="form-control RPRATE" id="RPRATE" name="RPRATE" placeholder="Masukkan Rate" value="{{ number_format($header->RPRATE, 2, '.', ',') }}" style="text-align: right; width:140px" >
                                </div>
                                <div class="col-md-1" align="right">
                                    <label for="RPHARGA" class="form-label">RpHarga</label>
                                </div>
                                <div class="col-md-2" align="left">
                                    <input type="text" onblur="hitung()" class="form-control RPHARGA" id="RPHARGA" name="RPHARGA" placeholder="RPHARGA" value="{{ number_format($header->RPHARGA, 2, '.', ',') }}" style="text-align: right; width:140px" readonly>
                                </div>
                            </div>
							

							<div {{($flagz == 'BL') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="RPLAIN" class="form-label">RpLain</label>
                                </div>
                                <div class="col-md-2" align="left">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control RPLAIN" id="RPLAIN" name="RPLAIN" placeholder="Masukkan RpLain" value="{{ number_format($header->RPLAIN, 2, '.', ',') }}" style="text-align: right; width:140px" >
                                </div>
                                <div class="col-md-1" align="right">
                                    <label for="RPTOTAL" class="form-label">RpTotal</label>
                                </div>
                                <div class="col-md-2" align="left">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control RPTOTAL" id="RPTOTAL" name="RPTOTAL" placeholder="RPTOTAL" value="{{ number_format($header->RPTOTAL, 2, '.', ',') }}" style="text-align: right; width:140px" readonly>
                                </div>
                            </div>

							

							<div {{($flagz == 'BL') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="AJU" class="form-label">AJU</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control AJU" id="AJU" name="AJU" placeholder="Masukkan AJU" value="{{$header->AJU}}">
                                </div>
                                <div class="col-md-1" align="right">
                                    <label for="BL" class="form-label">BL</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control BL" id="BL" name="BL" placeholder="-" value="{{$header->BL}}">
                                </div>
                                
                                <div class="col-md-1" align="right">
                                    <label for="TRUCK" class="form-label">Truck</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control TRUCK" id="TRUCK" name="TRUCK" placeholder="-" value="{{$header->TRUCK}}">
                                </div>
                                
                            </div>
							
							<div {{($flagz == 'BL') ? '' : 'hidden' }}  class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="EMKL" class="form-label">EMKL</label>
                                </div>
                                 <div class="col-md-3 input-group" >
                                  <input type="text" class="form-control EMKL" id="EMKL" name="EMKL" placeholder="Masukkan EMKL"value="{{$header->EMKL}}" style="text-align: left" readonly >
                                </div>
                                <div class="col-md-1" align="right">
                                    <label for="JCONT" class="form-label">J-Cont</label>
                                </div>
                                <div class="col-md-1">
                                    <input type="text" class="form-control JCONT" id="JCONT" name="JCONT" placeholder="-" value="{{$header->JCONT}}">
                                </div>

                                <div class="col-md-1" align="right">
                                    <label for="TGL_BL" class="form-label">Tgl-Beli</label>
                                </div>
                                <div class="col-md-2">
									<input class="form-control date" id="TGL_BL" name="TGL_BL" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->TGL_BL))}}">
                                </div>
								
                            </div>


							<div class="form-group row">
								
								<div class="col-md-1" align="right">
                                    <label for="KONTRAK" class="form-label">Kontrak</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control KONTRAK" id="KONTRAK" name="KONTRAK" placeholder="Masukkan Kontrak" value="{{$header->KONTRAK}}">
                                </div>
								
                            </div>
                            
							<div class="form-group row">
								
								<div class="col-md-1" align="right">
                                    <label for="NOTES" class="form-label">Notes</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NOTES" id="NOTES" name="NOTES" placeholder="Masukkan Notes" value="{{$header->NOTES}}">
                                </div>
								
                            </div>

                            <div class="form-group row">
                                <div class="col-md-1">
                                    <label for="ACNOA" class="form-label">Acc#</label>
                                </div>
                                <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control ACNOA" id="ACNOA" name="ACNOA" placeholder="Acc#" value="{{$header->ACNOA}}" style="text-align: left" readonly >
        						
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NACNOA" id="NACNOA" name="NACNOA" placeholder="-" value="{{ $header->NACNOA }}" readonly>
                                </div>
							</div>
							
                            <div {{($flagz == 'UM') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1">
                                    <label for="BACNO" class="form-label">Bank#</label>
                                </div>
                                <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control BACNO" id="BACNO" name="BACNO" placeholder="Bank#" value="{{$header->BACNO}}" style="text-align: left" readonly >
        						
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control BNAMA" id="BNAMA" name="BNAMA" placeholder="-" value="{{ $header->BNAMA }}" readonly>
                                </div>

                               <div class="col-md-2">
                                    <input type="text" class="form-control NO_BANK" id="NO_BANK" name="NO_BANK" placeholder="-" value="{{ $header->NO_BANK }}" readonly>
                                </div>
                                
                                                                
							</div>
							
							
	
                        </div>


						        
						<div class="mt-3 col-md-12 form-group row">
							<div class="col-md-4">
								<button type="button" hidden id='TOPX'  onclick="location.href='{{url('/beli/edit/?idx=' .$idx. '&tipx=top&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Top</button>
								<button type="button" hidden id='PREVX' onclick="location.href='{{url('/beli/edit/?idx='.$header->NO_ID.'&tipx=prev&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Prev</button>
								<button type="button" hidden id='NEXTX' onclick="location.href='{{url('/beli/edit/?idx='.$header->NO_ID.'&tipx=next&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Next</button>
								<button type="button" hidden id='BOTTOMX' onclick="location.href='{{url('/beli/edit/?idx=' .$idx. '&tipx=bottom&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Bottom</button>
							</div>
							<div class="col-md-5">
								<button type="button" hidden id='NEWX' onclick="location.href='{{url('/beli/edit/?idx=0&tipx=new&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-warning">New</button>
								<button type="button" hidden id='EDITX' onclick='hidup()' class="btn btn-secondary">Edit</button>                    
								<button type="button" hidden id='UNDOX' onclick="location.href='{{url('/beli/edit/?idx=' .$idx. '&tipx=undo&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-info">Undo</button>  
								<button type="button" id='SAVEX' onclick='simpan()'   class="btn btn-success"<i class="fa fa-save"></i>Save</button>

							</div>
							<div class="col-md-3">
								<button type="button" hidden id='HAPUSX'  onclick="hapusTrans()" class="btn btn-outline-danger">Hapus</button>
								<button type="button" id='CLOSEX'  onclick="location.href='{{url('/beli?flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-secondary">Close</button>
							</div>
						</div>
						
						


                    </form>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
	

	<div class="modal fade" id="browsePoModal" tabindex="-1" role="dialog" aria-labelledby="browsePoModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browsePoModalLabel">Cari Po#</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bpo">
				<thead>
					<tr>
						<th>Po#</th>
						<th>Suplier</th>
						<th>Barang</th>
						<th>Harga</th>
						<th>Kg</th>
						<th>Kirim</th>
						<th>Sisa</th>						
						<th>Kontrak</th>
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
	
	<div class="modal fade" id="browseAccountModal" tabindex="-1" role="dialog" aria-labelledby="browseAccountModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseAccountModalLabel">Cari Account</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-baccount">
				<thead>
					<tr>
						<th>Acc#</th>
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
	

	<div class="modal fade" id="browseMklModal" tabindex="-1" role="dialog" aria-labelledby="browseMklModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseMklModalLabel">Cari EMKL</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bmkl">
				<thead>
					<tr>
						<th>Kode</th>
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

	<div class="modal fade" id="browsePoxModal" tabindex="-1" role="dialog" aria-labelledby="browsePoxModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browsePoxModalLabel">Cari Po#</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bpox">
				<thead>
					<tr>
						<th>Po#</th>
						<th>Kode</th>
						<th>-</th>
						<th>Total</th>
						<th>Bayar</th>
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
<script src="{{ asset('js/autoNumerics/autoNumeric.min.js') }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="{{asset('foxie_js_css/bootstrap.bundle.min.js')}}"></script>

<script>
	var idrow = 1;
    function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	$(document).ready(function() {

		$tipx = $('#tipx').val();
		$searchx = $('#CARI').val();

		$('body').on('keydown', 'input, select', function(e) {
			if (e.key === "Enter") {
				var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
				focusable = form.find('input,select,textarea').filter(':visible');
				next = focusable.eq(focusable.index(this)+1);
				console.log(next);
				if (next.length) {
					next.focus().select();
				} else {
					// tambah();
					var nomer = idrow-1;
					console.log("NO_BUKTI");
					document.getElementById("NO_BUKTI").focus();
					// form.submit();
				}
				return false;
			}
		});
		
		
        if ( $tipx == 'new' )
		{
			 baru();			
		}

        if ( $tipx != 'new' )
		{
			 ganti();			
		}    
		
	
	
		$("#LAIN").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#RPTOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#RPLAIN").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-99999999.99'});
		$("#RPRATE").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});		
		$("#RPHARGA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});		
		$("#KG").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#HARGA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-9999.99999'});
		$("#TOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});


		
		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		})
		

	
	
		hitung=function() {	

			var RPRATEX = parseFloat($('#RPRATE').val().replace(/,/g, ''));		
			var HARGAX = parseFloat($('#HARGA').val().replace(/,/g, ''));
			var KGX = parseFloat($('#KG').val().replace(/,/g, ''));
			var LAINX = parseFloat($('#LAIN').val().replace(/,/g, ''));
			var RPLAINX = parseFloat($('#RPLAIN').val().replace(/,/g, ''));					
					
			var TOTALX  = ( HARGAX * KGX ) + LAINX;
			var RPHARGAX  = HARGAX * RPRATEX ;

			
			$('#TOTAL').val(numberWithCommas(TOTALX));	
		    $("#TOTAL").autoNumeric('update');	

			$('#RPHARGA').val(numberWithCommas(RPHARGAX));	
		    $("#RPHARGA").autoNumeric('update');	

			var TOTAL2X = parseFloat($('#TOTAL').val().replace(/,/g, ''));	
			
			var RPTOTAL2X  = ( TOTAL2X * RPRATEX ) + RPLAINX;
			
			$('#RPTOTAL').val(numberWithCommas(RPTOTAL2X));	
		    $("#RPTOTAL").autoNumeric('update');	


		
		
		}			
///////////////////////////////////////////////////////////////////////

		var dTableBPo;
		loadDataBPo = function(){
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('po/browse')}}",
				data: {
					'GOL': "{{$golz}}",
				},
				success: function( response )
				{
					resp = response;
					if(dTableBPo){
						dTableBPo.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBPo.row.add([
							'<a href="javascript:void(0);" onclick="choosePo(\''+resp[i].NO_BUKTI+'\', \''+resp[i].KODES+'\',  \''+resp[i].NAMAS+'\', \''+resp[i].ALAMAT+'\',  \''+resp[i].KOTA+'\',  \''+resp[i].KD_BRG+'\' ,  \''+resp[i].NA_BRG+'\' ,  \''+resp[i].KG+'\',  \''+resp[i].HARGA+'\' , \''+resp[i].SISA+'\' ,   \''+resp[i].KONTRAK+'\'         )">'+resp[i].NO_BUKTI+'</a>',
							resp[i].NAMAS,
							resp[i].NA_BRG,
							resp[i].HARGA,							
							Intl.NumberFormat('en-US').format(resp[i].KG),	
							Intl.NumberFormat('en-US').format(resp[i].KIRIM),	
							Intl.NumberFormat('en-US').format(resp[i].SISA),	
							resp[i].KONTRAK,								
						]);
					}
					dTableBPo.draw();
				}
			});
		}
		
		dTableBPo = $("#table-bpo").DataTable({
			
		});
		
		browsePo = function(){
			loadDataBPo();
			$("#browsePoModal").modal("show");
		}
		
		choosePo = function(NO_BUKTI,KODES, NAMAS, ALAMAT, KOTA, KD_BRG, NA_BRG, KG, HARGA,  SISA, KONTRAK ){
			$("#NO_PO").val(NO_BUKTI);
			$("#KODES").val(KODES);
			$("#NAMAS").val(NAMAS);
			$("#ALAMAT").val(ALAMAT);
			$("#KOTA").val(KOTA);
			$("#KD_BRG").val(KD_BRG);
			$("#NA_BRG").val(NA_BRG);
			$("#HARGA").val(HARGA);
			$("#KG").val(SISA);
			$("#KONTRAK").val(KONTRAK);
			$("#browsePoModal").modal("hide");
			
			hitung();
		}
		
		$("#NO_PO").keypress(function(e){

			if(e.keyCode == 46){
				e.preventDefault();
				
				$flagz = $('#flagz').val();
				
				if ( $flagz == 'BL' ) {
					browsePo();
					
				} else {
					
					browsePox();

                }					
					
			}
			
		}); 
		
		
		////////////////////////////////////////
		

		var dTableBPox;
		loadDataBPox = function(){
		
			$.ajax(
			{
				type: 'GET', 		
				url: '{{url('po/browseuang')}}',
				data: {
					'GOL': "{{$golz}}",
				},
				success: function( response )
				{
					resp = response;
					if(dTableBPox){
						dTableBPox.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBPox.row.add([
							'<a href="javascript:void(0);" onclick="choosePox(\''+resp[i].NO_BUKTI+'\',  \''+resp[i].KODES+'\', \''+resp[i].NAMAS+'\')">'+resp[i].NO_BUKTI+'</a>',
							resp[i].KODES,
							resp[i].NAMAS,
							Intl.NumberFormat('en-US').format(resp[i].TOTAL),
							Intl.NumberFormat('en-US').format(resp[i].BAYAR),
							Intl.NumberFormat('en-US').format(resp[i].SISA),
							
						]);
					}
					dTableBPox.draw();
				}
			});
		}
		
		dTableBPox = $("#table-bpox").DataTable({
			columnDefs: [
				{
                    className: "dt-right", 
					targets:  [],
					render: $.fn.dataTable.render.number( ',', '.', 2, '' )
				}
			],
		});
		
		browsePox = function(){
			 loadDataBPox();
			$("#browsePoxModal").modal("show");
		}
		
		choosePox = function(NO_BUKTI,KODES,NAMAS){
			$("#NO_PO").val(NO_BUKTI);
			$("#KODES").val(KODES);
			$("#NAMAS").val(NAMAS);		
			$("#browsePoxModal").modal("hide");
		}
		
 
						
		
		
		
		
		//////////////////////////////////////

 		var dTableBAccount;
		var tipex ;
		
		loadDataBAccount = function(){
			
		  if ( tipex == '0' )
		  {
			$.ajax(
			{
				type: 'GET',    
				url: '{{url('account/browse')}}',
				success: function( response )
				{
					resp = response;
					if(dTableBAccount){
						dTableBAccount.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBAccount.row.add([
							'<a href="javascript:void(0);" onclick="chooseAccount(\''+resp[i].ACNO+'\',  \''+resp[i].NAMA+'\' )">'+resp[i].ACNO+'</a>',
							resp[i].NAMA,
						]);
					}
					dTableBAccount.draw();
				}
			});
			
		  }
		  	
		  if ( tipex == '1' )
		  {
			
			  
			$.ajax(
			{
				type: 'GET',    
				url: '{{url('account/browsebank')}}',
				success: function( response )
				{
					resp = response;
					if(dTableBAccount){
						dTableBAccount.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBAccount.row.add([
							'<a href="javascript:void(0);" onclick="chooseAccount(\''+resp[i].ACNO+'\',  \''+resp[i].NAMA+'\' )">'+resp[i].ACNO+'</a>',
							resp[i].NAMA,
						]);
					}
					dTableBAccount.draw();
				}
			});
			
		  }
		  
			
		}
		
		dTableBAccount = $("#table-baccount").DataTable({
			
		});
		
		browseAccount = function(rid){
			tipex = rid;
			loadDataBAccount();
			$("#browseAccountModal").modal("show");
		}
		
		chooseAccount = function(ACNO, NAMA){
			
			if ( tipex =='0' )
			{
			  $("#ACNOA").val(ACNO);
			  $("#NACNOA").val(NAMA);
			}
			
			if ( tipex =='1' )
			{
			  $("#BACNO").val(ACNO);
			  $("#BNAMA").val(NAMA);
			}
			
			$("#browseAccountModal").modal("hide");
		}
		
		$("#ACNOA").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseAccount(0);
			}
		});
		
		$("#BACNO").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseAccount(1);
			}
		}); 



		
		///////////////////////////////////////////////////////////////////////////////////////////////		
		var dTableBMkl;
		var rowidMkl;
		loadDataBMkl = function(){
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('mkl/browse')}}",

				success: function( response )
				{
					resp = response;
					if(dTableBMkl){
						dTableBMkl.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBMkl.row.add([
							'<a href="javascript:void(0);" onclick="chooseMkl(\''+resp[i].KODE+'\',  \''+resp[i].NAMA+'\' )">'+resp[i].KODE+'</a>',
							resp[i].NAMA,
						
						]);
					}
					dTableBMkl.draw();
				}
			});
		}
		
		dTableBMkl = $("#table-bmkl").DataTable({
			
		});
		
		browseMkl = function(){
			loadDataBMkl();
			$("#browseMklModal").modal("show");
		}
		
		chooseMkl = function(KODE,NAMA){
			$("#EMKL").val(NAMA);			
			$("#browseMklModal").modal("hide");
		}
		
		
		$("#EMKL").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseMkl();
			}
		}); 	
 		
		/*
		$("#KA").keypress(function(e){

			if(e.keyCode == 46){
				e.preventDefault();
				browseRefa();
			}
		}); */
		
		//////////////////////////////////////////////////////////////////////////////////////////////////
	});		



 	function simpan() {

    	var flagz = $('#flagz').val();

			if ( flagz =='BL'  ){
                 hitung();			
			}
		
		var tgl = $('#TGL').val();
		var bulanPer = {{session()->get('periode')['bulan']}};
		var tahunPer = {{session()->get('periode')['tahun']}};
		
        var check = '0';
		
		
			if ( $('#NO_PO').val()=='' ) 
            {			
			    check = '1';
				alert("PO# Harus diisi.");
			}

			if ( $('#ACNOA').val()=='' ) 
            {			
			    check = '1';
				alert("Account Harus diisi.");
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

	    
			if ( flagz =='TH'  ){
			    var RPTOTALXX = $("#TOTAL").val();
		
			    $('#RPTOTAL').val(numberWithCommas(RPTOTALXX));	
		        $("#RPTOTAL").autoNumeric('update');
		        
		      
				
			}

			if ( flagz =='UM'  ){
			    var RPTOTALXX = $("#TOTAL").val() * -1;
		
			    $('#RPTOTAL').val(numberWithCommas(RPTOTALXX));	
		        $("#RPTOTAL").autoNumeric('update');				
				
			}
			
		(check==0) ? document.getElementById("entri").submit() : alert('Masih ada kesalahan');

			
	}




	function baru() {
		
		 kosong();
		 hidup();
	
	}
	
	function ganti() {
		
		// mati();
		hidup();
	
	}
	
	function batal() {
		
		// alert($header[0]->NO_BUKTI);
		
		 //$('#NO_BUKTI').val($header[0]->NO_BUKTI);	
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
	  //  $("#CLOSEX").attr("disabled", true);

		$("#CARI").attr("readonly", true);	
	    $("#SEARCHX").attr("disabled", true);
		
	    $("#PLUSX").attr("hidden", false)
		   
			$("#NO_BUKTI").attr("readonly", true);		   
			$("#TGL").attr("readonly", false);
			$("#NO_PO").attr("readonly", true);
			$("#KODES").attr("readonly", true);
			$("#NAMAS").attr("readonly", true);
			$("#ALAMAT").attr("readonly", true);
			$("#KOTA").attr("readonly", true);
			$("#KD_BRG").attr("readonly", true);
			$("#NA_BRG").attr("readonly", true);
			$("#KG").attr("readonly", false);
			$("#HARGA").attr("readonly", true);
			$("#LAIN").attr("readonly", false);
			$("#TOTAL").attr("readonly", true);
			$("#RPRATE").attr("readonly", false);
			$("#RPHARGA").attr("readonly", true);
			$("#RPLAIN").attr("readonly", false);
			$("#RPTOTAL").attr("readonly", true);

			$("#AJU").attr("readonly", false );
			$("#BL").attr("readonly", false );
			$("#EMKL").attr("readonly", true );
			$("#JCONT").attr("readonly", false );
			$("#TGL_BL").attr("readonly", false );						
			$("#NOTES").attr("readonly", false);
			
		
    		var flagz = $('#flagz').val();
    		var golz = $('#golz').val();

		    
			if ( flagz !='BL' ){
			    $("#TOTAL").attr("readonly", false);
			}
			
			if ( flagz =='BL' && golz =='Z' ){
			    $("#HARGA").attr("readonly", false);
			}
			

		
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
		
	    $(".NO_BUKTI").attr("readonly", true);	
		
		$("#TGL").attr("readonly", true);
		$("#NO_PO").attr("readonly", true);
		$("#KODES").attr("readonly", true);
		$("#NAMAS").attr("readonly", true);
		$("#ALAMAT").attr("readonly", true);
		$("#KOTA").attr("readonly", true);
		$("#KD_BRG").attr("readonly", true);
		$("#NA_BRG").attr("readonly", true);
		$("#KG").attr("readonly", true);
		$("#HARGA").attr("readonly", true);
		$("#LAIN").attr("readonly", true);
		$("#TOTAL").attr("readonly", true)
		$("#RPRATE").attr("readonly", true);
		$("#RPHARGA").attr("readonly", true);
		$("#RPLAIN").attr("readonly", true);
		$("#RPTOTAL").attr("readonly", true);

		$("#AJU").attr("readonly", true);
		$("#BL").attr("readonly", true);
		$("#EMKL").attr("readonly", true);
		$("#JCONT").attr("readonly", true);
		$("#TGL_BL").attr("readonly", true);
		$("#NOTES").attr("readonly", true);
		

		
	}


	function kosong() {
				
		 $('#NO_BUKTI').val("+");	
	//	 $('#TGL').val("");	
		 $('#KODES').val("");	
		 $('#NAMAS').val("");
		 $('#ALAMAT').val("");	
		 $('#KOTA').val("");
		 $('#NO_PO').val("");

		 $('#KONTRAK').val("");
		 
		 $('#KD_BRG').val("");	
		 $('#NA_BRG').val("");	
		 $('#KG').val("0.00");
		 $('#HARGA').val("0.00");		 
		 $('#LAIN').val("0.00");
		 $('#TOTAL').val("0.00");		 
		 $('#RPRATE').val("1.00");		 
		 $('#RPHARGA').val("0.00");
		 $('#RPLAIN').val("0.00");
		 $('#RPTOTAL').val("0.00");		 

		 $('#AJU').val("");	
		 $('#BL').val("");	
		 $('#EMKL').val("");	
		 $('#JCONT').val("0");			 	 
		 $('#NOTES').val("");	
		 $('#ACNOA').val("");
		 $('#NACNOA').val("");	
		 $('#BACNO').val("");
		 $('#BNAMA').val("");	
		 
		var flagz = $('#flagz').val();
		var golz = $('#golz').val();
		
			if ( flagz =='BL'  ){


                if ( golz =='Y'  ){ 
                    
			        $('#ACNOA').val('115102');					
			        $('#NACNOA').val('PERSEDIAAN DALAM PERJALANAN');			
			    
			    }
			 
			    if ( flagz =='Z'  ){   
			        $('#ACNOA').val('');					
			        $('#NACNOA').val('');					
			    }
			    
				
			}
			

			if ( flagz =='UM'  ){


			    if ( golz =='Y'  ){   
			        $('#ACNOA').val('116102');					
			        $('#NACNOA').val('UANG MUKA PEMBELIAN');					
			    }
			 
			    if ( flagz =='Z'  ){   
			        $('#ACNOA').val('116106');					
			        $('#NACNOA').val('UANG MUKA PEMBELIAN NON');					
			    }
			    
			}
			
			
		
	}
	
	function hapusTrans() {
		let text = "Hapus Transaksi "+$('#NO_BUKTI').val()+"?";
		if (confirm(text) == true) 
		{
			window.location ="{{url('/beli/delete/'.$header->NO_ID .'/?flagz='.$flagz.'&golz=' .$golz.'' )}}";
			//return true;
		} 
		return false;
	}
	

	function CariBukti() {
		
		var flagz = "{{ $flagz }}";
		var golz = "{{ $golz }}";
		var cari = $("#CARI").val();
		var loc = "{{ url('/beli/edit/') }}" + '?idx={{ $header->NO_ID}}&tipx=search&flagz=' + encodeURIComponent(flagz) +'&golz=' + encodeURIComponent(golz) + '&buktix=' +encodeURIComponent(cari);
		window.location = loc;
		
	}


		
	//////////////////////////////////////////////////////////////////////
</script>
@endsection