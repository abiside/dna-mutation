<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DnaRequest extends FormRequest
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
            'dna' => [
                'array',
                'required',
                'min:6',
                'max:6',
            ],
            'dna.*' => [
                'string',
                'required_with:dna',
                'min:6',
                'max:6',
                'regex:/^[ATCG]*$/',
            ],
        ];
    }

    /**
     * Prepare some request attributes to be validated
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        // Make a characters normalization to uppercase
        if (is_array($this->dna)) {
            $this->merge([
                'dna' => (collect($this->dna)->map(function ($value, $key){
                    return strtoupper($value);
                }))->toArray()
            ]);
        }
    }
}
