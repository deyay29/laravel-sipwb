<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Profil kontak memakai satu baris singleton, jadi modul resource ini
 * hanya menyediakan form edit.
 */
class ContactController extends Controller
{
    public function index(): View
    {
        $contact = Contact::instance();

        return view('admin.contact.form', compact('contact'));
    }

    public function edit(Contact $contact): View
    {
        return view('admin.contact.form', compact('contact'));
    }

    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $validated = $request->validate([
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:100'],
            'map_embed' => ['nullable', 'string'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
        ]);

        $contact->update($validated);

        return redirect()
            ->route('admin.contact.index')
            ->with('success', 'Data kontak berhasil diperbarui.');
    }
}
