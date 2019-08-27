<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;

class PostsController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth',['except'=>['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts=Post::all();
        $posts =Post::orderBy('created_at','desc')->paginate(10);
        //$posts =Post::orderBy('title','desc')->take(1)->get();(show the take number amount)
        //$posts=Post::where('title','Post Two')->get();
        //$posts =DB::select('SELECT * FROM posts');
        return view('posts.index')
            ->with('posts',$posts);
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
        $this -> validate($request,[
            'title'=>'required',
            'body'=> 'required',
            'cover_image'=>'image|nullable|max:1999' //under 2mbs
            ]);
        //handle file upload
        if($request->hasFile('cover_image')){
            //get file name with the ext
            $filenamewithExt = $request->file('cover_image')->getClientOriginalName();
            //get just file name
            $filename = pathinfo($filenamewithExt, PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore=$filename.'_'.time().'.'.$extension;
            //Upload img
            $path=$request->file('cover_image')->storeAs('public/cover_image', $fileNameToStore);
        }else{
            $fileNameToStore='noimage.jpg';
        }
        $post = new Post;
        $post->title=$request->input('title');
        $post->body=$request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image= $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success','New Post Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post= Post::find($id);
        return view('posts.show')
            ->with('post',$post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post= Post::find($id);
        //chck for correct user
        if(auth()->user()->id !==$post->user_id){
            return redirect('/posts')->with('error','Unauthorized Page');
        }
        return view('posts.edit')
            ->with('post',$post);
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
        $this -> validate($request,[
            'title'=>'required',
            'body'=> 'required',
            'cover_image'=>'image|nullable|max:1999' //under 2mbs
            ]);
        //handle file upload
        if($request->hasFile('cover_image')){
            //get file name with the ext
            $filenamewithExt = $request->file('cover_image')->getClientOriginalName();
            //get just file name
            $filename = pathinfo($filenamewithExt, PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore=$filename.'_'.time().'.'.$extension;
            //Upload img
            $path=$request->file('cover_image')->storeAs('public/cover_image', $fileNameToStore);
        }
        $post = Post::find($id);
        $post->title=$request->input('title');
        $post->body=$request->input('body');
        if($request->hasFile('cover_image')){
            $post->cover_image=$fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success','New Post Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post= Post::find($id);
        //chck for correct user
        if(auth()->user()->id !==$post->user_id){
            return redirect('/posts')->with('error','Unauthorized Page');
        }

        if($post->cover_image!=='noimage.jpg'){
            //delete img
            Storage::delete('public/cover_image/'.$post->cover_image);
        }

        $post->delete();
        return redirect('/posts')->with('success','Post Removed');
    }
}
