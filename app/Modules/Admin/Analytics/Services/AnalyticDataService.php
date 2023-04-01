<?php

namespace App\Modules\Admin\Analytics\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticDataService
{

    public function getAnalytic(\Illuminate\Http\Request $request): array
    {
        $dateStart = Carbon::now();
        if ($request->dateStart) {
            $dateStart = Carbon::parse($request->dateStart);
        }
        $dateEnd = Carbon::now();
        if ($request->dateEnd) {
            $dateEnd = Carbon::parse($request->dateEnd);
        }

        return DB::select(
            'CALL countLeads("'.$dateStart->format('Y-m-d').'", "'.$dateEnd->format('Y-m-d').'")'
        );
    }
}
