{{--
  <x-empty-state colspan="8" message="No products match this search." />
  Standard "nothing here" table row, used instead of hand-writing the same
  colspan/markup on every list page.
--}}
@props(['colspan' => 1, 'message' => 'Nothing to show yet.'])
<tr>
    <td colspan="{{ $colspan }}" class="px-5 py-10 text-center muted">{{ $message }}</td>
</tr>
