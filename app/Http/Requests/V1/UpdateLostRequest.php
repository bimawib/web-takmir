<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLostRequest extends FormRequest
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
        $method = $this->method();

        if($method == 'PUT'){
            return [
                'title'=>'required|max:255',
                // 'slug'=>'required|unique:losts|max:255',
                'note'=>'required|max:255',
                'contact'=>'required|numeric',
                'date'=>'required|date_format:Y-m-d H:i:s',
                'isReturned'=>['required',Rule::in([0,1])]
            ];
        } else {
            return [
                'title'=>'sometimes|required|max:255',
                // 'slug'=>'sometimes|required|unique:losts|max:255',
                'note'=>'sometimes|required|max:255',
                'contact'=>'sometimes|required|numeric',
                'date'=>'sometimes|required|date_format:Y-m-d H:i:s',
                'isReturned'=>['sometimes','required',Rule::in([0,1])]
            ];
        }
        
    }

    protected function prepareForValidation(){
        if(isset($this->isReturned)){
        $this->merge([
            'is_returned'=>$this->isReturned
        ]);
        }
    }
}
