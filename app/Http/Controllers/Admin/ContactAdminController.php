<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages   = $query->latest()->paginate(10)->withQueryString();
        $newCount   = Contact::where('status', 'new')->count();

        return view('admin.messages.index', compact('messages', 'newCount'));
    }

    public function updateStatus(Request $request, Contact $message)
    {
        $request->validate([
            'status' => 'required|in:new,read,replied',
        ]);

        $message->update(['status' => $request->status]);

        return back()->with('success', 'ស្ថានភាពសារត្រូវបានធ្វើបច្ចុប្បន្នភាព!');
    }

    public function destroy(Contact $message)
    {
        $message->delete();

        return back()->with('success', 'សារត្រូវបានលុប!');
    }
}
