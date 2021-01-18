<div class="row">
	<div class="col col-12">
		<div class="ibox">
			<div class="ibox-title">
				<h5>Tambah Materi Pelajaran</h5>
			</div>
			<div class="ibox-content">
				<div class="form-group row">
					<input type="hidden" wire:model="materi_id">
					<label class="col-md-2">Kelas</label>
					<div class="col-md-10 @error('kelas') has-error @enderror">
						@foreach ($arr_kelas as $kls)
						<label class="mr-2"><input type="checkbox" class="i-checks" wire:model="kelas_id" value="{{ $kls->id }}">
							{{ $kls->kelas }}</label>
						@endforeach
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-2 col-form-label">Judul Materi</label>
					<div class="col-md-10 input-group @error('judul') has-error @enderror">
						<input type="text" class="form-control" wire:model="judul">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-md-2 col-form-label">Photo</label>
					<div class="col-md-10">
						{!! Form::file('photo', ['class' => '', 'wire:model' => 'photo']) !!}
					</div>
				</div>
				<div class="form-group" wire:ignore>
					<textarea class="isi" id="isi" rows="10">{{ $isi }}</textarea>
				</div>
			</div>
			<div class="ibox-footer pb-5">
				<span class="float-right">
					<a href="{{ route('materi') }}" class="btn btn-white btn-sm">Tutup</a>
					<button class="btn btn-primary btn-sm" wire:click.prevent="save()" id="btn_simpan">Simpan</button>
				</span>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script src="{{ url('/') }}/assets/tinymce/tinymce.min.js"></script>
<script>
	var editor_config = {
		path_absolute : "/",
      mode : "textareas",
		indentation : '15pt',
      //menubar : false,
      forced_root_block : false,
      force_br_newlines : true,
      force_p_newlines : false,
      height: 500,
		plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime media nonbreaking save table directionality",
			"emoticons template paste textpattern"
		],
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
		file_picker_callback : function(callback, value, meta) {
			var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
			var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;
			var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
			if (meta.filetype == 'image') {
				cmsURL = cmsURL + "&type=Images";
			} else {
				cmsURL = cmsURL + "&type=Files";
			}
			tinyMCE.activeEditor.windowManager.openUrl({
				url : cmsURL,
				title : 'Filemanager',
				width : x * 0.8,
				height : y * 0.8,
				resizable : "yes",
				close_previous : "no",
				onMessage: (api, message) => {
					callback(message.content);
				}
			});
		}
	};
	tinymce.init(editor_config);
	$('#btn_simpan').click(function (e) { 
		e.preventDefault();
		@this.set('isi', tinymce.get('isi').getContent());
	});
</script>
@endpush