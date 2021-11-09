<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use Illuminate\Http\Request;

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
        $errors = [];
        $success = $request->session()->get('success', false);

        if($request->isMethod('POST')) {
            $name = $request->input('name');
            $phone = $request->input('phone');
            $email = $request->input('email');
            $message = $request->input('message');

            if ($name === null) {
                $errors['name'] = 'Fill the "name" field';
            }

            if($phone === null && $email === null) {
                $errors['contacts'] = 'Fill in at least one of the fields';
            }

            if(strlen($phone) !== 11) {
                $errors['phone'] = 'Phone lenth must be 11 characters';
            }

            if ($message === null) {
                $errors['message'] = 'Fill the "message" field';
            }

            if (count($errors) > 0) {
                $request->flash();
            } else {
                $appeal = new Appeal();
                $appeal->name = $name;
                $appeal->phone = $phone;
                $appeal->email = $email;
                $appeal->message = $message;
                $appeal->save();

                $success = true;

                return redirect()
                    ->route('appeal')
                    ->with('success', $success);
            }

            
        }

        return view('appeal', ['errors' => $errors, 'success' => $success]);
    }
}
