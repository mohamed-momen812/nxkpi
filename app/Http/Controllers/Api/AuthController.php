<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Company;
use App\Models\Tenant;
use App\Services\TenantService;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    use ApiTrait;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    private $categoryRepo ;
    public function __construct(CategoryRepositoryInterface $categoryRepository) {
//        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->categoryRepo = $categoryRepository ;
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request){
        if( auth()->user() ) {
            auth()->logout();
        }
        if (! $token = auth()->attempt($request->validated())) {
            return $this->responseJsonFailed( 'Credintials fail' , 401 );
        }

        return $this->createNewToken($token);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request , TenantService $tenantService) {
        $userData = array_merge( $request->validated(), ['password' => bcrypt($request->password) ] );
        $user = User::create($userData);
        $user->assignRole( "Owner" );
        $tenant = Tenant::create(['user_id' => $user->id ]);
        $tenant->domains()->create(['domain' => $user->company_domain . "." . config('tenancy.custom_domain')]);

        $tenantService->intiateTenant($tenant , $userData);

        return $this->responseJson(['user' => new UserResource($user)] ,  'User successfully registered' , 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return $this->responseJson([] , $message = 'User successfully signed out');
//        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return $this->responseJson(new UserResource( auth()->user() ));
//        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return $this->responseJson([
            'access_token' => auth()->user()->createToken('MyApp')->plainTextToken,

//            'access_token' => $token,
//            'token_type' => 'bearer',
//            'expires' => auth()->factory()->getTTL() * 60,
            'user' => new UserResource(auth()->user()),
        ], "logged in successfully" , 200);
//        return response()->json([
//            'access_token' => $token,
//            'token_type' => 'bearer',
//            'expires_in' => auth()->factory()->getTTL() * 60,
//            'user' => auth()->user()
//        ]);
    }
}
