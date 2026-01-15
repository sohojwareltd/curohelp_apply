@php
    $candidate = $getRecord();
    $roles = $candidate->jobRoles;
@endphp

@if($roles->count())
    <div class="space-y-1">
        @foreach($roles as $role)
            <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded">
                {{ $role->name }}
            </span>
        @endforeach
    </div>
@else
    <span class="text-gray-500 dark:text-gray-400">—</span>
@endif
