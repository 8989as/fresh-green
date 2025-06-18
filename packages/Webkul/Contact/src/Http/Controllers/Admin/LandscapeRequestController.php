<?php

namespace Webkul\Contact\Http\Controllers\Admin;

use Webkul\Contact\Repositories\LandscapeRequestRepository;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class LandscapeRequestController extends Controller
{
    protected $landscapeRequestRepository;

    public function __construct(LandscapeRequestRepository $landscapeRequestRepository)
    {
        $this->landscapeRequestRepository = $landscapeRequestRepository;
    }

    public function index()
    {
        $landscapeRequests = $this->landscapeRequestRepository->all();
        return view('contact::admin.landscape_requests.index', compact('landscapeRequests'));
    }

    public function show($id)
    {
        return response()->json(['data' => $this->landscapeRequestRepository->find($id)]);
    }

    public function destroy($id)
    {
        $this->landscapeRequestRepository->delete($id);
        return response()->json(['message' => 'Deleted successfully.']);
    }
}
