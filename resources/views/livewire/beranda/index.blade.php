<div class="row">
  <div class="col-12">
    @if ($belumlogin > 0)
    <div class="alert alert-danger">
      <a class="alert-link">{{ Str::upper(Auth::user()->name) }}</a> Belum update profil
    </div>
    @endif
  </div>
  <div class="col-md-6">
    <div class="ibox ">
      <div class="ibox-title">
        <h5>Materi Terbaru</h5>
        <div class="ibox-tools">
          <a class="collapse-link">
            <i class="fa fa-chevron-up"></i>
          </a>
          <a class="close-link">
            <i class="fa fa-times"></i>
          </a>
        </div>
      </div>
      <div class="ibox-content table-responsive">
        <table class="table table-hover no-margins">
          <thead>
            <tr>
              <th>No</th>
              <th>Judul</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($materi as $row)
            <tr>
              <td class="align-middle">{{ $loop->iteration }}</td>
              <td class="align-middle">{{ $row->judul }}</td>
              <td>
                @if (in_array(Auth::user()->id,json_decode($row->baca) ?? []))
                <span class="label label-primary">Sudah dibaca</span>
                @else
                <span class="label label-danger">Belum dibaca</span>
                @endif
                <a href="{{ route('materi.baca', ['id'=> $row->id ]) }}" class="btn btn-xs btn-outline btn-primary"
                  target="_blank" wire:click="baca({{ $row->id }})">Baca <i class="fa fa-long-arrow-right"></i>
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td class="text-center" colspan="3">Tidak ada data</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="ibox ">
      <div class="ibox-title">
        <h5>Tugas Terbaru</h5>
        <div class="ibox-tools">
          <a class="collapse-link">
            <i class="fa fa-chevron-up"></i>
          </a>
          <a class="close-link">
            <i class="fa fa-times"></i>
          </a>
        </div>
      </div>
      <div class="ibox-content table-responsive">
        <table class="table table-hover no-margins">
          <thead>
            <tr>
              <th>No</th>
              <th>Judul</th>
              <th>Nilai</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center" colspan="4">Tidak ada data</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>