<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreFoundRequest extends FormRequest
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
            '*.title'=>'required|max:255',
            '*.slug'=>'required|unique:founds|max:255',
            '*.note'=>'required|max:255',
            '*.contact'=>'required|numeric',
            '*.date'=>'required|date_format:Y-m-d H:i:s',
        ];
    }

    protected function prepareForValidation(){
        $data = [];

        foreach($this->toArray() as $obj){
            $obj['created_at'] = $obj['createdAt'] ?? null;

            $data[] = $obj;
        }
        $this->merge($data);
    }
}
