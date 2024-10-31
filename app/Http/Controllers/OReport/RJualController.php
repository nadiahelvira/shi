<?php

namespace App\Http\Controllers\OReport;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Master\Cust;
use DataTables;
use Auth;
use DB;

include_once base_path()."/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";
use PHPJasperXML;

use \koolreport\laravel\Friendship;
use \koolreport\bootstrap4\Theme;

class RJualController extends Controller
{

  	public function report()
    {
		$kodec = Cust::orderBy('KODEC')->get();
		session()->put('filter_gol', '');
		session()->put('filter_kodec1', '');
		session()->put('filter_namac1', '');
		session()->put('filter_tglDari', date("d-m-Y"));
		session()->put('filter_tglSampai', date("d-m-Y"));
		session()->put('filter_brg1', '');
		session()->put('filter_nabrg1', '');
		session()->put('filter_kdgd1', '');
	
        return view('oreport_jual.report')->with(['kodec' => $kodec])->with(['hasil' => []]);
    }
	  

	public function jasperJualReport(Request $request) 
	{
		$file 	= 'jualn';
		$PHPJasperXML = new PHPJasperXML();
		$PHPJasperXML->load_xml_file(base_path().('/app/reportc01/phpjasperxml/'.$file.'.jrxml'));
		
			// Check Filter

			if (!empty($request->kodec))
			{
				$filterkodec = " and KODEC='".$request->kodec."' ";
			}
			
			
			if (!empty($request->tglDr) && !empty($request->tglSmp))
			{
				$tglDrD = date("Y-m-d", strtotime($request->tglDr));
				$tglSmpD = date("Y-m-d", strtotime($request->tglSmp));
				$filtertgl = " and TGL between '".$tglDrD."' and '".$tglSmpD."' ";
			}

			if (!empty($request->brg1))
			{
				$filterbrg = " and KD_BRG='".$request->brg1."' ";
			}

			if (!empty($request->kdgd1))
			{
				$filtergudang = " and GUDANG='".$request->kdgd1."' ";
			}


			session()->put('filter_gol', $request->gol);
			session()->put('filter_kodec1', $request->kodec);
			session()->put('filter_namac1', $request->NAMAC);
			session()->put('filter_tglDari', $request->tglDr);
			session()->put('filter_tglSampai', $request->tglSmp);
			session()->put('filter_brg1', $request->brg1);
			session()->put('filter_nabrg1', $request->nabrg1);
			session()->put('filter_kdgd1', $request->kdgd1);
			session()->put('filter_no_so1', $request->no_so1);
			
		$query = DB::SELECT("
			SELECT NO_BUKTI,TGL,NO_SO,TRUCK, KODEC,NAMAC,KD_BRG,NA_BRG,KG, QTY, HARGA,TOTAL, 
			DPP, PPN, GUDANG, NOTES from jual WHERE FLAG='JL' $filtertgl  $filterkodec  $filterbrg $filtergudang;
		");
      
		if($request->has('filter'))
		{
			return view('oreport_jual.report')->with(['hasil' => $query]);
		}

		$data=[];
		foreach ($query as $key => $value)
		{
			array_push($data, array(
				'NO_BUKTI' => $query[$key]->NO_BUKTI,
				'TGL' => $query[$key]->TGL,
				'NO_SO' => $query[$key]->NO_SO,
				'KODEC' => $query[$key]->KODEC,
				'NAMAC' => $query[$key]->NAMAC,
				'TRUCK' => $query[$key]->TRUCK,
				'KG' => $query[$key]->KG,
				'QTY' => $query[$key]->QTY,
				'NAMAC' => $query[$key]->NAMAC,
				'KD_BRG' => $query[$key]->KD_BRG,
				'NA_BRG' => $query[$key]->NA_BRG,
				'NA_BRG' => $query[$key]->NA_BRG,
				'GDG' => $query[$key]->GUDANG,
				'DPP' => $query[$key]->DPP,
				'PPN' => $query[$key]->PPN,
				'TOTAL' => $query[$key]->TOTAL,
				'HARGA' => $query[$key]->HARGA,
				'NOTES' => $query[$key]->NOTES,

			));
		}
		$PHPJasperXML->setData($data);
		ob_end_clean();
		$PHPJasperXML->outpage("I");
	}
	
}