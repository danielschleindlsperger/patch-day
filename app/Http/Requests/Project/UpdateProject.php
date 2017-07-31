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
            'name' => 'string',
            'company_id' => 'integer|exists:companies,id',
            'base_price' => 'integer',
            'penalty' => 'integer',
            'technologies.*' => 'integer|exists:technologies,id',
        ];
    }
}
