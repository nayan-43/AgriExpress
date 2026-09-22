{{--
  <x-pagination-bar :paginator="$products" />
  Thin wrapper so every list page renders Laravel's paginator with the same
  look as the rest of the UI. Pass any LengthAwarePaginator.
--}}
@props(['paginator'])
@if($paginator instanceof \Illuminate\Contracts\Pagination\Paginator || $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="p-4 flex flex-wrap gap-3 items-center justify-between text-sm">
        <p class="muted">
            Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results
        </p>
        <div>{{ $paginator->onEachSide(1)->links() }}</div>
    </div>
@endif
