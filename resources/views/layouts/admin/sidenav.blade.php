@php
$pageName = null;
if( isset($page) ){
    $pageName = $page;
}
@endphp
<section class="sidebar">
    <div class="xw-80px mt-3 mb-3 xml-3 text-center">
        <a href="{{route('home')}}">
            {{-- <img src="{{asset('admin/assets/img/logo/logo_cyan.png')}}" alt="Auction" style="height:48px;"> --}}
            <h2 class="text-upper">Auction</h2>
        </a>
    </div>
    <ul class="sidebar-menu hover-dark">
        <li class="header"><strong>MAIN NAVIGATION</strong></li>
        <li class="@if( $pageName == 'dashboard' ) nav-active @endif">
            <a href="{{route('home')}}">
                <i class="icon icon-dashboard2 s-18"></i> 
                <span>Dashboard</span>
            </a>
        </li>

        @can(App\Models\Category::LIST_PERMISSION)
        <li class="@if( $pageName == 'category_management' ) nav-active @endif">
            <a href="{{route('admin.category')}}">
                <i class="icon icon-users s-18"></i> 
                <span>Category Management</span> 
            </a>
        </li>
        @endif

        @can(App\Models\User::LIST_PERMISSION)
        <li class="@if( $pageName == 'user_management' ) nav-active @endif">
            <a href="{{route('admin.users')}}">
                <i class="icon icon-users s-18"></i> 
                <span>Admin Users Management</span> 
            </a>
        </li>
        @endif

        @if( Gate::any([
            App\Models\Activity\ActivityLog::LIST_PERMISSION,
        ]) )
        <li class="treeview @if( in_array($pageName,['activity_log']) ) active @endif ">
            <a href="#">
                <i class="icon icon-clipboard-text2 light-green-text s-18"></i>
                Logs
                <i class="icon icon-angle-left s-18 pull-right"></i>
            </a>
            <ul class="treeview-menu">

                @can(App\Models\Activity\ActivityLog::LIST_PERMISSION)
                <li class="@if( $pageName == 'activity_log' ) nav-active @endif">
                    <a href="{{ route('admin.activity.logs')}}">
                        <i class="icon icon-circle-o"></i>Activity Logs 
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endif

        @if( Gate::any([
            App\Models\Role::LIST_PERMISSION,
            App\Models\Setting::LIST_PERMISSION,
        ]) )
        <li class="treeview @if( in_array($pageName,['role','setting']) ) active @endif ">
            <a href="#">
                <i class="icon icon-gear light-green-text s-18"></i>
                Configurations
                <i class="icon icon-angle-left s-18 pull-right"></i>
            </a>
            <ul class="treeview-menu">

                

                @can(App\Models\Role::LIST_PERMISSION)
                <li class="@if( $pageName == 'role' ) nav-active @endif">
                    <a href="{{ route('admin.roles')}}">
                        <i class="icon icon-circle-o"></i>Roles Management
                    </a>
                </li>
                @endcan

                @can(App\Models\Setting::LIST_PERMISSION)
                <li class="@if( $pageName == 'setting' ) nav-active @endif">
                    <a href="{{ route('admin.settings.edit')}}">
                        <i class="icon icon-circle-o"></i>Settings
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endif

        <li style="height: 200px;">

        </li>

    </ul>
</section>