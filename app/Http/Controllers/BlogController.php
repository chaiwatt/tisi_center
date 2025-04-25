<?php

namespace App\Http\Controllers;

use App\Blog;
use App\BlogCategory;
use App\BlogComment;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;

class BlogController extends Controller
{
    private $tags;

    private $permission;

    public function __construct()
    {
        $this->permission  = str_slug('blog','-');
    }

    public function index()
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            // Grab all the blogs
            $blogs = Blog::all();
            // Show the page
            return view('blog.index', compact('blogs'));
        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if(auth()->user()->can('add-'.$this->permission)) {
            $blogcategory = BlogCategory::select('title','id')->get();
            return view('blog.create', compact('blogcategory'));
        }
        abort(403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->can('add-'.$this->permission)) {
            $this->validate($request,[
                'title' => 'required|min:3',
                'content' => 'required|min:3',
                'blog_category_id' => 'required',
            ],
                [
                    'blog_category_id.required' => 'Please select category'
                ]);
            $blog = new Blog($request->except('files','image','tags'));

            $tag_slug = str_slug($request->title, '-');
            $tag_slug = $tag_slug!=''?$tag_slug:str_slug(md5($request->title), '-');
            $blog->slug = $tag_slug;

            $message=$request->get('content');
            $dom = new \DOMDocument();
            $dom->loadHtml('<?xml encoding="utf-8" ?>'.$message);
            $images = $dom->getElementsByTagName('img');

            // foreach <img> in the submited message
            foreach($images as $img){

                $src = $img->getAttribute('src');
                // if the img source is 'data-url'
                if(preg_match('/data:image/', $src)){
                    // get the mimetype
                    preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
                    $mimetype = $groups['mime'];
                    // Generating a random filename
                    $filename = uniqid();
                    $filepath = "/storage/uploads/blog/$filename.$mimetype";
                    // @see http://image.intervention.io/api/
                    $image = Image::make($src)
                        // resize if required
                        /* ->resize(300, 200) */
                        ->encode($mimetype, 100)  // encode file to the specified mimetype
                        ->save(public_path($filepath));
                    $new_src = asset($filepath);
                    $dirname = dirname($filename);

                    $img->removeAttribute('src');
                    $img->setAttribute('src', $new_src);
                } // <!--endif
            } // <!-

            $content = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">', '', $dom->saveHTML());
            $content = str_replace('<?xml encoding="utf-8" ?>', '', $content);
            $content = str_replace('<html>', '', $content);
            $content = str_replace('<body>', '', $content);
            $content = str_replace('</body>', '', $content);
            $content = str_replace('</html>', '', $content);

            $blog->content = $content;

            $picture = "";

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->extension()?: 'png';
                $picture = str_random(10) . '.' . $extension;
                $destinationPath = public_path() . '/storage/uploads/blog/';
                $file->move($destinationPath, $picture);
                $blog->image = $picture;

            }
            $blog->user_id = auth()->user()->getKey();
            $blog->save();

            //Adding tags
            if($request->tags != null){
                $tag_ids = [];
                $tags = explode(',',$request->tags);
                foreach ($tags as $item){

                    $tag_slug = str_slug($item, '-');
                    $tag_slug = $tag_slug!=''?$tag_slug:str_slug(md5($item), '-');

                    $tag =  Tag::where('slug','=', $tag_slug)->first();
                    if($tag == null){
                        $tag = new Tag();
                        $tag->name = $item;
                        $tag->slug = $tag_slug;
                        $tag->save();
                    }
                    $tag_ids[]= $tag->id;
                }
            }
            if(isset($tag_ids)){
                $blog->tags()->attach($tag_ids);
            }

            if ($blog->id) {
                return redirect('/blog')->with('success', 'Blog created successfully');
            } else {
                return redirect('/blog')->withInput()->with('error', trans('blog/message.error.create'));
            }
        }
        abort(403);
    }


    /**
     * Display the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function show(Request $request)
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $blog = Blog::find($request->id);
            $comments = $blog->comments;
            $tags = $blog->tags()->pluck('name')->implode(', ');
            return view('blog.show', compact('blog', 'comments', 'tags'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function edit(Request $request)
    {
        if(auth()->user()->can('edit-'.$this->permission)) {
            $blog = Blog::where('id','=',$request->id)->first();
            $blogcategory = BlogCategory::select('title','id')->get();
            if(count($blog->tags) > 0){
                $tags = $blog->tags()->pluck('name')->implode(', ');
            }else{
                $tags = '';
            }
            return view('blog.edit', compact('blog', 'blogcategory','tags'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function update(Request $request)
    {
        if(auth()->user()->can('edit-'.$this->permission)) {

            $this->validate($request,[
                'title' => 'required|min:3',
                'content' => 'required|min:3',
                'blog_category_id' => 'required',
            ],
                [
                    'blog_category_id.required' => 'Please select category'
                ]);

            $blog = Blog::findOrfail($request->id);

            $tag_slug = str_slug($request->title, '-');
            $tag_slug = $tag_slug!=''?$tag_slug:str_slug(md5($request->title), '-');
            $blog->slug = $tag_slug;

            $blog->save();

            $message=$request->get('content');
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            $dom->loadHtml('<?xml encoding="utf-8" ?>'.$message);
            $images = $dom->getElementsByTagName('img');

            // foreach <img> in the submited message
            foreach($images as $img){
                $src = $img->getAttribute('src');
                // if the img source is 'data-url'
                if(preg_match('/data:image/', $src)){
                    // get the mimetype
                    preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
                    $mimetype = $groups['mime'];
                    // Generating a random filename
                    $filename = uniqid();
                    info($filename);
                    $filepath = "/storage/uploads/blog/$filename.$mimetype";
                    // @see http://image.intervention.io/api/
                    $image = Image::make($src)
                        ->encode($mimetype, 100)  // encode file to the specified mimetype
                        ->save(public_path($filepath));
                    $new_src = asset($filepath);
                } // <!--endif
                else{
                    $new_src=$src;
                }
                $img->removeAttribute('src');
                $img->setAttribute('src', $new_src);
            } // <!-

            $content = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">', '', $dom->saveHTML());
            $content = str_replace('<?xml encoding="utf-8" ?>', '', $content);
            $content = str_replace('<html>', '', $content);
            $content = str_replace('<body>', '', $content);
            $content = str_replace('</body>', '', $content);
            $content = str_replace('</html>', '', $content);

            $blog->content = $content;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->extension()?: 'png';
                $picture = str_random(10) . '.' . $extension;
                $destinationPath = public_path() . '/storage/uploads/blog';
                $file->move($destinationPath, $picture);
                $blog->image = $picture;
                $blog->save();
            }

            if($request->tags != null){
                $tag_ids = [];
                $tags = explode(',',$request->tags);
                foreach ($tags as $item){

                    $tag_slug = str_slug($item, '-');
                    $tag_slug = $tag_slug!=''?$tag_slug:str_slug(md5($item), '-');

                    $tag =  Tag::where('slug','=', $tag_slug)->first();

                    if($tag == null){
                        $tag = new Tag();
                        $tag->name = $item;
                        $tag->slug = $tag_slug;
                        $tag->save();
                    }
                    $tag_ids[]= $tag->id;
                }
            }
            if(isset($tag_ids)){
                $blog->tags()->detach();
                $blog->tags()->attach($tag_ids);
            }

            if ($blog->update($request->except('content','image','files','_method', 'tags'))) {
                return redirect('/blog')->with('success', 'Blog updated');
            } else {
                return redirect('/blog')->withInput()->with('error', 'Something went wrong');
            }
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function destroy(Request $request)
    {
        if(auth()->user()->can('delete-'.$this->permission)) {
            $blog = Blog::findOrfail($request->id);
            if($blog != null){
                $blog->delete();
                return redirect('blog')->with('success', 'Blog deleted');
            }else{
                return redirect('blog')->withInput()->with('error', 'Something went wrong');
            }
        }
        abort(403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BlogCommentRequest $request
     * @param Blog $blog
     *
     * @return Response
     */
    public function storeComment(Request $request)
    {
        $this->validate($request,[
            'comment' => 'required|min:3',
            'name' => 'required|min:3',
            'email' => 'required|email',
        ]);
        $blog = Blog::findOrfail($request->id);
        $data = $request->all();

        $blogcooment          = new BlogComment();
        $blogcooment->comment = strip_tags($data['comment']);
        $blogcooment->name    = strip_tags($data['name']);
        $blogcooment->email   = strip_tags($data['email']);
        $blogcooment->blog_id = $blog->id;
        $blogcooment->save();

        return redirect('/blog/view/' . $blog->id );
    }


    /**
     * Get list of blogs in client side.
     * @return Response
     */
    public function getBlogList(){
        $blogs = Blog::paginate(10);
        return view('blog.frontend.blog-list',compact('blogs'));
    }

    /**
     * Get list of blogs in client side.
     * @param $slug
     * @return Response
     */

    public function getBlog($slug){
        $blog = Blog::where('slug','=',$slug)->first();
        if($blog != null){
            $comments = $blog->comments;
            $tags = $blog->tags()->pluck('name')->implode(', ');
            return view('blog.frontend.show', compact('blog', 'comments', 'tags'));
        }else{
            abort(404);
        }
    }


    /**
     * Get list of blogs belongs to a category.
     * @param $slug
     * @return Response
     */

    public function getCategoryBlog($slug){
        $category = BlogCategory::where('slug','=',$slug)->first();
        $blogs = $category->blogs()->paginate(10);
        if($category != null){
            return view('blog.frontend.category-blogs', compact('blogs','category'));
        }else{
            abort(404);
        }
    }

    /**
     * Get list of blogs belongs to a tag.
     * @param $slug
     * @return Response
     */

    public function getTagBlog($slug){
        $tag = Tag::where('slug','=',$slug)->first();
        if($tag != null){
            $blogs = $tag->blogs()->paginate(10);
            return view('blog.frontend.tag-blogs', compact('blogs', 'tag'));
        }else{
            abort(404);
        }
    }

    public function getAuthorBlog($id){
        $author = User::findOrfail($id);
        if($author != null){
            $blogs = $author->blogs()->paginate(10);
            return view('blog.frontend.author-blogs', compact('blogs', 'author'));
        }else{
            abort(404);
        }
    }


}
