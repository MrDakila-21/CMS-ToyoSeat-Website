<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\InquiryMail;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],  // NEW
            'company_name' => ['nullable', 'string', 'max:255'],   // NEW
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:2048'],
        ]);

        $attachment = $request->file('attachment');
        if ($attachment) {
            $attachmentPath = $attachment->store('inquiries/attachments', 'public');
            $validated['attachment_path'] = $attachmentPath;
            $validated['attachment_original_name'] = $attachment->getClientOriginalName();
            $validated['attachment_mime'] = $attachment->getMimeType();
        }

        // Save to database
        $inquiry = Inquiry::create($validated);

        // Send email with logging and error handling
        try {
            Mail::to('interntoyo@gmail.com')->send(new InquiryMail($validated));
            Log::info('Inquiry email sent', ['inquiry_id' => $inquiry->id, 'email' => $validated['email']]);
        } catch (\Exception $e) {
            Log::error('Failed to send inquiry email', ['error' => $e->getMessage(), 'inquiry_id' => $inquiry->id]);
            return back()->with('success', 'Message saved but failed to send email.');
        }

        return back()->with('success', 'Message sent successfully!');
    }
}