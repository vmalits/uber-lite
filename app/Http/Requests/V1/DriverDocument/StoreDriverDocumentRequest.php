<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\DriverDocument;

use App\Enums\DriverDocumentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class StoreDriverDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type'       => ['required', new Enum(DriverDocumentType::class)],
            'document'   => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function bodyParameters(): array
    {
        return [
            'type' => [
                'description' => 'The type of the document',
                'example'     => 'driving_license',
            ],
            'document' => [
                'description' => 'The document file (jpg, png, pdf, max 10MB)',
                'type'        => 'file',
                'required'    => true,
            ],
            'expires_at' => [
                'description' => 'Expiration date of the document',
                'example'     => '2027-01-01',
            ],
        ];
    }
}
