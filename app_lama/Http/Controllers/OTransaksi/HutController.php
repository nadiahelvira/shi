<?php

namespace App\Http\Controllers\OTransaksi;

use App\Http\Controllers\Controller;
// ganti 1

use App\Models\OTransaksi\Hut;
use App\Models\OTransaksi\HutDetail;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;

include_once base_path() . "/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";
use PHPJasperXML;

// ganti 2
class HutController extends Controller
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
        if ( $request->flagz == 'PH' && $request->golz == 'A1' ) {
            $this->judul = "Pembayaran Hutang Barang";
        } else if ( $request->flagz == 'PH' && $request->golz == 'A2' ) {
            $this->judul = "Pembayaran Hutang Non";
        } 
		
		
        $this->FLAGZ = $request->flagz;
        $this->GOLZ = $request->golz;    
		

    }	 


    public function index(Request $request)
    {

        // ganti 3
        $this->setFlag($request);
        // ganti 3
        return view('otransaksi_hut.index')->with(['judul' => $this->judul, 'golz' => $this->GOLZ , 'flagz' => $this->FLAGZ ]);
    }


    // ganti 4
	
	
	

    public function getHut(Request $request)
    {
        // ganti 5

        if ($request->session()->has('periode')) {
            $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        } else {
            $periode = '';
        }

        $this->setFlag($request);
	
        $hut = DB::SELECT("SELECT * from hut  where  PER ='$periode' and FLAG ='$this->FLAGZ' AND GOL ='$this->GOLZ'  ORDER BY NO_BUKTI ");
	

        // ganti 6

        return Datatables::of($hut)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant" || Auth::user()->divisi=="pembelian") 
				{
                    //CEK POSTED di index dan edit
                    $btnEdit =   ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' href="hut/edit/?idx
					=' . $row->NO_ID . '&tipx=edit&flagz=' . $row->FLAG . '&golz=' . $row->GOL . '&judul=' . $this->judul . '"';
					
                    $btnDelete = ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)"  href="hut/delete/' . $row->NO_ID . '/?flagz=' . $row->FLAG . '&golz=' . $row->GOL . '" ';


                    $btnPrivilege =
                        '
                                <a class="dropdown-item" ' . $btnEdit . '>
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a class="dropdown-item btn btn-danger" href="hut/print/' . $row->NO_ID . '">
                                    <i class="fa fa-print" aria-hidden="true"></i>
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


    public function store(Request $request)
    {


        $this->validate(
            $request,
            // GANTI 9

            [
 //               'NO_PO'       => 'required',
                'TGL'      => 'required',
                'BACNO'       => 'required',
                'KODES'       => 'required'

            ]
        );

        //////     nomer otomatis

        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;	
		
        $bulan    = session()->get('periode')['bulan'];
        $tahun    = substr(session()->get('periode')['tahun'], -2);

        // Check apakah No Bukti terakhir NULL
 
		if ( $request->flagz == 'PH' && $request->golz == 'A1' ) {

            $query = DB::table('hut')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'PH')->where('GOL', 'A1')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'HA1' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'HA1' . $tahun . $bulan . '-0001';
				}
		
        } else if ( $request->flagz == 'PH' && $request->golz == 'A2' ) {

            $query = DB::table('hut')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'PH')->where('GOL', 'A2')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'HA2' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'HA2' . $tahun . $bulan . '-0001';
				}
				
        } 
        
		
//////////////////////////////////////////////////////////////////////////
        $typebayar = substr($request['BNAMA'],0,1);
        $totalbayar = (float) str_replace(',', '', $request['TBAYAR']);


		
		if ( $typebayar != 'K' )
        {	
		   
              $bulan    = session()->get('periode')['bulan'];
              $tahun    = substr(session()->get('periode')['tahun'], -2);
              $query2 = DB::table('bank')->select('NO_BUKTI')->where('PER', $periode)->where('TYPE', 'BBK')->orderByDesc('NO_BUKTI')->limit(1)->get();

              if ($query2 != '[]') {
                  $query2 = substr($query2[0]->NO_BUKTI, -4);
                  $query2 = str_pad($query2 + 1, 4, 0, STR_PAD_LEFT);
                  $no_bukti2 = 'BBK' . $tahun . $bulan . '-' . $query2;
              } else {
               $no_bukti2 = 'BBK' . $tahun . $bulan . '-0001';
              }
			  
		   
        } 
///////////////////////////////////////////////////////////////////////////
        else 
        {

    		   $bulan    = session()->get('periode')['bulan'];
               $tahun    = substr(session()->get('periode')['tahun'], -2);
               $query2 = DB::table('kas')->select('NO_BUKTI')->where('PER', $periode)->where('TYPE', 'BKK')->orderByDesc('NO_BUKTI')->limit(1)->get();

               if ($query2 != '[]') {
                  $query2 = substr($query2[0]->NO_BUKTI, -4);
                  $query2 = str_pad($query2 + 1, 4, 0, STR_PAD_LEFT);
                  $no_bukti2 = 'BKK' . $tahun . $bulan . '-' . $query2;
                } else {
                  $no_bukti2 = 'BKK' . $tahun . $bulan . '-0001';
                }
			
		   
            
        }

        // Insert Header

        // ganti 10

        $hut = Hut::create(
            [
                'NO_BUKTI'         => $no_bukti,
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'PER'              => $periode,
                'NO_PO'            => ($request['NO_PO'] == null) ? "" : $request['NO_PO'],
                'KODES'            => ($request['KODES'] == null) ? "" : $request['KODES'],
                'NAMAS'            => ($request['NAMAS'] == null) ? "" : $request['NAMAS'],
                'BACNO'            => ($request['BACNO'] == null) ? "" : $request['BACNO'],
                'BNAMA'            => ($request['BNAMA'] == null) ? "" : $request['BNAMA'],
                'NO_BANK'          => $no_bukti2,
                'FLAG'             =>  $FLAGZ,
                'GOL'               => $GOLZ,
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'BAYAR'            => (float) str_replace(',', '', $request['BAYAR']),
                'LAIN'            => (float) str_replace(',', '', $request['LAIN']),
                'USRNM'            => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );


        //  ganti 11
        //$variablell = DB::select('call hutins(?,?)', array($no_bukti, $no_bukti2));
		
		$variablell = DB::select('call hutins(?)', array($no_bukti));

	    $no_buktix = $no_bukti;
		
		$hut = Hut::where('NO_BUKTI', $no_buktix )->first();
					 
        DB::SELECT("UPDATE HUT, HUTD
                            SET HUTD.ID = HUT.NO_ID  WHERE HUT.NO_BUKTI = HUTD.NO_BUKTI 
							AND HUT.NO_BUKTI='$no_buktix';");

		return redirect('/hut?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 12


 public function edit( Request $request , Hut $hut)
    {


		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
		
				
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect('/hut')
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
		   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from hut
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
			

		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from  hut
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
			
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from hut      
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
	   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from hut    
		             where PER ='$per' and GOL ='$this->GOLZ' 
					 and FLAG ='$this->FLAGZ' and NO_BUKTI > 
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
		  
    		$bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from hut
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
			$hut = Hut::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
				$hut = new Hut;
                $hut->TGL = Carbon::now();
      
				
		 }

        $no_bukti = $hut->NO_BUKTI;
				
        $hutDetail = DB::table('hutd')->where('NO_BUKTI', $no_bukti)->get();
        $data = [
            'header'        => $hut,
            'detail'        => $hutDetail
        ];
 
         
         return view('otransaksi_hut.edit', $data)
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

    public function update(Request $request, Hut $hut)
    {

        $this->validate(
            $request,
            [

                // ganti 19

 //               'NO_PO'       => 'required',
                'TGL'      => 'required',
                'BACNO'       => 'required',
                'KODES'       => 'required'


            ]
        );


		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;	
		
		
        // ganti 20
        //$variablell = DB::select('call hutdel(?,?)', array($hut['NO_BUKTI'], '0'));
		
		$variablell = DB::select('call hutdel(?)', array($hut['NO_BUKTI']));

        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

        // ganti 20

        $hut->update(
            [
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'NO_PO'            => ($request['NO_PO'] == null) ? "" : $request['NO_PO'],
                'KODES'            => ($request['KODES'] == null) ? "" : $request['KODES'],
                'NAMAS'                => ($request['NAMAS'] == null) ? "" : $request['NAMAS'],
                'BACNO'            => ($request['BACNO'] == null) ? "" : $request['BACNO'],
                'BNAMA'                => ($request['BNAMA'] == null) ? "" : $request['BNAMA'],
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'BAYAR'            => (float) str_replace(',', '', $request['BAYAR']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'LAIN'            => (float) str_replace(',', '', $request['LAIN']),
                'USRNM'            => Auth::user()->username,
                //'updated_by'       => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );

        //  ganti 21
        //$variablell = DB::select('call hutins(?,?)', array($hut['NO_BUKTI'], 'X'));
		
		$variablell = DB::select('call hutins(?)', array($hut['NO_BUKTI']));

		$no_buktix = $hut->NO_BUKTI;
		
		$hut = Hut::where('NO_BUKTI', $no_buktix )->first();
					 
        DB::SELECT("UPDATE HUT, HUTD
                            SET HUTD.ID = HUT.NO_ID  WHERE HUT.NO_BUKTI = HUTD.NO_BUKTI 
							AND HUT.NO_BUKTI='$no_buktix';");
							
		return redirect('/hut?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 22

    public function destroy( Request $request, Hut $hut)
    {

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		
		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect()->route('hut')
                ->with('status', 'Maaf Periode sudah ditutup!')
                ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ]);
				
        }
		
        //$variablell = DB::select('call hutdel(?,?)', array($hut['NO_BUKTI'], '1'));
		
		$variablell = DB::select('call hutdel(?)', array($hut['NO_BUKTI']));


        // ganti 23
        $deleteHut = Hut::find($hut->NO_ID);

        // ganti 24

        $deleteHut->delete();

        // ganti 

		return redirect('/hut?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ])
			   ->with('statusHapus', 'Data '.$hut->NO_BUKTI.' berhasil dihapus');

    }
    
    public function cetak(Hut $hut)
    {
        $no_hut = $hut->NO_BUKTI;
		
		$selisih_hari = DB::SELECT("SELECT DATEDIFF(NOW(),TGL) as SELISIH from hut WHERE NO_BUKTI='$no_hut'");
        if ($selisih_hari[0]->SELISIH>14 && Auth::user()->divisi!="owner") {
            return redirect('/hut')->with('status', 'Pembayaran '.$no_hut.' melebihi 2 minggu, tidak bisa dicetak ulang..');
        }

        $file     = 'hutc';
        $PHPJasperXML = new PHPJasperXML();
        $PHPJasperXML->load_xml_file(base_path() . ('/app/reportc01/phpjasperxml/' . $file . '.jrxml'));

        $query = DB::SELECT("
            SELECT b.NO_BUKTI, b.KODES, b.NAMAS, b.ALAMAT, b.KOTA, b.KD_BRG, b.NA_BRG, b.TGL, b.KG1, b.KA, b.REF, b.JUMREF, b.KG, b.KG as NETT, 
			b.HARGA, b.B_KULI, b.B_TRANSPORT, b.TRUCK, b.B_MSOL, b.B_INAP, b.B_LAIN, b.TOTAL, a.BAYAR, b.NOTES, b.FLAG  
			from hutd a, beli b
			WHERE a.NO_FAKTUR=b.NO_BUKTI and a.NO_BUKTI='$no_hut'
			ORDER BY NO_BUKTI;
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
                'NO_BUKTI' => $no_hut,
                // 'TGL'      => date("d/m/Y", strtotime($hut->TGL)),
                'TGL'      => $hut->TGL,
                'KODES'    => $query[$key]->KODES,
                'NAMAS'    => $query[$key]->NAMAS,
                'ALAMAT'   => $query[$key]->ALAMAT,
                'KOTA'     => $query[$key]->KOTA,
                'REC'      => $rec,
                'NO_FAKTUR' => $query[$key]->NO_BUKTI,
                'TGL_FAKTUR'=> date("d/m/y", strtotime($query[$key]->TGL)),
                'KG1'      => $query[$key]->KG1,				
                'KA'       => $query[$key]->KA,
                'REF'      => $query[$key]->REF,
                'JUMREF'   => $query[$key]->JUMREF,
                'KG'       => $query[$key]->KG,
                'NETT'     => $query[$key]->NETT,
                'HARGA'    => $query[$key]->HARGA,
                'B_KULI'   => $query[$key]->B_KULI,
                'TRUCK'    => $query[$key]->TRUCK,
                'B_MSOL'   => $query[$key]->B_MSOL,
                'B_INAP'   => $query[$key]->B_INAP,
                'B_LAIN'   => $query[$key]->B_LAIN,
                'TOTAL'    => $query[$key]->TOTAL,
                'BAYAR'    => $query[$key]->BAYAR,
                'NOTES'    => $query[$key]->NOTES,
                'TGL_CETAK'=> date("d/m/Y"),
                'USR'      => Auth::user()->username,
                'B_TRANSPORT' => $query[$key]->B_TRANSPORT,
                'FLAG'    => $query[$key]->FLAG,
                'CETAK' => $hut->CETAK,
            ));
            $rec++;
        }

        $PHPJasperXML->arrayParameter = array(
            "KD_BRG" => (string) $kdbrg,
            "NA_BRG" => (string) $nabrg,
        );
        if ($hut->CETAK==0) DB::SELECT("UPDATE hut SET CETAK=1, TGL_CETAK=NOW() WHERE NO_BUKTI='$no_hut'");
		
        $PHPJasperXML->setData($data);
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }
}
