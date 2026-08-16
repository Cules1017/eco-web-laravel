<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameConfig;
use App\Models\QuizQuestion;
use App\Models\Voucher;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $configs = [
            'game_enabled' => GameConfig::get('game_enabled', '0'),
            'daily_questions' => GameConfig::get('daily_questions', 5),
            'streak_required' => GameConfig::get('streak_required', 3),
            'win_probability' => GameConfig::get('win_probability', 30),
            'game_voucher_id' => GameConfig::get('game_voucher_id', ''),
        ];
        $vouchers = Voucher::where('is_active', true)->get();
        return view('admin.game.index', compact('configs', 'vouchers'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'daily_questions' => 'required|integer|min:1',
            'streak_required' => 'required|integer|min:1',
            'win_probability' => 'required|integer|min:0|max:100',
            'game_voucher_id' => 'nullable|exists:vouchers,id',
        ]);

        GameConfig::set('game_enabled', $request->has('game_enabled') ? '1' : '0');
        foreach ($validated as $key => $value) {
            GameConfig::set($key, $value);
        }

        return redirect()->route('admin.game.index')->with('success', 'Cập nhật cấu hình game thành công.');
    }

    public function questions()
    {
        $questions = QuizQuestion::latest()->paginate(10);
        return view('admin.game.questions', compact('questions'));
    }

    public function storeQuestion(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'is_correct_true' => 'required|boolean',
            'explanation' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        QuizQuestion::create($validated);

        return redirect()->route('admin.game.questions')->with('success', 'Thêm câu hỏi thành công.');
    }

    public function updateQuestion(Request $request, QuizQuestion $question)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'is_correct_true' => 'required|boolean',
            'explanation' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $question->update($validated);

        return redirect()->route('admin.game.questions')->with('success', 'Cập nhật câu hỏi thành công.');
    }

    public function destroyQuestion(QuizQuestion $question)
    {
        $question->delete();
        return redirect()->route('admin.game.questions')->with('success', 'Xóa câu hỏi thành công.');
    }
}
