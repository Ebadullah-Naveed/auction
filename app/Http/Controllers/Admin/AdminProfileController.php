<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Helper;
use App\Models\User;

class AdminProfileController extends Controller
{
    public $module_title = 'Password Reset';

    public function passwordReset()
    {
        $data['title'] = $this->module_title;
        $currentUser = auth()->user();
        $data['action_url'] = route('admin.profile.update_password',$currentUser->id);
        return view('admin.profile.password_reset', $data);
    }

    public function updatePassword(Request $request, $id)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);
        
        $hashedPassword = auth()->user()->password;
        if (\Hash::check($request->current_password , $hashedPassword )) {

            if (!\Hash::check($request->password , $hashedPassword)) {
                $user = User::find(auth()->user()->id);
                $user->password = bcrypt($request->password);
                User::where( 'id' , auth()->user()->id)->update( array( 'password' =>  $user->password));
                
                Helper::successToast('Password updated successfully');
                return redirect()->back();
            } else {
                Helper::errorToast('New password can not be the current password!');
                return redirect()->back();
            }
        
        } else {
            Helper::errorToast('Current password does not matched');
            return redirect()->back();
        }
            
    }

}
