<?php

namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;


class UsersController extends Controller
{

    use ValidatesRequests;

    /*=================== Authentication ===================*/

    // عرض صفحة تسجيل الدخول
    public function login(Request $request)
    {
        return view('users.login');
    }

    // تنفيذ تسجيل الدخول
    public function doLogin(Request $request)
    {
        // محاولة تسجيل الدخول
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
        }
    
        // جلب بيانات المستخدم
        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);
    
        // التأكد إن الإيميل متفعل
        if (!$user->email_verified_at) {
            Auth::logout(); // علشان نتأكد إنه مش متسجل دخول
            return redirect()->back()->withInput($request->input())->withErrors('Your email is not verified.');
        }
    
        return redirect('/');
    }
    

    // تسجيل الخروج
    public function doLogout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    // عرض صفحة التسجيل
    public function register(Request $request)
    {
        return view('users.register');
    }

    // تنفيذ التسجيل
    public function doRegister(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'confirmed', Password::min(8)],
                'account_type' => ['required', 'in:customer,admin'],
                'credit' => ['required_if:account_type,customer', 'numeric', 'min:0']
            ]);

            if ($validated['account_type'] === 'admin') {
                $adminExists = User::role('admin')->exists();
                if ($adminExists && (!auth()->check() || !auth()->user()->hasRole('admin'))) {
                    return redirect()->back()->withInput()->withErrors('Only existing administrators can create new admin accounts.');
                }
            }

            // إنشاء المستخدم
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'credit' => $validated['account_type'] === 'customer' ? $validated['credit'] : 0
            ]);

            // تعيين الدور
            $user->assignRole($validated['account_type']);

            // إرسال رابط التحقق بالإيميل
            $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
            $link = route("verify", ['token' => $token]);
            Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

            // تسجيل الدخول تلقائيًا
            if (!auth()->check()) {
                Auth::login($user);
            }

            $successMessage = $validated['account_type'] === 'admin'
                ? 'تم إنشاء حساب المدير بنجاح'
                : 'تم التسجيل بنجاح! تم إرسال رابط التفعيل إلى بريدك الإلكتروني.';

            return redirect('/')->with('success', $successMessage);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }


    /*=================== Users Listing & View ===================*/

    // قائمة المستخدمين - index
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // عرض قائمة المستخدمين مع فلترة
    public function list(Request $request)
    {
        $authuser = User::find(Auth::id());
        if (!$authuser->hasPermissionTo('show_users')) abort(401);

        $query = User::select(['id', 'name', 'email', 'created_at'])
            ->when($request->keywords, function ($q) use ($request) {
                return $q->where('name', 'like', "%{$request->keywords}%")
                    ->orWhere('email', 'like', "%{$request->keywords}%");
            });
        $users = $query->paginate(10);
        return view('users.list', compact('users'));
    }

    // حذف مستخدم
    public function delete(Request $request, User $user)
    {
        $authuser = User::find(Auth::id());
        if (!$authuser->hasPermissionTo('delete_users')) abort(401);

        if ($authuser->id === $user->id) {
            return redirect()->back()->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        if ($user->hasRole('super-admin')) {
            return redirect()->back()->with('error', 'لا يمكن حذف المستخدم الرئيسي');
        }

        $user->delete();
        return redirect()->route('users.list')->with('success', 'تم حذف المستخدم بنجاح');
    }

    // نسخة ثانية من delete (يفضل حذف التكرار واستخدام واحدة فقط)
    public function destroy(Request $request, User $user)
    {
        $authuser = Auth::user();
        if ($authuser->id === $user->id) {
            return redirect()->back()->with('error', 'لا يمكنك حذف حسابك الخاص');
        }
        if ($user->hasRole('super-admin')) {
            return redirect()->back()->with('error', 'لا يمكن حذف المستخدم الرئيسي');
        }
        $user->delete();
        return redirect()->route('users.list')->with('success', 'تم حذف المستخدم بنجاح');
    }

    /*=================== Profile ===================*/

    // عرض الملف الشخصي
    public function profile(Request $request, User $user = null)
    {
        $user = $user ?? auth()->user();

        if (auth()->user()->id != $user->id && !auth()->user()->hasPermissionTo('show_users')) {
            abort(401);
        }

        $permissions = [];
        foreach ($user->permissions as $permission) $permissions[] = $permission;
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) $permissions[] = $permission;
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    // تعديل البيانات
    public function edit(Request $request, User $user = null)
    {
        $user = $user ?? auth()->user();

        if (auth()->user()->id != $user->id && !auth()->user()->hasPermissionTo('edit_users')) {
            abort(401);
        }

        $roles = Role::all()->map(function ($role) use ($user) {
            $role->taken = $user->hasRole($role->name);
            return $role;
        });

        $permissions = Permission::all()->map(function ($permission) use ($user) {
            $permission->taken = $user->hasDirectPermission($permission->name);
            return $permission;
        });

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    // حفظ التعديلات
    public function save(Request $request, User $user)
    {
        if (auth()->user()->id != $user->id && !auth()->user()->hasPermissionTo('edit_users')) {
            abort(401);
        }

        $user->name = $request->name;

        if (auth()->user()->hasRole('admin')) {
            if (in_array('customer', $request->roles)) {
                $user->credit = $request->credit;
            }
            $user->syncRoles($request->roles);
            Artisan::call('cache:clear');
        }

        $user->save();

        return redirect(route('profile', ['user' => $user->id]))
            ->with('success', 'تم حفظ البيانات بنجاح');
    }

    /*=================== Password ===================*/

    // عرض صفحة تعديل الباسورد
    public function editPassword(Request $request, User $user = null)
    {
        $user = $user ?? auth()->user();

        if (auth()->id() != $user->id && !auth()->user()->hasPermissionTo('edit_users')) {
            abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    // حفظ الباسورد الجديد
    public function savePassword(Request $request, User $user)
    {
        $authuser = User::find(Auth::id());

        if ($authuser->id === $user->id) {
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if (!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                Auth::logout();
                return redirect('/');
            }
        } elseif (!$authuser->hasPermissionTo('edit_users')) {
            abort(401);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect(route('profile', ['user' => $user->id]));
    }

    /*=================== Credit Management ===================*/

    // إضافة رصيد
    public function addCredit(Request $request, User $user)
    {
        if (!auth()->user()->hasPermissionTo('add_credit')) {
            abort(403);
        }

        $validated = $this->validate($request, [
            'amount' => 'required|numeric|min:0'
        ]);

        $user->credit += $validated['amount'];
        $user->save();

        return redirect()->back()->with('success', 'تم إضافة الرصيد بنجاح');
    }

    /*=================== Employee & Customer ===================*/

    // عرض العملاء للموظف
    public function myCustomers()
    {
        if (!auth()->user()->hasRole('employee')) {
            abort(403);
        }

        $customers = User::role('customer')->get();
        return view('users.customers', compact('customers'));
    }
    //انشاء عملاء 
    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('users.create'); // لازم يكون عندك الملف ده
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:customer,employee,admin'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->role); // استخدمي spatie/laravel-permission

        return redirect()->route('users')->with('success', 'User created successfully!');
    }



    // عرض صفحة إنشاء موظف
    public function createEmployee()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('users.create_employee');
    }

    // تنفيذ إنشاء موظف
    public function storeEmployee(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $this->validate($request, [
            'name' => ['required', 'string', 'min:5'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $employee = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'credit' => 0
        ]);

        $employee->assignRole('employee');

        return redirect()->route('users.my-customers')->with('success', 'تم إنشاء حساب الموظف بنجاح');
    }

    /*=================== Roles ===================*/

    // استرجاع أدوار المستخدم
    public function getUserRoles($userId)
    {
        $user = User::find($userId);
        if (!$user) return back()->with('error', 'المستخدم غير موجود');

        $roles = $user->roles;
        return view('users.roles', compact('roles'));
    }


    public function showProfile()
    {
        $user = auth()->user();  // الحصول على المستخدم الحالي
        $purchases = $user->purchases()->with('product')->get();  // جلب كل عمليات الشراء للمستخدم مع معلومات المنتجات

        return view('users.profile', compact('user', 'purchases'));
    }

    public function verify(Request $request) {
    $decryptedData = json_decode(Crypt::decryptString($request->token),true); 
    $user = User::find($decryptedData['id']);
    if(!$user) abort(401);
    $user->email_verified_at = Carbon::now();
    $user->save();
 return view('users.verified', compact('user'));
}
public function redirectToGoogle()
 {
 return Socialite::driver('google')->redirect();
}


public function handleGoogleCallback() {
    try {
        $googleUser = Socialite::driver('google')->user();
        $user = User::updateOrCreate([
            'google_id'=> $googleUser->id,
        ], [
    'name'=> $googleUser->name,
    'email'=> $googleUser->email,
    'google_token'=> $googleUser->token,
    'google_refresh_token'=> $googleUser->refreshToken
    ]);
    Auth::login($user);

    return redirect('/');
    }catch (\Exception $e) {
        return redirect('/login')->with('error', 'Google login failed.'); 
    }
}
}
