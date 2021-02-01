<div class="row">
  <div class="col col-md-6" wire:click="tbl_name('profil')">
    <div class="ibox">
      <div class="ibox-title">
        <h5>Status Baca Materi Siswa</h5>
      </div>
      <div class="ibox-content">

        <div class="mb-2">
          <div class="form-group row">
            <div class="col-md-12">
              {!! Form::select('kelas_id', $arr_kelas,null,
              ['class' => 'form-control', 'wire:model' => 'kelas_id', 'placeholder' => '-- Pilih Kelas --']) !!}
            </div>
          </div>
        </div>

        <table class="table table-bordered">
          <thead class="text-center">
            <tr>
              <th style="width: 5%;">No</th>
              <th style="width: 5%;">Photo</th>
              <th>Nama Lengkap</th>
              <th>Materi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($belumbaca as $user)
            <tr>
              <td class="align-middle">
                {{ ($belumbaca->currentpage()-1) * $belumbaca->perpage() + $loop->index + 1 }}</td>
              <td class="align-middle text-center"><img src="{{ $user->photo_url }}" class="img-sm"></td>
              <td class="align-middle">{{ $user->name }}</td>
              <td class="align-middle">
                @foreach ($user->materis() as $baca)
                {{ Str::limit($baca['judul'], 20) }}
                @if ($baca['baca'] == 'Sudah dibaca')
                <span class="label label-primary"> {{ $baca['baca'] }}</span>
                @else
                <span class="label badge-danger"> {{ $baca['baca'] }}</span>
                @endif
                <br>
                @endforeach
              </td>
            </tr>
            @empty
            <tr>
              <td class="text-center" colspan="3">Tidak ada data</td>
            </tr>
            @endforelse
          </tbody>
        </table>
        {{ $belumbaca->links() }}
      </div>
    </div>
  </div>
</div>