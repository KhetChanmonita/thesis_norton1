<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'full_name'    => 'required|string|max:100',
            'phone'        => 'required|string|max:20',
            'email'        => 'nullable|email',
            'inquiry_type' => 'required|in:import,export,price,partnership,other',
            'message'      => 'required|string|min:10',
        ], [
            'full_name.required'    => 'សូមបញ្ចូលឈ្មោះពេញ។',
            'phone.required'        => 'សូមបញ្ចូលលេខទូរស័ព្ទ។',
            'inquiry_type.required' => 'សូមជ្រើសរើសប្រភេទសំណួរ។',
            'message.required'      => 'សូមបញ្ចូលសារ។',
            'message.min'           => 'សារត្រូវតែមានយ៉ាងហោចណាស់ ១០ តួអក្សរ។',
        ]);

        Contact::create([
            'full_name'    => $request->full_name,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'company_name' => $request->company_name,
            'inquiry_type' => $request->inquiry_type,
            'message'      => $request->message,
            'status'       => 'new',
        ]);

        return redirect()->route('home')
            ->with('contact_success', 'សូមអរគុណ! សំណួររបស់អ្នកត្រូវបានផ្ញើដោយជោគជ័យ។ យើងនឹងទាក់ទងអ្នកក្នុងពេលឆាប់ៗ។');
    }
}
