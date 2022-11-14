@extends('adminlte::page')

@section('title', 'Profile | Settings')

@section('content_header')
    <h4 class="m-0 text-dark">Change Password : {{auth()->user()->username}}</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- show change password form --}}
                    <form action="{{route('settings.index')}}/generatenewpassword" id="newpassword-form" method="POST">
                        @csrf
                        <div class="row">
                            {{-- old password --}}
                            <div class="col-md-12">
                                <x-flash-message />
                                <div class="col-md-6">
                                    <x-adminlte-input name="old_password" type="password" label="Old Password :"
                                    placeholder="Type old password..." />
                                </div>
                            </div>
                            <br>
                        
                            {{-- new password --}}
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <x-adminlte-input name="password" type="password" label="New Password :"
                                    placeholder="Type new password..." />
                                    <p class="text-info">Mininal password length is 8 characters</p>
                                </div>
                            </div>
    
                            {{-- confirm password --}}
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <x-adminlte-input name="password_confirmation" type="password" label="Confirm Password :"
                                    placeholder="Type confirm password..." />
                                </div>
                            </div>

                            {{-- submit buttons --}}
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="float-right">
                                        <x-adminlte-button type="submit" class="btn-flat edit-mode-button" label="Update" theme="success"
                                        icon="fas fa-recycle" id="submit-newpassword-button" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
