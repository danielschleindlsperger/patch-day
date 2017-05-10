<?php

namespace App\Http\Requests\Project;

use App\Project;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProject extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $project = Project::find($this->route('project'));

        return $project && $this->user()->can('update', $project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'exists:companies,id',
            'patch_day.cost' => 'numeric',
            'patch_day.active' => 'boolean',
            'patch_day.start_date' => 'date',
        ];
    }
}
