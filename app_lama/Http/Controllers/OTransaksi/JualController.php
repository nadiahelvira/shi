<?php

namespace App\Http\Controllers\OTransaksi;

use App\Http\Controllers\Controller;
// ganti 1

use App\Models\OTransaksi\Jual;
use App\Models\OTransaksi\JualDetail;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;

include_once base_path() . "/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";

use PHPJasperXML;

// ganti 2
class JualController extends Controller
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
        if ( $request->flagz == 'JL' && $request->golz == 'A2' ) {
            $this->judul = "Penjualan Barang";
        } else if ( $request->flagz == 'TP' && $request->golz == 'A2' ) {
            $this->judul = "Transaksi Piutang";
        } else if ( $request->flagz == 'UM' && $request->golz == 'A2' ) {
            $this->judul = "Uang Muka Penjualan";
        }
				
        $this->FLAGZ = $request->flagz;
        $this->GOLZ = $request->golz;    
		
	}
	
    public function index(Request $request)
    {

        // ganti 3
        $this->setFlag($request);
        // ganti 3
        return view('otransaksi_jual.index')->with(['judul' => $this->judul, 'golz' => $this->GOLZ , 'flagz' => $this->FLAGZ ]);
    }


    public function browse(Request $request)
    {
        //$jual = DB::table('jual')->select('NO_BUKTI', 'TGL', 'KODEC','NAMAC', 'ALAMAT','KOTA', 'TOTAL','BAYAR','SISA')->where('NO_SO', $request['NO_SO'] )->where('SISA', '<>', 0 )->where('GOL', 'Y')->orderBy('KODEC', 'ASC')->get();

        $listDetail = implode(",", $request->listDetail);
        $inDetail = '';
        if ($request->listDetail) {
            $inDetail = " and NO_BUKTI not in ($listDetail) ";
        }

        $jual = DB::SELECT("SELECT NO_BUKTI,TGL,KODEC,NAMAC,ALAMAT,KOTA,TOTAL,BAYAR,SISA,TRUCK,if(DATEDIFF(date(now()),TGL)>=30,'Y','') as LEBIH30 from jual
		WHERE NO_SO='" . $request['NO_SO'] . "' and SISA<>0 and GOL='Y' " . $inDetail . " 
		ORDER BY KODEC;");

        return response()->json($jual);
    }

    public function browseuang(Request $request)
    {
        //$beli = DB::table('beli')->select('NO_BUKTI', 'TGL', 'KODES','NAMAS', 'ALAMAT','KOTA', 'TOTAL','BAYAR','SISA')->where('KODES', $request['KODES'] )->where('SISA', '<>', 0 )->where('GOL', 'Y')->orderBy('KODES', 'ASC')->get();

		$listDetail = implode(",", $request->listDetail);
        $inDetail = '';
        if ($request->listDetail) {
            $inDetail = " and NO_BUKTI not in ($listDetail) ";
        }

        $jual = DB::SELECT("SELECT NO_BUKTI,TGL, NO_SO, KODEC, NAMAC, RPTOTAL AS TOTAL, RPBAYAR AS BAYAR, RPSISA AS SISA  from jual
		WHERE  NO_SO='" . $request['NO_SO'] . "' AND RPSISA<>0  ORDER BY NO_BUKTI; ");

        return response()->json($jual);
    }
	
	
    // ganti 4

    public function getJual(Request $request)
    {
        // ganti 5

        if ($request->session()->has('periode')) {
            $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        } else {
            $periode = '';
        }

        $this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;	
		
        $jual = DB::SELECT("SELECT * from jual  where  PER ='$periode' and FLAG ='$this->FLAGZ' AND GOL ='$this->GOLZ'  ORDER BY NO_BUKTI ");

  
        // ganti 6

        return Datatables::of($jual)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant" || Auth::user()->divisi=="penjualan") 
                {
 
                    $btnEdit =   ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' href="jual/edit/?idx
					=' . $row->NO_ID . '&tipx=edit&flagz=' . $row->FLAG . '&golz=' . $row->GOL . '&judul=' . $this->judul . '"';
					
                    $btnDelete = ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)" href="jual/delete/' . $row->NO_ID . '/?flagz=' . $row->FLAG . '&golz=' . $row->GOL . '" ';

 
                    $btnPrivilege =
                        '
                                <a class="dropdown-item" ' . $btnEdit . '>
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a class="dropdown-item btn btn-danger" href="jual/print/' . $row->NO_ID . '">
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
                'NO_SO'       => 'required',
                'TGL'      => 'required',
                'KODEC'       => 'required',

            ]
        );

        // Insert Header
		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;	
		
        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

        $bulan    = session()->get('periode')['bulan'];
        $tahun    = substr(session()->get('periode')['tahun'], -2);
       
        $no_bukti ='';
		$no_bukti2 ='';
		
		if ( $request->flagz == 'JL' && $request->golz == 'A2' ) {

            $query = DB::table('jual')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'JL')->where('GOL', 'A2')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'JA2' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'JA2' . $tahun . $bulan . '-0001';
				}
		
        } else if ( $request->flagz == 'TP' && $request->golz == 'A2' ) {

            $query = DB::table('jual')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'TP')->where('GOL', 'Y')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'TPY' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'TPY' . $tahun . $bulan . '-0001';
				}
				
        } else if ( $request->flagz == 'UM' && $request->golz == 'A2' ) {
 
            $query = DB::table('jual')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'UM')->where('GOL', 'Y')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'UJ' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'UJ' . $tahun . $bulan . '-0001';
				}
 
 			$bulan    = session()->get('periode')['bulan'];
            $tahun    = substr(session()->get('periode')['tahun'], -2);
            $query2 = DB::table('bank')->select('NO_BUKTI')->where('PER', $periode)->where('TYPE', 'BBM')->orderByDesc('NO_BUKTI')->limit(1)->get();

            if ($query2 != '[]') {
                $query2 = substr($query2[0]->NO_BUKTI, -4);
                $query2 = str_pad($query2 + 1, 4, 0, STR_PAD_LEFT);
                $no_bukti2 = 'BBM' . $tahun . $bulan . '-' . $query2;
            } else {
                $no_bukti2 = 'BBM' . $tahun . $bulan . '-0001';
            }
			
			
        } 
        
		
        // Insert Header

        // ganti 10

        $jual = Jual::create(
            [
                'NO_BUKTI'         => $no_bukti,
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'PER'              => $periode,
                'NO_SO'            => ($request['NO_SO'] == null) ? "" : $request['NO_SO'],
                'KODEC'            => ($request['KODEC'] == null) ? "" : $request['KODEC'],
                'NAMAC'            => ($request['NAMAC'] == null) ? "" : $request['NAMAC'],
                'TRUCK'            => ($request['TRUCK'] == null) ? "" : $request['TRUCK'],
                //'SOPIR'            => ($request['SOPIR'] == null) ? "" : $request['SOPIR'],
                'ALAMAT'           => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'             => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'FLAG'             => $FLAGZ,
                'GOL'               => $GOLZ,
                //'ACNOA'            => '113101',
                //'NACNOA'           => 'PIUTANG DAGANG ',
                //'ACNOB'            => '411101',
                //'NACNOB'           => 'PENJUALAN',
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
				'GDG'           => ($request['GDG'] == null) ? "" : $request['GDG'],
                'QTY'            => (float) str_replace(',', '', $request['QTY']),
                'KG'            => (float) str_replace(',', '', $request['KG']),
                'HARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                //'DPP'            => (float) str_replace(',', '', $request['DPP']),
                //'PPN'            => (float) str_replace(',', '', $request['PPN']),				
                'RPSISA'             => (float) str_replace(',', '', $request['TOTAL']),
                'RPTOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'BACNO'              => ($request['BACNO'] == null) ? "" : $request['BACNO'],
                'BNAMA'               => ($request['BNAMA'] == null) ? "" : $request['BNAMA'],	
                'RPRATE'          => (float) str_replace(',', '', $request['RPRATE']),			
                'NO_BANK'               => $no_bukti2,
                'USRNM'            => Auth::user()->username,
                'created_by'       => Auth::user()->username,
				
                'BA'               => (float) str_replace(',', '', $request['BA']),
                'BP'               => (float) str_replace(',', '', $request['BP']),
                'BAG'              => (float) str_replace(',', '', $request['BAG']),
                'KA'               => (float) str_replace(',', '', $request['KA']),
                'REF'              => (float) str_replace(',', '', $request['REF']),
                'RP'               => (float) str_replace(',', '', $request['RP']),
                'JUMREF'           => (float) str_replace(',', '', $request['JUMREF']),
                'KG1'             => (float) str_replace(',', '', $request['KG1']),
                'POT2'            => (float) str_replace(',', '', $request['POT2']),
                'KGBAG'             => (float) str_replace(',', '', $request['KGBAG']),
                'POT'            => (float) str_replace(',', '', $request['POT']),
                'GUDANG'            => ($request['GUDANG'] == null) ? "" : $request['GUDANG'],
               // 'KAPAL'            => ($request['KAPAL'] == null) ? "" : $request['KAPAL'],
			   
                'TG_SMP'           => Carbon::now()
            ]
        );


        //  ganti 11
		if ( $FLAGZ == 'JL' ) {	
		
			//$variablell = DB::select('call jualins(?)', array($no_bukti));
			
		} else if ( $FLAGZ == 'UM' ) {
			//$variablell = DB::select('call ujins(?,?)', array($no_bukti, $no_bukti2));

        } else if ( $FLAGZ == 'TP' ) {
            //$variablell = DB::select('call tpiuins(?)', array($no_bukti));
        }
		

	    $no_buktix = $no_bukti;
		
		$jual = Jual::where('NO_BUKTI', $no_buktix )->first();
					 
        // return redirect('/jual?flagz='.$FLAGZ.'&golz='.$GOLZ)
		//        ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);

        return redirect('/jual/edit/?idx=' . $jual->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&flagz=' . $this->FLAGZ . '&judul=' . $this->judul . '');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 12




   public function edit( Request $request , Jual $jual)
    {


		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
		
				
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect('/jual')
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
		   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from jual
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
			

		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from jual 
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
			
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from jual      
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
	   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from jual    
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
		  
    		$bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from jual  where PER ='$per'
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
			$jual = Jual::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
				$jual = new Jual;
                $jual->TGL = Carbon::now();
      
				
		 }

        $no_bukti = $jual->NO_BUKTI;
				
		$data = [
            'header'        => $jual,

        ];
 
         
         return view('otransaksi_jual.edit', $data)
		 ->with(['tipx' => $tipx, 'idx' => $idx, 'golz' =>$this->GOLZ, 'flagz' =>$this->FLAGZ, 'judul', $this->judul ]);
			 
    
      
    }


    // ganti 18

    public function update(Request $request, Jual $jual)
    {

        $this->validate(
            $request,
            [

                // ganti 19

                'TGL'      => 'required',
                'NO_SO'       => 'required',
                'KODEC'       => 'required',
            ]
        );

        // ganti 20


		
		

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;	
		

		if ( $FLAGZ == 'JL' ) {	
		
			//$variablell = DB::select('call jualdel(?)', array($jual['NO_BUKTI']));

			
		} else if ( $FLAGZ == 'UM' ) {

           // $variablell = DB::select('call ujdel(?,?)', array($jual['NO_BUKTI'], '0'));

        } else if ( $FLAGZ == 'TP' ) {
           // $variablell = DB::select('call tpiudel(?)', array($jual['NO_BUKTI']));

        }
		
		

        $jual->update(
            [
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'NO_SO'            => ($request['NO_SO'] == null) ? "" : $request['NO_SO'],
                'KODEC'            => ($request['KODEC'] == null) ? "" : $request['KODEC'],
                'NAMAC'            => ($request['NAMAC'] == null) ? "" : $request['NAMAC'],
                'TRUCK'            => ($request['TRUCK'] == null) ? "" : $request['TRUCK'],
                //'SOPIR'            => ($request['SOPIR'] == null) ? "" : $request['SOPIR'],
                'ALAMAT'           => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'             => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
				'GDG'           => ($request['GDG'] == null) ? "" : $request['GDG'],
                'QTY'            => (float) str_replace(',', '', $request['QTY']),
                'KG'            => (float) str_replace(',', '', $request['KG']),				
                'HARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                //'DPP'            => (float) str_replace(',', '', $request['DPP']),
                //'PPN'            => (float) str_replace(',', '', $request['PPN']),				
                'RPSISA'           => (float) str_replace(',', '', $request['TOTAL']),
                'RPTOTAL'          => (float) str_replace(',', '', $request['TOTAL']),
                'BACNO'              => ($request['BACNO'] == null) ? "" : $request['BACNO'],
                'BNAMA'               => ($request['BNAMA'] == null) ? "" : $request['BNAMA'],	
                'RPRATE'           => (float) str_replace(',', '', $request['RPRATE']),			
                'USRNM'            => Auth::user()->username,
                'updated_by'       => Auth::user()->username,
				
				
                'BA'               => (float) str_replace(',', '', $request['BA']),
                'BP'               => (float) str_replace(',', '', $request['BP']),
                'BAG'              => (float) str_replace(',', '', $request['BAG']),
                'KA'               => (float) str_replace(',', '', $request['KA']),
                'REF'              => (float) str_replace(',', '', $request['REF']),
                'RP'               => (float) str_replace(',', '', $request['RP']),
                'JUMREF'           => (float) str_replace(',', '', $request['JUMREF']),
                'KG1'             => (float) str_replace(',', '', $request['KG1']),
                'POT2'            => (float) str_replace(',', '', $request['POT2']),
                'KGBAG'             => (float) str_replace(',', '', $request['KGBAG']),
                'POT'            => (float) str_replace(',', '', $request['POT']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'GUDANG'            => ($request['GUDANG'] == null) ? "" : $request['GUDANG'],
               // 'KAPAL'            => ($request['KAPAL'] == null) ? "" : $request['KAPAL'],
				
                'TG_SMP'           => Carbon::now()
            ]
        );


        //  ganti 21
		if ( $FLAGZ == 'JL' ) {	
		
			//$variablell = DB::select('call jualins(?)', array($jual['NO_BUKTI']));
			
		} else if ( $FLAGZ == 'UM' ) {
         
     		//$variablell = DB::select('call ujins(?,?)', array($jual['NO_BUKTI'], 'X'));

        } else if ( $FLAGZ == 'TP' ) {
            //$variablell = DB::select('call tpiuins(?)', array($jual['NO_BUKTI']));
        }
		
		

	    $no_buktix = $jual->NO_BUKTI;
		
		$jual = Jual::where('NO_BUKTI', $no_buktix )->first();
					 
        // return redirect('/jual?flagz='.$FLAGZ.'&golz='.$GOLZ)
		//        ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);

        return redirect('/jual/edit/?idx=' . $jual->NO_ID . '&tipx=edit&flagz=' . $this->FLAGZ . '&golz=' . $this->GOLZ . '&judul=' . $this->judul . '');
        
 
    }
	

    
   
	
    public function destroy( Request $request, Jual $jual)
    {

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		
		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
 
		$cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect()->route('jual')
                ->with('status', 'Maaf Periode sudah ditutup!')
                ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ]);
				
        }
		
		if ( $FLAGZ == 'JL' ) {	
		
			//$variablell = DB::select('call jualdel(?)', array($jual['NO_BUKTI']));
			
		} else if ( $FLAGZ == 'UM' ) {

			//$variablell = DB::select('call ujdel(?,?)', array($jual['NO_BUKTI'], '1'));
		
        } else if ( $FLAGZ == 'TP' ) {
            //$variablell = DB::select('call tpiudel(?)', array($jual['NO_BUKTI']));
        }
		
        // ganti 23
        $deleteJual = Jual::find($jual->NO_ID);

        // ganti 24

        $deleteJual->delete();

        // ganti 
		return redirect('/jual?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ])
			   ->with('statusHapus', 'Data '.$jual->NO_BUKTI.' berhasil dihapus');
			   
			   
    }
	///////////////////////////////////
	 public function cetak(Jual $jual)
    {
        $no_so = $jual->NO_SO;

        $file     = 'jualc';
        $PHPJasperXML = new PHPJasperXML();
        $PHPJasperXML->load_xml_file(base_path() . ('/app/reportc01/phpjasperxml/' . $file . '.jrxml'));
		

        $query = DB::SELECT("
            SELECT NO_SO,  TGL, KODEC, NAMAC, KD_BRG, NA_BRG, KG, HARGA, TOTAL, NOTES
			FROM so 
			WHERE so.NO_SO='$no_so' 
			ORDER BY NO_SO;
		");
		
		$xno_so1   = $query[0]->NO_SO;
        $xtgl1     = $query[0]->TGL;
        $xkodec1   = $query[0]->KODEC;
        $xnamac1   = $query[0]->NAMAC;
        $xnotes1   = $query[0]->NOTES;
        $xkd_brg1  = $query[0]->KD_BRG;
        $xna_brg1  = $query[0]->NA_BRG;
        $xkg1      = $query[0]->KG;
        $xharga1   = $query[0]->HARGA;
        $xtotal1   = $query[0]->TOTAL;
        
        $PHPJasperXML->arrayParameter = array("HARGA1" => (float) $xharga1, "TOTAL1" => (float) $xtotal1, "KG1" => (float) $xkg1, 
										"NO_SO1" => (string) $xno_so1, "TGL1" => (string) $xtgl1,  
										"KODEC1" => (string) $xkodec1,  "NAMAC1" => (string) $xnamac1,  "KD_BRG1" => (string) $xkd_brg1,  
										"NA_BRG1" => (string) $xna_brg1,  "NOTES1" => (string) $xnotes1 );
        $PHPJasperXML->arraysqltable = array();


        $query2 = DB::SELECT("
			SELECT NO_BUKTI, NO_SO, TGL, KODEC, NAMAC, if(ALAMAT='','NOT-FOUND.png',ALAMAT) as ALAMAT,  IF ( FLAG='BL' , 'A','B' ) AS FLAG, 
					KD_BRG, NA_BRG, KG, RPHARGA AS HARGA, RPTOTAL AS TOTAL, 0 AS BAYAR,  NOTES, TRUCK, BAG, KA, REF, KG1, BP, RPTOTAL
			FROM jual 
			WHERE jual.NO_SO='$no_so'  UNION ALL 
			SELECT NO_BUKTI, NO_SO, TGL, KODEC, NAMAC, if(ALAMAT='','NOT-FOUND.png',ALAMAT) as ALAMAT,  'C' AS FLAG,
			'' AS KD_BRG, '' AS NA_BRG, 0 AS KG, 
			0 AS HARGA, 0 AS TOTAL, BAYAR, NOTES, '' AS TRUCK, '' AS BAG, '' AS KA, '' AS REF, '' AS KG1, '' AS BP, '' AS RPTOTAL
			FROM piu
			WHERE piu.NO_SO='$no_so' 
			ORDER BY TGL, FLAG, NO_SO;
		");

        $data = [];


        $rec=1;
        $kdbrg = '';
        $nabrg = '';
        foreach ($query as $key => $value) {
            if($query[$key]->KD_BRG!='')
            {
                $kdbrg = $query[$key]->KD_BRG;
                $nabrg = $query[$key]->NA_BRG;
            }

           
                array_push($data, array(
				'NO_SO' => $query2[$key]->NO_SO,
                'NO_BUKTI' => $query2[$key]->NO_BUKTI,
                'TGL'      => $query2[$key]->TGL,
                'TRUCK'    => $query2[$key]->TRUCK,
                'NAMAS'    => $query2[$key]->NAMAS,
                'ALAMAT'    => $query2[$key]->ALAMAT,
                'BAG'    => $query2[$key]->BAG,
                'KA'       => $query2[$key]->KA,
                'REF'    => $query2[$key]->REF,
                'KG'       => $query2[$key]->KG,
                'KG1'       => $query2[$key]->KG1,
                'BP'       => $query2[$key]->BP,
                'HARGA'    => $query2[$key]->HARGA,
                'TOTAL'    => $query2[$key]->TOTAL,
                'RPTOTAL'    => $query2[$key]->RPTOTAL,
                'BAYAR'    => $query2[$key]->BAYAR,
                'NOTES'    => $query2[$key]->NOTES
               
            ));
            $rec++;
        }

        $PHPJasperXML->setData($data);
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }

}
