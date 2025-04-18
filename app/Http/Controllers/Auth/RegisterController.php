<?php 
 
namespace App\Http\Controllers\Auth; 
 
use App\Models\User; 
use App\Models\Admin; 
use App\Models\Author; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator; 
use Illuminate\Foundation\Auth\RegistersUsers; 
use Illuminate\Http\Request; 
 
class RegisterController extends Controller 
{ 
    /* 
|-------------------------------------------------------------------------- 
    | Register Controller 
    |-------------------------------------------------------------------------- 
    | 
    | This controller handles the registration of new users as well as their 
    | validation and creation. By default this controller uses a trait to 
    | provide this functionality without requiring any additional code. 
    | 
    */ 
 
    use RegistersUsers; 
 
    /** 
     * Where to redirect users after registration. 
     * 
     * @var string 
     */ 
    protected $redirectTo = '/home'; 
 
    /** 
     * Create a new controller instance. 
     * 
     * @return void 
     */ 
    public function __construct() 
    { 
        $this->middleware('guest'); 
        $this->middleware('guest:admin'); 
        $this->middleware('guest:author'); 
    } 
 
    /** 
     * Get a validator for an incoming registration request. 
     * 
     * @param  array  $data 
     * @return \Illuminate\Contracts\Validation\Validator 
     */ 
    protected function validator(array $data) 
    { 
        $table = request()->is('register/author') ? 'authors' : 
            (request()->is('register/admin') ? 'admins' : 'users');

        return Validator::make($data, [ 
            'name' => ['required', 'string', 'max:255'], 
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.$table],
            'password' => ['required', 'string', 'min:6', 'confirmed'], 
        ]); 
    } 
 
    /** 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View 
     */ 
    public function showAdminRegisterForm() 
    { 
        return view('auth.register', ['url' => 'admin']); 
    } 
 
    /** 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View 
     */ 
    public function showAuthorRegisterForm() 
    { 
        return view('auth.register', ['url' => 'author']); 
    } 
/** 
     * @param array $data 
     * 
     * @return mixed 
     */ 
    protected function create(array $data) 
    { 
        return Author::create([ 
            'name' => $data['name'], 
            'email' => $data['email'], 
            'password' => Hash::make($data['password']), 
        ]); 
    } 
 
    /** 
     * @param Request $request 
     * 
     * @return \Illuminate\Http\RedirectResponse 
     */ 
    protected function createAdmin(Request $request) 
    { 
        $this->validator($request->all())->validate(); 
        Admin::create([ 
            'name' => $request->name, 
            'email' => $request->email, 
            'password' => Hash::make($request->password), 
        ]); 
        return redirect()->intended('login/admin'); 
    } 
 
    /** 
     * @param Request $request 
     * 
     * @return \Illuminate\Http\RedirectResponse 
     */ 
    protected function createAuthor(Request $request) 
    { 
        $this->validator($request->all())->validate(); 
        Author::create([ 
            'name' => $request->name, 
            'email' => $request->email, 
            'password' => Hash::make($request->password), 
        ]); 
        return redirect()->intended('login/author'); 
    } 
} 