@if ($paginator->hasPages())
<div class="row">
	<div class="col col-12 d-flex justify-content-center">
		<ul class="pagination">
			{{-- Previous Page Link --}}
			@if ($paginator->onFirstPage())
			<li class="page-item disabled">
				<a class="page-link"><span aria-hidden="true">&laquo;</span></a>
			</li>
			@else
			<li class="page-item">
				<a class="page-link" wire:click="previousPage" id="previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>
			@endif

			{{-- Pagination Elements --}}
			@foreach ($elements as $element)
			@if (is_string($element))
			<li class="page-item">
				<a class="page-link">{{ $element }}</a>
			</li>
			@endif

			{{-- Array Of Links --}}
			@if (is_array($element))
			@foreach ($element as $page => $url)
			@if ($page == $paginator->currentPage())
			<li class="page-item active">
				<a class="page-link">{{ $page }}</a>
			</li>
			@else
			<li class="page-item">
				<a class="page-link" href="{{ $url }}" id="{{$page}}"> {{ $page }} </a>
			</li>
			@endif
			@endforeach
			@endif
			@endforeach

			{{-- Next Page Link --}}
			@if ($paginator->hasMorePages())
			<li class="page-item">
				<a class="page-link" wire:click="nextPage" id="next"><span aria-hidden="true">&raquo;</span></a>
			</li>
			@else
			<li class="page-item disabled">
				<a class="page-link"><span aria-hidden="true">&raquo;</span></a>
			</li>
			@endif
		</ul>
	</div>
</div>
@endif