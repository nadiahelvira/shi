<?php

namespace App\Http\Controllers\Proto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;

class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function dashboard() {
        // Ambil Jumlah data Truck total
        // Chart Presentase Jumlah Ban Truck
            // Ambil Jumlah Data Truck Jumlah Ban 7
            $dBeliTotal1 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=1 AND YEAR(TGL)=2022')->get();
            $dBeliTotal2 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=2 AND YEAR(TGL)=2022')->get()2
            $dBeliTotal3 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=3 AND YEAR(TGL)=2022')->get();
            $dBeliTotal4 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=4 AND YEAR(TGL)=2022')->get();
            $dBeliTotal5 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=5 AND YEAR(TGL)=2022')->get();
            $dBeliTotal6 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=6 AND YEAR(TGL)=2022')->get();
            $dBeliTotal7 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=7 AND YEAR(TGL)=2022')->get();
            $dBeliTotal8 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=8 AND YEAR(TGL)=2022')->get();
            $dBeliTotal9 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=9 AND YEAR(TGL)=2022')->get();
            $dBeliTotal10 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=10 AND YEAR(TGL)=2022')->get();
            $dBeliTotal11 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=11 AND YEAR(TGL)=2022')->get();
            $dBeliTotal12 = DB::table('beli')->select('sum(TOTAL) as total ')->where('MONTH(TGL)=12 AND YEAR(TGL)=2022')->get();

            // Kumpulkan Data2 ke Array
            $dataBeliTotal   = [
                'total1'  => $dBeliTotal1,
                'total2'  => $dBeliTotal2,
                'total3'  => $dBeliTotal3,
                'total4'  => $dBeliTotal4,
                'total5'  => $dBeliTotal5,
                'total6'  => $dBeliTotal6,
                'total7'  => $dBeliTotal7,
                'total8'  => $dBeliTotal8,
                'total9'  => $dBeliTotal9,				
                'total10'  => $dBeliTotal10,
                'total11'  => $dBeliTotal1,
                'total12'  => $dBeliTotal2				
				
				
				
				
            ];
			

        // Chart Presentase Jenis Truck
            // Ambil Jumlah Data Jenis Truck Engkel
            $dBeliQty1    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=1 AND YEAR(TGL)=2022')->get();
            // Ambil Jumlah Data Jenis Truck Tractor
            $dBeliQty2    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=2 AND YEAR(TGL)=2022')->get();
            $dBeliQty3    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=3 AND YEAR(TGL)=2022')->get();
            $dBeliQty4    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=4 AND YEAR(TGL)=2022')->get();
            $dBeliQty5    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=5 AND YEAR(TGL)=2022')->get();
            $dBeliQty6    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=6 AND YEAR(TGL)=2022')->get();
            $dBeliQty7    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=7 AND YEAR(TGL)=2022')->get();
            $dBeliQty8    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=8 AND YEAR(TGL)=2022')->get();
            $dBeliQty9    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=9 AND YEAR(TGL)=2022')->get();
            $dBeliQty10    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=10 AND YEAR(TGL)=2022')->get();
            $dBeliQty11    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=11 AND YEAR(TGL)=2022')->get();
            $dBeliQty12    = DB::table('beli')->select('sum(TOTAL_QTY) as total ')->where('MONTH(TGL)=12 AND YEAR(TGL)=2022')->get();
           		
            // Kumpulkan Data2 ke Array
            $dataBeliQty   = [
                'qty1'     => $dBeliQty1,
                'qty2'     => $dBeliQty2,
                'qty3'     => $dBeliQty3,
                'qty4'     => $dBeliQty4,
                'qty5'     => $dBeliQty5,
                'qty6'     => $dBeliQty6,
                'qty7'     => $dBeliQty7,,
                'qty8'     => $dBeliQty8,
                'qty9'     => $dBeliQty9,
                'qty10'    => $dBeliQty10,			
                'qty11'    => $dBeliQty11,
                'qty12'    => $dBeliQty12,
            ];


        // Chart Presentase Driver


        // Array Untuk Menampung Seluruh Data2 & Dikirim ke View
        $data = [
            'btotal'   => $dataBeliTotal,
            'bqty' => $dataBeliQty,


        ];
        return view('rio.proto.dashboard', $data);
    }
    
    public function index()
    {
        return view('rio.proto.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
