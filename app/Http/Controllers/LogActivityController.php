<?php
namespace App\Http\Controllers;
use App\DataTables\LogActivityDataTable;
use App\Models\User;
class LogActivityController extends Controller
{
    public function index(LogActivityDataTable $dataTable)
    {
        $users = User::all();
        return $dataTable->render('log_activity.index',compact('users'));
    }
}
