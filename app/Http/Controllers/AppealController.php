<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppealPostRequest;
use App\Models\Appeal;
use Illuminate\Http\Request;
use App\Sanitizers\PhoneSanitizer;

class AppealController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $grat_message = $request->session()->get('grat_message');
            if ($grat_message)
                $request->session()->put('grat_message', false);
        if($request->isMethod('POST')) {

            

            $validate = $request->validate(AppealPostRequest::rules());

            $appeal = new Appeal();
            $appeal->name = $validate['name'];
            $appeal->surname = $validate['surname'];
            $appeal->patronymic = $validate['patronymic'];
            $appeal->age = $validate['age'];
            $appeal->gender = $validate['gender'];
            $appeal->phone = PhoneSanitizer::num_sanitize($validate['phone']);
            $appeal->email = $validate['email'];
            $appeal->message = $validate['message'];
            $appeal->save();
            $request->session()->put("is_appeal_send", true);

            return redirect()
                ->route('appeal');            
        }

        //return view('appeal');
        return view('appeal', ['grat_message' => $grat_message]);
    }
}
