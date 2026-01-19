<?php

declare(strict_types=1);

return [
    'auth' => [
        'phone_required'           => 'Номер телефона обязателен',
        'phone_invalid'            => 'Номер телефона недействителен',
        'otp_sent'                 => 'Код OTP успешно отправлен',
        'otp_expired'              => 'Код OTP истёк',
        'otp_invalid'              => 'Неверный код OTP',
        'verification_failed'      => 'Ошибка верификации',
        'unauthorized'             => 'Не авторизован',
        'user_not_found'           => 'Пользователь не найден',
        'logged_out'               => 'Выход успешно выполнен.',
        'account_deleted'          => 'Аккаунт успешно удалён.',
        'email_added'              => 'Email успешно добавлен.',
        'phone_not_verified'       => 'Номер телефона не подтверждён.',
        'role_selected'            => 'Роль успешно выбрана.',
        'profile_completed'        => 'Профиль успешно заполнен.',
        'otp_requested'            => 'OTP был успешно запрошен.',
        'otp_resent'               => 'OTP был успешно отправлен повторно.',
        'email_verify_link'        => 'Недействительная ссылка верификации.',
        'email_already_verified'   => 'Ваш email уже подтверждён.',
        'email_verified'           => 'Email успешно подтверждён.',
        'verification_link_sent'   => 'Ссылка для верификации была отправлена.',
        'too_many_attempts'        => 'Слишком много неудачных попыток верификации. Пожалуйста, запросите новый OTP.',
        'phone_already_verified'   => 'Номер телефона уже подтверждён.',
        'verification_successful'  => 'Верификация прошла успешно.',
        'invalid_or_expired_code'  => 'Неверный или истёкший код.',
        'phone_not_verified_error' => 'Номер телефона не подтверждён.',
        'role_already_selected'    => 'Роль уже выбрана.',
        'email_not_set'            => 'Email не установлен.',
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
        'created'             => 'Поездка успешно создана',
        'accepted'            => 'Поездка успешно принята',
        'completed'           => 'Поездка успешно завершена',
        'cancelled'           => 'Поездка успешно отменена',
        'rated'               => 'Поездка успешно оценена',
        'not_active'          => 'Активная поездка не найдена',
        'already_active'      => 'У вас уже есть активная поездка.',
        'estimate_calculated' => 'Оценка рассчитана успешно.',
    ],
    'profile' => [
        'updated' => 'Профиль успешно обновлён',
    ],
    'avatar' => [
        'upload_started' => 'Загрузка аватара началась',
    ],
    'driver' => [
        'online'  => 'Теперь вы онлайн и доступны для поездок.',
        'offline' => 'Теперь вы офлайн.',
    ],
    'favorite' => [
        'location_added'   => 'Избранное место успешно добавлено.',
        'location_removed' => 'Избранное место успешно удалено.',
    ],
    'vehicle' => [
        'added' => 'Транспортное средство успешно добавлено.',
    ],
    'payment_methods' => [
        'fetched' => 'Способы оплаты успешно получены.',
    ],
];
