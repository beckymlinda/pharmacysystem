<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Carbon\Carbon;

class ExpenseController extends Controller
{
   public function index(Request $request)
{
    $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
    $end   = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

    $expenses = Expense::whereBetween('date', [$start, $end])->latest()->get();
    $total = $expenses->sum('amount');

    return view('expenses.index', compact('expenses', 'total', 'start', 'end'));
}

    public function create()
    {
        return view('expenses.create');
    }

 public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'category' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'description' => 'nullable|string',
    ]);

    $expense = Expense::create([
        'date' => $request->date,
        'category' => $request->category,
        'amount' => $request->amount,
        'description' => $request->description,
        'user_id' => auth()->id(),
    ]);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Expense recorded.',
            'html' => view('expenses.index', [
                'expenses' => Expense::with('user')->latest()->get()
            ])->render()
        ]);
    }

    return redirect()->route('expenses.index')->with('success', 'Expense recorded.');
}


}
