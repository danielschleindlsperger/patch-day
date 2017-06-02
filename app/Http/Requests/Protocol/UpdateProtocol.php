<?php

namespace App\Http\Requests\Protocol;

use App\Protocol;
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
        $protocol = Protocol::find($this->route('protocol'));

        return $protocol && $this->user()->can('update', $protocol);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'done' => 'boolean',
            'comment' => 'string|nullable'
        ];
    }
}
