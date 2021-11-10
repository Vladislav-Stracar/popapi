<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchPopulationRequest extends FormRequest
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
            'country' => 'string',
            'yearFrom' => 'numeric|required_with:yearTo',
            'yearTo' => 'numeric|required_with:yearFrom',
            'populationFrom' => 'numeric|required_with:populationTo',
            'populationTo' => 'numeric|required_with:populationFrom',
            'page' => 'numeric|gt:0',
        ];
    }

    // to not redirect on failed rules
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
