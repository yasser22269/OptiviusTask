<?php

namespace App\Http\Controllers;

use App\Models\Profile ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ProfileController extends Controller
{
    public function show(Request $request,$id)
    {
        $langArrays =  Config::get('app.languages');
        if( ! in_array($request->lang,$langArrays) || !isset($request->lang,$os) ){
            $request->lang = app()->getLocale();
        }

        $profile =Profile::with('article')->find($id);
        if (!$profile)
        return responsejson(true, "لا يوجد يوزر هكذه ", []);

        $name =  json_decode($profile->name, TRUE);
        $profile->name =  $name[$request->lang];

        $this->validateJson($profile->article,$request->lang);

        if (!$profile)
        return responsejson(true, "لا يوجد شخص هكذا ", []);

        return responsejson(
            false,
            "تم الدخول بنجاح",
            [
                $profile,
            ]
        );
    }


    public function validateJson($mydata ,$lang){
        foreach($mydata as $data){
            $content =  json_decode($data->content, TRUE);
            $title =  json_decode($data->title, TRUE);
            $data->content =  $content[$lang];
            $data->title =  $title[$lang];
         }
    }
}
