<?php

namespace App\Http\Controllers\FReport;

use App\Http\Controllers\Controller;
use App\Models\FMaster\Account;
use App\Models\Master\Perid;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;

include_once base_path()."/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";
use PHPJasperXML;

use \koolreport\laravel\Friendship;
use \koolreport\bootstrap4\Theme;

class RAccountController extends Controller
{

   public function report()
    {
		$acno = Account::query()->get();
		$per = Perid::query()->get();
		//return $acno;
        return view('freport_account.report')->with(['acno' => $acno])->with(['per' => $per])->with(['hasil' => []]);
		
    }
	
	public function getAccountReport(Request $request)
    {
		
		 if ($request->session()->has('periode')) 
		{
			$periode = $request->session()->get('periode')['bulan']. '/' . $request->session()->get('periode')['tahun'];
		} else
		{
			$periode = '';
		}
		
		if($request['perio'])
		{
			$periode = $request['perio'];
		}
		
		$bulan = substr($periode,0,2);
		$tahun = substr($periode,3,4);
		
        		$query = DB::table('account')
			->join('accountd', 'account.ACNO', '=', 'accountd.ACNO')
			->select('account.ACNO','account.NAMA','account.POS2','account.KEL', 'account.NAMA_KEL','accountd.AW'.$bulan. ' as AW', 'accountd.KD'.$bulan. ' as KD', 
			'accountd.KK'.$bulan. ' as KK', 'accountd.BD'.$bulan. ' as BD', 'accountd.BK'.$bulan. ' as BK', 
			'accountd.MD'.$bulan. ' as MD', 'accountd.MK'.$bulan. ' as MK', 'accountd.AK'.$bulan. ' as AK')->where('accountd.YER',$tahun)->orderBy('accountd.ACNO', 'ASC')->get();
			
		//if ($request->ajax())
		//{
				//$query = $query->whereBetween('TGL', [$tglDrD, $tglSmp]);
			
			//return Datatables::of($query)->addIndexColumn()->make(true);
		//}
		return Datatables::of($query)->addIndexColumn()->make(true);
    }	  

	public function jasperAccountReport(Request $request) 
	{
		$file 	= 'nerapr';
		$PHPJasperXML = new PHPJasperXML();
		$PHPJasperXML->load_xml_file(base_path().('/app/reportc01/phpjasperxml/'.$file.'.jrxml'));
		
		
        	if ($request->session()->has('periode')) 
		{
			$periode = $request->session()->get('periode')['bulan']. '/' . $request->session()->get('periode')['tahun'];
		} else
		{
			$periode = '';
		}
		
		if($request['perio'])
		{
			$periode = $request['perio'];
		}
		
		$bulan = substr($periode,0,2);
		$tahun = substr($periode,3,4);

		session()->put('filter_periode', $periode);
		
		$queryakum = DB::SELECT("SET @akum:=0;");
		$query = DB::SELECT("
		SELECT account.ACNO,account.POS2, account.KEL, account.NAMA_KEL, account.NAMA,accountd.AW$bulan as AW, accountd.KD$bulan as KD, 
        accountd.KK$bulan as KK, accountd.BD$bulan as BD, accountd.BK$bulan as BK, 
        accountd.MD$bulan as MD, accountd.MK$bulan as MK, accountd.AK$bulan as AK 
        from account, accountd
        where account.ACNO=accountd.ACNO and accountd.YER='$tahun' order by account.ACNO;
		");

		if($request->has('filter'))
		{
			$acno = Account::query()->get();
			$per = Perid::query()->get();
			return view('freport_account.report')->with(['acno' => $acno])->with(['per' => $per])->with(['hasil' => $query]);
		}

		$data=[];
		foreach ($query as $key => $value)
		{
			array_push($data, array(
				'ACNO' => $query[$key]->ACNO,
				'NAMA' => $query[$key]->NAMA,
				'AW' => $query[$key]->AW,
				'DEBETK' => $query[$key]->KD,
				'KREDITK' => $query[$key]->KK,
				'DEBETB' => $query[$key]->BD,
				'KREDITB' => $query[$key]->BK,
				'DEBETM' => $query[$key]->MD,
				'KREDITM' => $query[$key]->MK,
				'AKHIR' => $query[$key]->AK,
				'POS2' => $query[$key]->POS2,
				'KEL' => $query[$key]->KEL,
				'NAMA_KEL' => $query[$key]->NAMA_KEL,				
				'PER' => $periode,
				'TGL_CETAK' => date("d/m/Y"),
			));
		}
		$PHPJasperXML->setData($data);
		ob_end_clean();
		$PHPJasperXML->outpage("I");
	}
	
}