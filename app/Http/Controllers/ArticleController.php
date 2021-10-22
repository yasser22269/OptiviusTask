<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticlerRequest;
use App\Models\Article ;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{


    public function store(ArticlerRequest $request)
    {
       // return $request;
       try {
             $Profile = Profile::where('api_token',$request->api_token)->first();
             DB::beginTransaction();

            //validateJson
           if($this->validateJson($request) == true){
            return responsejson(true, "يرجى ملئ جميع اللغات", []);
           }

             $Article = new Article;
             $Article->title = $request->title;
             $Article->content = $request->content;
             $Article->profile_id =$Profile->id;
             $Article->save();

             DB::commit();
                 return responsejson(
                    false,
                    "تم الحفظ بنجاح",
                    [
                        "Article" => $Article,
                    ]
                );
        } catch (\Exception $ex) {
            DB::rollback();
            return responsejson(true, "يوجد مشكلة حاول مره اخرة", []);
        }
    }


    public function update(ArticlerRequest $request,$id)
    {
       try {
            DB::beginTransaction();
            $Article = Article::find($id);
            if (!$Article)
            return responsejson(true, "لا يوجد مقال ", []);
            
            //validateJson
           if($this->validateJson($request) == true){
            return responsejson(true, "يرجى ملئ جميع اللغات", []);
           }

            $Article->update($request->except('api_token'));
            DB::commit();
                return responsejson(
                    false,
                    "تم التحديث بنجاح",
                    [
                        "Article" =>  $Article,
                    ]
                );
        } catch (\Exception $ex) {
            DB::rollback();
            return responsejson(true, "يوجد مشكلة حاول مره اخرة", []);
        }
    }

    public function validateJson($request){
        $langArrays =  Config::get('app.languages');
        if( strpos($request->content, ',') == true && strpos($request->title, ',') == true){
            $content =  json_decode($request->content, TRUE);
            $title =  json_decode($request->title, TRUE);
            foreach($langArrays as $langArray){
              if(! isset($title[ $langArray])  && !isset($content[ $langArray]))
              {
                  return true;
              }
            }
          }else
              return true;
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $Article = Article::find($id);
            if (!$Article)
            return responsejson(true, "لا يوجد مقال ", []);

            $Article->delete();
            DB::commit();
                return responsejson(
                    false,
                    "تم الحذف بنجاح",
                    [
                        "Article" =>  $Article,
                    ]
                );
        } catch (\Exception $ex) {
            DB::rollback();
            return responsejson(true, "يوجد مشكلة حاول مره اخرة", []);
        }
    }
}
