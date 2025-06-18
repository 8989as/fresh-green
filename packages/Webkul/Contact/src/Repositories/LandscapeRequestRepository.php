<?php

namespace Webkul\Contact\Repositories;

use Webkul\Contact\Models\LandscapeRequest;

class LandscapeRequestRepository
{
    public function all()
    {
        return LandscapeRequest::all();
    }

    public function find($id)
    {
        return LandscapeRequest::findOrFail($id);
    }

    public function create(array $data)
    {
        return LandscapeRequest::create($data);
    }

    public function update($id, array $data)
    {
        $request = LandscapeRequest::findOrFail($id);
        $request->update($data);
        return $request;
    }

    public function delete($id)
    {
        return LandscapeRequest::destroy($id);
    }
}
