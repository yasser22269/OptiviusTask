<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;
class VerificationController extends Controller
{
    public function Verify(Request $request)
    {
         try {
            DB::beginTransaction();
        $Profile = Profile::where("api_token",'=',$request->token)->first();
        $Profile->email_verified_at = now();
        $Profile->save();

       DB::commit();
        return responsejson(
            false,
            "تم تاكيد حسابك بنجاح",
            [
                 "api_token" => $Profile->api_token ,
                 "Profile" => $Profile,
            ]
        );
          
       } catch (\Exception $ex) {
           DB::rollback();
           return responsejson( true,"يوجد مشكلة حاول مره اخرة", [] );
       }
     
    }
}
