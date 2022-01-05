<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::paginate();

        return view('pages.users.list-users')->with(compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.users.add-user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        if ($data['password'] == $data['confirmPassword']) {
            $password = md5($data['password']);
        } else {
            return redirect()->route('user.create')->with('message', 'Mật khẩu không trùng khớp!');
        }
        
        if ($request['level']) {
            
            if ($request['level'] != 'Admin') {
                $level = 'User';
            } else {
                $level = 'Admin';
            }
        } else {
            $level = 'User';
        }
        
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $password,
            'level' => $level,
            'created_at'

        ]);

        return redirect()->route('user.create')->with('message', 'Thêm user thành công');

    }

    /**
     * Đăng nhập hệ thống
     */
    public function login(Request $request)
    {
        $email = $request['email'];
        $password = md5($request['password']);
        
        $login = User::where('email', '=', $email)->where('password', '=', $password)->first();
        
        if($login) {
            $count_login = $login->count();
            if($count_login > 0) {
                Session::put('id', $login['id']);
                Session::put('name', $login['name']);
                Session::put('email', $login['email']);
                Session::put('level', $login['level']);

                return redirect()->route('item.index');
            } 
        } else {
            return redirect('login')->with('message', 'Tài khoản hoặc mật khẩu không chính xác!');
        }
    }
    /**
     * Tìm kiếm thông tin tài khoản theo email đăng nhập
     */
    public function profile()
    {
        $data = User::where('email', Session::get('email'))->first();

        return view('pages.profile.profile')->with(compact('data'));
    }

    /**
     * Cập nhật thông tin tài khoản của người đăng nhập
     */
    public function updateProfile(UserRequest $request)
    {
        $data = $request->validated();

        $user = User::find($data['id']);
        $user->name = $data['name'];
        $user->email = $data['email'];
        if ($data['password'] == $data['confirmPassword']) {
            $user->password = md5($data['password']);
        } else {
            Session::put('message', 'Mật khẩu không trùng khớp!');
                
            return Redirect::to('profile');
        }
        $user->save();

        return Redirect::to('profile');
    }
    /**
     * Tìm kiếm User
     */
    public function search(Request $request)
    {
        $key = $request['key'];
        $data = DB::table('users')
            ->where('name', 'LIKE', '%' . $key . '%')
            ->orWhere('email','LIKE','%' . $key . '%')
            ->orWhere('level','LIKE','%' . $key . '%')
            ->paginate();

        return  view('pages.users.list-users')->with(compact('data'));
    }
}
