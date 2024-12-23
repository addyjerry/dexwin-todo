namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    // List todos with filtering, sorting, and searching
    public function index(Request $request)
    {
        $query = Todo::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('details', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('sort_by')) {
            $query->orderBy($request->sort_by, $request->get('order', 'asc'));
        }

        return response()->json($query->get());
    }

    // Create a new todo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'nullable|string',
            'status' => 'nullable|in:not_started,in_progress,completed',
        ]);

        $todo = Todo::create($validated);

        return response()->json($todo, 201);
    }

    // Update a todo
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'status' => 'nullable|in:not_started,in_progress,completed',
        ]);

        $todo->update($validated);

        return response()->json($todo);
    }

    // Delete a todo
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json(['message' => 'Todo deleted']);
    }
}
