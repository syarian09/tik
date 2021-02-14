<div class="row">
  <div class="col col-12">
    <div class="ibox">
      <div class="ibox-title">
        <h5>Tambah Soal Ulangan</h5>
        <div class="ibox-tools">
          <button class="btn btn-primary btn-sm" wire:click="tambahSoal">
            <i class="fa fa-plus"></i>
            <span>Tambah Soal</span>
          </button>
        </div>
      </div>
      <div class="ibox-content">
        <div class="form-group row">
          <input type="hidden" wire:model="ulangan_id">
          <label class="col-md-2">Kelas</label>
          <div class="col-md-10">
            @foreach ($arr_kelas as $kls)
              <label class="mr-2"><input type="checkbox" class="i-checks" wire:model="kelas"
                  value="{{ $kls->id }}">
                {{ $kls->kelas }}</label>
            @endforeach
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-2 col-form-label">Judul Ulangan</label>
          <div class="col-md-10 input-group">
            <input type="text" class="form-control" wire:model="judul">
          </div>
        </div>
        <div class="form-group row">
          <div class="input-group col-md-4">
            <div class="input-group-prepend">
              <span class="input-group-addon">Tanggal Awal</span>
            </div>
            <input id="tgl_awal" type="text" class="form-control datepicker" wire:model="tgl_awal">
          </div>
          <div class="input-group col-md-4">
            <div class="input-group-prepend">
              <span class="input-group-addon">Tanggal Akhir</span>
            </div>
            <input id="tgl_akhir" type="text" class="form-control datepicker" wire:model="tgl_akhir">
          </div>
          <div class="input-group col-md-4">
            <div class="input-group-prepend">
              <span class="input-group-addon">Waktu</span>
            </div>
            <input type="number" class="form-control" wire:model="waktu">
          </div>
        </div>
      </div>
    </div>
  </div>

  @foreach ($data as $key => $item)
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-3">
              <div class="input-group m-b">
                <div class="input-group-prepend">
                  <span class="input-group-addon">Nomor Soal</span>
                </div>
                <input type="text" class="form-control" wire:model="data.{{ $key }}.no">
                <div class="input-group-append">
                  <button class="btn btn-danger" wire:click="hapusSoal({{ $key }})">
                    <i class="fa fa-trash"></i></button>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="input-group m-b">
                <div class="input-group-prepend">
                  <span class="input-group-addon">Jawaban</span>
                </div>
                <input type="text" class="form-control" wire:model="data.{{ $key }}.jawaban">
              </div>
            </div>
            <div class="col-md-12">
              <textarea wire:model="data.{{ $key }}.soal" rows="5" class="form-control m-b"></textarea>
            </div>
            <div class="col-md-6">
              <div class="input-group m-b">
                <div class="input-group-prepend">
                  <span class="input-group-addon">Pilihan A</span>
                </div>
                <input type="text" class="form-control" wire:model="data.{{ $key }}.jwbA">
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group m-b">
                <div class="input-group-prepend">
                  <span class="input-group-addon">Pilihan B</span>
                </div>
                <input type="text" class="form-control" wire:model="data.{{ $key }}.jwbB">
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group m-b">
                <div class="input-group-prepend">
                  <span class="input-group-addon">Pilihan C</span>
                </div>
                <input type="text" class="form-control" wire:model="data.{{ $key }}.jwbC">
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group m-b">
                <div class="input-group-prepend">
                  <span class="input-group-addon">Pilihan D</span>
                </div>
                <input type="text" class="form-control" wire:model="data.{{ $key }}.jwbD">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach

  <div class="col col-12 mb-3">
    <div class="row">
      <div class="col-md-6">
        <a href="{{ route('ujian') }}" class="btn btn-lg btn-danger btn-block">TUTUP</a>
      </div>
      <div class="col-md-6">
        <button class="btn btn-lg btn-primary btn-block" wire:click.prevent="save()" id="btn_simpan">SIMPAN</button>
      </div>
    </div>
  </div>
</div>

@push('css')
  <link href="{{ url('/') }}/assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
@endpush
@push('scripts')
  <script src="{{ url('/') }}/assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
  <script>
    $(document).ready(function() {
      $('.datepicker').on('change', function(e) {
        var id = $(this).attr('id');
        @this.set(id, e.target.value);
      });
    });

    var mem = $('.datepicker').datepicker({
      todayBtn: "linked",
      keyboardNavigation: false,
      forceParse: false,
      calendarWeeks: true,
      autoclose: true,
      format: "yyyy-mm-dd"
    });

  </script>
@endpush
