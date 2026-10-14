<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuliner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KulinerController extends Controller
{
    public function index(): View
    {
        $kuliners = Kuliner::with('user')->latest()->paginate(10);

        return view('admin.kuliner.index', compact('kuliners'));
    }

    public function create(): View
    {
        return view('admin.kuliner.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        Kuliner::create($validated + ['id_user' => $request->user()->id]);

        return redirect()
            ->route('admin.kuliner.index')
            ->with('success', 'Kuliner berhasil ditambahkan.');
    }

    public function edit(Kuliner $kuliner): View
    {
        return view('admin.kuliner.form', compact('kuliner'));
    }

    public function update(Request $request, Kuliner $kuliner): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $validated['id_user'] = $request->user()->id;

        $kuliner->update($validated);

        return redirect()
            ->route('admin.kuliner.index')
            ->with('success', 'Kuliner berhasil diperbarui.');
    }

    public function destroy(Kuliner $kuliner): RedirectResponse
    {
        $kuliner->delete();

        return redirect()
            ->route('admin.kuliner.index')
            ->with('success', 'Kuliner berhasil dihapus.');
    }

    protected function rules(): array
    {
        return [
            'nama_kuliner' => ['required', 'string', 'max:255'],
            'kategori_suhu' => ['nullable', 'in:'.Kuliner::SUHU_MENGHANGATKAN.','.Kuliner::SUHU_MENYEGARKAN],
            'harga' => ['nullable', 'integer', 'min:0'],
            'jam_operasional' => ['nullable', 'string', 'max:100'],
            'kontak' => ['nullable', 'string', 'max:100'],
            'alamat_lengkap' => ['nullable', 'string'],
            'deskripsi' => ['required', 'string'],
            'foto' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:'.Kuliner::STATUS_AKTIF.','.Kuliner::STATUS_NONAKTIF],
        ];
    }
}
