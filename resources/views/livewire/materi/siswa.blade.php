@push('css')
<style>
   .bgmateri {
      background-repeat: no-repeat;
      background-size: 95% 95%;
      background-position: center;
   }
</style>
@endpush
<div class="row animated fadeInRight">
   @foreach ($materi as $row)
   <div class="col-md-3">
      <div class="ibox">
         <div class="ibox-content product-box">
            <div class="product-imitation bgmateri text-center" style="background-image: url('{{ $row->photo_url }}')"></div>
            <div class="product-desc">
               {{-- <span class="product-price">$10</span> --}}
               <a href="#" class="product-name"> {{ $row->judul }}</a>
               <div class="m-t text-righ">
                  <a href="{{ route('materi.baca', ['id'=> $row->id ]) }}" class="btn btn-xs btn-outline btn-primary"
                     target="_blank">Baca Materi <i class="fa fa-long-arrow-right"></i> </a>
               </div>
            </div>
         </div>
      </div>
   </div>
   @endforeach
</div>