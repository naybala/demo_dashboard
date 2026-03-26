<?php

namespace BasicDashboard\Web\Users\Controllers;

use App\Enums\Users\UserType;
use App\Enums\Users\Gender;
use App\Http\Controllers\Controller;
use BasicDashboard\Foundations\Domain\Users\User;
use BasicDashboard\Web\Users\Resources\UserResource;
use BasicDashboard\Web\Users\Services\UserService;
use BasicDashboard\Web\Users\Validation\StoreUserRequest;
use BasicDashboard\Web\Users\Validation\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->getPaginatedUsers($request->all());

        return Inertia::render('Users/Index', [
            'data'    => UserResource::collection($users)->resolve(),
            'meta'    => [
                'total'        => $users->total(),
                'per_page'     => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'from'         => $users->firstItem(),
                'to'           => $users->lastItem(),
                'links'        => $users->linkCollection()->toArray(),
            ],
            'filters' => $request->only(['keyword']),
        ]);
    }

    public function show(string $id)
    {
        $decodedId = customDecoder($id);
        $user = User::with(['roles', 'guardians'])->findOrFail($decodedId);  
        return Inertia::render('Users/Show', [
            'user' => (new UserResource($user))->resolve(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/CreateEdit', [
            'types'   => UserType::options(),
            'genders' => Gender::options(),
            'roles'   => Role::where('guard_name', 'web')->get(['id', 'name']),
            'classes' => DB::table('classes')->select('id', 'name')->whereNull('deleted_at')->get(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->createUser($request->validated());

        return redirect()->route('users.index')->with('success', __('user.user_created'));
    }

    public function edit(string $id)
    {
        $decodedId = customDecoder($id);
        $user = User::with([ 'roles', 'guardians'])->findOrFail($decodedId);

        return Inertia::render('Users/CreateEdit', [
            'user'    => (new UserResource($user))->resolve(),
            'types'   => UserType::options(),
            'genders' => Gender::options(),
            'roles'   => Role::where('guard_name', 'web')->get(['id', 'name']),
            'classes' => DB::table('classes')->select('id', 'name')->whereNull('deleted_at')->get(),
        ]);
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        $decodedId = customDecoder($id);
        $user      = User::findOrFail($decodedId);
        $this->userService->updateUser($user, $request->validated());

        return redirect()->route('users.index')->with('success', __('user.user_updated'));
    }

    public function destroy(string $id)
    {
        $decodedId = customDecoder($id);
        $user      = User::findOrFail($decodedId);
        $this->userService->deleteUser($user);

        return redirect()->route('users.index')->with('success', __('user.user_deleted'));
    }
}
