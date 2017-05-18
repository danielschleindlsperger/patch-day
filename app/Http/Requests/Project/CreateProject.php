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
            'name' => 'required|',
            'company_id' => 'required|exists:companies,id',
            'patch_day.cost' => 'numeric',
            'patch_day.active' => 'boolean',
            'patch_day.start_date' => 'date',
            'patch_day.technologies' => 'filled',
            'patch_day.technologies.*' => 'numeric|exists:technologies,id',
        ];
    }
}
