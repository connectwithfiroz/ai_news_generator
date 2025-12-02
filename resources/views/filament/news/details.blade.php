@php
    $summarize_title = $record->rewritten_title ?? $record->summarize_response_json['title'] ?? '';
    $summarize_description = $record->rewritten_description ?? $record->summarize_response_json['description'] ?? '';
    $source = $record->response['url'] ?? '';
    if($source){
        $source = "\nSource URL- $source";
    }
    $copy_data = ($summarize_title ?? '') . " \n\n" . ($summarize_description ?? '')  . $source;
@endphp
<div class="w-full block">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- LEFT CONTENT --}}
        <div class="space-y-4">
            {{-- Image --}}
            @if(!empty($record->response['image']))
                <img src="{{ $record->response['image'] }}" alt="news image" class="w-full rounded-md shadow">
            @endif



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
                    {{ $source }}

                    {{-- Copy button --}}
                    <button x-data x-on:click="navigator.clipboard.writeText($el.dataset.copy)"
                        data-copy="{{ $copy_data }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded w-full text-center">
                        Copy for Facebook/LinkedIn
                    </button>


                </div>
            @endif

        </div>
    </div>
</div>