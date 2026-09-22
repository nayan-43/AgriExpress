{{--
  admin/reviews/index.blade.php
  Controller: App\Http\Controllers\Admin\ReviewController@index
--}}
@extends('admin.layouts.admin')

@section('title', 'Reviews')
@section('active', 'reviews')

@section('content')
<div class="flex flex-wrap gap-3 items-center justify-between mb-5">
    <div><h1 class="text-2xl font-bold">Reviews</h1><p class="muted text-sm mt-1">Moderate product reviews before they go live</p></div>
</div>

<div class="surface border rounded-2xl">
    <div class="flex gap-6 px-5 border-b bd overflow-x-auto text-sm">
        @php $activeStatus = request('status', 'pending'); @endphp
        @foreach (['pending' => 'Pending', 'approved' => 'Approved'] as $key => $label)
            <a href="{{ route('admin.reviews.index', ['status' => $key]) }}"
               class="py-3.5 font-medium border-b-2 whitespace-nowrap {{ $activeStatus === $key ? 'border-blue-600 text-blue-600' : 'border-transparent muted' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="divide-y" style="border-color:var(--border)">
        @forelse ($reviews as $review)
            <div class="p-5 flex flex-col sm:flex-row gap-4">
                <div class="flex items-center gap-3 sm:w-56 shrink-0">
                    <x-thumb :icon="'fa-box'" tone="bg-slate-100 text-slate-600" size="w-11 h-11" />
                    <div class="min-w-0">
                        <p class="font-medium text-sm truncate">{{ $review->product->name ?? 'Deleted product' }}</p>
                        <p class="muted text-[12px]">{{ $review->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <x-avatar :name="$review->user->name ?? 'Guest'" />
                        <span class="font-medium text-sm">{{ $review->user->name ?? 'Guest' }}</span>
                        @if ($review->is_verified_purchase)
                            <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-100 rounded-full px-2 py-0.5">Verified purchase</span>
                        @endif
                        <span class="ml-auto sm:ml-0 flex items-center gap-0.5 text-amber-400 text-[13px]">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </span>
                    </div>
                    @if ($review->title)
                        <p class="font-medium text-sm mt-2">{{ $review->title }}</p>
                    @endif
                    @if ($review->comment)
                        <p class="muted text-[13px] mt-1 leading-relaxed">{{ $review->comment }}</p>
                    @endif
                </div>

                <div class="flex sm:flex-col gap-2 sm:w-32 shrink-0">
                    <form action="{{ route('admin.reviews.updateStatus', $review) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $review->status ? 0 : 1 }}">
                        <button type="submit" class="w-full border bd rounded-lg h-9 text-[13px] font-medium {{ $review->status ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ $review->status ? 'Unpublish' : 'Approve' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this review?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full border bd rounded-lg h-9 text-[13px] font-medium text-red-500">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="p-10 text-center muted text-sm">Nothing to moderate here.</p>
        @endforelse
    </div>

    <x-pagination-bar :paginator="$reviews" />
</div>
@endsection
