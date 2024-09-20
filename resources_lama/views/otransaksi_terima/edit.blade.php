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

                    <form action="{{($tipx=='new')? url('/terima/store?flagz='.$flagz.'') : url('/terima/update/'.$header->NO_ID.'&flagz='.$flagz.'' ) }}" method="POST" name ="entri" id="entri" >
  
                        @csrf
						
                        <div class="tab-content mt-3">
        
                            <div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="NO_BUKTI" class="form-label">Bukti#  </label>
                                </div>
								
                                <input type="text" class="form-control NO_ID" id="NO_ID" name="NO_ID"
                                    value="{{$header->NO_ID ?? ''}}" hidden readonly>
								<input name="tipx" class="form-control tipx" id="tipx" value="{{$tipx}}" hidden >
								<input name="flagz" class="form-control flagz" id="flagz" value="{{$flagz}}" hidden >
								<input name="golz" class="form-control golz" id="golz" value="{{$golz}}" hidden >
								<input name="searchx" class="form-control searchx" id="searchx" value="{{$searchx ?? ''}}" hidden >

								
                                <div class="col-md-2">
                                    <input type="text" class="form-control NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI"
                                    placeholder="Masukkan Bukti#" value="{{$header->NO_BUKTI}}" readonly style="width:140px">
                                </div>
                                <div class="col-md-1" align="right">
                                    <label for="TGL" class="form-label">Tanggal</label>
                                </div>
                                <div class="col-md-2">				
								  <input class="form-control date" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->TGL))}}" style="width:140px">
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
									<label style="color:red;font-size:20px">* </label>
                                    <label for="NO_BL" class="form-label">Beli#</label>
                                </div>
                                 <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control NO_BL" id="NO_BL" name="NO_BL" placeholder="Masukkan Beli"value="{{$header->NO_BL}}" style="text-align: left" readonly >
                                </div>
                            </div>

                            <div class="form-group row">
								<div class="col-md-1" align="right">
									<label style="color:red;font-size:20px">* </label>
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
                                    <input type="text" class="form-control KODES" id="KODES" name="KODES" placeholder="Masukkan Suplier#" value="{{$header->KODES}}" readonly style="width:140px">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NAMAS" id="NAMAS" name="NAMAS" placeholder="Nama Suplier" value="{{$header->NAMAS}}" readonly>
                                </div>
                            </div>
							
							<!--div class="form-group row">
                                <div class="col-md-2" align="right">
                                    <label for="ALAMAT" class="form-label">Alamat</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control ALAMAT" id="ALAMAT" name="ALAMAT" placeholder="Alamat Customer" value="{{$header->ALAMAT}}" readonly>
                                </div>
                                <div class="col-md-2" align="right">
                                    <label for="KOTA" class="form-label">Kota</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control KOTA" id="KOTA" name="KOTA" placeholder="Kota Customer" value="{{$header->KOTA}}" readonly>
                                </div>
                            </div-->

							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="KD_BRG" class="form-label">Barang</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KD_BRG" id="KD_BRG" name="KD_BRG" placeholder="Masukkan Barang" value="{{$header->KD_BRG}}" readonly style="width:140px">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NA_BRG" id="NA_BRG" name="NA_BRG" placeholder="Nama Barang" value="{{$header->NA_BRG}}" readonly>
                                </div>
                        	</div>
                        
							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="KG1" class="form-label">Kg1</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text"  onkeyup="hitung()" class="form-control KG1" id="KG1" name="KG1" placeholder="-" value="{{ number_format( $header->KG1, 2, '.', ',') }}" style="text-align: right; width:140px" >
                                </div>
                           </div>

							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="SUSUT" class="form-label">Susut</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text"  class="form-control SUSUT" id="SUSUT" name="SUSUT" placeholder="SUSUT" value="{{ number_format( $header->SUSUT, 2, '.', ',') }}" style="text-align: right; width:140px" readonly>
                                </div>
                           </div>
							
							
						   
							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="KG" class="form-label">Kg</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onkeyup="hitung()"  class="form-control KG" id="KG" name="KG" placeholder="-" value="{{ number_format( $header->KG, 2, '.', ',') }}" style="text-align: right; width:140px" >
                                </div>
                           </div>
						   
                        	<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="TRUCK" class="form-label">Truck</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control TRUCK" id="TRUCK" name="TRUCK" placeholder="Masukkan Truck" value="{{$header->TRUCK}}" style="width:140px">
                                </div>
                                <div class="col-md-1" align="right">
                                    <label for="SOPIR" class="form-label">Sopir</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control SOPIR" id="SOPIR" name="SOPIR" placeholder="Masukkan Sopir" value="{{$header->SOPIR}}">
                                </div>
                            </div>
                            
							<div class="form-group row">
                                <div class="col-md-1" align="right">
									<label style="color:red;font-size:20px">* </label>		
                                    <label for="AJU" class="form-label">AJU#</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control AJU" id="AJU" name="AJU" placeholder="Masukkan AJU#" value="{{$header->AJU}}"  style="width:140px">
                                </div>
								
								
                                <div class="col-md-1" align="right">
                                    <label for="BL" class="form-label">BL#</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control BL" id="BL" name="BL" placeholder="-"value="{{$header->BL}}" style="width:140px" >
                                </div>
                            </div>

							<div class="form-group row">
                                <div class="col-md-1" align="right">
									<label style="color:red;font-size:20px">* </label>		
                                    <label for="NO_CONT" class="form-label">CONT</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control NO_CONT" id="NO_CONT" name="NO_CONT" placeholder="Masukkan CONT#" value="{{$header->NO_CONT}}"  style="width:140px">
                                </div>
                                <div class="col-md-1" align="right">
                                    <label for="SEAL" class="form-label">SEAL</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control SEAL" id="SEAL" name="SEAL" placeholder="Masukkan SEAL" value="{{$header->SEAL}}" >
                                </div>
                            </div>
							

							<div class="form-group row">
                                <div class="col-md-1" align="right">
									<label style="color:red;font-size:20px">* </label>		
                                    <label for="EMKL" class="form-label">EMKL</label>
                                </div>
                                 <div class="col-md-4 input-group" >
                                  <input type="text" class="form-control EMKL" id="EMKL" name="EMKL" placeholder="Masukkan EMKL"value="{{$header->EMKL}}" style="text-align: left" readonly >
                                </div>
                                <div class="col-md-1" align="right">
                                    <label for="GUDANG" class="form-label">Gudang</label>
                                </div>
                                <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control GUDANG" id="GUDANG" name="GUDANG" placeholder="Masukkan Gudang"value="{{$header->GUDANG}}" style="text-align: left" readonly >
                               </div>
                            </div>
							

								<div class="form-group row">
									<div class="col-md-1"align="right">
										<label for="T_TRUCK" class="form-label">T-TRUCK</label>
									</div>
									
									<div class="col-md-2">
									  <select id="T_TRUCK" class="form-control" name="T_TRUCK">
										<option value="MANUAL" {{ ($header->T_TRUCK == 'MANUAL') ? 'selected' : '' }}>MANUAL</option>
										<option value="DUMP" {{ ($header->T_TRUCK == 'DUMP') ? 'selected' : '' }}>DUMP</option>
									  </select>
									</div>                             
								</div>	
								
								
								<div class="form-group row">

									<div class="col-md-1"align="right">
										<label for="T_CONT" class="form-label">T-CONT</label>
									</div>
									
									<div class="col-md-2">
									  <select id="T_CONT" class="form-control" name="T_CONT">
										<option value="20F" {{ ($header->T_CONT == '20F') ? 'selected' : '' }}>20F</option>
										<option value="40F" {{ ($header->T_CONT == '40F') ? 'selected' : '' }}>40F</option>

									  </select>
									</div>  

									
								</div>	
								
								
							
							<div class="form-group row">

                              <div class="col-md-1"align="right">
                                    <label for="HARGA" class="form-label">Harga</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control HARGA" id="HARGA" name="HARGA" placeholder="Masukkan Harga"
									value="{{ number_format( $header->HARGA, 5, '.', ',') }}" style="text-align: right" >
                                </div>
                            </div>
							
						

							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="TOTAL" class="form-label">Total</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control TOTAL" id="TOTAL" name="TOTAL" placeholder="TOTAL" value="{{ number_format( $header->TOTAL, 2, '.', ',') }}" style="text-align: right; width:140px" readonly>
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
                                <div class="col-md-1" align="right">
                                    <label for="RPRATE" class="form-label">RpRate</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onkeyup="hitung()" class="form-control RPRATE" id="RPRATE" name="RPRATE" placeholder="RPRATE" value="{{ number_format( $header->RPRATE, 2, '.', ',') }}" style="text-align: right; width:140px" readonly>
                                </div>
                            </div>	
							
							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="RPHARGA" class="form-label">RpHarga</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control RPHARGA" id="RPHARGA" name="RPHARGA" placeholder="RPHARGA" value="{{ number_format( $header->RPHARGA, 2, '.', ',') }}" style="text-align: right; width:140px" readonly>
                                </div>
                            </div>	
							
							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="RPTOTAL" class="form-label">RpTotal</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control RPTOTAL" id="RPTOTAL" name="RPTOTAL" placeholder="RPTOTAL" value="{{ number_format( $header->RPTOTAL, 2, '.', ',') }}" style="text-align: right; width:140px" readonly>
                                </div>
                            </div>	







                        </div>


						<div class="mt-3 col-md-12 form-group row">
							<div class="col-md-4">
								<button type="button" hidden id='TOPX'  onclick="location.href='{{url('/terima/edit/?idx=' .$idx. '&tipx=top&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Top</button>
								<button type="button" hidden id='PREVX' onclick="location.href='{{url('/terima/edit/?idx='.$header->NO_ID.'&tipx=prev&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Prev</button>
								<button type="button" hidden id='NEXTX' onclick="location.href='{{url('/terima/edit/?idx='.$header->NO_ID.'&tipx=next&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Next</button>
								<button type="button" hidden id='BOTTOMX' onclick="location.href='{{url('/terima/edit/?idx=' .$idx. '&tipx=bottom&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Bottom</button>
							</div>
							<div class="col-md-5">
								<button type="button" hidden id='NEWX' onclick="location.href='{{url('/terima/edit/?idx=0&tipx=new&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-warning">New</button>
								<button type="button" hidden id='EDITX' onclick='hidup()' class="btn btn-secondary">Edit</button>                    
								<button type="button" hidden id='UNDOX' onclick="location.href='{{url('/terima/edit/?idx=' .$idx. '&tipx=undo&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-info">Undo</button>  
								<button type="button" id='SAVEX' onclick='simpan()'   class="btn btn-success"<i class="fa fa-save"></i>Save</button>

							</div>
							<div class="col-md-3">
								<button type="button" hidden id='HAPUSX'  onclick="hapusTrans()" class="btn btn-outline-danger">Hapus</button>
								<button type="button" id='CLOSEX'  onclick="location.href='{{url('/terima?flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-secondary">Close</button>
							</div>
						</div>
						
						
						
                    </form>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
	


	<div class="modal fade" id="browseBeliModal" tabindex="-1" role="dialog" aria-labelledby="browseBeliModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseSoModalLabel">Cari Beli#</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bbeli">
				<thead>
					<tr>
						<th>Beli#</th>
						<th>PO#</th>
						<th>Suplier</th>
						<th>Barang</th>
						<th>Harga</th>	
						<th>Kg</th>	
						<th>Kirim</th>	
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



	
<div class="modal fade" id="browseGdgModal" tabindex="-1" role="dialog" aria-labelledby="browseGdgModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseMklModalLabel">Cari Gudang</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bgdg">
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
		
		
        if ( $tipx == 'new' )
		{
			 baru();			
		}

        if ( $tipx != 'new' )
		{
			 ganti();			
		}    
		
	
	
		$("#KG1").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#SUSUT").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#KG").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		
		$("#HARGA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99999'});
		$("#TOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});


		$("#RPRATE").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#RPHARGA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#RPTOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		

		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		})
		
		hitung=function() {

			var KG1X = parseFloat($('#KG1').val().replace(/,/g, ''));
			var KGX = parseFloat($('#KG').val().replace(/,/g, ''));
			var HARGAX = parseFloat($('#HARGA').val().replace(/,/g, ''));
			
			var SUSUTX = KG1X - KGX;
			
			$('#SUSUT').val(numberWithCommas(SUSUTX));	
		    $("#SUSUT").autoNumeric('update');
			
			var RPRATEX = parseFloat($('#RPRATE').val().replace(/,/g, ''));			
			var RPHARGAX = HARGAX * RPRATEX;
			$('#RPHARGA').val(numberWithCommas(RPHARGAX));			
		    $("#RPHARGA").autoNumeric('update');			
		
            var TOTALX = ( HARGAX * KG1X ) ;
			$('#TOTAL').val(numberWithCommas(TOTALX));	
		    $("#TOTAL").autoNumeric('update');	
		
		
            var RPTOTALX = ( TOTALX  * RPRATEX ) ;
			$('#RPTOTAL').val(numberWithCommas(RPTOTALX));	
		    $("#RPTOTAL").autoNumeric('update');
		
		
		}		

		///////////////////////////////////////////////////////////////////////

 		var dTableBBeli;
		loadDataBBeli = function(){
			$.ajax(
			{
				type: 'GET',    
				url: '{{url('beli/browse')}}',
				data: {
					'GOL': "{{$golz}}",
				},
				success: function( response )
				{
					resp = response;
					if(dTableBBeli){
						dTableBBeli.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBBeli.row.add([
							'<a href="javascript:void(0);" onclick="chooseBeli(\''+resp[i].NO_BUKTI+'\',  \''+resp[i].NO_PO+'\', \''+resp[i].KODES+'\', \''+resp[i].NAMAS+'\', \''+resp[i].ALAMAT+'\', \''+resp[i].KOTA+'\' , \''+resp[i].KD_BRG+'\' , \''+resp[i].NA_BRG+'\' , \''+resp[i].KG+'\', \''+resp[i].HARGA+'\', \''+resp[i].KIRIM+'\', \''+resp[i].SISA+'\',                 \''+resp[i].RPRATE+'\' , \''+resp[i].AJU+'\' , \''+resp[i].EMKL+'\' , \''+resp[i].BL+'\' ,   \''+resp[i].SISAC+'\'    )">'+resp[i].NO_BUKTI+'</a>',
							resp[i].NO_PO,
							resp[i].NAMAS,
							resp[i].NA_BRG,
							resp[i].HARGA,
							resp[i].KG,
							resp[i].KIRIM,
							resp[i].SISA,								

							
						]);
					}
					dTableBBeli.draw();
				}
			});
		}
		
		dTableBBeli = $("#table-bbeli").DataTable({
		columnDefs: [
				{
                    className: "dt-right", 
					targets:  [6],
					render: $.fn.dataTable.render.number( ',', '.', 2, '' )
				}
			],
		});
		
		browseBeli = function(){
			loadDataBBeli();
			$("#browseBeliModal").modal("show");
		}
		
		chooseBeli = function(NO_BUKTI,NO_PO, KODES,NAMAS,ALAMAT, KOTA, KD_BRG, NA_BRG, KG, HARGA, KIRIM, SISA, RPRATE, AJU, EMKL, BL ){
			$("#NO_BL").val(NO_BUKTI);
			$("#NO_PO").val(NO_PO);
			$("#KODES").val(KODES);
			$("#NAMAS").val(NAMAS);
			$("#ALAMAT").val(ALAMAT);
			$("#KOTA").val(KOTA);
			$("#KD_BRG").val(KD_BRG);
			$("#NA_BRG").val(NA_BRG);
			$("#BL").val(BL);
			$("#AJU").val(AJU);
			$("#EMKL").val(EMKL);
			
		/* 	$("#KG1").val(SISA);
		    $("#KG1").autoNumeric('update');
			
			$("#KG").val(SISA);	
		    $("#KG").autoNumeric('update'); */
			
			$("#HARGA").val(HARGA);	
	        $("#HARGA").autoNumeric('update');
			
			$("#RPRATE").val(RPRATE);
	        $("#RPRATE").autoNumeric('update');
						
			$("#browseBeliModal").modal("hide");
		}
		
		$("#NO_BL").keypress(function(e){

			if(e.keyCode == 46){
				e.preventDefault();
				browseBeli();
			}
		}); 
		
		/////////////////////////////////////////////////////////////////
	

////////////////////////////

		var dTableBGdg;
		var rowidGdg;
		loadDataBGdg = function(){
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('gdg/browse')}}",

				success: function( response )
				{
					resp = response;
					if(dTableBGdg){
						dTableBGdg.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBGdg.row.add([
							'<a href="javascript:void(0);" onclick="chooseGdg(\''+resp[i].KODE+'\',  \''+resp[i].NAMA+'\' )">'+resp[i].KODE+'</a>',
							resp[i].NAMA,
						
						]);
					}
					dTableBGdg.draw();
				}
			});
		}
		
		dTableBGdg = $("#table-bgdg").DataTable({
			
		});
		
		browseGdg = function(){
			loadDataBGdg();
			$("#browseGdgModal").modal("show");
		}
		
		chooseGdg = function(KODE,NAMA){
			$("#GUDANG").val(NAMA);			
			$("#browseGdgModal").modal("hide");
		}
		
		
		$("#GUDANG").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseGdg();
			}
		}); 
		
		
 		
///////////////////////////////////////////

 	
		
	});		


 	function simpan() {
		hitung();
		
		var tgl = $('#TGL').val();
		var bulanPer = {{session()->get('periode')['bulan']}};
		var tahunPer = {{session()->get('periode')['tahun']}};
		
        var check = '0';
		
		
			if ( $('#NO_BL').val()=='' ) 
            {			
			    check = '1';
				alert("BELI# Harus diisi.");
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
		
		 //mati();
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
	    //$("#CLOSEX").attr("disabled", true);


		$("#CARI").attr("readonly", true);	
	    $("#SEARCHX").attr("disabled", true);

		
	    $("#PLUSX").attr("hidden", false)
		   
			$("#NO_BUKTI").attr("readonly", true);		   
			$("#TGL").attr("readonly", false);
			$("#NO_BL").attr("readonly", true);
			$("#NO_PO").attr("readonly", true);
			$("#KODES").attr("readonly", true);
			$("#NAMAS").attr("readonly", true);

			$("#KD_BRG").attr("readonly", true);
			$("#NA_BRG").attr("readonly", true);
			$("#KG1").attr("readonly", false);
			$("#SUSUT").attr("readonly", true);			
			$("#KG").attr("readonly", false);
			$("#HARGA").attr("readonly", true);
			$("#TOTAL").attr("readonly", true);
			$("#RPRATE").attr("readonly", true);
			$("#RPHARGA").attr("readonly", true);
			$("#RTOTAL").attr("readonly", true);


			$("#TRUCK").attr("readonly", false);
			$("#SOPIR").attr("readonly", false);

			$("#AJU").attr("readonly", false);
			$("#BL").attr("readonly", false);
			$("#NO_CONT").attr("readonly", false);
			$("#SEAL").attr("readonly", false);
		
		
			$("#EMKL").attr("readonly", false);
			$("#GUDANG").attr("readonly", false);
			$("#T_CONT").attr("readonly", false);
			$("#T_TRUCK").attr("readonly", false);


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
		
	    $(".NO_BUKTI").attr("readonly", true);	
		
		$("#TGL").attr("readonly", true);
		$("#NO_BL").attr("readonly", true);
		$("#NO_PO").attr("readonly", true);
		$("#KODES").attr("readonly", true);
		$("#NAMAS").attr("readonly", true);
		$("#KD_BRG").attr("readonly", true);
		$("#NA_BRG").attr("readonly", true);

		$("#KG1").attr("readonly", true);
		$("#SUSUT").attr("readonly", true);
		$("#KG").attr("readonly", true);

		$("#RPRATE").attr("readonly", true);
		$("#RPHARGA").attr("readonly", true);
		$("#RPTOTAL").attr("readonly", true);
		$("#HARGA").attr("readonly", true);
		$("#TOTAL").attr("readonly", true);

		

		$("#TRUCK").attr("readonly", true);
		$("#SOPIR").attr("readonly", true);

		$("#AJU").attr("readonly", true);
		$("#BL").attr("readonly", true);
		$("#NO_CONT").attr("readonly", true);
		$("#SEAL").attr("readonly", true);
		
		
		$("#EMKL").attr("readonly", true);
		$("#GUDANG").attr("readonly", true);
		$("#T_CONT").attr("readonly", true);
		$("#T_TRUCK").attr("readonly", true);


		$("#NOTES").attr("readonly", true);
		
		

		
	}


	function kosong() {
				
		 $('#NO_BUKTI').val("+");	
	//	 $('#TGL').val("");	
		 $('#NO_BL').val("");	
		 $('#NO_PO').val("");	
		 $('#KODES').val("");	
		 $('#NAMAS').val("");	
		 $('#KD_BRG').val("");	
		 $('#NA_BRG').val("");

		 $('#KG1').val("0");	
		 $('#SUSUT').val("0");	
		 $('#KG').val("0");	
		 $('#HARGA').val("0");	
		 $('#TOTAL').val("0");	
		 $('#RPRATE').val("0");			 
		 $('#RPHARGA').val("0");	
		 $('#RPTOTAL').val("0");	
 
		 
				 
		 $('#TRUCK').val("");	
		 $('#SOPIR').val("");
		 
		 $('#AJU').val("");	
		 $('#BL').val("");	
		 $('#NO_CONT').val("");	
		 $('#SEAL').val("");

		 $('#EMKL').val("");	
		 $('#GUDANG').val("");	
		 $('#T_TRUCK').val("");	
		 $('#T_CONT').val("");
	
		
	}
	
	function hapusTrans() {
		let text = "Hapus Transaksi "+$('#NO_BUKTI').val()+"?";
		if (confirm(text) == true) 
		{
			window.location ="{{url('/terima/delete/'.$header->NO_ID .'/?flagz='.$flagz.'&golz=' .$golz.'' )}}";
			//return true;
		} 
		return false;
	}
	

	function CariBukti() {
		
		var flagz = "{{ $flagz }}";
		var golz = "{{ $golz }}";
		var cari = $("#CARI").val();
		var loc = "{{ url('/terima/edit/') }}" + '?idx={{ $header->NO_ID}}&tipx=search&flagz=' + encodeURIComponent(flagz) +'&golz=' + encodeURIComponent(golz) + '&buktix=' +encodeURIComponent(cari);
		window.location = loc;
		
	}
	
</script>
@endsection