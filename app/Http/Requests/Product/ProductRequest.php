<?php

namespace App\Http\Requests\Product;

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
            'name' => 'required|unique:products,name|string|min:1|max:255' . $this->name,
            'slug' => 'unique:products,slug|max:255' . $this->slug,
            'price' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|max:1000',
            'images' => 'array|max:8',
            'images.*' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'categories' => 'required|array',
            'categories.*' => 'numeric|exists:categories,id'
        ];
    }

    /**
     * Customize validation messages
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'categories.required' => 'Product must have at least 1 category'
        ];
    }
}
