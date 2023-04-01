<?php

namespace App\Modules\Admin\Lead\Controllers\Api;

use App\Modules\Admin\Lead\Models\Lead;
use App\Modules\Admin\Lead\Requests\LeadCreateRequest;
use App\Modules\Admin\Lead\Services\LeadService;
use App\Services\Response\ResponseServise;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
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
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LeadCreateRequest $request
     * @return JsonResponse
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
     * @param Lead $lead
     * @return JsonResponse
     */
    public function show(Lead $lead)
    {
        return ResponseServise::sendJsonResponse(true, '200', [], [
            'item'=>$lead
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Lead $lead
     * @return void
     */
    public function edit(Lead $lead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LeadCreateRequest $request
     * @param Lead $lead
     * @return JsonResponse
     */
    public function update(LeadCreateRequest $request, Lead $lead): JsonResponse
    {
        $this->authorize('edit', Lead::class);

        $lead = $this->service->update($request, Auth::user(), $lead);

        return ResponseServise::sendJsonResponse(true, '200', [], [
            'item'=>$lead
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lead $lead
     * @return Response
     */
    public function destroy(Lead $lead)
    {
        //
    }

    public function archive() {
        $this->authorize('view', Lead::class);

        $leads = $this->service->archive();

        return ResponseServise::sendJsonResponse(true, 200, [], [
            'items' => $leads,
        ]);
    }

    public function checkIfExist(Request $request) {
        $this->authorize('create', Lead::class);

        $lead = $this->service->checkIfExist($request);

        if ($lead) {
            return ResponseServise::sendJsonResponse(true, 200, [], [
                'item' => $lead,
                'exist' => true
            ]);
        }
        return ResponseServise::success();
    }

    public function setAsQualityLead(Request $request, Lead $lead) {
        $this->authorize('edit', Lead::class);

        $lead = $this->service->setAsQualityLead($request, $lead);

        return ResponseServise::sendJsonResponse(true, 200, [], [
            'item' => $lead
        ]);
    }
}
