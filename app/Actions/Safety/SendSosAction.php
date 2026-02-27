<?php

declare(strict_types=1);

namespace App\Actions\Safety;

use App\Data\Safety\SosData;
use App\Events\Safety\SosAlertTriggered;
use App\Models\User;
use App\Notifications\Safety\SosNotification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

final readonly class SendSosAction
{
    public function handle(User $user, SosData $data): bool
    {
        $contacts = $user->emergencyContacts;

        if ($contacts->isEmpty()) {
            return false;
        }

        SosAlertTriggered::dispatch(
            $user,
            $data->latitude,
            $data->longitude,
            $data->message,
        );

        foreach ($contacts as $contact) {
            $notifiable = new AnonymousNotifiable;

            if ($contact->email !== null) {
                $notifiable->route('mail', $contact->email);
            }

            Notification::send($notifiable, new SosNotification(
                $user,
                $data->latitude,
                $data->longitude,
                $data->message,
            ));
        }

        return true;
    }
}
