<?php

namespace App\Modules\Admin\Analytics\Services;

use App\Services\Date\Facade\DateService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticDataService
{

    public function getAnalytic(\Illuminate\Http\Request $request): array
    {
        $dateStart = Carbon::now();
        if ($request->dateStart && DateService::isValid($request->dateStart, "d-m-Y")) {
            $dateStart = Carbon::parse($request->dateStart);
        }
        $dateEnd = Carbon::now();
        if ($request->dateEnd && DateService::isValid($request->dateEnd, "d-m-Y")) {
            $dateEnd = Carbon::parse($request->dateEnd);
        }

        return DB::select(
            'CALL countLeads("'.$dateStart->format('Y-m-d').'", "'.$dateEnd->format('Y-m-d').'")'
        );
    }
}
