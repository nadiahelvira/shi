<?php

namespace App\Http\Controllers\OTransaksi;

use App\Http\Controllers\Controller;
use App\Http\Traits\Terbilang;
// ganti 1

use App\Models\OTransaksi\Bl;
use App\Models\OTransaksi\BlDetail;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;


include_once base_path() . "/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";

use PHPJasperXML;




// ganti 2
class BlController extends Controller
{
	use Terbilang;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
    var $judul = '';
    var $GOLZ = '';
    var $XBUKTI ='';


    function setFlag(Request $request)
    {
        if ( $request->golz == 'Y' ) {
            $this->judul = "Bill Of Lading";
        } else if ( $request->golz == '' ) {
            $this->judul = "";
        }
		
        $this->GOLZ = $request->golz;
        $this->XBUKTI = $request->xbukti;

    }	 
	 
	 
	 
    public function index(Request $request)
    {

        // ganti 3
        $this->setFlag($request);
        // ganti 3
        return view('otransaksi_bl.index')->with(['judul' => $this->judul, 'golz' => $this->GOLZ ]);
    }
	
	
    // ganti 4


    public function browse_bukti(Request $request)
    {
        $NO_BUKTI = $request->NO_BUKTI;

        $kel = DB::SELECT("SELECT KAS.NO_ID AS NO_IDHX, KAS.NO_BUKTI, KAS.TGL, KAS.BACNO, KAS.BNAMA, KAS.KET, KAS.JUMLAH AS TJUMLAH,
		                   KASD.REC, KASD.NO_ID, KASD.ACNO, KASD.NAMA, KASD.URAIAN, KASD.JUMLAH, KASD.VAL FROM 
						   KAS, KASD WHERE KAS.NO_BUKTI = KASD.NO_BUKTI and KASD.VAL = 0 
						   AND KAS.NO_BUKTI ='$NO_BUKTI' ");
       
		return response()->json($kel);
    }


    public function cari(Request $request)
    {
        

		$type01 = $request['golz'];
			
        if ($request->session()->has('periode')) {
            $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        } else {
            $periode = '';
        }

        $this->setFlag($request);
		
        $kel = DB::SELECT("SELECT KAS.NO_BUKTI, DATE_FORMAT(KAS.TGL,'%d/%m/%Y') AS TGL, KASD.ACNO, KASD.NACNO, KASD.URAIAN, KASD.NO_HUT,  KASD.JUMLAH FROM 
						   KAS, KASD WHERE KAS.NO_BUKTI = KASD.NO_BUKTI  AND LEFT(KAS.NO_BUKTI,3)='$type01' AND  KAS.PER = '$periode'  ");
 
 
 
		return response()->json($kel);
    }
	
    public function getBl(Request $request)
    {
        // ganti 5


        if ($request->session()->has('periode')) {
            $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        } else {
            $periode = '';
        }

        $this->setFlag($request);
		
        $bl = DB::SELECT("SELECT * from bl where  PER ='$periode'  ORDER BY NO_BUKTI ");
	  
        // ganti 6

        return Datatables::of($bl)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="blir" || Auth::user()->divisi=="accounting") 
                {
                    $btnPrivilege =


                    $btnEdit =    ' href="bl/edit/?idx=' . $row->NO_ID . '&tipx=edit&golz=' . $row->GOL . '&judul=' . $this->judul . '"';					
                    $btnDelete = ' href="bl/delete/' . $row->NO_ID . '/?golz=' . $row->GOL  . '" ';


                    $btnPrivilege =
                        '
                                <a class="dropdown-item" ' . $btnEdit . '>
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a class="dropdown-item btn btn-danger" href="bl/print/' . $row->NO_ID . '">
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                    Print
                                </a>	
                                <hr></hr>
                                <a class="dropdown-item btn btn-danger" onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)" ' . $btnDelete . '>
   
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
	 
    public function store(Request $request, Bl $bl )
    {

		
        $this->validate(
            $request,
            // GANTI 9

            [
                
                'TGL'          => 'required'

            ]
        );

        //////     nomer otomatis

		
		$this->setFlag($request);
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;	
		
        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

        $bulan    = session()->get('periode')['bulan'];
        $tahun    = substr(session()->get('periode')['tahun'], -2);
        $query = DB::table('bl')->select('NO_BUKTI')->where('PER', $periode)->where('GOL', $this->GOLZ)->orderByDesc('NO_BUKTI')->limit(1)->get();

        $bukti = $this->GOLZ . '-' . $tahun . $bulan;

        $no_bukti = $request->input('NO_BUKTI');
		
		// if ( $no_bukti == '' )
	    // {		
        // // Check apakah No Bukti terakhir NULL
        //    if ($query != '[]') {
        //        $query = substr($query[0]->NO_BUKTI, -4);
        //        $query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
        //        $no_bukti = $this->GOLZ . '-' . $tahun . $bulan . $query;
        //    } else {
        //        $no_bukti = $this->GOLZ . '-' . $tahun . $bulan . '0001';
        //    }
		
		// } else {
        //     $no_bukti = $bukti . $no_bukti;
        // }




        // Insert Header

        // ganti 10
  
        $bl = Bl::create(
            [
                'NO_BUKTI'         => $no_bukti,
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'PER'              => $periode,
                'NO_BELI'            => ($request['NO_BELI'] == null) ? "" : $request['NO_BELI'],
                'NO_PO'             => ($request['NO_PO'] == null) ? "" : $request['NO_PO'],
                'FLAG'              => 'BB',
                'GOL'               => $GOLZ,
                'KODES'              => ($request['KODES'] == null) ? "" : $request['KODES'],
                'NAMAS'              => ($request['NAMAS'] == null) ? "" : $request['NAMAS'],
                'KD_BRG'              => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'              => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'AJU'              => ($request['AJU'] == null) ? "" : $request['AJU'],
                'NOTES'              => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'TOTAL_KG'           => (float) str_replace(',', '', $request['TOTAL_KG']),
                'USRNM'            => Auth::user()->username,
                'created_by'       => Auth::user()->username,
                'TG_SMP'           => Carbon::now()

            ]
        );

        // Insert Detail
        $REC    = $request->input('REC');
        $NO_CONT    = $request->input('NO_CONT');
        $SEAL    = $request->input('SEAL');
        $EMKL    = $request->input('EMKL');
        $KG    = $request->input('KG');
        $KET    = $request->input('KET');
        $TGL_DTG    = $request->input('TGL_DTG');

        // Check jika value detail ada/tidak
        if ($REC) {
            foreach ($REC as $key => $value) {
                // Declare new data di Model
                $detail    = new BlDetail;

                // Insert ke Database
                $detail->NO_BUKTI   = $no_bukti;
                $detail->REC        = $REC[$key];
                $detail->PER        = $periode;
                $detail->FLAG       = 'BB';
                $detail->GOL        = $GOLZ;
                $detail->NO_CONT    = ($NO_CONT[$key] == null) ? "" :  $NO_CONT[$key];
                $detail->SEAL       = ($SEAL[$key] == null) ? "" :  $SEAL[$key];
                $detail->EMKL       = ($EMKL[$key] == null) ? "" :  $EMKL[$key];
                $detail->KET      = ($KET[$key] == null) ? "" :  $KET[$key];
                $detail->KG         = (float) str_replace(',', '', $KG[$key]);
                $detail->Tgl_dtg     = date('Y-m-d', strtotime($TGL_DTG[$key]));
                // $detail->USRNM      = Auth::user()->username;
                // $detail->TG_SMP     = Carbon::now();
                $detail->save();
            }
        }

        //  ganti 11

        // $variablell = DB::select('call blins(?)', array($no_bukti));
		
	    $no_buktix = $no_bukti;
		
		$bl = Bl::where('NO_BUKTI', $no_buktix )->first();

        DB::SELECT("UPDATE bl,  bld
                            SET  bld.ID =  bl.NO_ID  WHERE  bl.NO_BUKTI =  bld.NO_BUKTI 
							AND  bl.NO_BUKTI='$no_buktix';");
					 
        return redirect('/bl/edit/?idx=' . $bl->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&judul=' . $this->judul . '');

	
    }


    // ganti 15

    public function edit( Request $request , Bl $bl)
    {


		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
		
				
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect('/bl')
			       ->with('status', 'Maaf Periode sudah ditutup!')
                   ->with(['judul' => $judul, 'golz' => $GOLZ]);
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
		   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from bl 
		                 where PER ='$per' and GOL ='$this->GOLZ' 
						 and NO_BUKTI = '$buktix'						 
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
			

		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from bl 
		                 where PER ='$per' and GOL ='$this->GOLZ'     
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
			
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from bl      
		             where PER ='$per' and GOL ='$this->GOLZ'  and NO_BUKTI < 
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
	   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from bl    
		             where PER ='$per' and GOL ='$this->GOLZ'  and NO_BUKTI > 
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
		  
    		$bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from bl  where PER ='$per'
            			and GOL ='$this->GOLZ'    
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
			$bl = Bl::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
				$bl = new Bl;
                $bl->TGL = Carbon::now();
      
				
		 }

        $no_bukti = $bl->NO_BUKTI;
				
        $blDetail = DB::table('bld')->where('NO_BUKTI', $no_bukti)->get();
        $data = [
            'header'        => $bl,
            'detail'        => $blDetail
        ];
        
        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        
        $query = DB::table('bl')->select('NO_BUKTI')->where('PER', $periode)->where('GOL', $this->GOLZ)->orderByDesc('NO_BUKTI')->limit(1)->get();
		
		
		$xbukti = '';
        // Check apakah No Bukti terakhir NULL
        if ($query != '[]') {
            $xbukti = $query[0]->NO_BUKTI;
        } else {
            $xbukti ='';
        }


         return view('otransaksi_bl.edit', $data)
		 ->with(['tipx' => $tipx, 'idx' => $idx, 'golz' =>$this->GOLZ, 'xbukti' =>$xbukti, 'judul' => $this->judul ]);
			 
    
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 18

    public function update(Request $request, Bl $bl)
    {
        // return $request;
        $this->validate(
            $request,
            [
                // ganti 19
                'TGL'       => 'required'
            ]
        );

        // ganti 20
		
		
        // $variablell = DB::select('call bldel(?)', array($bl['NO_BUKTI']));
    
		
		$this->setFlag($request);
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;	
		
        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

      
	  
        $bl->update(
            [

                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'PER'              => $periode,
                'NO_BELI'            => ($request['NO_BELI'] == null) ? "" : $request['NO_BELI'],
                'NO_PO'             => ($request['NO_PO'] == null) ? "" : $request['NO_PO'],
                'FLAG'              => 'BB',
                'GOL'               => $GOLZ,
                'KODES'              => ($request['KODES'] == null) ? "" : $request['KODES'],
                'NAMAS'              => ($request['NAMAS'] == null) ? "" : $request['NAMAS'],
                'KD_BRG'              => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'              => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'AJU'              => ($request['AJU'] == null) ? "" : $request['AJU'],
                'NOTES'              => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'TOTAL_KG'           => (float) str_replace(',', '', $request['TOTAL_KG']),
                'USRNM'            => Auth::user()->username,
                'updated_by'       => Auth::user()->username,
                'TG_SMP'           => Carbon::now()

            ]
        );

		$no_buktix = $bl->NO_BUKTI;
		
		
        // Update Detail
        $length = sizeof($request->input('REC'));
		$NO_ID  = $request->input('NO_ID');
		$NO_CONT    = $request->input('NO_CONT');
        $SEAL    = $request->input('SEAL');
        $EMKL    = $request->input('EMKL');
        $KG    = $request->input('KG');
        $KET    = $request->input('KET');
        $TGL_DTG    = $request->input('TGL_DTG');


        // Delete yang NO_ID tidak ada di input
        $query = DB::table('bld')->where('NO_BUKTI', $request->NO_BUKTI)->whereNotIn('NO_ID',  $NO_ID)->delete();

        
        // Update / Insert
        for ($i = 0; $i < $length; $i++) {
            // Insert jika NO_ID baru
            if ($NO_ID[$i] == 'new') {
                $insert = BlDetail::create(
                    [
                        'NO_BUKTI'   => $no_buktix,
                        'REC'        => $REC[$i],
                        'PER'        => $periode,
                        'FLAG'       => 'BB',
                        'GOL'        =>  $GOLZ,
                        'NO_CONT'    => ($NO_CONT[$i] == null) ? "" :  $NO_CONT[$i],
                        'SEAL'       =>  ($SEAL[$i] == null) ? "" : $SEAL[$i],
                        'EMKL'       => ($EMKL[$i] == null) ? "" : $EMKL[$i],
                        'KG'         => (float) str_replace(',', '', $KG[$i]),
                        'Tgl_dtg'   => ($TGL_DTG[$i] != '') ? date("Y-m-d", strtotime($TGL_DTG[$i])) : "",
                        'KET'      => ($KET[$i] == null) ? "" : $KET[$i],
                        // 'USRNM'      => Auth::user()->username,
                        // 'TG_SMP'     => Carbon::now(),
						

                    ]
                );
            } else {
                // Update jika NO_ID sudah ada
                $update = BlDetail::updateOrCreate(
                    [
                        'NO_BUKTI'  => $no_buktix,
                        'NO_ID'     => (int) str_replace(',', '', $NO_ID[$i])
                    ],

                    [
                        'REC'        => $REC[$i],
                        'NO_CONT'    => ($NO_CONT[$i] == null) ? "" :  $NO_CONT[$i],
                        'SEAL'       =>  ($SEAL[$i] == null) ? "" : $SEAL[$i],
                        'EMKL'       => ($EMKL[$i] == null) ? "" : $EMKL[$i],
                        'KG'         => (float) str_replace(',', '', $KG[$i]),
                        'Tgl_dtg'   => ($TGL_DTG[$i] != '') ? date("Y-m-d", strtotime($TGL_DTG[$i])) : "",
                        'KET'      => ($KET[$i] == null) ? "" : $KET[$i],
                        // 'USRNM'      => Auth::user()->USERNAME,
                        // 'TG_SMP'     => Carbon::now(),

                    ]
                );
            }
        }

 
        // ganti 21
        // $variablell = DB::select('call blins(?)', array($bl['NO_BUKTI']));
		
		$bl = Bl::where('NO_BUKTI', $no_buktix )->first();
					 
        return redirect('/bl/edit/?idx=' . $bl->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&judul=' . $this->judul . '');			
 
	}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 22

    public function destroy( Request $request, Bl $bl)
    {
        
		$this->setFlag($request);
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect()->route('bl')
                ->with('status', 'Maaf Periode sudah ditutup!')
                ->with(['judul' => $judul, 'golz' => $GOLZ]);
        }
		
		
		
		
        // $variablell = DB::select('call bldel(?)', array($bl['NO_BUKTI']));

        // ganti 23
        $deleteBl = Bl::find($bl->NO_ID);

        // ganti 24
        $deleteBl->delete();

		 
        // ganti 
		return redirect('/bl?golz='.$GOLZ)->with(['judul' => $judul, 'golz' => $GOLZ ])->with('statusHapus', 'Data '.$bl->NO_BUKTI.' berhasil dihapus');


    }


    public function cetak(Bl $bl, Request $request)
    {
        $this->setFlag($request);
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;

        $no_bukti = $bl->NO_BUKTI;
        $TJUMLAH = $bl->JUMLAH;
		
        $file     = 'Transaksi_Bl_Bl';
        $PHPJasperXML = new PHPJasperXML();
        $PHPJasperXML->load_xml_file(base_path() . ('/app/reportc01/phpjasperxml/' . $file . '.jrxml'));

        $terbilangx = ucwords($this->pembilang($TJUMLAH));
        $PHPJasperXML->arrayParameter = array("TERBILANG" => (string) $terbilangx);

		$queryakum = DB::SELECT("SET @akum:=0;");
		$query = DB::SELECT(

			"SELECT bl.NO_BUKTI AS NO_BUKTI, 
                    RIGHT(bl.NO_BUKTI, 4) AS NO_BUKTI_BELAKANG, 
					if ( bl.GOL='BKM' , 'KAS MASUK', 'KAS KELUAR' ) as judul,
                    DATE_FORMAT(bl.TGL,'%d/%m/%Y') AS TGL,
                    bl.KET AS KET, 
                    bl.JUMLAH AS TJUMLAH, 
                    bld.REC AS REC,
                    bld.ACNO AS ACNO,
                    bld.URAIAN AS URAIAN,
                    bld.JUMLAH AS JUMLAH
                FROM bl, bld 
                WHERE bl.NO_BUKTI='" . $no_bukti . "' 
                AND bl.NO_BUKTI = bld.NO_BUKTI
                ORDER BY bld.REC"
			);

        $data = [];
        foreach ($query as $key => $value) {
            array_push($data, array(

                'NO_BUKTI' => $query[$key]->NO_BUKTI,
                'NO_BUKTI_BELAKANG' => $query[$key]->NO_BUKTI_BELAKANG,
                'TGL' => $query[$key]->TGL,
                'KET' => $query[$key]->KET,
                'TJUMLAH' => $query[$key]->TJUMLAH,
                'REC' => $query[$key]->REC,
                'ACNO' => $query[$key]->ACNO,
                'URAIAN' => $query[$key]->URAIAN,
                'JUMLAH' => $query[$key]->JUMLAH,
                'JUDUL' => $query[$key]->judul,
                // 'JUMLAH_TERBILANG' => number_to_words($query[$key]->TJUMLAH),
				//'JUMLAH_TERBILANG' => ucwords($this->pembilang($query[$key]->TJUMLAH)),
            ));
        }
        $PHPJasperXML->setData($data);
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }

    public function cetak2(Bl $bl, Request $request)
    {
        $this->setFlag($request);
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;

        $no_bukti = $bl->NO_BUKTI;
        $TJUMLAH = $bl->JUMLAH;
		
        $file     = 'Transaksi_Bl_Bl';
        $PHPJasperXML = new PHPJasperXML();
        $PHPJasperXML->load_xml_file(base_path() . ('/app/reportc01/phpjasperxml/' . $file . '.jrxml'));

        $terbilangx = ucwords($this->pembilang($TJUMLAH));
        $PHPJasperXML->arrayParameter = array("TERBILANG" => (string) $terbilangx);

		$queryakum = DB::SELECT("SET @akum:=0;");
		$query = DB::SELECT(
			"SELECT bl.NO_BUKTI AS NO_BUKTI, 
                '' AS NO_BUKTI_BELAKANG, 
                DATE_FORMAT(bl.TGL,'%d/%m/%Y') AS TGL,
                bl.KET AS KET, 
                bl.JUMLAH AS TJUMLAH, 

                bld.REC AS REC,
                bld.ACNO AS ACNO,
                bld.URAIAN AS URAIAN,
                bld.JUMLAH AS JUMLAH
            FROM bl, bld 
            WHERE bl.NO_BUKTI='" . $no_bukti . "' 
            AND bl.NO_BUKTI = bld.NO_BUKTI
            ORDER BY bld.REC "
			);
			
        $data = [];
        foreach ($query as $key => $value) {
            array_push($data, array(

                'NO_BUKTI' => $query[$key]->NO_BUKTI,
                'NO_BUKTI_BELAKANG' => $query[$key]->NO_BUKTI_BELAKANG,
                'TGL' => $query[$key]->TGL,
                'KET' => $query[$key]->KET,
                'TJUMLAH' => $query[$key]->TJUMLAH,
                'REC' => $query[$key]->REC,
                'ACNO' => $query[$key]->ACNO,
                'URAIAN' => $query[$key]->URAIAN,
                'JUMLAH' => $query[$key]->JUMLAH,
                'JUDUL' => $judul,
                // 'JUMLAH_TERBILANG' => number_to_words($query[$key]->TJUMLAH),
				//'JUMLAH_TERBILANG' => ucwords($this->pembilang($query[$key]->TJUMLAH)),

                
            ));
        }
        $PHPJasperXML->setData($data);
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }
	
	
	
	 public function cek_bukti(Request $request)
    {
        $getItem = DB::SELECT('select count(*) as ADA from bl where NO_BUKTI ="' . $request->NO_BUKTI . '"');

        return $getItem;
    }
		
	
}
