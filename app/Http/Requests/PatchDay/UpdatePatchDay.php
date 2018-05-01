<?php

namespace App\Http\Requests\PatchDay;

use App\PatchDay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatchDay extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $patch_day = PatchDay::findOrFail($this->route('patch_day'))->first();

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
            'date'   => 'date',
            'status' => Rule::in(config('enums.patch_day_status')),
        ];
    }
}
