<?php

namespace App\Http\Requests\Protocol;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProtocol extends FormRequest
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
            'due_date' => 'date',
            'done' => 'boolean',
            'comment' => 'string|nullable'
        ];
    }
}
