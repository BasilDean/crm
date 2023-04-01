<?php

namespace App\Modules\Admin\Analytics\Controllers\Api;

use App\Modules\Admin\Analytics\Models\Analytic;
use App\Modules\Admin\Analytics\Services\AnalyticDataService;
use App\Modules\Admin\Lead\Models\Lead;
use App\Services\Response\ResponseServise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{

    private $service;

    /**
     * @param $service
     */
    public function __construct(AnalyticDataService $analyticDataService)
    {
        $this->service = $analyticDataService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAnalytic', Lead::class);

        $leadsData = $this->service->getAnalytic($request);

        return ResponseServise::sendJsonResponse(true, 200, [], [
            'items' => $leadsData
        ]);
    }

    /**
     * Create of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modules\Admin\Analytics\Models\Analytic  $analytic
     * @return \Illuminate\Http\Response
     */
    public function show(Analytic $analytic)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modules\Admin\Analytics\Models\Analytic  $analytic
     * @return \Illuminate\Http\Response
     */
    public function edit(Analytic $analytic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modules\Admin\Analytics\Models\Analytic  $analytic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Analytic $analytic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modules\Admin\Analytics\Models\Analytic  $analytic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Analytic $analytic)
    {
        //
    }
}
