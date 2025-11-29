<div class="space-y-2">
    <h2 class="text-lg font-semibold">
        {{ $record->summarize_response_json['title'] ?? '' }}
    </h2>

    <p class="text-sm whitespace-pre-line">
        {{ $record->summarize_response_json['description'] ?? '' }}
    </p>
</div>
