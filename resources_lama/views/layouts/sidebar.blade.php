<style>
.nav-item li.active {
  border-bottom: 3px solid #338ecf;
  background: #494e52;
}
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('/')}}" class="brand-link" style="text-align: center">
      <img src="{{url('/img/company.jpg')}}" alt="Logo Jago" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">SHI</span>
    </a>

    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        {{-- <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="test">
        </div> --}}
        <div class="info">
          <a href="{{url('profile')}}" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
          <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
              <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-sidebar">
                  <i class="fas fa-search fa-fw"></i>
                </button>
              </div>
            </div>
          </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{url('/')}}" class="nav-link {{ (Request::is('/')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-home"></i>
              <p>Home</p>
            </a>
          </li>
          
          @if ( (Auth::user()->divisi=="programmer") )
          <li class="nav-item {{ (Request::is('cheatsheet*')) ? 'active' : '' }}">
            <a href="{{url('cheatsheet')}}" class="nav-link">
              <i class="nav-icon fas fa-skull-crossbones icon-pink "></i>
              <p>Cheatsheet</p>
            </a>
          </li>
          @endif
          
          @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") )
          <li class="nav-item {{ (Request::is('chart*'))  ? 'menu-open' : '' }}">
            <a href="{{url('chart')}}" class="nav-link">
              <i class="nav-icon far fa-chart-bar icon-yellow "></i>
              <p>Dashboard</p>
            </a>
          </li>
          @endif

          @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="penjualan") )
          <li class="nav-item">
            <a href="#" class="nav-link">
              <p>
                Posting
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") )
              <li class="nav-item">
                <a href="{{url('posting/index?JNS=f')}}" class="nav-link">
                  <i class="nav-icon fas fa-money-bill icon-white"></i>
                  <p>Finance </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('posting/index?JNS=s')}}" class="nav-link">
                  <i class="nav-icon fas fa-boxes icon-orange"></i>
                  <p>Stok </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('posting/index?JNS=u')}}" class="nav-link">
                  <i class="nav-icon fas fa-donate icon-green"></i>
                  <p>Uang </p>
                </a>
              </li>
              @endif
				
              
              
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") )
              <li class="nav-item">
                <a href="{{url('postingsls/index?JNS=PO')}}" class="nav-link">
                  <i class="nav-icon 	far fa-check-circle icon-aqua"></i>
                  <p>Posting Status SLS PO</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('postingsls/index?JNS=SO')}}" class="nav-link">
                  <i class="nav-icon fas fa-check-circle icon-yellow"></i>
                  <p>Posting Status SLS SO</p>
                </a>
              </li>
              @endif
            </ul>
          </li>
          @endif
<!--------------------------------------------------------------------------------------->
          <li class="nav-header">Operational</li>
          <li class="nav-item {{ (Request::is('cust*')) || (Request::is('sup*')) || (Request::is('pegawai*')) || (Request::is('brg*')) || (Request::is('refa*')) || (Request::is('truck*')) || (Request::is('tujuan*')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-database icon-white"></i> 
              <p>
                Master
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item {{ (Request::is('cust*')) ? 'active' : '' }}">
                <a href="{{url('cust')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-user-tie icon-yellow"></i> -->
                  <p>Customer</p>
                </a>
              </li>
              
              <li class="nav-item {{ (Request::is('sup*')) ? 'active' : '' }}">
                <a href="{{url('sup')}}" class="nav-link">
                  <!-- <i class="nav-icon far fa-address-card icon-purple "></i> -->
                  <p>Suplier</p>
                </a>
              </li>
              

              <li class="nav-item {{ (Request::is('brg*')) ? 'active' : '' }}">
                <a href="{{url('brg')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-box icon-green"></i> -->
                  <p>Barang</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('gdg*')) ? 'active' : '' }}">
                <a href="{{url('gdg')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-tag icon-aqua "></i> -->
                  <p>Gudang</p>
                </a>
              </li>         
              
              <li class="nav-item {{ (Request::is('mkl*')) ? 'active' : '' }}">
                <a href="{{url('mkl')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-truck icon-pink "></i> -->
                  <p>Emkl</p>
                </a>
              </li>        
              
            </ul>
          </li>			  
<!--------------------------------------------------------------------------------------->	
          <!-- @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="pembelian") || (Auth::user()->divisi=="accounting") )
          <li class="nav-item {{ (Request::is('po')) || (Request::is('beli')) || (Request::is('thut')) || (Request::is('um')) || (Request::is('tb')) || (Request::is('hut'))? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shopping-basket icon-aqua"></i>
              <p>
                Transaksi Pembelian Barang
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>			 -->
			
			      <!-- <ul class="nav nav-treeview">
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="pembelian") )
              <li class="nav-item {{ (Request::is('po*')) ? 'active' : '' }}">
                <a href="{{url('po?golz=Y')}}" class="nav-link"> -->
                  <!-- <i class="nav-icon fas fa-car icon-brown"></i> -->
                  <!-- <p>Purchase Order</p>
                </a>
              </li>
              @endif

              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="pembelian") )
              <li class="nav-item {{ (Request::is('beli*')) ? 'active' : '' }}">             
				        <a href="{{url('beli?flagz=BL&golz=Y')}}" class="nav-link"> -->
                  <!-- <i class="nav-icon fas fa-briefcase icon-green"></i> -->
                  <!-- <p>Pembelian</p>
                </a>
              </li>
              @endif


			  
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="pembelian") )
              <li class="nav-item {{ (Request::is('thut*')) ? 'active' : '' }}">
                <a href="{{url('beli?flagz=TH&golz=Y')}}" class="nav-link"> -->
                  <!-- <i class="nav-icon fas fa-hand-holding-usd icon-pink"></i> -->
                  <!-- <p>Transaksi Hutang</p>
                </a>
              </li>
              @endif

              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="accounting") )
              <li class="nav-item {{ (Request::is('um*')) ? 'active' : '' }}">
                <a href="{{url('beli?flagz=UM&golz=Y')}}" class="nav-link"> -->
                  <!-- <i class="nav-icon fas fa-file-invoice icon-white"></i> -->
                  <!-- <p>U.M Pembelian</p>
                </a>
              </li>
              @endif
			  
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="pembelian") )
              <li class="nav-item {{ (Request::is('hut*')) ? 'active' : '' }}">
                <a href="{{url('hut?flagz=B&golz=Y')}}" class="nav-link"> -->
                  <!-- <i class="nav-icon fas fa-file-invoice-dollar icon-yellow"></i> -->
                  <!-- <p>Pembayaran Hutang </p>
                </a>
              </li>
              @endif
			  
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="pembelian") )
              <li class="nav-item {{ (Request::is('terima*')) ? 'active' : '' }}">
                <a href="{{url('terima?flagz=TR&golz=Y')}}" class="nav-link"> -->
                  <!-- <i class="nav-icon fas fa-file-invoice-dollar icon-yellow"></i> -->
                  <!-- <p>Terima Gudang </p>
                </a>
              </li>
              @endif
			   @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="pembelian") )
              <li class="nav-item {{ (Request::is('pp*')) ? 'active' : '' }}">
                <a href="{{url('pp')}}" class="nav-link"> -->
                  <!-- <i class="nav-icon fas fa-car icon-brown"></i> -->
                  <!-- <p>Container-BL</p>
                </a>
              </li>
              @endif
			   @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="pembelian") )
              <li class="nav-item">
                <a href="{{url('terima/index-posting')}}" class="nav-link"> -->
                  <!-- <i class="nav-icon fas fa-car icon-brown"></i> -->
                  <!-- <p>Posting Terima</p>
                </a>
              </li>
              @endif
			  
            </ul>			 		
          </li>
          @endif -->
<!--------------------------------------------------------------------------------------->
          <li class="nav-item {{ (Request::is('pon*')) || (Request::is('belin*')) || (Request::is('thutn*')) || (Request::is('umn*')) || (Request::is('tn*')) || (Request::is('hutn*')) || (Request::is('gaji*')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-people-carry icon-green"></i>
              <p>
                Transaksi Non
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>			
			
			      <ul class="nav nav-treeview">
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="pembelian") )
              <li class="nav-item {{ (Request::is('pon*')) ? 'active' : '' }}">
                <a href="{{url('po?golz=A2')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-beer icon-red"></i> -->
                  <p>Purchase Order Non</p>
                </a>
              </li>
              @endif

              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="penjualan") )
              <li class="nav-item {{ (Request::is('belin*')) ? 'active' : '' }}">         
        		 <a href="{{url('beli?flagz=BL&golz=A2')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-receipt icon-yellow"></i> -->
                  <p>Pembelian Non</p>
                </a>
              </li>
              @endif


              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="penjualan") || (Auth::user()->divisi=="accounting") )
              <li class="nav-item {{ (Request::is('hutn*')) ? 'active' : '' }}">
                <a href="{{url('hut?flagz=PH&golz=A2')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-comments-dollar icon-pink"></i> -->
                  <p>Pembayaran Hutang Non </p>
                </a>
              </li>
              @endif

            </ul>			 		
          </li> 
<!--------------------------------------------------------------------------------------->
          @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="penjualan") || (Auth::user()->divisi=="accounting") )
          <li class="nav-item {{ (Request::is('so*')) || (Request::is('surats*')) || (Request::is('jual*')) || (Request::is('tpiu*')) || (Request::is('uj*')) || (Request::is('piu*')) || (Request::is('stock*')) ? 'menu-open' : '' }}">
			      <a href="#" class="nav-link">
              <i class="nav-icon fas fa-warehouse icon-red"></i>
              <p>
                Transaksi Penjualan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>			
			
			      <ul class="nav nav-treeview">
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="penjualan") )
              <li class="nav-item {{ (Request::is('so*')) ? 'active' : '' }}">
                <a href="{{url('so?golz=A2')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-crop icon-white"></i> -->
                  <p>Sales Order </p>
                </a>
              </li>
              @endif		  

			  
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="penjualan") )
              <li class="nav-item {{ (Request::is('jual*')) ? 'active' : '' }}">
                <a href="{{url('jual?flagz=JL&golz=A2')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-dolly icon-purple"></i> -->
                  <p>Penjualan </p>
                </a>
              </li>
              @endif

              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") )
              <li class="nav-item {{ (Request::is('piu*')) ? 'active' : '' }}">
                <a href="{{url('piu?flagz=PP&golz=A2')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-envelope-open-text icon-pink"></i> -->
                  <p>Pembayaran Piutang </p>
                </a>
              </li>
              @endif
			  
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="penjualan") )
              <li class="nav-item {{ (Request::is('stock*')) ? 'active' : '' }}">
                <a href="{{url('stock?flagz=KR&golz=Y')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-flag icon-orange"></i> -->
                  <p>Koreksi Stock Barang </p>
                </a>
              </li>
              @endif
			 
            </ul>			 		
          </li> 
          @endif
          
          <li class="nav-item">          
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book icon-yellow"></i>
              <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">

              <li class="nav-item {{ (Request::is('rbrg')) ? 'active' : '' }}">
                <a href="{{url('rbrg')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-flask icon-green"></i> -->
                  <p>Rpt-Barang</p>
                </a>
              </li>
			  
              <li class="nav-item {{ (Request::is('rsup')) ? 'active' : '' }}">
                <a href="{{url('rsup')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-flask icon-purple"></i> -->
                  <p>Rpt-Suplier</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('rcust')) ? 'active' : '' }}">
                <a href="{{url('rcust')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-flask icon-yellow"></i> -->
                  <p>Rpt-Customer</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('rpo')) ? 'active' : '' }}">
                <a href="{{url('rpo')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-flask icon-red"></i> -->
                  <p>Rpt-Purchase Order</p>
                </a>
              </li>
			  
              <li class="nav-item {{ (Request::is('rbeli')) ? 'active' : '' }}">
                <a href="{{url('rbeli')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-folder icon-brown"></i> -->
                  <p>Rpt-Pembelian</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('rhut')) ? 'active' : '' }}">
                <a href="{{url('rhut')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-gamepad icon-white"></i> -->
                  <p>Rpt-Pembayaran Hutang</p>
                </a>
              </li>
			  
              <li class="nav-item {{ (Request::is('rthut')) ? 'active' : '' }}">
                <a href="{{url('rthut')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-gavel icon-purple"></i> -->
                  <p>Rpt-Transaksi Hutang</p>
                </a>
              </li>
              
              <li class="nav-item {{ (Request::is('rsisahut')) ? 'active' : '' }}">
                <a href="{{url('rsisahut')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-gavel icon-aqua"></i> -->
                  <p>Rpt-Sisa Hutang</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('rum')) ? 'active' : '' }}">
                <a href="{{url('rum')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-gift icon-green"></i> -->
                  <p>Rpt-U.M Pembelian</p>
                </a>
              </li>
			  
              <li class="nav-item {{ (Request::is('rterima')) ? 'active' : '' }}">
                <a href="{{url('rterima')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-gift icon-green"></i> -->
                  <p>Rpt-Terima Gudang</p>
                </a>
              </li>
			  
              <li class="nav-item {{ (Request::is('rso')) ? 'active' : '' }}">
                <a href="{{url('rso')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-bug icon-pink"></i> -->
                  <p>Rpt-Sales Order</p>
                </a>
              </li>


              <li class="nav-item {{ (Request::is('rjual')) ? 'active' : '' }}">
                <a href="{{url('rjual')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-heart icon-yellow"></i> -->
                  <p>Rpt-Penjualan</p>
                </a>
              </li>
              
              <li class="nav-item {{ (Request::is('rtpiu')) ? 'active' : '' }}">
                <a href="{{url('rtpiu')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-inbox icon-green"></i> -->
                  <p>Rpt-Transaksi Piutang</p>
                </a>
              </li>
              
              <li class="nav-item {{ (Request::is('rsisapiu')) ? 'active' : '' }}">
                <a href="{{url('rsisapiu')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-inbox icon-purple"></i> -->
                  <p>Rpt-Sisa Piutang</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('ruj')) ? 'active' : '' }}">
                <a href="{{url('ruj')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-key icon-blue"></i> -->
                  <p>Rpt-U.M Penjualan</p>
                </a>
              </li>
			  
              <li class="nav-item {{ (Request::is('rpiu')) ? 'active' : '' }}">
                <a href="{{url('rpiu')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-laptop icon-pink"></i> -->
                  <p>Rpt-Pembayaran Piutang</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('rstockb')) ? 'active' : '' }}">
                <a href="{{url('rstockb')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-lock icon-white"></i> -->
                  <p>Rpt-Koreksi Stock Barang</p>
                </a>
              </li>
            </ul>
          </li>
          
          <li class="nav-item {{ (Request::is('rkarstk')) || (Request::is('rkartuh')) || (Request::is('rkartup')) ? 'menu-open' : '' }}">          
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-archive icon-pink"></i>
              <p>
                Kartu
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item {{ (Request::is('rkarstk')) ? 'active' : '' }}">
                <a href="{{url('rkarstk')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-box-open icon-red"></i> -->
                  <p>Stok</p>
                </a>
              </li>
              
              <li class="nav-item {{ (Request::is('rkartuh')) ? 'active' : '' }}">
                <a href="{{url('rkartuh')}}" class="nav-link">
                  <!-- <i class="nav-icon far fa-sticky-note icon-pink"></i> -->
                  <p>Hutang</p>
                </a>
              </li>
              
              <li class="nav-item {{ (Request::is('rkartup')) ? 'active' : '' }}">
                <a href="{{url('rkartup')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-inbox icon-green"></i> -->
                  <p>Piutang</p>
                </a>
              </li>
                         
            </ul>
         </li>
<!--------------------------------------------------------------------------------------->
        @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") || (Auth::user()->divisi=="accounting") )
        <li class="nav-header">Financial</li>
          <li class="nav-item {{ (Request::is('account*')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cloud icon-white"></i>
              <p>
                Master
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item {{ (Request::is('account*')) ? 'active' : '' }}">
                <a href="{{url('account')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-map icon-aqua"></i> -->
                  <p>Account </p>
                </a>
              </li>

            </ul>									
          </li>

	  
          <li class="nav-item {{ (Request::is('kas*')) || (Request::is('bank*')) || (Request::is('memo*')) || (Request::is('cbin*')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-university icon-green"></i>
              <p>
                Transaksi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			
			      <ul class="nav nav-treeview">
              <li class="nav-item {{ (Request::is('kas')) ? 'active' : '' }}">
                <a href="{{url('kas?flagz=BKM')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-lock icon-yellow"></i> -->
                  <p>Kas Masuk</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('kask*')) ? 'active' : '' }}">
                <a href="{{url('kas?flagz=BKK')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-magic icon-blue"></i> -->
                  <p>Kas Keluar</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('bank')) ? 'active' : '' }}">
                <a href="{{url('bank?flagz=BBM')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-magnet icon-red"></i> -->
                  <p>Bank Masuk</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('bankk*')) ? 'active' : '' }}">
                <a href="{{url('bank?flagz=BBK')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-male icon-purple"></i> -->
                  <p>Bank Keluar</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('memo*')) ? 'active' : '' }}">
                <a href="{{url('memo?flagz=M')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-bug icon-orange"></i> -->
                  <p>Penyesuaian</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('cbin*')) ? 'active' : '' }}">
                <a href="{{url('cbin')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-microphone icon-white"></i> -->
                  <p>Kas - Bank </p>
                </a>
              </li>
			  
            </ul>									
          </li>

          <li class="nav-item {{ (Request::is('rkas')) || (Request::is('rbank')) || (Request::is('rmemo')) || (Request::is('rbuku')) || (Request::is('raccount')) || (Request::is('rrl')) || (Request::is('rnera')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-print icon-purple"></i>
              <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
			
			      <ul class="nav nav-treeview">
              <li class="nav-item {{ (Request::is('rkas')) ? 'active' : '' }}">
                <a href="{{url('rkas')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-car icon-green"></i> -->
                  <p>Journal Kas</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('rbank')) ? 'active' : '' }}">
                <a href="{{url('rbank')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-plus icon-purple"></i> -->
                  <p>Journal Bank</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('rmemo')) ? 'active' : '' }}">
                <a href="{{url('rmemo')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-beer icon-red"></i> -->
                  <p>Journal Penyesuaian</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('rbuku')) ? 'active' : '' }}">
                <a href="{{url('rbuku')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-eraser icon-blue"></i> -->
                  <p>Buku Besar</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('raccount')) ? 'active' : '' }}">
                <a href="{{url('raccount')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-gift icon-aqua"></i> -->
                  <p>Neraca Percobaan</p>
                </a>
              </li>

              <li class="nav-item {{ (Request::is('rrl')) ? 'active' : '' }}">
                <a href="{{url('rrl')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-random icon-white"></i> -->
                  <p>Laba Rugi</p>
                </a>
              </li>
			  
              <li class="nav-item {{ (Request::is('rnera')) ? 'active' : '' }}">
                <a href="{{url('rnera')}}" class="nav-link">
                  <!-- <i class="nav-icon fas fa-road icon-pink"></i> -->
                  <p>Neraca</p>
                </a>
              </li>
			  
            </ul>									
          </li>
        @endif 
<!--------------------------------------------------------------------------------------->
          <li class="nav-header">Utility</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <!-- <i class="nav-icon fas fa-plus icon-yellow"></i> -->
              <i class="nav-icon fas fa-tools icon-white"></i>
              <p>
                Utility
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="javascript:void(0)" data-toggle="modal" data-target="#periodeModal" id="periode"  class="nav-link">
                  <!-- <i class="nav-icon far fa-calendar-alt icon-red"></i> -->
                  <p>Ganti Periode</p>
                </a>
              </li>
            </ul>

            <ul class="nav nav-treeview">
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") )
              <li class="nav-item">
                <a href="{{url('perbaiki')}}" class="nav-link">
                  <i class="nav-icon fas fa-tools icon-white"></i> 
                  <p>Perbaiki Data</p>
                </a>
              </li>
              @endif 
            </ul>
            <ul class="nav nav-treeview">
              <!--
              @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") || (Auth::user()->divisi=="assistant") )
              <li class="nav-item">
                <a href="{{url('kosongi')}}" class="nav-link">
                  <i class="nav-icon fas fa-ban icon-red"></i>
                  <p>Kosongkan Data</p>
                </a>
              </li>
              @endif 
		          -->	  
            </ul>
          </li>

          @if ( (Auth::user()->divisi=="programmer") || (Auth::user()->divisi=="owner") )
          <li class="nav-header">User Management</li>
          <li class="nav-item {{ (Request::is('user*')) ? 'active' : '' }}">
            <a href="{{url('/user/manage')}}" class="nav-link">
              <i class="nav-icon fas fa-users icon-orange"></i>
              <p>
                User
              </p>
            </a>
          </li>
          @endif

        </ul>
      </nav>
    </div>
  </aside>