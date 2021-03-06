<?php

namespace App\Http\Requests\Company;

use App\Company;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompany extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $company = Company::findOrFail($this->route('company'))->first();

        return $company && $this->user()->can('update', $company);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string',
            'logo' => 'mimes:jpeg,png,svg|max:500',
        ];
    }
}
