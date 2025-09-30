<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. إذا كان المستخدم قد سجل دخوله بالكامل، اسمح له بالمرور.
        if (Auth::check()) {
            return $next($request);
        }

        // 2. إذا كان المستخدم قد أنهى مرحلة كلمة المرور وله ID في الجلسة (في انتظار 2FA)، قم بتوجيهه لصفحة التحقق.
        if ($request->session()->has('2fa_user_id')) {
            // تحقق: إذا كان المسار الحالي هو بالفعل مسار التحقق، استمر في العرض.
            if ($request->routeIs('verification.notice') || $request->routeIs('verification.verify')) {
                 return $next($request);
            }
            // وإلا، قم بإعادة توجيهه إلى صفحة التحقق.
            return redirect()->route('verification.notice');
        }

        // 3. إذا لم يكن المستخدم مصادقاً عليه وليس لديه 2fa_user_id، أعده لصفحة تسجيل الدخول.
        return redirect()->route('login');
    }
}