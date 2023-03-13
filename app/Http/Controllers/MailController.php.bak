<?php

namespace App\Http\Controllers;

use File;
use Mail;
use App\Mail\Notification;
use Illuminate\Http\Request;

class MailController extends Controller
{
    /**
     * email send view.
     *
     * @return $this
     */
    public function mailView()
    {
        return view('mailView');
    }

    /**
     * save file and send mail.
     *
     * @return $this
     */
    public function mailSend(Request $request)
    {
        $input = $request->validate([
            'email' => 'required',
            'attachment' => 'required',
        ]);

        $path = public_path('uploads');
        $attachment = $request->file('attachment');

        $name = time().'.'.$attachment->getClientOriginalExtension();;

        // create folder
        if(!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $attachment->move($path, $name);

        $filename = $path.'/'.$name;

        try {
            Mail::to($input['email'])->send(new Notification($filename));
        } catch (\Exception $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Mail sent successfully.');
    }
}