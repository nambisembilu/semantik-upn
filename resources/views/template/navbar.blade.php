<div class="navbar navbar-expand-xl navbar-static shadow">
    <div class="container-fluid">
        <div class="d-flex d-xl-none me-2">
            <button type="button" class="navbar-toggler sidebar-mobile-main-toggle rounded-pill">
                <i class="ph-list"></i>
            </button>
        </div>
        <div class="navbar-brand flex-1">
            <a href="{{route('home')}}" class="d-inline-flex align-items-center">
                <img src="{{ asset(getenv('APP_LOGO_ICON')) }}" class="w-32px h-32px" alt="">
                <img src="{{ asset(getenv('APP_LOGO_SECOND_ICON')) }}" class="d-none d-sm-inline-block h-24px invert-dark ms-1" alt="">
            </a>
        </div>

        <div class="d-flex w-100 w-xl-auto overflow-auto overflow-xl-visible scrollbar-hidden border-top border-top-xl-0 order-1 order-xl-0 pt-2 pt-xl-0 mt-2 mt-xl-0">
            @if(!empty(session('role_name')))
                <ul class="nav gap-1 justify-content-center flex-nowrap flex-xl-wrap mx-auto">
                    <li class="nav-item">
                        <a href="{{route('home')}}" class="navbar-nav-link rounded">
                            <i class="ph-house me-2"></i>
                            Home
                        </a>
                    </li>
                    @php
                        $role=\App\Models\Master\Role::where('name',session('role_name'))->first();
                        $role_menus=\App\Models\Master\RoleMenu::where('role_id',$role->id)->get();
                        $modules = \App\Models\Master\Module::whereIn('id',\App\Models\Master\Menu::whereIn('id',$role_menus->pluck('menu_id'))->get()->pluck('module_id'))->orderBy('rank')->get();
                    @endphp
                    @foreach($modules as $module)
                        <li class="nav-item">
                            <a href="{{route($module->path)}}" class="navbar-nav-link rounded">
                                <i class="{{$module->icon}} me-2"></i>
                                {{$module->title}}
                            </a>
                        </li>
                    @endforeach

                </ul>
            @endif
        </div>

        <ul class="nav gap-1 flex-xl-1 justify-content-end order-0 order-xl-1">

            <li class="nav-item">
                <a href="#" class="navbar-nav-link navbar-nav-link-icon rounded-pill" data-bs-toggle="offcanvas" data-bs-target="#notifications">
                    <i class="ph-bell"></i>
                    <span class="badge bg-yellow text-black position-absolute top-0 end-0 translate-middle-top zindex-1 rounded-pill mt-1 me-1">1</span>
                </a>
            </li>

            <li class="nav-item nav-item-dropdown-xl dropdown">
                <a href="#" class="navbar-nav-link align-items-center rounded-pill p-1" data-bs-toggle="dropdown">
                    <div class="status-indicator-container">
                        <img src="{{ asset('assets/images/icons/user.png') }}" class="w-32px h-32px rounded-pill" alt="">
                        <span class="status-indicator bg-success"></span>
                    </div>
                    <span class="d-none d-md-inline-block mx-md-2">{{ucfirst(session('user_name'))}}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-end">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        {{--                        <a class="dropdown-item">--}}
                        {{--                            <b class="me-1">{{session('role')}}</b>--}}
                        {{--                        </a>--}}
                        <a class="dropdown-item">
                            <b class="me-1">Hak Akses</b> {{session('role_name')}}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{route('modules.account.profile.index')}}" class="dropdown-item">
                            <i class="ph-user-circle me-2"></i>
                            Profil
                        </a>
                        <a href="{{route('modules.account.profile.new-password')}}" class="dropdown-item">
                            <i class="ph-lock me-2"></i>
                            Password
                        </a>
                        @if(!empty(session("role_name")) && ((session("role_name") == "Superadmin") || session("role_name") == "SuperadminUK"))
                            <a href="{{route('modules.account.loginasother.index')}}" class="dropdown-item">
                                <i class="ph-lock me-2"></i>
                                Login Sebagai Pegawai Lain
                            </a>
                        @endif

                        <div class="dropdown-divider"></div>
                        <a href="{{route('logout')}}" onclick="event.preventDefault();this.closest('form').submit();"" class="dropdown-item">
                        <i class="ph-sign-out me-2"></i>
                        Logout
                        </a>
                    </form>

                </div>
            </li>
        </ul>
    </div>
</div>
