@php
$summarize_title = $record->rewritten_title ?? $record->summarize_response_json['title'] ?? '';
$summarize_description = $record->rewritten_description ?? $record->summarize_response_json['description'] ?? '';
@endphp
<div class="w-full block" x-data="{ text: `{{ $record->title }}\n\n{{ $record->description }}` }">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- LEFT CONTENT --}}
        <div class="space-y-4">
            {{-- Image --}}
            @if(!empty($record->response['image']))
            <img src="{{ $record->response['image'] }}" alt="news image" class="w-full rounded-md shadow">
            @endif


            {{-- Summary --}}
            <h6 class="text-center text-blue-600 m-1 p-1">Summarize</h6>
            <div class="space-y-2">
                <h2 class="text-lg font-semibold">
                    {{ $summarize_title }}
                </h2>

                <p class="text-sm whitespace-pre-line">
                    {{ $summarize_description }}
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



        </div> {{-- end left col --}}

        {{-- RIGHT CONTENT: Final Section --}}
        <div class="space-y-4">
            {{-- Local processed image --}}
            @if($record->local_image_path)
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-1">Generated Image Preview:</h3>
                <img src="{{ asset('storage/' . $record->local_image_path) }}" class="w-full rounded-md shadow">
            </div>
            @endif
            @if(!empty($summarize_title) || !empty($summarize_description))
            <div class="final-section p-4 border border-gray-300 rounded-lg">

                <h2 class="text-lg font-bold mb-3">Final (For Posting)</h2>

                {{-- Title (Bold + bigger) --}}
                @if(!empty($summarize_title))
                <h3 class="text-xl font-semibold mb-2">
                    {{ $summarize_title }}
                </h3>
                @endif

                {{-- Description with spacing + readable formatting --}}
                @if(!empty($summarize_description))
                <p class="leading-relaxed text-gray-800 mb-4 whitespace-pre-line">
                    {{ $summarize_description }}
                </p>
                @endif

                {{-- Copy button --}}
                <button @click="navigator.clipboard.writeText(text)" class="px-3 py-2 bg-blue-500 text-white rounded">
                    Copy
                </button>


            </div>
            @endif

        </div>
    </div>
</div>