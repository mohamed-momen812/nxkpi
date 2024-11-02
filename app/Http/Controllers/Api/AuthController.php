<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\Tenant;
use App\Services\TenantService;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\SubscriptionTrait;
use Illuminate\Support\Facades\Hash;
use Rennokki\Plans\Models\PlanModel;

class AuthController extends Controller
{
    use ApiTrait, SubscriptionTrait;

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request , TenantService $tenantService) {

        $userData = array_merge( $request->validated(), ['password' => bcrypt($request->password) ] );
        $user = User::create($userData);

        $company = Company::create([
            "user_id" => $user->id ,
            "support_email" => $user->email,
            "country" => "United State",
            "site_url" => $user->company_domain . "." . config('tenancy.custom_domain') ,
        ]);

        $subscription = $this->subscribeToPlan($company, PlanModel::find($request->plan_id), $request->plan_type);

        $user->assignRole( "Owner" );

        $tenant = Tenant::create(['user_id' => $user->id ]);

        $tenant->domains()->create(['domain' => $user->company_domain . "." . config('tenancy.custom_domain')]);

        $tenantService->intiateTenant($tenant , $userData);

        return $this->responseJson(['user' => new UserResource($user)] ,  'User successfully registered' , 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request){
        if( auth()->user() ) {
            $this->logout();
        }

        if (!auth()->attempt(credentials: $request->validated())) {
            return $this->responseJsonFailed( 'Credintials fail' , 401 );
        }

        return $this->createNewToken();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return $this->createNewToken();
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        Auth::user()->currentAccessToken()->delete();
        return $this->responseJson([] , $message = 'User successfully signed out');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return $this->responseJson(new UserResource( auth()->user() ));
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken(){
        return $this->responseJson([
            'access_token' => auth()->user()->createToken('MyApp')->plainTextToken,
            'user' => new UserResource(auth()->user()),
        ], "logged in successfully" , 200);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return $this->responseJsonFailed('Old password does not match.', 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();
        return $this->responseJson(new UserResource($user), 'Password changed successfully', 200);
    }
}
