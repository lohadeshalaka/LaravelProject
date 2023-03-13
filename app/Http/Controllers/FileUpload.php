<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Models\File;
class FileUpload extends Controller
{
  public function createForm(){
    return view('file-upload');
  }
  public function fileUpload(Request $req){
	// Define allowed file types
        $req->validate([
        'file' => 'required|mimes:pdf,txt,doc,docx,xml,csv|max:10000'
        ]);
		// Get the file from the request
        $fileModel = new File;
        if($req->file()) {
			// Generate a unique file name
            $fileName = time().'_'.$req->file->getClientOriginalName();
			// Move the uploaded file to the storage directory
            $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');
			// Save file details in database
            $fileModel->file_name = time().'_'.$req->file->getClientOriginalName();
            $fileModel->file_path = '/storage/' . $filePath;
			$fileModel->s3_object_id = 1;
            $fileModel->save();
			 // Send email notification with file details
			   Mail::send([], [], function ($message) use ($fileName) {
            $message->to('recipient@example.com')
                    ->subject('New file upload')
                    ->attach(public_path('uploads/' . $fileName));
        });

        return back()->with('success', 'File has been uploaded successfully and sent to recipient.');
        }
   }
}