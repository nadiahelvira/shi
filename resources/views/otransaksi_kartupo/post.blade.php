@extends('layouts.main')
@section('styles')
<link rel="stylesheet" href="{{url('http://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css') }}">
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Kartu Purchase Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Kartu Purchase Order</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Status -->
    @if (session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
    @endif
    @if (session('gagal'))
        <div class="alert alert-danger">
            {{session('gagal')}}
        </div>
    @endif

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
              <form id="entri" target="_" action="{{url('kartupo/posting')}}">
                @csrf	  
                <button class="btn btn-danger" type="button"  onclick="simpan()">Cetak</button>


                <!-- <div class="form-group row">
                    <div class="col-md-1">
                      <label><strong>Gol :</strong></label>
                      
                      <select name="gol" id="gol" class="form-control gol">
                        <option value="K" {{ session()->get('filter_gol')=='K' ? 'selected': ''}}>K</option>
                        <option value="L" {{ session()->get('filter_gol')=='L' ? 'selected': ''}}>L</option>
                      </select>
                    </div>
                    <div class="col-md-2">						
                      <label class="form-label">Suplier</label>
                      <input type="text" class="form-control kodes" id="kodes" name="kodes" placeholder="Pilih Suplier" value="{{ session()->get('filter_kodes1') }}" readonly>
                    </div>  
                    <div class="col-md-3">
                      <label class="form-label">Nama</label>
                      <input type="text" class="form-control NAMAS" id="NAMAS" name="NAMAS" placeholder="Nama" value="{{ session()->get('filter_namas1') }}" readonly>
                    </div>
                </div> -->

                
                
                <table class="table table-fixed table-striped table-border table-hover nowrap datatable" id="datatable">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="text-align: center">#</th>		
                            <th scope="col">Pilih</th>	
                            <th scope="col">details</th>	
                            <th scope="col" style="text-align: left">No Bukti</th>
                            <th scope="col" style="text-align: center">Tanggal</th>
                            <th scope="col" style="text-align: left">Supplier</th>
                            <th scope="col" style="text-align: left">Barang</th>
                            <th scope="col" style="text-align: left">Kg</th>
                            <th scope="col" style="text-align: left">Kirim</th>
                            <th scope="col" style="text-align: left">Sisa</th>
                            <th scope="col" style="text-align: right">Harga</th>
                            <th scope="col" style="text-align: right">Total</th>
                            <th scope="col" style="text-align: left">Notes</th>
                        </tr>
                    </thead>
    
                    <tbody>
                    </tbody> 
                </table>
              </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="browseSuplierModal" tabindex="-1" role="dialog" aria-labelledby="browseSuplierModalLabel" aria-hidden="true">
	  	<div class="modal-dialog modal-xl" role="document">
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

  <div class="modal fade" id="cekdetailsxModal" tabindex="-1" role="dialog" aria-labelledby="cekdetailsxModalLabel" aria-hidden="true">
	  	<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cekdetailsxModalLabel">PEMBELIAN/RETUR</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" id="tabContent">
					<li class="nav-item active"><a class="nav-link active" href="#beli" 		data-toggle="tab">PEMBELIAN / RETUR</a></li>
					<li class="nav-item"><a class="nav-link" href="#bayar" data-toggle="tab">PEMBAYARAN</a></li>
              		<li class="nav-item"><a class="nav-link" href="#kartu" data-toggle="tab">KARTU</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="beli">
						<legend class="font-weight-bold mt-2">PEMBELIAN / RETUR</legend>
						<table class="table table-stripped table-bordered" id="table-bbeli">
							<thead>
								<tr>
									<th>NO BUKTI</th>
									<th>NO PO</th>
									<th>TGL</th>
									<th>SUPPLIER</th>
									<th>BARANG</th>
									<th>TRUCK</th>
									<th>KG</th>
									<th>HARGA</th>
									<th>TOTAL</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<div class="tab-pane" id="bayar">
						<legend class="font-weight-bold mt-2">PEMBAYARAN</legend>
						<table class="table table-stripped table-bordered" id="table-bhut">
						<thead>
							<tr>
								<th>NO BUKTI</th>
								<th>NO PO</th>
								<th>TGL</th>
								<th>KODE</th>
								<th>SUPPLIER</th>
								<th>URAIAN</th>
								<th>BAYAR</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						</table>
					</div> 
					<div class="tab-pane" id="kartu">
						<legend class="font-weight-bold mt-2">KARTU</legend>
						<table class="table table-stripped table-bordered" id="table-bkartu">
						<thead>
							<tr>
								<th>NO BUKTI</th>
								<th>TGL</th>
								<th>SUPPLIER</th>
								<th>KG</th>
								<th>TOTAL</th>
								<th>BAYAR</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						</table>
					</div> 
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			</div>
	  	</div>
	</div>



@endsection

@section('javascripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="{{ asset('js/autoNumerics/autoNumeric.min.js') }}"></script>

<script>
  $(document).ready(function() {
      var dataTable = $('.datatable').DataTable({
          processing: true,
          serverSide: true,
 //         autoWidth: true,
 //         'scrollX': true,
          'scrollY': '400px',
          "order": [[ 0, "asc" ]],
          ajax: 
          {
              url: "{{ route('get-kartupo') }}",
              data: 
              {
                filterpost : 1,
              }
          },
          columns: 
          [
              {data: 'DT_RowIndex', orderable: false, searchable: false },
              {data: 'cek', name: 'cek'},	
              {data: 'detailsx', name: 'detailsx'},	
              {data: 'NO_BUKTI', name: 'NO_BUKTI'},
              {data: 'TGL', name: 'TGL'},
              {data: 'NAMAS', name: 'NAMAS'},
              {data: 'NA_BRG', name: 'NA_BRG'},	
              {data: 'KG', name: 'KG', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},	
              {data: 'KIRIM', name: 'KIRIM', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},	
              {data: 'SISA', name: 'SISA', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},	
              {data: 'HARGA', name: 'HARGA', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},	
              {data: 'TOTAL', name: 'TOTAL', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},	
              {data: 'NOTES', name: 'NOTES'},
          ],
		  
          columnDefs: 
          [
            {
                "className": "dt-center", 
                "targets": [0,1,3]
            },		
            {
                "className": "dt-right", 
                "targets": [6,7,8,9,10]
            },		
            {
                "className": "dt-left", 
                "targets": [2,4,5,11]
            },			
            {
              targets: [4],
              render: $.fn.dataTable.render.moment( 'DD-MM-YYYY' )
            },
          ],
      });

      //CHOOSE Wilayah
      var dTableBSuplier;
		  loadDataBSuplier = function(){
		
			$.ajax(
			{
				type: 'GET', 		
				url: "{{url('sup/browse')}}",
				data: {
					'GOL': $('#gol').val(),
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
			$("#kodes").val(KODES);
			$("#NAMAS").val(NAMAS);	
			$("#browseSuplierModal").modal("hide");
		}
		
		$("#kodes").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseSuplier();
			}
		});
		
//////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

    
    	var dTableBBeli;
		loadDataBBeli = function(idpo){
		
			$.ajax(
			{
				type: 'GET', 	
				url: "{{url('beli/browsekartu')}}",
				data: {
					'IDPO': idpo,
				},
        
				success: function( response )
				{
					resp = response;
					if(dTableBBeli){
						dTableBBeli.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBBeli.row.add([
							'<a href="javascript:void(0);" onclick="chooseBeli(\''+resp[i].NO_BUKTI+'\', \''+resp[i].NO_PO+'\', \''+resp[i].TGL+'\', \''+resp[i].NAMAS+'\', \''+resp[i].NA_BRG+'\', \''+resp[i].TRUCK+'\', \''+resp[i].KG+'\', \''+resp[i].HARGA+'\', \''+resp[i].TOTAL+'\')">'+resp[i].NO_BUKTI+'</a>',
							resp[i].NO_PO,
							resp[i].TGL,
							resp[i].NAMAS,
							resp[i].NA_BRG,
							resp[i].TRUCK,
							Intl.NumberFormat('en-US').format(resp[i].KG),
							Intl.NumberFormat('en-US').format(resp[i].HARGA),
							Intl.NumberFormat('en-US').format(resp[i].TOTAL),
						]);
					}
					dTableBBeli.draw();
				}
			});
		}
		
		dTableBBeli = $("#table-bbeli").DataTable({
			
		});

/////////////////////////////////////////////////////////////////////////// 
///////////////////////////////////////////////////////////////////////////

		var dTableBHut;
		loadDataBHut = function(idpo){
		
			$.ajax(
			{
				type: 'GET', 		
				url: "{{url('hut/browsekartu')}}",
				data: {
					'IDPO': idpo,
				},
				success: function( response )
				{
					resp = response;
					if(dTableBHut){
						dTableBHut.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBHut.row.add([
							'<a href="javascript:void(0);" onclick="chooseHut(\''+resp[i].NO_BUKTI+'\', \''+resp[i].NO_PO+'\', \''+resp[i].TGL+'\', \''+resp[i].KODES+'\', \''+resp[i].NAMAS+'\', \''+resp[i].URAIAN+'\', \''+resp[i].BAYAR+'\')">'+resp[i].NO_BUKTI+'</a>',
							resp[i].NO_PO,
							resp[i].TGL,
							resp[i].KODES,
							resp[i].NAMAS,
							resp[i].URAIAN,
							Intl.NumberFormat('en-US').format(resp[i].BAYAR),
						]);
					}
					dTableBHut.draw();
				}
			});
		}
		
		dTableBHut = $("#table-bhut").DataTable({
			
		});

//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////

		var dTableBKartu;
		loadDataBKartu = function(idpo){
		
			$.ajax(
			{
				type: 'GET', 		
				url: "{{url('beli/browsekartu2')}}",
				data: {
					'IDPO': idpo,
				},
				success: function( response )
				{
					resp = response;
					if(dTableBKartu){
						dTableBKartu.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBKartu.row.add([
							'<a href="javascript:void(0);" onclick="chooseKartu(\''+resp[i].NO_BUKTI+'\', \''+resp[i].TGL+'\', \''+resp[i].NAMAS+'\', \''+resp[i].KG+'\', \''+resp[i].TOTAL+'\', \''+resp[i].BAYAR+'\')">'+resp[i].NO_BUKTI+'</a>',
							resp[i].TGL,
							resp[i].NAMAS,
							Intl.NumberFormat('en-US').format(resp[i].KG),
							Intl.NumberFormat('en-US').format(resp[i].TOTAL),
							Intl.NumberFormat('en-US').format(resp[i].BAYAR),
						]);
					}
					dTableBKartu.draw();
				}
			});
		}
		
		dTableBKartu = $("#table-bkartu").DataTable({
			
		});

//////////////////////////////////////////////////////////////////////

  });

  function cekQty(){
		$(".qtyt").each(function() {
      var qtyt = parseFloat($(this).val().replace(/,/g, ''));

      let z = $(this).closest('tr');
      var centang = z.find('.cek:checked').val();
      
      if(qtyt<0 && centang)
      {
        console.log(qtyt);
        return qtyt;
      }
		});
	}
  
  function hitung() {
		$(".qtyt").each(function() {
		  $(this).autoNumeric('init', {aSign: '<?php echo ''; ?>', vMin: '-999999999.99'});
		});
  }

	function simpan() {
		
		var check = '0';

		// if ($('#wilayah1').val() == '') {
		// 	check = '1';
		// 	alert("Wilayah Harus di pilih.");
		// }	
		
		// 	if ($('#KOTA_SETOR').val() == '') {
		// 	check = '1';
		// 	alert("Kota Setor Harus di isi.");
		// }	

		if (check=='0')
		{
			$(".NO_PO").each(function() {
				let z = $(this).closest('tr');
				var notcentang = z.find('.cek:not(:checked)').val();
				if(notcentang)
				{
					$(this).prop("disabled", true );
				}
			});
		}

		(check==0) ? document.getElementById("entri").submit() : alert('Masih ada kesalahan');
   
		setTimeout(
		$(".NO_PO").each(function() {
				$(this).prop("disabled", false );
		})
		, 1000);
	}

  	cekdetailsx = function(iddetailsx){
		loadDataBBeli(iddetailsx);
		loadDataBHut(iddetailsx);
		loadDataBKartu(iddetailsx);
		$("#cekdetailsxModal").modal("show");
	}

  
</script>
@endsection
