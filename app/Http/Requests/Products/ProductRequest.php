<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:1|max:255',
            'slug' => 'max:255|unique:products,id,slug'.$this->id,
            'price' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|max:1000',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'categories' => 'array',
            'categories.*' => 'numeric|exists:categories,id'
        ];
    }
}
