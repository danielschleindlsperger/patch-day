<?php

namespace App\Http\Requests\Company;

use App\Company;
use Illuminate\Foundation\Http\FormRequest;

class CreateCompany extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Company::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'logo' => 'mimes:jpeg,png,svg|max:500',
        ];
    }
}
