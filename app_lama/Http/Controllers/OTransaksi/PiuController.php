<?php

namespace App\Http\Controllers\OTransaksi;

use App\Http\Controllers\Controller;
// ganti 1

use App\Models\OTransaksi\Piu;
use App\Models\OTransaksi\PiuDetail;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;

include_once base_path() . "/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";

use PHPJasperXML;


// ganti 2
class PiuController extends Controller
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
        if ( $request->flagz == 'PP' && $request->golz == 'A2' ) {
            $this->judul = "Pembayaran Barang";
        } 
		
		
        $this->FLAGZ = $request->flagz;
        $this->GOLZ = $request->golz;    
		

    }	 


    public function index(Request $request)
    {

        // ganti 3
        $this->setFlag($request);
        // ganti 3
        return view('otransaksi_piu.index')->with(['judul' => $this->judul, 'golz' => $this->GOLZ , 'flagz' => $this->FLAGZ ]);
    }


    // ganti 4

    public function getPiu(Request $request)
    {
        // ganti 5

        if ($request->session()->has('periode')) {
            $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        } else {
            $periode = '';
        }

        $this->setFlag($request);
		
        $piu = DB::SELECT("SELECT * from piu  where  PER ='$periode' and FLAG ='$this->FLAGZ' AND GOL ='$this->GOLZ'  ORDER BY NO_BUKTI ");
	

        // ganti 6

        return Datatables::of($piu)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant") 
                {

                    $btnEdit =   ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' href="piu/edit/?idx
					=' . $row->NO_ID . '&tipx=edit&flagz=' . $row->FLAG . '&golz=' . $row->GOL . '&judul=' . $this->judul . '"';
					
                    $btnDelete = ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)"  href="piu/delete/' . $row->NO_ID . '/?flagz=' . $row->FLAG . '&golz=' . $row->GOL . '" ';



                    $btnPrivilege =
                        '
                                <a class="dropdown-item" ' . $btnEdit . '>
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a class="dropdown-item btn btn-danger" href="jasper-piu-trans/' . $row->NO_ID . '">
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
                'TGL'         => 'required',
                'KODEC'       => 'required',
                'BACNO'       => 'required'

            ]
        );

        // Insert Header


        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;	
		

        $bulan    = session()->get('periode')['bulan'];
        $tahun    = substr(session()->get('periode')['tahun'], -2);

		if ( $request->flagz == 'PP' && $request->golz == 'A2' ) {

            $query = DB::table('piu')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'PP')->where('GOL', 'A2')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'PA2' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'PA2' . $tahun . $bulan . '-0001';
				}
		
        } 


        $typebayar = substr($request['BNAMA'],0,1);
		
		if ( $typebayar != 'K' )
        {
			
			$bulan	= session()->get('periode')['bulan'];
			$tahun	= substr(session()->get('periode')['tahun'],-2);
			$query2 = DB::table('bank')->select('NO_BUKTI')->where('PER', $periode)->where('TYPE', 'BBM')->orderByDesc('NO_BUKTI')->limit(1)->get();
		
			if ($query2 != '[]')
			{
				$query2 = substr($query2[0]->NO_BUKTI, -4);
				$query2 = str_pad($query2 + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti2 = 'BBM'.$tahun.$bulan.'-'.$query2;
			} else {
				$no_bukti2 = 'BBM'.$tahun.$bulan.'-0001';
			}
		}

		else 
        {
			
    		$bulan    = session()->get('periode')['bulan'];
            $tahun    = substr(session()->get('periode')['tahun'], -2);
            $query2 = DB::table('kas')->select('NO_BUKTI')->where('PER', $periode)->where('TYPE', 'BKM')->orderByDesc('NO_BUKTI')->limit(1)->get();

            if ($query2 != '[]') {
                $query2 = substr($query2[0]->NO_BUKTI, -4);
                $query2 = str_pad($query2 + 1, 4, 0, STR_PAD_LEFT);
                $no_bukti2 = 'BKM' . $tahun . $bulan . '-' . $query2;
            } else {
                $no_bukti2 = 'BKM' . $tahun . $bulan . '-0001';
            }

        }

        // ganti 10

        $piu = Piu::create(
            [
                'NO_BUKTI'         => $no_bukti,
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'PER'              => $periode,
                'NO_SO'            => ($request['NO_SO'] == null) ? "" : $request['NO_SO'],
                'KODEC'            => ($request['KODEC'] == null) ? "" : $request['KODEC'],
                'NAMAC'            => ($request['NAMAC'] == null) ? "" : $request['NAMAC'],
                'BACNO'            => ($request['BACNO'] == null) ? "" : $request['BACNO'],
                'BNAMA'            => ($request['BNAMA'] == null) ? "" : $request['BNAMA'],
                'NO_BANK'          => $no_bukti2,
                'FLAG'             => $FLAGZ,
                'GOL'               => $GOLZ,
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'BAYAR'            => (float) str_replace(',', '', $request['BAYAR']),
                'LAIN'            => (float) str_replace(',', '', $request['LAIN']),
                'RPRATE'           => '1',
                'USRNM'            => Auth::user()->username,
                //'created_by'       => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );

        //  ganti 11
        //$variablell = DB::select('call piuins(?,?)', array($no_bukti, $no_bukti2));

		$variablell = DB::select('call piuins(?)', array($no_bukti));

	    $no_buktix = $no_bukti;
		
		$piu = Piu::where('NO_BUKTI', $no_buktix )->first();
		
        DB::SELECT("UPDATE PIU, PIUD
                            SET PIUD.ID = PIU.NO_ID  WHERE PIU.NO_BUKTI = PIUD.NO_BUKTI 
							AND PIU.NO_BUKTI='$no_buktix';");
							

		return redirect('/piu?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);
    }


	public function edit( Request $request , Piu $piu)
    {


		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
		
				
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect('/piu')
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
		   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from piu
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
			

		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from piu 
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
			
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from piu     
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
	   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from piu   
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
		  
    		$bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from piu 
						where PER ='$per'
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
			$piu = Piu::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
				$piu = new Piu;
                $piu->TGL = Carbon::now();
      
				
		 }

        $no_bukti = $piu->NO_BUKTI;
				
        $piuDetail = DB::table('piud')->where('NO_BUKTI', $no_bukti)->get();
        $data = [
            'header'        => $piu,
            'detail'        => $piuDetail
        ];
 
         
         return view('otransaksi_piu.edit', $data)
		 ->with(['tipx' => $tipx, 'idx' => $idx, 'golz' =>$this->GOLZ, 'flagz' =>$this->FLAGZ, 'judul', $this->judul ]);
			 
    
      
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 18

    public function update(Request $request, Piu $piu)
    {

        $this->validate(
            $request,
            [

                // ganti 19

                'NO_SO'       => 'required',
                'TGL'         => 'required',
                'KODEC'       => 'required',
                'BACNO'       => 'required'
            ]
        );

        // ganti 20
        //$variablell = DB::select('call piudel(?,?)', array($piu['NO_BUKTI'], '0'));

        $variablell = DB::select('call piudel(?)', array($piu['NO_BUKTI']));
		
		
		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;

        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

        $piu->update(
            [
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'NO_SO'            => ($request['NO_SO'] == null) ? "" : $request['NO_SO'],
                'KODEC'            => ($request['KODEC'] == null) ? "" : $request['KODEC'],
                'NAMAC'                => ($request['NAMAC'] == null) ? "" : $request['NAMAC'],
                'BACNO'            => ($request['BACNO'] == null) ? "" : $request['BACNO'],
                'BNAMA'                => ($request['BNAMA'] == null) ? "" : $request['BNAMA'],
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'BAYAR'            => (float) str_replace(',', '', $request['BAYAR']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'LAIN'            => (float) str_replace(',', '', $request['LAIN']),
                'RPRATE'           => '1',
                'USRNM'            => Auth::user()->username,
                //'updated_by'       => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );

        //  ganti 21
        //$variablell = DB::select('call piuins(?,?)', array($piu['NO_BUKTI'], 'X'));

		$variablell = DB::select('call piuins(?)', array($piu['NO_BUKTI']));

	    $no_buktix = $piu->NO_BUKTI;
		
		$piu = Piu::where('NO_BUKTI', $no_buktix )->first();

		
        DB::SELECT("UPDATE PIU, PIUD
                            SET PIUD.ID = PIU.NO_ID  WHERE PIU.NO_BUKTI = PIUD.NO_BUKTI 
							AND PIU.NO_BUKTI='$no_buktix';");
							
							
        //return redirect('/piu/edit/?idx=' . $piu->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&flagz=' . $this->FLAGZ . '&judul=' . $this->judul . '');
		return redirect('/piu?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);
			   


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 22

    public function destroy( Request $request, Piu $piu)
    {

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		
		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
        
		$cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect()->route('piu')
                ->with('status', 'Maaf Periode sudah ditutup!')
                ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ]);
				
        }
		
        //$variablell = DB::select('call piudel(?,?)', array($piu['NO_BUKTI'], '1'));
		
		$variablell = DB::select('call piudel(?)', array($piu['NO_BUKTI']));

        // ganti 23
        $deletePiu = Piu::find($piu->NO_ID);

        // ganti 24

        $deletePiu->delete();

        // ganti 
		return redirect('/piu?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ])
			   ->with('statusHapus', 'Data '.$piu->NO_BUKTI.' berhasil dihapus');
			   
			   
    }

    public function jasperPiuTrans(Piu $piu)
    {
        $no_bukti = $piu->NO_BUKTI;

        $file     = 'piun';
        $PHPJasperXML = new PHPJasperXML();
        $PHPJasperXML->load_xml_file(base_path() . ('/app/reportc01/phpjasperxml/' . $file . '.jrxml'));

        $query = DB::SELECT("
			SELECT PIU.NO_BUKTI, PIU.TGL, PIU.NO_SO, PIU.KODEC, PIU.NAMAC, PIUD.NO_FAKTUR, PIUD.TOTAL,    
			PIUD.BAYAR 
			FROM PIU, PIUD WHERE PIU.NO_BUKTI = PIUD.NO_BUKTI AND PIU.NO_BUKTI='$no_bukti'
			ORDER BY PIU.NO_BUKTI;
		");

        $data = [];
        foreach ($query as $key => $value) {
            array_push($data, array(
                'NO_BUKTI' => $query[$key]->NO_BUKTI,
                'TGL' => $query[$key]->TGL,
                'NO_SO' => $query[$key]->NO_SO,
                'KODEC' => $query[$key]->KODEC,
                'NAMAC' => $query[$key]->NAMAC,
                'NO_FAKTUR' => $query[$key]->NO_FAKTUR,
                'TOTAL' => $query[$key]->TOTAL,
                'BAYAR' => $query[$key]->BAYAR,
            ));
        }
        $PHPJasperXML->setData($data);
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }
}
