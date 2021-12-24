<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Category::all();

        return view('pages.categories.list-categories')->with(compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Session::get('level') == 'Admin') {
            return view('pages.categories.add-category');
        } else {
            return Redirect::to('/login');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Session::get('level') == 'Admin') {
            $data = $request->validate([
                'name' => 'required',
            ]);
            $category = new Category();
            $category->name = $data['name'];
            $category->save();
            Session::put('message', 'Thêm thể loại thành công');
            
            return Redirect::to('category/add-category');
        } else {
            return Redirect::to('/login');
        }
    }
}
