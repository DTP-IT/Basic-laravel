<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
        // Display Item list, remove SoftDelete items
        $data = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->join('users', 'items.user_id', '=', 'users.id')
            ->whereNull('items.deleted_at')
            ->whereNull('categories.deleted_at')
            ->whereNull('users.deleted_at')
            ->select('items.*', 'categories.name as category_name', 'users.name as user_name')
            ->orderBy('created_at', 'DESC')
            ->paginate();
        
        return view('pages.items.list-items')->with(compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoriesData = Category::all();
        
        return view('pages.items.add-item')->with(compact('categoriesData'));
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
            //add image
            $image = $request['image'];
            $extension = $image->getClientOriginalExtension();
            $name = time() . '_' . $image->getClientOriginalName();
            Storage::disk('public')->put($name, File::get($image));
        } else {
            $name = "default.png";
        }
     
        Item::create([
            'title' => $data['title'],
            'publisher' => $data['publisher'],
            'image' => $name,
            'category_id' => $data['category'],
            'user_id' => Session::get('login.id'),
            'quantity' => $request['quantity'],
            'price' => $request['price'],
        ]);

        return redirect()->route('item.create')->with('message', 'Create Item success!');
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
        $categoriesData = Category::all();

        return view('pages.items.update-item')->with(compact('item', 'categoriesData'));
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
            //add image
            $image = $request['image'];
            $extension = $image->getClientOriginalExtension();
            $name = time() . '_' . $image->getClientOriginalName();
            Storage::disk('public')->put($name, File::get($image));
            $image = $name;

            $data = [
                'title' => $data['title'],
                'publisher' => $data['publisher'],
                'image' => $image,
                'category_id' => $data['category'],
                'user_id' => Session::get('login.id'),
                'quantity' => $request['quantity'],
                'price' => $request['price'],
            ];
        } else {
            $data = [
                'title' => $data['title'],
                'publisher' => $data['publisher'],
                'category_id' => $data['category'],
                'user_id' => Session::get('login.id'),
                'quantity' => $request['quantity'],
                'price' => $request['price'],
            ];
        }
        
        Item::where('id', $id)->update($data);

        return redirect()->route('item.index')->with('message-update-success', 'Update Item success');
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
        $email = $emailUser['email'];
        $name = $emailUser['name'];
        $item->delete($id);
        /**
         * Check admin deleter account
         * If correct, will send a message to email
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
     * Search Item by fields
     * Do not display SoftDelete data
     */
    public function search(Request $request)
    {
        $key = $request['key'];
        $data = DB::table('items')
            ->select('items.*', 'categories.name as category_name', 'users.name as user_name')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->join('users', 'items.user_id', '=', 'users.id')
            ->whereNull('items.deleted_at')
            ->whereNull('categories.deleted_at')
            ->whereNull('users.deleted_at')
            ->where('title', 'LIKE', '%' . $key . '%')
            ->orWhere('publisher','LIKE','%' . $key . '%')
            ->orWhere('image','LIKE','%' . $key .'%')
            ->orWhere('categories.name','LIKE','%' . $key . '%')
            ->orWhere('users.name','LIKE','%' . $key . '%')
            ->orWhere('quantity','LIKE','%' . $key . '%')
            ->orWhere('price','LIKE','%' . $key . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return  view('pages.items.list-items')->with(compact('data'));
    }
    
    // Show SoftDelete items
    public function showSoftDelete()
    {
        $data = Item::onlyTrashed()
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->join('users', 'items.user_id', '=', 'users.id')
            ->select('items.*', 'categories.name as category_name', 'users.name as user_name')
            ->orderBy('created_at', 'DESC')->paginate();
            
        $isSoftDelete = true;

        return view('pages.items.list-items')->with(compact('data', 'isSoftDelete'));
    }

    // Recovery SoftDelete items
    public function restore($id)
    {
        $itemRestore = Item::withTrashed()->find($id)->restore();

        return $itemRestore;
    }
}
