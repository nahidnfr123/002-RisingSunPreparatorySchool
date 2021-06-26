<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Images;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\settings\settings_home;
use App\Rules\WordCountRule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Symfony\Component\Console\Output\Output;
use Yajra\DataTables\Facades\DataTables;

class ManagePagesController extends Controller
{

    public function index(Request $request){
        if ($request->ajax()) {
            $data = Images::orderBy('home', 'DESC')->orderBy('created_at', 'DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('#', function($row){
                    $H = '';
                    if($row->home == 1) {
                        $H = ' checked ';
                    }
                    $P = '';
                    if($row->post == 1) {
                        $P = ' checked ';
                    }
                    $E = '';
                    if($row->event == 1) {
                        $E = ' checked ';
                    }
                    $G = '';
                    if($row->gallery == 1) {
                        $G = ' checked ';
                    }
                    $C = '';
                    if($row->contact == 1) {
                        $C = ' checked ';
                    }
                    $A = '';
                    if($row->about == 1) {
                        $A = ' checked ';
                    }

                    return '
                    <div class="CheckBoxes">
                     <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="check_home'.$row->id.'" value="home" '. $H .'
                        onclick="updateImg('. $row->id .', this.value)">
                        <label for="check_home'.$row->id.'" class="custom-control-label" style="height: 100%; width: 100%; cursor: pointer; margin: 0; padding: 0;">Home</label>
                     </div>

                     <div class="custom-control custom-checkbox">
                     <input class="custom-control-input" type="checkbox" id="check_post'.$row->id.'" value="post" '. $P .'
                        onclick="updateImg('. $row->id .', this.value)">
                        <label for="check_post'.$row->id.'" class="custom-control-label" style="height: 100%; width: 100%; cursor: pointer; margin: 0; padding: 0;">Post</label>
                     </div>

                     <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="check_event'.$row->id.'" value="event" '. $E .'
                        onclick="updateImg('. $row->id .', this.value)">
                        <label for="check_event'.$row->id.'" class="custom-control-label" style="height: 100%; width: 100%; cursor: pointer; margin: 0; padding: 0;">Event</label>
                     </div>

                     <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="check_gallery'.$row->id.'" value="gallery" '. $G .'
                        onclick="updateImg('. $row->id .', this.value)">
                        <label for="check_gallery'.$row->id.'" class="custom-control-label" style="height: 100%; width: 100%; cursor: pointer; margin: 0; padding: 0;">Gallery</label>
                     </div>

                     <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="check_contact'.$row->id.'" value="contact" '. $C .'
                        onclick="updateImg('. $row->id .', this.value)">
                        <label for="check_contact'.$row->id.'" class="custom-control-label" style="height: 100%; width: 100%; cursor: pointer; margin: 0; padding: 0;">Contact</label>
                     </div>

                     <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="check_about'.$row->id.'" value="about" '. $A .'
                        onclick="updateImg('. $row->id .', this.value)">
                        <label for="check_about'.$row->id.'" class="custom-control-label" style="height: 100%; width: 100%; cursor: pointer; margin: 0; padding: 0;">About</label>
                     </div>
                    </div>
                     ';
                })
                ->addColumn('image', function($row){
                    return '<img src="/'.$row->thumbnail.'" alt="" width="100%" height="60" style="object-fit: cover; object-position: center;">';
                })
                ->addColumn('action', function($row){
                    $btn = '
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-default btn-sm" onclick="deleteImg('. $row->id .')"><i class="fa fa-trash-alt"></i></button>
                        <button class="btn btn-default btn-sm" onclick="editImg('. $row->id .')"><i class="fa fa-edit"></i></button>
                    </div>
                    ';
                    /*$btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';*/
                    return $btn;
                })
                ->rawColumns(['#', 'image', 'action'])
                ->make(true);
        }
    }

    public function home_settings()
    {
        $home_datas = Images::orderBy('id', 'DESC')->get();
        return view('admin.settings.home', compact('home_datas'));
    }


    public function pages_image_add(Request $request){
        $this->validate($request, [
            'title' => ['sometimes'],
            'image' => 'required|image|max:4096',
            'details' => ['sometimes'],
        ], [
            'image.image' => 'Image must be a image file.',
            'image.file' => 'Image must be a image file.',
            'image.max' => 'Image must be less then 4096 Kb or 4 Mb.',
            'image.mimes' => 'Image can only contain jpg, jpeg and png file.',
        ]);

        /*$validator = Validator::make($request->all(), [
            'image' => 'required|image|max:4096',
            'title' => ['sometimes'],
            'details' => ['sometimes'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }*/

        if ($request->hasFile('image')) {
            $Image = $request->file('image');
            $allowedFileExtension = array('jpg', 'jpeg', 'png');

            $ImageName = $Image->getClientOriginalName();
            $extension = $Image->getClientOriginalExtension();
            $checkImage = in_array(strtolower($extension), $allowedFileExtension, true);

            if ($checkImage == false) {
                return back()->with('Error', 'Image can only contain jpg, jpeg and png file.')->withInput();
            } else {
                try {
                    $HomeContent = new Images();

                    $random_string = md5(microtime());
                    $newFileName = 'Slide' . '_' . $random_string . '.' . $extension; // Set the file name to store in the database ....
                    $Thumbnail = 'storage/image/web_layout/bg/' . $newFileName;
                    $BigImage = 'storage/image/web_layout/bg/big_image/' . $newFileName;

                    Image::make($Image)->fit(300, 200)->save(public_path($Thumbnail));
                    // fit :- Crop the image in the given dimension .....
                    Image::make($Image)->save(public_path($BigImage));
                    //Image::make($get_image)->resize(200, 200)->insert('public/watermark.png')->save(public_path($BigImage));

                    $HomeContent->title = $request->title;
                    $HomeContent->user_id = auth()->id();
                    $HomeContent->details = $request->details;
                    $HomeContent->image = $BigImage;
                    $HomeContent->thumbnail = $Thumbnail;
                    $HomeContent->home = false;
                    $HomeContent->save();

                    return back()->with('Success','Image successfully added.');
                } catch (Exception $e) {
                    return back()->with('Error', $e->getMessage())->withInput();
                }
            }
        } else {
            return back()->with('Error', 'You must select an image.')->withInput();
        }
    }

    public function pages_image_update(Request $request){
        $this->validate($request, [
            'title' => ['sometimes'],
            'image' => 'sometimes|image|max:4096',
            'details' => ['sometimes'],
        ], [
            'image.image' => 'Image must be a image file.',
            'image.file' => 'Image must be a image file.',
            'image.max' => 'Image must be less then 4096 Kb or 4 Mb.',
            'image.mimes' => 'Image can only contain jpg, jpeg and png file.',
        ]);

        $HomeContent = Images::findOrFail($request->id);

        if ($request->hasFile('image')) {
            $Image = $request->file('image');
            $allowedFileExtension = array('jpg', 'jpeg', 'png');

            $ImageName = $Image->getClientOriginalName();
            $extension = $Image->getClientOriginalExtension();
            $checkImage = in_array(strtolower($extension), $allowedFileExtension, true);

            if ($checkImage == false) {
                return back()->with('Error', 'Slider Image can only contain jpg, jpeg and png file.')->withInput();
            } else {
                try {
                    if(File::exists(public_path($HomeContent->thumbnail)) || File::exists(public_path($HomeContent->image))) {
                        File::delete([
                            public_path($HomeContent->thumbnail),
                            public_path($HomeContent->image),
                        ]);
                    }

                    $random_string = md5(microtime());
                    $newFileName = 'Slide' . '_' . $random_string . '.' . $extension; // Set the file name to store in the database ....
                    $Thumbnail = 'storage/image/web_layout/bg/' . $newFileName;
                    $BigImage = 'storage/image/web_layout/bg/big_image/' . $newFileName;

                    Image::make($Image)->fit(300, 200)->save(public_path($Thumbnail));
                    // fit :- Crop the image in the given dimension .....
                    Image::make($Image)->save(public_path($BigImage));
                    //Image::make($get_image)->resize(200, 200)->insert('public/watermark.png')->save(public_path($BigImage));

                    $HomeContent->title = $request->title;
                    $HomeContent->user_id = auth()->id();
                    $HomeContent->details = $request->details;
                    $HomeContent->image = $BigImage;
                    $HomeContent->thumbnail = $Thumbnail;
                    $HomeContent->save();

                    return back()->with('Success','Image successfully updated.');
                } catch (Exception $e) {
                    return back()->with('Error', $e->getMessage())->withInput();
                }
            }
        } else {
            $HomeContent->title = $request->title;
            $HomeContent->user_id = auth()->id();
            $HomeContent->details = $request->details;
            $HomeContent->save();

            return back()->with('Success','Image slider successfully added.');
        }

    }

    public function home_settings_status_update(Request $request){
        if($request->ajax()){
            $id = $request->id;

            $PageImage = Images::findOrFail($id);

            $page_name = $request->page_name;

            $GetImages = Images::where($page_name, 1)->get();

            if ($page_name != 'home'){
                if($PageImage->$page_name == 0) {
                    if (count($GetImages) > 0) {
                        foreach ($GetImages as $getImage) {
                            $getImage->$page_name = 0;
                            $getImage->save();
                        }
                    }
                    $msg = 'Image Successfully set to active';
                    $Alert = ' alert-success';
                    $PageImage->$page_name = 1;
                    $PageImage->save();
                }elseif($PageImage->$page_name == 1){
                    if(count($GetImages) == 1){
                        $msg = 'Page must contain at-least 1 image.';
                        $Alert = 'alert-warning';
                    }else{
                        $PageImage->$page_name = 1;
                        $PageImage->save();
                        $msg = 'Slider image set to inactive.';
                        $Alert = ' alert-success';
                    }
                }
            }else{
                if($PageImage->$page_name == 0){
                    if(count($GetImages) == 3){
                        $msg = 'Maximum of 3 images can be in home slider.';
                        $Alert = 'alert-warning';
                    }else{
                        $PageImage->$page_name = 1;
                        $PageImage->save();
                        $msg = 'Slider image set to active.';
                        $Alert = ' alert-success';
                    }
                }
                elseif($PageImage->$page_name == 1){
                    if(count($GetImages) == 1){
                        $msg = 'Slider must contain at-least 1 image.';
                        $Alert = 'alert-warning';
                    }else{
                        $PageImage->$page_name = 0;
                        $PageImage->save();
                        $msg = 'Slider image set to inactive.';
                        $Alert = ' alert-success';
                    }
                }else{
                    $msg = 'There is some error.';
                    $Alert = 'alert-danger';
                    return response()->json(array('Output' => $msg, 'AlertStatus' => $Alert), 200);
                }
            }
            return response()->json(array('Output' => $msg, 'AlertStatus' => $Alert), 200);
        }
    }

    public function home_settings_status_edit(Request $request){
        $product = Images::findOrFail($request->id);
        return response()->json($product);
    }

    public function home_settings_status_delete(Request $request){
        if($request->ajax()) {

            $id = $request->id;

            $HomeData = Images::findOrFail($id);

            $GetImages = Images::where('home',1)->get();

            if(count($GetImages) == 1 || $HomeData->post == 1 || $HomeData->event == 1 || $HomeData->gallery == 1 || $HomeData->contact == 1 || $HomeData->about == 1){
                $msg = 'Active images cannot be deleted.';
                $Alert = 'alert-warning';
            }else{
                if(File::exists(public_path($HomeData->thumbnail)) || File::exists(public_path($HomeData->image))) {
                    File::delete([
                        public_path($HomeData->thumbnail),
                        public_path($HomeData->image),
                    ]);
                }
                $HomeData->forceDelete();
                $msg = 'Image deleted';
                $Alert = 'alert-info';
            }
            return response()->json(array('Output' => $msg, 'AlertStatus' => $Alert), 200);
        }
    }

    public function about(){
        $About = About::first();
        return view('admin.about.about', compact('About'));
    }

    public function about_add(Request $request){
        $this->validate($request, [
            'about' => ['required', 'string', new WordCountRule('About', 20, 800)],
        ], [

        ]);
        try {
            $About = new About();
            $About->about = $request->about;
            $About->save();

            return back()->with('success', 'About content added successfully.');
        } catch (Exception $e) {
            return back()->with('Error', $e->getMessage())->withInput();
        }
    }

    public function about_update(Request $request){
        $this->validate($request, [
            'about' => ['required', 'string', new WordCountRule('About', 20, 800)],
        ], [

        ]);
        try {
            $About = About::findOrFail($request->id);
            $About->about = $request->about;
            $About->save();

            return back()->with('success', 'About content updated successfully.');
        } catch (Exception $e) {
            return back()->with('Error', $e->getMessage())->withInput();
        }
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
