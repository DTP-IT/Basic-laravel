<?php

namespace App\Http\Controllers;

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
        /**
         * Kiểm tra quyền hạn, chỉ các tài khoản admin mới được quyền sử dụng
         */
        if(Session::get('level') == 'Admin') {
            $data = User::paginate(20);

            return view('pages.users.list-users')->with(compact('data'));
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
        /**
         * Kiểm tra quyền hạn, chỉ các tài khoản admin mới được quyền sử dụng
         */
        if(Session::get('level') == 'Admin') {
            return view('pages.users.add-user');
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
        /**
         * Kiểm tra quyền hạn, chỉ các tài khoản admin mới được quyền sử dụng
         */
        if(Session::get('level') == 'Admin') {
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'confirmPassword' => 'required',
            ]);
            
            $user = new User();
            
            $user->name = $data['name'];
            $user->email = $data['email'];
            if ($data['password'] == $data['confirmPassword']) {
                $user->password = md5($data['password']);
            } else {
                Session::put('message', 'Mật khẩu không trùng khớp!');
                    
                return Redirect::to('user/add-user');
            }
            
            if ($request['level']) {
                
                if ($request['level'] != 'Admin') {
                    $user->level = 'User';
                } else {
                    $user->level = 'Admin';
                }
            } else {
                $user->level = 'User';
            }
        
            $user->save();
            Session::put('message', 'Thêm User thành công!');

            return Redirect::to('user/add-user');
        } else {
            return Redirect::to('/login');
        }
    }

    /**
     * Đăng nhập hệ thống
     */
    public function login(Request $request)
    {
        $email = $request['email'];
        $password = md5($request['password']);
        
        $login = User::where('email', '=', $email)->where('password', '=', $password)->get();
        
        if($login) {
            $count_login = $login->count();
            if($count_login > 0) {
                Session::put('id', $login['0']['id']);
                Session::put('name', $login['0']['name']);
                Session::put('email', $login['0']['email']);
                Session::put('level', $login['0']['level']);

                return Redirect::to('/');
            } else {
                Session::put('message', 'Tài khoản hoặc mật khẩu không chính xác!');
                
                return Redirect::to('login');
            }
        } 
    }
    /**
     * Tìm kiếm thông tin tài khoản theo email đăng nhập
     */
    public function profile()
    {
        $data = User::where('email', '=', Session::get('email'))->get();

        return view('pages.profile.profile')->with(compact('data'));
    }

    /**
     * Cập nhật thông tin tài khoản của người đăng nhập
     */
    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'confirmPassword' => 'required',
        ]);
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
        /**
         * Kiểm tra quyền hạn, chỉ các tài khoản admin mới được quyền sử dụng
         */
        if(Session::get('level') == 'Admin') {
            $key = $request['key'];
            $data = DB::table('users')
                    ->where('name', 'LIKE', '%'.$key.'%')
                    ->Orwhere('email','LIKE','%'.$key.'%')
                    ->Orwhere('level','LIKE','%'.$key.'%')
                    ->paginate(20);

            return  view('pages.users.list-users')->with(compact('data'));
        } else {
            return Redirect::to('/login');
        }
    }
}
