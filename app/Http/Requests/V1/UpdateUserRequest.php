<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        return [
            'name'=>'sometimes|required|max:255',
            'isVerified'=>['sometimes','required',Rule::in([0,1])],
            'isAdmin'=>['sometimes','required',Rule::in([0,1])]
        ];
    }

    protected function prepareForValidation(){
        if(isset($this->isVerified)){
        $this->merge([
            'is_verified'=>$this->isVerified
        ]);
        }
        if(isset($this->isAdmin)){
            $this->merge([
                'is_admin'=>$this->isAdmin
            ]);
        }
    }
}
