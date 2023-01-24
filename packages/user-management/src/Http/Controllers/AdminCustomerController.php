<?php

namespace UserManagement\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\UserFormRequest;
use Helper;
use App\Models\Customer;
use App\Models\Account;
use App\Models\UserDocument;
use App\Models\UserDocumentStatus;
use App\Models\UserGoalConfig;
use App\Models\UserGoalSuggestion;
use App\Models\ScheduledPayment;
use App\Models\ParserLog;
use App\Models\UserRoundUp;
use App\Models\Activity\Transaction;
use App\Models\Activity\RequestLog;

class AdminCustomerController extends Controller
{
    
    public $module_title = 'Customers';
    public $listing_view = 'user-management::customers.index';
    public $add_edit_view = 'user-management::customers.add_edit';
    public $show_view = 'user-management::customers.show';

    public function index(){

        //checking edit permission
        if( ! Customer::canOpenList() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        $data['title'] = $this->module_title;
        // $data['roles'] = Role::getPortalRoles();
        // $data['add_url'] = route('admin.users.create');
        $data['listing_fetch_url'] = route('admin.customers.fetch');
        return view( $this->listing_view , $data );
    }

    public function fetch(Request $request) {
        
        $query = $this->getModelCollection($request);

        return Datatables::of($query)
        ->addColumn('view_btn', function($row){
            return $row->getCustomerViewBtnHtml();
        })
        ->addColumn('status_html', function($row){
            return $row->getStatusHtml();
        })
        ->addColumn('is_phone_verified_html', function($row){
            return $row->getIsPhoneVerifiedHtml();
        })
        ->addColumn('is_email_verified_html', function($row){
            return $row->getIsEmailVerifiedHtml();
        })
        ->addColumn('is_kyc_completed_html', function($row){
            return $row->customer?$row->customer->getIsKycCompletedHtml():'';
        })
        // ->addColumn('roundup_status_html', function($row){
        //     return $row->customer?$row->customer->account->getRoundupStatusHtml():'';
        // })
        ->addColumn('total_goal_html', function($row){
            return $row->getTotalGoalHtml();
        })
        ->rawColumns([
            'view_btn',
            'status_html',
            'is_phone_verified_html',
            'is_email_verified_html',
            'is_kyc_completed_html',
            // 'roundup_status_html',
            'total_goal_html'
        ])
        ->addIndexColumn()
        ->make(true);
    }

    private function getModelCollection($request, $with=[]){
        $with = array_merge([
            'customer:id,user_id,is_kyc_completed',
            // 'customer.account.user_roundup',
            'customer.user_goal_config:user_goal_config.id,user_goal_config.account_id',
            'user_document:user_documents.customer_id,user_documents.first_name,user_documents.last_name,user_documents.email'
        ], $with);
        $query = User::where('role_id',null);
                    // ->whereHas('user_document');

        if( ($request->status != null) && ($request->status != '') ){
            $query->where('is_active',$request->status);
        }

        if( ($request->is_phone_verified != null) && ($request->is_phone_verified != '') ){
            $query->where('is_phone_verified',$request->is_phone_verified);
        }

        if( ($request->is_email_verified != null) && ($request->is_email_verified != '') ){
            $query->where('is_email_verified',$request->is_email_verified);
        }

        $query = $query->with($with);
        // ->select(['users.id as uid','users.*']);

        return $query;
    }

    public function show($id) {
        $data['user'] = User::getByEid($id);
        $data['title'] = 'Customer Details';
        $data['action_url'] = route('admin.customers');
        $data['update_status_url'] = route('admin.customer.update.status',['id'=>$data['user']->e_id]);
        $accountId = $data['user']->account->id;
        $data['transactions'] = Transaction::where('account_id',$accountId)->with(['user_goal_config:id,name'])->orderBy('id','DESC')->limit(10)->get();
        return view($this->show_view, $data);
    }

    public function updateStatus(Request $request,$id){

        if( ! Customer::canEdit() ) {
            return Helper::redirectUnauthorizedPermission();
        }

        $user = User::getByEid($id);
        if( $request->status == User::STATUS_ACTIVE ){
            $user->is_active = User::STATUS_ACTIVE;
        } else {
            $user->is_active = User::STATUS_INACTIVE;
        }
        $user->save();

        Helper::successToast('User status has been updated successfully');
        return back();
    }

    public function dropdownData(Request $request){
        $searchTerms = $request->term??null;
        $data = Customer::whereHas('user_document', function($query) use($searchTerms){
            $query->where([
                'kyc_status' => UserDocument::STATUS_APPROVED,
                'cnic_status' => UserDocument::STATUS_APPROVED,
                'live_photo_status' => UserDocument::STATUS_APPROVED,
                'is_declaration' => UserDocument::DECLARATION_ACCEPTED,
            ]);
            if( $searchTerms ){
                $query->where(function ($query) use($searchTerms) {
                    $query->whereRaw('`first_name` LIKE "%'.$searchTerms.'%"')
                        ->orWhereRaw('`mobile_number` LIKE "%'.$searchTerms.'%"');
                });
            }
            return $query;
        })->with(['account:id,customer_id','user_document:id,customer_id,first_name,last_name,mobile_number'])->limit(3)->get(['id']);

        $results = [];
        foreach( $data as $row ){
            if( $row->user_document ){
                $results[] = [
                    'id' => $row->account->id,
                    'text' => $row->user_document->full_name.' ('.$row->user_document->mobile_number.')',
                ];
            }
        }

        return [
            'results' => $results
        ];

    }

}
