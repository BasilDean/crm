<?php

namespace App\Modules\Admin\Lead\Controllers\Api;

use App\Modules\Admin\Lead\Models\Lead;
use App\Modules\Admin\Lead\Requests\LeadCreateRequest;
use App\Modules\Admin\Lead\Services\LeadService;
use App\Services\Response\ResponseServise;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{

    private $service;

    /**
     * @param $service
     */
    public function __construct(LeadService $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Lead::class);

        $result = $this->service->getLeads();

        return ResponseServise::sendJsonResponse(true, 200, [], [
            'items' => $result,
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
     * @param LeadCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LeadCreateRequest $request)
    {
        $this->authorize('create', Lead::class);

        $lead = $this->service->store($request, Auth::user());

        return ResponseServise::sendJsonResponse(true, '200', [], [
            'item'=>$lead
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modules\Admin\Lead\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modules\Admin\Lead\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function edit(Lead $lead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modules\Admin\Lead\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lead $lead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modules\Admin\Lead\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        //
    }
}
