<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminResource;
use App\Http\Resources\AdminCreateResource;
use App\Http\Requests\AuthenticateAdminRequest;
use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Resources\AdminUpdateResource;
use App\Http\Resources\AuthenticatedAdminResource;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class AdminController extends Controller
{
    public function index() 
    {
        $admins = Admin::all();

        if ($admins->count() > 0) {
            return response()->json(['admins' => $admins]);
        } else {
            return response()->json([
                'admins' => 'No Admins Found'
            ]);
        }
    }

    //Login Admin
    public function login(AuthenticateAdminRequest $payload){
        $email = $payload->email; 
        $password = $payload->password;

        $admin = Admin::where('email', $email)->first();

        if(!$admin || !Hash::check($password, $admin->password)){
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect'],
            ]);
        }

        $response = (object) [
            'user' => new AdminResource($admin),
            'token' => $admin->createToken('auth-token')->plainTextToken
        ];

        return new AuthenticatedAdminResource($response);
    }

    public function logout(Request $payload){
        $taker = $payload->user();
        $taker->tokens()->delete();
        return "logout success";
    }

    public function sendVerifyEmail($email){
        if(auth()->user()){
            $admin = Admin::where('email', $email)->first();
    
            if($admin){
                $random = Str::random(40);
                $domain = URL::to('/');
                $url = $domain.'/verify-mail/admins/'.$random;
    
                $data['url'] = $url;
                $data['email'] = $email;
                $data['title'] = "Email verification";
                $data['body'] = "Please click here to verify";
    
                // Use the correct view name for the mail
                Mail::send('verifyMail',['data' => $data], function($message) use ($data){
                    $message->to($data['email'])->subject($data['title']);
                });
    
                // Retrieve the model instance
                $adminModel = Admin::find($admin->id);
                $adminModel->remember_token = $random;
                $adminModel->save();
    
                return response()->json(['success'=>true, 'msg'=>'Mail sent success', '__token' => $random]);
            }
            else{
                return response()->json(['success'=>false, 'msg'=>'User is not found']);
            }
        }
        else{
            return response()->json(['success'=>false, 'msg'=>'User is not authenticated']);
        }
    }

    public function verificationMail($token){
        $admin = Admin::where('remember_token',$token)->get();
        if(count($admin) > 0){
            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            $admin = Admin::find($admin[0]['id']);
            $admin->remember_token = '';
            $admin->is_verified = '1';
            $admin->email_verified_at = $datetime;
            $admin->save();
            return redirect('http://localhost:3000/admin/dashboard')->with([
                'success' => true,
                'msg' => 'User is verified',
            ]);
        }
        else{
            return response()->json([
                'success'=>false, 'msg'=>'404'
            ]);
        }

    }
    
    public function getAdmin(Request $payload)
    {
        $admin = auth()->user();
        return new AdminResource($admin);
    }
    public function create(CreateAdminRequest $payload) 
    {
        $first_name = $payload->first_name;
        $last_name = $payload->last_name;
        $age = $payload->age;
        $email = $payload->email;
        $password = $payload->password;

        $admin = Admin::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'age' => $age,
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        return new AdminCreateResource($admin);
    }


    public function read($id) 
    {
        $admin = Admin::find($id);

        if ($admin) {
            return response()->json([
                'admin' => $admin
            ]);
        } else {
            return response()->json([
                'message' => 'Admin Not Found'
            ]);
        }
    }

    public function read_admin_questions() 
    {
        $admins = Admin::with('questions')->get();

        if ($admins->count() > 0) {
            return response()->json(['admins' => $admins]);
        } else {
            return response()->json([
                'admins' => 'No Admins Found'
            ]);
        }
    }

    public function read_admin_options() 
    {
        $admins = Admin::with('options')->get();

        if ($admins->count() > 0) {
            return response()->json(['admins' => $admins]);
        } else {
            return response()->json([
                'admins' => 'No Admins Found'
            ]);
        }
    }

    public function update(UpdateAdminRequest $payload, $id) 
    {
        $first_name = $payload->first_name;
        $last_name = $payload->last_name;
        $age = $payload->age;
        $email = $payload->email;
        $password = $payload->password;

        $admin = Admin::find($id);

            if ($admin) {

                $admin->update([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'age' => $age,
                    'email' => $email,
                    'password' => $password
                ]);
            }

            return new AdminUpdateResource($admin);               
    }
    

    public function delete($id) 
    {
        $admin = Admin::find($id);

        if ($admin) {
            $admin->delete();

            return response()->json([
                'message' => 'Admin Deleted Successfully'
            ]);
        } else {
            return response()->json([
                'message' => 'Admin Not Found'
            ]);
        }
    }
}
