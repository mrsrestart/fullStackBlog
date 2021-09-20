<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Blogcategory;
use App\Models\Blogtag;
use App\Models\Role;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        } elseif (Auth::check() && $request->path() == 'login') {
            return redirect('/');
        } else {
            if ($user) {
                return $this->checkPermission($user, $request);
            } else {
                return view('welcome');
            }
        }

    }

    public function checkPermission($user, $request)
    {
        $permission = json_decode($user->role->permission);

        $path = $request->path();
        $hasPermission = false;
        if ($path) {
            foreach ($permission as $item) {
                if ($path != '/') {
                    if ($item->name == $path && $item->read) {
                        $hasPermission = true;
                    } else {
                        $hasPermission = true;

                    }
                } else {
                    if ($item->name == 'home' && $item->read) {
                        $hasPermission = true;
                    }
                }
            }

            if ($hasPermission) return view('welcome');
            return view('notFound');
        } else {
            return view('welcome');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function addTag(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required'
        ]);
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }

    public function editTag(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required'
        ]);
        return Tag::whereId($request->id)->update([
            'tagName' => $request->tagName
        ]);
    }

    public function deleteTag(Request $request)
    {
        $tag = Tag::find($request->id);
        return $tag->delete();

    }

    public function getTag()
    {
        return Tag::all();
    }

    //upload Image
    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required | mimes : jpeg,jpg,png'
        ]);
        $picName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $picName);
        return $picName;
    }

    //upload image in blog
    public function imageBlog(Request $request)
    {
        $this->validate($request, [
            'image' => 'required | mimes : jpeg,jpg,png'
        ]);
        $picName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $picName);
        return response()->json([
            'success' => 1,
            'file' => [
                "url" => "http://127.0.0.1:8000/uploads/$picName"
            ]
        ]);
    }

    public function addCat(Request $request)
    {
        return Category::create([
            'iconImage' => $request->iconImage,
            'categoryName' => $request->categoryName
        ]);

    }

    public function getCat()
    {
        return Category::all();
    }

    public function deleteCat(Request $request)
    {
        $cat = Category::find($request->id);
        return $cat->delete();

    }

    public function editCat(Request $request)
    {

        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
            'id' => 'required'
        ]);
        return Category::whereId($request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }

    public function deleteImage(Request $request)
    {
        $image = $request->iconImage;
        $this->deleteFileFromServer($image);
        return "done";
    }

    public function deleteFileFromServer($fileName)
    {
        $filePath = public_path() . '/uploads/' . $fileName;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }


    public function addAdminUser(Request $request)
    {
        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        $password = Hash::make($request->password);

        return User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role_id
        ]);
    }

    public function getAminUser()
    {
        return User::all();
    }

    public function deleteAminUser(Request $request)
    {
        $cat = User::find($request->id);
        return $cat->delete();

    }


    public function editAdminUser(Request $request)
    {

        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'required',
            'userType' => 'required',
        ]);
        return User::whereId($request->id)->update([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'userType' => $request->userType
        ]);
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'password' => 'required',
            'email' => 'required',
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->role->is_admin == 0) {
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect Login Detail'
                ]);
            }
            return response()->json([
                'msg' => 'You Are Logged in',
                'user' => $user
            ], 201);
        } else {
            return response()->json([
                'msg' => 'Incorrect Login Detail'
            ]);
        }
    }

    public function getAllRole()
    {
        return Role::all();
    }

    public function createRole(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required',
        ]);
        return Role::create([
            'roleName' => $request->roleName
        ]);
    }

    public function deleteRole(Request $request)
    {
        $role = Role::find($request->id);
        return $role->delete();
    }

    public function editRole(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required'
        ]);
        return Role::whereId($request->id)->update([
            'roleName' => $request->roleName
        ]);
    }


    public function assignRole(Request $request)
    {
        $this->validate($request, [
            'permission' => 'required',
            'id' => 'required'
        ]);
        return Role::whereId($request->id)->update([
            'permission' => $request->permission,
        ]);
    }


//Blog functions
    public function createBlog(Request $request)
    {
        DB::beginTransaction();
        try {
            $title = $request->title;
            $metaDescription = $request->metaDescription;
            $post_excerpt = $request->post_excerpt;
            $post = $request->post;
            $jsonData = $request->jsonData;
            $blog =  Blog::create([
                'title' => $title,
                'post_excerpt' => $post_excerpt,
                'user_id' => Auth::user()->id,
                'metaDescription' => $metaDescription,
                'post' => $post,
                'jsonData' => $jsonData
            ]);
            $categories = $request->category_id;
            $tags = $request->tag_id;
            $categoriesItem = array();
            foreach ($categories as  $item){
                array_push($categoriesItem ,['category_id'=>$item , 'blog_id'=>$blog->id]);
            }
            Blogcategory::insert($categoriesItem);

            $tagItem = array();
            foreach ($tags as  $item){
                array_push($tagItem ,['tag_id'=>$item , 'blog_id'=>$blog->id]);
            }
            Blogtag::insert($tagItem);
            DB::commit();
            return 'done';
        }catch (\Throwable $th){
            DB::rollBack();
            return "not Done";
        }


    }

    public function editBlogs(Request $request , $id)
    {
        DB::beginTransaction();
        try {
            $title = $request->title;
            $metaDescription = $request->metaDescription;
            $post_excerpt = $request->post_excerpt;
            $post = $request->post;
            $jsonData = $request->jsonData;
            $blog = Blog::whereId($id)->update([
                'title' => $title,
                'post_excerpt' => $post_excerpt,
                'user_id' => Auth::user()->id,
                'metaDescription' => $metaDescription,
                'post' => $post,
                'jsonData' => $jsonData
            ]);

            $categories = $request->category_id;
            $tags = $request->tag_id;
            $categoriesItem = array();
            foreach ($categories as  $item){
                array_push($categoriesItem ,['category_id'=>$item , 'blog_id'=>$id]);
            }

            Blogcategory::where('blog_id', $id)->delete();
            Blogcategory::insert($categoriesItem);

            $tagItem = array();
            foreach ($tags as  $item){
                array_push($tagItem ,['tag_id'=>$item , 'blog_id'=>$id]);
            }
            Blogtag::where('blog_id', $id)->delete();
            Blogtag::insert($tagItem);
            DB::commit();
            return 'done';
        }catch (\Throwable $th){
            DB::rollBack();
            return "not Done";
        }

    }

    public function getBlogs()
    {
       return Blog::with(['tag' , 'cat'])->orderBy('id','desc')->get();
    }

    public function getBlogData(Request $request , $id)
    {

       return Blog::with(['tag' , 'cat'])->where('id',$id)->first();
    }

    public function deleteBlog(Request $request)
    {
        $blog = Blog::find($request->id);
        return $blog->delete();

    }

}
