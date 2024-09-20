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
                                    <label for="TGL" class="form-label">Tanggal</label>
                                </div>
                                <div class="col-md-2">
								  <input class="form-control date" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->TGL))}}">
                                </div>
                            </div>
  
							<div class="form-group row">
                                <div class="col-md-1" align="right">
									<label style="color:red;font-size:20px">* </label>
                                    <label for="NO_SO" class="form-label">SO#</label>
                                </div>
                               <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control NO_SO" id="NO_SO" name="NO_SO" placeholder="Masukkan SO"value="{{$header->NO_SO}}" style="text-align: left" readonly >
                                </div>
                            </div>
							
                            <div class="form-group row">
                                <div class="col-md-1"  align="right">
                                    <label for="KODEC" class="form-label">Customer#</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KODEC" id="KODEC" name="KODEC"
                                    placeholder="Masukkan Customer#" value="{{$header->KODEC}}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NAMAC" id="NAMAC" name="NAMAC"
                                    placeholder="-" value="{{$header->NAMAC}}" readonly>
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
                                    <input type="text" class="form-control BNAMA" id="BNAMA" name="BNAMA"
                                    placeholder="-" value="{{$header->BNAMA}}" readonly>
                                </div>
                            </div>

							<div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="NOTES" class="form-label">Notes</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control NOTES" id="NOTES" name="NOTES" placeholder="-" value="{{$header->NOTES}}">
                                </div>
                            </div>
        
							<table id="datatable" class="table table-striped table-border">
                                <thead>
                                    <tr>
										<th width="100px" style="text-align:center">No.</th>
                                        <th width="200px" style="text-align:center">
								        	<label style="color:red;font-size:20px">* </label>
                                            <label for="BACNO" class="form-label">Faktur#</label></th>
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
										    <input name="TOTAL[]" onblur="hitung()" value="{{$detail->TOTAL}}" id="TOTAL{{$no}}" type="text" style="text-align: right"  class="form-control TOTAL" readonly>
										</td>                         
										
										<td>
										    <input name="BAYAR[]"  onblur="hitung()" value="{{$detail->BAYAR}}" id="BAYAR{{$no}}" type="text" style="text-align: right"  class="form-control BAYAR" >
										</td>
										<td>
										    <input name="SISA[]" onblur="hitung()" value="{{$detail->SISA}}" id="SISA{{$no}}" type="text" style="text-align: right"  class="form-control SISA" readonly>
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
	


	
	<div class="modal fade" id="browseSoModal" tabindex="-1" role="dialog" aria-labelledby="browseSoModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseSoModalLabel">Cari So#</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bso">
				<thead>
					<tr>
						<th>SO#</th>
						<th>Customer</th>
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
	

	<div class="modal fade" id="browseJualModal" tabindex="-1" role="dialog" aria-labelledby="browseJualModalLabel" aria-hidden="true">
	  <div class="modal-dialog mw-100 w-75" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseJualModalLabel">Cari Jual#</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bjual">
				<thead>
					<tr>
						<th>Jual#</th>
						<th>Kode</th>
						<th>-</th>
						<th>Tgl</th>
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
			<button type="button" onclick="chooseJualArr()" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
			$("#TOTAL" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-9999999999.99'});
			$("#BAYAR" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-9999999999.99'});
			$("#SISA" + i.toString()).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-9999999999.99'});
			
		}
		
		
        $('body').on('click', '.btn-delete', function() {
			var val = $(this).parents("tr").remove();
			baris--;
			nomor();
			
		});

		$('.date').datepicker({  
            dateFormat: 'dd-mm-yy'
		});
		


	    $(".NO_FAKTUR").each(function() {
			var getid = $(this).attr('id');
			var noid = getid.substring(9,12);

			$("#NO_FAKTUR"+noid).keypress(function(e){
				if(e.keyCode == 46){
					e.preventDefault();
					browseJual(noid);
				}
			}); 
		});
		
///////////////////////////////////////////////////
		
	/////////////////////////////////////////////////
	var dTableBSo;
		loadDataBSo = function(){
			$.ajax(
			{
				type: 'GET', 		
				url: '{{url('so/browseuang')}}',
				data: {
					'GOL': 'A2',
				},
				success: function( response )
				{
					resp = response;
					if(dTableBSo){
						dTableBSo.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBSo.row.add([
							'<a href="javascript:void(0);" onclick="chooseSo(\''+resp[i].NO_BUKTI+'\',  \''+resp[i].KODEC+'\', \''+resp[i].NAMAC+'\' , \''+resp[i].TOTAL+'\' , \''+resp[i].BAYAR+'\' , \''+resp[i].SISA+'\'                )">'+resp[i].NO_BUKTI+'</a>',
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
		
		chooseSo = function(NO_BUKTI,KODEC,NAMAC, TOTAL, BAYAR, SISA ){
			$("#NO_SO").val(NO_BUKTI);
			$("#KODEC").val(KODEC);
			$("#NAMAC").val(NAMAC);		
			$("#browseSoModal").modal("hide");
		}
		
		$("#NO_SO").keypress(function(e){

			if(e.keyCode == 46){
				e.preventDefault();
				browseSo();
			}
		}); 
		
				/////////////////////////////////////////////////////////////////////////////

		var dTableBAccount1;
		loadDataBAccount1 = function(){
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('account/browsebank')}}",
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
		///////////////////////////////////////////////
		

		var dTableBJual;
		var rowidJual;

		loadDataBJual = function(){
			var dataDetail = $("input[name='NO_FAKTUR[]']").map(function() {
				var isi = "''";
				if ($(this).val()) {
					isi = "'" + $(this).val() + "'";
				}
				return isi;
			}).get();
	
			
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('jual/browseuang')}}",
				data: {
					'NO_SO': $("#NO_SO").val(),
					listDetail: dataDetail, 
				},
				success: function( response )
				{
					resp = response;
					if(dTableBJual){
						dTableBJual.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBJual.row.add([
							'<a href="javascript:void(0);" '+(resp[i].LEBIH30=='Y' ? 'style="color:red;"' : "")+' onclick="chooseJual(\''+resp[i].NO_BUKTI+'\',  \''+resp[i].KODEC+'\',   \''+resp[i].NAMAC+'\',  \''+resp[i].TOTAL+'\' ,  \''+resp[i].BAYAR+'\', \''+resp[i].SISA+'\'   )">'+resp[i].NO_BUKTI+'<input id="pilihFaktur'+i+'" hidden value="'+resp[i].NO_BUKTI+'"></input></a>',
							resp[i].KODEC,
							resp[i].NAMAC,
							resp[i].TGL,
							//Intl.NumberFormat('en-US').format(resp[i].TOTAL),
							'<label for="pilihTotal" id="pilihTotal'+i+'" value="'+resp[i].TOTAL+'">'+Intl.NumberFormat('en-US').format(resp[i].TOTAL)+'</label>',
							Intl.NumberFormat('en-US').format(resp[i].BAYAR),
							//Intl.NumberFormat('en-US').format(resp[i].SISA),	
							'<label for="pilihSisa" id="pilihSisa'+i+'" value="'+resp[i].SISA+'">'+Intl.NumberFormat('en-US').format(resp[i].SISA)+'</label>',
							'<input type="checkbox" id="pilih'+i+'" value="'+resp[i].KD_BRG+'"></input>',						
						]);
					}
					dTableBJual.draw();
				}
			});
		}
		

		dTableBJual = $("#table-bjual").DataTable({
			columnDefs: [
				{
					targets:  [4,5,6],
					className: 'dt-body-right'
				}
			],
			
		});
		
		
		browseJual = function(rid){
			rowidJual = rid;
			loadDataBJual();
			$("#browseJualModal").modal("show");
		}
		
		chooseJual = function(NO_BUKTI,KODEC,NAMAC, TOTAL, BAYAR, SISA){
			$("#NO_FAKTUR"+rowidJual).val(NO_BUKTI);
			$("#TOTAL"+rowidJual).val(SISA);
			$("#TOTAL"+rowidJual).autoNumeric('update');
			$("#BAYAR"+rowidJual).val(SISA);
			$("#BAYAR"+rowidJual).autoNumeric('update');
			$("#browseJualModal").modal("hide");
			hitung();
		}
		
		chooseJualArr = function(){
			var jualDipilih = $("input[type='checkbox']").map(function() {
				var idx = dTableBJual.row(this).index();
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

			while (idrow<(rowidJual+fakturArr.length))
			{
				tambah();
			};

			for (i=0 ; i<fakturArr.length ; i++) 
			{
				$("#NO_FAKTUR"+(rowidJual+i)).val(fakturArr[i]);
				$("#TOTAL"+(rowidJual+i)).val(sisaArr[i]);
				$("#BAYAR"+(rowidJual+i)).val(sisaArr[i]);
			};

			$("#browseJualModal").modal("hide");
			hitung();
		}
		
		
		$("#NO_FAKTUR0").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseJual(0);
			}
		}); 
		
    });
	function cekDetail(){
		$(".BAYAR").each(function() {
			let z = $(this).closest('tr');
			var TOTALX = parseFloat(z.find('.TOTAL').val().replace(/,/g, ''));
			var BAYARX = parseFloat(z.find('.BAYAR').val().replace(/,/g, ''));
			var FAKTURX = z.find('.NO_FAKTUR').val();
			if(BAYARX>TOTALX){
				return FAKTURX;
			};			
		});
	}

	function simpan() {
		hitung();
		
		var tgl = $('#TGL').val();
		var bulanPer = {{session()->get('periode')['bulan']}};
		var tahunPer = {{session()->get('periode')['tahun']}};
		var TBAYARX = parseFloat($('#TBAYAR').val().replace(/,/g, ''));

				
        var check = '0';
		
/* 			if (cekDetail())
			{	
			    check = '1';
				alert("Faktur# "+cekDetail()+" lebih bayar! ")
			} */
			
			if ( $('#NO_SO').val()=='' ) 
            {			
			    check = '1';
				alert("SO# Harus diisi.");
			}

			
			/* if ( TBAYARX < 0 ) 
            {			
			    check = '1';
				alert("Total Bayar Negatif.");
			} */
			
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
	
    function nomor() {
		var i = 1;
		$(".REC").each(function() {
			$(this).val(i++);
		});
		
	//	hitung();
	}


    function hitung() {
		var TBAYAR = 0;

		$(".BAYAR").each(function() {
			
			let z = $(this).closest('tr');
			var TOTALX = parseFloat(z.find('.TOTAL').val().replace(/,/g, ''));
			var BAYARX = parseFloat(z.find('.BAYAR').val().replace(/,/g, ''));
		
            var SISAX  = TOTALX - BAYARX;
			z.find('.SISA').val(SISAX);

		    z.find('.TOTAL').autoNumeric('update');			
		    z.find('.BAYAR').autoNumeric('update');			
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
			$("#TOTAL" + i.toString()).attr("readonly", true);
			$("#BAYAR" + i.toString()).attr("readonly", false);
			$("#SISA" + i.toString()).attr("readonly", true);
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
		$("#NO_SO").attr("readonly", true);
		$("#KODEC").attr("readonly", true);
		$("#NAMAC").attr("readonly", true);
		$("#BACNO").attr("readonly", true);
		$("#BNAMA").attr("readonly", true);
		$("#NOTES").attr("readonly", true);
		
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#REC" + i.toString()).attr("readonly", true);
			$("#NO_KFATUR" + i.toString()).attr("readonly", true);
			$("#TOTAL" + i.toString()).attr("readonly", true);
			$("#BAYAR" + i.toString()).attr("readonly", true);
			$("#SISA" + i.toString()).attr("readonly", true);
			$("#DELETEX" + i.toString()).attr("hidden", true);	
			
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
		 $('#TBAYAR').val("0.00");	
		 
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
 
		html=`<tr>

                <td>
 					<input name='NO_ID[]' id='NO_ID${idrow}' type='hidden' class='form-control NO_ID' value='new' readonly> 
					<input name='REC[]' id='REC${idrow}' type='text' class='REC form-control' onkeypress='return tabE(this,event)' readonly>
	            </td>
						       
                <td>
				    <input name='NO_FAKTUR[]' data-rowid=${idrow}  id='NO_FAKTUR${idrow}' type='text' class='form-control  NO_FAKTUR' required readonly>
                </td>
				
				<td>
		            <input name='TOTAL[]'  onblur='hitung()' value='0' id='TOTAL${idrow}' type='text' style='text-align: right' class='form-control TOTAL text-primary' readonly required >
                </td>
				
				<td>
		            <input name='BAYAR[]'  onblur='hitung()' value='0' id='BAYAR${idrow}' type='text' style='text-align: right' class='form-control BAYAR text-primary' required >
                </td>
				
				<td>
		            <input name='SISA[]'  value='0' id='SISA${idrow}' type='text' style='text-align: right' class='form-control SISA text-primary' readonly required >
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
				browseJual(eval($(this).data("rowid")));
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
@endsection