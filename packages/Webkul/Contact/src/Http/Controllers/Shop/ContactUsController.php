<?php

namespace Webkul\Contact\Http\Controllers\Shop;

use Webkul\Contact\Http\Requests\ContactUsFormRequest;
use Webkul\Contact\Repositories\ContactUsRepository;
use Illuminate\Routing\Controller;

class ContactUsController extends Controller
{
    protected $contactUsRepository;

    public function __construct(ContactUsRepository $contactUsRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
    }

    public function store(ContactUsFormRequest $request)
    {
        $data = $request->validated();
        $contactUs = $this->contactUsRepository->create($data);
        return response()->json(['data' => $contactUs, 'message' => 'Contact form submitted successfully.'], 201);
    }
}
