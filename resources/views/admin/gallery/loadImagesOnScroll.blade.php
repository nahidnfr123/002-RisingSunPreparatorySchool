@if(count($GalleryImages) > 0)
    @foreach($GalleryImages as $Gallery)
        <div class="col-12 m-t-20">
            <h4><span>{{ $Gallery->gallery_title }} </span> <span style="font-size: 12px"> ( {{ \Carbon\Carbon::parse($Gallery->created_at)->format('Y-m-d h:i a') }} )</span></h4>
            <div class="btn-group-xs">
                <button type="button" class="btn btn-xs btn-info rounded AddMoreImageToGallery" title="Add Image"
                        data-toggle="modal" data-target="#EditGallery"
                        data-title="{{ $Gallery->gallery_title }}" data-id="{{ encrypt($Gallery->id)  }}">
                    <i class="fa fa-plus p-r-4"></i>  Edit Gallery
                </button>
                {{--<a href="{{ route('admin.gallery.image.edit', ['id' => encrypt($Gallery->id)]) }}" class="btn btn-xs bg-gradient-pink rounded" title="Edit Gallery">
                    <i class="fa fa-edit p-r-4"></i> Edit Gallery
                </a>--}}
                <a href="{{ route('admin.gallery.destroy', ['id' => encrypt($Gallery->id)]) }}" class="btn btn-xs btn-danger rounded" title="Delete Gallery"
                   onclick="return confirm('Are you sure you want to delete this gallery?')">
                    <i class="fa fa-trash p-r-4"></i> Delete Gallery
                </a>
            </div>
            {{--<div class="clearfix"></div>--}}
            <hr>
        </div>
        @if(count($Gallery->gallery_image) > 0)
            @foreach($Gallery->gallery_image as $Img)
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 item" data-aos="fade" data-src="/{{ $Img->image }}"
                     data-sub-html="<h4>{{ $Gallery->gallery_title }}</h4>">
                    <a href="#"><img src="/{{ $Img->thumbnail }}" alt="IMage" class="img-fluid"></a>
                </div>
            @endforeach
            {{--<div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 text-center" data-aos="fade" style="display: flex; align-content: center; vertical-align: center; ">
                <a><i class="fa fa-plus" style="font-size: 30px;"></i></a>
            </div>--}}
        @else
            <div class="col-12 py-1">
                <div class="text-center">
                    <h5 class="text-muted text-monospace"> <i class="fa fa-frown"></i> No 'Image' in this gallery. </h5>
                </div>
            </div>
        @endif
    @endforeach
@endif

