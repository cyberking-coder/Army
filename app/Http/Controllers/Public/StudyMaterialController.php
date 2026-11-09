<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\StudyMaterial;

class StudyMaterialController extends Controller
{
    public function index()
    {
        return view('public.study-material.index', [
            'items' => StudyMaterial::where('is_active', true)->latest()->paginate(20),
        ]);
    }
}
