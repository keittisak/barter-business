<nav class="nav-bottom px-md-7 px-lg-7 {{ Request::is('users/sign_up') || Request::is('users/sign_in') ? "" : ""}}">
    <a href="{{ route('front.home') }}" class="nav-link">
        <img src="{{ asset('assets/images/logo_v3_60.jpg') }}" class="fa logo-home" alt="Barter Advance">
        <span class="text">หน้าแรก</span>
    </a>

    <a href="{{ route('front.shops.category') }}" class="nav-link {{Request::is('category')? "active" : ""}}">
        <i class="icon fas fa-store"></i><span class="text">ร้านค้าและบริการ</span>
    </a>
    @guest
    <a href="{{ route('front.users.form') }}" class="nav-link {{Request::is('users/sign_up')? "active" : ""}}">
        <i class="icon fas fa-user-plus"></i><span class="text">สมัครสมาชิก</span>
    </a>
    <a href="{{ route('front.users.login.form') }}" class="nav-link {{Request::is('users/sign_in')? "active" : ""}}">
        <i class="icon fas fa-sign-in-alt"></i><span class="text">เข้าสู่ระบบ</span>
    </a>
    @endguest
    @auth
    {{-- <a href="{{ route('front.users.point.transfer.lists') }}" class="nav-link {{Request::is('users/transfer/lists')? "active" : ""}}">
        <i class="icon far fa-list-alt"></i><span class="text">รายการ</span>
    </a> --}}
    <a href="{{ route('front.users.profile') }}" class="nav-link {{Request::is('users/profile')? "active" : ""}}">
        <i class="icon fa fa-user"></i><span class="text">โปรไฟล์</span>
    </a>
    <a href="{{ route('front.users.logout.process') }}" class="nav-link">
        <i class="icon fas fa-sign-out-alt"></i><span class="text">ออกจากระบบ</span>
    </a>
    @endauth
</nav>