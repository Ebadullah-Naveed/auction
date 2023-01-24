<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends BaseModel
{
    use HasFactory;
    
    protected $table = 'settings';
    protected $fillable=['value'];
    public $timestamps = false;

    const LIST_PERMISSION = 'list-settings';
    const VIEW_PERMISSION = '';
    const ADD_PERMISSION = '';
    const EDIT_PERMISSION = 'edit-settings';
    const DELETE_PERMISSION = '';

    // To get multiple configuration values
    static public function getValues($params) {
        $rows = self::whereIn('key',$params)->get(['key','value']);
        $data = [];
        foreach($rows as $r) {
            $data[$r['key']] = $r['value'];
        }
        return $data;
    }

    // To get single configuration value
    static public function getValue($param) {
        $row = self::where('key',$param)->first(['key','value']);
        return $row->value;
    }

    public function getJsonParamsAttribute($value) {
        return json_decode($value,TRUE);
    }

    static public function canOpenList(){
        return ( auth()->user()->can(self::LIST_PERMISSION) );
    }

    static public function canEdit(){
        return ( auth()->user()->can(self::EDIT_PERMISSION) );
    }

}
