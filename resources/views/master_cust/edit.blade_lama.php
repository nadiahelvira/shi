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
            <h1 class="m-0">Data Customer </h1>
            </div>
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
  
                    <form action="{{($tipx=='new')? url('/cust/store/') : url('/cust/update/'.$header->NO_ID ) }}" method="POST" name ="entri" id="entri" >
  
                      @csrf
						
                        <ul class="nav nav-tabs">
                            <li class="nav-item active">
                                <a class="nav-link active" href="#custInfo" data-toggle="tab">Cust Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#bankInfo" data-toggle="tab">Bank Info</a>
                            </li>
                        </ul>
        
                        <div class="tab-content mt-3">
							<div id="custInfo" class="tab-pane active">
        
                             <div class="form-group row">
                                <div class="col-md-1">
                                    <label for="KODEC" class="form-label">Kode</label>
                                </div>
 
                                    <input type="text" class="form-control NO_ID" id="NO_ID" name="NO_ID"
                                    placeholder="Masukkan NO_ID" value="{{$header->NO_ID ?? ''}}" hidden readonly>

									<input name="tipx" class="form-control flagz" id="tipx" value="{{$tipx}}" hidden>
		 

								<div class="col-md-2">
                                    <input type="text" class="form-control KODEC" id="KODEC" name="KODEC"
                                    placeholder="Masukkan Kode Customer" value="{{$header->KODEC}}" readonly>
                                </div>                                
                            </div>

							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="NAMAC" class="form-label">Nama</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NAMAC" id="NAMAC" name="NAMAC"
                                    placeholder="Masukkan Nama Customer" value="{{$header->NAMAC}}">
                                </div>                                
                            </div>
        
                            <div class="form-group row">
                                <div class="col-md-1">
                                    <label for="ALAMAT" class="form-label">Alamat</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control ALAMAT" id="ALAMAT" name="ALAMAT"
                                    placeholder="Masukkan Alamat" value="{{$header->ALAMAT}}">
                                </div>
							</div>

							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="KOTA" class="form-label">Kota</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KOTA" id="KOTA" name="KOTA"
                                    placeholder="Masukkan Kota" value="{{$header->KOTA}}">
                                </div>
                            </div>
        
							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="TELPON1" class="form-label">Telpon</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control TELPON1" id="TELPON1"name="TELPON1"
                                    placeholder="Masukkan Telpon" value="{{$header->TELPON1}}">
                                </div>
                            </div>

							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="FAX" class="form-label">Fax</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control FAX" id="FAX"name="FAX"
                                    placeholder="Masukkan Fax" value="{{$header->FAX}}">
                                </div>
                            </div>
 
							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="HP" class="form-label">HP</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control HP" id="HP"name="HP"
                                    placeholder="Masukkan HP" value="{{$header->HP}}">
                                </div>
								
							
								<div class="col-md-4">
                                    <input type="checkbox" class="form-check-input" id="AKT"name="AKT"
                                    placeholder="Masukkan Aktif/Tidak" value="1" {{ ($header->AKT == 1) ? 'checked' : '' }}>
									<label for="AKT">Aktif</label>
                                </div>
                            </div>

							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="KONTAK" class="form-label">Kontak</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KONTAK" id="KONTAK"name="KONTAK"
                                    placeholder="Masukkan Kontak" value="{{$header->KONTAK}}">
                                </div>
                            </div>
 
							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="EMAIL" class="form-label">Email</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control EMAIL" id="EMAIL"name="EMAIL"
                                    placeholder="Masukkan Email" value="{{$header->EMAIL}}">
                                </div>
                            </div> 
 
							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="NPWP" class="form-label">NPWP</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control NPWP" id="NPWP"name="NPWP"
                                    placeholder="Masukkan NPWP" value="{{$header->NPWP}}">
                                </div>
                            </div>

							
							<div class="form-group row">
                                <div class="col-md-1">
                                    <label for="KET" class="form-label">Ket</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control KET" id="KET"name="KET"
                                    placeholder="Masukkan Ket" value="{{$header->KET}}">
                                </div>
                            </div>
        								
								
							</div>

							
							<div id="bankInfo" class="tab-pane">
				
								<div class="form-group row">
									<div class="col-md-1">
										<label for="BANK" class="form-label">Bank</label>
									</div>
									<div class="col-md-2">
										<input type="text" class="form-control BANK" id="BANK" name="BANK" placeholder="Masukkan Bank" value="{{$header->BANK}}">
									</div>                                
								</div>

								<div class="form-group row">							       
									<div class="col-md-1">
										<label for="BANK_CAB" class="form-label">Cabang</label>
									</div>
									<div class="col-md-4">
										<input type="text" class="form-control BANK_CAB" id="BANK_CAB" name="BANK_CAB" placeholder="Masukkan Cabang" value="{{$header->BANK_CAB}}">
									</div>
								</div>

								<div class="form-group row">							       
									<div class="col-md-1">
										<label for="BANK_KOTA" class="form-label">Kota</label>
									</div>
									<div class="col-md-2">
										<input type="text" class="form-control BANK_KOTA" id="BANK_KOTA" name="BANK_KOTA" placeholder="Masukkan Kota" value="{{$header->BANK_KOTA}}">
									</div>
								</div>
								
								<div class="form-group row">
									<div class="col-md-1">
										<label for="BANK_NAMA" class="form-label">A/N</label>
									</div>
									<div class="col-md-4">
										<input type="text" class="form-control BANK_NAMA" id="BANK_NAMA" name="BANK_NAMA" placeholder="Masukkan Nama" value="{{$header->BANK_NAMA}}">
									</div>                                
								</div>
								
								<div class="form-group row">
									<div class="col-md-1">
										<label for="BANK_REK" class="form-label">Rek</label>
									</div>
									<div class="col-md-4">
										<input type="text" class="form-control BANK_REK" id="BANK_REK" name="BANK_REK" placeholder="Masukkan Nomor Rekening" value="{{$header->BANK_REK}}">
									</div>                                
								</div>
								
								<div class="form-group row">
									<div class="col-md-1">
										<label for="LIM" class="form-label">Kr_Limit</label>
									</div>
									<div class="col-md-2">
										<input type="text" class="form-control LIM" id="LIM" name="LIM" placeholder="Masukkan Limit" value="{{$header->LIM}}">
									</div>                                
								</div>
								
								<div class="form-group row">
									<div class="col-md-1">
										<label for="HARI" class="form-label">Janji Hari</label>
									</div>
									<div class="col-md-2">
										<input type="text" class="form-control HARI" id="HARI" name="HARI" placeholder="Masukkan Jumlah Hari" value="{{$header->HARI}}">
									</div>                                
								</div>
								
							</div>
						</div>
                                
                        </div>
        
						<div class="mt-3 col-md-12 form-group row">
							<div class="col-md-4">
								<button type="button" hidden id='TOPX'  onclick="location.href='{{url('/cust/edit/?idx=' .$idx. '&tipx=top')}}'" class="btn btn-outline-primary">Top</button>
								<button type="button" hidden id='PREVX' onclick="location.href='{{url('/cust/edit/?idx='.$header->NO_ID.'&tipx=prev&kodex='.$header->ACNO )}}'" class="btn btn-outline-primary">Prev</button>
								<button type="button" hidden id='NEXTX' onclick="location.href='{{url('/cust/edit/?idx='.$header->NO_ID.'&tipx=next&kodex='.$header->ACNO )}}'" class="btn btn-outline-primary">Next</button>
								<button type="button" hidden id='BOTTOMX' onclick="location.href='{{url('/cust/edit/?idx=' .$idx. '&tipx=bottom')}}'" class="btn btn-outline-primary">Bottom</button>
							</div>
							<div class="col-md-5">
								<button type="button" hidden id='NEWX' onclick="location.href='{{url('/cust/edit/?idx=0&tipx=new')}}'" class="btn btn-warning">New</button>
								<button type="button" hidden id='EDITX' onclick='hidup()' class="btn btn-secondary">Edit</button>                    
								<button type="button" hidden id='UNDOX' onclick="location.href='{{url('/cust/edit/?idx=' .$idx. '&tipx=undo' )}}'" class="btn btn-info">Undo</button> 
								<button type="button" id='SAVEX' onclick='simpan()'   class="btn btn-success"<i class="fa fa-save"></i>Save</button>

							</div>
							<div class="col-md-3">
								<button type="button" hidden id='HAPUSX'  onclick="hapusTrans()" class="btn btn-outline-danger">Hapus</button>
								<button type="button" id='CLOSEX'  onclick="location.href='{{url('/cust' )}}'" class="btn btn-outline-secondary">Close</button>


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
@endsection

@section('footer-scripts')
<script>
    var target;
	var idrow = 1;

    $(document).ready(function () {

 		$tipx = $('#tipx').val();
				
        if ( $tipx == 'new' )
		{
			 baru();			
		}

        if ( $tipx != 'new' )
		{
			 //mati();	
    		 ganti();
		} 
		
    });


	function baru() {
		
		 kosong();
		 hidup();
		 
	}
	
	function ganti() {
		
		// mati();
		hidup();
	
	}
	
	
	function batal() {
			
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
		
		
 		$tipx = $('#tipx').val();
		
        if ( $tipx == 'new' )		
		{	
		  	
			$("#KODEC").attr("readonly", false);	

		   }
		else
		{
	     	$("#KODEC").attr("readonly", true);	

		   }
		   
		
		$("#NAMAC").attr("readonly", false);	
		$("#ALAMAT").attr("readonly", false);			
		$("#KOTA").attr("readonly", false);		
		$("#TELPON1").attr("readonly", false);			
		$("#FAX").attr("readonly", false);	
		$("#HP").attr("readonly", false);			
		$("#AKT").attr("readonly", false);		
		$('#KONTAK').attr("readonly", false);

		 $('#EMAIL').attr("readonly", false);	
		 $('#NPWP').attr("readonly", false);	
		 $('#KET').attr("readonly", false);


		 $('#BANK').attr("readonly", false);	
		 $('#BANK_CAB').attr("readonly", false);	
		 $('#BANK_KOTA').attr("readonly", false);	
		 $('#BANK_NAMA').attr("readonly", false);		
		 $('#BANK_REK').attr("readonly", false);
		 $('#HARI').attr("readonly", false);
		 $('#LIM').attr("readonly", false);
		
		//document.getElementById("KET").disabled = false;
		
	
	
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
		
		$("#KODEC").attr("readonly", true);			
		$("#NAMAC").attr("readonly", true);	
		$("#ALAMAT").attr("readonly", true);			
		$("#KOTA").attr("readonly", true);		
		$("#TELPON1").attr("readonly", true);			
		$("#FAX").attr("readonly", true);	
		$("#HP").attr("readonly", true);			
		$("#AKT").attr("readonly", true);		
		$('#KONTAK').attr("readonly", true);

		 $('#EMAIL').attr("readonly", true);	
		 $('#NPWP').attr("readonly", true);	
		 $('#KET').attr("readonly", true);


		 $('#BANK').attr("readonly", true);	
		 $('#BANK_CAB').attr("readonly", true);	
		 $('#BANK_KOTA').attr("readonly", true);	
		 $('#BANK_NAMA').attr("readonly", true);		
		 $('#BANK_REK').attr("readonly", true);
		 $('#HARI').attr("readonly", true);
		 $('#LIM').attr("readonly", true);	
		
	}


	function kosong() {
				
		 $('#KODEC').val("");	
		 $('#NAMAC').val("");	
		 $('#ALAMAT').val("");	
		 $('#KOTA').val("");		

		 $('#TELPON1').val("");	
		 $('#FAX').val("");	
		 $('#HP').val("");	
		 $('#AKT').val("0");		
		 $('#KONTAK').val("");

		 $('#EMAIL').val("");	
		 $('#NPWP').val("");	
		 $('#KET').val("");	


		 $('#BANK').val("");	
		 $('#BANK_CAB').val("");	
		 $('#BANK_KOTA').val("");	
		 $('#BANK_NAMA').val("");		
		 $('#BANK_REK').val("");
		 $('#HARI').val("0");
		 $('#LIM').val("0");	

		 
	}
	
	function hapusTrans() {
		let text = "Hapus Master "+$('#KODEC').val()+"?";
		if (confirm(text) == true) 
		{
			window.location ="{{url('/cust/delete/'.$header->NO_ID )}}'";
			//return true;
		} 
		return false;
	}

	function CariBukti() {
		
		var cari = $("#CARI").val();
		var loc = "{{ url('/cust/edit/') }}" + '?idx={{ $header->NO_ID}}&tipx=search&kodex=' +encodeURIComponent(cari);
		window.location = loc;
		
	}
	

     var hasilCek;
	function cekCust(kodec) {
		$.ajax({
			type: "GET",
			url: "{{url('cust/cekcust')}}",
            async: false,
			data: ({ KODEC: kodec, }),
			success: function(data) {
                if (data.length > 0) {
                    $.each(data, function(i, item) {
                        hasilCek=data[i].ADA;
                    });
                }
			},
			error: function() {
				alert('Error cekCust occured');
			}
		});
		return hasilCek;
	}
    
	function simpan() {
        //cekCust($('#KODEC').val());
        //(hasilCek==0) ? document.getElementById("entri").submit() : alert('Customer '+$('#KODEC').val()+' sudah ada!');
        document.getElementById("entri").submit() 
	}
</script>
@endsection

