<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20)->withQueryString();

        return view('dashboards.super_admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Define all roles available in registration
        $roles = [
            'super_admin' => 'Super Admin',
            'shree_sangh' => 'Shree Sangh',
            'yuva_sangh' => 'Yuva Sangh',
            'spf' => 'SPF',
            'mahila_samiti' => 'Mahila Samiti',
            'sahitya' => 'Shramnopasak (Sahitya)',
            'sahitya_publication' => 'Sahitya Publication',
            'app_user' => 'App User / IT Cell'
        ];

        return view('dashboards.super_admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,shree_sangh,yuva_sangh,spf,mahila_samiti,sahitya,sahitya_publication,app_user'
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);

            return redirect()->route('dashboard.users.index')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        // Prevent deleting the active logged-in super admin
        if (auth()->id() == $id) {
            return redirect()->route('dashboard.users.index')->with('error', 'You cannot delete yourself!');
        }

        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('dashboard.users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.users.index')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
