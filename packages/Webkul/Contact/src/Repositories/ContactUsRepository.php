<?php

namespace Webkul\Contact\Repositories;

use Webkul\Contact\Models\ContactUs;

class ContactUsRepository
{
    public function all()
    {
        return ContactUs::all();
    }

    public function find($id)
    {
        return ContactUs::findOrFail($id);
    }

    public function create(array $data)
    {
        return ContactUs::create($data);
    }

    public function update($id, array $data)
    {
        $contact = ContactUs::findOrFail($id);
        $contact->update($data);
        return $contact;
    }

    public function delete($id)
    {
        return ContactUs::destroy($id);
    }
}
