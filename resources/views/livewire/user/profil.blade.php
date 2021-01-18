<div class="row">
	<div class="col col-12">
		<div class="ibox">
			<div class="ibox-title">
				<h5>Profil User</h5>
			</div>
			<div class="ibox-content">
				<div class="form-group row">
					<label class="col-md-3 col-form-label">Photo</label>
					<div class="col-md-9">
						{!! Form::file('photo', ['class' => '', 'wire:model' => 'photo']) !!}
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3 col-form-label">NISN</label>
					<div class="col-md-9 input-group @error('nisn') has-error @enderror">
						<input type="text" class="form-control" wire:model="nisn" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3 col-form-label">Nama Pengguna</label>
					<div class="col-md-9 input-group @error('name') has-error @enderror">
						<input type="text" class="form-control" wire:model="name">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3 col-form-label">Nomor Telephone</label>
					<div class="col-md-9 input-group @error('hp') has-error @enderror">
						<input type="number" class="form-control" wire:model="hp">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3 col-form-label">Password Lama</label>
					<div class="col-md-9 input-group @error('old_password') has-error @enderror">
						<input type="password" class="form-control" wire:model="old_password">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-3 col-form-label">Password Baru</label>
					<div class="col-md-9 input-group @error('new_password') has-error @enderror">
						<input type="password" class="form-control" wire:model="new_password">
					</div>
				</div>
			</div>
			<div class="ibox-footer pb-5">
				<span class="float-right">
					<a href="{{ url('/') }}" class="btn btn-white btn-sm">Tutup</a>
					<button class="btn btn-primary btn-sm" wire:click.prevent="save()">Simpan</button>
				</span>
			</div>
		</div>
	</div>
</div>