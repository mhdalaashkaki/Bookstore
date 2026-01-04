<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

// متحكم المصادقة - تسجيل الدخول والتسجيل
class AuthController extends Controller
{
    /**
     * معالجة تسجيل الدخول
     */
    public function login(Request $request)
    {
        // قبول بريد أو اسم مستخدم مع كلمة المرور
        $data = $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $login = $data['login'];
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (auth()->attempt([$field => $login, 'password' => $data['password']])) {
            return redirect()->route('home')->with('success', 'تم تسجيل الدخول بنجاح!');
        }

        return redirect()->back()
                        ->with('error', 'بيانات تسجيل الدخول غير صحيحة');
    }

    /**
     * معالجة التسجيل
     */
    public function register(Request $request)
    {
        // التحقق من بيانات التسجيل
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // إنشاء مستخدم جديد
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // جميع المستخدمين الجدد يكونون users عاديين
        ]);

        // تسجيل الدخول تلقائياً بعد التسجيل
        auth()->login($user);

        event(new Registered($user));

        return redirect()->route('home')->with('success', 'تم إنشاء الحساب بنجاح! مرحباً ' . $user->name);
    }

    /**
     * معالجة تسجيل الخروج
     */
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
