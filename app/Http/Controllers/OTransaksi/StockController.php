<?php

namespace App\Http\Controllers\OTransaksi;

use App\Http\Controllers\Controller;
// ganti 1

use App\Models\OTransaksi\Stock;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;

// ganti 2
class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    var $judul = '';
    var $FLAGZ = '';
    var $GOLZ = '';
	
    function setFlag(Request $request)
    {
        if ( $request->flagz == 'KR' && $request->golz == 'Y' ) {
            $this->judul = "Koreksi Barang";
        } 
		
		
        $this->FLAGZ = $request->flagz;
        $this->GOLZ = $request->golz;    
		

    }	 


    public function index(Request $request)
    {

        // ganti 3
        $this->setFlag($request);
        // ganti 3
        return view('otransaksi_stock.index')->with(['judul' => $this->judul, 'golz' => $this->GOLZ , 'flagz' => $this->FLAGZ ]);
    }


    // ganti 4

    public function getStock(Request $request)
    {
        // ganti 5

        if ($request->session()->has('periode')) {
            $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        } else {
            $periode = '';
        }


        $this->setFlag($request);
		
        $stock = DB::SELECT("SELECT * from stock  where  PER ='$periode' and FLAG ='$this->FLAGZ' AND GOL ='$this->GOLZ'  ORDER BY NO_BUKTI ");
	
        // ganti 6

        return Datatables::of($stock)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant" || Auth::user()->divisi=="penjualan") 
                {

                    $btnEdit =   ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' href="stock/edit/?idx
					=' . $row->NO_ID . '&tipx=edit&flagz=' . $row->FLAG . '&golz=' . $row->GOL . '&judul=' . $this->judul . '"';
					
                    $btnDelete = ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)" href="stock/delete/' . $row->NO_ID . '/?flagz=' . $row->FLAG . '&golz=' . $row->GOL . '" ';


                    $btnPrivilege =
                        '
                                <a class="dropdown-item" ' . $btnEdit . '>
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a class="dropdown-item btn btn-danger" href="stock/print/' . $row->NO_ID . '">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    Print
                                </a> 									
                                <hr></hr>
                                <a class="dropdown-item btn btn-danger" ' . $btnDelete . '>
   
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
            // GANTI 9

            [
                'NO_BUKTI'       => 'required',
                'TGL'              => 'required',
                'KD_BRG'      => 'required'

            ]
        );

		
		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;	
		
        //////     nomer otomatis

        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

        $bulan    = session()->get('periode')['bulan'];
        $tahun    = substr(session()->get('periode')['tahun'], -2);
        $query = DB::table('stock')->select('NO_BUKTI')->where('PER', $periode)->orderByDesc('NO_BUKTI')->limit(1)->get();

        if ($query != '[]') {
            $query = substr($query[0]->NO_BUKTI, -4);
            $query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
            $no_bukti = 'ST' . $tahun . $bulan . '-' . $query;
        } else {
            $no_bukti = 'ST' . $tahun . $bulan . '-0001';
        }



        // Insert Header

        // ganti 10

        $stock = Stock::create(
            [
                'NO_BUKTI'         => $no_bukti,
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'PER'              => $periode,
                'FLAG'             => 'KY',
               // 'TYPE'             => ($request['TYPE'] == null) ? "" : $request['TYPE'],
                'KD_BRG'            => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'            => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'KG'              => (float) str_replace(',', '', $request['KG']),
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'USRNM'            => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );


        //  ganti 11
        $variablell = DB::select('call stockbins(?)', array($no_bukti));
	    $no_buktix = $no_bukti;
		
		$stock = Stock::where('NO_BUKTI', $no_buktix )->first();
					 
        //return redirect('/stock/edit/?idx=' . $stock->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&flagz=' . $this->FLAGZ . '&judul=' . $this->judul . '');
		return redirect('/stock?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);

    }

  
    // ganti 15
   public function edit( Request $request , Stock $stock)
    {


		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
		
				
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect('/stock')
			       ->with('status', 'Maaf Periode sudah ditutup!')
                   ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ]);
        }
		
		$this->setFlag($request);
		
        $tipx = $request->tipx;

		$idx = $request->idx;
			

		
		if ( $idx =='0' && $tipx=='undo'  )
	    {
			$tipx ='top';
			
		   }
		   
		 
		   
		if ($tipx=='search') {
			
		   	
    	   $buktix = $request->buktix;
		   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from stock
		                 where PER ='$per' and FLAG ='$this->FLAGZ'
						 and GOL ='$this->GOLZ' and NO_BUKTI = '$buktix'						 
		                 ORDER BY NO_BUKTI ASC  LIMIT 1" );
						 
			
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
			

		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from stock 
		                 where PER ='$per' and GOL ='$this->GOLZ' 
						 and FLAG ='$this->FLAGZ'    
		                 ORDER BY NO_BUKTI ASC  LIMIT 1" );
						 
		
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
			
    	   $buktix = $request->buktix;
			
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from stock      
		             where PER ='$per' and GOL ='$this->GOLZ' 
					 and FLAG ='$this->FLAGZ'  and NO_BUKTI < 
					 '$buktix' ORDER BY NO_BUKTI DESC LIMIT 1" );
			

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
			
				
      	   $buktix = $request->buktix;
	   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from stock    
		             where PER ='$per' and GOL ='$this->GOLZ' 
					 and FLAG ='$this->FLAGZ'  and NO_BUKTI > 
					 '$buktix' ORDER BY NO_BUKTI ASC LIMIT 1" );
					 
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
		  
    		$bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from stock  where PER ='$per'
            			and GOL ='$this->GOLZ' and FLAG ='$this->FLAGZ'  
		              ORDER BY NO_BUKTI DESC  LIMIT 1" );
					 
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
			$stock = Stock::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
				$stock = new Stock;
                $stock->TGL = Carbon::now();
      
				
		 }

        $no_bukti = $stock->NO_BUKTI;
				
		$data = [
            'header'        => $stock,

        ];
 
         
         return view('otransaksi_stock.edit', $data)
		 ->with(['tipx' => $tipx, 'idx' => $idx, 'golz' =>$this->GOLZ, 'flagz' =>$this->FLAGZ, 'judul', $this->judul ]);
			 
    
      
    }



    // ganti 18

    public function update(Request $request, Stock $stock)
    {

        $this->validate(
            $request,
            [

                // ganti 19

                'TGL'      => 'required'
            ]
        );

        // ganti 20
        $variablell = DB::select('call stockbdel(?)', array($stock['NO_BUKTI']));

        $stock->update(
            [
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'KD_BRG'           => ($request['NOTES'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NOTES'] == null) ? "" : $request['NA_BRG'],
               // 'TYPE'             => ($request['TYPE'] == null) ? "" : $request['TYPE'],
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KG'               => (float) str_replace(',', '', $request['KG']),
                'USRNM'            => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );



        //  ganti 21
        $variablell = DB::select('call stockbins(?)', array($stock['NO_BUKTI']));

		$no_buktix = $stock->NO_BUKTI;
		
		$stock = Stock::where('NO_BUKTI', $no_buktix )->first();
					 
        //return redirect('/stock/edit/?idx=' . $stock->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&flagz=' . $this->FLAGZ . '&judul=' . $this->judul . '');
		return redirect('/stock?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 22

    public function destroy( Request $request, Stock $stock)
    {

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		
		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
           return redirect()->route('stock')
                ->with('status', 'Maaf Periode sudah ditutup!')
                ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ]);
				
        }
		
        $variablell = DB::select('call stockbdel(?)', array($stock['NO_BUKTI']));

        // ganti 23
        $deleteStock = Stock::find($stock->NO_ID);

        // ganti 24

        $deleteStock->delete();

        // ganti 

		return redirect('/stock?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ])
			   ->with('statusHapus', 'Data '.$stock->NO_BUKTI.' berhasil dihapus');

    }
}
