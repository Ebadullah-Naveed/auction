<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Category extends BaseModel
{
    use HasFactory;

    protected $table = 'category';
    protected $guarded = ['id'];
    // public $timestamps = false;
    protected $appends = ['e_id','m_created_at','m_attribute_json'];

    const STATUS_ACTIVE  = 1;
    const STATUS_INACTIVE  = 0;

    const FEATURED_YES  = 1;
    const FEATURED_NO  = 0;

    const LIST_PERMISSION = 'list-category';
    const VIEW_PERMISSION = 'view-category';
    const ADD_PERMISSION = 'add-category';
    const EDIT_PERMISSION = 'edit-category';
    const DELETE_PERMISSION = 'delete-category';

    public function getEidAttribute(){
        return encrypt($this->id);
    }

    public function getMCreatedAtAttribute(){
        return Carbon::parse($this->date_joined??$this->created_at)->isoFormat('DD MMM YYYY');
    }

    public function getMAttributeJsonAttribute(){
        return json_decode($this->attribute_json);
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

    public function getEditBtnHtml(){
        if( self::canEdit() ){
            $link = route('admin.category.edit',[ 'id' => $this->e_id ]);
            return '<a href="'.$link.'" class="btn btn-primary btn-sm" title="Edit"><i class="icon icon-edit pr-0"></i> </a>';
        }
    }

    public function getStatusHtml(){
        if($this->status == self::STATUS_ACTIVE){
            return '<label class="badge badge-success">Active</label>';
            // return '<span class="icon icon-unlock f-16 mr-1 text-success" title="Active"></span>';
        } else{
            // return '<span class="icon icon-lock f-16 mr-1 text-danger" title="InActive"></span>';
            return '<label class="badge badge-danger">Inactive</label>';
        }
    }

    public function getFeaturedHtml(){
        if($this->status == self::FEATURED_YES){
            return '<label class="badge badge-success">Yes</label>';
        } else{
            return '<label class="badge badge-danger">No</label>';
        }
    }

}
