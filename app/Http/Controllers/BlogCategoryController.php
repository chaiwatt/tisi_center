<?php

namespace App\Http\Controllers;

use App\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BlogCategoryController extends Controller
{
    private $permission;

    public function __construct()
    {
        $this->permission  = str_slug('blog-category','-');
    }

    public function getIndex(){
        if(auth()->user()->can('view-'.$this->permission)) {
            $categories =  BlogCategory::paginate(10);
            return view('blog-category.index',compact('categories'));
        }
        abort(403);
    }

    public function create(){
        if(auth()->user()->can('add-'.$this->permission)) {
            $categories = BlogCategory::all();
            return view('blog-category.create',compact('categories'));
        }
        abort(403);
    }

    public function save(Request $request){
        if(auth()->user()->can('add-'.$this->permission)) {
            $this->validate($request,[
                'title' => 'required'
            ]);
            $category = BlogCategory::firstOrCreate(['title' => $request->title]);
            $category->slug = str_slug($request->title);
            $category->save();

            Session::flash('message','Blog category has been added');
            return back();
        }
        abort(403);
    }

    public function edit(Request $request){
        if(auth()->user()->can('edit-'.$this->permission)) {
            $category = BlogCategory::findOrfail($request->id);
            return  view('blog-category.edit',compact('category'));
        }
        abort(403);


    }

    public function update(Request $request){
        if(auth()->user()->can('edit-'.$this->permission)) {
            $this->validate($request,[
                'title' => 'required'
            ]);
            $category = BlogCategory::findOrfail($request->id);
            $category->title = $request->title;
            $category->slug = str_slug($request->title);
            $category->save();
            Session::flash('message','Blog category has been updated');
            return redirect('blog-category');
        }
        abort(403);


    }

    public function delete(Request $request){
        if(auth()->user()->can('delete-'.$this->permission)) {
            $category = BlogCategory::findOrfail($request->id);
            $category->delete();
            Session::flash('message','Blog category has been deleted');
            return back();
        }
        abort(403);
    }
}
