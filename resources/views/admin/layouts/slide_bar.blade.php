<aside class="main-sidebar sidebar-dark-light elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('assets/images/'.env('LOGO_IMAGE')) }}" alt="AdminLTE Logo" class="brand-image">
        <span class="brand-text font-weight-light">Admin</span><span class="text-green">BA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        {{-- <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/images/img_profile_default.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">admin admin</a>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                @if( Auth::user()->isRoleAccess('admin|sub_admin') )
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{Request::is('dashboard')? "active" : ""}}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>หน้าแรก</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin|sub_admin') )
                <li class="nav-item">
                    <a href="{{ route('membership-requests.index') }}" class="nav-link {{Request::is('membership-requests')? "active" : ""}}">
                        <i class="nav-icon fas fa-user-plus mr-2"></i>
                        <p>คำขอสมัครสมาชิก</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin|sub_admin') )
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{Request::is('users')? "active" : ""}}">
                        <i class="nav-icon fas fa-user-check mr-2"></i>
                        <p>สมาชิก</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin|sub_admin') )
                <li class="nav-item">
                    <a href="{{ route('trades.index') }}" class="nav-link {{Request::is('trades')? "active" : ""}}">
                        <i class="nav-icon fas fa-list-ul mr-2"></i>
                        <p>การซื้อขาย</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin|sub_admin') )
                <li class="nav-item">
                    <a href="{{ route('trades.report') }}" class="nav-link {{Request::is('trades.report')? "active" : ""}}">
                        <i class="nav-icon fas fa-list-ul mr-2"></i>
                        <p>รายงานการซื้อขาย</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('incomes.index') }}" class="nav-link {{Request::is('incomes')? "active" : ""}}">
                        <i class="nav-icon fas fa-list-ul mr-2"></i>
                        <p>รายได้</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin|sub_admin') )
                <li class="nav-item">
                    <a href="{{ route('billing.index') }}" class="nav-link {{Request::is('billing')? "active" : ""}}">
                        <i class="nav-icon fas fa-file-invoice-dollar mr-2"></i>
                        <p>ค่าธรรมเนียม</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin') )
                <li class="nav-item">
                    <a href="{{ route('incomes.create') }}" class="nav-link {{Request::is('incomes/create')? "active" : ""}}">
                        <i class="nav-icon fas fa-sync-alt mr-2"></i>
                        <p>โอนเทรดบาท</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin') )
                <li class="nav-item">
                    <a href="{{ route('credits.form') }}" class="nav-link {{Request::is('credits')? "active" : ""}}">
                        <i class="nav-icon fas fa-sync-alt mr-2"></i>
                        <p>โอนเงินเครดิต</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin|sub_admin') )
                <li class="nav-item">
                    <a href="{{ route('trades.create') }}" class="nav-link {{Request::is('trades/create')? "active" : ""}}">
                        <i class="nav-icon fas fa-sync-alt mr-2"></i>
                        <p>โอนเทรดบาทซื้อขาย</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin') )
                <li class="nav-item">
                    <a href="{{ route('return-point-balances.index') }}" class="nav-link {{Request::is('return-point-balances/*')? "active" : ""}}">
                        <i class="nav-icon fas fa-list-ul mr-2"></i>
                        <p>รายการปรับปรุงเทรดบาท</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin|sub_admin') )
                <li class="nav-item">
                    <a href="{{ route('auctions.index') }}" class="nav-link {{Request::is('auctions')? "active" : ""}}">
                        <i class="nav-icon fas fa-gavel mr-2"></i>
                        <p>ประมูลสินค้า</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin') )
                <li class="nav-item">
                    <a href="{{ route('branners.index',['after_login'=>'n']) }}" class="nav-link">
                        <i class="nav-icon far fa-images mr-2"></i>
                        <p>ภาพสไลด์</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('branners.index',['after_login'=>'y']) }}" class="nav-link">
                        <i class="nav-icon far fa-images mr-2"></i>
                        <p>ภาพสไลด์หลัง Login</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin') )
                <li class="nav-item">
                    <a href="{{ route('shop-types.index') }}" class="nav-link {{Request::is('shop-types')? "active" : ""}}">
                        <i class="nav-icon fas fa-tag mr-2"></i>
                        <p>ประเภทร้านค้า</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin') )
                <li class="nav-item">
                    <a href="{{ route('sms.index') }}" class="nav-link {{Request::is('sms')? "active" : ""}}">
                        <i class="nav-icon far fa-envelope mr-2"></i>
                        <p>SMS</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin') )
                <li class="nav-item">
                    <a href="{{ route('branches.about.index',1) }}" class="nav-link {{Request::is('branches/*')? "active" : ""}}">
                        <i class="nav-icon fas fa-align-left mr-2"></i>
                        <p>About Barter Advance</p>
                    </a>
                </li>
                @endif

                @if( Auth::user()->isRoleAccess('admin') )
                <li class="nav-item">
                    <a href="{{ route('branchs.index') }}" class="nav-link {{Request::is('setting')? "active" : ""}}">
                        <i class="nav-icon fas fa-cog mr-2"></i>
                        <p>ตั้งค่า</p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>