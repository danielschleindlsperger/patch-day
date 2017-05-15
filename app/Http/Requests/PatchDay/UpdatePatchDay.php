<?php

namespace App\Http\Requests\PatchDay;

use App\PatchDay;
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
        $patch_day = PatchDay::find($this->route('patch_day'));

        return $patch_day && $this->user()->can('update', $patch_day);
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
