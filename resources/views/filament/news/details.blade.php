<div class="p-4 space-y-4">
    {{-- Image --}}
    @if(!empty($record->response['image']))
        <img src="{{ $record->response['image'] }}" alt="news image" class="w-full rounded-md shadow">
    @endif


    {{-- Summary --}}
    <h6 class="text-center text-blue-600 m-1 p-1">Summarize</h6>
        <div class="space-y-2">
            <h2 class="text-lg font-semibold">
                {{ $record->rewritten_title ?? $record->summarize_response_json['title'] ?? 'No' }}
            </h2>

            <p class="text-sm whitespace-pre-line">
                {{ $record->rewritten_description ?? $record->summarize_response_json['description'] ?? '' }}
            </p>
        </div>


    <h6 class="text-center text-blue-600 m-1 p-1">Original</h6>
    {{-- Title --}}
    <h4 class="text-xl font-bold">
        {{ $record->response['title'] ?? 'No Title' }}
    </h4>
    {{-- Description --}}
    <div class="mt-2">
        <p class="whitespace-pre-line">
            {{ $record->response['description'] ?? 'No Description' }}
        </p>
    </div>

    {{-- Metadata --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm">

        {{-- Source --}}
        <div class="space-y-1">
            <div class="">
                <span class="font-medium">Source:</span>
                <span>{{ $record->response['source'] ?? 'N/A' }}</span>
            </div>

            <div class="">
                <span class="font-medium">Author:</span>
                <span>{{ $record->response['author'] ?? 'N/A' }}</span>
            </div>
        </div>

        {{-- Category --}}
        <div class="space-y-1">
            <div class="">
                <span class="font-medium">Category:</span>
                <span>{{ $record->response['category'] ?? 'N/A' }}</span>
            </div>

            <div class="">
                <span class="font-medium">Language:</span>
                <span>{{ $record->response['language'] ?? 'N/A' }}</span>
            </div>
        </div>

        {{-- Publish Date --}}
        <div class="">
            <span class="font-medium">Published At:</span>
            <span>
                {{ isset($record->response['published_at'])
    ? \Carbon\Carbon::parse($record->response['published_at'])->format('d M Y, H:i')
    : 'N/A'
            }}
            </span>
        </div>

        {{-- URL --}}
        <div class="">
            <span class="font-medium">Original URL:</span>
            <a href="{{ $record->response['url'] ?? '#' }}" class="text-blue-600 underline" target="_blank">
                View Source
            </a>
        </div>

    </div>


    {{-- Local processed image --}}
    @if($record->local_image_path)
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-1">Generated Image Preview:</h3>
            <img src="{{ asset('storage/' . $record->local_image_path) }}" class="w-full rounded-md shadow">
        </div>
    @endif

</div>