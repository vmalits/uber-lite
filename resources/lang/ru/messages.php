<?php

declare(strict_types=1);

return [
    'auth' => [
        'phone_required'      => 'Номер телефона обязателен',
        'phone_invalid'       => 'Номер телефона недействителен',
        'otp_sent'            => 'Код OTP успешно отправлен',
        'otp_expired'         => 'Код OTP истёк',
        'otp_invalid'         => 'Неверный код OTP',
        'verification_failed' => 'Ошибка верификации',
        'unauthorized'        => 'Не авторизован',
        'user_not_found'      => 'Пользователь не найден',
    ],
    'validation' => [
        'required' => 'Поле :attribute обязательно для заполнения',
        'email'    => 'Поле :attribute должно быть действительным email',
        'min'      => 'Поле :attribute должно содержать минимум :min символов',
        'max'      => 'Поле :attribute не может содержать более :max символов',
    ],
    'errors' => [
        'server_error'      => 'Ошибка сервера',
        'not_found'         => 'Ресурс не найден',
        'forbidden'         => 'Доступ запрещён.',
        'invalid_locale'    => 'Неверная локаль',
        'unauthorized'      => 'Не авторизован.',
        'validation_failed' => 'Данные недействительны.',
        'too_many_requests' => 'Слишком много запросов.',
    ],
    'success' => [
        'created'        => 'Успешно создано',
        'updated'        => 'Успешно обновлено',
        'deleted'        => 'Успешно удалено',
        'locale_updated' => 'Локаль успешно обновлена',
    ],
    'ride' => [
        'created'    => 'Поездка успешно создана',
        'cancelled'  => 'Поездка успешно отменена',
        'rated'      => 'Поездка успешно оценена',
        'not_active' => 'Активная поездка не найдена',
    ],
    'profile' => [
        'updated' => 'Профиль успешно обновлён',
    ],
    'avatar' => [
        'upload_started' => 'Загрузка аватара началась',
    ],
];
