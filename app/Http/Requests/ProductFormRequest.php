<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class ProductFormRequest extends FormRequest
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
        // $id = '';
        // if( isset($data['product_id']) ){
        //     $id = $data['product_id'];
        // }
        return [
            'name' => ['required','max:150','min:3'],
            'price' => ['required','numeric','max:10000000','min:3'],
            'min_increment' => ['required','numeric','max:1000000','min:1'],
            'max_increment' => ['required','numeric','max:1000000','min:1'],
            'end_datetime' => ['required'],
            'short_desc' => ['nullable','max:255'],
            'terms' => ['nullable','max:1000'],
        ];
    }

    protected function getValidatorInstance() {
        $data = $this->all();

        if( isset($data['status']) ){
            $data['status'] = Product::STATUS_ACTIVE;
        } else {
            $data['status'] = Product::STATUS_PENDING;
        }

        // if( isset($data['featured']) ){
        //     $data['featured'] = Category::FEATURED_YES;
        // } else {
        //     $data['featured'] = Category::FEATURED_NO;
        // }

        //arranging attribute json
        // $attributes = [];
        // if( isset( $data['custom_field']['key'] ) && ( count($data['custom_field']['key']) > 0 ) ){
        //     foreach( $data['custom_field']['key'] as $key=>$value ){
        //         $attributes[] = [
        //             'key' => strtolower($value),
        //             'label' => $data['custom_field']['label'][$key],
        //             'type' => $data['custom_field']['type'][$key],
        //         ];
        //     }
        // }
        // $data['attribute_json'] = json_encode($attributes);

        // dd( array_keys($data['product_attributes']) );

        // dd($data['product_attributes']);

        // $data['product_attributes'] =  (array)$data['product_attributes'];
        // $data['product_attributes_label'] =  (array)$data['product_attributes_label'];

        // unset($data['product_attributes']);
        // unset($data['product_attributes_label']);

        $this->getInputSource()->replace($data);

        /** modify data before send to validator */
        return parent::getValidatorInstance();
    }

}
