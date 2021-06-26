<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventImage;
use App\Rules\WordCountRule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class ManageEventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /*$AllEvents = Event::with('event_image')
            ->where('user_id', Auth::guard('admin')->id())
            ->orderBy('event_start_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->latest()->paginate(10);

        $subPageName = '';
        return view('admin.event.event',compact('AllEvents', 'subPageName'));*/

        $AllEvents = Event::with('event_image')
            ->orderBy('event_start_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->latest();
        if(count($AllEvents->get()) == 0){$eventCount = '';} else {$eventCount = count($AllEvents->get());}
        //$eventCount = count($AllEvents->get());
        $AllEvents = $AllEvents->paginate(10);
        $subPageName = 'All Events';
        return view('admin.event.event', compact('AllEvents', 'subPageName', 'eventCount'));
    }

    public function eventAll(){
        $AllEvents = Event::with('event_image')
            ->orderBy('event_start_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->latest();
        if(count($AllEvents->get()) == 0){$eventCount = '';} else {$eventCount = count($AllEvents->get());}
        $AllEvents = $AllEvents->paginate(10);
        $subPageName = 'Events';
        return view('admin.event.event',compact('AllEvents', 'subPageName', 'eventCount'));
    }

    public function eventSearch(Request $request){
        $this->validate($request, [
            'search' => 'required|string',
        ]);
        $Search_text = $request->search;

        $AllEvents = Event::with('event_image')
            ->where('title', 'like', '%'.$Search_text.'%')
            ->orWhere('description', 'like', '%'.$Search_text.'%')
            ->orderBy('event_start_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->latest();
        if(count($AllEvents->get()) == 0){$eventCount = '';} else {$eventCount = count($AllEvents->get());}
        $AllEvents = $AllEvents->paginate(10);

        $subPageName = 'Search';
        return view('admin.event.event',compact('AllEvents', 'subPageName' ,'eventCount', 'Search_text'));
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
            'event_title' => ['required','string', new WordCountRule('Event title', 1, 20)],
            'event_image' => 'sometimes|max:4096',
            'event_description' => ['required','string', new WordCountRule('Event description', 10, 600)],
            'Start_Date' => ['required', 'date', 'date_format:Y-m-d', 'after: Today'],
            'End_Date' => ['required', 'date', 'date_format:Y-m-d', ],
        ], [
            'event_title.required' => 'Event title is required.',

            'event_image.image' => 'Event image must be a image file.',
            'event_image.file' => 'Event image must be a image file.',
            'event_image.max' => 'Event image must be less then 4096 Kb or 4 Mb.',
            'event_image.mimes' => 'Event image can only contain jpg, jpeg and png file.',

            'event_description.required' => 'Event description is required.',

            'Start_Date.required' => 'Start date is required.',
            'Start_Date.date' => 'Start date must be a date.',
            'Start_Date.date_format' => 'Start date is in incorrect format. Correct date format is d-m-Y.',
            'Start_Date.after' => 'Start date should be atleast after today.',
            'Start_Date.before' => 'Start date should be before end date.',

            'End_Date.required' => 'End date is required.',
            'End_Date.date' => 'End date must be a date.',
            'End_Date.date_format' => 'End date is in incorrect format. Correct date format is d-m-Y.',
            'End_Date.after' => 'End date should be a date after end date.',
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
        /*$StartDate = Carbon::parse($request['Start_Date'])->format('Y-m-d');
        $EndDate = Carbon::parse($request['End_Date'])->format('Y-m-d');
        $request['Start_Date'] = $StartDate;
        $request['End_Date'] = $EndDate;*/

        $this->validation($request);

        $StartDate = date('Y-m-d', strtotime($request->Start_Date));
        $EndDate = date('Y-m-d', strtotime($request->End_Date));

        $title = ucwords(trim($request->event_title));
        $title_slug = str_replace(array('?', '!', ':', ' ', '^', '+', '='), '_', $title);
        $body = ucfirst(trim($request->event_description));
        $dismissedImage = array();

        if ($request->hasFile('event_image')) {
            $dismissedImage = explode(',', $request->dismissedImage);
            $Images = $request->file('event_image');
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
            if($Count_Img > 5){return back()->withErrors('You can add a maximum of 5 photos for a event.')->withInput();}

            if($checkImage == false) {
                return back()->withErrors('Event image can only contain jpg, jpeg and png file.')->withInput();
            } else {
                try{
                    $EventInsert = new Event;
                    $EventInsert->user_id = Auth::guard('admin')->id();
                    $EventInsert->title = $title;
                    $EventInsert->title_slug = $title_slug;
                    $EventInsert->description = $body;
                    $EventInsert->event_start_date = $StartDate;
                    $EventInsert->event_end_date = $EndDate;
                    $EventInsert->created_at = Carbon::now();
                    $EventInsert->save();

                    foreach ($Images as $key => $Image) {
                        if(!in_array(strval($key), $dismissedImage, true)) {
                            $random_string = md5(microtime());
                            $newFileName = 'Event-' . $EventInsert->id . '_' . $random_string . '.' . $extension; // Set the file name to store in the database ....
                            $Thumbnail = 'storage/image/event/' . $newFileName;
                            $BigImage = 'storage/image/event/big_image/' . $newFileName;

                            Image::make($Image)->fit(300, 200)->save(public_path($Thumbnail));
                            // fit :- Crop the image in the given dimension .....
                            Image::make($Image)->save(public_path($BigImage));
                            //Image::make($get_image)->resize(200, 200)->insert('public/watermark.png')->save(public_path($BigImage));

                            EventImage::Insert([
                                'event_id' => $EventInsert->id,
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
        }else{ // Event with no image ....
            $EventInsert = new Event;
            $EventInsert->user_id = Auth::guard('admin')->id();
            $EventInsert->title = $title;
            $EventInsert->title_slug = $title_slug;
            $EventInsert->description = $body;
            $EventInsert->event_start_date = $StartDate;
            $EventInsert->event_end_date = $EndDate;
            $EventInsert->created_at = Carbon::now();
            $EventInsert->save();
        }

        Session::forget('formError');
        return redirect()->route('admin.events')->with('Success', 'Event successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($title, $id)
    {
        $id = $this->decryptID($id);
        $EventView = Event::withTrashed()->where('id', $id)->where('title_slug', $title)->first();
        $subPageName = '';
        if($EventView != null){
            $string = strip_tags($EventView->title);
            if (strlen($string) > 20) {
                // truncate string
                $stringCut = substr($string, 0, 20);
                $endPoint = strrpos($stringCut, ' ');
                //if the string doesn't contain any space then it will cut without word basis.
                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                $string .= '... ';
            }
            if($EventView->deleted_at != null){
                $subPageName = 'Trash / '.$string;
            }else{
                $subPageName = $string;
            }
        }else{
            abort('404');
        }
        return view('admin.event.event_view', compact('EventView', 'subPageName'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = $this->decryptID($id);
        $Event = Event::with('event_image')->findOrFail($id);
        $subPageName = 'Edit event';
        return view('admin.event.event_edit', compact('Event','subPageName'));
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
        $id = $this->decryptID($id);
        $StartDate = Carbon::parse($request['Start_Date'])->format('Y-m-d');
        $EndDate = Carbon::parse($request['End_Date'])->format('Y-m-d');
        $request['Start_Date'] = $StartDate;
        $request['End_Date'] = $EndDate;

        $request->validate([
            'event_title' => ['required','string', new WordCountRule('Event title', 1, 20)],
            'event_image' => 'sometimes|max:4096',
            'event_description' => ['required','string', new WordCountRule('Event description', 10, 600)],
            'Start_Date' => ['required', 'date', 'date_format:Y-m-d', 'after: Today', ],
            'End_Date' => ['required', 'date', 'date_format:Y-m-d', 'after: Today', ],
        ], [
            'event_title.required' => 'Event title is required.',

            'event_image.image' => 'Event image must be a image file.',
            'event_image.file' => 'Event image must be a image file.',
            'event_image.max' => 'Event image must be less then 4096 Kb or 4 Mb.',
            'event_image.mimes' => 'Event image can only contain jpg, jpeg and png file.',

            'event_description.required' => 'Event description is required.',

            'Start_Date.required' => 'Start date is required.',
            'Start_Date.date' => 'Start date must be a date.',
            'Start_Date.date_format' => 'Start date is in incorrect format. Correct date format is d-m-Y.',
            'Start_Date.after' => 'Start date should be atleast after today.',
            'Start_Date.before' => 'Start date should be before end date.',

            'End_Date.required' => 'End date is required.',
            'End_Date.date' => 'End date must be a date.',
            'End_Date.date_format' => 'End date is in incorrect format. Correct date format is d-m-Y.',
            'End_Date.after' => 'End date should be a date after end date.',
        ]);


        $title = ucwords(trim($request->event_title));
        $title_slug = str_replace(array('?', '!', ':', ' ', '^', '+', '='), '_', $title);
        $body = ucfirst(trim($request->event_description));

        if ($request->hasFile('event_image')) {
            $dismissedImage = explode(',', $request->dismissedImage);
            $Images = $request->file('event_image');
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
            if($Count_Img > 5){return back()->withErrors('You can add a maximum of 5 photos for a event.')->withInput();}

            if($checkImage == false) {
                return back()->withErrors('Event image can only contain jpg, jpeg and png file.')->withInput();
            } else {
                try{
                    foreach ($Images as $key => $Image) {
                        if(!in_array(strval($key), $dismissedImage, true)) {
                            $random_string = md5(microtime());
                            $newFileName = 'Event-' . $id . '_' . $random_string . '.' . $extension; // Set the file name to store in the database ....
                            $Thumbnail = 'storage/image/event/' . $newFileName;
                            $BigImage = 'storage/image/event/big_image/' . $newFileName;

                            Image::make($Image)->fit(300, 200)->save(public_path($Thumbnail));
                            // fit :- Crop the image in the given dimension .....
                            Image::make($Image)->save(public_path($BigImage));
                            //Image::make($get_image)->resize(200, 200)->insert('public/watermark.png')->save(public_path($BigImage));

                            EventImage::Insert([
                                'event_id' => $id,
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
        $EventUpdate = Event::findOrFail($id);
        $EventUpdate->title = $title;
        $EventUpdate->title_slug = $title_slug;
        $EventUpdate->description = $body;
        $EventUpdate->event_start_date = $request->Start_Date;
        $EventUpdate->event_end_date = $request->End_Date;
        $EventUpdate->save();

        // Delete Old Images if any ...selected ..
        if($request->has('deletePreviousImage') && $request->deletePreviousImage != null){
            $deleteDBImages = explode(',', $request->deletePreviousImage);
            if(count($deleteDBImages) > 0){
                foreach ($deleteDBImages as $ImageToDelete){
                    $EventData = EventImage::findOrFail($ImageToDelete);
                    if(File::exists(public_path($EventData->thumbnail)) || File::exists(public_path($EventData->image))) {
                        File::delete([
                            public_path($EventData->thumbnail),
                            public_path($EventData->image),
                        ]);
                    }
                    EventImage::withoutTrashed()->findOrFail($ImageToDelete)->forceDelete();
                }
            }
        }
        return redirect(route('admin.events.read-more', ['title' => $EventUpdate->title_slug,'id' => encrypt($id)]))->with('Success', 'Event successfully updated.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $id = $this->decryptID($id);// Perform decryption If not successful then redirect to 404
        $EventData = Event::findOrFail($id);
        if(count($EventData->event_image) > 0){
            foreach ($EventData->event_image as $Img){
                EventImage::withoutTrashed()->findOrFail($Img->id)->delete();
            }
        }
        Event::withoutTrashed()->findOrFail($id)->delete();
        return back()->with('Success', 'Event successfully deleted.');
    }


    public function eventTrash(){
        $AllEvents = Event::onlyTrashed()->with('event_image_Trashed')
            ->orderBy('deleted_at', 'DESC')
            ->orderBy('event_start_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->latest()->paginate(10);
        return view('admin.event.event_trash',compact('AllEvents'));
    }


    public function destroy($id)
    {
        $id = $this->decryptID($id);// Perform decryption If not successful then redirect to 404
        $EventData = Event::onlyTrashed()->findOrFail($id);
        if(count($EventData->event_image_Trashed) > 0){
            foreach ($EventData->event_image_Trashed as $Img){
                if(File::exists(public_path($Img->thumbnail)) || File::exists(public_path($Img->image))) {
                    File::delete([
                        public_path($Img->thumbnail),
                        public_path($Img->image),
                    ]);
                }
                EventImage::onlyTrashed()->findOrFail($Img->id)->forceDelete();
            }
        }
        Event::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('Success', 'Event "permanently" deleted.');
    }


    public function restore($id){
        $id = $this->decryptID($id);// Perform decryption If not successful then redirect to 404
        $EventData = Event::onlyTrashed()->findOrFail($id);
        if(count($EventData->event_image_Trashed) > 0){
            foreach ($EventData->event_image_Trashed as $Img){
                EventImage::onlyTrashed()->findOrFail($Img->id)->restore();
            }
        }
        Event::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('Success', 'Event successfully restored.');
    }



    public function showImages(Request $request){
        if($request->ajax()){
            $Output = '';
            $InputTag = '';
            $Arrow = '';
            $Navigation = '';
            $checked = '';
            $Event_Id = $request->event_id;
            $eventImages = EventImage::where('event_id', $Event_Id)->get();
            foreach ($eventImages as $key => $eventImage) {
                if($key == 0){$checked = 'checked';}else{ $checked = '';}

                $Output .= '<li><img src="'.asset($eventImage->image).'" alt=""></li>';
                $InputTag .= '<input type="radio" name="slides" value="'.encrypt($eventImage->id).'" id="slides_'.$key.'" '.$checked.'>';
                $Arrow .= '<label for="slides_'.$key.'" onclick="return GetId('.$eventImage->id.')"></label>';
                $Navigation .= '<label for="slides_'.$key.'" value="'.$eventImage->id.'" onclick="return GetId('.$eventImage->id.')"></label>';
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

            $Img_id = EventImage::where('event_id', $Event_Id)->first()->id;
            //return response($Output);
            return response()->json(array('Output' => $Output, 'ID' => $Img_id), 200);
        }else{
            abort('404');
        }
    }



    public function downloadImage($id){
        //$id = $this->decryptID($id);
        $ImageToDownload = EventImage::withTrashed()->findOrFail($id);
        //Storage::download($ImageToDownload->image, $ImageToDownload->title);

        return response()->download(public_path($ImageToDownload->image), $ImageToDownload->title);
    }


    public function deleteImage($id){
        $id = $this->decryptID($id);
        /*$PostData = EventImage::findOrFail($id);
        if(File::exists(public_path($PostData->thumbnail)) || File::exists(public_path($PostData->image))) {
            File::delete([
                public_path($PostData->thumbnail),
                public_path($PostData->image),
            ]);
        }*/
        EventImage::withoutTrashed()->findOrFail($id)->delete();
        //EventImage::withoutTrashed()->findOrFail($id)->forceDelete();
        return back()->with('Success', 'You have deleted a event image.');
    }


    public function showTrashedImages(Request $request){
        if($request->ajax()){
            $Output = '';
            $InputTag = '';
            $Arrow = '';
            $Navigation = '';
            $checked = '';
            $Event_Id = $request->event_id;
            $eventImages = EventImage::onlyTrashed()->where('event_id', $Event_Id)->get();
            foreach ($eventImages as $key => $eventImage) {
                if($key == 0){$checked = 'checked';}else{ $checked = '';}

                $Output .= '<li><img src="'.asset($eventImage->image).'" alt=""></li>';
                $InputTag .= '<input type="radio" name="slides" value="'.encrypt($eventImage->id).'" id="slides_'.$key.'" '.$checked.'>';
                $Arrow .= '<label for="slides_'.$key.'" onclick="return GetId('.$eventImage->id.')"></label>';
                $Navigation .= '<label for="slides_'.$key.'" value="'.$eventImage->id.'" onclick="return GetId('.$eventImage->id.')"></label>';
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

            $Img_id = EventImage::onlyTrashed()->where('event_id', $Event_Id)->first()->id;
            //return response($Output);
            return response()->json(array('Output' => $Output, 'ID' => $Img_id), 200);
        }else{
            abort('404');
        }
    }
}
