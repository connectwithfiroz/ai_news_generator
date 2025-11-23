<div class="p-4 space-y-4">

    {{-- Title --}}
    <h2 class="text-xl font-bold">
        {{ $record->response['title'] ?? 'No Title' }}
    </h2>

    {{-- Image --}}
    @if(!empty($record->response['image']))
        <img 
            src="{{ $record->response['image'] }}" 
            alt="news image" 
            class="w-full rounded-md shadow"
        >
    @endif

    {{-- Summary --}}
    @if($record->summarize_response)
        <div class="mt-4">
            <h3 class="text-lg font-semibold mb-1">Hindi Summary:</h3>
            <p class="whitespace-pre-line text-gray-800">
                {{ $record->summarize_response }}
            </p>
        </div>
    @endif

    {{-- Description --}}
    <div class="mt-4">
        <h3 class="text-lg font-semibold mb-1">Original Description:</h3>
        <p class="whitespace-pre-line text-gray-700">
            {{ $record->response['description'] ?? 'No Description' }}
        </p>
    </div>

    {{-- Metadata --}}
    <div class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-600">

        <div>
            <strong>Source:</strong> {{ $record->response['source'] ?? 'N/A' }} <br>
            <strong>Author:</strong> {{ $record->response['author'] ?? 'N/A' }}
        </div>

        <div>
            <strong>Category:</strong> {{ $record->response['category'] ?? 'N/A' }} <br>
            <strong>Language:</strong> {{ $record->response['language'] ?? 'N/A' }}
        </div>

        <div>
            <strong>Published At:</strong>
            {{ isset($record->response['published_at']) ? \Carbon\Carbon::parse($record->response['published_at'])->format('d M Y, H:i') : 'N/A' }}
        </div>

        <div>
            <strong>Original URL:</strong>
            <a href="{{ $record->response['url'] ?? '#' }}"
               class="text-blue-600 underline"
               target="_blank">
                View Source
            </a>
        </div>
    </div>

    {{-- Local processed image --}}
    @if($record->local_image_path)
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-1">Generated Image Preview:</h3>
            <img 
                src="{{ asset('storage/' . $record->local_image_path) }}"
                class="w-full rounded-md shadow"
            >
        </div>
    @endif

</div>
