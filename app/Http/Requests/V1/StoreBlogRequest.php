<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogRequest extends FormRequest
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
            'slug'=>'required|unique:blogs|max:255',
            'body'=>'required',
            // 'image'=>'image|file|max:1024'
        ];
    }

    protected function prepareForValidation(){
        $this->merge([
            'published_at'=>$this->publishedAt
        ]);
    }
}

// protected $fillable = [
//     'user_id',
//     'title',
//     'slug',
//     'body',
//     'image' 'image'=>'image|file|max:1024',
// ];