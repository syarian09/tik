<div class="row">
	<div class="col col-12">
		<div class="ibox">
			<div class="ibox-title">
				<h5>Data User</h5>
			</div>
			<div class="ibox-content">
				<div class="mb-2">
					<div class="form-group row">
						<input type="hidden" wire:model="user_id">
						<div class="col-md-3 @error('kelas_id') has-error @enderror" wire:key="key_1">
							{!! Form::select('kelas_id', $arr_kelas,null,
							['class' => 'form-control', 'wire:model' => 'kelas_id', 'placeholder' => '-- Pilih Kelas --']) !!}
						</div>
						<div class="col-md-4 input-group @error('name') has-error @enderror">
							<input type="text" class="form-control" wire:model="name" placeholder="Nama Siswa">
						</div>
						<div class="col-md-5 input-group @error('nisn') has-error @enderror">
							<input type="text" class="form-control" wire:model="nisn" placeholder="NISN">
							<div class="input-group-append">
								<button class="btn btn-primary ml-1 tooltips" wire:click="store()">
									<i class="fa fa-plus"></i>
									<span class="tooltipstext">Tambah</span>
								</button>
								<button class="btn btn-dark tooltips" wire:click="resetFields()">
									<i class="fa fa-repeat"></i>
									<span class="tooltipstext">Bersih</span>
								</button>
								<button class="btn btn-danger tooltips ml-1" wire:click="delete()">
									<i class="fa fa-trash"></i>
									<span class="tooltipstext">Hapus</span>
								</button>
								<button class="btn btn-success tooltips" wire:click="resetPass()">
									<i class="fa fa-unlock"></i>
									<span class="tooltipstext">Reset Pass</span>
								</button>
							</div>
						</div>
					</div>
				</div>

				<table class="table table-bordered">
					<thead class="text-center">
						<tr>
							<td style="width: 4%"><input type="checkbox" class="i-checks" wire:model="selectAll"></td>
							<th style="width: 5%;">Photo</th>
							<th>Nama Lengkap</th>
							<th>NISN</th>
							<th>Kelas</th>
							<th style="width: 10%;">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($users as $user)
						<tr>
							<td class="text-center align-middle">
								<input type="checkbox" class="i-checks" wire:model="chuser_id" value="{{ $user->id }}">
							</td>
							<td class="align-middle text-center"><img src="{{ $user->photo_url }}" class="img-sm"></td>
							<td class="align-middle">{{ $user->name }}</td>
							<td class="align-middle">{{ $user->nisn }}</td>
							<td class="align-middle">{{ $user->nama_kelas }}</td>
							<td class="align-middle text-center">
								<button class="btn btn-warning btn-sm tooltips" wire:click="edit({{ $user->id }})">
									<i class="fa fa-pencil"></i>
									<span class="tooltipstext">Edit</span>
								</button>
							</td>
						</tr>
						@empty
						<tr>
							<td class="text-center" colspan="8">Tidak ada data</td>
						</tr>
						@endforelse
					</tbody>
				</table>
				{{ $users->links() }}
			</div>
		</div>
	</div>
</div>