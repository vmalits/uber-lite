<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class AdjustCreditsRequest extends FormRequest
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
            'amount'      => ['required', 'integer'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'amount' => [
                'description' => 'Amount to add (positive) or deduct (negative).',
                'example'     => 100,
            ],
            'description' => [
                'description' => 'Reason for the adjustment.',
                'example'     => 'Compensation for cancelled ride',
            ],
        ];
    }

    /**
     * @return array{amount: int, description: string}
     */
    public function getValidatedData(): array
    {
        /** @var array{amount: int, description: string} $data */
        $data = $this->validated();

        return $data;
    }

    public function getAmount(): int
    {
        return $this->getValidatedData()['amount'];
    }

    public function getDescription(): string
    {
        return $this->getValidatedData()['description'];
    }
}
