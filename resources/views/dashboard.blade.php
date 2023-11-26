@section('title', 'Dashboard Music')
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-lg font-medium">MUXIC - online sharing music website!</h2>
                    <div class="flex justify-end mb-4 mt-2 p-2">
                        <form action="{{ route('music.search') }}" method="GET">
                            @csrf
                            <input type="text" name="query" class="text-gray-600 border rounded p-2" placeholder="Search for songs">
                            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Search</button>
                        </form>
                    </div>                                      
                    <div class="container mx-auto mt-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                            @foreach($songs as $song)
                            <div class="relative p-4 bg-gray-300 rounded-lg shadow-md">
                                @if($song->img_path)
                                <div class="relative p-4 bg-gray-300 rounded-lg shadow-md">
                                    <img src="{{ route('music.img', ['filename' => $song->img_path]) }}" alt="" class="w-full h-auto mx-auto">
                                </div>
                            @endif
                                <div class="mb-4 mt-2">
                                    <div class="flex justify-between items-center"> 

                                        <div>
                                            <h3 class="text-2xl text-black font-medium">{{ $song->title }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ $song->artist }}</p>
                                        </div>

                                    </div>
                                </div>
                                <audio controls class="w-full audio-player">
                                    <source src="{{ route('music.stream', ['filename' => $song->file_path]) }}" type="audio/mpeg">
                                </audio>
                                
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-sm  text-left px-2 py-1 rounded-full bg-gray-400 text-white">Tag: {{ $song->genre }}</span>
                                    <p class="text-sm text-gray-600 text-right"><b>Upload by:</b> {{ $song->user->name }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
