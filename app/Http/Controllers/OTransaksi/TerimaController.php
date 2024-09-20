<?php

namespace App\Http\Controllers\OTransaksi;

use App\Http\Controllers\Controller;

use App\Models\OTransaksi\Terima;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;

class TerimaController extends Controller
{


    var $judul = '';
    var $FLAGZ = '';
    var $GOLZ = '';
	
    function setFlag(Request $request)
    {
        if ( $request->flagz == 'TR' && $request->golz == 'Y' ) {
            $this->judul = "Terima Barang";
        }
		
		
        $this->FLAGZ = $request->flagz;
        $this->GOLZ = $request->golz;    
		

    }	 


    public function index(Request $request)
    {

        // ganti 3
        $this->setFlag($request);
        // ganti 3
        return view('otransaksi_terima.index')->with(['judul' => $this->judul, 'golz' => $this->GOLZ , 'flagz' => $this->FLAGZ ]);
    }


    public function getTerima(Request $request)
    {
        if ($request->session()->has('periode')) {
            $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        } else {
            $periode = '';
        }

        $this->setFlag($request);
		
        $terima = DB::SELECT("SELECT * from terima  where  PER ='$periode' and FLAG ='$this->FLAGZ' AND GOL ='$this->GOLZ'  ORDER BY NO_BUKTI ");
	

        return Datatables::of($terima)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant" || Auth::user()->divisi=="penjualan") 
                {

                    $btnEdit =   ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' href="terima/edit/?idx
					=' . $row->NO_ID . '&tipx=edit&flagz=' . $row->FLAG . '&golz=' . $row->GOL . '&judul=' . $this->judul . '"';
					
                    $btnDelete = ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)" href="terima/delete/' . $row->NO_ID . '/?flagz=' . $row->FLAG . '&golz=' . $row->GOL . '" ';


                    $btnPrivilege =
                        '
                                <a class="dropdown-item" ' . $btnEdit . '>
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a class="dropdown-item btn btn-danger" href="terima/print/' . $row->NO_ID . '">
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
            ->addColumn('cek', function ($row) {
                return
                    '
                    <input type="checkbox" name="cek[]" class="form-control cek" ' . (($row->POSTED == 1) ? "checked" : "") . '  value="' . $row->NO_ID . '" ' . (($row->POSTED == 1) ? "disabled" : "") . '></input>
                    ';
            })			
            /* ->addColumn('sta', function ($row) {
                return
                    '
					<select class="form-control"  name="sta">
											<option value="1" selected>Ok</option>
											<option value="0">Cancel</option>
										  </select>
                    ';
            })	 */
            ->rawColumns(['action','cek'])
            ->make(true);
    }

   



    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'NO_BUKTI'       => 'required',
                'TGL'      => 'required',
                'KODES'       => 'required',
                'KD_BRG'      => 'required',
            ]
        );

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		
        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

        $bulan    = session()->get('periode')['bulan'];
        $tahun    = substr(session()->get('periode')['tahun'], -2);
        $query = DB::table('terima')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)->orderByDesc('NO_BUKTI')->limit(1)->get();

        if ($query != '[]') {
            $query = substr($query[0]->NO_BUKTI, -4);
            $query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
            $no_bukti = 'TY' . $tahun . $bulan . '-' . $query;
        } else {
            $no_bukti = 'TY' . $tahun . $bulan . '-0001';
        }

        // Insert Header
        $terima = Terima::create(
            [
                'NO_BUKTI'         => $no_bukti,
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'PER'              => $periode,
                'NO_PO'            => ($request['NO_PO'] == null) ? "" : $request['NO_PO'],
                'NO_BL'            => ($request['NO_BL'] == null) ? "" : $request['NO_BL'],
                'KODES'            => ($request['KODES'] == null) ? "" : $request['KODES'],
                'NAMAS'            => ($request['NAMAS'] == null) ? "" : $request['NAMAS'],
                'ALAMAT'           => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'             => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'FLAG'             => 'TR',
                'GOL'              => 'Y',
                'TRUCK'            => ($request['TRUCK'] == null) ? "" : $request['TRUCK'],
                'SOPIR'            => ($request['SOPIR'] == null) ? "" : $request['SOPIR'],
                'AJU'              => ($request['AJU'] == null) ? "" : $request['AJU'],
                'BL'               => ($request['BL'] == null) ? "" : $request['BL'],
                'EMKL'             => ($request['EMKL'] == null) ? "" : $request['EMKL'],
                'GUDANG'           => ($request['GUDANG'] == null) ? "" : $request['GUDANG'],
                'NO_CONT'          => ($request['NO_CONT'] == null) ? "" : $request['NO_CONT'],
                'SEAL'             => ($request['SEAL'] == null) ? "" : $request['SEAL'],
                'T_CONT'           => ($request['T_CONT'] == null) ? "" : $request['T_CONT'],
                'T_TRUCK'          => ($request['T_TRUCK'] == null) ? "" : $request['T_TRUCK'],
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'KG1'              => (float) str_replace(',', '', $request['KG1']),
                'SUSUT'            => (float) str_replace(',', '', $request['SUSUT']),
                'KG'               => (float) str_replace(',', '', $request['KG']),
                'HARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'RPRATE'           => (float) str_replace(',', '', $request['RPRATE']),
                'RPTOTAL'          => (float) str_replace(',', '', $request['RPTOTAL']),
                'RPHARGA'          => (float) str_replace(',', '', $request['RPHARGA']),
				'ACNOA'            => '115101',
                'NACNOA'           => 'PERSEDIAAN ',
                'ACNOB'            => '115102',
                'NACNOB'           => 'PERSEDIAAN DALAM PERJALANAN',
                'USRNM'            => Auth::user()->username,
                'created_by'       => Auth::user()->username,
                'TG_SMP'           => Carbon::now()

            ]
        );
        
        $variablell = DB::SELECT("CALL terimains('". $no_bukti ."') ");


	    $no_buktix = $no_bukti;
		
		$terima = Terima::where('NO_BUKTI', $no_buktix )->first();
					 
        //return redirect('/terima/edit/?idx=' . $terima->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&flagz=' . $this->FLAGZ . '&judul=' . $this->judul . '');
		return redirect('/terima?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);

    }


	public function edit( Request $request , Terima $terima)
    {


		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
		
				
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect('/terima')
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
		   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from terima
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
			

		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from terima
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
			
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from terima      
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
	   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from terima   
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
		  
    		$bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from terima
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
			$terima = Terima::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
				$terima = new Terima;
                $terima->TGL = Carbon::now();
      
				
		 }

        $no_bukti = $terima->NO_BUKTI;
				
		$data = [
            'header'  => $terima,

        ];
 
         
         return view('otransaksi_terima.edit', $data)
		 ->with(['tipx' => $tipx, 'idx' => $idx, 'golz' =>$this->GOLZ, 'flagz' =>$this->FLAGZ, 'judul', $this->judul ]);
			 
    
      
    }




    public function update(Request $request, Terima $terima)
    {
        $this->validate(
            $request,
            [
                'TGL'      => 'required',
                //'KODEC'       => 'required',
                'KD_BRG'      => 'required',
            ]
			
        );

        // ganti 20

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		$variablell = DB::select('call terimadel(?)', array($terima['NO_BUKTI']));

				
        $terima->update(
            [
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'NO_PO'            => ($request['NO_PO'] == null) ? "" : $request['NO_PO'],
                'NO_BL'            => ($request['NO_BL'] == null) ? "" : $request['NO_BL'], 
				'KODES'            => ($request['KODES'] == null) ? "" : $request['KODES'],
                'NAMAS'            => ($request['NAMAS'] == null) ? "" : $request['NAMAS'],
                'ALAMAT'           => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'             => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'AJU'              => ($request['AJU'] == null) ? "" : $request['AJU'],
                'BL'               => ($request['BL'] == null) ? "" : $request['BL'],
				'EMKL'             => ($request['EMKL'] == null) ? "" : $request['EMKL'],
                'GUDANG'           => ($request['GUDANG'] == null) ? "" : $request['GUDANG'],
                'NO_CONT'          => ($request['NO_CONT'] == null) ? "" : $request['NO_CONT'],
                'SEAL'             => ($request['SEAL'] == null) ? "" : $request['SEAL'],
                'T_CONT'           => ($request['T_CONT'] == null) ? "" : $request['T_CONT'],
                'T_TRUCK'          => ($request['T_TRUCK'] == null) ? "" : $request['T_TRUCK'],				
                'TRUCK'            => ($request['TRUCK'] == null) ? "" : $request['TRUCK'],
                'SOPIR'            => ($request['SOPIR'] == null) ? "" : $request['SOPIR'],
                'KG1'              => (float) str_replace(',', '', $request['KG1']),
                'KG'               => (float) str_replace(',', '', $request['KG']),
                'SUSUT'            => (float) str_replace(',', '', $request['SUSUT']),
                'HARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'RPRATE'           => (float) str_replace(',', '', $request['RPRATE']),
                'RPHARGA'          => (float) str_replace(',', '', $request['RPHARGA']),
                'RPTOTAL'          => (float) str_replace(',', '', $request['RPTOTAL']),
				'ACNOA'            => '115101',
                'NACNOA'           => 'PERSEDIAAN ',
                'ACNOB'            => '115102',
                'NACNOB'           => 'PERSEDIAAN DALAM PERJALANAN',
                'USRNM'            => Auth::user()->username,
                'updated_by'       => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );

        //  ganti 21

        $variablell = DB::select('call terimains(?)', array($terima['NO_BUKTI']));

		$no_buktix = $terima->NO_BUKTI;
		
		
		$terima = Terima::where('NO_BUKTI', $no_buktix )->first();
					 
        //return redirect('/terima/edit/?idx=' . $terima->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&flagz=' . $this->FLAGZ . '&judul=' . $this->judul . '');
		return redirect('/terima?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);



    }

    
///
 
///
    public function destroy( Request $request, Terima $terima)
   {

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		
		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect()->route('terima')
                ->with('status', 'Maaf Periode sudah ditutup!')
                ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ]);
        }
				
        $variablell = DB::select('call terimadel(?)', array($terima['NO_BUKTI']));

        $deleteTerima = Terima::find($terima->NO_ID);
        $deleteTerima->delete();

		return redirect('/terima?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ])
			   ->with('statusHapus', 'Data '.$terima->NO_BUKTI.' berhasil dihapus');
			   
    }
	    
}
