<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class IndonesiaController extends Controller
{
    public function provinces()
    {
        return response()->json(
            Province::orderBy('name')->get(['id', 'name'])
        );
    }

    public function regencies(string $provinceId)
    {
        return response()->json(
            Regency::where('province_id', $provinceId)
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }

    public function districts(string $regencyId)
    {
        return response()->json(
            District::where('regency_id', $regencyId)
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }

    public function villages(string $districtId)
    {
        return response()->json(
            Village::where('district_id', $districtId)
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }
}

