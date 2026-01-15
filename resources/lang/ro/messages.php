<?php

declare(strict_types=1);

return [
    'auth' => [
        'phone_required'      => 'Numărul de telefon este obligatoriu',
        'phone_invalid'       => 'Numărul de telefon este invalid',
        'otp_sent'            => 'Codul OTP a fost trimis cu succes',
        'otp_expired'         => 'Codul OTP a expirat',
        'otp_invalid'         => 'Codul OTP este invalid',
        'verification_failed' => 'Verificarea a eșuat',
        'unauthorized'        => 'Neautorizat',
        'user_not_found'      => 'Utilizatorul nu a fost găsit',
    ],
    'validation' => [
        'required' => 'Câmpul :attribute este obligatoriu',
        'email'    => 'Câmpul :attribute trebuie să fie o adresă de email validă',
        'min'      => 'Câmpul :attribute trebuie să aibă cel puțin :min caractere',
        'max'      => 'Câmpul :attribute nu poate avea mai mult de :max caractere',
    ],
    'errors' => [
        'server_error'      => 'Eroare de server',
        'not_found'         => 'Resursa nu a fost găsită',
        'forbidden'         => 'Acces interzis.',
        'invalid_locale'    => 'Local invalid',
        'unauthorized'      => 'Neautorizat.',
        'validation_failed' => 'Datele date sunt invalide.',
        'too_many_requests' => 'Prea multe cereri.',
    ],
    'success' => [
        'created'        => 'Creat cu succes',
        'updated'        => 'Actualizat cu succes',
        'deleted'        => 'Șters cu succes',
        'locale_updated' => 'Local actualizat cu succes',
    ],
    'ride' => [
        'created'        => 'Cursa creată cu succes',
        'accepted'       => 'Cursa acceptată cu succes',
        'completed'      => 'Cursa finalizată cu succes',
        'cancelled'      => 'Cursa anulată cu succes',
        'rated'          => 'Cursa evaluată cu succes',
        'not_active'     => 'Nu a fost găsită nicio cursă activă',
        'already_active' => 'Ai deja o cursă activă.',
    ],
    'profile' => [
        'updated' => 'Profil actualizat cu succes',
    ],
    'avatar' => [
        'upload_started' => 'Încărcarea avatarului a început',
    ],
];
