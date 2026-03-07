<?php

declare(strict_types=1);

namespace App\Presenters\Admin;

use App\Models\Ride;
use Illuminate\Support\LazyCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface RidesCsvExportPresenterInterface
{
    /**
     * @param LazyCollection<int, Ride> $rides
     */
    public function present(LazyCollection $rides): StreamedResponse;
}
