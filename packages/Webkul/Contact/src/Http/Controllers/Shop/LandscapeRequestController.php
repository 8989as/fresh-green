<?php

namespace Webkul\Contact\Http\Controllers\Shop;

use Webkul\Contact\Http\Requests\LandscapeRequestFormRequest;
use Webkul\Contact\Repositories\LandscapeRequestRepository;
use Illuminate\Routing\Controller;

class LandscapeRequestController extends Controller
{
    protected $landscapeRequestRepository;

    public function __construct(LandscapeRequestRepository $landscapeRequestRepository)
    {
        $this->landscapeRequestRepository = $landscapeRequestRepository;
    }

    public function store(LandscapeRequestFormRequest $request)
    {
        $data = $request->validated();
        $landscapeRequest = $this->landscapeRequestRepository->create($data);
        return response()->json(['data' => $landscapeRequest, 'message' => 'Landscape request submitted successfully.'], 201);
    }
}
