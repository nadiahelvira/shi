@extends('layouts.main')


<style>
    .card {

    }

    .form-control:focus {
        background-color: #E0FFFF !important;
    }
	
	.NAMA_KET {
        background-color: #FFFACD !important;
		
    }
	

	.table-scrollable {
		margin: 0;
		padding: 0;
	}

	table {
		table-layout: fixed !important;
	}

	.uppercase {
		text-transform: uppercase;
	}
	
</style>

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <!-- /.col -->
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
																	
                    <form action="{{($tipx=='new')? url('/bl/store?golz='.$golz.'') : url('/bl/update/'.$header->NO_ID.'&golz='.$golz.'' ) }}" method="POST" name ="entri" id="entri" >
  
                        @csrf
        
                        <div class="tab-content mt-3">
        
                            <div class="form-group row">
                                <div class="col-md-1">
                                    <label for="NO_BUKTI" class="form-label">Bukti#.</label>
                                </div>
								
				
                                <input type="text" class="form-control NO_ID" id="NO_ID" name="NO_ID"
                                    placeholder="Masukkan NO_ID" value="{{$header->NO_ID ?? ''}}" hidden readonly>
																		
                                <div class="col-md-2">

									<input name="tipx" class="form-control tipx" id="tipx" value="{{$tipx}}" hidden >
									<input name="golz" class="form-control golz" id="golz" value="{{$golz}}" hidden >
									<input name="searchx" class="form-control searchx" id="searchx" value="{{$searchx ?? ''}}" hidden >

                                    <input type="text" class="form-control NO_BUKTI" onclick="select()" id="NO_BUKTI" name="NO_BUKTI"
                                    placeholder="Masukkan Bukti#" value="{{$header->NO_BUKTI}}" >
								</div>
								
								<div class="col-md-6"></div>
					
								<div class="col-md-3 input-group">

									<input type="text" class="form-control CARI" id="CARI" name="CARI"
                                    placeholder="Cari Bukti#" value="" >
									<button type="button" id='SEARCHX'  onclick="CariBukti()" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>

								</div> 
							</div>
							
							<!-- <div class="form-group row">
                                <div class="col-md-1">
                                    <label for="NO_BUKTI2" class="form-label">Bukti Manual</label>
                                </div>
                                
								<div class="col-md-2">
									<input type="text" class="form-control NO_BUKTI2" id="NO_BUKTI2" name="NO_BUKTI2"
                                    placeholder="Manual#" value='' >
								</div>
                            </div> -->
							
        
							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="TGL" class="form-label">Tgl</label>
                                </div>
                                
								<div class="col-md-2">
 
								  <input class="form-control date" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->TGL))}}" >
								
								</div>		
								
                            </div>
        
		
                            <div class="form-group row">
                                <div class="col-md-1">
									<label style="color:red;font-size:20px">* </label>	
                                    <label for="NO_BELI" class="form-label">No Beli</label>
                                </div>
                                <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control NO_BELI" id="NO_BELI" name="NO_BELI" placeholder="Pilih Suplier"value="{{$header->NO_BELI}}" style="text-align: left" readonly >
        						  <button type="button" class="btn btn-primary" onclick="browseBeli()"><i class="fa fa-search"></i></button>
                                </div>

								 <div class="col-md-1">
                                    <label for="NO_PO" class="form-label">No PO</label>
                                </div>
								
								 <div class="col-md-3">
                                    <input type="text" class="form-control NO_PO" id="NO_PO" name="NO_PO"
                                    placeholder="" value="{{$header->NO_PO ?? ''}}" readonly >
                                 </div>
								
							</div>
							
							<div class="form-group row">
								<div class="col-md-1">
                                    <label for="KODES" class="form-label">Supplier</label>
                                </div>
                                 <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control KODES" id="KODES" name="KODES" placeholder="" value="{{$header->KODES ?? ''}}"  style="text-align: left" readonly >
                                 </div>
								
								 <div class="col-md-4">
                                    <input type="text" class="form-control NAMAS" id="NAMAS" name="NAMAS"
                                    placeholder="" value="{{$header->NAMAS ?? ''}}" readonly >
                                 </div>
							</div>

							<div class="form-group row">
								<div class="col-md-1">
                                    <label for="KD_BRG" class="form-label">Barang</label>
                                </div>
                                 <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control KD_BRG" id="KD_BRG" name="KD_BRG" placeholder="" value="{{$header->KD_BRG ?? ''}}"  style="text-align: left" readonly >
                                 </div>
								
								 <div class="col-md-4">
                                    <input type="text" class="form-control NA_BRG" id="NA_BRG" name="NA_BRG"
                                    placeholder="" value="{{$header->NA_BRG ?? ''}}" readonly >
                                 </div>
							</div>

							<div class="form-group row">
								<div class="col-md-1">
                                    <label for="NOTES" class="form-label">Notes</label>
                                </div>
                                 <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control NOTES" id="NOTES" name="NOTES" placeholder="" value="{{$header->NOTES ?? ''}}"  style="text-align: left" readonly >
                                 </div>
							</div>
        
        
							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="AJU" class="form-label">Aju</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control AJU" id="AJU" name="AJU" value="{{$header->AJU ?? ''}}"  placeholder="">
                                </div>

								<!-- <div class="col-md-2" align="right">
                                    <label>No Bukti Terakhir</label>
                                </div>
								<div class="col-md-3">
									<input name="xbukti" class="form-control xbukti" id="xbukti" value="{{$xbukti}}" readonly>
                                </div> -->
                            </div>
							
						<hr style="margin-top: 30px; margin-buttom: 30px">
							
						<!-- <div style="overflow-y:scroll; height:200px;" class="col-md-12 scrollable" align="right"> -->
						<div style="overflow-y:scroll; " class="col-md-12 scrollable" align="right">
							
							<!-- <table id="datatable" class="table table-striped table-border table-scrollable">                 -->
							<table id="datatable" class="table table-striped table-border">                
								<thead>
                                    <tr>
                                        <th width="50px" style="text-align:center">No</th>
                                        <th width="100px" style="text-align:center">No Container</th>
                                        <th width="100px" style="text-align:center">Seal</th>
                                        <th width="100px" style="text-align:center">EMKL</th>
                                        <th width="100px" style="text-align:center">Kg</th>
                                        <th width="100px" style="text-align:center">Notes</th>
                                        <th width="100px" style="text-align:center">Tgl Datang</th>
                                        <th width="100px" style="text-align:center"></th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php $no=0 ?>
								@foreach ($detail as $detail)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="NO_ID[]" id="NO_ID{{$no}}" type="text" value="{{$detail->NO_ID}}" 
                                            class="form-control NO_ID" onkeypress="return tabE(this,event)" readonly>
                                            
                                            <input name="REC[]" id="REC{{$no}}" type="text" value="{{$detail->REC}}" 
                                            class="form-control REC" onkeypress="return tabE(this,event)" readonly>
                                        </td>

                                        <td>
                                            <input name="NO_CONT[]" id="NO_CONT{{$no}}" type="text" value="{{$detail->NO_CONT}}"
                                              class="form-control NO_CONT ">
										</td>
			
										 <td>
                                             <input name="SEAL[]" id="SEAL{{$no}}" type="text" value="{{$detail->SEAL}}"
                                             class="form-control SEAL" >
                                         </td>
										<td >
                                            <input name="EMKL[]" id="EMKL{{$no}}" type="text" value="{{$detail->EMKL}}"
                                            class="form-control EMKL">
                                        </td>

										<td>
                                            <input name="KG[]"  onblur="hitung()" style="text-align: right" id="KG{{$no}}" type="text" value="{{$detail->KG}}"
                                            class="form-control KG">
                                        </td>
									
										<td>
                                            <input name="KET[]" id="KET{{$no}}" type="text" value="{{$detail->KET}}"
                                            class="form-control uppercase  KET" required>
                                        </td>										
										<td>
											<input name="TGL_DTG[]" id="TGL_DTG{{$no}}" type="text" class="date form-control text_input" data-date-format="dd-mm-yyyy" value="{{$detail->TGL_DTG}}">
										</td>
                                        
										<td>
                                            <button type="button" id="DELETEX{{$no}}" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </button>
                                        </td>
                                        
                                    </tr>
								<?php $no++; ?>	
								@endforeach
									
									
                                </tbody>
								<tfoot>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
									<td>
										<input class="form-control TOTAL_KG  text-primary font-weight-bold"
                                         style="text-align: right" id="TOTAL_KG" name="TOTAL_KG"
                                         value="{{$header->TOTAL_KG ?? ''}}" readonly>
									</td>
                                    <td></td>
									<td></td>
                                </tfoot>
							</table>     
							                           
						</div>


						   
						   
                            <div class="col-md-2 row">
                               <a type="button" id='PLUSX' onclick="tambah()" class="fas fa-plus fa-sm md-3" style="font-size: 20px"></a>					
							</div>			
                                 
						<div class="mt-3 col-md-12 form-group row">
							<div class="col-md-4">
								<button type="button" id='TOPX'  onclick="location.href='{{url('/bl/edit/?idx=' .$idx. '&tipx=top&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Top</button>
								<button type="button" id='PREVX' onclick="location.href='{{url('/bl/edit/?idx='.$header->NO_ID.'&tipx=prev&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Prev</button>
								<button type="button" id='NEXTX' onclick="location.href='{{url('/bl/edit/?idx='.$header->NO_ID.'&tipx=next&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Next</button>
								<button type="button" id='BOTTOMX' onclick="location.href='{{url('/bl/edit/?idx=' .$idx. '&tipx=bottom&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Bottom</button>
							</div>
							<div class="col-md-5">
								<button type="button" id='NEWX' onclick="location.href='{{url('/bl/edit/?idx=0&tipx=new&golz='.$golz.'' )}}'" class="btn btn-warning">New</button>
								<button type="button" id='EDITX' onclick='hidup()' class="btn btn-secondary">Edit</button>                    
								<button type="button" id='UNDOX' onclick="location.href='{{url('/bl/edit/?idx=' .$idx. '&tipx=undo&golz='.$golz.'' )}}'" class="btn btn-info">Undo</button>  
								<button type="button" id='SAVEX' onclick='simpan()'   class="btn btn-success" class="fa fa-save"></i>Save</button>
								<!-- <button type="button" id='PRINTX' onclick="location.href='{{url('/bl/print/'.$header->NO_ID.'' )}}'" class="btn btn-outline-primary">Print</button>  
								<button type="button" id='PRINTX' onclick="location.href='{{url('/bl/print2/'.$header->NO_ID.'' )}}'" class="btn btn-outline-primary">Print 2</button>   -->

							</div>
							<div class="col-md-3">
								<button type="button" id='HAPUSX'  onclick="hapusTrans()" class="btn btn-outline-danger">Hapus</button>
								<button type="button" id='CLOSEX'  onclick="location.href='{{url('/bl?golz='.$golz.'' )}}'" class="btn btn-outline-secondary">Close</button>
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
	
	

	
	<div class="modal fade" id="browseBeliModal" tabindex="-1" role="dialog" aria-labelledby="browseBeliModalLabel" aria-hidden="true">
	  <div class="modal-dialog mw-100 w-75" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseBeliModalLabel">Cari Beli</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bbeli">
				<thead>
					<tr>
						<th>No Beli</th>
						<th>No PO</th>
						<th>Kode Supplier</th>
						<th>Nama Supplier</th>
						<th>Kode Barang</th>
						<th>Nama Barang</th>
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


	<div class="modal fade" id="browseAccount1Modal" tabindex="-1" role="dialog" aria-labelledby="browseAccount1ModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseAccount1ModalLabel">Cari Account</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-baccount1">
				<thead>
					<tr>
						<th>Account#</th>
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
<!-- TAMBAH 1 -->

<script src="{{ asset('js/autoNumerics/autoNumeric.min.js') }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="{{asset('foxie_js_css/bootstrap.bundle.min.js')}}"></script>

<script>

	var idrow = 1;
	var baris = 1;
    function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

// TAMBAH HITUNG
	$(document).ready(function() {
	<?php $searchx = '' ?>
    idrow=<?=$no?>;
    baris=<?=$no?>;

		$('body').on('keydown', 'input, select', function(e) {
			if (e.key === "Enter") {
				var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
				focusable = form.find('input,select,textarea').filter(':visible');
				next = focusable.eq(focusable.index(this)+1);
				console.log(next);
				if (next.length) {
					next.focus().select();
				} else {
					tambah();
					var nomer = idrow-1;
					console.log("NO_CONT"+nomer);
					document.getElementById("NO_CONT"+nomer).focus();
					// form.submit();
				}
				return false;
			}
		});
	
		$tipx = $('#tipx').val();
		$searchx = $('#CARI').val();
		
		
        if ( $tipx == 'new' )
		{
			 baru();			
			 tambah();			
		}

        if ( $tipx != 'new' )
		{
			 ganti();			
		}    
		
		$("#TOTAL_KG").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});

		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#KG" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
		}
		
		
		$('body').on('click', '.btn-delete', function() {
			var val = $(this).parents("tr").remove();
			baris--;
			nomor();
		});
		
		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		})
		
		
	
		
///////////////////////////////////////////////////////////////////////
 
		


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		
		
		//CHOOSE Bacno
 		var dTableBBeli;
		loadDataBBeli = function(){
			$.ajax(
			{
				type: 'GET',    
				url: '{{url('beli/browsebl')}}',
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
							'<a href="javascript:void(0);" onclick="chooseBeli(\''+resp[i].NO_BUKTI+'\',\''+resp[i].NO_PO+'\',\''+resp[i].KODES+'\',\''+resp[i].NAMAS+'\',\''+resp[i].KD_BRG+'\',\''+resp[i].NA_BRG+'\',\''+resp[i].AJU+'\')">'+resp[i].NO_BUKTI+'</a>',
							resp[i].NO_PO,
							resp[i].KODES,
							resp[i].NAMAS,
							resp[i].KD_BRG,
							resp[i].NA_BRG,
						]);
					}
					dTableBBeli.draw();
				}
			});
		}
		
		dTableBBeli = $("#table-bbeli").DataTable({
			
		});
		
		browseBeli = function(){
			loadDataBBeli();
			$("#browseBeliModal").modal("show");
		}
		
		chooseBeli = function(NO_BUKTI, NO_PO, KODES, NAMAS, KD_BRG, NA_BRG, AJU){
			$("#NO_BELI").val(NO_BUKTI);
			$("#NO_PO").val(NO_PO);
			$("#KODES").val(KODES);
			$("#NAMAS").val(NAMAS);
			$("#KD_BRG").val(KD_BRG);
			$("#NA_BRG").val(NA_BRG);
			$("#AJU").val(AJU);
			$("#browseBeliModal").modal("hide");
		}
		
		$("#NO_BELI").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseBeli();
			}
		}); 
		
//////////////////////////////////////////////////////////////////////////////////////////////////

	});



//////////////////////////////////////////////////////////////////

	function cekDetail(){
		var cekCont = '';
		$(".NO_CONT").each(function() {
			
			let z = $(this).closest('tr');
			var NO_CONTX = z.find('.NO_CONT').val();
			
			if( NO_CONTX =="" )
			{
					cekCont = '1';
					
			}	
		});
		
		return cekCont;
	}



///////////////////////////////////////
    function getNacno(id) {

				var urut = id.substring(4, 9);
				$('#NAMA_KET').val($('#NAMA'+urut).val());
	}
			
	function cekBukti(no_bukti) {

				var hasilcek = '';
		
                $.ajax({
                    type: "GET",
                    url: "{{ url('bl/cek_bukti') }}",
                    async: true,
                    data: ({
                        NO_BUKTI: no_bukti,
                    }),
                    success: function(data) {
                        if (data.length > 0) {
                            $.each(data, function(i, item) {
                                hasilCek = data[i].ADA;
                            });
                        }
                    },
                    error: function() {
                        alert('Error cekBukti occured');
                    }
                });
                return hasilCek;
    }
			
    function simpan() {

                hitung();

                var no_bukti = $('#NO_BUKTI').val();
				
                var tgl = $('#TGL').val();
                var bulanPer = {{ session()->get('periode')['bulan'] }};
                var tahunPer = {{ session()->get('periode')['tahun'] }};

                var check = '0';
				var tipx = $('#tipx').val();

	
				if ( tipx == 'new' )
				{
					
					if ( no_bukti == '+' )
					{
						check = '1';
						alert("No Bukti masih kosong ");
					}
					
					
					// if ( xx > 3 )
					// {
					// 	check = '1';
					// 	alert("Bukti yang dimasukkan lebih dari 3");
					// }
					
					
					//  if ( cekBukti(no_bukti) )
					//  {
					// 		check = '1';
					// 		alert("No Bukti sudah pernah ada.");
					
					//  }
						
					
					
				}
				
				// if ( tipx == 'new' )
				// {
				// 	var xx = no_bukti2.length;
				
				// 	if (no_bukti2 != ''){
						
				// 		if ( xx < 4 )
				// 		{
				// 			check = '1';
				// 			alert("Nomer Bukti Manual yang dimasukkan kurang dari 4, Masukkan dengan 4 Digit Angka");
				// 		}
						
				// 		if ( xx > 4 )
				// 		{
				// 			check = '1';
				// 			alert("Nomer Bukti Manual yang dimasukkan lebih dari 4, Masukkan dengan 4 Digit Angka");
				// 		}

				// 	} else {
				// 			$('#NO_BUKTI').val('');

				// 			// check = '1';
				// 			// alert("Nomor Manual Harus Terisi");
				// 	}
					

					
				// }


				if ( cekDetail() )
				{	
					check = '1';
					alert("Ada No Container Kosong Didetail.");
				}
			
                if ($('#NO_BELI').val() == '') {
                    check = '1';
                    alert("No Beli Harus di pilih.");
                }

                if (tgl.substring(3, 5) != bulanPer) {

                    check = '1';
                    alert("Bulan tidak sama dengan Periode");
                }

                if (tgl.substring(tgl.length - 4) != tahunPer) {
                    check = '1';
                    alert("Tahun tidak sama dengan Periode");

				}


		(check==0) ? document.getElementById("entri").submit() : alert('Masih ada kesalahan');


    }
	


    function nomor() {
		var i = 1;
		$(".REC").each(function() {
			$(this).val(i++);
		});
		// hitung();
	}

    function hitung() {
		var TOTAL_KG = 0;


		$(".KG").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if(isNaN(val)) val = 0;
			TOTAL_KG+=val;
		});
		
		if(isNaN(TOTAL_KG)) TOTAL_KG = 0;

		$('#TOTAL_KG').val(numberWithCommas(TOTAL_KG));
		$("#TOTAL_KG").autoNumeric('update');

	}
	

	function baru() {
		
		 kosong();
		 hidup();
	
	}
	
	function ganti() {
		
		 mati();
	
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
	    $("#CLOSEX").attr("disabled", true);

		$("#CARI").attr("readonly", true);	
	    $("#SEARCHX").attr("disabled", true);
		
	    $("#PLUSX").attr("hidden", false)
		   	
			$("#TGL").attr("readonly", false);
			$("#NO_BELI").attr("readonly", true);
			$("#NO_PO").attr("readonly", true);
			$("#KODES").attr("readonly", true);
			$("#NAMAS").attr("readonly", true);
			$("#KD_BRG").attr("readonly", true);
			$("#NA_BRG").attr("readonly", true);
			$("#AJU").attr("readonly", true);
			$("#NOTES").attr("readonly", false);
		
	
		$tipx = $('#tipx').val();
	
		
        if ( $tipx != 'new' )
		{
			$("#NO_BUKTI").attr("readonly", true);				
		} 
		else 
		{
			$("#NO_BUKTI").attr("readonly", false);				
		}

		
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#REC" + i.toString()).attr("readonly", true);
			$("#NO_CONT" + i.toString()).attr("readonly", false);
			$("#SEAL" + i.toString()).attr("readonly", false);
			$("#EMKL" + i.toString()).attr("readonly", false);
			$("#KG" + i.toString()).attr("readonly", false);
			$("#KET" + i.toString()).attr("readonly", false);
			$("#TGL_DTG" + i.toString()).attr("readonly", false);
			$("#DELETEX" + i.toString()).attr("hidden", false);
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
		$("#NO_BELI").attr("readonly", true);
		$("#NO_PO").attr("readonly", true);
		$("#NO_PO").attr("readonly", true);
		$("#KODES").attr("readonly", true);
		$("#NAMAS").attr("readonly", true);
		$("#KD_BRG").attr("readonly", true);
		$("#NA_BRG").attr("readonly", true);
		$("#AJU").attr("readonly", true);

		
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#REC" + i.toString()).attr("readonly", true);
			$("#NO_CONT" + i.toString()).attr("readonly", true);
			$("#SEAL" + i.toString()).attr("readonly", true);
			$("#EMKL" + i.toString()).attr("readonly", true);
			$("#KG" + i.toString()).attr("readonly", true);
			$("#KET" + i.toString()).attr("readonly", true);
			$("#TGL_DTG" + i.toString()).attr("readonly", true);
			$("#DELETEX" + i.toString()).attr("hidden", true);
		}


		
	}


	function kosong() {
				
		 $('#NO_BUKTI').val("+");	
	//	 $('#TGL').val("");	
		 $('NO_CONT').val("");	
		 $('#SEAL').val("");	
		 $('#AJU').val("");	
		 $('#TOTAL_KG').val("0.00");	
		 $('#TGL_DTG').val("00-00-0000");	

		 
		var html = '';
		$('#detailx').html(html);	
		
	}
	
	function hapusTrans() {
		let text = "Hapus Transaksi "+$('#NO_BUKTI').val()+"?";
		if (confirm(text) == true) 
		{
			window.location ="{{url('/bl/delete/'.$header->NO_ID .'/?golz='.$golz.'' )}}";
			//return true;
		} 
		return false;
	}
	

	function CariBukti() {
		
		var golz = "{{ $golz }}";
		var cari = $("#CARI").val();
		var loc = "{{ url('/bl/edit/') }}" + '?idx={{ $header->NO_ID}}&tipx=search&golz=' + encodeURIComponent(golz) + '&buktix=' +encodeURIComponent(cari);
		window.location = loc;
		
	}


    function tambah() {

        var x = document.getElementById('datatable').insertRow(baris + 1);
 
		html=`<tr>

                <td>
 					<input name='NO_ID[]' id='NO_ID${idrow}' type='hidden' class='form-control NO_ID' value='new' readonly> 
					<input name='REC[]' id='REC${idrow}' type='text' class='REC form-control' onkeypress='return tabE(this,event)' readonly>
	            </td>
						       
                <td>
				    <input name='NO_CONT[]' data-rowid=${idrow}  id='NO_CONT${idrow}' type='text' class='form-control  NO_CONT'>
                </td>
                <td>
				    <input name='SEAL[]'   id='SEAL${idrow}' type='text' class='form-control  SEAL' required >
                </td>
                <td >
				    <input name='EMKL[]' data-rowid=${idrow} id='EMKL${idrow}' type='text' class='form-control  EMKL' >
                </td>
				
				<td>
		            <input name='KG[]' onclick='select()' onblur='hitung()' value='0' id='KG${idrow}' type='text' style='text-align: right' class='form-control KG text-primary' required >
                </td>
                <td>
				    <input name='KET[]' id='KET${idrow}' type='text' class='form-control uppercase  KET' required>
                </td>
				
				<td>
					<input name="TGL_DTG[]" ocnlick='select()' id="TGL_DTG{idrow}" type="text" class="date form-control text_input" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {
																																										} else echo 											date('00-00-0000'); ?>" onclick="select()">
				</td>

                <td>
					<button type='button' id='DELETEX${idrow}'  class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>
                </td>				
         </tr>`;
				
        x.innerHTML = html;
        var html='';
		
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#KG" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
		}
		
		

		idrow++;
		baris++;
		nomor();

		$(".ronly").on('keydown paste', function(e) {
			e.preventDefault();
			e.currentTarget.blur();
		});
		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		});
     }
</script>
@endsection
