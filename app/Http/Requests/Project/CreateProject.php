<?php

namespace App\Http\Requests\Project;

use App\Project;
use Illuminate\Foundation\Http\FormRequest;

class CreateProject extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Project::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'           => 'required|string',
            'company_id'     => 'required|integer|exists:companies,id',
            'base_price'     => 'numeric',
            'penalty'        => 'numeric',
            'technologies.*' => 'integer|exists:technologies,id',
        ];
    }
}
