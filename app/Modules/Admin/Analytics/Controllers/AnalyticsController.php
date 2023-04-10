<?php

namespace App\Modules\Admin\Analytics\Controllers;

use App\Modules\Admin\Analytics\Export\LeadExport;
use App\Modules\Admin\Lead\Models\Lead;
use App\Modules\Admin\User\Models\User;
use App\Services\Date\Facade\DateService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;

class AnalyticsController extends Controller
{
    public function index() {
        return 'test';
    }
    public function export(User $user, $dateStart = null, $dateEnd = null) {


        $export = new LeadExport($user, $dateStart, $dateEnd);
        return Excel::download($export,'leads.xlsx');
    }
}
