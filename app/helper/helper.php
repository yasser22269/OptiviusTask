<?php
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
 function responsejson($status =false, $mas ="تم بنجاح",$data =null){
    $response =[
        'errors'=>  $status ,
        'mas'=> $mas,
        'results'=> $data
    ];
    return response()->json($response);
 }


