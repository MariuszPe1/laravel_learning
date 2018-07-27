<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use DB;
//dodana biblioteka, żeby można było kasowac zdjecia ze storage po skasowaniu posta
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
 
    /* 
        dodoany wycinek,żeby zablokować możliwość pisania postów
        bez logowania, ale żeby można było wchodzić na widoki 
        index i show
    */
     public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Post::all(); - ściągnie wszystkie posty
        
        //wyszuka w bazie danych konkretnego wpisu
        //$posts = Post::where('title', '3Third post')->get();
        
        //wersja z zapytaniami SQL ('use DB;' u góry), a reszta to eloquent
        //$posts = DB::select('SELECT * FROM posts ORDER BY created_at DESC');
       
        //$posts = Post::orderBy('created_at', 'desc')->take(1)->get();
        //sciagnie i ułoży w tym wypadku od ostatniego do pierwszego
        //$posts = Post::orderBy('created_at', 'desc')->get();

        $posts = Post::orderBy('created_at', 'desc')->paginate(5);

        return view('posts.index')->with('posts', $posts);
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
            //weryfikacja dodawanej fotki - typ jpt, bmp gif itp, lub brat, max wielkosc 1999kB
            'cover_image' => 'image|nullable|max:1999',
        ]);
        
        //uploadowanie pliku
        //Sprawdzanie czy ktoś pospiął plik przy dodawaniu posta
        if($request->hasFile('cover_image')) {
            //sciagnie całą nazwe pliku z rozszerzeniem
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //wyciagamy samą nazwę pliku
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //wyciągamy samo rozszerzenie pliku
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //nazwa jaką zapiszemy w bazie danych
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload zdjecia - storeAS zapisze fotke w storage/app/public
            //zeby sie tam dostac trzeba zrobić simlink
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            //ustawi to w bazie jak nie ma fotki dodanej
            $fileNameToStore = 'noimage.jpg'; 
        }

        //Dodawanie postu
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();
        return redirect ('/posts')->with('success', 'Post Created');
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

        //Sprawdzamy czy jest poprawny user, zeby nie dało się 
        //wpisac z ręki adresu do edycji postu
        if(auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized page!');
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
            'body' => 'required'
        ]);
        
        //uploadowanie pliku
        //Sprawdzanie czy ktoś pospiął plik przy dodawaniu posta
        if($request->hasFile('cover_image')) {
            //sciagnie całą nazwe pliku z rozszerzeniem
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //wyciagamy samą nazwę pliku
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //wyciągamy samo rozszerzenie pliku
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //nazwa jaką zapiszemy w bazie danych
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload zdjecia - storeAS zapisze fotke w storage/app/public
            //zeby sie tam dostac trzeba zrobić simlink
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            //ustawi to w bazie jak nie ma fotki dodanej
            $fileNameToStore = 'noimage.jpg'; 
        }

        //create post
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')) {
            $post->cover_image = $fileNameToStore;
        }      
        $post->save();
        return redirect ('/posts')->with('success', 'Post Edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post:: find($id);

        if(auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized page!');
        }

        if($post->cover_image != 'noimage.jpg') {
            //kasowanie zdjecia w momencie kasowania postu
            Storage::delete('public/cover_images/'.$post->cover_image);
        }

        $post->delete();
        return redirect ('/posts')->with('success', 'Post Removed');
    }
}
