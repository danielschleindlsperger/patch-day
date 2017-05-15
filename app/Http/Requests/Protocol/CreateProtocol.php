<?php

namespace App\Http\Requests\Protocol;

use App\Protocol;
use Illuminate\Foundation\Http\FormRequest;

class CreateProtocol extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Protocol::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'due_date' => 'required|date',
            'done' => 'boolean',
            'comment' => 'string|nullable'
        ];
    }
}
