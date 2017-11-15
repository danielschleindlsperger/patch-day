<?php

namespace App\Http\Requests\Technology;

use App\Technology;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTechnology extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $tech = Technology::findOrFail($this->route('technology'))->first();

        return $tech && $this->user()->can('update', $tech);
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
            'version' => 'string',
        ];
    }
}
