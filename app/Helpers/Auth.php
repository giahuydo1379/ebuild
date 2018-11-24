<?php
namespace App\Helpers;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Auth
{
    public static function keyAuthGoogle2fa() {
        return '2fa:user';
    }
    public static function isAuthGoogle2fa() {
        $key = self::keyAuthGoogle2fa();

        return session($key);
    }
    public static function setAuthGoogle2fa($flag=1) {
        $key = self::keyAuthGoogle2fa();

        return session([$key => $flag]);
    }
    public static function user_name($value=false) {
        $key = '_username';

        if ($value===false) {
            return session($key);
        }

        return session([$key => $value]);
    }

    public static function keyCacheToken() {
        return 'token:'.self::user_name();
    }

    //---------user info--------------------

    public static function keyCacheUserInfo() {
        return 'userinfo:'.self::user_name();
    }

    public static function setUserInfo($info, $expires_at=false) {
        $minutes = ( $expires_at - time() ) / 60;

        cache([self::keyCacheUserInfo() => $info], $minutes);
    }

    public static function getUserInfo() {
        return \Auth::user();
    }

    //---------user id----------------------

    public static function keyCacheUserId() {
        return 'userid:'.self::user_name();
    }

    public static function setUserId($id, $expires_at=false) {
        $minutes = ( $expires_at - time() ) / 60;

        cache([self::keyCacheUserId() => $id], $minutes);
    }

    public static function getUserId() {
        return cache(self::keyCacheUserId());
    }

    //--------------------------------------

    public static function setToken($token, $expires_at=false) {
        $minutes = ( $expires_at - time() ) / 60;

        cache([self::keyCacheToken() => $token], $minutes);
    }
    public static function removeToken() {
        Cache::forget(self::keyCacheToken());
    }
    public static function getToken() {
        return cache(self::keyCacheToken());
    }

    public static function check() {
        return cache(self::keyCacheToken()) ? true : false;
    }

    public static function is_salesman() {
        $user = self::getUserInfo();

        return $user && $user->user_title_id==2;
    }

    public static function is_admin($user=false) {
        if (!$user) $user = self::getUserInfo();

        return $user && $user->id==1;
    }

    public static function forget_prefix($prefix_key)
    {
        Cache::flush();
        return;

        if ($prefix_key=="*") return;

        $count = 0;
        foreach (Cache::getMemory() as $cacheKey => $cacheValue)
        {
            if (strpos($cacheKey, 'mypackage') !== false)
            {
                Cache::forget($cacheKey);
                $count++;
            }
        }
        return $count;

        $redis = Cache::getRedis();
        $keys = $redis->keys($prefix_key);
        $count = 0;
        foreach ($keys as $key) {
            $redis->del($key);
            $count++;
        }
        return $count;
    }

    public static function keyGetPermissionsByUser($user_id) {
        return 'permissions:user:'.$user_id;
    }

    public static function forget_permissions() {
        return self::forget_prefix('permissions:user:*');
    }

    public static function keyGetRolesByUser($user_id) {
        return 'roles:user:'.$user_id;
    }

    public static function get_permissions($user=false) {
        if (!$user) $user = self::getUserInfo();

        $key = self::keyGetPermissionsByUser($user->id);

        $permissions = Cache::get($key);

        if ($permissions) return $permissions;

        $permissions = \App\Models\Permissions::getPermissionsByUser($user->id);

        Cache::forever($key, $permissions);

        return $permissions;
    }

    public static function get_roles($user=false) {
        if (!$user) $user = self::getUserInfo();

        $key = self::keyGetRolesByUser($user->id);

        $roles = Cache::get($key);

        if ($roles) return $roles;

        $roles = \App\Models\UserHasRole::select('role_id')->where('user_id', $user->id)->get()->pluck('role_id')->toArray();

        Cache::forever($key, $roles);

        return $roles;
    }

    public static function has_permission($route_name, $user=false, $permissions=false, $debug=false) {
        if (!$user) $user = self::getUserInfo();

        if (self::is_admin($user)) return true;

        $roles = self::get_roles($user);
        if (in_array(1, $roles)) {
            return true;
        }

        // thuc hien call tu api va cache lai
        if (!$permissions) $permissions = self::get_permissions($user);

        if (is_array($route_name)) {
            foreach ($route_name as $rn) {
               if (!array_key_exists($rn, $permissions) || $permissions[$rn]) {
                   return true;
               }
            }

            return false;
        }

        return !array_key_exists($route_name, $permissions) || $permissions[$route_name];
    }

    public static function get_first_permission($user=false, $permissions=false) {
        if (!$user) $user = self::getUserInfo();

        // thuc hien call tu api va cache lai
        if (!$permissions) $permissions = self::get_permissions($user);

        foreach ($permissions as $route_name => $flag) {
            if ($flag) {
                if (strpos($route_name, '.index')) {
                    return route($route_name);
                }
            }
        }

        return '/dashboard';
    }
}