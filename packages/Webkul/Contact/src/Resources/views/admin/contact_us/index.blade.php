<x-admin::layouts>
    <x-slot:title>
        Contact Us Submissions
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Contact Us Submissions
        </p>
    </div>

    <div class="mt-8 bg-white dark:bg-gray-800 rounded shadow">
        <div class="p-4">
            <table class="w-full">
                <thead>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contactUs as $contact)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-2">{{ $contact->id }}</td>
                            <td class="px-4 py-2">{{ $contact->name }}</td>
                            <td class="px-4 py-2">{{ $contact->phone }}</td>
                            <td class="px-4 py-2">{{ $contact->email }}</td>
                            <td class="px-4 py-2">{{ $contact->message }}</td>
                            <td class="px-4 py-2">{{ $contact->created_at }}</td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('admin.contact.contact-us.destroy', $contact->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</x-admin::layouts>
