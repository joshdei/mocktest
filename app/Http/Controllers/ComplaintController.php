<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\Complaint;
use App\Mail\NewComplaintAdminMail;
use App\Models\AdminMailModel;
use App\Mail\ComplaintReceivedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ComplaintController extends Controller
{


    public function compliant(Request $request)
    {
        
  
    
        // Validate the request
        $validator = Validator::make($request->all(), [
            'topic' => 'required|in:order,request,general,submit,partnership',
            'message' => 'required|string|min:10|max:5000',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            // Store in database
            $complaint = Complaint::create([
                'name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'email' => Auth::user()->email,
                'topic' => $request->topic,
                'message' => $request->message,
                'is_read' => false,
                'is_replied' => false,
            ]);
          
            // Send confirmation email to user
           Mail::to(Auth::user()->email)->send(new ComplaintReceivedMail($complaint));

            $adminEmail = AdminMailModel::first()->admin_mail ?? null;
            // // Optional: Send notification to admin
            Mail::to($adminEmail)->send(new NewComplaintAdminMail($complaint));
            Mail::to("joshuadeinne@gmail.com")->send(new NewComplaintAdminMail($complaint));
            
            return back()->with('success', 'Your message has been sent successfully! We will get back to you soon.');

        } catch (\Exception $e) {
            Log::error('Complaint submission failed: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Sorry, something went wrong. Please try again later.');
        }
    }

}