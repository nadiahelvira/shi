<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Sup;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;

class SupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master_sup.index');
    }


    public function browse(Request $request)
    {

		
        $sup = DB::table('sup')->select('KODES', 'NAMAS', 'ALAMAT', 'KOTA')->where('GOL', $request['GOL'] )->orderBy('KODES', 'ASC')->get();
        // $sup = DB::table('sup')->select('KODES', 'NAMAS', 'ALAMAT', 'KOTA')->orderBy('KODES', 'ASC')->get();
        return response()->json($sup);
    }


    public function getSup( Request $request )
    {
		
        $sup = DB::SELECT("SELECT * from sup ORDER BY KODES ");
	
        return Datatables::of($sup)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant" || Auth::user()->divisi=="accounting" || Auth::user()->divisi=="pembelian" || Auth::user()->divisi=="penjualan") 
                {
                    $btnPrivilege =
                        '
                                <a class="dropdown-item" href="sup/edit/?idx=' . $row->NO_ID . '&tipx=edit";                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <hr></hr>
                                <a class="dropdown-item btn btn-danger" onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)" href="sup/delete/' . $row->NO_ID . '">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    Delete
                                </a> 
                        ';
                } else {
                    $btnPrivilege = '';
                }

                $actionBtn =
                    '
                    <div class="dropdown show" style="text-align: center">
                        <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bars"></i>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                            ' . $btnPrivilege . '
                        </div>
                    </div>
                    ';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate(
            $request,
            // GANTI 8 SESUAI NAMA KOLOM DI NAVICAT //
            [
                'KODES'       => 'required',
                'NAMAS'       => 'required',
                'GOL'         => 'required'
            ]
        );

        // Insert Header

        $query = DB::table('sup')->select('KODES')->orderByDesc('KODES')->limit(1)->get();

   //     if ($query != '[]') {
   //         $query = substr($query[0]->KODES, -4);
   //         $query = str_pad($query + 1, 6, 0, STR_PAD_LEFT);
   //         $kodes = 'S'. $query;
   //     } else {
   //         $kodes = 'S' . '000001';
   //     }
		
        $sup = Sup::create(
            [
//              'KODES'         => $kodes,
                'KODES'         => ($request['KODES'] == null) ? "" : $request['KODES'],				
                'NAMAS'         => ($request['NAMAS'] == null) ? "" : $request['NAMAS'],
                //'KODESGD'     => ($request['KODESGD'] == null) ? "" : $request['KODESGD'],				
                //'NAMASGD'     => ($request['NAMASGD'] == null) ? "" : $request['NAMASGD'],
                'ALAMAT'        => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'          => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'TELPON1'       => ($request['TELPON1'] == null) ? "" : $request['TELPON1'],
                'FAX'           => ($request['FAX'] == null) ? "" : $request['FAX'],
                'HP'            => ($request['HP'] == null) ? "" : $request['HP'],
                'AKT'           => ($request['AKT'] == null) ? "" : $request['AKT'],
                'KONTAK'        => ($request['KONTAK'] == null) ? "" : $request['KONTAK'],
                'EMAIL'         => ($request['EMAIL'] == null) ? "" : $request['EMAIL'],
                'NPWP'          => ($request['NPWP'] == null) ? "" : $request['NPWP'],
                'KET'           => ($request['KET'] == null) ? "" : $request['KET'],
                'BANK'          => ($request['BANK'] == null) ? "" : $request['BANK'],
                'BANK_CAB'      => ($request['BANK_CAB'] == null) ? "" : $request['BANK_CAB'],
                'BANK_KOTA'     => ($request['BANK_KOTA'] == null) ? "" : $request['BANK_KOTA'],
                'BANK_NAMA'     => ($request['BANK_NAMA'] == null) ? "" : $request['BANK_NAMA'],
                'BANK_REK'      => ($request['BANK_REK'] == null) ? "" : $request['BANK_REK'],
                'GOL'           => ($request['GOL'] == null) ? "" : $request['GOL'],
                'TYPE'          => ($request['TYPE'] == null) ? "" : $request['TYPE'],
                'HARI'          => (float) str_replace(',', '', $request['HARI']),
                'USRNM'         => Auth::user()->username,
                'TG_SMP'        => Carbon::now()
            ]
        );


	    $kodesx = $request['KODES'];
		
		$sup = Sup::where('KODES', $kodesx )->first();
					       
        //return redirect('/sup/edit/?idx=' . $sup->NO_ID . '&tipx=edit')->with('statusInsert', 'Data baru berhasil ditambahkan');
		return redirect('/sup')->with('statusInsert', 'Data baru berhasil ditambahkan');		


    }

 
 
    public function edit(Request $request ,  Sup $sup)
    {

        // ganti 16


		$tipx = $request->tipx;

		$idx = $request->idx;
					

		
		if ( $idx =='0' && $tipx=='undo'  )
	    {
			$tipx ='top';
			
		   }
		   

		if ($tipx=='search') {
			
		   	
    	   $kodex = $request->kodex;
		   
		   $bingco = DB::SELECT("SELECT NO_ID, KODES from sup 
		                 where KODES = '$kodex'						 
		                 ORDER BY KODES ASC  LIMIT 1" );
						 
			
			if(!empty($bingco)) 
			{
				$idx = $bingco[0]->NO_ID;
			  }
			else
			{
				$idx = 0; 
			  }
		
					
		}
		   
		if ($tipx=='top') {
			
		   $bingco = DB::SELECT("SELECT NO_ID, KODES from sup      
		                 ORDER BY KODES ASC  LIMIT 1" );
					 
			if(!empty($bingco)) 
			{
				$idx = $bingco[0]->NO_ID;
			  }
			else
			{
				$idx = 0; 
			  }
			  
		}
		
		
		if ($tipx=='prev' ) {
			
    	   $kodex = $request->kodex;
			
		   $bingco = DB::SELECT("SELECT NO_ID, KODES from SUP     
		             where KODES < 
					 '$kodex' ORDER BY KODES DESC LIMIT 1" );
			

			if(!empty($bingco)) 
			{
				$idx = $bingco[0]->NO_ID;
			  }
			else
			{
				$idx = $idx; 
			  }
			  
			  
			  
			  

		}
		if ($tipx=='next' ) {
			
				
      	   $kodex = $request->kodex;
	   
		   $bingco = DB::SELECT("SELECT NO_ID, KODES from SUP    
		             where KODES > 
					 '$kodex' ORDER BY KODES ASC LIMIT 1" );
					 
			if(!empty($bingco)) 
			{
				$idx = $bingco[0]->NO_ID;
			  }
			else
			{
				$idx = $idx; 
			  }
			  
			
		}

		if ($tipx=='bottom') {
		  
    		$bingco = DB::SELECT("SELECT NO_ID, KODES from SUP    
		              ORDER BY KODES DESC  LIMIT 1" );
					 
			if(!empty($bingco)) 
			{
				$idx = $bingco[0]->NO_ID;
			  }
			else
			{
				$idx = 0; 
			  }
			  
			
		}


		if ( $tipx=='undo' || $tipx=='search' )
	    {
        
			$tipx ='edit';
			
		   }
		
	
	  	if ( $idx != 0 ) 
		{
			$sup = Sup::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
             $sup = new Sup;			 
		 }

		 $data = [
						'header' => $sup,
			        ];				
			return view('master_sup.edit', $data)->with(['tipx' => $tipx, 'idx' => $idx ]);
		 
	 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sup $sup)
    {

        $this->validate(
            $request,
            [
                'KODES'       => 'required',
                'NAMAS'      => 'required'
            ]
        );

		$tipx = 'edit';
		$idx = $request->idx;
		
        $sup->update(
            [

                'NAMAS'       	=> $request['NAMAS'],
                //'KODESGD'     => ($request['KODESGD'] == null) ? "" : $request['KODESGD'],
                //'NAMASGD'     => ($request['NAMASGD'] == null) ? "" : $request['NAMASGD'],
                'ALAMAT'        => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'          => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'TELPON1'       => ($request['TELPON1'] == null) ? "" : $request['TELPON1'],
                'FAX'           => ($request['FAX'] == null) ? "" : $request['FAX'],
                'HP'            => ($request['HP'] == null) ? "" : $request['HP'],
                'AKT'           => ($request['AKT'] == null) ? "" : $request['AKT'],
                'KONTAK'        => ($request['KONTAK'] == null) ? "" : $request['KONTAK'],
                'EMAIL'         => ($request['EMAIL'] == null) ? "" : $request['EMAIL'],
                'NPWP'          => ($request['NPWP'] == null) ? "" : $request['NPWP'],
                'KET'           => ($request['KET'] == null) ? "" : $request['KET'],
                'BANK'          => ($request['BANK'] == null) ? "" : $request['BANK'],
                'BANK_CAB'      => ($request['BANK_CAB'] == null) ? "" : $request['BANK_CAB'],
                'BANK_KOTA'     => ($request['BANK_KOTA'] == null) ? "" : $request['BANK_KOTA'],
                'BANK_NAMA'     => ($request['BANK_NAMA'] == null) ? "" : $request['BANK_NAMA'],
                'BANK_REK'      => ($request['BANK_REK'] == null) ? "" : $request['BANK_REK'],
                'HARI'          => (float) str_replace(',', '', $request['HARI']),
                'GOL'     		=> ($request['GOL'] == null) ? "" : $request['GOL'],
                'TYPE'     		=> ($request['TYPE'] == null) ? "" : $request['TYPE'],
                'USRNM'         => Auth::user()->username,
                'TG_SMP'        => Carbon::now()
            ]
        );


        //return redirect('/sup/edit/?idx=' . $sup->NO_ID . '&tipx=edit');
		return redirect('/sup')->with('statusInsert', 'Data baru berhasil diupdate');
				
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, Sup $sup)
    {
        $deleteSup = Sup::find($sup->NO_ID);
        $deleteSup->delete();

        return redirect('/sup')->with('status', 'Data berhasil dihapus');
    }

    public function ceksup(Request $request)
    {
        $getItem = DB::SELECT('select count(*) as ADA from sup where KODES ="' . $request->KODES . '"');

        return $getItem;
    }
	
    public function getSelectKodes(Request $request)
    {
        $search = $request->search;
        $page = $request->page;
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        
        $hasil = DB::SELECT("SELECT KODES, NAMAS from sup WHERE (KODES LIKE '%$search%' or NAMAS LIKE '%$search%') ORDER BY KODES LIMIT $xa,$perPage ");
        $selectajax = array();
        foreach ($hasil as $row => $value) {
            $selectajax[] = array(
                'id' => $hasil[$row]->KODES,
                'text' => $hasil[$row]->KODES,
                'namas' => $hasil[$row]->NAMAS,
            );
        }
        $select['total_count'] =  count($selectajax);
        $select['items'] = $selectajax;
        return response()->json($select);
    }
}
