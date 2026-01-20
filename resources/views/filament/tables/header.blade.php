<div class="">

    @php
    $records = $table->getRecords()
    @endphp
    @if ($records instanceof \Illuminate\Contracts\Pagination\Paginator)
        <x-filament::pagination
            :extreme-links="true"
            :paginator="$records"
            class="fi-ta-pagination px-3 py-3 sm:px-6 border-solid border-b border-b-gray-200 border-t-0 dark:border-white/10"
        />
    @endif

    <div class="flex items-center justify-end gap-x-2 border-b border-b-gray-200 border-t-0 dark:border-white/10 px-3 py-3 sm:px-6">

        @foreach ($this->getTable()->getHeaderActions() as $action)
            {{ $action }}
        @endforeach
    </div>
</div>