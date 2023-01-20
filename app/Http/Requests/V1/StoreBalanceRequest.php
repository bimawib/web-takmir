<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBalanceRequest extends FormRequest
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
            'title'=>'required|max:255',
            'isSpend'=>['required',Rule::in([0,1])],
            'amountBalance'=>'required|numeric|min:100',
            'note'=>'required|max:255'
        ];
    }

    protected function prepareForValidation(){
        $this->merge([
            'is_spend'=>$this->isSpend
        ]);
    }
}
