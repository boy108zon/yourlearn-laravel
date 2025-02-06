<?php
namespace App\Http\Controllers;
 
use App\DataTables\UsersDataTable;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Models\Role;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Storage;
use App\Services\UserActionService;

class UsersController extends Controller
{
    protected $userActionService;
    public function __construct(UserActionService $userActionService)
    {
        $this->middleware('auth');
        $this->userActionService = $userActionService;
    }

    public function index(UsersDataTable $dataTable)
    {
       $user = auth()->user();
       $actions = $this->userActionService->getActions($user);
       return $dataTable->render('users.index',compact('actions')); 
    }

    public function store(CreateUserRequest $request)
    {
        $validated = $request->validated();

        $fullName = $validated['first_name'] . ' ' . $validated['last_name'];

       
        $validated['password'] = bcrypt($request->password);
        $validated['name'] = $fullName;

        $user = User::create($validated);

        if ($request->has('roles')) {
            $user->roles()->attach($request->roles);
        }

        if ($request->hasFile('file')) {
            $path = $this->uploadProfilePhoto($request->file('file'), $user->id);
            $user->profile_picture = $path;
            $user->save();
        }

        return redirect()->route('users.index')->with('swal', [
            'message' => 'profile created and saved',
            'type' => 'success',
        ]);
    }

    public function edit(User $user)
    {
       $countries = Country::where('status', 1)->get(); 
       $states = State::get(); 
       $roles = Role::all();
       return view('users.edit', compact('user','countries','states','roles'));
    }

    public function create(User $user)
    {
        $countries = Country::where('status', 1)->get(); 
        $states = State::get(); 
        $roles = Role::all();
        return view('users.create', compact('user','countries','states','roles'));
       
    }

    public function uploadProfilePhoto($file, $userId) 
    {
        
        $user = User::find($userId);
        $currentPhoto = $user->profile_picture;

        if ($currentPhoto) {
            $oldFilePath = 'profile_photos' . $currentPhoto;
            $fullPath = Storage::disk('public')->path($oldFilePath);
            
            if (Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->delete($oldFilePath);
            }
        }

        $timestamp = time(); 
        $fileExtension = $file->getClientOriginalExtension(); 
        $fileName = "{$userId}_{$timestamp}_profile_photo.{$fileExtension}";

        $path = $file->storeAs('public/profile_photos', $fileName);

        $user->profile_picture = $fileName;
        $user->save();
        return Storage::url($path);
    }


    public function update(UpdateUserRequest $request, $id)
    {
        
        $user = User::findOrFail($id);

        $validated = $request->validated();

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }
         
        if ($request->filled('first_name') && $request->filled('last_name')) {
            $validated['name'] = ucfirst($request->first_name).' '.$request->last_name;
        }

        $user->update($validated);

        if ($request->has('roles')) {
           $user->roles()->sync($request->roles);
        }

        if ($request->hasFile('file')) {
            $path = $this->uploadProfilePhoto($request->file('file'),$id);
            $user->profile_picture = $path;
            $user->save();
        }

        return redirect()->route('users.index')->with('swal', [
            'message' => 'profile updated and changes saved.',
            'type' => 'success',
        ]);
    }


    public function resetPassword(User $user)
    {
        return view('users.reset-password', compact('user'));
    }

    public function resetPasswordUpdate(Request $request, User $user)
    {
        $request->validate([
            'current_password' => 'required', 
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
           return redirect()->route('users.index')->with('swal', [
                'message' => 'Current password is incorrect.',
                'type' => 'error'
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('users.index')->with('swal', [
            'message' => 'Password reset successfully!',
            'type' => 'success'
        ]);

    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('swal', [
            'message' => 'Removed successfully.',
            'type' => 'success'
        ]);
    }
    
    public function getStates($countryId)
    {
        return State::where('country_id', $countryId)->get();
    }
}