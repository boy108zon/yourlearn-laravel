<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;  

class DataTableController extends Controller
{
    public function index(Request $request, $module)
    {
        $modelClass = "App\\Models\\" . ucfirst($module);

        // Check if the model exists
        if (!class_exists($modelClass)) {
            abort(404, "Model not found");
        }

        $columns = $request->get('columns', []);  // Get the columns to be shown
        if (empty($columns)) {
            abort(400, 'Columns parameter is required');
        }

        $data = $modelClass::all();  // Retrieve all data dynamically

        return view('datatable.index', compact('data', 'columns'));
    }
}
