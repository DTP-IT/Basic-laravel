<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * Hiển thị danh sách Item, loại bỏ các Item bị SoftDelete
         */
        if(Session::get('email')) {
            Session::put('softDelete', null);
            $data = DB::table('items')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->where('items.deleted_at', '=', null)
            ->select('items.*', 'categories.name as category_name', 'users.name as user_name')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
   
            return view('pages.items.list-items')->with(compact('data'));
        } else {
            return Redirect::to('/login');
        }
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category_datas = Category::all();
        
        return view('pages.items.add-item')->with(compact('category_datas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'publisher' => 'required',
            'category' => 'required'
        ]);
        $item = new Item();
        $item->title = $data['title'];
        $item->publisher = $data['publisher'];
        if ($request['image']) {
            //them image
            $image = $request['image'];
            $extension = $image->getClientOriginalExtension();
            $name = time().'_'.$image->getClientOriginalName();
            Storage::disk('public')->put($name,File::get($image));
            $item->image = $name;
        }
        else {
            $item->image = "default.png";
        }
        $item->category_id = $data['category'];
        $item->user_id = Session::get('id');
        $item->quantity = $request['quantity'];
        $item->price = $request['price'];
        $item->save();
        Session::put('message', 'Thêm sản phẩm thành công!');

        return Redirect::to('item/add-item');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Item::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Item::find($id);
        $category_datas = Category::all();

        return view('pages.items.update-item')->with(compact('item', 'category_datas'));
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
        $data = $request->validate([
            'title' => 'required',
            'publisher' => 'required',
            'category' => 'required'
        ]);
        $item = Item::find($id);
        $item->title = $data['title'];
        $item->publisher = $data['publisher'];
        if ($request['image']) {
            //them image
            $image = $request['image'];
            $extension = $image->getClientOriginalExtension();
            $name = time().'_'.$image->getClientOriginalName();
            Storage::disk('public')->put($name,File::get($image));
            $item->image = $name;
        }
        $item->category_id = $data['category'];
        $item->user_id = Session::get('id');
        $item->quantity = $request['quantity'];
        $item->price = $request['price'];
        $item->save();
        Session::put('message-update-success', 'Cập nhật sản phẩm thành công!');

        return Redirect::to('item');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $item = Item::find($id);
        $title = $item->title;
        $email_user = User::where('id', '=' , $item->user_id)->get();
        $email = $email_user['0']['email'];
        $name = $email_user['0']['name'];
        $item->delete($id);
        /**
         * Kiểm tra người xóa có phải admin không
         * Nếu đúng sẽ gửi tin nhắn về mail
         */
        if ($request['level'] == 'Admin') {
            $data = [
                'id' => $id,
                'email' => $email,
                'name' => $name,
                'title' => $title,
            ];
            Mail::send('pages.sendMail', $data, function($message) use ($email, $name) {
                $message->from('phongdo789@gmail.com', 'Phong Do');
                $message->to($email, $name);
                $message->subject('Thông báo');
               
            });
        }
              
        return $item;
    }
    /**
     * Tìm kiếm Item theo các trường
     * Không hiển thị các dữ liệu đã SoftDelete
     */
    public function search(Request $request)
    {
        $key = $request['key'];
        $data = DB::table('items')
                ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                ->leftJoin('users', 'items.user_id', '=', 'users.id')
                ->select('items.*', 'categories.name as category_name', 'users.name as user_name')
                ->where('items.deleted_at', '=', null)
                ->where('title', 'LIKE', '%'.$key.'%')
                ->Orwhere('publisher','LIKE','%'.$key.'%')
                ->Orwhere('image','LIKE','%'.$key.'%')
                ->Orwhere('categories.name','LIKE','%'.$key.'%')
                ->Orwhere('users.name','LIKE','%'.$key.'%')
                ->Orwhere('quantity','LIKE','%'.$key.'%')
                ->Orwhere('price','LIKE','%'.$key.'%')
                ->orderBy('created_at', 'DESC')
                ->paginate(20);

        return  view('pages.items.list-items')->with(compact('data'));
    }
    /**
     * Hiển thị các item đã SoftDelete
     */
    public function showSoftDelete() {
        if(Session::get('email')) {
            $data = Item::onlyTrashed()
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->select('items.*', 'categories.name as category_name', 'users.name as user_name')->onlyTrashed()
            ->orderBy('created_at', 'DESC')->paginate(20);
            Session::put('softDelete', true);

            return view('pages.items.list-items')->with(compact('data'));
        } else {
            return Redirect::to('/login');
        }
    }

    /**
     * Khôi phục sản phẩm bị SoftDelete
     */
    public function restore($id)
    {
        $item_restore = Item::withTrashed()->find($id)->restore();

        return $item_restore;
    }
}
