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
					<h1 class="m-0">Edit Pembayaran Hutang {{$header->NO_BUKTI}}</h1>	
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{url('/hut')}}">Pembayaran Hutang</a></li>
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

                    <form action="{{($tipx=='new')? url('/hut/store?flagz='.$flagz.'&golz='.$golz.'') : url('/hut/update/'.$header->NO_ID.'&flagz='.$flagz.'&golz='.$golz.'' ) }}" method="POST" name ="entri" id="entri" >
      
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
                                    <label for="NO_PO" class="form-label">PO#</label>
                                </div>

								<div class="col-md-2 input-group" >
									<input type="text" class="form-control NO_PO" id="NO_PO" name="NO_PO" placeholder="Masukkan PO"value="{{$header->NO_PO}}" style="text-align: left" readonly >

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
                                    <label for="KODES" class="form-label">Suplier#</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KODES" id="KODES" name="KODES" placeholder="Masukkan Suplier#" value="{{$header->KODES}}"readonly>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control NAMAS" id="NAMAS" name="NAMAS" placeholder="-" value="{{$header->NAMAS}}" readonly>
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
							
							
                            <table id="datatable" class="table table-striped table-border">
                                <thead>
                                    <tr>
										<th width="100px" style="text-align:center">No.</th>
                                        <th width="200px" style="text-align:center">
								        	<label style="color:red;font-size:20px">* </label>
                                            <label for="BACNO" class="form-label">Faktur#</label>
										</th>
                                       <th width="200px" style="text-align:right">Total</th>
                                        <th width="200px" style="text-align:right">Bayar</th>
                                        <th width="200px" style="text-align:right">Sisa</th>
                                        <th></th>										
                                    </tr>
                                </thead>
        
								<tbody>
								<?php $no=0 ?>
								@foreach ($detail as $detail)		
                                    <tr>
                                        <td>
                                            <input type="hidden" name="NO_ID[]{{$no}}" id="NO_ID" type="text" value="{{$detail->NO_ID}}" 
                                            class="form-control NO_ID" onkeypress="return tabE(this,event)" readonly>
											
                                            <input name="REC[]" id="REC{{$no}}" type="text" value="{{$detail->REC}}" class="form-control REC" onkeypress="return tabE(this,event)" readonly style="text-align:center">
                                        </td>
                                        <td>
                                            <input name="NO_FAKTUR[]" id="NO_FAKTUR{{$no}}" type="text" class="form-control NO_FAKTUR" value="{{$detail->NO_FAKTUR}}" readonly required>
                                        </td>


										<td>
										    <input name="TOTAL[]" onclick="select()" onblur="hitung()" value="{{$detail->TOTAL}}" id="TOTAL{{$no}}" type="text" style="text-align: right"  class="form-control TOTAL" readonly>
										</td>                         
										
										<td>
										    <input name="BAYAR[]" onclick="select()" onblur="hitung()" value="{{$detail->BAYAR}}" id="BAYAR{{$no}}" type="text" style="text-align: right"  class="form-control BAYAR">
										</td>                        
										
										<td>
										    <input name="LAIN[]" onclick="select()" onblur="hitung()" value="{{$detail->LAIN}}" id="LAIN{{$no}}" type="text" style="text-align: right"  class="form-control LAIN">
										</td>
										
										<td>
										    <input name="SISA[]" onclick="select()" onblur="hitung()" value="{{$detail->SISA}}" id="SISA{{$no}}" type="text" style="text-align: right"  class="form-control SISA" readonly>
										</td>
                                        
										
										<td>
										
                                            <button type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
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
                                    <td><input class="form-control TBAYAR  text-light font-weight-bold" style="text-align: right"  id="TBAYAR" name="TBAYAR" value="{{$header->BAYAR}}" readonly></td>
                                    <td></td>
                                    <td></td>
                                </tfoot>
                            </table>
							
                            <div class="col-md-2 row">
                                <button type="button" onclick="tambah()" class="btn btn-sm btn-success"><i class="fas fa-plus fa-sm md-3"></i> </button>
                            </div>
							
                        </div>


						        
						<div class="mt-3 col-md-12 form-group row">
							<div class="col-md-4">
								<button type="button" hidden id='TOPX'  onclick="location.href='{{url('/hut/edit/?idx=' .$idx. '&tipx=top&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Top</button>
								<button type="button" hidden id='PREVX' onclick="location.href='{{url('/hut/edit/?idx='.$header->NO_ID.'&tipx=prev&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Prev</button>
								<button type="button" hidden id='NEXTX' onclick="location.href='{{url('/hut/edit/?idx='.$header->NO_ID.'&tipx=next&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Next</button>
								<button type="button" hidden id='BOTTOMX' onclick="location.href='{{url('/hut/edit/?idx=' .$idx. '&tipx=bottom&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Bottom</button>
							</div>
							<div class="col-md-5">
								<button type="button" hidden id='NEWX' onclick="location.href='{{url('/hut/edit/?idx=0&tipx=new&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-warning">New</button>
								<button type="button" hidden id='EDITX' onclick='hidup()' class="btn btn-secondary">Edit</button>                    
								<button type="button" hidden id='UNDOX' onclick="location.href='{{url('/hut/edit/?idx=' .$idx. '&tipx=undo&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-info">Undo</button>  
								<button type="button" id='SAVEX' onclick='simpan()'   class="btn btn-success"<i class="fa fa-save"></i>Save</button>

							</div>
							<div class="col-md-3">
								<button type="button" hidden id='HAPUSX'  onclick="hapusTrans()" class="btn btn-outline-danger">Hapus</button>
								<button type="button" id='CLOSEX'  onclick="location.href='{{url('/hut?flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-secondary">Close</button>
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
		

	<div class="modal fade" id="browseBeliModal" tabindex="-1" role="dialog" aria-labelledby="browseBeliModalLabel" aria-hidden="true">
	  <div class="modal-dialog mw-100 w-75" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseBeliModalLabel">Cari Beli#</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bbeli">
				<thead>
					<tr>
						<th>Beli#</th>
						<th>Kode</th>
						<th>-</th>
						<th>Total</th>
						<th>Bayar</th>
						<th>Sisa</th>
						<th>Pilih</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		  </div>
		  <div class="modal-footer">
			<button type="button" onclick="chooseBeliArr()" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
             tambah();
			 
		}

        if ( $tipx != 'new' )
		{
			 ganti();			
		}    
		
	
	
		$("#TBAYAR").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});


		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#TOTAL" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
			$("#BAYAR" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
			$("#LAIN" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
			$("#SISA" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
		}	

		$(".NO_FAKTUR").each(function() {
			var getid = $(this).attr('id');
			var noid = getid.substring(9,12);

			$("#NO_FAKTUR"+noid).keypress(function(e){
				if(e.keyCode == 46){
					e.preventDefault();
					browseBeli(noid);
				}
			}); 
		});
		
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

		
 	var dTableBPo;
		loadDataBPo = function(){
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
					if(dTableBPo){
						dTableBPo.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBPo.row.add([
							'<a href="javascript:void(0);" onclick="choosePo(\''+resp[i].NO_PO+'\',  \''+resp[i].KODES+'\', \''+resp[i].NAMAS+'\' , \''+resp[i].TOTAL+'\' , \''+resp[i].BAYAR+'\' , \''+resp[i].SISA+'\'                )">'+resp[i].NO_PO+'</a>',
							resp[i].KODES,
							resp[i].NAMAS,
							Intl.NumberFormat('en-US').format(resp[i].TOTAL),
							Intl.NumberFormat('en-US').format(resp[i].BAYAR),
							Intl.NumberFormat('en-US').format(resp[i].SISA),
							
						]);
					}
					dTableBPo.draw();
				}
			});
		}
		
		dTableBPo = $("#table-bpo").DataTable({
			columnDefs: [
				{
                    className: "dt-right", 
					targets:  [],
					render: $.fn.dataTable.render.number( ',', '.', 2, '' )
				}
			],
		});
		
		browsePo = function(){
			 loadDataBPo();
			$("#browsePoModal").modal("show");
		}
		
		choosePo = function(NO_PO,KODES,NAMAS, TOTAL, BAYAR, SISA ){
			$("#NO_PO").val(NO_PO);
			$("#KODES").val(KODES);
			$("#NAMAS").val(NAMAS);		
			$("#browsePoModal").modal("hide");
			
			getBeli(NO_PO);
			hitung();
		}
		
		$("#NO_PO").keypress(function(e){

			if(e.keyCode == 46){
				e.preventDefault();
				browsePo();
			}
		}); 
		
/////////////////////////////////////////////////////////////////////////////////////////


		
		//////////////////////////////////////////////////////////////////////////////////////////////////

		var dTableBBeli;
		var rowidBeli;

		loadDataBBeli = function(){
	
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('beli/browseuang')}}",
				data: {		
					'NO_PO': $("#NO_PO").val(),				
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
							'<a href="javascript:void(0);" onclick="chooseBeli(\''+resp[i].NO_BUKTI+'\',  \''+resp[i].KODES+'\',   \''+resp[i].NAMAS+'\',   \''+resp[i].TOTAL+'\' ,  \''+resp[i].BAYAR+'\', \''+resp[i].SISA+'\'   )">'+resp[i].NO_BUKTI+'<input id="pilihFaktur'+i+'" hidden value="'+resp[i].NO_BUKTI+'"></input></a>',
							resp[i].KODES,
							resp[i].NAMAS,
							'<label for="pilihTotal" id="pilihTotal'+i+'" value="'+resp[i].TOTAL+'">'+Intl.NumberFormat('en-US').format(resp[i].TOTAL)+'</label>',
							Intl.NumberFormat('en-US').format(resp[i].BAYAR),	
							'<label for="pilihSisa" id="pilihSisa'+i+'" value="'+resp[i].SISA+'">'+Intl.NumberFormat('en-US').format(resp[i].SISA)+'</label>',
							'<input type="checkbox" class="form-control" id="pilih'+i+'" value="'+resp[i].KD_BRG+'"></input>',						
						]);
					}
					dTableBBeli.draw();
				}
			});
		}
		
		dTableBBeli = $("#table-bbeli").DataTable({
			
			columnDefs: [
				{
					targets:  [4,5,6],
					className: 'dt-body-right'
				}
			],
		});
		
		browseBeli = function(rid){
			rowidBeli = rid;
			loadDataBBeli();
			$("#browseBeliModal").modal("show");
		}
		
		chooseBeli = function(NO_BUKTI,KODES, NAMAS, TOTAL, BAYAR, SISA){
			$("#NO_FAKTUR"+rowidBeli).val(NO_BUKTI);
			$("#TOTAL"+rowidBeli).val(SISA);
			$("#BAYAR"+rowidBeli).val(SISA);	
			$("#TOTAL"+rowidBeli).autoNumeric('update');
			$("#BAYAR"+rowidBeli).autoNumeric('update');
			
			$("#browseBeliModal").modal("hide");
			hitung();
		}
		
		chooseBeliArr = function(){
			var beliDipilih = $("input[type='checkbox']").map(function() {
				var idx = dTableBBeli.row(this).index();
				var kode = null;
				if($(this).prop("checked"))
				{
					kode = '"'+$(this).val()+'"';
				} 
				return kode;
			}).get();
			var fakturDipilih = $("input[type='checkbox']").map(function() {
				var kode = null;
				if($(this).prop("checked"))
				{
					var idx = (this.id).substring(5, 7);
					kode = '"' + $("#pilihFaktur"+idx).val() + '"';
				} 
				return kode;
			}).get();
			var totalDipilih = $("input[type='checkbox']").map(function() {
				var kode = null;
				if($(this).prop("checked"))
				{
					var idx = (this.id).substring(5, 7);
					kode = '"' + $("#pilihTotal"+idx).text() + '"';
				} 
				return kode;
			}).get();
			var sisaDipilih = $("input[type='checkbox']").map(function() {
				var kode = null;
				if($(this).prop("checked"))
				{
					var idx = (this.id).substring(5, 7);
					kode = '"' + $("#pilihSisa"+idx).text() + '"';
				} 
				return kode;
			}).get();

			var fakturArr = JSON.parse("[" + fakturDipilih + "]");
			var totalArr = JSON.parse("[" + totalDipilih + "]");
			var sisaArr = JSON.parse("[" + sisaDipilih + "]");

			while (idrow<(parseInt(rowidBeli)+parseInt(fakturArr.length)))
			{
				tambah();
			};

			for (i=0 ; i<fakturArr.length ; i++) 
			{
				$("#NO_FAKTUR"+(parseInt(rowidBeli)+i)).val(fakturArr[i]);
				$("#TOTAL"+(parseInt(rowidBeli)+i)).val(sisaArr[i]);
				$("#BAYAR"+(parseInt(rowidBeli)+i)).val(sisaArr[i]);
			};

			$("#browseBeliModal").modal("hide");
			hitung();
		}
		
		
		$("#NO_FAKTUR0").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseBeli(0);
			}
		}); 
		
	
		
    });


	function cekDetail(){
		var cekFaktur = '';
		$(".BAYAR").each(function() {
			let z = $(this).closest('tr');
			var TOTALX = parseFloat(z.find('.TOTAL').val().replace(/,/g, ''));
			var BAYARX = parseFloat(z.find('.BAYAR').val().replace(/,/g, ''));
			var FAKTURX = z.find('.NO_FAKTUR').val();
			if(FAKTURX.substring(0,2)!="UM")
			{
				if(BAYARX>TOTALX){
					cekFaktur = FAKTURX;
				};		
			}		
		});

		return cekFaktur;
	}

 	function simpan() {
		hitung();
		
		var tgl = $('#TGL').val();
		var bulanPer = {{session()->get('periode')['bulan']}};
		var tahunPer = {{session()->get('periode')['tahun']}};
		var TBAYARX = parseFloat($('#TBAYAR').val().replace(/,/g, ''));

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
	
	
	function getBeli(NO_PO)
	{

		var mulai = (idrow==baris) ? idrow-1 : idrow;
		
		$.ajax(
			{
				type: 'GET',    
				url: "{{url('po/browse')}}",
				data: {
					NO_PO: NO_PO,
				},
				success: function( resp )
				{
					var html = '';
					for(i=0; i<resp.length; i++){
						html+=`<tr>
                                    <td><input name='REC[]' id='REC${i}' value=${resp[i].REC+1} type='text' class='REC form-control' onkeypress='return tabE(this,event)' readonly></td>
                                    <td><input name='NO_FAKTUR[]' data-rowid=${i} id='NO_FAKTUR${i}' value="${resp[i].NO_FAKTUR}" type='text' class='form-control NO_FAKTUR'  readonly ></td>
                                    <td><input name='TOTAL[]' onblur="hitung()" id='TOTAL${i}' value="0" type='text' style='text-align: right' class='form-control TOTAL text-primary' required></td>
                                    <td><input name='BAYAR[]' onblur="hitung()" id='BAYAR${i}' value="0" type='text' style='text-align: right' class='form-control BAYAR text-primary' required></td>
                                    <td><input name='LAIN[]' onblur="hitung()" id='LAIN${i}' value="0" type='text' style='text-align: right' class='form-control LAIN text-primary' required></td>
                                    <td><input name='SISA[]' onblur="hitung()" id='SISA${i}' value="0" type='text' style='text-align: right' class='form-control SISA text-primary' required></td>							
                                    <td><button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button></td>
                                </tr>`;
					}
					$('#detailPegawai').html(html);
					$(".TOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
					$(".TOTAL").autoNumeric('update');
					
					$(".BAYAR").autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
					$(".BAYAR").autoNumeric('update');
					
					$(".LAIN").autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
					$(".LAIN").autoNumeric('update');
					
					$(".SISA").autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
					$(".SISA").autoNumeric('update');
					idrow=resp.length;
					baris=resp.length;
					
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
		var TBAYAR = 0;

		$(".BAYAR").each(function() {
			
			let z = $(this).closest('tr');
			var TOTALX = parseFloat(z.find('.TOTAL').val().replace(/,/g, ''));
			var BAYARX = parseFloat(z.find('.BAYAR').val().replace(/,/g, ''));
			var LAINX = parseFloat(z.find('.LAIN').val().replace(/,/g, ''));
		
            var SISAX  = TOTALX - BAYARX - LAINX;
			z.find('.SISA').val(SISAX);

		    z.find('.TOTAL').autoNumeric('update');			
		    z.find('.BAYAR').autoNumeric('update');			
		    z.find('.LAIN').autoNumeric('update');			
		    z.find('.SISA').autoNumeric('update');
		
            TBAYAR +=BAYARX;				
		
		});
		
		
		if(isNaN(TBAYAR)) TBAYAR = 0;

		$('#TBAYAR').val(numberWithCommas(TBAYAR));		
		$("#TBAYAR").autoNumeric('update');
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
			$("#NO_PO").attr("readonly", true);
			$("#KODES").attr("readonly", true);
			$("#NAMAS").attr("readonly", true);
			$("#BACNO").attr("readonly", true);
			$("#BNAMA").attr("readonly", true);
			
			$("#NOTES").attr("readonly", false);
		
	

		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#REC" + i.toString()).attr("readonly", true);
			$("#NO_FAKTUR" + i.toString()).attr("readonly", true);
			$("#TOTAL" + i.toString()).attr("readonly", true);
			$("#BAYAR" + i.toString()).attr("readonly", false);
			$("#SISA" + i.toString()).attr("readonly", false);
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
		$("#NO_PO").attr("readonly", true);
		$("#KODES").attr("readonly", true);
		$("#NAMAS").attr("readonly", true);
		$("#BACNO").attr("readonly", true);
		$("#BNAMA").attr("readonly", true);
		$("#NOTES").attr("readonly", true);

		
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#REC" + i.toString()).attr("readonly", true);
			$("#NO_FAKTUR" + i.toString()).attr("readonly", true);
			$("#TOTAL" + i.toString()).attr("readonly", true);
			$("#BAYAR" + i.toString()).attr("readonly", true);
			$("#SISA" + i.toString()).attr("readonly", true);
			$("#DELETEX" + i.toString()).attr("hidden", true);		
		}


		
	}


	function kosong() {
				
		 $('#NO_BUKTI').val("+");	
	//	 $('#TGL').val("");	
		 $('#BACNO').val("");	
		 $('#BNAMA').val("");
		 $('#KODES').val("");	
		 $('#NAMAS').val("");	
		 $('#NOTES').val("");	
		 $('#TBAYAR').val("0.00");
		 
		 
		var html = '';
		$('#detailx').html(html);	
		
	}
	
	function hapusTrans() {
		let text = "Hapus Transaksi "+$('#NO_BUKTI').val()+"?";
		if (confirm(text) == true) 
		{
			window.location ="{{url('/hut/delete/'.$header->NO_ID .'/?flagz='.$flagz.'&golz=' .$golz.'' )}}";
			//return true;
		} 
		return false;
	}
	

	function CariBukti() {
		
		var flagz = "{{ $flagz }}";
		var golz = "{{ $golz }}";
		var cari = $("#CARI").val();
		var loc = "{{ url('/hut/edit/') }}" + '?idx={{ $header->NO_ID}}&tipx=search&flagz=' + encodeURIComponent(flagz) +'&golz=' + encodeURIComponent(golz) + '&buktix=' +encodeURIComponent(cari);
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
				    <input name='NO_FAKTUR[]' data-rowid=${idrow}  id='NO_FAKTUR${idrow}' type='text' class='form-control  NO_FAKTUR' required readonly>
                </td>
				
				<td>
		            <input name='TOTAL[]'  onblur='hitung()' value='0' id='TOTAL${idrow}' type='text' style='text-align: right' class='form-control TOTAL text-primary' required >
                </td>
				
				<td>
		            <input name='BAYAR[]'  onblur='hitung()' value='0' id='BAYAR${idrow}' type='text' style='text-align: right' class='form-control BAYAR text-primary' required >
                </td>
				
				<td>
		            <input name='SISA[]'  value='0' id='SISA${idrow}' type='text' style='text-align: right' class='form-control SISA text-primary' required >
                </td>
				
                <td>
					<button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>
                </td>				
         </tr>`;
				
        x.innerHTML = html;
        var html='';
		
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#TOTAL" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});

			$("#BAYAR" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
			
			$("#SISA" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});			 


		}

		$("#NO_FAKTUR"+idrow).keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseBeli(eval($(this).data("rowid")));
			}
		}); 
		

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