<?php

namespace App\Http\Controllers\OTransaksi;

use App\Http\Controllers\Controller;
// ganti 1

use App\Models\OTransaksi\Po;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;


include_once base_path() . "/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";

use PHPJasperXML;

// ganti 2
class PoController extends Controller
{

    var $judul = '';
    var $FLAGZ = '';
    var $GOLZ = '';
	
    function setFlag(Request $request)
    {
        if ( $request->golz == 'A1' ) {
            $this->judul = "Purchase Order Barang";
        } 

        if ( $request->golz == 'A2' ) {
            $this->judul = "Purchase Order Non";
        } 
		
        //$this->FLAGZ = $request->flagz;
        $this->GOLZ = $request->golz;    
		

    }
	
    public function index(Request $request)
    {

        // ganti 3
        $this->setFlag($request);
        // ganti 3
        return view('otransaksi_po.index')->with(['judul' => $this->judul, 'golz' => $this->GOLZ , 'flagz' => $this->FLAGZ ]);
    }


	
	
    // ganti 4
    public function browse(Request $request)
    {
        $po = DB::table('po')->select('NO_PO', 'TGL', 'KODES', 'NAMAS', 'ALAMAT', 'KOTA', 'KD_BRG', 'NA_BRG', 'HARGA', 'KG', 'KIRIM', 'SISA', 'TOTAL')->where('SLS', 0)->where('GOL', $request->GOL )->orderBy('KODES', 'ASC');
		
        if (!empty($request->KODES)) {
			$po = $po->where( 'KODES', $request->KODES );
        }
		
        return response()->json($po->get());
    }


    public function browseuang(Request $request)
    {
		$po = DB::SELECT("SELECT NO_PO,TGL,  KODES, NAMAS, PERB AS TOTAL, PERBB AS BAYAR, (PERB-PERBB) AS SISA  from po
		WHERE ( PERB-PERBB ) <> 0 AND  GOL='$request->GOL' ORDER BY NO_PO; ");

        return response()->json($po);
    }

    public function browseisi(Request $request)
    {
		$po = DB::SELECT("SELECT NO_PO AS NO_PO, NO_BUKTI AS NO_FAKTUR, TGL AS TGL_FAKTUR, TOTAL, 0 AS BAYAR FROM beli WHERE NO_PO ='$request->NO_PO' UNION ALL
						  SELECT NO_PO AS NO_PO, NO_BUKTI AS NO_FAKTUR, TGL AS TGL_FAKTUR, TOTAL, BAYAR AS BAYAR FROM HUT  WHERE NO_PO ='$request->NO_PO' ORDER BY NO_FAKTUR  ");

        return response()->json($po);
    }

    public function getPo(Request $request)
    {
        // ganti 5

        if ($request->session()->has('periode')) {
            $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        } else {
            $periode = '';
        }

        $this->setFlag($request);
		
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
				
        $po = DB::SELECT("SELECT * from po  where  PER ='$periode' and GOL ='$this->GOLZ'   ORDER BY NO_PO ");
	

        // ganti 6

        return Datatables::of($po)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant" || Auth::user()->divisi=="pembelian") 
                {
					
                    $btnEdit =   ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_PO . ' sudah diposting!\')" href="#" ' : ' href="po/edit/?idx
					=' . $row->NO_ID . '&tipx=edit&golz=' . $row->GOL . '&judul=' . $this->judul . '"';
					
                    $btnDelete = ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_PO . ' sudah diposting!\')" href="#" ' : ' onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)" href="po/delete/' . $row->NO_ID . '/?golz=' . $row->GOL . '" ';

                    $btnPrivilege =
                        '
                                <a class="dropdown-item" ' . $btnEdit . '>
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a class="dropdown-item btn btn-danger" href="jspoc/' . $row->NO_ID . '">
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

                'TGL'      => 'required',
                'KODES'       => 'required',
                'KD_BRG'       => 'required'
            ]
        );

		$this->setFlag($request);
		
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		
        // Generate Nomor Bukti
        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

        $bulan    = session()->get('periode')['bulan'];
        $tahun    = substr(session()->get('periode')['tahun'], -2);

		
		if ( $GOLZ == 'A1' ) {
	
			$query = DB::table('po')->select('NO_PO')->where('PER', $periode)->where('GOL', $GOLZ)->orderByDesc('NO_PO')->limit(1)->get();
			
			if ($query != '[]') {
				$query = substr($query[0]->NO_PO, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'PY' . $tahun . $bulan . '-' . $query;
			} else {
				$no_bukti= 'PY' . $tahun . $bulan . '-0001';
			}

		} else if ( $GOLZ == 'A2' ) {

			$query = DB::table('po')->select('NO_PO')->where('PER', $periode)->where('GOL', $GOLZ)->orderByDesc('NO_PO')->limit(1)->get();

			if ($query != '[]') {
				$query = substr($query[0]->NO_PO, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'PA2' . $tahun . $bulan . '-' . $query;
			} else {
				$no_bukti= 'PA2' . $tahun . $bulan . '-0001';
			}
			//dd($no_bukti);       

		}
		
        // Insert Header

        // ganti 10

        $po = Po::create(
            [
                'NO_PO'         	=> $no_bukti,
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'JTEMPO'              => date('Y-m-d', strtotime($request['JTEMPO'])),
                'PER'              => $periode,
                'KODES'            => ($request['KODES'] == null) ? "" : $request['KODES'],
                'NAMAS'            => ($request['NAMAS'] == null) ? "" : $request['NAMAS'],
                'ALAMAT'           => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'             => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'GOL'               => $GOLZ,
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'KG'               => (float) str_replace(',', '', $request['KG']),
                'SISA'             => (float) str_replace(',', '', $request['SISA']),
                'HARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
				
                'RPHARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'RPTOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
				
				'KODEC'            => ($request['KODEC'] == null) ? "" : $request['KODES'],
                'NAMAC'            => ($request['NAMAC'] == null) ? "" : $request['NAMAS'],
                'ALAMAT2'           => ($request['ALAMAT2'] == null) ? "" : $request['ALAMAT2'],
                'KOTA2'             => ($request['KOTA2'] == null) ? "" : $request['KOTA2'],
                'NO_SO'           => ($request['NO_SO'] == null) ? "" : $request['NO_SO'],
                'TGL_SO'           => date('Y-m-d', strtotime($request['TGL_SO'])),
                'KD_BRG2'           => ($request['KD_BRG2'] == null) ? "" : $request['KD_BRG2'],
                'NA_BRG2'           => ($request['NA_BRG2'] == null) ? "" : $request['NA_BRG2'],
				
                'USRNM'            => Auth::user()->username,
                'created_by'       => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );

        //  ganti 11

	    $no_buktix = $no_bukti;
		
		$po = Po::where('NO_PO', $no_buktix )->first();
					 
		// return redirect('/po?golz='.$GOLZ)->with(['judul' => $judul, 'golz' => $GOLZ ]);
        return redirect('/po/edit/?idx=' . $po->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&judul=' . $this->judul . '');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */


      public function edit( Request $request , Po $po)
    {


		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
		
				
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect('/po')
			       ->with('status', 'Maaf Periode sudah ditutup!')
                   ->with(['judul' => $judul, 'golz' => $GOLZ ]);
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
		   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_PO from po
		                 where PER ='$per' and  
						 and GOL ='$this->GOLZ' and NO_PO = '$buktix'						 
		                 ORDER BY NO_PO ASC  LIMIT 1" );
						 
			
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
			

		   $bingco = DB::SELECT("SELECT NO_ID, NO_PO from po
		                 where PER ='$per' and GOL ='$this->GOLZ' 
		                 ORDER BY NO_PO ASC  LIMIT 1" );
						 
		
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
			
		   $bingco = DB::SELECT("SELECT NO_ID, NO_PO from po     
		             where PER ='$per' and GOL ='$this->GOLZ' 
					 and NO_PO < '$buktix' ORDER BY NO_PO DESC LIMIT 1" );
			

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
	   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_PO from po    
		             where PER ='$per' and GOL ='$this->GOLZ' 
					 and NO_PO > '$buktix' ORDER BY NO_PO ASC LIMIT 1" );
					 
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
		  
    		$bingco = DB::SELECT("SELECT NO_ID, NO_PO from po
						where PER ='$per' and GOL ='$this->GOLZ'     
		              ORDER BY NO_PO DESC  LIMIT 1" );
					 
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
			$po = Po::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
				$po = new Po;
                $po->TGL = Carbon::now();
      
				
		 }

        $no_bukti = $po->NO_PO;
				
		$data = [
            'header'        => $po,

        ];
 
         
        return view('otransaksi_po.edit', $data)
        ->with(['tipx' => $tipx, 'idx' => $idx, 'golz' =>$this->GOLZ, 'judul', $this->judul ]);
			 
    
      
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 18

    public function update(Request $request, Po $po)
    {

        $this->validate(
            $request,
            [

                // ganti 19

                'TGL'              => 'required',
                'KODES'           => 'required',
                'KD_BRG'           => 'required'
            ]
        );


        // ganti 20
		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;

		
        $po->update(
            [
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'JTEMPO'           => date('Y-m-d', strtotime($request['JTEMPO'])),
                'KODES'            => ($request['KODES'] == null) ? "" : $request['KODES'],
                'NAMAS'               => ($request['NAMAS'] == null) ? "" : $request['NAMAS'],
                'ALAMAT'            => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'               => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KG'               => (float) str_replace(',', '', $request['KG']),
                'SISA'             => (float) str_replace(',', '', $request['SISA']),
                'HARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
				
                'RPHARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'RPTOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
				
				'KODEC'            => ($request['KODEC'] == null) ? "" : $request['KODEC'],
                'NAMAC'            => ($request['NAMAC'] == null) ? "" : $request['NAMAC'],
                'ALAMAT2'            => ($request['ALAMAT2'] == null) ? "" : $request['ALAMAT2'],
                'KOTA2'               => ($request['KOTA2'] == null) ? "" : $request['KOTA2'],
                'NO_SO'           => ($request['NO_SO'] == null) ? "" : $request['NO_SO'],
                'TGL_SO'           => ($request['TGL_SO'] == null) ? "" : $request['TGL_SO'],
                'KD_BRG2'           => ($request['KD_BRG2'] == null) ? "" : $request['KD_BRG2'],
                'NA_BRG2'           => ($request['NA_BRG2'] == null) ? "" : $request['NA_BRG2'],
				
                'USRNM'            => Auth::user()->username,
                'updated_by'       => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );




        //  ganti 21
		
		$no_buktix = $po->NO_PO;
		
		
		$po = Po::where('NO_PO', $no_buktix )->first();
					 
		// return redirect('/po?golz='.$GOLZ)
		//        ->with(['judul' => $judul, 'golz' => $GOLZ ]);

        return redirect('/po/edit/?idx=' . $po->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&judul=' . $this->judul . '');
		
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 22

    public function destroy( Request $request, Po $po)
    {

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		
		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect()->route('po')
                ->with('status', 'Maaf Periode sudah ditutup!')
                ->with(['judul' => $judul, 'golz' => $GOLZ ]);        
		}
		
        // ganti 23
        $deletePo = Po::find($po->NO_ID);

        // ganti 24

        $deletePo->delete();

        // ganti 


		return redirect('/po?golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ])
			   ->with('statusHapus', 'Data '.$po->NO_PO.' berhasil dihapus');

    }



    /////////////////////////////////////////////////////////////////

    public function jspoc(Po $po)
    {
        $no_po = $po->NO_PO;

        $file     = 'poc';
        $PHPJasperXML = new PHPJasperXML();
        $PHPJasperXML->load_xml_file(base_path() . ('/app/reportc01/phpjasperxml/' . $file . '.jrxml'));

        $query = DB::SELECT("
			SELECT NO_PO,  TGL, KODES, NAMAS, KD_BRG, NA_BRG, KG, HARGA, TOTAL, NOTES
			FROM po 
			WHERE po.NO_PO='$no_po' 
			ORDER BY NO_PO;
		");

        $xno_po1   = $query[0]->NO_PO;
        $xtgl1     = $query[0]->TGL;
        $xkodes1   = $query[0]->KODES;
        $xnamas1   = $query[0]->NAMAS;
        $xnotes1   = $query[0]->NOTES;
        $xkd_brg1  = $query[0]->KD_BRG;
        $xna_brg1  = $query[0]->NA_BRG;
        $xkg1      = $query[0]->KG;
        $xharga1   = $query[0]->RPHARGA;
        $xtotal1   = $query[0]->RPTOTAL;
        
        $PHPJasperXML->arrayParameter = array("HARGA1" => (float) $xharga1, "TOTAL1" => (float) $xtotal1, "KG1" => (float) $xkg1, "HARGA1" => (float) $xharga1, "NO_PO1" => (string) $xno_po1, "TGL1" => (string) $xtgl1,  "KODES1" => (string) $xkodes1,  "NAMAS1" => (string) $xnamas1,  "KD_BRG1" => (string) $xkd_brg1,  "NA_BRG1" => (string) $xna_brg1,  "NOTES1" => (string) $xnotes1 );
        $PHPJasperXML->arraysqltable = array();


        $query2 = DB::SELECT("
			SELECT NO_PO, TGL, KODES, NAMAS, if(ALAMAT='','NOT-FOUND.png',ALAMAT) as ALAMAT, NO_PO,  IF ( FLAG='BL' , 'A','B' ) AS FLAG, AJU, BL, EMKL, KD_BRG, NA_BRG, KG, RPHARGA AS HARGA, RPTOTAL AS TOTAL, 0 AS BAYAR,  NOTES
			FROM beli 
			WHERE beli.NO_PO='$no_po'  UNION ALL 
			SELECT NO_PO, TGL, KODES, NAMAS, if(ALAMAT='','NOT-FOUND.png',ALAMAT) as ALAMAT,  NO_PO,  'C' AS FLAG, '' AS AJU, '' AS BL, '' AS EMKL,  '' AS KD_BRG, '' AS NA_BRG, 0 AS KG, 
			0 AS HARGA, 0 AS TOTAL, BAYAR, NOTES
			FROM hut 
			WHERE hut.NO_PO='$no_po' 
			ORDER BY TGL, FLAG, NO_PO;
		");

        $data = [];

        foreach ($query2 as $key => $value) {
            array_push($data, array(
                'NO_BUKTI' => $query2[$key]->NO_PO,
                'TGL'      => $query2[$key]->TGL,
                'KODES'    => $query2[$key]->KODES,
                'NAMAS'    => $query2[$key]->NAMAS,
                'ALAMAT'    => $query2[$key]->ALAMAT,
                'AJU'    => $query2[$key]->AJU,
                'BL'       => $query2[$key]->BL,
                'EMKL'    => $query2[$key]->EMKL,
                'KG'       => $query2[$key]->KG,
                'HARGA'    => $query2[$key]->HARGA,
                'TOTAL'    => $query2[$key]->TOTAL,
                'BAYAR'    => $query2[$key]->BAYAR,
                'NOTES'    => $query2[$key]->NOTES
            ));
        }
		
        $PHPJasperXML->setData($data);
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }




    //////////////////////////////////////////////


}
