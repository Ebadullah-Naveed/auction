<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    //Constant
    const DEFAULT_AVATAR  = 'profile_pictures/avatar.png';
    const STATUS_ACTIVE  = 1;
    const STATUS_INACTIVE  = 0;

    const IS_PHONE_VERIFIED_YES  = 1;
    const IS_PHONE_VERIFIED_NO  = 0;

    const IS_EMAIL_VERIFIED_YES  = 1;
    const IS_EMAIL_VERIFIED_NO  = 0;

    const LIST_PERMISSION = 'list-admin-user';
    const VIEW_PERMISSION = 'view-admin-user';
    const ADD_PERMISSION = 'add-admin-user';
    const EDIT_PERMISSION = 'edit-admin-user';
    const DELETE_PERMISSION = 'delete-admin-user';

    const CUSTOMER_LIST_PERMISSION = 'list-customer';
    const CUSTOMER_VIEW_PERMISSION = 'view-customer';
    const CUSTOMER_ADD_PERMISSION = 'add-customer';
    const CUSTOMER_EDIT_PERMISSION = 'edit-customer';
    const CUSTOMER_DELETE_PERMISSION = 'delete-customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
       'id'
    ];

    protected $appends = ['e_id','m_created_at','m_date_joined'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Relationships
    public function role(){
        return $this->belongsTo('App\Models\Role','role_id');
    }

    public function products(){
        return $this->hasMany('App\Models\Product','user_id');
    }

    //Mutators and Accessors
    public function getImageAttribute() {
        return asset(self::DEFAULT_AVATAR);
    }

    public function getEidAttribute(){
        return encrypt($this->id);
    }
    
    public function getLastLoginAttribute($value) {
        if($value){
            return Carbon::parse($value)
            ->isoFormat('DD MMM YYYY, h:mm a');
        }
        return 'n/a';
    }

    public function getMDateJoinedAttribute() {
        return Carbon::parse($this->date_joined)
        ->isoFormat('DD MMM YYYY, h:mm a');
    }

    public function getMCreatedAtAttribute(){
        return Carbon::parse($this->date_joined??$this->created_at)->isoFormat('DD MMM YYYY');
    }

    static public function canOpenList($customer=null) {
        if( $customer == 'customer' ){
            return ( auth()->user()->can(self::CUSTOMER_LIST_PERMISSION) );
        } else {
            return ( auth()->user()->can(self::LIST_PERMISSION) );
        }
    }

    static public function canAdd($customer=null){
        if( $customer == 'customer' ){
            return ( auth()->user()->can(self::CUSTOMER_ADD_PERMISSION) );
        } else {
            return ( auth()->user()->can(self::ADD_PERMISSION) );
        }
    }

    static public function canEdit($customer=null){
        if( $customer == 'customer' ){
            return ( auth()->user()->can(self::CUSTOMER_EDIT_PERMISSION) );
        } else {
            return ( auth()->user()->can(self::EDIT_PERMISSION) );
        }
    }

    static public function canView($customer=null){
        if( $customer == 'customer' ){
            return ( auth()->user()->can(self::CUSTOMER_VIEW_PERMISSION) );
        } else {
            return ( auth()->user()->can(self::VIEW_PERMISSION) );
        }
    }

    public function getEditBtnHtml(){
        if( self::canEdit() ){
            $link = route('admin.users.edit',[ 'id' => $this->e_id ]);
            return '<a href="'.$link.'" class="btn btn-primary btn-sm" title="Edit"><i class="icon icon-edit pr-0"></i> </a>';
        }
    }

    public function getViewBtnHtml() {
        if( self::canView() ){
            $link = route('admin.users.show', [ 'id' => $this->e_id ] );
            return '<a href="'.$link.'" class="btn btn-primary btn-sm" title="View Details"><i class="icon icon-eye pr-0"></i> </a>';
        }
        return '';
    }

    public function getCustomerViewBtnHtml() {
        if( self::canView() ){
            $link = route('admin.customer.show', [ 'id' => $this->e_id ] );
            return '<a href="'.$link.'" class="btn btn-primary btn-sm" title="View Details"><i class="icon icon-eye pr-0"></i> </a>';
        }
        return '';
    }

    public function getStatusHtml(){
        if($this->status == self::STATUS_ACTIVE){
            // return '<label class="badge badge-success">Active</label>';
            return '<span class="icon icon-unlock f-16 mr-1 text-success" title="Active"></span>';
        } else{
            return '<span class="icon icon-lock f-16 mr-1 text-danger" title="InActive"></span>';
            // return '<label class="badge badge-danger">Inactive</label>';
        }
    }

    public function getIsPhoneVerifiedHtml(){
        if($this->is_phone_verified == self::IS_PHONE_VERIFIED_YES){
            // return '<label class="badge badge-success" title="'.$this->m_phone_verified_at.'">Verified</label>';
            return '<span class="icon icon-check f-16 mr-1 text-success" title="Verified at"></span>';
        } else{
            // return '<label class="badge badge-danger">Not Verified</label>'
            return '<span class="icon icon-remove f-16 mr-1 text-danger" title="Not Verified"></span>';
        }
    }

    public function getIsEmailVerifiedHtml(){
        if($this->is_email_verified == self::IS_EMAIL_VERIFIED_YES){
            // return '<label class="badge badge-success" title="'.$this->m_email_verified_at.'">Verified</label>';
            return '<span class="icon icon-check f-16 mr-1 text-success" title="Verified"></span>';
        } else{
            // return '<label class="badge badge-danger">Not Verified</label>';
            return '<span class="icon icon-remove f-16 mr-1 text-danger" title="Not Verified"></span>';
        }
    }

    public function updateLastLogin($ip){
        $this->last_login_ip = $ip;
        $this->last_login = now();
        $this->save();

        //Logging last login
        Helper::createActivityLog( Helper::LOG_USER_LOGIN, [ 'ip' => $ip ] );
    }

    //Static Functions
    static public function getByEid($id){
        $id = decrypt($id);
        return self::findOrFail($id);
    }

}
