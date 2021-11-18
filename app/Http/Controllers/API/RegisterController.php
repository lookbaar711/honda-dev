<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Member;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Foundation\Auth\AuthenticatesUsers;


use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

use Sentinel;
use Str;
use DB;


class RegisterController extends BaseController
{
    use AuthenticatesUsers;

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['status'] = 1;
        $member = Member::create($input);
        //$success['token'] = 'aaaaaaaaaa'; //$member->createToken('MyApp')->accessToken;
        //$success['name'] = $member->first_name.' '.$member->last_name;


        return $this->sendResponse($input, 'Employee register successfully.');
    }

    public function login(Request $request)
    {

        try {
            // Try to log the user in
            if ($member = Sentinel::authenticate($request->only('email', 'password'))) {
                //Activity log for login
                /*
                activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('LoggedIn');
                */

                $member_token = $this->get_token($request->email);

                $data = ['member_token' => $member_token];  

                return $this->sendResponse($data,'Login success');
            } else {
                return $this->sendError('Email or password is incorrect.');

            }

        } catch (UserNotFoundException $e) {
            $messageBag = 'Account not found';
        } catch (NotActivatedException $e) {
            $messageBag = 'Account not activated';
        } catch (UserSuspendedException $e) {
            $messageBag = 'Account suspended';
        } catch (UserBannedException $e) {
            $messageBag = 'Account banned';
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            $messageBag = 'Account suspended'; 
        }

        // Ooops.. something went wrong
        return $this->sendError($messageBag); 
        
    }

    public function get_token($email)
    {
        $member = DB::table('members')->select('member_token')->where('email','=',$email)->first();

        if(isset($member->member_token)){
            $member_token = $member->member_token;

        }
        else{
            $member_token = Str::random(60);
            DB::table('members')->where('email', $email)->update(['member_token' => $member_token]);
        } 

        return $member_token;
    }
}