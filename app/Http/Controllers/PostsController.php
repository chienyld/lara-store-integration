<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Post;
use App\Models\Borrow;
use App\Models\Bulletin;
use App\Models\Carousel;
use DB;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index','type0','type1','type2', 'show','search']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Post::all();
        //return Post::where('title', 'Post Two')->get();
        //$posts = DB::select('SELECT * FROM posts');
        //$posts = Post::orderBy('title','desc')->take(1)->get();
        //$posts = Post::orderBy('title','desc')->get();
        $bulletin = Bulletin::find(1);
        $carousel = Carousel::orderBy('created_at','desc')->get();
        $posts = Post::orderBy('created_at','desc')->paginate(10);
        
        return view('posts.index')->with('posts', $posts)->with('carousel', $carousel)->with('bulletin', $bulletin);
    }
    public function type0(){
        $bulletin = Bulletin::find(1);
        $carousel = 0;
        $posts0 = Post::orderBy('created_at','desc')->where('type', '0')->paginate(10);
        return view('posts.index')->with('posts', $posts0)->with('bulletin', $bulletin)->with('carousel', $carousel);
    }
    public function type1(){
        $bulletin = Bulletin::find(1);
        $carousel = 0;
        $posts1 = Post::orderBy('created_at','desc')->where('type', '1')->paginate(10);
        return view('posts.index')->with('posts', $posts1)->with('bulletin', $bulletin)->with('carousel', $carousel);
    }
    public function type2(){
        $bulletin = Bulletin::find(1);
        $carousel = 0;
        $posts2 = Post::orderBy('created_at','desc')->where('type', '2')->paginate(10);
        return view('posts.index')->with('posts', $posts2)->with('bulletin', $bulletin)->with('carousel', $carousel);
    }
    public function search(Request $request){
        $this->validate($request, [
            'keyword' => 'required'
        ]);
        $keyword = $request->input('keyword');
        $bulletin = Bulletin::find(1);
        $carousel = 0;
        $search = Post::orderBy('created_at','desc')->where('title', 'like', '%' . $keyword . '%' )->paginate(10);
        return view('posts.index')->with('posts', $search)->with('bulletin', $bulletin)->with('carousel', $carousel);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'type' => 'required',
            'deposit' => 'required',
            'inventory' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
		/*$image=Image::make($request->file('cover_image'));
            $image->resize(300,300);
            $path = $image->save('storage/cover_images'. $fileNameToStore);
            */
	    // make thumbnails
	    $thumbStore = 'thumb.'.$filename.'_'.time().'.'.$extension;
            $thumb = Image::make($request->file('cover_image')->getRealPath());
            $thumb->resize(300, 300);
            $thumb->save('storage/cover_images/'.$thumbStore);
		
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        // Create Post
        
        $post = new Post;
        $post->title = $request->input('title');
        $post->deposit = $request->input('deposit');
        $post->inventory = $request->input('inventory');
        $post->total = $request->input('inventory');
        $post->type = $request->input('type');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        
        //Check if post exists before deleting
        if (!isset($post)){
            return redirect('/posts')->with('error', 'No Post Found');
        }

        // Check for correct user
        //if(auth()->user()->id !==$post->user_id){
        if(auth()->user()->privilege !=='sa_admin'){
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        return view('posts.edit')->with('post', $post);
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
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'type' => 'required',
            'deposit' => 'required',
            'inventory' => 'required',
        ]);
		$post = Post::find($id);
         // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
            // Delete file if exists
            Storage::delete('public/cover_images/'.$post->cover_image);
		
	   //Make thumbnails
	    $thumbStore = 'thumb.'.$filename.'_'.time().'.'.$extension;
            $thumb = Image::make($request->file('cover_image')->getRealPath());
            $thumb->resize(300, 300);
            $thumb->save('storage/cover_images/'.$thumbStore);
		
        }

        // Update Post
        $borrow = Borrow::find($id);
        if($borrow){
        $letting=$borrow->qty;
        }
        else{
        $letting=0;    
        }
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->type = $request->input('type');
        $post->deposit = $request->input('deposit');
        $post->inventory = $request->input('inventory');
        $post->total = $request->input('inventory')+$letting;
        if($request->hasFile('cover_image')){
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        
        //Check if post exists before deleting
        if (!isset($post)){
            return redirect('/posts')->with('error', 'No Post Found');
        }

        // Check for correct user
        if(auth()->user()->privilege !=='sa_admin'){
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        if($post->cover_image != 'noimage.jpg'){
            // Delete Image
            Storage::delete('public/cover_images/'.$post->cover_image);
        }
        
        $post->delete();
        return redirect('/posts')->with('success', 'Post Removed');
    }
}
