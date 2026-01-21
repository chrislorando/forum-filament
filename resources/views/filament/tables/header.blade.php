<div>
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

    @php
        $visibleActions = array_filter(
            $this->getTable()->getHeaderActions(), 
            fn ($action) => $action->isVisible()
        );
    @endphp

    @if (count($visibleActions) > 0)
        <div class="flex items-center justify-end gap-x-2 border-b border-gray-200 dark:border-white/10 px-3 py-3 sm:px-6">
            @foreach ($visibleActions as $action)
                {{ $action }}
            @endforeach
        </div>
    @endif
</div>