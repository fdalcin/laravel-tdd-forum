<?php

namespace App\Http\Requests;

use App\Exceptions\ThrottleException;
use App\Rules\SpamFree;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateReplyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('create', new \App\Reply);
    }

    protected function failedAuthorization()
    {
        throw new ThrottleException(
            'You are replying too frequently. Please take a break.'
        );
    }

    public function rules()
    {
        return [
            'body' => ['required', new SpamFree]
        ];
    }
}
