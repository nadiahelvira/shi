@extends('layouts.main')
@section('styles')
<!-- <link rel="stylesheet" href="{{url('http://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css') }}"> -->
<link rel="stylesheet" href="{{asset('foxie_js_css/jquery.dataTables.min.css')}}" />

@endsection


<style>

    th { font-size: 13px; }
    td { font-size: 13px; }
</style>


@section('content')
<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{$judul}} </h1>
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

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
  
                <input name="flagz" class="form-control flagz" id="flagz" value="{{$flagz}}" hidden>                
                <input name="golz" class="form-control golz" id="golz" value="{{$golz}}" hidden>                

  
				<table class="table table-fixed table-striped table-border table-hover nowrap datatable" id="datatable">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="text-align: center">#.</th>
                            <th scope="col" style="text-align: center">-</th>	
                            <th scope="col" style="text-align: left">Bukti#</th>
                            <th scope="col" style="text-align: center">Tgl</th>
                            <th scope="col" style="text-align: left">Po#</th>
                            <th scope="col" style="text-align: left">Kode#</th>
                            <th scope="col" style="text-align: left">Suplier</th>
                            <th {{($flagz == 'BL') ? '' : 'hidden' }}  
							    scope="col" style="text-align: right">Kg</th>
                            <th scope="col" style="text-align: center">Total</th>
							<th scope="col" style="text-align: center">RpTotal</th>							
							<th scope="col" style="text-align: center">Notes</th>
							<th {{($flagz == 'BL') ? '' : 'hidden' }} 
							    scope="col" style="text-align: center">AJU</th>
							
							<th {{($flagz == 'BL') ? '' : 'hidden' }} 
							    scope="col" style="text-align: center">BL</th>
								
							<th {{($flagz == 'BL') ? '' : 'hidden' }} 
							    scope="col" style="text-align: center">Emkl</th>	
								
							<th scope="col" style="text-align: center">Posted</th>
							<th scope="col" style="text-align: center">User</th>
                            <th 
							scope="col" style="text-align: center">Bank#</th>							
                        </tr>
                    </thead>
    
                     <tbody>
                     </tbody> 
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('javascripts')

<script>
  $(document).ready(function() {
        var dataTable = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            'scrollX': true,
            'scrollY': '400px',	
            "order": [[ 0, "asc" ]],
            ajax: 
            {
                url: "{{ route('get-beli') }}",
				data: {
					'flagz': "{{$flagz}}",
					'golz': "{{$golz}}",
				},
				
            },
            columns: 
            [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
			    { data: 'action', name: 'action'},
                { data: 'NO_BUKTI', name: 'NO_BUKTI'},
                { data: 'TGL', name: 'TGL'},
                { data: 'NO_PO', name: 'NO_PO'},
                { data: 'KODES', name: 'KODES'},
                { data: 'NAMAS', name: 'NAMAS'},
                { data: 'KG', name: 'KG', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},								
                { data: 'TOTAL', name: 'TOTAL', render: $.fn.dataTable.render.number( ',', '.', 0, '' )},	
                { data: 'RPTOTAL', name: 'RPTOTAL', render: $.fn.dataTable.render.number( ',', '.', 2, '' )},
                { data: 'NOTES', name: 'NOTES'},
				
	 
					{ data: 'AJU', name: 'AJU'},
					{ data: 'BL', name: 'BL'},
					{ data: 'EMKL', name: 'EMKL'},				
                { data: 'POSTED', name: 'POSTED',
                  render : function(data, type, row, meta) {
                    if(row['POSTED']=="0"){
                        return '';
                    }else{
                        return '<input type="checkbox" checked style="pointer-events: none;">';
                    }
                  }
                },
                { data: 'USRNM', name: 'USRNM'},
                { data: 'NO_BANK', name: 'NO_BANK'},
            ],
            columnDefs: 
            [
                {
                    "className": "dt-center", 
                    "targets": [0],
                },			
                {
                  targets: 3,
                  render: $.fn.dataTable.render.moment( 'DD-MM-YYYY' )
                },
				        {
                    "className": "dt-right", 
                    "targets":[7,8,9,10]
                },
				        {
                    "className": "dt-left", 
                    "targets":[4,5,6]
                },
            ],
            lengthMenu: 
            [
                [8, 10, 20, 50, 100, -1],
                [8, 10, 20, 50, 100, "All"]
            ],
            dom: "<'row'<'col-md-6'><'col-md-6'>>" +
                "<'row'<'col-md-2'l><'col-md-6 test_btn m-auto'><'col-md-4'f>>" +
                "<'row'<'col-md-12't>><'row'<'col-md-12'ip>>",
			stateSave:true,

       });

       $("div.test_btn").html('<a class="btn btn-lg btn-md btn-success" href="{{url('beli/edit?flagz='.$flagz.'&golz='.$golz.'&idx=0&tipx=new')}}"> <i class="fas fa-plus fa-sm md-3" ></i></a>');


		
		if ( '{{$flagz}}' == 'BL' ) {
			datatable.column(7).visible(true, true)
			datatable.column(11).visible(true, true)
			datatable.column(12).visible(true, true)
			datatable.column(13).visible(true, true)			
		} else {
			datatable.column(7).visible(false, false)
			datatable.column(11).visible(false, false)			
			datatable.column(12).visible(false, false)
			datatable.column(13).visible(false, false)			
		}
		
		
    });
	
</script>
@endsection


