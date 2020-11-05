<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
            'site_id' => 'nullable|exists:sites,id',
            'referer' => 'nullable|string',
            'endpoint' => 'required|string',
            'p256dh' => 'required|string',
            'auth' => 'required|string',
            'timezone' => 'nullable|digits_between:0,23'
        ];
    }
}
