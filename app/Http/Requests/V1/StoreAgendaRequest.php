<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAgendaRequest extends FormRequest
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
            'slug'=>'required|unique:agendas|max:255',
            // 'image'=>'image|file|max:1024',
            'location'=>'required|max:255',
            'date'=>'required|date_format:Y-m-d H:i:s'
        ];
    }

    protected function prepareForValidation(){
        $this->merge([
            'published_at'=>$this->publishedAt
        ]);
    }
}