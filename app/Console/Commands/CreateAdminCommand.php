<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Admin\CreateAdminAction;
use App\Data\Admin\CreateAdminData;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'admin:create',
    description: 'Create a new admin user',
)]
final class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CreateAdminAction $createAdmin,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $data = $this->askForData();
        if ($data === null) {
            return self::FAILURE;
        }

        $validationResult = $this->validateData($data);
        if ($validationResult !== true) {
            $this->outputValidationErrors($validationResult);

            return self::FAILURE;
        }

        if (! $this->createAdmin($this->createAdmin, $data)) {
            return self::FAILURE;
        }

        $this->outputSuccess($data);

        return self::SUCCESS;
    }

    /**
     * @return array{phone: string, email: string|null, password: string}|null
     */
    private function askForData(): ?array
    {
        /** @var string $phone */
        $phone = $this->ask('Phone number (+373...)');

        /** @var string|null $emailInput */
        $emailInput = $this->ask('Email (optional)');

        /** @var string $password */
        $password = $this->secret('Password');

        /** @var string $confirm */
        $confirm = $this->secret('Confirm password');

        if ($password !== $confirm) {
            $this->error('Passwords do not match.');

            return null;
        }

        $email = $emailInput === '' || $emailInput === null ? null : $emailInput;

        return [
            'phone'    => $phone,
            'email'    => $email,
            'password' => $password,
        ];
    }

    /**
     * @param array{phone: string, email: string|null, password: string} $data
     *
     * @return true|array<int, string>
     */
    private function validateData(array $data): true|array
    {
        $validator = Validator::make($data, [
            'phone'    => ['required', 'string', 'phone:MD', 'unique:users,phone'],
            'email'    => ['nullable', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return array_values($validator->errors()->all());
        }

        return true;
    }

    /**
     * @param array<int, string> $errors
     */
    private function outputValidationErrors(array $errors): void
    {
        foreach ($errors as $error) {
            $this->error($error);
        }
        $this->logger->info('Admin creation failed validation', ['errors' => $errors]);
    }

    /**
     * @param array{phone: string, email: string|null, password: string} $data
     */
    private function createAdmin(CreateAdminAction $createAdmin, array $data): bool
    {
        try {
            $createAdmin->handle(new CreateAdminData(
                phone: $data['phone'],
                email: $data['email'],
                password: $data['password'],
            ));
        } catch (Exception $e) {
            $this->error('Failed to create admin: '.$e->getMessage());
            $this->logger->error('Failed to create admin', ['error' => $e->getMessage()]);

            return false;
        }

        return true;
    }

    /**
     * @param array{phone: string, email: string|null, password: string} $data
     */
    private function outputSuccess(array $data): void
    {
        $this->info('Admin created successfully!');
        $this->line("  Phone: {$data['phone']}");
        $this->line('  Role:  admin');
    }
}
