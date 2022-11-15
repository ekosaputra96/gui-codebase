<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use App\Models\MasterLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;

class SettingsController extends Controller
{
    /**
     * getting all users from User table to dataTable.
     *
     * @return all users
     */
    public function getUsers(){
        return Datatables::of(User::orderBy('created_at'))->make(true);
    }


    /**
     * checking the username is available or not from User table.
     *
     * @return locations
     */
    public function username($username){
        return response()->json(User::select('username')->where('username', $username)->count());
    }
    
    /**
     * getting locations lists from MasterLokasi table.
     *
     * @return locations
     */
    public function getLocations()
    {
        return MasterLokasi::pluck('nama_lokasi', 'kode_lokasi')->toArray();
    }

    /**
     * getting companies lists from Company table.
     *
     * @return companies
     */
    public function getcompanies()
    {
        // getting companies
        $companies = [];

        foreach (Company::select('kode_company', 'nama_company')->get() as $item) {
            $companies[$item->kode_company] = $item->kode_company . ' - ' . $item->nama_company;
        }

        return $companies;
    }

    /**
     * Display new password form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function message(bool $success, string $title, string $message)
    {
        return [
            'success' => $success,
            'title' => $title,
            'message' => $message,
        ];
    }

    /**
     * validating and generating password, return valid/invalid.
     *
     * @return \Illuminate\Http\Response
     */
    public function manageUsers()
    {
        if (
            auth()
                ->user()
                ->hasRole('superadministrator')
        ) {
            // getting companies
            $companies = $this->getCompanies();

            // getting locations
            $locations = $this->getLocations();
            return view('admin.settings.manageusers', compact('companies', 'locations'));
        }
        return abort('401');
    }

    /**
     * validating and generating password, return valid/invalid.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateNewPassword(Request $request)
    {
        // validate user input
        $formFields = $request->validate([
            'old_password' => ['required', 'min:8'],
            'password' => 'required|confirmed|min:8',
        ]);

        // getting logged in user information from database
        $user = User::select('id', 'password')->find(auth()->id());

        // check old password and userpassword
        if (Hash::check($formFields['old_password'], $user['password'])) {
            $user->update([
                'password' => Hash::make($formFields['password']),
            ]);
            return redirect(route('settings.index') . '/newpassword')->with('message', 'Your password has been changed successfullly !');
        }

        return back()
            ->withErrors(['old_password' => 'Invalid Old Password'])
            ->onlyInput('old_password');
    }

    /**
     * Display new password form of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function newPassword()
    {
        return view('admin.settings.newpassword');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // getting companies for company input
        $companies = $this->getCompanies();

        // getting locations for locations input
        $locations = $this->getLocations();

        return view('admin.settings.profile', compact('companies', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check if the username is available 
        if(User::select('username')->where('username', $request->username)->count() >= 1){
            return response()->json($this->message(false, 'Error', 'The username is already taken !'));
        }

        // check if the password and password confirmation matches
        if($request->password !== $request->password_confirmation){
            return response()->json($this->message(false, 'Error', 'The password does not match !'));
        }

        // set remember token
        $request['remember_token'] = Str::random(60);

        try {
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'kode_lokasi' => $request->kode_lokasi,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'remember_token' => $request->remember_token,
                'kode_company' => $request->kode_company
            ]);
            return response()->json($this->message(true, 'Created', 'The user has been added successfully'));
        } catch (\Throwable $th) {
            return response()->json($this->message(false, 'Failed', $th->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // check if the current logged in user has superadministrator role
        if(!auth()->user()->hasRole('superadministrator')) {
            return abort('401');
        }

        // getting companies for company input
        $companies = $this->getCompanies();

        // getting locations for locations input
        $locations = $this->getLocations();

        return view('admin.settings.userdetail', compact('companies', 'locations', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // getting user information from User table
        $user = User::select('id', 'name', 'username', 'kode_lokasi', 'email', 'kode_company')->find($id);

        
        // if user is null
        if (!$user) {
            return abort(404, 'Not Found User');
        }

        // getting user roles
        $user['roles'] = $user->getRoles();

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // init message
        $message = [];

        // getting user by id and update
        try {
            $user = User::find($id)->update([
                'name' => $request->name_edit,
                'email' => $request->email_edit,
                'kode_company' => $request->kode_company_edit,
                'kode_lokasi' => $request->kode_lokasi_edit,
            ]);
            if ($user) {
                $message = $this->message(true, 'Update', 'Data ' . $request->username_edit . ' telah diupdate');
            } else {
                $message = $this->message(false, 'Gagal', 'Data ' . $request->username_edit . ' gagal diupdate');
            }
        } catch (\Throwable $th) {
            $message = $this->message(false, 'Gagal', $th->getMessage());
        }
        return response()->json($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // getting user by id
            $user = User::find($id);
            if(!$user){
                return abort('404');
            }

            $user->delete();

            return response()->json($this->message(true, 'Deleted', $user->username . ' has been deleted'));
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json($this->message(false, 'Failed', $th->getMessage()));
        }
    }
}
