<?php

namespace App\Http\Requests\PatchDay;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatchDay extends FormRequest
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
            'cost' => 'numeric',
            'active' => 'boolean',
            'start_date' => 'date',
            'project_id' => 'numeric',
        ];
    }
}
