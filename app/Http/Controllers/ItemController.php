<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
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
            ->whereNull('items.deleted_at')
            ->select('items.*', 'categories.name as category_name', 'users.name as user_name')
            ->orderBy('created_at', 'DESC')
            ->paginate();
   
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
        $categoryDatas = Category::all();
        
        return view('pages.items.add-item')->with(compact('categoryDatas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile(['image'])) {
            //them image
            $image = $request['image'];
            $extension = $image->getClientOriginalExtension();
            $name = time(). '_' .$image->getClientOriginalName();
            Storage::disk('public')->put($name, File::get($image));
        }
        else {
            $name = "default.png";
        }
     
        Item::create([
            'title' => $data['title'],
            'publisher' => $data['publisher'],
            'image' => $name,
            'category_id' => $data['category'],
            'user_id' => Session::get('id'),
            'quantity' => $request['quantity'],
            'price' => $request['price'],
        ]);

        return redirect()->route('item.create')->with('message', 'Thêm sản phẩm thành công');
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
        $categoryDatas = Category::all();

        return view('pages.items.update-item')->with(compact('item', 'categoryDatas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, $id)
    {
        $data = $request->validated();
        if ($request->hasFile(['image'])) {
            //them image
            $image = $request['image'];
            $extension = $image->getClientOriginalExtension();
            $name = time(). '_' .$image->getClientOriginalName();
            Storage::disk('public')->put($name, File::get($image));
            $image = $name;

            $data = [
                'title' => $data['title'],
                'publisher' => $data['publisher'],
                'image' => $image,
                'category_id' => $data['category'],
                'user_id' => Session::get('id'),
                'quantity' => $request['quantity'],
                'price' => $request['price'],
            ];
        } else {
            $data = [
                'title' => $data['title'],
                'publisher' => $data['publisher'],
                'category_id' => $data['category'],
                'user_id' => Session::get('id'),
                'quantity' => $request['quantity'],
                'price' => $request['price'],
            ];
        }
        
        Item::find($id)->update($data);

        return redirect()->route('item.index')->with('message-update-success', 'Cập nhật sản phẩm thành công');;
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
        $emailUser = User::where('id', '=' , $item->user_id)->first();
        $email = $emailUser->email;
        $name = $emailUser->name;
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
                'title' => $item->title,
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
            ->whereNull('items.deleted_at')
            ->where('title', 'LIKE', '%' . $key . '%')
            ->orWhere('publisher','LIKE','%' . $key . '%')
            ->orWhere('image','LIKE','%' . $key .'%')
            ->orWhere('category_name','LIKE','%' . $key . '%')
            ->orWhere('user_name','LIKE','%' . $key . '%')
            ->orWhere('quantity','LIKE','%' . $key . '%')
            ->orWhere('price','LIKE','%' . $key . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return  view('pages.items.list-items')->with(compact('data'));
    }
    /**
     * Hiển thị các item đã SoftDelete
     */
    public function showSoftDelete()
    {
        if(Session::get('email')) {
            $data = Item::onlyTrashed()
                ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                ->leftJoin('users', 'items.user_id', '=', 'users.id')
                ->select('items.*', 'categories.name as category_name', 'users.name as user_name')->onlyTrashed()
                ->orderBy('created_at', 'DESC')->paginate();
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
        $itemRestore = Item::withTrashed()->find($id)->restore();

        return $itemRestore;
    }
}
