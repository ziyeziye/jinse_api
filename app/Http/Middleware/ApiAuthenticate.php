<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class ApiAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        //返回json
        return false;
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                // 做过期时间验证
                $user = $this->auth->guard($guard)->user();
                $expires_at = $user->expires_at;
                $time = time();
                $expiresTime = strtotime($expires_at);
                $date = date("Y-m-d H:i:s", $time + 1800);

                //记住我
                if ($user->remember_token==$user->api_token) {
                    return $this->auth->shouldUse($guard);
                }else{
                    if ($time > $expiresTime) {
                        return "token已过期,请重新登录";
                    } else if (($expiresTime - $time) < 300) {
                        //未过期则代表持续活跃中,且token剩余过期时间小于5分钟，则延迟过期时间(30分钟)
                        $this->auth->guard($guard)->user()->update(['expires_at' => $date]);
                    }
                }

                return $this->auth->shouldUse($guard);
            }
        }
        return $this->unauthenticated($request, $guards);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string[] ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $check = $this->authenticate($request, ['api']);
        if (false === $check) {
            return response()->json(['code' => 401, 'msg' => "请先登录"]);
        } elseif (is_string($check)) {
            return response()->json(['code' => 402, 'msg' => $check]);
        }

        return $next($request);
    }
}
