<x-layouts.app>
<div class="mb-7"><p class="text-sm font-bold tracking-widest text-red-700">STUDENT DISCIPLINE OFFICE</p><h1 class="mt-2 text-3xl font-black">{{ $title }}</h1><p class="mt-2 text-zinc-500">{{ $subtitle }}</p></div>
<section class="office-panel"><div class="table-scroll"><table class="case-table"><thead><tr>@foreach($columns as $column)<th>{{ $column }}</th>@endforeach</tr></thead><tbody>@forelse($rows as $row)<tr>@foreach((array) $row as $value)<td>{{ $value ?? '—' }}</td>@endforeach</tr>@empty<tr><td colspan="{{ count($columns) }}" class="empty-state">No records available.</td></tr>@endforelse</tbody></table></div></section>
@if($rows instanceof \Illuminate\Contracts\Pagination\Paginator)<div class="mt-5">{{ $rows->links() }}</div>@endif
</x-layouts.app>
