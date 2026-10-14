<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sejarah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SejarahController extends Controller
{
    public function index(): View
    {
        $sejarahs = Sejarah::with('user')->withCount('babs')->latest()->paginate(10);

        return view('admin.sejarah.index', compact('sejarahs'));
    }

    public function create(): View
    {
        return view('admin.sejarah.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        Sejarah::create($validated + ['id_user' => $request->user()->id]);

        return redirect()
            ->route('admin.sejarah.index')
            ->with('success', 'Topik sejarah berhasil ditambahkan.');
    }

    public function edit(Sejarah $sejarah): View
    {
        return view('admin.sejarah.form', compact('sejarah'));
    }

    public function update(Request $request, Sejarah $sejarah): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $validated['id_user'] = $request->user()->id;

        $sejarah->update($validated);

        return redirect()
            ->route('admin.sejarah.index')
            ->with('success', 'Topik sejarah berhasil diperbarui.');
    }

    public function destroy(Sejarah $sejarah): RedirectResponse
    {
        $sejarah->delete();

        return redirect()
            ->route('admin.sejarah.index')
            ->with('success', 'Topik sejarah berhasil dihapus.');
    }

    protected function rules(): array
    {
        return [
            'judul_topik' => ['required', 'string', 'max:255'],
            'ringkasan' => ['nullable', 'string'],
            'gambar_utama' => ['nullable', 'string', 'max:255'],
        ];
    }
}
