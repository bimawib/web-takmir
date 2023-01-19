<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $method = $this->method;
        if($method == "PUT"){
            return [
                'title'=>'required|max:255',
                'body'=>'required',
                // 'image'=>'image|file|max:1024'
            ];
        } else {
            return [
                'title'=>'required|max:255',
                'body'=>'sometimes|required',
                // 'image'=>'sometimes|image|file|max:1024'
            ];
        }
        
    }

    protected function prepareForValidation(){
        if(isset($this->isVerified)){
        $this->merge([
            'is_verified'=>$this->isVerified
        ]);
        }
    }
}
