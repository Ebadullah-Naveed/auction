<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Str;
use App\Models\Category;

class CategoryFormRequest extends FormRequest
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
            'name' => ['required','max:50','min:3','unique:category,name,'.$id],
            'image' => ['nullable'],
        ];
    }

    protected function getValidatorInstance() {
        $data = $this->all();

        $data['slug'] = Str::slug($data['name']);
        
        if( isset($data['status']) ){
            $data['status'] = Category::STATUS_ACTIVE;
        } else {
            $data['status'] = Category::STATUS_INACTIVE;
        }

        if( isset($data['featured']) ){
            $data['featured'] = Category::FEATURED_YES;
        } else {
            $data['featured'] = Category::FEATURED_NO;
        }

        //arranging attribute json
        $attributes = [];
        if( isset( $data['custom_field']['key'] ) && ( count($data['custom_field']['key']) > 0 ) ){
            foreach( $data['custom_field']['key'] as $key=>$value ){
                $attributes[] = [
                    'key' => strtolower($value),
                    'label' => $data['custom_field']['label'][$key],
                    'required' => $data['custom_field']['required'][$key],
                    'type' => $data['custom_field']['type'][$key],
                ];
            }
        }
        $data['attribute_json'] = json_encode($attributes);

        $this->getInputSource()->replace($data);

        /** modify data before send to validator */
        return parent::getValidatorInstance();
    }

}
