<?php

namespace App\Http\Controllers\OReport;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Master\Brg;
// ganti 1

use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;

include_once base_path()."/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";
use PHPJasperXML;

use \koolreport\laravel\Friendship;
use \koolreport\bootstrap4\Theme;

// ganti 2
class RKarstkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function kartu()
    {
		session()->put('filter_brg1', '');
		session()->put('filter_nabrg1', '');
		session()->put('filter_brg2', '');
		session()->put('filter_nabrg2', '');
		session()->put('filter_tglDr', now()->format('d-m-Y'));
		session()->put('filter_tglSmp', now()->format('d-m-Y'));
		$brg = Brg::orderBy('KD_BRG', 'ASC')->get();
// GANTI 3 //
        return view('oreport_brg.kartu')->with(['brg' => $brg])->with(['hasil' => []]);
		
    }
	
	public function getStokKartu(Request $request)
    {
		$periode = $request->session()->get('periode')['bulan']. '/' . $request->session()->get('periode')['tahun'];
		$bulan = substr($periode,0,2);
		$tahun = substr($periode,3,4);
		$acno = '';
		$tgawal = $tahun.'-'.$bulan.'-01';
		
		if ($request->ajax())
		{
			// Ganti format tanggal input agar sama dengan database
			$tglDrD = date("Y-m-d", strtotime($request['tglDr']));
            $tglSmpD = date("Y-m-d", strtotime($request['tglSmp']));
			
			// Convert tanggal agar ambil start of day/end of day
			$tglDr = Carbon::parse($request->tglDr)->startOfDay();
            $tglSmp = Carbon::parse($request->tglSmp)->endOfDay();
			
			// Check Filter
			/*
			if (!empty($request->acno))
			{
				$query = $query->where('BACNO', $request->acno);
			}
			
			if (!empty($request->tglDr) && !empty($request->tglSmp))
			{
				$query = $query->whereBetween('TGL', [$tglDrD, $tglSmp]);
			}
			*/
			
			$periode = date("m/Y", strtotime($request['tglDr']));
			$bulan = date("m", strtotime($request['tglDr']));
			$tahun = date("Y", strtotime($request['tglDr']));
			$brg = $request->brg;
			$tgawal = $tahun.'-'.$bulan.'-01';
		}
		
		$queryakum = DB::SELECT("SET @akum:=0;");
		$query = DB::SELECT("
		SELECT *,@akum:=@akum+AWAL+MASUK-KELUAR+LAIN AS SALDO from
		(
			SELECT '' AS NO_BUKTI, '$tglDrD'  AS TGL, KD_BRG AS KD_BRG, NA_BRG AS NA_BRG, 
			'SALDO AWAL' URAIAN, 
			SUM(AWAL) AS AWAL, 0 AS MASUK, 0 AS KELUAR, 0 AS LAIN 
			from
			(
				SELECT KD_BRG, NA_BRG, AW$bulan AS AWAL 
				from brgd WHERE KD_BRG='$brg' and YER='$tahun'
				
				UNION ALL
				
				SELECT KD_BRG, NA_BRG, KG1 AS AWAL 
				from terima where terima.TGL<'$tglDrD' 
				and terima.KD_BRG='$brg' and terima.PER='$periode' and  terima.KG1 <> 0 and GOL ='Y' union all
				

				SELECT KD_BRG, NA_BRG, ( KG1 * -1 ) AS AWAL 
				from surats where surats.TGL<'$tglDrD' 
				and surats.KD_BRG='$brg' and surats.PER='$periode' and  surats.KG1 <> 0  union all
				
				SELECT KD_BRG, NA_BRG, ( KG1 * -1 ) AS AWAL 
				from jual where jual.TGL<'$tglDrD' 
				and jual.KD_BRG='$brg' and jual.PER='$periode' and  jual.KG1 <> 0  union all
				
				SELECT KD_BRG, NA_BRG, KG AS AWAL 
				from stock where stock.TGL<'$tglDrD' 
				and stock.KD_BRG='$brg' and stock.PER='$periode' 

				
			) as AWAL00
			UNION ALL

			SELECT NO_BUKTI, TGL, KD_BRG, NA_BRG,CONCAT('terima-',TRIM(NAMAS)) AS URAIAN, 0 as AWAL, KG1 AS MASUK, 0 AS KELUAR, 0 AS LAIN 
			from terima where terima.TGL BETWEEN '$tglDrD' and '$tglSmpD' and terima.KD_BRG='$brg' and terima.PER='$periode' and ( terima.KG1 <> 0 ) and terima.GOL =-'Y' union all

			SELECT NO_BUKTI, TGL, KD_BRG, NA_BRG, CONCAT('SURATS-',TRIM(NAMAT)) AS URAIAN, 0 AS AWAL, 0 AS MASUK, 0 AS KELUAR, KG1 AS LAIN 
			from surats where surats.TGL BETWEEN '$tglDrD' and '$tglSmpD' and surats.KD_BRG='$brg' and surats.PER='$periode' and ( surats.KG1 <> 0 ) union all

			SELECT NO_BUKTI, TGL, KD_BRG, NA_BRG, CONCAT('JUAL-',TRIM(NAMAT)) AS URAIAN, 0 AS AWAL, 0 AS MASUK, KG1 AS KELUAR, 0 AS LAIN 
			from jual where jual.TGL BETWEEN '$tglDrD' and '$tglSmpD' and jual.KD_BRG='$brg' and jual.PER='$periode' and ( jual.KG1 <> 0 ) union all

			SELECT NO_BUKTI, TGL, KD_BRG, NA_BRG, CONCAT('KOREKSI-') AS URAIAN,           0 AS AWAL, 0 AS MASUK, 0 AS KELUAR, KG AS LAIN 
			from stock where stock.TGL BETWEEN '$tglDrD' and '$tglSmpD' and stock.KD_BRG='$brg' and stock.PER='$periode'   order by TGL, NO_BUKTI ASC


		) as kartustok  ;"
		);
		
		return Datatables::of($query)->addIndexColumn()->make(true);
		
    }	 
	 


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function jasperStokKartu(Request $request) 
	{
		$file 	= 'karstk';
		$PHPJasperXML = new PHPJasperXML();
		$PHPJasperXML->load_xml_file(base_path().('/app/reportc01/phpjasperxml/'.$file.'.jrxml'));
		
            // Ganti format tanggal input agar sama dengan database
            $tglDrD = date("Y-m-d", strtotime($request['tglDr']));
            $tglSmpD = date("Y-m-d", strtotime($request['tglSmp']));
            
            // Convert tanggal agar ambil start of day/end of day
            $tglDr = Carbon::parse($request->tglDr)->startOfDay();
            $tglSmp = Carbon::parse($request->tglSmp)->endOfDay();
            
            $periode = date("m/Y", strtotime($request['tglDr']));
            $bulan = date("m", strtotime($request['tglDr']));
            $tahun = date("Y", strtotime($request['tglDr']));
			$filterbrg = " AND KD_BRG<>'' " ;
			$filterterima = " AND terima.KD_BRG<>'' " ;
			$filterjual = " AND jual.KD_BRG<>'' " ;
			$filterstock = " AND stock.KD_BRG<>'' " ;
			if($request->brg1)
			{
				$filterbrg = " AND KD_BRG between '".$request->brg1."' and '".$request->brg2."' " ;
				$filterterima = " AND terima.KD_BRG between'".$request->brg1."' and '".$request->brg2."' " ;
				$filterjual = " AND jual.KD_BRG between '".$request->brg1."' and '".$request->brg2."' " ;
				$filterstock = " AND stock.KD_BRG between '".$request->brg1."' and '".$request->brg2."' " ;
			}
            $tgawal = $tahun.'-'.$bulan.'-01';
		
			session()->put('filter_brg1', $request->brg1);
			session()->put('filter_nabrg1', $request->nabrg1);
			session()->put('filter_brg2', $request->brg2);
			session()->put('filter_nabrg2', $request->nabrg2);
			session()->put('filter_tglDr', $request->tglDr);
			session()->put('filter_tglSmp', $request->tglSmp);

		$queryakum = DB::SELECT("SET @akum:=0;");
		$query = DB::SELECT("
        	SELECT *, if(@kdbrg<>KD_BRG,@akum:=AWAL+MASUK-KELUAR+LAIN,@akum:=@akum+AWAL+MASUK-KELUAR+LAIN) as SALDO,@kdbrg:=KD_BRG as ganti, URUTAN from
		(
			SELECT ' ' AS NO_BUKTI, '$tglDrD'  AS TGL, KD_BRG AS KD_BRG, NA_BRG AS NA_BRG, 
			'SALDO AWAL' URAIAN, 
			SUM(AWAL) AS AWAL, 0 MASUK, 0 KELUAR, 0 AS LAIN, 1 as URUTAN
			from
			(
				SELECT KD_BRG AS KD_BRG, NA_BRG AS NA_BRG, AW$bulan AS AWAL 
				from brgd WHERE YER='$tahun' $filterbrg
				
				UNION ALL
				
				SELECT KD_BRG, NA_BRG, KG AS AWAL 
				from terima where terima.TGL<'$tglDrD' 
				$filterterima and terima.PER='$periode' and terima.GOL ='Y' and terima.KG1 <> 0 union all
				
				
				SELECT KD_BRG, NA_BRG , ( KG * -1 ) AS AWAL 
				from jual where jual.TGL<'$tglDrD' 
				$filterjual and jual.PER='$periode' and ( jual.FLAG ='JL' or jual.FLAG ='AJ' ) union all
				
				SELECT KD_BRG, stock.NA_BRG, KG AS AWAL 
				from stock where stock.TGL<'$tglDrD' 
				$filterstock and stock.PER='$periode' 

				
			) as AWAL00
			group by KD_BRG
			UNION ALL

			SELECT NO_BUKTI, TGL, KD_BRG, NA_BRG, CONCAT('terima-',TRIM(NAMAS)) AS URAIAN, 0 AWAL, KG AS MASUK, 0 AS KELUAR, 0 AS LAIN,  2 as URUTAN 
			from terima where terima.TGL BETWEEN '$tglDrD' and '$tglSmpD' $filterterima and terima.GOL ='Y' and terima.KG1 <> 0 and terima.PER='$periode' union all


			SELECT NO_BUKTI, TGL, KD_BRG, NA_BRG, CONCAT('JUAL-',TRIM(NAMAC)) AS URAIAN, 0 AWAL, 0 AS MASUK, KG AS KELUAR,  0 AS LAIN, 4 as URUTAN  
			from jual where jual.TGL BETWEEN '$tglDrD' and '$tglSmpD' $filterjual and jual.PER='$periode' union all

			SELECT NO_BUKTI, TGL, KD_BRG, NA_BRG, CONCAT('KOREKSI-') AS URAIAN, 0 AWAL, 0 AS MASUK, 0 AS KELUAR, KG AS LAIN, 5 as URUTAN  
			from stock where stock.TGL BETWEEN '$tglDrD' and '$tglSmpD' $filterstock and stock.PER='$periode' 
			
			order by KD_BRG, TGL, NO_BUKTI, URUTAN ASC
			
		) as kartustok  ;
		");

		$brg = Brg::where('KD_BRG', '<>','ZZ')->get();
		if($request->has('filter'))
		{
			return view('oreport_brg.kartu')->with(['brg' => $brg])->with(['hasil' => $query]);
		}

		$data=[];
		foreach ($query as $key => $value)
		{
			array_push($data, array(
				'NO_BUKTI' => $query[$key]->NO_BUKTI,
				'TGL' => $query[$key]->TGL,
				'KD_BRG' => $query[$key]->KD_BRG,
				'NA_BRG' => $query[$key]->NA_BRG,
				'URAIAN' => $query[$key]->URAIAN,
				'AWAL' => $query[$key]->AWAL,
				'MASUK' => $query[$key]->MASUK,
				'KELUAR' => $query[$key]->KELUAR,
				'LAIN' => $query[$key]->LAIN,
				'AKHIR' => $query[$key]->SALDO,
			));
		}
		$PHPJasperXML->setData($data);
		ob_end_clean();
		$PHPJasperXML->outpage("I");
	}
	
}
