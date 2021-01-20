<div class="row">
	<div class="col col-md-6" wire:click="tbl_name('profil')">
		<div class="ibox">
			<div class="ibox-title">
				<h5>Belum Update Profil</h5>
			</div>
			<div class="ibox-content">
				@if (Auth::user()->level == 9)
				<div class="mb-2">
					<div class="form-group row">
						<div class="col-md-12">
							{!! Form::select('kelas_id', $arr_kelas,null,
							['class' => 'form-control', 'wire:model' => 'kelas_id', 'placeholder' => '-- Pilih Kelas --']) !!}
						</div>
					</div>
				</div>
				@endif
				<table class="table table-bordered">
					<thead class="text-center">
						<tr>
							<th style="width: 5%;">No</th>
							<th style="width: 5%;">Photo</th>
							<th>Nama Lengkap</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($belumlogin as $user)
						<tr>
							<td class="align-middle">
								{{ ($belumlogin->currentpage()-1) * $belumlogin->perpage() + $loop->index + 1 }}</td>
							<td class="align-middle text-center"><img src="{{ $user->photo_url }}" class="img-sm"></td>
							<td class="align-middle">{{ $user->name }}</td>
						</tr>
						@empty
						<tr>
							<td class="text-center" colspan="3">Tidak ada data</td>
						</tr>
						@endforelse
					</tbody>
				</table>
				{{ $belumlogin->links() }}
			</div>
		</div>
	</div>
</div>