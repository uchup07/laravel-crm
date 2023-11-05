<?php

namespace VentureDrake\LaravelCrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportImportRequest extends FormRequest
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
            'name_modal' => 'required|max:255',
            'owner' => 'required|exists:users.id',
        ];
    }
}
