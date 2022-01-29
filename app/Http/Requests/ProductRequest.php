<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

        switch($this->method())
        {
            case 'POST':
            {
                 return [
                    'title'       => ['required', 'string', 'max:255'],
                    'sku'         => ['required', 'string', 'max:255', 'unique:products'],
                    'description' => ['nullable', 'string'],
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                 return [
                    'title'       => ['required', 'string', 'max:255'],
                    'sku'         => ['required', 'string', 'max:255', Rule::unique('products')->ignore($this->product->id)],
                    'description' => ['nullable', 'string'],
                ];
            }
            default:break;
        }
    }
}
