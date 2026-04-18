<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Services\HolidaySyncService;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $data = Holiday::orderBy('tanggal', 'desc')->paginate(10);
        $defaultFromYear = (int) now()->format('Y');
        $defaultToYear = $defaultFromYear + 4;
        return view('master.holiday.index', compact('data', 'defaultFromYear', 'defaultToYear'));
    }

    public function create()
    {
        return view('master.holiday.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kode_libur' => 'required|string|max:20|unique:holidays,kode_libur',
            'keterangan' => 'required|string|max:255',
            'is_tetap' => 'nullable|boolean',
            'is_hari_raya' => 'nullable|boolean',
        ]);
        $validated['is_tetap'] = (bool)($request->input('is_tetap', false));
        $validated['is_hari_raya'] = (bool)($request->input('is_hari_raya', false));

        Holiday::create($validated);
        return redirect()->route('holiday.index')->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Holiday::findOrFail($id);
        return view('master.holiday.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Holiday::findOrFail($id);
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kode_libur' => 'required|string|max:20|unique:holidays,kode_libur,' . $item->id,
            'keterangan' => 'required|string|max:255',
            'is_tetap' => 'nullable|boolean',
            'is_hari_raya' => 'nullable|boolean',
        ]);
        $validated['is_tetap'] = (bool)($request->input('is_tetap', false));
        $validated['is_hari_raya'] = (bool)($request->input('is_hari_raya', false));

        $item->update($validated);
        return redirect()->route('holiday.index')->with('success', 'Hari libur berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Holiday::whereKey($id)->delete();
        return redirect()->route('holiday.index')->with('success', 'Hari libur berhasil dihapus.');
    }

    public function sync(Request $request, HolidaySyncService $holidaySyncService)
    {
        $validated = $request->validate([
            'from_year' => 'required|integer|min:2026|max:2100',
            'to_year' => 'required|integer|min:2026|max:2100|gte:from_year',
        ]);

        $summary = $holidaySyncService->syncRange(
            (int) $validated['from_year'],
            (int) $validated['to_year']
        );

        return redirect()->route('holiday.index')->with(
            'success',
            'Sinkronisasi hari libur berhasil. ' .
            'Tahun: ' . implode(', ', $summary['years']) . '. ' .
            'Baru: ' . $summary['created'] . ', diperbarui: ' . $summary['updated'] . '.'
        );
    }
}
