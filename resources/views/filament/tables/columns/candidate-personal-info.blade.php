@php
    $candidate = $getRecord();
@endphp

<div class="space-y-1">
    <div class="font-semibold text-gray-900 dark:text-white">
        {{ $candidate->full_name }} {{ $candidate->last_name }}
    </div>
    <div class="text-sm text-gray-600 dark:text-gray-400">
        {{ $candidate->email }}
    </div>
    <div class="text-sm text-gray-600 dark:text-gray-400">
        {{ $candidate->phone }}
    </div>
    @if($candidate->nearest_city || $candidate->postcode)
        <div class="text-sm text-gray-600 dark:text-gray-400">
            @if($candidate->nearest_city)
                {{ $candidate->nearest_city }}
            @endif
            @if($candidate->postcode)
                @if($candidate->nearest_city), @endif
                {{ $candidate->postcode }}
            @endif
        </div>
    @endif
</div>
