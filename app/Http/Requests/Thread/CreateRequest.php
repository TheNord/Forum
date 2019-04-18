<?php

namespace App\Http\Requests\Thread;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
     * @param Recaptcha $recaptcha
     * @return array
     */
    public function rules(Recaptcha $recaptcha)
    {
        return [
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required', 'exists:channels,id',
            'g-recaptcha-response' => $recaptcha
        ];
    }
}
