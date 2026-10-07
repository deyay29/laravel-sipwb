<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::first();
        if (!$contact) {
            $contact = Contact::create(['address' => 'Balai Kota Bandung']);
        }
        return view('admin.contact.form', compact('contact'));
    }

    public function create() { }
    
    public function store(Request $request) { }
    
    public function show(string $id) { }
    
    public function edit(string $id) { }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'address' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'map_embed' => 'nullable|string',
            'facebook' => 'nullable|string',
            'instagram' => 'nullable|string',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->update($validated);
        return redirect()->route('admin.contact.index')->with('success', 'Contact updated successfully.');
    }

    public function destroy(string $id) { }
}
