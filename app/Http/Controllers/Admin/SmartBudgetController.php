<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmartBudgetPaket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SmartBudgetController extends Controller
{
    public function index(): View
    {
        $pakets = SmartBudgetPaket::with('user')->latest()->paginate(10);

        return view('admin.smart-budget.index', compact('pakets'));
    }

    public function create(): View
    {
        return view('admin.smart-budget.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('smart-budget', 'public');
        }

        if (isset($validated['rincian_json'])) {
            $validated['rincian_biaya'] = $this->parseRincian($validated['rincian_json']);
            unset($validated['rincian_json']);
        }

        SmartBudgetPaket::create($validated + ['id_user' => $request->user()->id]);

        return redirect()
            ->route('admin.smart-budget.index')
            ->with('success', 'Paket Smart Budget berhasil ditambahkan.');
    }

    public function edit(SmartBudgetPaket $smartBudget): View
    {
        return view('admin.smart-budget.form', ['paket' => $smartBudget]);
    }

    public function update(Request $request, SmartBudgetPaket $smartBudget): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('foto')) {
            if ($smartBudget->foto && Str::startsWith($smartBudget->foto, 'smart-budget/')) {
                Storage::disk('public')->delete($smartBudget->foto);
            }
            $validated['foto'] = $request->file('foto')->store('smart-budget', 'public');
        }

        if (isset($validated['rincian_json'])) {
            $validated['rincian_biaya'] = $this->parseRincian($validated['rincian_json']);
            unset($validated['rincian_json']);
        }

        $validated['id_user'] = $request->user()->id;

        $smartBudget->update($validated);

        return redirect()
            ->route('admin.smart-budget.index')
            ->with('success', 'Paket Smart Budget berhasil diperbarui.');
    }

    public function destroy(SmartBudgetPaket $smartBudget): RedirectResponse
    {
        if ($smartBudget->foto && Str::startsWith($smartBudget->foto, 'smart-budget/')) {
            Storage::disk('public')->delete($smartBudget->foto);
        }

        $smartBudget->delete();

        return redirect()
            ->route('admin.smart-budget.index')
            ->with('success', 'Paket Smart Budget berhasil dihapus.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate($this->rules());
    }

    protected function rules(): array
    {
        return [
            'nama_paket' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['required', 'string'],
            'total_estimasi' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'rincian_json' => ['nullable', 'string'],
        ];
    }

    protected function parseRincian(?string $json): array
    {
        if (! $json) {
            return [];
        }

        $decoded = json_decode($json, true);
        if (! is_array($decoded)) {
            return [];
        }

        $result = [];
        foreach ($decoded as $item) {
            $item = $item ?? [];
            $nama = trim((string) ($item['item'] ?? ($item['nama'] ?? '')));
            $biaya = (int) ($item['biaya'] ?? ($item['harga'] ?? 0));
            if ($nama === '') {
                continue;
            }
            $result[] = ['item' => $nama, 'biaya' => $biaya];
        }

        return $result;
    }
}
