<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail as FacadesMail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $Profile = Profile::create($request->all());
            $Profile->api_token = Str::random(60);
            $Profile->save();

            Mail::to($Profile->email)->send(new VerifyEmail($Profile->api_token));
            DB::commit();
            if (Mail::failures()) {
                return responsejson(true, "حاول مره اخرة", []);
            } else {
                return responsejson(
                    false,
                    "تم ارسال رساله تاكيد الحساب على اميلك الخاص",
                    [
                        // "api_token" => $Profile->api_token ,
                        // "Profile" => $Profile,
                    ]
                );
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return responsejson(true, "يوجد مشكلة حاول مره اخرة", []);
        }
    }
    public function login(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->username == null || $request->password == null) {
                return responseJson(0, 'هذه البيانات غير مطابقه');
            }
            if (is_numeric($request->username)) {
                return   $this->checkLogin('phone', $request);
            } elseif (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
                return $this->checkLogin('email', $request);
            } else
                return responseJson(1, 'هذه البيانات غير مطابقه');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return responsejson(true, "يوجد مشكلة حاول مره اخرة", []);
        }
    }

    public function checkLogin($userName, $request)
    {

        $Profile = Profile::where($userName, $request->username)->first();
        if ($Profile == Null) {
            return responsejson(true, "كلمة المرور او الايميل غير صحيح", []);
        }
        if ($Profile->email_verified_at == Null) {
            return responsejson(true, "يرجى تاكيد حسابك حتى تستطيع الدخول", []);
        }

        if (Hash::check($request->password, $Profile->password)) {
            return responsejson(false, 'تم التسجيل بنجاح', [
                'api_token' => $Profile->api_token,
                'Profile' => $Profile->name,
            ]);
        } else
            return responseJson(1, 'هذه البيانات غير مطابقه');
    }
}
