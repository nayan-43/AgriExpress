@props(['editRoute' => '#', 'deleteRoute' => null])
<div class="flex items-center gap-2 text-[13px]">
    <a href="{{ $editRoute }}" class="w-8 h-8 rounded-lg border bd grid place-items-center text-blue-600" aria-label="Edit">
        <i class="fa-regular fa-pen-to-square"></i>
    </a>
    @if($deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('Delete this item? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-8 h-8 rounded-lg border bd grid place-items-center text-red-500" aria-label="Delete">
                <i class="fa-regular fa-trash-can"></i>
            </button>
        </form>
    @endif
    <button type="button" class="w-8 h-8 rounded-lg grid place-items-center muted" aria-label="More">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>
</div>
