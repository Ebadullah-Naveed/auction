@php
$pageName = null;
if( isset($page) ){
    $pageName = $page;
}
@endphp
<section class="sidebar">
    <div class="xw-80px mt-3 mb-3 xml-3 text-center">
        <a href="{{route('home')}}">
            {{-- <img src="{{asset('admin/assets/img/logo/logo_orange.png')}}" alt="Trikl" height="48"> --}}
            <img src="{{asset('admin/assets/img/logo/logo_cyan.png')}}" alt="Trikl" style="height:48px;">
            {{-- <h2 class="text-upper">Trikl</h2> --}}
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

        @can(App\Models\UserDocument::LIST_PERMISSION)
        <li class="@if( $pageName == 'kyc' ) nav-active @endif">
            <a href="{{route('admin.kyc')}}">
                <i class="icon icon-documents2 s-18"></i> 
                <span>KYC Management</span> 
            </a>
        </li>
        @endif

        @can(App\Models\UserDocument::AMC_LIST_PERMISSION)
        <li class="@if( $pageName == 'amc_kyc' ) nav-active @endif">
            <a href="{{route('admin.kyc.amc')}}">
                <i class="icon icon-documents2 s-18"></i> 
                <span>AMC KYC Management</span> 
            </a>
        </li>
        @endif

        @if( Gate::any([
            App\Models\Customer::LIST_PERMISSION,
            App\Models\UserGoal::LIST_PERMISSION,
            App\Models\UserRoundUp::LIST_PERMISSION,
            App\Models\WhitelistUser::LIST_PERMISSION,
            App\Models\ViralLoop\WixUser::LIST_PERMISSION,
        ]) )
        <li class="treeview @if( in_array($pageName,['user','user_goal','user_roundup','wix_user','whitelist_user','delete_test_user']) ) active @endif ">
            <a href="#">
                <i class="icon icon-users light-green-text s-18"></i>
                Users Management
                <i class="icon icon-angle-left s-18 pull-right"></i>
            </a>
            <ul class="treeview-menu">

                @can(App\Models\Customer::LIST_PERMISSION)
                <li class="@if( $pageName == 'user' ) nav-active @endif">
                    <a href="{{ route('admin.customers')}}">
                        <i class="icon icon-circle-o"></i>Customers
                    </a>
                </li>
                @endcan

                @can(App\Models\UserGoal::LIST_PERMISSION)
                <li class="@if( $pageName == 'user_goal' ) nav-active @endif">
                    <a href="{{ route('admin.user.goals')}}">
                        <i class="icon icon-circle-o"></i>Goals 
                    </a>
                </li>
                @endcan

                @can(App\Models\UserRoundUp::LIST_PERMISSION)
                <li class="@if( $pageName == 'user_roundup' ) nav-active @endif">
                    <a href="{{ route('admin.user.roundups')}}">
                        <i class="icon icon-circle-o"></i>Roundup 
                    </a>
                </li>
                @endcan

                @can(App\Models\ViralLoop\WixUser::LIST_PERMISSION)
                <li class="@if( $pageName == 'wix_user' ) nav-active @endif">
                    <a href="{{route('admin.wix.users')}}">
                        <i class="icon icon-circle-o"></i>Wix Users 
                    </a>
                </li>
                @endif

                @can(App\Models\WhitelistUser::LIST_PERMISSION)
                <li class="@if( $pageName == 'whitelist_user' ) nav-active @endif">
                    <a href="{{route('admin.whitelist.users')}}">
                        <i class="icon icon-circle-o"></i>Whitelist Users 
                    </a>
                </li>
                @endif

                @if( config('app.env') == 'local' )
                <li class="@if( $pageName == 'delete_test_user' ) nav-active @endif">
                    <a href="{{route('admin.users.delete.test')}}">
                        <i class="icon icon-circle-o"></i>Delete Users 
                    </a>
                </li>
                @endif

            </ul>
        </li>
        @endif

        @can(App\Models\AssetManagement\Fund::LIST_PERMISSION)
        <li class="@if( $pageName == 'fund' ) nav-active @endif">
            <a href="{{route('admin.funds')}}">
                <i class="icon icon-money-1 s-18"></i> 
                <span>Funds Management</span>
            </a>
        </li>
        @endcan

        @can(App\Models\AssetManagement\Asset::LIST_PERMISSION)
        <li class="@if( $pageName == 'asset' ) nav-active @endif">
            <a href="{{route('admin.assets')}}">
                <i class="icon icon-server s-18"></i> 
                <span>Assets Management</span>
            </a>
        </li>
        @endcan

        @can(App\Models\GoalSuggestion::LIST_PERMISSION)
        <li class="@if( $pageName == 'goal_suggestion' ) nav-active @endif">
            <a href="{{route('admin.goal.suggestion')}}">
                <i class="icon icon-goals s-18"></i> 
                <span>Goal Suggestion</span> 
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

        @can(App\Models\Content::LIST_PERMISSION)
        <li class="@if( $pageName == 'content' ) nav-active @endif">
            <a href="{{route('admin.content')}}">
                <i class="icon icon-file-text-o s-18"></i> 
                <span>CMS Management</span>
            </a>
        </li>
        @endif

        @can(App\Models\Permission::LIST_ADMIN_NOTIFICATION)
        <li class="@if( $pageName == 'admin_notification' ) nav-active @endif">
            <a href="{{route('admin.notification')}}">
                <i class="icon icon-notifications_active s-18"></i> 
                <span>Notifications</span>
            </a>
        </li>
        @endif

        @if( Gate::any([
            App\Models\ParserLog::LIST_PERMISSION,
            App\Models\Activity\Transaction::LIST_PERMISSION,
            App\Models\Activity\RequestLog::LIST_PERMISSION,
            App\Models\Activity\ActivityLog::LIST_PERMISSION,
        ]) )
        <li class="treeview @if( in_array($pageName,['parser_log','activity_log','request_log','transaction_log','third_party_log']) ) active @endif ">
            <a href="#">
                <i class="icon icon-clipboard-text2 light-green-text s-18"></i>
                Logs
                <i class="icon icon-angle-left s-18 pull-right"></i>
            </a>
            <ul class="treeview-menu">

                @can(App\Models\ParserLog::LIST_PERMISSION)
                <li class="@if( $pageName == 'parser_log' ) nav-active @endif">
                    <a href="{{ route('admin.parser.logs')}}">
                        <i class="icon icon-circle-o"></i>Parser Logs
                    </a>
                </li>
                @endcan

                @can(App\Models\Activity\Transaction::LIST_PERMISSION)
                <li class="@if( $pageName == 'transaction_log' ) nav-active @endif">
                    <a href="{{route('admin.transaction.logs')}}">
                        <i class="icon icon-circle-o"></i>Transaction Logs 
                    </a>
                </li>
                @endif

                @can(App\Models\Activity\RequestLog::LIST_PERMISSION)
                <li class="@if( $pageName == 'request_log' ) nav-active @endif">
                    <a href="{{ route('admin.request.logs')}}">
                        <i class="icon icon-circle-o"></i>Request Logs 
                    </a>
                </li>
                @endcan

                @can(App\Models\Activity\ActivityLog::LIST_PERMISSION)
                <li class="@if( $pageName == 'activity_log' ) nav-active @endif">
                    <a href="{{ route('admin.activity.logs')}}">
                        <i class="icon icon-circle-o"></i>Activity Logs 
                    </a>
                </li>
                @endcan

                @can(App\Models\Activity\ThirdPartyLog::LIST_PERMISSION)
                <li class="@if( $pageName == 'third_party_log' ) nav-active @endif">
                    <a href="{{ route('admin.third.party.logs')}}">
                        <i class="icon icon-circle-o"></i>Third Party Logs 
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endif

        @if( Gate::any([
            App\Models\RoundUpFactor::LIST_PERMISSION,
            App\Models\Role::LIST_PERMISSION,
            App\Models\Setting::LIST_PERMISSION,
        ]) )
        <li class="treeview @if( in_array($pageName,['role','setting','roundup_factor','cronjob']) ) active @endif ">
            <a href="#">
                <i class="icon icon-gear light-green-text s-18"></i>
                Configurations
                <i class="icon icon-angle-left s-18 pull-right"></i>
            </a>
            <ul class="treeview-menu">

                @can(App\Models\RoundUpFactor::LIST_PERMISSION)
                <li class="@if( $pageName == 'roundup_factor' ) nav-active @endif">
                    <a href="{{ route('admin.roundup.factors')}}">
                        <i class="icon icon-circle-o"></i>Roundup Factors
                    </a>
                </li>
                @endcan

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
                <li class="@if( $pageName == 'cronjob' ) nav-active @endif">
                    <a href="{{ route('admin.cronjob.listing')}}">
                        <i class="icon icon-circle-o"></i>Cronjobs
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endif

        @can(App\Models\City::LIST_PERMISSION)
        <li class="@if( $pageName == 'city' ) nav-active @endif">
            <a href="{{route('admin.cities')}}">
                <i class="icon icon-clipboard-text2 s-18"></i> 
                <span>City Management</span>
            </a>
        </li>
        @endcan

        @can(App\Models\IncomeSource::LIST_PERMISSION)
        <li class="@if( $pageName == 'income_source' ) nav-active @endif">
            <a href="{{route('admin.income.source')}}">
                <i class="icon icon-work s-18"></i> 
                <span>Occupation Management</span>
            </a>
        </li>
        @endcan

        <li style="height: 200px;">

        </li>

    </ul>
</section>