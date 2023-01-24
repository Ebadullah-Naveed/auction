<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Str;

class RoleFormRequest extends FormRequest
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
            //
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required','max:20','min:3']
        ];
    }

    protected function getValidatorInstance() {
        $data = $this->all();
        $data['slug'] = Str::slug($data['name']);
        
        if( !isset($data['permissions']) ) {
            $data['permissions'] = [];
        }

        $this->getInputSource()->replace($data);

        /** modify data before send to validator */
        return parent::getValidatorInstance();
    }

}
