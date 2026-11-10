<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ResultRegistration;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        return view('public.exam.index', [
            'exams' => Exam::where('is_active', true)->orderByDesc('exam_date')->get(),
        ]);
    }

    public function register(Exam $exam)
    {
        abort_unless($exam->is_active, 404);
        return view('public.exam.register', compact('exam'));
    }

    public function submit(Request $request, Exam $exam)
    {
        abort_unless($exam->is_active, 404);

        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'mobile'  => 'required|string|max:20',
            'email'   => 'required|email|max:150',
            'roll_no' => 'required|string|max:50',
            'zone'    => 'required|string|max:100',
        ]);

        ResultRegistration::create([
            ...$data,
            'exam_id'      => $exam->id,
            'submitted_at' => now(),
        ]);

        return redirect()->route('exams.register', $exam)
            ->with('success', 'Registered successfully. We will email your result when it is announced.');
    }
}
