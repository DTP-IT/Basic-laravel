<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginResquet;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
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
        
        if ($request('level') == 'Admin') {
            $level = 'Admin';
        } else {
            $level = 'User';
        }
        
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => md5($data['password']),
            'level' => $level,
            'created_at'
        ]);

        return redirect()->route('user.create')->with('message', 'Create user success');
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $login = User::where('email', $data['email'])->where('password', md5($data['password']))->first();
        
        if($login) {
            Session::put('login', [
                'id' => $login['id'],
                'name'=> $login['name'],
                'email'=> $login['email'],
                'level'=> $login['level']
            ]);

            return redirect()->route('item.index');
        }

        return redirect()->route('guest.login')->with('message', 'Incorrect account or password!');
    }

    public function profile()
    {
        $data = User::where('email', Session::get('login.email'))->first();

        return view('pages.profile.profile')->with(compact('data'));
    }

    public function updateProfile(UserRequest $request, $id)
    {
        $data = $request->validated();

        $data = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => md5($data['password'])
        ];

        User::where('id', $id)->update($data);

        return redirect()->route('user.profile')->with('message', 'Profile update successful!');
    }
    /**
     * Search User
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
