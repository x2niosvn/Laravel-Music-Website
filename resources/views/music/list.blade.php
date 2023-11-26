@section('title', 'List Music')

<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Music List
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Your music list.
                    </p>
                </header>

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="text-gray-200" id="musicTable" class="display">
                    <thead>
                        <tr>
                            <th class="text">Title</th>
                            <th class="text">Artist</th>
                            <th class="text">Genre</th>
                            <th class="text">Image</th>
                            <th class="text">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($musics as $music)
                            <tr>
                                <td>{{ $music->title }}</td>
                                <td>{{ $music->artist }}</td>
                                <td>{{ $music->genre }}</td>
                                <td>
                                    @if($music->img_path)
                                    <img src="{{ route('musicm.img', ['filename' => $music->img_path]) }}" alt="">
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                        href="{{ route('music.edit', ['id' => $music->id]) }}">Edit</a>
                                    <a class="px-4 py-2 bg-red-800 dark:bg-red-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" href="{{ route('music.destroy', ['id' => $music->id]) }}"
                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $music->id }}').submit();">
                                        Delete
                                    </a>
                                    <form id="delete-form-{{ $music->id }}"
                                        action="{{ route('music.destroy', ['id' => $music->id]) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>


</x-app-layout>
