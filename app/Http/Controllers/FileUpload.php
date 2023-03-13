<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Notifications\EmailNotification;
use App\Models\File;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Invite;
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
			$fileUrl = asset($filePath);
			$fileSize = round($fileModel->size/1024, 2).' KB';
			$fileType = pathinfo($filePath, PATHINFO_EXTENSION);
			//Mail::to('notify@test.test')->send(new FileUploadNotification($fileModel->name, $fileUrl, $fileSize, $fileType));
			//return response()->json(['status' => 'success', 'file_path' => $filePath, 'file_id' => $fileDetails->id]);
			return back()
            ->with('success','File has been uploaded.')
            ->with('file', $fileName);
        }
   }
}