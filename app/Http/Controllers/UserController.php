<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AgentInfo;
use App\Models\Country;
use App\Models\Project;
use App\Models\MasterSetting;
use Bouncer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public int $roleId = 5; //middleman

    public function index(Request $request)
    {
        if (!Bouncer::can('user')) {
            return back()->with('error', __('message.return_message-access_restricted'));
        }

        $route_name = explode('.', Route::currentRouteName());
        $role_name = $route_name[1];
        $role = Bouncer::role()->where('name', $role_name)->first();
        if (!$role && $role_name <> 'staff') {
            return redirect()->route('home');
        }

        if ($role_name == 'admin') {
            // Only superadmin can open admin
            if (!Auth::user()->isA('systemadmin', 'superadmin')) {
                return back()->with('error', __('message.return_message-access_restricted'));
            }

            $users = User::where('role_id', $role->id)
                ->orderBy('id', 'desc')
                ->get();

        } elseif ($role_name == 'staff') {
            if (Auth::user()->isA('systemadmin', 'superadmin')) {
                $users = User::whereNotIn('role_id', [1, 2, 3, 4, 5])
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $users = User::where('upline', Auth::user()->id)
                    ->whereNotIn('role_id', [1, 2, 3, 4, 5])
                    ->orderBy('id', 'desc')
                    ->get();
            }
        } else {
            if (Auth::user()->isA('systemadmin', 'superadmin')) {
                $users = User::where('role_id', $role->id)
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $users = User::where([
                        ['upline', '=', Auth::user()->id],
                        ['role_id', '=', $role->id]
                    ])
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }

        return view('user.index')->with(compact('users', 'role_name'));
    }

    public function create(Request $request)
    {
        if (!Bouncer::can('user-create')) {
            return back()->with('error', __('message.return_message-access_restricted'));
        }

        $route_name = explode('-', Route::currentRouteName());
        $role = $route_name[1];
        $role = Bouncer::role()->where('name', $role)->first();
        if ($role && in_array($role->id, [3, 4, 5])) {
            $role_name = $role->name;
            $roles = Role::whereIn('id', [$role->id])->get();
        } else {
            $role_name = 'staff';
            $roles = Role::whereNotIn('id', [1, 2, 3, 4, 5])->get();
        }

        // $country = Session::get('country') ?? null;
        // if($country == null) $countries = Country::where('is_active', '1')->get();
        // else $countries = Country::where([['is_active', '=', '1'], ['country_code', '=', $country]])->get();


        return view('user.create')->with(compact('roles', 'role_name'));
    }

    public function store(Request $request)
    {
        if (!Bouncer::can('user-create')) {
            return back()->with('error', __('message.return_message-access_restricted'));
        }

        $request->validate([
            'username'  => 'required|unique:users,username'
        ]);
        $request->merge([
            'upline' => Auth::user()->id,
            'role_id' => $request->role,
            'password' =>Hash::make($request->password_input)
        ]);
        $user = User::create($request->all());
        $role = Bouncer::role()->where('id', $user->role_id)->first();
        $user->assign($role->name);

        return redirect()->route('user.edit',$user)->withSuccess('Data saved');
    }

    public function edit(User $user, Request $request)
    {
        if (!Bouncer::can('user-edit')) {
            return back()->with('error', __('message.return_message-access_restricted'));
        }

        if (!Auth::user()->isA('systemadmin', 'superadmin') && $user->upline <> Auth::user()->id) {
            return back()->with('error', __('message.return_message-access_restricted'));
        }

        if (in_array($user->role_id, [3, 4, 5])) {
            $role_name = $user->role_name;
            $roles = Role::whereIn('id', [$user->role_id])->get();
        } else {
            $role_name = 'staff';
            $roles = Role::whereNotIn('id', [1, 2, 3, 4, 5])->get();
        }

        $country = Session::get('country') ?? null;
        if($country != null) {
            $country_id = Country::where('country_code', $country)->first()->id;
            $projects = Project::with('country')->where('country_id', '=', $country_id)->get();
        }
        else $projects = Project::with('country')->get();

        $middlemans = User::where([
            ['role_id', '=', $this->roleId],
            ['upline', '=', Auth::user()->id]
        ])->get();

        $agentInfos = AgentInfo::where('user_id', $user->id)
                        ->when($country != null, function($query) use($country) {
                            $query->whereHas('country', function($q) use ($country) {
                                $q->where('country_code', '=', $country);
                            });
                        })
                        ->get()->toArray();

        $keep_time = MasterSetting::where('type','keep_time')->first();

        return view('user.create')->with(compact('projects', 'middlemans', 'user', 'keep_time', 'roles', 'agentInfos', 'role_name'));
    }

    public function update(Request $request, User $user)
    {
        if (!Bouncer::can('user-edit')) {
            return back()->with('error', __('message.return_message-access_restricted'));
        }

        if (!Auth::user()->isA('systemadmin', 'superadmin') && $user->upline <> Auth::user()->id) {
            return back()->with('error', __('message.return_message-access_restricted'));
        }

        $request->validate([
            'username'  => 'required|unique:users,username,' . $user->id,
        ]);

        if (isset($request->password)) {
            $request->merge(['password'=>Hash::make($request->password_input)]);
        }

        $user->update($request->all());

        // Agent
        // if($request->role == 2) {
        //     return redirect()->route('user.agent.edit', ['user' => $user])->withSuccess('Data saved');
        // }

        return redirect()->route('user.edit',$user)->withSuccess('Data saved');
    }

    public function destroy(User $user)
    {
        if (!Bouncer::can('user-delete')) {
            return back()->with('error', __('message.return_message-access_restricted'));
        }

        if (!Auth::user()->isA('systemadmin', 'superadmin') && $user->upline <> Auth::user()->id) {
            return back()->with('error', __('message.return_message-access_restricted'));
        }

        $user->forceDelete();

        return redirect()->back()->withSuccess('Data deleted');
    }

    public function toggleStatus(Request $request) {
        $user_id = $request->user_id;
        $checked = $request->checked;

        $user = User::find($user_id);
        if($checked) {
            $user->is_active = 1;
        } else {
            $user->is_active = 0;
        }
        $user->save();

        return response()->json(['success' => true, 'data' => null, 'message' => 'Update status successfully.'], 200);
    }

}
