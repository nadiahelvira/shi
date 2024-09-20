<?php

namespace App\Http\Controllers\OTransaksi;

use App\Http\Controllers\Controller;
use App\Http\Traits\Terbilang;

use App\Models\OTransaksi\Kartupo;
use App\Models\OTransaksi\Kartupod;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;

include_once base_path() . "/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";

use PHPJasperXML;


class KartupoController extends Controller
{
	use Terbilang;
	
    public function index()
    {
        return view('otransaksi_kartupo.index');
    }

    public function index_posting()
    {   
        return view('otransaksi_kartupo.post');
    }

    public function getKartupo(Request $request)
    {
        if ($request->session()->has('periode')) {
            $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        } else {
            $periode = '';
        }

        session()->put('filter_gol', $request->gol);
        session()->put('filter_kodes1', $request->kodes);
        session()->put('filter_namas1', $request->NAMAS);
        session()->put('filter_tglDari', $request->tglDr);
        session()->put('filter_tglSampai', $request->tglSmp);

        if (!empty($request->gol))
		{
			$filter_gol = " and GOL ='".$request->gol."' ";
		}
        
        if (!empty($request->kodes))
		{
			$filter_kodes = " and KODES ='".$request->kodes."' ";
		}

        $kartupo = DB::SELECT("SELECT NO_ID, NO_PO AS NO_BUKTI,  TGL, NAMAS, 
                                        NA_BRG, KG, KIRIM, SISA, HARGA, TOTAL, NOTES  
								from po 
                                WHERE YEAR (TGL) >= 2023 
                                -- AND GOL='".$request->gol."'
                                ");
       
        return Datatables::of($kartupo)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant" || Auth::user()->divisi=="penjualan") 
                {
                    $btnEdit = ' href="kartupo/edit/' . $row->NO_ID . '" ';
                    $btnDelete = ' href="kartupo/delete/' . $row->NO_ID . '" ';

                    $btnPrivilege ='
                                <a class="dropdown-item" ' . $btnEdit . '>
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a class="dropdown-item btn btn-danger" href="kartupo/print/' . $row->NO_ID . '">
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
                            <a class="dropdown-item" href="kartupo/show/' . $row->NO_ID . '">
                            <i class="fas fa-eye"></i>
                                Lihat
                            </a>

                            ' . $btnPrivilege . '
                        </div>
                    </div>
                    ';

                return $actionBtn;
            })
            ->addColumn('cek', function ($row) {
                return
                    '
                    <input type="checkbox" name="cek[]" class="form-control cek" ' . '></input>
                    <input type="hidden" name="NO_PO[]" class="form-control NO_PO" value='.$row->NO_BUKTI.'></input>
                    ';
            })
            ->addColumn('detailsx', function ($row) {
                return
                    '
                    <a type="button" class="btn btn-info btn-sm view-details" onclick="cekdetailsx(' . $row->NO_ID . ')" >View Details</a>
                    ';
            })

            ->rawColumns(['action', 'cek', 'detailsx', 'qtyt'])
            ->make(true);
    }

    public function posting(Request $request)
    {   
		
        // if ($request->session()->has('posttimer'))
        // {
        //     if ( (time() - $request->session()->get('posttimer')) <= 5)
        //     {
        //         session()->put('posttimer', time());
        //         return redirect('/kartupo/index-posting')->with('gagal', 'Terlalu cepat. Ulangi 5 detik lagi..');
        //     }
        // }

        // session()->put('posttimer', time());
        $CEK = $request->input('cek');
        $no_po = $request->input('NO_PO');
 
 
        $hasil = "";

        if ($CEK) {
            foreach ($CEK as $key => $value) {

                $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
                $bulan = substr($periode,0,2);
                $tahun = substr($periode,3,4);
				
                $file     = 'poc';
                $PHPJasperXML = new PHPJasperXML();
                $PHPJasperXML->load_xml_file(base_path() . ('/app/reportc01/phpjasperxml/' . $file . '.jrxml'));
        

                $query = DB::SELECT("
                        SELECT NO_PO,  TGL, KODES, NAMAS, KD_BRG, NA_BRG, KG, HARGA, TOTAL, NOTES, RPTOTAL
                        FROM po 
                        WHERE po.NO_PO='".$no_po[$key]."' 
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
                    $xharga1   = $query[0]->HARGA;
                    $xtotal1   = $query[0]->RPTOTAL;
                    
                    $PHPJasperXML->arrayParameter = array(
                        "HARGA1" => (float) $xharga1, 
                        "TOTAL1" => (float) $xtotal1, 
                        "KG1" => (float) $xkg1, 
                        "HARGA1" => (float) $xharga1, 
                        "NO_PO1" => (string) $xno_po1, 
                        "TGL1" => (string) $xtgl1,  
                        "KODES1" => (string) $xkodes1,  
                        "NAMAS1" => (string) $xnamas1,  
                        "KD_BRG1" => (string) $xkd_brg1,  
                        "NA_BRG1" => (string) $xna_brg1,  
                        "NOTES1" => (string) $xnotes1
                    );
                    $PHPJasperXML->arraysqltable = array();
            
            
                    $query2 = DB::SELECT("
                        SELECT NO_BUKTI, NO_PO, TGL, KODES, NAMAS, if(ALAMAT='','NOT-FOUND.png',ALAMAT) as ALAMAT, IF ( FLAG='BL' , 'A','B' ) AS FLAG, AJU, BL, EMKL, TRUCK, KD_BRG, NA_BRG, KG, RPHARGA AS HARGA, RPTOTAL AS TOTAL, 0 AS BAYAR,  NOTES
                        FROM beli 
                        WHERE beli.NO_PO='".$no_po[$key]."'  UNION ALL 
                        SELECT NO_BUKTI, NO_PO, TGL, KODES, NAMAS, if(ALAMAT='','NOT-FOUND.png',ALAMAT) as ALAMAT, 'C' AS FLAG, '' AS AJU, '' AS BL, '' AS EMKL, '' AS TRUCK, '' AS KD_BRG, '' AS NA_BRG, 0 AS KG, 
                        0 AS HARGA, 0 AS TOTAL, BAYAR, NOTES
                        FROM hut 
                        WHERE hut.NO_PO='".$no_po[$key]."' 
                        ORDER BY TGL, NO_BUKTI;
                    ");
            
                    $data = [];
            
                    foreach ($query2 as $key => $value) {
                        array_push($data, array(
                            'NO_BUKTI' => $query2[$key]->NO_BUKTI,
                            'TGL'      => $query2[$key]->TGL,
                            'KODES'    => $query2[$key]->KODES,
                            'NAMAS'    => $query2[$key]->NAMAS,
                            'ALAMAT'    => $query2[$key]->ALAMAT,
                            'AJU'    => $query2[$key]->AJU,
                            'BL'       => $query2[$key]->BL,
                            'EMKL'    => $query2[$key]->EMKL,
                            'TRUCK'    => $query2[$key]->TRUCK,
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
			   

			  
			   
			   
			   
			   
			   
			   
			   
			 
        }
        else
        {
            $hasil = $hasil ."Tidak ada Slip Bank yang dipilih! ; ";
        }

        if($hasil!='')
        {
            return redirect('/kartupo/index-posting')->with('status', 'Proses Request..')->with('gagal', $hasil);
        }
        else
        {
            return redirect('/kartupo/index-posting')->with('status', 'selesai..');
        }

    }

    public function create()
    {
        return view('otransaksi_surats.create');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'NO_BUKTI'       => 'required',
                'TGL'      => 'required',
                'KODEC'       => 'required',
                'KD_BRG'      => 'required',
            ]
        );

        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

        $bulan    = session()->get('periode')['bulan'];
        $tahun    = substr(session()->get('periode')['tahun'], -2);
        $query = DB::table('surats')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)->where('FLAG', 'SJ')->where('GOL', 'Y')->orderByDesc('NO_BUKTI')->limit(1)->get();

        if ($query != '[]') {
            $query = substr($query[0]->NO_BUKTI, -4);
            $query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
            $no_bukti = 'SJY' . $tahun . $bulan . '-' . $query;
        } else {
            $no_bukti = 'SJY' . $tahun . $bulan . '-0001';
        }

        // Insert Header
        $surats = Surats::create(
            [
                'NO_BUKTI'         => $no_bukti,
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'PER'              => $periode,
                'NO_SO'            => ($request['NO_SO'] == null) ? "" : $request['NO_SO'],
                'KODEC'            => ($request['KODEC'] == null) ? "" : $request['KODEC'],
                'NAMAC'            => ($request['NAMAC'] == null) ? "" : $request['NAMAC'],
                'ALAMAT'           => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'             => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'FLAG'             => 'SJ',
                'GOL'               => 'Y',
                'ACNOA'            => '113101',
                'NACNOA'           => 'PIUTANG USAHA ',
                'ACNOB'            => '711102',
                'NACNOB'           => 'PEND. / BIAYA LAIN2',
                'TRUCK'            => ($request['TRUCK'] == null) ? "" : $request['TRUCK'],
                'SOPIR'            => ($request['SOPIR'] == null) ? "" : $request['SOPIR'],
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'KODET'            => ($request['KODET'] == null) ? "" : $request['KODET'],
                'NAMAT'            => ($request['NAMAT'] == null) ? "" : $request['NAMAT'],
                'KG1'               => (float) str_replace(',', '', $request['KG']),
                'KG'                => (float) str_replace(',', '', $request['KG']),
                'SISA'                => (float) str_replace(',', '', $request['SISA']),
                'HARGA'             => (float) str_replace(',', '', $request['HARGA']),
                'TOTAL'             => (float) str_replace(',', '', $request['TOTAL']),
                'SISA'              => (float) str_replace(',', '', $request['TOTAL']),
                'USRNM'            => Auth::user()->username,
                'TG_SMP'           => Carbon::now()

            ]
        );
        
        DB::SELECT("CALL suratsins('". $no_bukti ."') ");

        return redirect('/surats')->with('status', 'Data baru '.$no_bukti.' berhasil ditambahkan');
    }

    public function show(Surats $surats)
    {
        $no_bukti = $surats->NO_BUKTI;
        $data = [
            'header'        => $surats
        ];
        return view('otransaksi_surats.show', $data);
    }

    public function edit(Surats $surats)
    {

        $no_bukti = $surats->NO_BUKTI;
        $data = [
            'header'        => $surats
        ];

        return view('otransaksi_surats.edit', $data);
    }

    public function update(Request $request, Surats $surats)
    {
        $this->validate(
            $request,
            [
                'TGL'      => 'required',
                //'KODEC'       => 'required',
                'KD_BRG'      => 'required',
            ]
        );

        DB::SELECT("CALL suratsdel('". $request['NO_BUKTI'] ."') ");

        $surats->update(
            [
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                'NO_SO'            => ($request['NO_SO'] == null) ? "" : $request['NO_SO'],
                'KODEC'            => ($request['KODEC'] == null) ? "" : $request['KODEC'],
                'NAMAC'            => ($request['NAMAC'] == null) ? "" : $request['NAMAC'],
                'ALAMAT'           => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'             => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'KODET'            => ($request['KODET'] == null) ? "" : $request['KODET'],
                'NAMAT'            => ($request['NAMAT'] == null) ? "" : $request['NAMAT'],
                'TRUCK'            => ($request['TRUCK'] == null) ? "" : $request['TRUCK'],
                'SOPIR'            => ($request['SOPIR'] == null) ? "" : $request['SOPIR'],
                'KG1'              => (float) str_replace(',', '', $request['KG']),
                'KG'               => (float) str_replace(',', '', $request['KG']),
                'SISA'               => (float) str_replace(',', '', $request['SISA']),
                'HARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'SISA'             => (float) str_replace(',', '', $request['TOTAL']),
                'USRNM'            => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );

        DB::SELECT("CALL suratsins('". $request['NO_BUKTI'] ."') ");

        return redirect('/surats')->with('status', 'Data '.$surats->NO_BUKTI.' berhasil diedit');
    }

    public function destroy(Surats $surats)
    {
        DB::SELECT("CALL suratsdel('". $surats->NO_BUKTI ."') ");
        Surats::find($surats->NO_ID)->delete();

        return redirect('/surats')->with('status', 'Data '.$surats->NO_BUKTI.' berhasil dihapus');
    }


    public function cetak(Kartupo $kartupo, Request $request)
    {

		$jenisx = $kartupo->JENIS;
		$no_bukti = $kartupo->NO_CHBG;
		$file ='';
		 
		if ( $jenisx == 'DANAMON' ) {
            $file     = 'Transaksi_CekBG_Cek_Danamon';
        } else if ( $jenisx == 'BRI' ) {
            $file     = 'Transaksi_CekBG_Cek_Bri';
        } else if ( $jenisx == 'BCA-SETORAN' ) {
            $file     = 'Transaksi_CekBG_Cek_Bca';
        } else if ( $jenisx == 'BCA-SETORAN-KLIRING' ) {
            $file     = 'Transaksi_CekBG_Slip_Storan';
        } else if ( $jenisx == 'DANAMON2' ) {
            $file     = 'Transaksi_CekBG_Cek_Danamon';
        }else if ( $jenisx == 'UOB' ) {
            $file     = 'Transaksi_CekBG_Cek_UOB';
        }else if ( $jenisx == 'DANAMON-1B' ) {
            $file     = 'Transaksi_CekBG_Cek_Bca';
        }else if ( $jenisx == 'BRI-NEW' ) {
            $file     = 'Transaksi_CekBG_Cek_Bri';
        }


        $PHPJasperXML = new PHPJasperXML();
        $PHPJasperXML->load_xml_file(base_path() . ('/app/reportc01/phpjasperxml/' . $file . '.jrxml'));

		$terbilangx = ucwords($this->pembilang($kartupo->TOTAL));
		$PHPJasperXML->arrayParameter = array("TERBILANG" => (string) $terbilangx);
				
		$query = DB::SELECT(

		"SELECT *
			FROM pms_piu
			WHERE NO_CHBG='" . $no_bukti . "' "
		);

        $data = [];
        foreach ($query as $key => $value) {
            array_push($data, array(

                'JTEMPO' => $query[$key]->JTEMPO,
                'TGL_SETOR' => $query[$key]->TGL,
                'NO_GIRO' => $query[$key]->NO_CHBG,
                'NILAI' => $query[$key]->TOTAL,
                'BIAYA' => $query[$key]->BIAYA,
                'BANK' => $query[$key]->BANK,
                'KOTA_SETOR' => $query[$key]->KOTA_SETOR,
                'BANK_SETOR' => $query[$key]->BANK_SETOR,
                'JUMLAH_TERBILANG' => $terbilangx,
				//'JUMLAH_TERBILANG' => ucwords($this->pembilang($query[$key]->TJUMLAH)),
            ));
		}     
        $PHPJasperXML->setData($data);
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }
}
