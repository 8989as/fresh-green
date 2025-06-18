<?php

namespace Webkul\Contact\Http\Controllers\Admin;

use Webkul\Contact\Repositories\ContactUsRepository;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    protected $contactUsRepository;

    public function __construct(ContactUsRepository $contactUsRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
    }

    public function index()
    {
        $contactUs = $this->contactUsRepository->all();
        return view('contact::admin.contact_us.index', compact('contactUs'));
    }

    public function show($id)
    {
        return response()->json(['data' => $this->contactUsRepository->find($id)]);
    }

    public function destroy($id)
    {
        $this->contactUsRepository->delete($id);
        return response()->json(['message' => 'Deleted successfully.']);
    }
}
