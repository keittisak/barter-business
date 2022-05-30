<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-user-circle" style="font-size: 1.5rem;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="{{ (Auth::user()->image) ? Auth::user()->image : asset('assets/images/img_profile_default.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                {{ Auth::user()->full_name()}}
                                <span class="float-right text-sm text-success"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm text-muted">Administrator</p>
                        </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{route('logout.process')}}" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> ออกจากระบบ
                </a>
            </div>
        </li>
    </ul>
</nav>