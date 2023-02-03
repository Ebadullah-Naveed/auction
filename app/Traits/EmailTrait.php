<?php

namespace App\Traits;
use Illuminate\Support\Facades\Mail;

trait EmailTrait
{
    /**
     * Email integration
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function sendEmail($email, $template)
    {
        try {
            Mail::to($email)->send($template);
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return false;
        }
    }

}


