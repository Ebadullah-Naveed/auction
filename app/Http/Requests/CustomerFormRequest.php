<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hash;
use App\Models\User;

class UserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = $this->all();
        $id = '';
        if( isset($data['user_id']) ){
            $id = $data['user_id'];
        }
        return [
            'name' => ['required','max:50','min:3'],
            'email' => ['required','email','unique:users,email,'.$id],
            'username' => ['required','max:30','min:3','unique:users,username,'.$id],
            'password' => ['nullable','min:6'],
            'phone_number' => ['required','max:20','min:10'],
            'image' => ['nullable','image'],
            'role_id' => ['required'],

        ];
    }

    protected function getValidatorInstance() {
        $data = $this->all();
        
        if( isset($data['status']) ){
            $data['status'] = User::STATUS_ACTIVE;
        } else {
            $data['status'] = User::STATUS_INACTIVE;
        }

        if( isset($data['password']) ){
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['date_joined'] = now();

        $this->getInputSource()->replace($data);

        /** modify data before send to validator */
        return parent::getValidatorInstance();
    }
}
