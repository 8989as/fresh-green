<x-admin::layouts>
    <x-slot:title>
        Landscape Requests
    </x-slot>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Landscape Requests
        </p>
    </div>

    <div class="mt-8 bg-white dark:bg-gray-800 rounded shadow">
        <div class="p-4">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-900">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Phone</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">City</th>
                        <th class="px-4 py-2 text-left">Landscape Area</th>
                        <th class="px-4 py-2 text-left">Message</th>
                        <th class="px-4 py-2 text-left">Created At</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($landscapeRequests as $request)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-2">{{ $request->id }}</td>
                            <td class="px-4 py-2">{{ $request->name }}</td>
                            <td class="px-4 py-2">{{ $request->phone }}</td>
                            <td class="px-4 py-2">{{ $request->email }}</td>
                            <td class="px-4 py-2">{{ $request->city }}</td>
                            <td class="px-4 py-2">{{ $request->landscape_area }}</td>
                            <td class="px-4 py-2">{{ $request->message }}</td>
                            <td class="px-4 py-2">{{ $request->created_at }}</td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('admin.contact.landscape-requests.destroy', $request->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</x-admin::layouts>
