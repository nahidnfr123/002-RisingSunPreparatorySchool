<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostImage;
use App\Rules\WordCountRule;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ManagePostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $AllPost = Post::with('post_image')
            ->where('user_id', Auth::guard('admin')->id())
            ->orderBy('publish_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->latest();
        if(count($AllPost->get()) == 0){$postCount = '';} else {$postCount = count($AllPost->get());}
        $AllPost = $AllPost->paginate(10);

        $subPageName = 'My posts';

        return view('admin.post.post',compact('AllPost', 'subPageName', 'postCount'));
    }

    public function postAll(){
        $AllPost = Post::with('post_image')
            ->orderBy('publish_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->latest();
        if(count($AllPost->get()) == 0){$postCount = '';} else {$postCount = count($AllPost->get());}
        $AllPost = $AllPost->paginate(10);
        $subPageName = 'All posts';
        return view('admin.post.post',compact('AllPost', 'subPageName', 'postCount'));
    }

    public function postSearch(Request $request){
        $this->validate($request, [
            'search' => 'required|string',
        ]);
        $Search_text = $request->search;
        $AllPost = Post::with('post_image')
            ->where('title', 'like', '%'.$Search_text.'%')
            ->orWhere('body', 'like', '%'.$Search_text.'%')
            ->orderBy('publish_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->latest();
        if(count($AllPost->get()) == 0){$postCount = '';} else {$postCount = count($AllPost->get());}
        $AllPost = $AllPost->paginate(10);
        $subPageName = 'Search';
        return view('admin.post.post',compact('AllPost', 'subPageName' ,'postCount', 'Search_text'));
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
            'post_title' => ['required','string', new WordCountRule('Post title', 1, 20)],
            'post_image' => 'sometimes|max:4096',
            'post_body' => ['required','string', new WordCountRule('Post body', 10, 600)],
            'publish_date' => 'required|date|date_format:d-m-Y|after:yesterday',
        ], [
            'post_title.required' => 'Post title is required.',

            'post_image.image' => 'Post image must be a image file.',
            'post_image.file' => 'Post image must be a image file.',
            'post_image.max' => 'Post image must be less then 4096 Kb or 4 Mb.',
            'post_image.mimes' => 'Post image can only contain jpg, jpeg and png file.',

            'post_body.required' => 'Post description is required.',

            'publish_date.required' => 'Publish date is required.',
            'publish_date.date' => 'Publish date is not in correct format.',
            'publish_date.after' => 'Invalid post publish date.',
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

        $Publish_Date = date('Y-m-d', strtotime($request->publish_date));

        $title = trim($request->post_title);
        $title_slug = str_replace(array('?', '!', ':', ' ', '^', '+', '='), '_', $title);
        $body = trim($request->post_body);
        $dismissedImage = array();

        if ($request->hasFile('post_image')) {
            $dismissedImage = explode(',', $request->dismissedImage);
            $Images = $request->file('post_image');
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
            if($Count_Img > 5){return back()->withErrors('You can add a maximum of 5 photos for a post.')->withInput();}

            if($checkImage == false) {
                return back()->withErrors('Post image can only contain jpg, jpeg and png file.')->withInput();
            } else {
                try{
                    $PostInsert = new Post;
                    $PostInsert->user_id = Auth::guard('admin')->id();
                    $PostInsert->title = $title;
                    $PostInsert->title_slug = $title_slug;
                    $PostInsert->body = $body;
                    $PostInsert->publish_date = $Publish_Date;
                    $PostInsert->created_at = Carbon::now();
                    $PostInsert->save();

                    foreach ($Images as $key => $Image) {
                        if(!in_array(strval($key), $dismissedImage, true)) {
                            $random_string = md5(microtime());
                            $newFileName = 'Post-' . $PostInsert->id . '_' . $random_string . '.' . $extension; // Set the file name to store in the database ....
                            $Thumbnail = 'storage/image/post/' . $newFileName;
                            $BigImage = 'storage/image/post/big_image/' . $newFileName;

                            Image::make($Image)->fit(300, 200)->save(public_path($Thumbnail));
                            // fit :- Crop the image in the given dimension .....
                            Image::make($Image)->save(public_path($BigImage));
                            //Image::make($get_image)->resize(200, 200)->insert('public/watermark.png')->save(public_path($BigImage));

                            PostImage::Insert([
                                'post_id' => $PostInsert->id,
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
        }else{ // Post with no image ....
            $PostInsert = new Post;
            $PostInsert->user_id = Auth::guard('admin')->id();
            $PostInsert->title = $title;
            $PostInsert->title_slug = $title_slug;
            $PostInsert->body = $body;
            $PostInsert->publish_date = $Publish_Date;
            $PostInsert->created_at = Carbon::now();
            $PostInsert->save();
        }

        Session::forget('formError');
        return redirect()->route('admin.post')->with('Success', 'Post successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($title, $id)
    {
        $id = $this->decryptID($id);
        $PostView = Post::withTrashed()->where('id', $id)->where('title_slug', $title)->first();
        $subPageName = '';
        if($PostView != null){
            $string = strip_tags($PostView->title);
            if (strlen($string) > 20) {
                // truncate string
                $stringCut = substr($string, 0, 20);
                $endPoint = strrpos($stringCut, ' ');
                //if the string doesn't contain any space then it will cut without word basis.
                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                $string .= '... ';
            }
            //echo $string;
            if($PostView->deleted_at != null){
                $subPageName = 'Trash / '.$string;
            }else{
                $subPageName = $string;
            }
        }else{
            abort('404');
        }
        return view('admin.post.post_view', compact('PostView', 'subPageName'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $id = $this->decryptID($id);
        $Post = Post::with('post_image')->findOrFail($id);
        $subPageName = 'Edit post';
        return view('admin.post.post_edit', compact('Post','subPageName'));
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
        $Po = Post::findOrFail($id);
        $request->validate([
            'post_title' => ['required','string', new WordCountRule('Post title', 1, 20)],
            'post_image' => 'sometimes|max:4096',
            'post_body' => ['required','string', new WordCountRule('Post body', 10, 600)],
            'publish_date' => ['required','date', 'date_format:d-m-Y'],
        ], [
            'post_title.required' => 'Post title is required.',

            'post_image.image' => 'Post image must be a image file.',
            'post_image.file' => 'Post image must be a image file.',
            'post_image.max' => 'Post image must be less then 4096 Kb or 4 Mb.',
            'post_image.mimes' => 'Post image can only contain jpg, jpeg and png file.',

            'post_body.required' => 'Post description is required.',

            'publish_date.required' => 'Publish date is required.',
            'publish_date.date' => 'Publish date is not in correct format.',
            'publish_date.after' => 'Invalid post publish date.',
        ]);

        //$Publish_Date = Carbon::parse(strtotime($request->publish_date))->addDays(1)->format('Y/m/d');
        $Publish_Date = date('Y-m-d', strtotime($request->publish_date));

        $title = trim($request->post_title);
        $title_slug = str_replace(array('?', '!', ':', ' ', '^', '+', '='), '_', $title);
        $body = trim($request->post_body);

        if ($request->hasFile('post_image')) {
            $dismissedImage = explode(',', $request->dismissedImage);
            $Images = $request->file('post_image');
            $allowedFileExtension = array('jpg','jpeg', 'gif', 'png');

            $Count_Img = 0;
            $checkImage =true;
            foreach ($Images as $key => $Image) {
                if(!in_array(strval($key), $dismissedImage, true)) {
                    $ImageName = $Image->getClientOriginalName();
                    $extension = $Image->getClientOriginalExtension();
                    $checkImage = in_array(strtolower($extension), $allowedFileExtension, true);
                    $Count_Img++;
                }
            }
            $CountDBImg = count(PostImage::where('post_id', $id)->get());
            if(($Count_Img+$CountDBImg) > 5){return back()->withErrors('You can add a maximum of 5 photos for a post.')->withInput();}

            if($checkImage == false) {
                return back()->withErrors('Post image can only contain jpg, jpeg and png file.')->withInput();
            } else {
                try{
                    foreach ($Images as $key => $Image) {
                        if(!in_array(strval($key), $dismissedImage, true)) {
                            $random_string = md5(microtime());
                            $newFileName = 'Post-' . $id . '_' . $random_string . '.' . $extension; // Set the file name to store in the database ....
                            $Thumbnail = 'storage/image/post/' . $newFileName;
                            $BigImage = 'storage/image/post/big_image/' . $newFileName;

                            Image::make($Image)->fit(300, 200)->save(public_path($Thumbnail));
                            // fit :- Crop the image in the given dimension .....
                            Image::make($Image)->save(public_path($BigImage));
                            //Image::make($get_image)->resize(200, 200)->insert('public/watermark.png')->save(public_path($BigImage));

                            PostImage::Insert([
                                'post_id' => $id,
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
        $PostUpdate = Post::findOrFail($id);
        $PostUpdate->title = $title;
        $PostUpdate->title_slug = $title_slug;
        $PostUpdate->body = $body;
        $PostUpdate->publish_date = $Publish_Date;
        $PostUpdate->save();

        // Delete Old Images if any ...selected ..
        if($request->has('deletePreviousImage') && $request->deletePreviousImage != null){
            $deleteDBImages = explode(',', $request->deletePreviousImage);
            if(count($deleteDBImages) > 0){
                foreach ($deleteDBImages as $ImageToDelete){
                    $PostData = PostImage::findOrFail($ImageToDelete);
                    if(File::exists(public_path($PostData->thumbnail)) || File::exists(public_path($PostData->image))) {
                        File::delete([
                            public_path($PostData->thumbnail),
                            public_path($PostData->image),
                        ]);
                    }
                    PostImage::withoutTrashed()->findOrFail($ImageToDelete)->forceDelete();
                }
            }
        }

        return redirect(route('admin.post.read-more', ['title' => $PostUpdate->title_slug,'id' => encrypt($id)]))->with('Success', 'Post successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $id = $this->decryptID($id);// Perform decryption If not successful then redirect to 404
        $PostData = Post::findOrFail($id);
        if(count($PostData->post_image) > 0){
            foreach ($PostData->post_image as $Img){
                PostImage::withoutTrashed()->findOrFail($Img->id)->delete();
            }
        }
        Post::withoutTrashed()->findOrFail($id)->delete();
        return back()->with('Success', 'Post successfully deleted.');
    }

    public function postTrash(){
        $AllPost = Post::onlyTrashed()->with('post_image_Trashed')
            ->orderBy('deleted_at', 'DESC')
            ->orderBy('publish_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->latest()->paginate(10);
        //dd($AllPost);
        return view('admin.post.post_trash',compact('AllPost'));
    }


    public function destroy($id)
    {
        $id = $this->decryptID($id);// Perform decryption If not successful then redirect to 404
        $PostData = Post::onlyTrashed()->findOrFail($id);
        if(count($PostData->post_image_Trashed) > 0){
            foreach ($PostData->post_image_Trashed as $Img){
                if(File::exists(public_path($Img->thumbnail)) || File::exists(public_path($Img->image))) {
                    File::delete([
                        public_path($Img->thumbnail),
                        public_path($Img->image),
                    ]);
                }
                PostImage::onlyTrashed()->findOrFail($Img->id)->forceDelete();
            }
        }
        Post::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('Success', 'Post "permanently" deleted.');
    }


    public function restore($id){
        $id = $this->decryptID($id);// Perform decryption If not successful then redirect to 404
        $PostData = Post::onlyTrashed()->findOrFail($id);
        if(count($PostData->post_image_Trashed) > 0){
            foreach ($PostData->post_image_Trashed as $Img){
                PostImage::onlyTrashed()->findOrFail($Img->id)->restore();
            }
        }
        Post::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('Success', 'Post successfully restored.');
    }



    public function showImages(Request $request){
        if($request->ajax()){
            $Output = '';
            $InputTag = '';
            $Arrow = '';
            $Navigation = '';
            $checked = '';
            $Post_Id = $request->post_id;
            $postImages = PostImage::where('post_id', $Post_Id)->get();
            foreach ($postImages as $key => $postImage) {
                if($key == 0){$checked = 'checked';}else{ $checked = '';}

                $Output .= '<li><img src="'.asset($postImage->image).'" alt=""></li>';
                $InputTag .= '<input type="radio" name="slides" value="'.encrypt($postImage->id).'" id="slides_'.$key.'" '.$checked.'>';
                $Arrow .= '<label for="slides_'.$key.'" onclick="return GetId('.$postImage->id.')"></label>';
                $Navigation .= '<label for="slides_'.$key.'" value="'.$postImage->id.'" onclick="return GetId('.$postImage->id.')"></label>';
            }

            $Output = '
            <div id="slider1" class="csslider">
                '.$InputTag.'
                <ul>'.$Output.'</ul>
                <div class="arrows">'.$Arrow.'</div>
                <div class="navigation">
                    <div id="ImgNavigationLinks">'.$Navigation.'</div>
                </div>
            </div>';

            $Img_id = PostImage::where('post_id', $Post_Id)->first()->id;
            //return response($Output);
            return response()->json(array('Output' => $Output, 'ID' => $Img_id), 200);
        }else{
            abort('404');
        }
    }



    public function downloadImage($id){
        //$id = $this->decryptID($id);
        $ImageToDownload = PostImage::withTrashed()->findOrFail($id);
        //Storage::download($ImageToDownload->image, $ImageToDownload->title);
        return response()->download(public_path($ImageToDownload->image), $ImageToDownload->title);
    }


    public function deleteImage($id){
        $id = $this->decryptID($id);
        /*$PostData = PostImage::findOrFail($id);
        if(File::exists(public_path($PostData->thumbnail)) || File::exists(public_path($PostData->image))) {
            File::delete([
                public_path($PostData->thumbnail),
                public_path($PostData->image),
            ]);
        }*/
        PostImage::withoutTrashed()->findOrFail($id)->delete();
        //PostImage::withoutTrashed()->findOrFail($id)->forceDelete();
        return back()->with('Success', 'You have deleted a post image.');
    }


    public function showTrashedImages(Request $request){
        if($request->ajax()){
            $Output = '';
            $InputTag = '';
            $Arrow = '';
            $Navigation = '';
            $checked = '';
            $Post_Id = $request->post_id;
            $postImages = PostImage::onlyTrashed()->where('post_id', $Post_Id)->get();
            foreach ($postImages as $key => $postImage) {
                if($key == 0){$checked = 'checked';}else{ $checked = '';}

                $Output .= '<li><img src="'.asset($postImage->image).'" alt=""></li>';
                $InputTag .= '<input type="radio" name="slides" value="'.encrypt($postImage->id).'" id="slides_'.$key.'" '.$checked.'>';
                $Arrow .= '<label for="slides_'.$key.'" onclick="return GetId('.$postImage->id.')"></label>';
                $Navigation .= '<label for="slides_'.$key.'" value="'.$postImage->id.'" onclick="return GetId('.$postImage->id.')"></label>';
            }

            $Output = '
            <div id="slider1" class="csslider">
                '.$InputTag.'
                <ul>'.$Output.'</ul>
                <div class="arrows">'.$Arrow.'</div>
                <div class="navigation">
                    <div id="ImgNavigationLinks">'.$Navigation.'</div>
                </div>
            </div>';

            $Img_id = PostImage::onlyTrashed()->where('post_id', $Post_Id)->first()->id;
            //return response($Output);
            return response()->json(array('Output' => $Output, 'ID' => $Img_id), 200);
        }else{
            abort('404');
        }
    }
}
