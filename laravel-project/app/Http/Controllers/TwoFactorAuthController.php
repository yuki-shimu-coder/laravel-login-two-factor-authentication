<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TwoFactorAuthPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TwoFactorAuthController extends Controller
{
    // ログインフォーム
    public function login_form()
    {
        return view('two_factor_auth.login_form');
    }

    // １段階目の認証
    public function first_auth(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::validate($credentials)) {

            /** ランダムパスワードを格納 */
            $random_password = '';

            for ($i = 0; $i < 4; $i++) {
                $random_password .= strval(rand(0, 9));
            }

            $user = User::where('email', $request->email)->first();
            $user->two_factor_token = $random_password;            // 4桁のランダムな数字
            $user->two_factor_token_expiration = now()->addMinutes(10);  // 10分間だけ有効
            $user->save();

            // メール送信
            Mail::to($user)->send(new TwoFactorAuthPassword($random_password));

            return [
                'result' => true,
                'user_id' => $user->id
            ];
        }

        return ['result' => false];
    }

    public function second_auth(Request $request)
    {  // ２段階目の認証

        $result = false;

        if ($request->filled('two_factor_token', 'user_id')) {

            $user = User::find($request->user_id);
            $expiration = new Carbon($user->two_factor_token_expiration);

            if ($user->two_factor_token === $request->two_factor_token && $expiration > now()) {

                $user->two_factor_token = null;
                $user->two_factor_token_expiration = null;
                $user->save();

                Auth::login($user);    // 自動ログイン
                $result = true;
            }
        }

        return ['result' => $result];
    }
}
