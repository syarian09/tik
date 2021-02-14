<div class="row">
  <div class="col col-12">
    <div class="ibox">
      <div class="ibox-title">
        <h5>Data Ujian</h5>
        <div class="ibox-tools">
          <a href="{{ route('ujian.add') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
          <button class="btn btn-danger btn-sm" wire:click="delete()">
            <i class="fa fa-trash"></i>
          </button>
        </div>
      </div>
      <div class="ibox-content">
        <div class="mb-2 row">
          <div class="col-md-3">
            {!! Form::select('kelas_id', $arr_kelas, null, ['class' => 'form-control', 'wire:model' => 'kelas_id',
            'placeholder' => '-- Pilih Kelas --']) !!}
          </div>
          <div class="col-md-9">
            <input type="text" class="form-control" wire:model="judul" placeholder="Judul Ulangan">
          </div>
        </div>
        <table class="table table-bordered">
          <thead class="text-center">
            <tr>
              <td style="width: 4%"><input type="checkbox" class="i-checks" wire:model="selectAll"></td>
              <th>Judul Ulangan</th>
              <th>Kelas</th>
              <th style="width: 10%;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($data ?? [] as $row)
              <tr>
                <td class="text-center align-middle">
                  <input type="checkbox" class="i-checks" wire:model="ch_id" value="{{ $row->id }}">
                </td>
                <td class="align-middle">{{ $row->judul }}</td>
                <td class="align-middle">{{ $row->nama_kelas }}</td>
                <td class="align-middle text-center">
                  <a class="btn btn-warning btn-sm tooltips" href="{{ route('ujian.edit', ['id' => $row->id]) }}">
                    <i class="fa fa-pencil"></i>
                    <span class="tooltipstext">Edit</span>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td class="text-center" colspan="5">Tidak ada data</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
