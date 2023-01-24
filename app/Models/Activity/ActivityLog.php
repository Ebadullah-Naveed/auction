<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Str;
use Log;

class ActivityLog extends Model
{
    use HasFactory;

    const LIST_PERMISSION = 'list-activity-logs';
    const VIEW_PERMISSION = '';
    const ADD_PERMISSION = '';
    const EDIT_PERMISSION = '';
    const DELETE_PERMISSION = '';

    protected $table = 'activity_log';
    protected $fillable = ['user_id', 'activity_template_id', 'activity_time', 'json_params'];
    public $timestamps = false;

    public function template(){
    	return $this->belongsTo('App\Models\Activity\ActivityTemplate','activity_template_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function getActivityTimeAttribute($value) {
        return Carbon::parse($value)
        ->setTimezone( config('app.convert_timezone') )
        ->isoFormat('DD MMM, YYYY (h:mm a)');
    }

    public function getText() {
        $text = Str::of($this->template->template)->replace('({user})', $this->user->name );
        $params = json_decode($this->json_params,TRUE);
        foreach( array_keys( $params ) as $keys ) {
            $val = $params[$keys]??'';
            if( gettype( $val ) == 'string' ) {
                $text = Str::of($text)->replace('({'.$keys.'})', $val );
            }
        }
        return $text;
    }

    static public function canOpenList(){
        return ( auth()->user()->can(self::LIST_PERMISSION) );
    }

}
