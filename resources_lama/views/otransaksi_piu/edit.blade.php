@extends('layouts.main')

<style>
    .card {

    }

    .form-control:focus {
        background-color: #b5e5f9 !important;
    }
</style>

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Edit Pembayaran Piutang{{$header->NO_BUKTI}}</h1>	
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{url('/piu')}}">Pembayaran Piutang</a></li>
						<li class="breadcrumb-item active">Edit {{$header->NO_BUKTI}}</li>
					</ol>
				</div>
			</div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <form action="{{($tipx=='new')? url('/piu/store?flagz='.$flagz.'&golz='.$golz.'') : url('/piu/update/'.$header->NO_ID.'&flagz='.$flagz.'&golz='.$golz.'' ) }}" method="POST" name ="entri" id="entri" >
      
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
								<input name="golz" class="form-control golz" id="flagz" value="{{$golz}}" hidden >
								<input name="searchx" class="form-control searchx" id="searchx" value="{{$searchx ?? ''}}" hidden >

								
                                <div class="col-md-2">
                                    <input type="text" class="form-control NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI"
                                    placeholder="Masukkan Bukti#" value="{{$header->NO_BUKTI}}" readonly>
                                </div>
								
								<div class="col-md-4"></div>
					
								<div class="col-md-3 input-group">

									<input type="text" hidden class="form-control CARI" id="CARI" name="CARI"
                                    placeholder="Cari Bukti#" value="" >
									<button type="button" hidden id='SEARCHX'  onclick="CariBukti()" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>

								</div> 
								
                            </div>

                            <div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="TGL" class="form-label">Tgl</label>
                                </div>
                                <div class="col-md-2">
								  <input class="form-control date" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->TGL))}}">
                                </div>
                            </div>
  

							<div class="form-group row">
                                <div class="col-md-1"align="right">
									<label style="color:red">*</label>									
                                    <label for="NO_SO" class="form-label">SO#</label>
                                </div>

								<div class="col-md-2 input-group" >
									<input type="text" onblur ='ambil_beliy()' class="form-control NO_SO" id="NO_SO" name="NO_SO" placeholder="Masukkan SO"value="{{$header->NO_SO}}" style="text-align: left" readonly >

								</div>
                            </div>

							<div class="form-group row">
                                <div class="col-md-1"align="right">
									<label style="color:red">*</label>									
                                    <label for="NOTES" class="form-label">Uraian</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NOTES" id="NOTES" name="NOTES" value="{{$header->NOTES}}" placeholder="Masukkan Notes" >
                                </div>
        
                            </div>

							
                            <div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="KODEC" class="form-label">Customer#</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KODEC" id="KODEC" name="KODEC" placeholder="Masukkan Suplier#" value="{{$header->KODEC}}"readonly>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control NAMAC" id="NAMAC" name="NAMAC" placeholder="-" value="{{$header->NAMAC}}" readonly>
                                </div>
                            </div>
        
							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="BACNO" class="form-label">Bank#</label>
                                </div>
								<div class="col-md-2 input-group" >
                                  <input type="text" class="form-control BACNO" id="BACNO" name="BACNO" placeholder="Masukkan Bank"value="{{$header->BACNO}}" style="text-align: left" readonly >
                               </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control BNAMA" id="BNAMA" name="BNAMA" placeholder="-" value="{{$header->BNAMA}}" readonly>
                                </div>
                            </div>


							<div class="form-group row">

                                <div class="col-md-1" align="right">
                                    <label for="TOTAL" class="form-label">Total</label>
                                </div>
                                <div class="col-md-2" align="left">
                                    <input type="text" onblur ='ambil_belix()' class="form-control TOTAL" id="TOTAL" name="TOTAL" placeholder="TOTAL" value="{{ number_format($header->TOTAL, 2, '.', ',') }}" style="text-align: right; width:140px" required>
                                </div>

                                <div class="col-md-1" align="right">
                                    <label for="BAYAR" class="form-label">Bayar</label>
                                </div>
                                <div class="col-md-2" align="left">
                                    <input type="text" onblur ='ambil_belix()' class="form-control BAYAR" id="BAYAR" name="BAYAR" placeholder="BAYAR" value="{{ number_format($header->BAYAR, 2, '.', ',') }}" style="text-align: right; width:140px" required>
                                </div>

                                <div class="col-md-1" align="right">
                                    <label for="LAIN" class="form-label">Lain</label>
                                </div>
                                <div class="col-md-2" align="left">
                                    <input type="text" onblur ='ambil_belix()' class="form-control LAIN" id="LAIN" name="LAIN" placeholder="LAIN" value="{{ number_format($header->LAIN, 2, '.', ',') }}" style="text-align: right; width:140px" required>
                                </div>
								
                            </div>



							
                            <table id="datatable" class="table table-striped table-border">
                                <thead>
                                    <tr>
										<th width="100px" style="text-align:center">No.</th>
                                        <th width="200px" style="text-align:center">
								        	<label style="color:red;font-size:20px">* </label>
                                            <label for="BACNO" class="form-label">Bukti#</label>
										</th>
                                        <th width="200px" style="text-align:right">Tgl</th>
                                        <th width="200px" style="text-align:right">Total</th>
                                        <th width="200px" style="text-align:right">Bayar</th>
                                        <th></th>										
                                    </tr>
                                </thead>
        
								<tbody id="detailJual">
								<?php $no=0 ?>	
                                    <tr>
                                        <td>
                                            <input name="REC[]" id="REC0" type="text" class="form-control REC" value="" readonly required>
                                        </td>
                                        <td>
                                            <input name="NO_FAKTUR[]" id="NO_FAKTUR0" type="text" class="form-control NO_FAKTUR" value="" readonly required>
                                        </td>
                                        <td>
                                            <input name="TGL_FAKTUR[]" id="TGL_FAKTUR0" type="text" class="form-control TGL_FAKTUR" value="" readonly required>
                                        </td>
										<td>
										    <input name="XTOTAL[]" value="0" id="XTOTAL0" type="text" style="text-align: right"  class="form-control XTOTAL" readonly>
										</td>                         
										
										<td>
										    <input name="XBAYAR[]" value="0" id="XBAYAR0" type="text" style="text-align: right"  class="form-control XBAYAR" readonly>
										</td>                        
										
                                       
                                    </tr>
								
                                </tbody>

								<tfoot>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><input class="form-control TSISA  text-black font-weight-bold" style="text-align: right"  id="TSISA" name="TSISA" value="0" readonly></td>
                                    <td></td>
                                    <td></td>
                                </tfoot>
                            </table>
							
							<!---------
                            <div class="col-md-2 row">
                                <button type="button" onclick="tambah()" class="btn btn-sm btn-success"><i class="fas fa-plus fa-sm md-3"></i> </button>
                            </div>
							---------->
							
                        </div>


						        
						<div class="mt-3 col-md-12 form-group row">
							<div class="col-md-4">
								<button type="button" hidden id='TOPX'  onclick="location.href='{{url('/piu/edit/?idx=' .$idx. '&tipx=top&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Top</button>
								<button type="button" hidden id='PREVX' onclick="location.href='{{url('/piu/edit/?idx='.$header->NO_ID.'&tipx=prev&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Prev</button>
								<button type="button" hidden id='NEXTX' onclick="location.href='{{url('/piu/edit/?idx='.$header->NO_ID.'&tipx=next&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Next</button>
								<button type="button" hidden id='BOTTOMX' onclick="location.href='{{url('/piu/edit/?idx=' .$idx. '&tipx=bottom&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Bottom</button>
							</div>
							<div class="col-md-5">
								<button type="button" hidden id='NEWX' onclick="location.href='{{url('/piu/edit/?idx=0&tipx=new&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-warning">New</button>
								<button type="button" hidden id='EDITX' onclick='hidup()' class="btn btn-secondary">Edit</button>                    
								<button type="button" hidden id='UNDOX' onclick="location.href='{{url('/piu/edit/?idx=' .$idx. '&tipx=undo&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-info">Undo</button>  
								<button type="button" id='SAVEX' onclick='simpan()'   class="btn btn-success"<i class="fa fa-save"></i>Save</button>

							</div>
							<div class="col-md-3">
								<button type="button" hidden id='HAPUSX'  onclick="hapusTrans()" class="btn btn-outline-danger">Hapus</button>
								<button type="button" id='CLOSEX'  onclick="location.href='{{url('/piu?flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-secondary">Close</button>
							</div>
						</div>
						
						
                    </form>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>

	

	<div class="modal fade" id="browseSoModal" tabindex="-1" role="dialog" aria-labelledby="browsePoModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browsePoModalLabel">Cari So#</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bso">
				<thead>
					<tr>
						<th>So#</th>
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
<script src="{{ asset('js/autoNumerics/autoNumeric.min.js') }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="{{asset('foxie_js_css/bootstrap.bundle.min.js')}}"></script>

<script>
	var idrow = 1;
	var baris = 1;

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	
    $(document).ready(function () {
    idrow=<?=$no?>;
    baris=<?=$no?>;

		$tipx = $('#tipx').val();
		$searchx = $('#CARI').val();
		
		
        if ( $tipx == 'new' )
		{
			 baru();	
           //  tambah();
			 
		}

        if ( $tipx != 'new' )
		{
			 ganti();			
		}    
		
		$("#TOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#BAYAR").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});

	
	
		$("#TSISA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});


		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#XTOTAL" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
			$("#XBAYAR" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
		}	

	
		
        $('body').on('click', '.btn-delete', function() {
			var val = $(this).parents("tr").remove();
			baris--;
			nomor();
			
		});

		$('.date').datepicker({  
            dateFormat: 'dd-mm-yy'
		});
		

///////////////////////////////////////////////////////////////



		var dTableBAccount1;
		loadDataBAccount1 = function(){
			$.ajax(
			{
				type: 'GET',    
				url: '{{url('account/browsebank')}}',
				success: function( response )
				{
					resp = response;
					if(dTableBAccount1){
						dTableBAccount1.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBAccount1.row.add([
							'<a href="javascript:void(0);" onclick="chooseAccount1(\''+resp[i].ACNO+'\',\''+resp[i].NAMA+'\')">'+resp[i].ACNO+'</a>',
							resp[i].NAMA,
						]);
					}
					dTableBAccount1.draw();
				}
			});
		}
		
		dTableBAccount1 = $("#table-baccount1").DataTable({
			
		});
		
		browseAccount1 = function(){
			loadDataBAccount1();
			$("#browseAccount1Modal").modal("show");
		}
		
		chooseAccount1 = function(acno,nama){
			$("#BACNO").val(acno);
			$("#BNAMA").val(nama);
			$("#browseAccount1Modal").modal("hide");
		}
		
		$("#BACNO").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseAccount1();
			}
		}); 
		
		


//////////////////////////////////////////////////////////////

		
//////////////////////////////////////////////////////////////////////////////////////////////////

		
 	var dTableBSo;
		loadDataBSo = function(){
			$.ajax(
			{
				type: 'GET', 		
				url: '{{url('so/browseuang')}}',
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
							'<a href="javascript:void(0);" onclick="chooseSo(\''+resp[i].NO_SO+'\',  \''+resp[i].KODEC+'\', \''+resp[i].NAMAC+'\' , \''+resp[i].TOTAL+'\' , \''+resp[i].BAYAR+'\' , \''+resp[i].SISA+'\'                )">'+resp[i].NO_SO+'</a>',
							resp[i].KODEC,
							resp[i].NAMAC,
							Intl.NumberFormat('en-US').format(resp[i].TOTAL),
							Intl.NumberFormat('en-US').format(resp[i].BAYAR),
							Intl.NumberFormat('en-US').format(resp[i].SISA),
							
						]);
					}
					dTableBSo.draw();
				}
			});
		}
		
		dTableBSo = $("#table-bso").DataTable({
			columnDefs: [
				{
                    className: "dt-right", 
					targets:  [],
					render: $.fn.dataTable.render.number( ',', '.', 2, '' )
				}
			],
		});
		
		browseSo = function(){
			 loadDataBSo();
			$("#browseSoModal").modal("show");
		}
		
		chooseSo = function(NO_SO,KODEC,NAMAC, TOTAL, BAYAR, SISA ){
			$("#NO_SO").val(NO_SO);
			$("#KODEC").val(KODEC);
			$("#NAMAC").val(NAMAC);		
			$("#browseSoModal").modal("hide");
			
			getJual(NO_SO);
			hitung();
		}
		
		$("#NO_SO").keypress(function(e){

			if(e.keyCode == 46){
				e.preventDefault();
				browseSo();
			}
		}); 
		
/////////////////////////////////////////////////////////////////////////////////////////


		
		//////////////////////////////////////////////////////////////////////////////////////////////////


		
	
		
    });


	function cekDetail(){
		var cekFaktur = '';
		$(".XBAYAR").each(function() {
			let z = $(this).closest('tr');
			var TOTALX = parseFloat(z.find('.XTOTAL').val().replace(/,/g, ''));
			var BAYARX = parseFloat(z.find('.XBAYAR').val().replace(/,/g, ''));
	
		});

		return cekFaktur;
	}

 	function simpan() {
		hitung();
		
		var tgl = $('#TGL').val();
		var bulanPer = {{session()->get('periode')['bulan']}};
		var tahunPer = {{session()->get('periode')['tahun']}};

        	var check = '0';
		

			if ( $('#BACNO').val()=='' ) 
            {				
			    check = '1';
				alert("Type Cash Bank Harus Diisi.");
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

			if (baris==0)
			{
				check = '1';
				alert("Data detail kosong (Tambahkan 1 baris kosong jika ingin mengosongi detail)");
			}

		(check==0) ? document.getElementById("entri").submit() : alert('Masih ada kesalahan');

			
	}
	
	
	function getJual(NO_SO)
	{
 		 var mulai = (idrow==baris) ? idrow-1 : idrow;
		 var zbukti = $("#NO_BUKTI").val() ;
		 var ztgl = $("#TGL").val();
		
		 var ztotal = $("#TOTAL").val();
		 var zbayar = $("#BAYAR").val();
		 
		$.ajax(
			{
				type: 'GET',    
				url: "{{url('so/browseisi')}}",
				data: {
					NO_SO: NO_SO,
				},
				success: function( resp )
				{
					var html = '';
					for(i=0; i<resp.length; i++){
						html+=`<tr>
                                    <td><input name='REC[]' id='REC${i}' value='1' type='text' class='REC form-control' onkeypress='return tabE(this,event)' readonly></td>
                                    <td><input name='NO_FAKTUR[]' data-rowid=${i} id='NO_FAKTUR${i}' value="${resp[i].NO_FAKTUR}" type='text' class='form-control NO_FAKTUR'  readonly ></td>
                                    <td><input name='TGL_FAKTUR[]' data-rowid=${i} id='TGL_FAKTUR${i}' value="${resp[i].TGL_FAKTUR}" type='text' class='form-control TGL_FAKTUR'  readonly ></td>

                                    <td><input name='XTOTAL[]' onblur="hitung()" id='XTOTAL${i}' value="${resp[i].TOTAL}" type='text' style='text-align: right' class='form-control XTOTAL text-primary' readonly required></td>
                                    <td><input name='XBAYAR[]' onblur="hitung()" id='XBAYAR${i}' value="${resp[i].BAYAR}" type='text' style='text-align: right' class='form-control XBAYAR text-primary' readonly required></td>
								</tr>`;
								
					}

					idrow=resp.length;
					baris=resp.length;
					
		            if (zbukti == '+' )
					{	
						html+=`<tr>
                                    <td><input name='REC[]' id='REC${idrow}' value='1' type='text' class='REC form-control' onkeypress='return tabE(this,event)' readonly></td>
                                    <td><input name='NO_FAKTUR[]'  id='NO_FAKTUR${idrow}' value="${zbukti}" type='text' class='form-control NO_FAKTUR'  readonly ></td>
                                    <td><input name='TGL_FAKTUR[]'  id='TGL_FAKTUR${idrow}' value="${ztgl}" type='text' class='form-control TGL_FAKTUR'  readonly ></td>

                                    <td><input name='XTOTAL[]' onblur="hitung()" id='XTOTAL${idrow}' value="${ztotal}" type='text' style='text-align: right' class='form-control XTOTAL text-primary' readonly required></td>
                                    <td><input name='XBAYAR[]' onblur="hitung()" id='XBAYAR${idrow}' value="${zbayar}" type='text' style='text-align: right' class='form-control XBAYAR text-primary' readonly required></td>
								</tr>`;

					idrow =idrow + 1;
					baris =baris + 1;

					}
					
					$('#detailJual').html(html);
					$(".XTOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
					$(".XTOTAL").autoNumeric('update');
					
					$(".XBAYAR").autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
					$(".XBAYAR").autoNumeric('update');
					
					
					nomor();
			
				}
			});
	}
	
	
		
    function nomor() {
		var i = 1;
		$(".REC").each(function() {
			$(this).val(i++);
		});
	//	 hitung();
	}

    function hitung() {
		var TSISA = 0;

		$(".XBAYAR").each(function() {
			
			let z = $(this).closest('tr');
			var XTOTALX = parseFloat(z.find('.XTOTAL').val().replace(/,/g, ''));
			var XBAYARX = parseFloat(z.find('.XBAYAR').val().replace(/,/g, ''));

            var SISAX  = XTOTALX - XBAYARX ;

            TSISA +=SISAX;				
		
		});
		
		
		if(isNaN(TSISA)) TSISA = 0;

		$('#TSISA').val(numberWithCommas(TSISA));		
		$("#TSISA").autoNumeric('update');
	}
	
	function ambil_belix() {
	
		 var zbukti = $("#NO_BUKTI").val() ;
		 var ztgl = $("#TGL").val();
		
		 var ztotal = $("#TOTAL").val();
		 var zbayar = $("#BAYAR").val();
		
		
		jumlahdata = 100;
		baris1 = baris - 1;
		
		for (i = 0; i <= jumlahdata; i++) {
			
			if ( i == baris1 )
			{
				
			     $("#NO_FAKTUR" + i.toString()).val(zbukti);
			     $("#TGL_FAKTUR" + i.toString()).val(ztgl);
			     $("#XTOTAL" + i.toString()).val(ztotal);
			     $("#XBAYAR" + i.toString()).val(zbayar);
					
					}			
		}
		
		hitung();
		
		
	}
	
	function ambil_beliy() {
		
		 var NO_SO = $("#NO_SO").val() ;
         getJual(NO_SO);
		
			
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
	    //$("#CLOSEX").attr("disabled", true);


		$("#CARI").attr("readonly", true);	
	    $("#SEARCHX").attr("disabled", true);
		
	    $("#PLUSX").attr("hidden", false)
		   
			$("#NO_BUKTI").attr("readonly", true);		   
			$("#TGL").attr("readonly", false);
			$("#NO_SO").attr("readonly", true);
			$("#KODEC").attr("readonly", true);
			$("#NAMAC").attr("readonly", true);
			$("#BACNO").attr("readonly", true);
			$("#BNAMA").attr("readonly", true);
			
			$("#NOTES").attr("readonly", false);
		
	

		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#REC" + i.toString()).attr("readonly", true);
			$("#NO_FAKTUR" + i.toString()).attr("readonly", true);
			$("#TGL_FAKTUR" + i.toString()).attr("readonly", true);
			$("#XTOTAL" + i.toString()).attr("readonly", true);
			$("#XBAYAR" + i.toString()).attr("readonly", false);		
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
		$("#NO_SO").attr("readonly", true);
		$("#KODEC").attr("readonly", true);
		$("#NAMAC").attr("readonly", true);
		$("#BACNO").attr("readonly", true);
		$("#BNAMA").attr("readonly", true);
		$("#NOTES").attr("readonly", true);

		
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#REC" + i.toString()).attr("readonly", true);
			$("#NO_FAKTUR" + i.toString()).attr("readonly", true);
			$("#TGL_FAKTUR" + i.toString()).attr("readonly", true);
			$("#XTOTAL" + i.toString()).attr("readonly", true);
			$("#XBAYAR" + i.toString()).attr("readonly", true);		
		}


		
	}


	function kosong() {
				
		 $('#NO_BUKTI').val("+");	
	//	 $('#TGL').val("");	
		 $('#NO_SO').val("");
		 $('#BACNO').val("");	
		 $('#BNAMA').val("");
		 $('#KODEC').val("");	
		 $('#NAMAC').val("");	
		 $('#NOTES').val("");	
		 $('#TSISA').val("0.00");
		 
		 $('#TOTAL').val("0.00");
		 $('#BAYAR').val("0.00");
		 
		var html = '';
		$('#detailx').html(html);	
		
	}
	
	function hapusTrans() {
		let text = "Hapus Transaksi "+$('#NO_BUKTI').val()+"?";
		if (confirm(text) == true) 
		{
			window.location ="{{url('/piu/delete/'.$header->NO_ID .'/?flagz='.$flagz.'&golz=' .$golz.'' )}}";
			//return true;
		} 
		return false;
	}
	

	function CariBukti() {
		
		var flagz = "{{ $flagz }}";
		var golz = "{{ $golz }}";
		var cari = $("#CARI").val();
		var loc = "{{ url('/piu/edit/') }}" + '?idx={{ $header->NO_ID}}&tipx=search&flagz=' + encodeURIComponent(flagz) +'&golz=' + encodeURIComponent(golz) + '&buktix=' +encodeURIComponent(cari);
		window.location = loc;
		
	}
	
	function tambah() {

        var x = document.getElementById('datatable').insertRow(baris + 1);
        
		 var zbukti = $("#NO_BUKTI").val() ;
		 var ztgl = $("#TGL").val();
		
		 var ztotal = $("#TOTAL").val();
		 var zbayar = $("#BAYAR").val();
		
		html=`<tr>

                <td>
					<input name='REC[]' id='REC${idrow}' type='text' class='REC form-control' onkeypress='return tabE(this,event)' readonly>
	            </td>
						       
                <td>
				    <input name='NO_FAKTUR[]' data-rowid=${idrow}  value ="${zbukti}" id='NO_FAKTUR${idrow}' type='text' class='form-control  NO_FAKTUR' required readonly>
                </td>
				
				<td>
		            <input name='TGL_FAKTUR[]' id='TGL_FAKTUR${idrow}' value ="${ztgl}" type='text' style='text-align: right' class='form-control TGL_FAKTUR text-primary' readonly required >
                </td>
				
				<td>
		            <input name='XTOTAL[]'  value ="${ztotal}" id='XTOTAL${idrow}' type='text' style='text-align: right' class='form-control XTOTAL text-primary' readonly required >
                </td>
				
				<td>
		            <input name='XBAYAR[]'   value ="${zbayar}"  id='XBAYAR${idrow}' type='text' style='text-align: right' class='form-control XBAYAR text-primary' readonly required >
                </td>
								
         </tr>`;
				
        x.innerHTML = html;
        var html='';
		
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#XTOTAL" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});

			$("#XBAYAR" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
				 
		}
 
		

        idrow++;
        baris++;
        nomor();
		
		$(".ronly").on('keydown paste', function(e) {
             e.preventDefault();
             e.currentTarget.blur();
         });

     }
</script>

<script src="autonumeric.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
<script src="https://unpkg.com/autonumeric"></script>
@endsection