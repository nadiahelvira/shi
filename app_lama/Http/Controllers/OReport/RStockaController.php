<?php

namespace App\Http\Controllers\OReport;

use App\Http\Controllers\Controller;
// ganti 1
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Master\Bhn;
use DataTables;
use Auth;
use DB;

// ganti 2
class RStockaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

   	public function report()
    {
// GANTI 3 //
		$kd_bhn = Bhn::query()->get();		//return $acno;
// GANTI 3 //
        return view('freport_stocka.report')->with(['kd_bhn' => $kd_bhn]);
		
    }
	
		public function getStockaReport(Request $request)
    {

        $query = DB::table('stocka')
			->join('stocka', 'stocka.NO_BUKTI', '=', 'stockad.NO_BUKTI')
			->select('stocka.NO_BUKTI', 'stocka.TGL', 'stockad.KD_BHN', 'stockad.NA_BHN', 'stockad.QTY', 'stockad.KET')->get();
			
		if ($request->ajax())
		{
			// Ganti format tanggal input agar sama dengan database
			$tglDrD = date("Y-m-d", strtotime($request['tglDr']));
            $tglSmpD = date("Y-m-d", strtotime($request['tglSmp']));
			
			// Convert tanggal agar ambil start of day/end of day
			//$tglDr = Carbon::parse($request->tglDr)->startOfDay();
            $tglSmp = Carbon::parse($request->tglSmp)->endOfDay();
			
			// Check Filter
			
			if (!empty($request->tglDr) && !empty($request->tglSmp))
			{
				$query = $query->whereBetween('TGL', [$tglDrD, $tglSmp]);
			}
			
			return Datatables::of($query)->addIndexColumn()->make(true);
		}
		
    }	  



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


	
	

	
}
