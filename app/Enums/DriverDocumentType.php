<?php

declare(strict_types=1);

namespace App\Enums;

enum DriverDocumentType: string
{
    case DRIVING_LICENSE = 'driving_license';
    case VEHICLE_REGISTRATION = 'vehicle_registration';
    case INSURANCE = 'insurance';
    case BACKGROUND_CHECK = 'background_check';
    case VEHICLE_INSPECTION = 'vehicle_inspection';
    case IDENTITY_DOCUMENT = 'identity_document';
}
