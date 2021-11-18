<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Mail\Register;
use App\User;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use File;
use Hash;
use Illuminate\Support\Facades\Mail;
use Redirect;
use Sentinel;
use URL;
use View;
use Yajra\DataTables\DataTables;
use Validator;
Use App\Mail\Restore;
USE DB;

class UsersController extends JoshController
{

    /**
     * Show a list of all the users.
     *
     * @return View
     */

    public function index()
    {
        //$groups = Sentinel::getRoleRepository()->all();
        //return view('admin.users.index', compact('groups'));

        return view('admin.users.index');
    }

    public function data(Request $request)
    {
        $query = DB::table('users')
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->join('roles', 'role_users.role_id', '=', 'roles.id')
            ->select('users.id as id', 'users.full_name as full_name', 'users.email as email', 'users.status_process as group_name', 'users.deleted_at as deleted_at');

        if(isset($request->text_search) && ($request->text_search != '')){
            $query->where([
                ['users.email','like','%'.$request->text_search.'%'],
                ['users.full_name','like','%'.$request->text_search.'%','or']
            ]);
        }
        if(isset($request->group_search)){
            //$query->where('roles.id','=',$request->group_search);
            $query->where('users.status_process','=',$request->group_search);
        }
        if(isset($request->status_search)){
            if($request->status_search == 1){
                $query->where('users.deleted_at','=',null);
            }
            else{
                $query->where('users.deleted_at','!=',null);
            }
        }

        $query->orderby('users.created_at','desc');
        $users = $query->get();

        return DataTables::of($users)
            ->editColumn('group_name',function($user){
                return ($user->group_name == 0)?'Super Admin' : 'Operation';
            })
            ->addColumn('status',function($user){
                return ($user->deleted_at == null)?'<span class="fa fa-circle dot-green"></span><span class="fnt-14">&nbsp;&nbsp; Active</span>' : '<span class="fa fa-circle dot-gray"></span><span class="fnt-14">&nbsp;&nbsp; Inactive</span>';
            })
            ->addColumn('actions',function($user) {
                $actions = '<a style="color: #000;" href='. route('admin.users.edit', $user->id) .'><i style="color: #000;" class="livicon" data-name="pen" data-size="18" data-loop="true" title="update user"></i>แก้ไข</a>&nbsp;&nbsp;';

                /*
                $actions .= '<a href='. route('admin.users.change', $user->id) .'><i class="livicon" data-name="lock" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="change password"></i></a>';
                
                if ((Sentinel::getUser()->id != $user->id) && ($user->id != 1)) {
                    if($user->deleted_at == null){
                        $actions .= '<a href='. route('admin.users.confirm-delete', $user->id) .' data-id="'.$user->id.'" data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete user"></i>ลบ</a>&nbsp;&nbsp;';
                    }
                    else{
                        $actions .= '<a href='. route('admin.users.confirm-restore', $user->id) .' data-id="'.$user->id.'" data-toggle="modal" data-target="#restore_confirm"><i class="livicon" data-name="rotate-left" data-size="18" data-loop="true" data-c="#6cc66c" data-hc="#6cc66c" title="restore user"></i>กู้คืน</a>&nbsp;&nbsp;';
                    }
                }
                */
                return $actions;
            })
            ->rawColumns(['full_name','status','actions'])
            ->make(true);

    }

    public function create()
    {
        $groups = Sentinel::getRoleRepository()->all();
        return view('admin.users.create', compact('groups'));
    }

    public function edit(User $user)
    {
        // Get this user groups
        $userRoles = $user->getRoles()->pluck('name', 'id')->all();
        // Get a list of all the available groups
        $roles = Sentinel::getRoleRepository()->all();

        $status = Activation::completed($user);

        return view('admin.users.edit', compact('user', 'roles', 'userRoles', 'status'));
    }

    public function change(User $user)
    {
        return view('admin.users.change_password', compact('user'));
    }

    public function show($id)
    {
        try {
            // Get the user information
            $user = Sentinel::findUserById($id);
            //get country name
            
            // if ($user->country) {
            //     $user->country = $this->countries[$user->country];
            // }
            
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));
            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('response_error', $error);
        }
        // Show the page
        return view('admin.users.show', compact('user'));

    }

    public function getDeletedUsers()
    {
        // Grab deleted users
        $users = User::onlyTrashed()->get();

        // Show the page
        return view('admin.deleted_users', compact('users'));
    }

    public function getRestoredUsers()
    {
        // Grab deleted users
        $users = User::onlyTrashed()->get();

        // Show the page
        return view('admin.restored_users', compact('users'));
        //return view('admin.restored_users');
    }

    public function getModalDelete($id)
    {
        $model = 'users';
        $confirm_route = $error = null;
        try {
            // Get user information
            $user = Sentinel::findById($id);

            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = trans('users/message.error.delete');

                return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
        $confirm_route = route('admin.users.delete', ['id' => $user->id]);
        return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
    }

    public function getModalRestore($id)
    {
        $model = 'users';
        $confirm_route = $error = null;
        try {
            // Get user information
            $user = Sentinel::findById($id);

            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = trans('users/message.error.delete');

                return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
        $confirm_route = route('admin.users.restore', ['id' => $user->id]);
        return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
    }





    //Insert
    public function store(UserRequest $request)
    {
        //check already user
        $check_user = DB::table('users')
            ->where('email','=',$request->email)
            ->get();

        if(count($check_user) == 0){
            //can use
            //check whether use should be activated by default or not
            $activate = ($request->get('activate')==1) ? true : false;

            try {
                // Register the user
                //$user = Sentinel::register($request->except('_token', 'password_confirm', 'group', 'activate', 'pic_file'), $activate);

                $user = Sentinel::register($request->except('_token', 'password_confirm', 'group', 'activate'), $activate);

                //add user to 'User' group
                $role = Sentinel::findRoleById($request->get('group'));
                if ($role) {
                    $role->users()->attach($user);
                }
                //check for activation and send activation mail if not activated by default
                if (!$request->get('activate')) {
                    // Data to be used on the email view
                    $data =[
                        'user_name' => $user->full_name,
                        'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code])
                    ];
                    // Send the activation code through email
                    //Mail::to($user->email)
                    //    ->send(new Register($data));
                }
                // Activity log for New employee create
                activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('New Administrator Created by '.Sentinel::getUser()->full_name);
                // Redirect to the home page with success menu
                return Redirect::route('admin.users.index')->with('response_success', trans('users/message.success.create'));

            } catch (LoginRequiredException $e) {
                $error = trans('users/message.user_login_required');
            } catch (PasswordRequiredException $e) {
                $error = trans('users/message.user_password_required');
            } catch (UserExistsException $e) {
                $error = trans('users/message.user_exists');
            }
        }
        else{
            //can't use
            $error = trans('users/message.user_exists');
        }
        
        // Redirect to the user creation page
        return Redirect::back()->withInput()->with('response_error', $error);
    }

    //Update
    public function update(User $user, UserRequest $request)
    {   

        try {
            //update ทุกค่ายกเว้นค่าเหล่านี้
            $user->update($request->except('password','password_confirm','groups','activate','status'));

            if ( !empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            //save record
            $user->save();

            // Get the current user groups
            $userRoles = $user->roles()->pluck('id')->all();

            // Get the selected groups

            //$selectedRoles = $request->get('groups');

            // Groups comparison between the groups the user currently
            // have and the groups the user wish to have.
            // $rolesToAdd = array_diff($selectedRoles, $userRoles);
            // $rolesToRemove = array_diff($userRoles, $selectedRoles);

            // Assign the user to groups

            // foreach ($rolesToAdd as $roleId) {
            //     $role = Sentinel::findRoleById($roleId);
            //     $role->users()->attach($user);
            // }

            // // Remove the user from groups
            // foreach ($rolesToRemove as $roleId) {
            //     $role = Sentinel::findRoleById($roleId);
            //     $role->users()->detach($user);
            // }


            //1=0
            if(($user->deleted_at == null) && ($request->status == 0)){
                //destroy
                $destroy = $this->destroy($user->id);
            }
            //0=1
            else if(($user->deleted_at != null) && ($request->status == 1)){
                //restore
                $destroy = $this->getRestore($user->id);
            }


            /*
            // Activate / De-activate user
            $status = $activation = Activation::completed($user);

            if ($request->get('activate') != $status) {
                if ($request->get('activate')) {
                    $activation = Activation::exists($user);
                    if ($activation) {
                        Activation::complete($user, $activation->code);
                    }
                } else {
                    //remove existing activation record
                    Activation::remove($user);
                    //add new record
                    Activation::create($user);
                    //send activation mail
                    $data=[
                        'user_name' =>$user->full_name,
                    'activationUrl' => URL::route('activate', [$user->id, Activation::exists($user)->code])
                    ];
                    // Send the activation code through email
                    //Mail::to($user->email)
                    //    ->send(new Restore($data));
                }
            }

            
            //Direct activate after update deleted_at is null 
            //get activation code
            // $activation = Activation::create($user);

            // if ($request->get('activate')==1) {
            //     //set activate user
            //     Activation::complete($user, $activation->code);

            //     return 'OK';
            // }
            // else{
            //     return 'Cancel';
            // }
            */
            



            // Was the user updated?
            if ($user->save()) {
                // Prepare the success message
                $success = 'Administrator was successfully updated.';
               //Activity log for user update
                activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('Administrator Updated by '.Sentinel::getUser()->full_name);
                // Redirect to the user page
                return Redirect::route('admin.users.index')->with('response_success', trans('users/message.success.update'));
            }

            // Prepare the error message
            $error = trans('users/message.error.update');
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('response_error', $error);
        }

        // Redirect to the user page
        return Redirect::route('admin.users.edit', $user)->withInput()->with('response_error', $error);
        
    }

    //Change
    public function changePassword(User $user, Request $request)
    {
        if((!empty($request->password)) && (!empty($request->new_password)) 
                && (!empty($request->confirm_password))){
            if($request->new_password == $request->confirm_password){
                
                //check password
                if(Hash::check($request->password, $user->password)) {
                    $user->password = Hash::make($request->new_password);

                    if($user->save()) {
                        $success = trans('users/message.success.change_password_success');
                        activity($user->full_name)
                            ->performedOn($user)
                            ->causedBy($user)
                            ->log('Administrator Change Password by '.Sentinel::getUser()->full_name);

                        return Redirect::back()->withInput()->with('response_success', $success);
                    }
                    else{
                        $error = trans('users/message.error.update');
                    }
                }
                else{
                    $error = trans('users/message.error.password_incorrect');
                }
            }
            else{
                $error = trans('users/message.error.check_confirm_password');
            }
        }
        else{
            $error = trans('users/message.error.params_required');
        }

        return Redirect::back()->withInput()->with('response_error', $error);
    }

    //Delete
    public function destroy($id)
    {
        try {
            // Get user information
            $user = Sentinel::findById($id);
            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = trans('admin/users/message.error.delete');
                // Redirect to the user management page
                return Redirect::route('admin.users.index')->with('response_error', $error);
            }
            // Delete the user
            //to allow soft deleted, we are performing query on users model instead of Sentinel model
            //User::destroy($id);
            //Activation::where('user_id',$user->id)->delete();

            //set inactive
            $users = DB::table('users')
                    ->where('id','=',$id)
                    ->update(['deleted_at' => date('Y-m-d H:i:s')]);



            // Prepare the success message
            $success = trans('users/message.success.delete');
            //Activity log for user delete
            activity($user->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('User deleted by '.Sentinel::getUser()->full_name);
            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('response_success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('admin/users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('response_error', $error);
        }
    }  

    //Restore
    public function getRestore($id)
    {
        try {
            // Get user information
            //$user = User::withTrashed()->find($id);
            // Restore the user
            //$user->restore();

            // Activation complete user
            //$activation = Activation::create($user);
            //Activation::complete($user, $activation->code);

            $user = Sentinel::findById($id);

            //set inactive
            $users = DB::table('users')
                    ->where('id','=',$id)
                    ->update(['deleted_at' => null]);
            
            // $data=[
            //     'user_name' => $user->full_name,
            //     'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code])
            // ];
            //Mail::to($user->email)
            //    ->send(new Restore($data));
            // Prepare the success message
            $success = trans('users/message.success.restored');
            activity($user->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('User restored by '.Sentinel::getUser()->full_name);
            // Redirect to the user management page
            //return Redirect::route('admin.restored_users')->with('success', $success);

            return Redirect::route('admin.users.index')->with('response_success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.restored_users')->with('response_error', $error);
        }
    }

    public function passwordreset( Request $request)
    {
        $id = $request->id;
        $user = Sentinel::findUserById($id);
        $password = $request->get('password');
        $user->password = Hash::make($password);
        $user->save();
    }

    public function lockscreen($id){

        if (Sentinel::check()) {
            $user = Sentinel::findUserById($id);
            return view('admin.lockscreen',compact('user'));
        }
        return view('admin.login');
    }

    public function postLockscreen(Request $request){
        $password = Sentinel::getUser()->password;
        if(Hash::check($request->password,$password)){
            return 'success';
        } else{
            return 'error';
        }
    }
}
