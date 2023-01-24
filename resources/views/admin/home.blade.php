@extends('layouts.admin.app', [ 'page' => 'dashboard', 'title'=>'Home'])

@push('css')

@endpush

@section('content')

{{-- <h1>
    {{ auth()->user()->name }} ({{ auth()->user()->role->name }}) logged in.
</h1> --}}

<div class="row">
    <div class="col-lg-3">
        <div class="counter-box p-40 gradient  text-white shadow2 r-5">
            <div class="float-right">
                <span class="icon icon-startup s-48"></span>
            </div>
            <div class="sc-counter s-36">{{$platinum_club_members}}</div>
            <h6 class="counter-title">Platinum Club Members</h6>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="counter-box p-40 white shadow2 r-5">
            <div class="float-right">
                <span class="icon icon-trophy7 s-48"></span>
            </div>
            <div class="sc-counter s-36">{{$verified_waitlist_users}}</div>
            <h6 class="counter-title">Verified Waitlist Users</h6>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="counter-box p-40 white shadow2 r-5">
            <div class="float-right">
                <span class="icon icon-people_outline s-48"></span>
            </div>
            <div class="sc-counter s-36">{{$total_users}}</div>
            <h6 class="counter-title">Total Users</h6>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="counter-box p-40 white shadow2 r-5">
            <div class="float-right">
                <span class="icon icon-target-12 s-48"></span>
            </div>
            <div class="sc-counter s-36">{{$total_waitlist_users}}</div>
            <h6 class="counter-title">Total Waitlist Users</h6>
        </div>
    </div>
    
</div>

<div class="row my-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header white">
                <strong> Top 10 Users </strong>
            </div>
            <div class="card-body p-0">
                <div class="slimScroll b-b" data-height="390">
                    <div class="table-responsive">
                        <table class="table table-hover earning-box">
                            <thead class="no-b">
                            <tr>
                                <th class="text-center"></th>
                                <th>Name</th>
                                <th>Total Goals</th>
                                <th>Investments</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($top_users as $key=>$item)
                                <tr>
                                    <td class="w-10 text-center">
                                        <a href="{{$item->getUserProfileDetailUrl()}}" class="instagram ml-2 mr-1 p-2 circle">
                                            <b>#{{($key+1)}}</b>
                                        </a>
                                    </td>
                                    <td>
                                        <h6>{{$item->user_document->full_name}}</h6>
                                        <small class="text-muted">Joined on: {{$item->user->m_date_joined}} </small>
                                    </td>
                                    <td>{!!$item->getTotalGoalHtml('eager')!!}</td>
                                    <td>
                                        <h6 class="m-0 p-0">
                                            {{$item->m_total_invested_amount}} 
                                        </h6>
                                        <label class="badge badge-success">Return {{$item->m_current_return}}</label>
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

    <div class="col-md-6">
        <div class="card ">
            <div class="card-header white">
                <h6>Total Invested amount by users chart</h6>
            </div>
            <div class="card-body">
                <div id="investmentPieChart" style="height:350px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row my-3">
    <div class="col-md-6">
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
                                    <th class="pl-4">User</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $key=>$trx)
                                    <tr class="no-bx">
                                        {{-- <td class="w-10">
                                            <img src="assets/img/demo/shop/s1.png" alt="">
                                        </td> --}}
                                        <td class="pl-4">
                                            <h6>{{$trx->account->user_document->full_name}}</h6>
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

    <div class="col-md-6">
        <div class="card">
            <div class="card-header white d-flex justify-content-between align-items-center">
                <strong> Lastest OTPs </strong>
                <a href="{{route('admin.customers')}}" class="btn btn-primary btn-xs">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="slimScroll b-b" data-height="500">
                    <div class="table-responsive">
                        <table class="table table-hover earning-box">
                            <thead class="no-b">
                                <tr>
                                    <th class="pl-4">Phone</th>
                                    <th>OTP</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lastest_otp as $key=>$user)
                                    <tr class="no-bx">
                                        <td class="pl-4" width="40%">
                                            <h6>0{{$user->phone_number}}</h6>
                                            <small class="text-muted">{{$user->user_document?$user->user_document->full_name:''}}</small>
                                        </td>
                                        <td width="20%">{{$user->otp}}</td>
                                        <td width="40%">
                                            <span> <i class="icon icon-data_usage"></i> {{ Carbon\Carbon::parse($user->last_otp_time)->diffForHumans(now()) }} </span>
                                            <br>
                                            <span> <i class="icon icon-timer"></i> {{$user->m_last_otp_time}}</span>
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
            text: kFormatter("{{$total_invested_amount}}"),
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
            radius: ['40%', '70%'],
            
            data: [
                { 
                    value: "{{$total_invested_through_topup}}", 
                    name: 'Topup', 
                    itemStyle: {
                        color: '#99B5F5'
                        ,borderRadius: 5,
                    borderColor: '#fff',
                    borderWidth: 1
                    },
                },
                { 
                    value: "{{$total_invested_through_recurring}}", 
                    name: 'Recurring',
                    itemStyle: {
                        color: '#FEE9BA'
                    ,borderRadius: 5,
                    borderColor: '#fff',
                    borderWidth: 1
                    }
                },
                { 
                    value: "{{$total_invested_through_roundup}}", 
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