@extends('layouts.admin.app', [ 'page' => 'user', 'title'=> $title ])

@push('css')

@endpush

@section('content')

<div class="page">
    <div>
        <header class="xblue xaccent-2 xbg-dark-blue bg-blue relative">
            <div class="container-fluid text-white">
                <div class="row p-t-b-10 ">
                    <div class="col">
                        <div class="pb-3">
                            <div class="image mr-3  float-left">
                                <img class="user_avatar no-b no-p" src="{{$user->avatar}}" alt="User Image">
                            </div>
                            <div>
                                <h6 class="p-t-10">{{$user->user_document?$user->user_document->full_name:''}}</h6>
                                {{$user->user_document?$user->user_document->email:''}}

                                @php
                                    // dd( iconv( 'UTF-8','ASCII', base64_decode($user->pin) ) );  
                                    // ( $user->pin ); 
                                @endphp

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <ul class="nav nav-material nav-material-white responsive-tab" id="v-pills-tab" role="tablist">
                        <li>
                            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home"><i class="icon icon-user-circle"></i>Details</a>
                        </li>
                        {{-- <li>
                            <a class="nav-link" id="v-pills-details-tab" data-toggle="pill" href="#v-pills-details" role="tab" aria-controls="v-pills-details" aria-selected="false"><i class="icon icon-user-circle"></i>Details</a>
                        </li> --}}
                        <li>
                            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false"><i class="icon icon-cog"></i>Edit Profile</a>
                        </li>
                    </ul>
                </div>

            </div>
        </header>

        <div class="container-fluid animatedParent animateOnce my-3">
            <div class="animated fadeInUpShort">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <div class="row">
                            <div class="col-md-3 px-0">
                                <div class="card ">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            {{-- <i class="icon icon-smartphone text-primary"></i><strong class="s-12">Phone</strong> <span class="float-right s-12"> {{$user->user_document?'0'.$user->user_document->mobile_number:'-'}} </span> --}}
                                            <i class="icon icon-smartphone text-primary"></i><strong class="s-12">Phone</strong> <span class="float-right s-12"> {{'0'.$user->phone_number}} </span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="icon icon-drivers-license text-secondary"></i><strong class="s-12">Cnic</strong> <span class="float-right s-12"> {{$user->user_document?'0'.$user->user_document->cnic_number:'-'}} </span>
                                        </li>
                                        <li class="list-group-item">
                                            {{-- <i class="icon icon-mail text-success"></i><strong class="s-12">Email</strong> <span class="float-right s-12">{{$user->user_document?$user->user_document->email:'-'}}</span> --}}
                                            <i class="icon icon-mail text-success"></i><strong class="s-12">Email</strong> <span class="float-right s-12">{{$user->user_document?$user->user_document->email:$user->email}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="icon icon-navigation text-warning"></i><strong class="s-12">Location</strong> <span class="float-right s-12">{{($user->user_document&&$user->user_document->city)?$user->user_document->city->name.', '.$user->user_document->city->country->name:'-'}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="icon icon-bank text-danger"></i><strong class="s-12">Bank</strong> <span class="float-right text-right s-12" style="width: 60%;">{{($user->user_document&&$user->user_document->bank)?$user->user_document->bank->name:'-'}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="icon icon-bank text-danger"></i><strong class="s-12">Roundup Bank</strong> <span class="float-right text-right s-12" style="width: 60%;">{{ $user->customer?$user->customer->account->getRoundupConfiguredBank():'N/A' }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="icon icon-date_range text-secondary"></i><strong class="s-12">Joined On</strong> <span class="float-right s-12">{{$user->m_date_joined}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="icon icon-sign-in text-secondary"></i><strong class="s-12">Last Login</strong> <span class="float-right s-12">{{$user->last_login}}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card mt-3 mb-3">
                                    <div class="card-header bg-white">
                                        <strong class="card-title">Overall Status</strong>
                                    </div>
                                    {{-- <div class="card-header bg-white">
                                        <strong class="card-title">Heading</strong>
                                    </div> --}}
                                    <div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item py-2">
                                                <h6 class="d-flex justify-content-between">Account
                                                    <span> {!! $user->getStatusHtml() !!} </span>
                                                </h6>
                                            </li>
                                            <li class="list-group-item py-2">
                                                <h6 class="d-flex justify-content-between">Phone Verification
                                                    <span> {!! $user->getIsPhoneVerifiedHtml() !!} </span>
                                                </h6>
                                            </li>
                                            <li class="list-group-item py-2">
                                                <h6 class="d-flex justify-content-between">Email Verification
                                                    <span> {!! $user->getIsEmailVerifiedHtml() !!} </span>
                                                </h6>
                                            </li>
                                            <li class="list-group-item py-2">
                                                <h6 class="d-flex justify-content-between">KYC
                                                    <span> {!! $user->customer?$user->customer->getIsKycCompletedHtml():'N/A' !!} </span>
                                                </h6>
                                            </li>
                                            <li class="list-group-item py-2">
                                                <h6 class="d-flex justify-content-between">Roundup
                                                    <span> {!! $user->customer?$user->customer->account->getRoundupStatusHtml():'N/A' !!} </span>
                                                </h6>
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-9 pr-0">
                                
                                <div class="row">

                                    <div class="col-lg-4">
                                        <div class="card r-3">
                                            <div class="p-4">
                                                <div class="float-right">
                                                    <span class="icon-goals text-light-blue s-48"></span>
                                                </div>
                                                <div class="counter-title">Total Goals</div>
                                                <h5 class="sc-counterx mt-3">{!!$user->getTotalGoalHtml()!!}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card r-3">
                                            <div class="p-4">
                                                <div class="float-right">
                                                    <span class="icon-investment-3 text-light-blue s-48"></span>
                                                </div>
                                                <div class="counter-title "> Total Invested Amount </div>
                                                <h5 class="sc-counterx mt-3">Rs {{number_format($user->account->total_invested_amount)}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="white card">
                                            <div class="p-4">
                                                <div class="float-right">
                                                    <span class="icon-investment-1 text-light-blue s-48"></span>
                                                </div>
                                                <div class="counter-title">Total Withdrawn</div>
                                                <h5 class="sc-counterx mt-3">Rs {{number_format($user->account->total_redemption_amount)}}</h5>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <div class="card ">
                                            <div class="card-header white">
                                                <h6>Total Invested Amount Chart</h6>
                                            </div>
                                            <div class="card-body">
                                                <div id="investmentPieChart" style="height:350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row my-3">

                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header white d-flex justify-content-between align-items-center">
                                                <strong> Recent Transactions </strong>
                                                <a href="{{route('admin.transaction.logs')}}" class="btn btn-primary btn-xs">View All</a>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="slimScroll b-b" data-height="500">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover earning-box">
                                                            <thead class="no-b">
                                                                <tr>
                                                                    <th class="pl-4">Goal</th>
                                                                    <th>Amount</th>
                                                                    <th>Status</th>
                                                                    <th>Timestamp</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($transactions as $key=>$trx)
                                                                    <tr class="no-bx">
                                                                        <td class="pl-4">
                                                                            <h6>{{$trx->user_goal_config->name}}</h6>
                                                                            <small class="text-muted">{{$trx->getActionName()}}</small>
                                                                        </td>
                                                                        <td>{{$trx->m_invested_amount}}</td>
                                                                        <td>
                                                                            {!! $trx->getStatusHtml() !!}
                                                                        </td>
                                                                        <td>
                                                                            <span> <i class="icon icon-data_usage"></i> {{ Carbon\Carbon::parse($trx->created_at)->diffForHumans(now()) }} </span>
                                                                            <br>
                                                                            <span> <i class="icon icon-timer"></i> {{$trx->m_created_at}}</span>
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td class="text-center" colspan="4">No Record Found</td>
                                                                    </tr>
                                                                @endforelse
                                                           
                                                            </tbody>
                                                        </table>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                

                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="v-pills-details" role="tabpanel" aria-labelledby="v-pills-details-tab">
                        
                        <div class="form-row">

                            <div class="form-group col-5 mb-3">
                                <label for="label" class="w-100 text-left">First Name </label>
                                <input type="text" class="form-control" value="{{$user->user_document?$user->user_document->first_name:'-'}}" readOnly>
                            </div>
                            <div class="form-group col-5 mb-3">
                                <label for="label" class="w-100 text-left">Last Name </label>
                                <input type="text" class="form-control" value="{{$user->user_document?$user->user_document->last_name:'-'}}" readOnly>
                            </div>
                            <div class="form-group col-5 mb-3">
                                <label for="label" class="w-100 text-left">Phone Number </label>
                                <input type="text" class="form-control" value="{{$user->phone_number}}" readOnly>
                            </div>
                            <div class="form-group col-5 mb-3">
                                <label for="label" class="w-100 text-left">Email </label>
                                <input type="text" class="form-control" value="{{$user->user_document?$user->user_document->email:'-'}}" readOnly>
                            </div>
                            <div class="form-group col-10 mb-3">
                                <label for="label" class="w-100 text-left">Permanent Address </label>
                                <input type="text" class="form-control" value="{{$user->user_document?$user->user_document->permanent_address:'-'}}" readOnly>
                            </div>
                            <div class="form-group col-10 mb-3">
                                <label for="label" class="w-100 text-left">Current Address </label>
                                <input type="text" class="form-control" value="{{$user->user_document?$user->user_document->current_address:'-'}}" readOnly>
                            </div>
                            <div class="form-group col-10 mb-3">
                                <label for="label" class="w-100 text-left">City </label>
                                <input type="text" class="form-control" value="{{($user->user_document&&$user->user_document->city)?$user->user_document->city->name.', '.$user->user_document->city->country->name:''}}" readOnly>
                            </div>

                            <div class="form-group col-5 mb-3">
                                <label for="label" class="w-100 text-left">Bank </label>
                                <input type="text" class="form-control" value="{{($user->user_document&&$user->user_document->bank)?$user->user_document->bank->name:''}}" readOnly>
                            </div>
                            <div class="form-group col-5 mb-3">
                                <label for="label" class="w-100 text-left">Account Number </label>
                                <input type="text" class="form-control" value="{{$user->user_document?$user->user_document->account_number:'-'}}" readOnly>
                            </div>
                            <div class="form-group col-10 mb-3">
                                <label for="label" class="w-100 text-left">IBAN </label>
                                <input type="text" class="form-control" value="{{$user->user_document?$user->user_document->iban:'-'}}" readOnly>
                            </div>
                            


                        </div>
                

                    </div>

                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">

                            <form class="form-horizontal" action="{{$update_status_url}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Status</label>

                                    <div class="col-sm-10">
                                        <select name="status" id="status" class="form-control">
                                                <option value="{{App\Models\User::STATUS_ACTIVE}}" @if( $user->is_active == App\Models\User::STATUS_ACTIVE ) selected @endif>Active</option>
                                                <option value="{{App\Models\User::STATUS_INACTIVE}}" @if( $user->is_active == App\Models\User::STATUS_INACTIVE ) selected @endif>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <label>
                                            Note: Status will be checked when user login.
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-danger">Submit</button>
                                    </div>
                                </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>

    </div>
</div>


@endsection


@push('scripts')
<script type="text/javascript" src="https://fastly.jsdelivr.net/npm/echarts@5.4.1/dist/echarts.min.js"></script>

<script type="text/javascript">

    var dom = document.getElementById('investmentPieChart');
    var myChart = echarts.init(dom, null, {
      renderer: 'canvas',
      useDirtyRect: false
    });
    var app = {};
    
    var option;

    option = {
        title: {
            text: ("{{$user->account->m_total_invested_amount}}"),
            subtext: "Total Contributions",
            x: "center",
            y: "center",
            itemGap: 0,
            textStyle: {
                color: "#15003d",
                fontSize: 16,
                fontWeight: "bolder"
            }
        },
        // title: {
        //     text: 'Total invested amount chart',
        //     subtext: '',
        //     left: 'center'
        // },
        tooltip: {
            // show: true,
            // showContent: true,
            // alwaysShowContent: true,
            trigger: 'item',
            formatter: "{b} <br /> {a} {c} ({d}%)"
        },
        // legend: {
        //     orient: 'vertical',
        //     left: 'left'
        // },
        legend: {
            x: "center",
            y: "bottom",
            data: ["Topup", "Recurring", "Roundup"]
        },
        series: [
            {
            name: 'Rs',
            type: 'pie',
            // radius: '60%',
            radius: ['50%', '70%'],
            
            data: [
                { 
                    value: "{{round($user->account->total_invested_through_topup)}}", 
                    name: 'Topup', 
                    itemStyle: {
                        color: '#99B5F5'
                        ,borderRadius: 5,
                    borderColor: '#fff',
                    borderWidth: 1
                    },
                },
                { 
                    value: "{{round($user->account->total_invested_through_recurring)}}", 
                    name: 'Recurring',
                    itemStyle: {
                        color: '#FEE9BA'
                    ,borderRadius: 5,
                    borderColor: '#fff',
                    borderWidth: 1
                    }
                },
                { 
                    value: "{{round($user->account->total_invested_through_roundup)}}", 
                    name: 'Roundup',
                    itemStyle: {
                        color: '#F87EBC'
                        ,borderRadius: 5,
                    borderColor: '#fff',
                    borderWidth: 1
                    }
                },
            ],
            emphasis: {
                itemStyle: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                },
                label: {
                    formatter: function (params) {
                                            var val = "Rs "+(params.value);
                                            return val;
                                        },
                }
            }
            }
        ]
    };

    if (option && typeof option === 'object') {
      myChart.setOption(option);
    }

    window.addEventListener('resize', myChart.resize);
</script>

@endpush