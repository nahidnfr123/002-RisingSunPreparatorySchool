<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventImage;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Rules\WordCountRule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $GalleryImages = Gallery::with('gallery_image')
            ->orderBy('id', 'DESC')->latest()->paginate(5);
        return view('admin.gallery.gallery', compact('GalleryImages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function validation($request){
        Session::flash('formError', 'Error.');
        $request->validate([
            'gallery_title' => ['sometimes','nullable','string','unique:galleries,gallery_title', new WordCountRule('Event title', 1, 20)],
            'gallery_image' => 'required|max:4096',
        ], [
            'gallery_title.required' => 'Gallery title is required.',
            'gallery_title.unique' => 'Gallery name cannot be same. There is already a gallery with this name.',

            'gallery_image.image' => 'Gallery image must be a image file.',
            'gallery_image.file' => 'Gallery image must be a image file.',
            'gallery_image.max' => 'Gallery image must be less then 4096 Kb or 4 Mb.',
            'gallery_image.mimes' => 'Gallery image can only contain jpg, jpeg and png file.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validation($request);
        $title = ucwords(trim($request->gallery_title));

        if($title == '' || $title == null){ $title = 'Untitled';}

        if ($request->hasFile('gallery_image')) {
            $dismissedImage = explode(',', $request->dismissedImage);
            $Images = $request->file('gallery_image');
            $allowedFileExtension = array('jpg','jpeg', 'gif', 'png');

            $Count_Img = 0; $checkImage =true;
            foreach ($Images as $key => $Image) {
                //echo strval($key);
                if(!in_array(strval($key), $dismissedImage, true)) {
                    $ImageName = $Image->getClientOriginalName();
                    $Size = $Image->getClientSize(); // the the image size ...
                    $extension = $Image->getClientOriginalExtension();
                    $checkImage = in_array(strtolower($extension), $allowedFileExtension, true);
                    $Count_Img++;
                }
            }
            if($Count_Img > 20){return back()->withErrors('You can upload a maximum of 20 images at once.')->withInput();}

            if($checkImage == false) {
                return back()->withErrors('Gallery image can only contain jpg, jpeg and png file.')->withInput();
            } else {
                try{
                    $GalleryInsert = new Gallery;
                    $GalleryInsert->user_id = Auth::guard('admin')->id();
                    $GalleryInsert->gallery_title = $title;
                    $GalleryInsert->created_at = Carbon::now();
                    $GalleryInsert->save();

                    foreach ($Images as $key => $Image) {
                        if(!in_array(strval($key), $dismissedImage, true)) {
                            $random_string = md5(microtime());
                            $newFileName = 'Gallery-' . $GalleryInsert->id . '_' . $random_string . '.' . $extension; // Set the file name to store in the database ....
                            $Thumbnail = 'storage/image/gallery/' . $newFileName;
                            $BigImage = 'storage/image/gallery/big_image/' . $newFileName;

                            Image::make($Image)->fit(300, 200)->save(public_path($Thumbnail));
                            // fit :- Crop the image in the given dimension .....
                            Image::make($Image)->save(public_path($BigImage));
                            //Image::make($get_image)->resize(200, 200)->insert('public/watermark.png')->save(public_path($BigImage));

                            GalleryImage::Insert([
                                'gallery_id' => $GalleryInsert->id,
                                'thumbnail' => $Thumbnail,
                                'image' => $BigImage,
                                'created_at' => Carbon::now(),
                            ]);
                        }
                    }
                }catch (Exception $e){
                    return back()->withErrors($e->getMessage())->withInput();
                }
            }
        }else{
            return back()->withErrors('Gallery must have image files.')->withInput();
        }

        Session::forget('formError');
        return redirect()->route('admin.gallery')->with('Success', 'Gallery successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'search' => 'required|string|max:20',
        ]);
        $Search_text = $request->search;
        $GalleryImages = Gallery::with('gallery_image')
            ->where('gallery_title', 'like', '%'.$Search_text.'%')
            ->orderBy('id', 'DESC')->latest()->paginate(5);

        /*$GalleryImages = Gallery::with('gallery_image')->join('users', 'users.id', 'galleries.user_id')
            ->where('galleries.gallery_title', 'like', '%'.$Search_text.'%')
            ->orWhere('users.first_name', 'like', '%'.$Search_text.'%')
            ->orWhere('users.last_name', 'like', '%'.$Search_text.'%')
            ->orderBy('galleries.id', 'DESC')->paginate(100);*/

        $subPageName = 'Search';
        return view('admin.gallery.gallery', compact('GalleryImages', 'subPageName' , 'Search_text'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        /*$id = $this->decryptID($id);
        $Gallery = Gallery::with('gallery_image')->findOrFail($id);
        return view('admin.gallery.gallery-edit', compact('Gallery'));*/
        if($request->ajax()){
            $Output = '';
            $Gallery_Id = $this->decryptID($request->gallery_id);
            $Gallery = Gallery::findOrFail($Gallery_Id);
            $GalleryImages = GalleryImage::where('gallery_id', $Gallery_Id)->get();
            if(count($GalleryImages) > 0) {
                foreach ($GalleryImages as $Image) {
                    $Output .= '<li id="' . $Image->id . '">
                                    <div class="ic-sing-file2 position-relative">
                                        <img id="' . $Image->id . '" src="/' . $Image->thumbnail . '" alt="Preview not available.">
                                        <i class="fa fa-times closeImg" id="' . $Image->id . '" data-id="previousImage"></i>
                                    </div>
                                </li>';
                }
                $Img_id = GalleryImage::where('gallery_id', $Gallery_Id)->first()->id;
            }else{
                $Output = 'No Image available in gallery.';
                $Img_id = '';
            }
            //return response($Output);
            return response()->json(array('Output' => $Output, 'Title' => $Gallery->gallery_title), 200);
        }else{
            abort('404');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $id = $this->decryptID($id);
        $request->validate([
            'gallery_title' => ['sometimes','nullable','string','unique:galleries,gallery_title,'.$id, new WordCountRule('Event title', 1, 20)],
            'gallery_image' => 'sometimes|max:4096',
        ], [
            'gallery_title.required' => 'Gallery title is required.',
            'gallery_title.unique' => 'Gallery name cannot be same. There is already a gallery with this name.',

            'gallery_image.image' => 'Gallery image must be a image file.',
            'gallery_image.file' => 'Gallery image must be a image file.',
            'gallery_image.max' => 'Gallery image must be less then 4096 Kb or 4 Mb.',
            'gallery_image.mimes' => 'Gallery image can only contain jpg, jpeg and png file.',
        ]);

        $title = ucwords(trim($request->gallery_title));

        if ($request->hasFile('gallery_image')) {
            $dismissedImage = explode(',', $request->dismissedImage);
            $Images = $request->file('gallery_image');
            $allowedFileExtension = array('jpg','jpeg', 'gif', 'png');

            $Count_Img = 0; $checkImage =true;
            foreach ($Images as $key => $Image) {
                if(!in_array(strval($key), $dismissedImage, true)) {
                    $ImageName = $Image->getClientOriginalName();
                    $extension = $Image->getClientOriginalExtension();
                    $checkImage = in_array(strtolower($extension), $allowedFileExtension, true);
                    $Count_Img++;
                }
            }
            if($Count_Img > 20){return back()->withErrors('You can upload a maximum of 20 images at once.')->withInput();}
            if($checkImage == false) {
                return back()->withErrors('Gallery image can only contain jpg, jpeg and png file.')->withInput();
            } else {
                try{
                    foreach ($Images as $key => $Image) {
                        if(!in_array(strval($key), $dismissedImage, true)) {
                            $random_string = md5(microtime());
                            $newFileName = 'Gallery-' . $id . '_' . $random_string . '.' . $extension; // Set the file name to store in the database ....
                            $Thumbnail = 'storage/image/gallery/' . $newFileName;
                            $BigImage = 'storage/image/gallery/big_image/' . $newFileName;

                            Image::make($Image)->fit(300, 200)->save(public_path($Thumbnail));
                            // fit :- Crop the image in the given dimension .....
                            Image::make($Image)->save(public_path($BigImage));
                            //Image::make($get_image)->resize(200, 200)->insert('public/watermark.png')->save(public_path($BigImage));

                            GalleryImage::Insert([
                                'gallery_id' => $id,
                                'thumbnail' => $Thumbnail,
                                'image' => $BigImage,
                                'created_at' => Carbon::now(),
                            ]);
                        }
                    }
                }catch (Exception $e){
                    return back()->withErrors($e->getMessage())->withInput();
                }
            }
        }
        $GalleryUpdate = Gallery::findOrFail($id);
        $GalleryUpdate->gallery_title = $title;
        $GalleryUpdate->save();

        // Delete Old Images if any ...selected ..
        if($request->has('deletePreviousImage') && $request->deletePreviousImage != null){
            $deleteDBImages = explode(',', $request->deletePreviousImage);
            if(count($deleteDBImages) > 0){
                foreach ($deleteDBImages as $ImageToDelete){
                    $GalleryImageData = GalleryImage::findOrFail($ImageToDelete);
                    if(File::exists(public_path($GalleryImageData->thumbnail)) || File::exists(public_path($GalleryImageData->image))) {
                        File::delete([
                            public_path($GalleryImageData->thumbnail),
                            public_path($GalleryImageData->image),
                        ]);
                    }
                    GalleryImage::withoutTrashed()->findOrFail($ImageToDelete)->forceDelete();
                }
            }
        }
        return redirect(route('admin.gallery'))->with('Success', 'Gallery successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $id = $this->decryptID($id);// Perform decryption If not successful then redirect to 404
        $GalleryData = Gallery::withoutTrashed()->findOrFail($id);
        if(count($GalleryData->gallery_image) > 0){
            foreach ($GalleryData->gallery_image as $Img){
                if(File::exists(public_path($Img->thumbnail)) || File::exists(public_path($Img->image))) {
                    File::delete([
                        public_path($Img->thumbnail),
                        public_path($Img->image),
                    ]);
                }
                GalleryImage::withoutTrashed()->findOrFail($Img->id)->forceDelete();
            }
        }
        Gallery::withoutTrashed()->findOrFail($id)->forceDelete();
        //return back()->with('Success', 'Gallery deleted successfully.');
        return back()->with('Success', 'Gallery deleted successfully.'); // Works form many pages .....
    }

    public function addImageToGallery(Request $request, $id){
        $id = $this->decryptID($id);
        $this->validation($request);

        if ($request->hasFile('gallery_image')) {
            $dismissedImage = explode(',', $request->dismissedImage);
            $Images = $request->file('gallery_image');
            $allowedFileExtension = array('jpg','jpeg', 'gif', 'png');

            $Count_Img = 0; $checkImage =true;
            foreach ($Images as $key => $Image) {
                echo strval($key);
                if(!in_array(strval($key), $dismissedImage, true)) {
                    $ImageName = $Image->getClientOriginalName();
                    $extension = $Image->getClientOriginalExtension();
                    $checkImage = in_array(strtolower($extension), $allowedFileExtension, true);
                    $Count_Img++;
                }
            }
            if($Count_Img > 20){return back()->withErrors('You can upload a maximum of 20 images at once.')->withInput();}

            if($checkImage == false) {
                return back()->withErrors('Gallery image can only contain jpg, jpeg and png file.')->withInput();
            } else {
                try{
                    foreach ($Images as $key => $Image) {
                        if(!in_array(strval($key), $dismissedImage, true)) {
                            $random_string = md5(microtime());
                            $newFileName = 'Gallery-' . $id . '_' . $random_string . '.' . $extension; // Set the file name to store in the database ....
                            $Thumbnail = 'storage/image/gallery/' . $newFileName;
                            $BigImage = 'storage/image/gallery/big_image/' . $newFileName;

                            Image::make($Image)->fit(300, 200)->save(public_path($Thumbnail));
                            // fit :- Crop the image in the given dimension .....
                            Image::make($Image)->save(public_path($BigImage));
                            //Image::make($get_image)->resize(200, 200)->insert('public/watermark.png')->save(public_path($BigImage));

                            GalleryImage::Insert([
                                'gallery_id' => $id,
                                'thumbnail' => $Thumbnail,
                                'image' => $BigImage,
                                'created_at' => Carbon::now(),
                            ]);
                        }
                    }
                }catch (Exception $e){
                    return back()->withErrors($e->getMessage())->withInput();
                }
            }
        }else{
            return back()->withErrors('Gallery must have only image files.')->withInput();
        }

        Session::forget('formError');
        return back()->with('Success', 'Image added to gallery.');
    }
}
