<?php

namespace App\Http\Requests;

use App\Enums\SettingsKeys;
use App\Models\SettingsConfig;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rules\Enum;

class UpdatesettingsRequest extends FormRequest
{

    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'key' => ['required', 'string', new Enum(SettingsKeys::class)],
            'value' => [],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if 'key' has failed validation
            if ($validator->errors()->has('key')) {
                return; // Skip further validation
            }

            // 'key' passed validation, apply rules to 'value'
            $key = $this->input('key');
            $validator->setRules([
                'value' => ['required', ...SettingsConfig::getValidation($key)],
            ]);
        });
    }
}
