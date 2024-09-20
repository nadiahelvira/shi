<?php

namespace App\Http\Controllers\FMaster;

use App\Http\Controllers\Controller;
// ganti 1

use App\Models\FMaster\Account;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;

// ganti 2
class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // ganti 3
        return view('master_account.index');
    }

    public function browseKel(Request $request)
    {
        $tipe = $request->tipe;
        if($tipe=='B')
        {
            $kel = DB::SELECT("SELECT DISTINCT GOL as KEL, NAMA as NAMA_KEL from nera where LEFT(TRIM(NAMA),1)='-' AND GOL<>'' AND NAMA <>'' ");
        }
        else if ($tipe=='R')
        {
            $kel = DB::SELECT("SELECT DISTINCT GOL as KEL, NAMA as NAMA_KEL from rl where LEFT(TRIM(NAMA),1)='-' AND GOL<>'' AND NAMA <>'' ");

        }
        else
        {
            $kel = DB::SELECT("SELECT '' as KEL, '' as NAMA_KEL ");
        }
		return response()->json($kel);
    }
    public function browsecash()
    {

        $account = Account::where('BNK', '=', '1')->get();
        return response()->json($account);
    }

    public function browsebank()
    {
        $account = Account::where('BNK', '=', '2')->get();
        return response()->json($account);
    }
    
    
    public function browsecashbank()
    {

        $account = Account::where('BNK', '<>', '')->get();
        return response()->json($account);
    }

    public function browse()
    {
        $account = Account::where('BNK', '=', '')->get();
        return response()->json($account);
    }

    public function browseallacc()
    {
        $account = Account::get();
        return response()->json($account);
    }
    // ganti 4a	

    public function getAccount()
    {
        // ganti 5

        $account =  DB::SELECT("SELECT * from ACCOUNT ORDER BY ACNO ");

        // ganti 6

        return Datatables::of($account)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant" || Auth::user()->divisi=="accounting") 
                {
                    $btnPrivilege =
                        '
                                <a class="dropdown-item" href="account/edit/?idx=' . $row->NO_ID . '&tipx=edit";
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <hr></hr>
                                <a class="dropdown-item btn btn-danger" onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)" href="account/delete/' . $row->NO_ID . '">
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
                'ACNO'       => 'required',
                'NAMA'      => 'required'
            ]
        );

        // Insert Header

        // ganti 10

        $account = Account::create(
            [
                'ACNO'            => ($request['ACNO'] == null) ? "" : $request['ACNO'],
                'NAMA'            => ($request['NAMA'] == null) ? "" : $request['NAMA'],
                'BNK'             => ($request['BNK'] == null) ? "" : $request['BNK'],
                'KEL'              => ($request['KEL'] == null) ? "" : $request['KEL'],
                'NAMA_KEL'         => ($request['NAMA_KEL'] == null) ? "" : $request['NAMA_KEL'],
                'POS2'            => ($request['POS2'] == null) ? "" : $request['POS2'],
                'USRNM'          => Auth::user()->username,
                'TG_SMP'         => Carbon::now()
            ]
        );

        //  ganti 11

			
	    $acnox = $request['ACNO'];
		
		$account = Account::where('ACNO', $acnox )->first();
					       
        //return redirect('/account/edit/?idx=' . $account->NO_ID . '&tipx=edit')->with('statusInsert', 'Data baru berhasil ditambahkan');
		return redirect('/account')->with('status', 'Data berhasil ditambahkan');
				
				
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

   
    // ganti 15

    public function edit(Request $request ,  Account $account)
    {

        // ganti 16


		$tipx = $request->tipx;

		$idx = $request->idx;
					

		
		if ( $idx =='0' && $tipx=='undo'  )
	    {
			$tipx ='top';
			
		   }
		   
		 if ($tipx=='search') {
			
		   	
    	   $kodex = $request->kodex;
		   
		   $bingco = DB::SELECT("SELECT NO_ID, ACNO from account 
		                 where ACNO = '$kodex'						 
		                 ORDER BY ACNO ASC  LIMIT 1" );
						 
			
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
			
		   $bingco = DB::SELECT("SELECT NO_ID, ACNO from account      
		                 ORDER BY ACNO ASC  LIMIT 1" );
					 
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
			
    	   $kodex = $request->kodex;
			
		   $bingco = DB::SELECT("SELECT NO_ID, ACNO from ACCOUNT      
		             where ACNO < 
					 '$kodex' ORDER BY ACNO DESC LIMIT 1" );
			

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
			
				
      	   $kodex = $request->kodex;
	   
		   $bingco = DB::SELECT("SELECT NO_ID, ACNO from ACCOUNT    
		             where ACNO > 
					 '$kodex' ORDER BY ACNO ASC LIMIT 1" );
					 
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
		  
    		$bingco = DB::SELECT("SELECT NO_ID, ACNO from ACCOUNT     
		              ORDER BY ACNO DESC  LIMIT 1" );
					 
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
			$account = Account::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
             $account = new Account;			 
		 }

		 $data = [
						'header' => $account,
			        ];				
			return view('master_account.edit', $data)->with(['tipx' => $tipx, 'idx' => $idx ]);
		 
	 
    }



    public function update(Request $request, Account $account)
    {


        $this->validate(
            $request,
            [

                // ganti 19

                'ACNO'       => 'required',
                'NAMA'      => 'required'
            ]
        );
		
		
        // ganti 20

		$tipx = 'edit';
		$idx = $request->idx;
					
					
        $account->update(
            [

                'NAMA'           => ($request['NAMA'] == null) ? "" : $request['NAMA'],			
                'BNK'            => ($request['BNK'] == null) ? "" : $request['BNK'],
                'KEL'              => ($request['KEL'] == null) ? "" : $request['KEL'],
                'NAMA_KEL'         => ($request['NAMA_KEL'] == null) ? "" : $request['NAMA_KEL'],
                'POS2'           => ($request['POS2'] == null) ? "" : $request['POS2'],
                'USRNM'          => Auth::user()->username,
                'TG_SMP'         => Carbon::now()

            ]
        );

		 
        //  ganti 21

        //return redirect('/account/edit/?idx=' . $account->NO_ID . '&tipx=edit');
		return redirect('/account')->with('status', 'Data berhasil di update');
			
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 22

    public function destroy( Request $request, Account $account)
    {

        // ganti 23
		
        $deleteAccount = Account::find($account->NO_ID);

        // ganti 24

        $deleteAccount->delete();

        // ganti 
        return redirect('/account')->with('status', 'Data berhasil dihapus');
		
    }

    public function cekacc(Request $request)
    {
        $getItem = DB::SELECT('select count(*) as ADA from account where ACNO ="' . $request->ACNO . '"');

        return $getItem;
    }
}
