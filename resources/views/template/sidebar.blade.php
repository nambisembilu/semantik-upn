@php
    $module_segment=Request::segment(1);
@endphp
@if($module_segment)
    @php
        $role=\App\Models\Master\Role::where('name',session('role_name'))->first();
        $role_menus=\App\Models\Master\RoleMenu::where('role_id',$role->id)->get();
        $module = \App\Models\Master\Module::where('path',$module_segment)->orderBy('rank')->first();
        $groups =\App\Models\Master\Menu::select('group as name',\Illuminate\Support\Facades\DB::raw('avg(rank) as orders'))->where('module_id',$module->id)->whereIn('id',$role_menus->pluck('menu_id'))->groupBy('group')->orderBy('orders')->get();
        $path=Request::path();
    @endphp
    @if(count($groups)>0)
        <!-- Main sidebar -->
        <div class="sidebar sidebar-main sidebar-expand-xl">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Sidebar header -->
                <div class="sidebar-section">
                    <div class="sidebar-section-body d-flex justify-content-center pb-1">
                        <h5 class="sidebar-resize-hide flex-grow-1 my-auto">Menu </h5>

                        <div>
                            <button type="button" class="btn btn-light btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-xl-inline-flex">
                                <i class="ph-arrows-left-right"></i>
                            </button>

                            <button type="button" class="btn btn-light btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-xl-none">
                                <i class="ph-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /sidebar header -->


                <!-- Main navigation -->
                <div class="sidebar-section">
                    <ul class="nav nav-sidebar" data-nav-type="accordion">
                        <!-- Main -->
                        @foreach($groups as $group)
                            <li class="nav-item-header pt-0">
                                <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">{{$group->name}}</div>
                                <i class="ph-dots-three sidebar-resize-show"></i>
                            </li>
                            @php

                                $menus = \App\Models\Master\Menu::whereIn('id',$role_menus->pluck('menu_id'))->where('module_id',$module->id)->where('group',$group->name)->orderBy('rank')->get();
                            @endphp
                            @foreach($menus as $menu)
                                <li class="nav-item">
                                    <a href="{{url($menu->path)}}" class="nav-link @if(strpos($path, $menu->path)!== false) active @endif">
                                        <i class="{{$menu->icon}}"></i>
                                        <span>{{$menu->title}}</span>
                                    </a>
                                </li>
                            @endforeach
                        @endforeach

                    </ul>
                </div>
                <!-- /main navigation -->

            </div>
            <!-- /sidebar content -->

        </div>
        <!-- /main sidebar -->
    @endif
@endif
