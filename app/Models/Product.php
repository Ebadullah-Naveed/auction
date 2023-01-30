<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $guarded = ['id'];
    protected $appends = ['e_id','m_price','m_min_increment','m_max_increment','m_created_at','m_end_datetime','bid','can_bid'];

    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_FINISHED = 2;

    const LIST_PERMISSION = 'list-product';
    const VIEW_PERMISSION = 'view-product';
    const ADD_PERMISSION = 'add-product';
    const EDIT_PERMISSION = 'edit-product';
    const DELETE_PERMISSION = 'delete-product';

    static public function getByEid($id){
        return self::find( decrypt($id) );
    } 

    public function getEIdAttribute(){
        return encrypt($this->id);
    }

    public function images(){
        return $this->hasMany('App\Models\ProductImage','product_id');
    }

    public function images_by_sequence(){
        return $this->hasMany('App\Models\ProductImage','product_id')->orderBy('sequence','ASC');
    }
    
    public function category(){
        return $this->belongsTo('App\Models\Category','category_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function attributes(){
        return $this->hasMany('App\Models\ProductAttribute','product_id');
    }

    public function bids(){
        return $this->hasMany('App\Models\ProductBid','product_id');
    }

    public function getMPriceAttribute(){
        return 'Rs '.number_format($this->price);
    }

    public function getMMinIncrementAttribute(){
        return 'Rs '.number_format($this->min_increment??0);
    }

    public function getMMaxIncrementAttribute(){
        return 'Rs '.number_format($this->max_increment??0);
    }

    public function getMCreatedAtAttribute(){
        return Carbon::parse($this->created_at)->isoFormat('DD MMM, YYYY (h:mm a)');
    }

    public function getMEndDatetimeAttribute(){
        return Carbon::parse($this->end_datetime)->isoFormat('DD MMM, YYYY (h:mm a)');
    }

    public function getCanBidAttribute(){
        $dateToday = Carbon::now();
        $endDatetime = Carbon::parse($this->end_datetime);
        if( ($endDatetime->lessThanOrEqualTo($dateToday)) 
            || ($this->status != self::STATUS_ACTIVE) 
        ){
            return 0;
        } else {
            return 1;
        }
    }

    public function getBidAttribute($value){
        $value = $this->bids()->count();
        return $value;
    }

    static public function canOpenList() {
        return ( auth()->user()->can(self::LIST_PERMISSION) );
    }

    static public function canAdd(){
        return ( auth()->user()->can(self::ADD_PERMISSION) );
    }

    static public function canEdit(){
        return ( auth()->user()->can(self::EDIT_PERMISSION) );
    }

    static public function canView(){
        return ( auth()->user()->can(self::VIEW_PERMISSION) );
    }

    static public function canDelete(){
        return ( auth()->user()->can(self::DELETE_PERMISSION) );
    }

    public function getEditBtnHtml(){
        if( self::canEdit() ){
            $link = route('admin.product.edit',[ 'id' => $this->e_id ]);
            return '<a href="'.$link.'" class="btn btn-primary btn-sm" title="Edit"><i class="icon icon-edit pr-0"></i> </a>';
        }
    }

    public function getStatusHtml(){
        if($this->status == self::STATUS_ACTIVE){
            return '<label class="badge badge-success">Active</label>';
        } else if($this->status == self::STATUS_FINISHED) {
            return '<label class="badge badge-secondary">Finished</label>';
        } else {
            return '<label class="badge badge-warning">Pending</label>';
        }
    }

    public function getCanBidStatusHtml(){
        if($this->can_bid == true){
            return '<label class="badge badge-success">Yes</label>';
        } else {
            return '<label class="badge badge-danger">No</label>';
        }
    }

    public function getLastBidHtml(){
        $latestBid = ProductBid::where('product_id',$this->id)->orderBy('created_at','DESC')->first();
        if( $latestBid ){
            return $latestBid->m_amount.'<br>'.$latestBid->m_created_at;
        }
        return '-';
    }

}
