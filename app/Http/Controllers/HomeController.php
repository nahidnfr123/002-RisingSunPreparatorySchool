<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\ContactDetails;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Images;
use App\Models\Post;
use App\Models\settings\settings_home;
use App\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth')->except('logout');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $Sliders = Images::orderBy('id', 'DESC')->where('home', 1)->paginate(3);
        $Posts = Post::with('post_image')->latest()->paginate(4);
        $Events = Event::with('event_image')->orderBy('created_at', 'DESC')->paginate(2);
        $GalleryImages = Gallery::with('gallery_image')
            ->orderBy('id', 'DESC')->latest()->paginate(1);

        return view('visitor.index', compact('Posts', 'Events', 'Sliders', 'GalleryImages'));
    }

    public function post()
    {
        $Posts = Post::with('post_image')->orderBy('publish_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->where('publish_date','<', Carbon::now())
            ->paginate(8);

        $PopularPosts = Post::with('post_image')->where('visitor', '>', 0)
            ->where('publish_date','<', Carbon::now())
            ->orderBy('visitor', 'DESC')
            ->orderBy('publish_date', 'DESC')->get();

        $Banner = Images::orderBy('id', 'DESC')->where('post', 1)->first();
        return view('visitor.post', compact('Posts', 'PopularPosts', 'Banner'));
    }

    public function postSearch(Request $request){
        $this->validate($request, [
            'search' => 'required|string',
        ]);
        $Search_text = $request->search;
        $Posts = Post::with('post_image')
            ->where('publish_date','<', Carbon::now())
            ->where('title', 'like', '%'.$Search_text.'%')
            ->orWhere('body', 'like', '%'.$Search_text.'%')
            ->orderBy('publish_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->latest();
        if(count($Posts->get()) == 0){$postCount = '';} else {$postCount = count($Posts->get());}
        $Posts = $Posts->paginate(8);

        $PopularPosts = Post::with('post_image')->where('visitor', '>', 0)
            ->where('publish_date','<', Carbon::now())
            ->orderBy('visitor', 'DESC')
            ->orderBy('publish_date', 'DESC')->get();

        $Banner = Images::orderBy('id', 'DESC')->where('post', 1)->first();
        return view('visitor.post_search',compact('Posts', 'PopularPosts', 'Search_text', 'Banner'));
    }

    public function postDetails($title, $id)
    {
        $ip = request()->ip();
        $Cookie_name = 'Post_ID:_'.$id;
        $Cookie_value = $ip;
        $Expire_Time = 1440; // 1 day ....

        $Post = Post::with('post_image')->where('id',$id)->where('title_slug', '=',$title)->where('publish_date','<', Carbon::now())->first();
        $value = Cookie::get($Cookie_name);

        if($value == null){
            if(isset($Post)){
                $Post->increment('visitor');
            }
            Cookie::queue($Cookie_name, $Cookie_value, $Expire_Time);
        }

        $PopularPosts = Post::with('post_image')->where('visitor', '>', 0)
            ->where('publish_date','<', Carbon::now())
            ->orderBy('visitor', 'DESC')
            ->orderBy('publish_date', 'DESC')->get();

        $Banner = Images::orderBy('id', 'DESC')->where('post', 1)->first();
        return view('visitor.post_details', compact('Post', 'PopularPosts', 'Banner'));
    }

    public function event()
    {
        $UpcomingEvents = Event::with('event_image')
            ->where('event_start_date', '>', Carbon::now())
            ->orderBy('created_at', 'DESC')
            ->paginate(6);

        $OngoingEvents = Event::with('event_image')
            ->where('event_start_date', '<', Carbon::now())
            ->where('event_end_date', '>=', Carbon::now())
            ->orderBy('created_at', 'DESC')
            ->paginate(6);

        $PreviousEvents = Event::with('event_image')
            ->where('event_start_date', '<', Carbon::now())
            ->where('event_end_date', '<', Carbon::now())
            ->orderBy('created_at', 'DESC')
            ->paginate(6);

        $Banner = Images::orderBy('id', 'DESC')->where('event', 1)->first();
        return view('visitor.event', compact('UpcomingEvents', 'OngoingEvents', 'PreviousEvents', 'Banner'));
    }
    public function eventDetails($title, $id){
        $Event = Event::with('event_image')
            ->where('id', $id)
            ->where('title_slug', '=',$title)
            ->first();

        $Banner = Images::orderBy('id', 'DESC')->where('event', 1)->first();
        return view('visitor.event_details', compact('Event', 'Banner'));
    }

    public function gallery()
    {
        $GalleryImages = Gallery::with('gallery_image')
            ->orderBy('id', 'DESC')->latest()->paginate(4);

        $Banner = Images::orderBy('id', 'DESC')->where('gallery', 1)->first();
        return view('visitor.gallery', compact('GalleryImages', 'Banner'));
    }

    public function about()
    {
        $About = About::first();
        $Banner = Images::orderBy('id', 'DESC')->where('post', 1)->first();
        return view('visitor.about', compact('About', 'Banner'));
    }

}
