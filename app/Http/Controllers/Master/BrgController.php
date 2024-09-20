<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
// ganti 1

use App\Models\Master\Brg;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;

// ganti 2
class BrgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // ganti 3
        return view('master_brg.index');
    }

    // ganti 4

    // ganti 4
    public function browse(Request $request)
	
    {

        $brg = DB::table('brg')->select('KD_BRG', 'NA_BRG', 'SATUAN')->where('GOL', $request['GOL'])->orderBy('KD_BRG', 'ASC')->get();
        // $brg = DB::table('brg')->select('KD_BRG', 'NA_BRG', 'SATUAN')->orderBy('KD_BRG', 'ASC')->get();
        return response()->json($brg); 
    
	}

    public function getBrg( Request $request )
    {
        // ganti 5

        $brg = DB::SELECT("SELECT * from brg  ORDER BY KD_BRG ");
	

        // ganti 6

        return Datatables::of($brg)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant" || Auth::user()->divisi=="accounting" || Auth::user()->divisi=="pembelian" || Auth::user()->divisi=="penjualan") 
                {
                    $btnPrivilege =
                        '
                                <a class="dropdown-item" href="brg/edit/?idx=' . $row->NO_ID . '&tipx=edit";
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <hr></hr>
                                <a class="dropdown-item btn btn-danger" onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)" href="brg/delete/' . $row->NO_ID . '">
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
                            <a class="dropdown-item" href="brg/show/' . $row->NO_ID . '">
                            <i class="fas fa-eye"></i>
                                Lihat
                            </a>

                            ' . $btnPrivilege . '
                        </div>
                    </div>
                    ';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
			
			
    }

    
    public function store(Request $request)
    {


        $this->validate(
            $request,
            // GANTI 9

            [
                'KD_BRG'       => 'required|unique:brg,KD_BRG',

            ]

        );

        // Insert Header

        // ganti 10

        $brg = Brg::create(
            [
                'KD_BRG'         => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'         => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'GOL'            => ($request['GOL'] == null) ? "" : $request['GOL'],
                'SATUAN'         => ($request['SATUAN'] == null) ? "" : $request['SATUAN'],
                'USRNM'          => Auth::user()->username,
                'TG_SMP'         => Carbon::now()
            ]
        );

        //  ganti 11

	    $kd_brgx = $request['KD_BRG'];
		
		$brg = Brg::where('KD_BRG', $kd_brgx )->first();
					       
        //return redirect('/brg/edit/?idx=' . $brg->NO_ID . '&tipx=edit')->with('statusInsert', 'Data baru berhasil ditambahkan');
		return redirect('/brg')->with('statusInsert', 'Data baru berhasil ditambahkan');	
		
		}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    

    // ganti 15

    public function edit(Request $request , Brg $brg)
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
		   
		   $bingco = DB::SELECT("SELECT NO_ID, ACNO from brg
		                 where KD_BRG = '$kodex'						 
		                 ORDER BY KD_BRG ASC  LIMIT 1" );
						 
			
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
			
		   $bingco = DB::SELECT("SELECT NO_ID, KD_BRG from brg      
		                 ORDER BY KD_BRG ASC  LIMIT 1" );
					 
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
			
		   $bingco = DB::SELECT("SELECT NO_ID, KD_BRG from BRG      
		             where KD_BRG < 
					 '$kodex' ORDER BY KD_BRG DESC LIMIT 1" );
			

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
	   
		   $bingco = DB::SELECT("SELECT NO_ID, KD_BRG from BRG   
		             where KD_BRG > 
					 '$kodex' ORDER BY KD_BRG ASC LIMIT 1" );
					 
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
		  
    		$bingco = DB::SELECT("SELECT NO_ID, KD_BRG from BRG     
		              ORDER BY KD_BRG DESC  LIMIT 1" );
					 
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
			$brg = Brg::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
             $brg = new Brg;			 
		 }

		 $data = [
						'header' => $brg,
			        ];				
			return view('master_brg.edit', $data)->with(['tipx' => $tipx, 'idx' => $idx ]);
		 
 
       
		
		
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 18

    public function update(Request $request, Brg $brg)
    {

        $this->validate(
            $request,
            [

                // ganti 19

                'KD_BRG'       => 'required',
                'NA_BRG'      => 'required'
            ]
        );

        // ganti 20
		
        $tipx = 'edit';
		$idx = $request->idx;

        $brg->update(
            [

                'NA_BRG'       => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'SATUAN'       => ($request['SATUAN'] == null) ? "" : $request['SATUAN'],			 
				'USRNM'          => Auth::user()->username,
                'TG_SMP'         => Carbon::now()


            ]
        );


        //  ganti 21

        //return redirect('/brg/edit/?idx=' . $brg->NO_ID . '&tipx=edit');
		return redirect('/brg')->with('status', 'Data berhasil diupdate');
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 22

    public function destroy(Request $request , Brg $brg)
    {

        // ganti 23
        $deleteBrg = Brg::find($brg->NO_ID);

        // ganti 24

        $deleteBrg->delete();

        // ganti 
        return redirect('/brg')->with('status', 'Data berhasil dihapus');
    }


    public function cekbarang(Request $request)
    {
        $getItem = DB::SELECT('select count(*) as ADA from brg where KD_BRG ="' . $request->KDBRG . '"');

        return $getItem;
    }
	
    public function getSelectKdbrg(Request $request)
    {
        $search = $request->search;
        $page = $request->page;
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        
        $hasil = DB::SELECT("SELECT KD_BRG, NA_BRG, SATUAN from brg WHERE (KD_BRG LIKE '%$search%' or NA_BRG LIKE '%$search%') ORDER BY KD_BRG LIMIT $xa,$perPage ");
        $selectajax = array();
        foreach ($hasil as $row => $value) {
            $selectajax[] = array(
                'id' => $hasil[$row]->KD_BRG,
                'text' => $hasil[$row]->KD_BRG,
                'na_brg' => $hasil[$row]->NA_BRG,
                'satuan' => $hasil[$row]->SATUAN,
            );
        }
        $select['total_count'] =  count($selectajax);
        $select['items'] = $selectajax;
        return response()->json($select);
    }
}
