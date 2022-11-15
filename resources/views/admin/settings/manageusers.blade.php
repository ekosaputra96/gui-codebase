@extends('adminlte::page')

{{-- @extends('adminlte::page', ['iFrameEnabled' => true]) --}}

@section('title', 'Users Managements | Settings')

@section('content_header')
    <h5 class="m-0 text-dark">Users Management</h5>
@stop

{{-- enabling select2 --}}
@section('plugins.Select2', true)

{{-- enabling Datatables --}}
@section('plugins.Datatables', true)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- header content --}}
                    <div class="header-content">
                        {{-- adding user button --}}
                        <x-adminlte-button label="New User" theme="info" icon="fas fa-user-plus" class="btn-xs"
                            id="new-user-button" />

                        {{-- back button --}}
                        <x-adminlte-button label="Back" theme="primary" icon="fas fa-arrow-left" class="btn-xs"
                            id="back-button" />

                        {{-- managing users/laratrust --}}
                        <a href="{{ url('/laratrust') }}" target="_blank">
                            <x-adminlte-button label="Manage Users" theme="success" icon="fas fa-users-cog"
                                class="btn-xs float-right" />
                        </a>
                    </div>

                    {{-- body content --}}
                    <div class="body-content">
                        {{-- form for editing logged in user --}}
                        <form action="{{ url('/') }}" id="add-user-form">
                            <hr>
                            @csrf
                            <div class="row">
                                {{-- fullname --}}
                                <div class="col-md-6">
                                    <x-adminlte-input name="name" type="text" label="Fullname :"
                                        placeholder="Enter Fullname..." required autocomplete="off" />
                                </div>

                                {{-- username --}}
                                <div class="col-md-6">
                                    <x-adminlte-input name="username" type="text" label="Username :"
                                        placeholder="Enter Username..." required autocomplete="off"> </x-adminlte-input>

                                    <span class="invalid-feedback d-block mb-3" role="alert">
                                        <strong class="username-feedback">The username is alreay taken.</strong>
                                    </span>
                                </div>

                                {{-- email --}}
                                <div class="col-md-12">
                                    <x-adminlte-input name="email" type="email" label="Email :"
                                        placeholder="Enter Email..." required autocomplete="off" />
                                </div>

                                {{-- password --}}
                                <div class="col-md-6">
                                    <x-adminlte-input name="password" type="password" label="Password :"
                                        placeholder="Enter Password..." minlength="8" required />

                                    <span class="invalid-feedback d-block mb-3" role="alert">
                                        <strong class="password-feedback">The password must be at least 8
                                            characters.</strong>
                                        <strong class="password-confirmation-feedback">The password doesn't match</strong>
                                    </span>
                                </div>

                                {{-- confirmation password --}}
                                <div class="col-md-6">
                                    <x-adminlte-input name="password_confirmation" type="password"
                                        label="Confirm Password :" placeholder="Enter Confirm Password..." minlength="8"
                                        required />
                                </div>

                                {{-- Company --}}
                                <div class="col-md-6">
                                    <x-adminlte-select2 name="kode_company" data-placeholder="Select a company..."
                                        label="Company :" required>
                                        <x-adminlte-options :options="$companies" placeholder="" />
                                    </x-adminlte-select2>
                                </div>

                                {{-- Lokasi --}}
                                <div class="col-md-6">
                                    <x-adminlte-select2 name="kode_lokasi" data-placeholder="Select a location..."
                                        label="Lokasi :" required>
                                        <x-adminlte-options :options="$locations" placeholder="" />
                                    </x-adminlte-select2>
                                </div>

                                <div class="col-md-12">
                                    <div class="float-right">
                                        {{-- submit button --}}
                                        <x-adminlte-button type="submit" class="btn-flat" label="Create" theme="success"
                                            icon="fas fa-user-check" id="submit-user-button" />

                                        {{-- cancel button --}}
                                        <x-adminlte-button type="reset" class="btn-flat" label="Reset" theme="danger"
                                            icon="fas fa-undo-alt" id="reset-user-button" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- table content --}}
                    <div class="table-content">
                        <hr>
                        <table class="table table-striped table-hover" id="data-table" style="font-size: 14px;"
                            width="100%">
                            <thead class="thead-light">
                                <tr>
                                    <td>id</td>
                                    <th scope="col">Username</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Kode Company</th>
                                    <th scope="col">Kode Lokasi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@push('js')
    <script type="text/javascript">
        // token for submitting
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            resetData(false, false, true);

            // submitting add-user-form
            $("#add-user-form").on('submit', function(e) {
                e.preventDefault();
                if ($('#password').val() !== $('#password_confirmation').val()) {
                    $('#password_confirmation').focus();
                    return Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "The password doesn't match !"
                    })
                }

                if ($('#username').hasClass('is-invalid')) {
                    $('#username').focus();
                    return Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "The username is already taken !"
                    })
                }
                // loading notification
                Swal.fire({
                    icon: 'warning',
                    title: 'Loading..',
                    text: "Please wait for a moment !",
                    showConfirmButton: false,
                });

                // set data from the add-user-form
                const data = $(this).serialize();
                $.ajax({
                    url: '{{ route('settings.index') }}',
                    type: 'POST',
                    data: data,
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: data.title,
                                text: data.message
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: data.title,
                                text: data.message
                            })
                        }
                        resetData(false, false, true);
                    }
                })
            });


        })

        // adding new user button
        $('#new-user-button').click(function() {
            $(this).hide();
            $('#back-button').show();
            $('#add-user-form').show();
        })

        // back button
        $('#back-button').click(function() {
            $('#add-user-form').hide();
            $('#new-user-button').show()
            resetData(false, false, true);
        })

        // reset button
        $('#reset-user-button').click(function() {
            resetData(true)
        })

        // username validation
        $('#username').change(function(e) {
            const username = e.target.value;
            $.ajax({
                url: '{{ route('settings.index') }}/' + 'username/' + username,
                type: 'GET',
                success: function(data) {
                    // if username is already taken
                    if (data >= 1) {
                        $('#username').removeClass('is-valid')
                        $('#username').addClass('is-invalid')
                        $('.username-feedback').show();
                    } else if (data == 0 && username.length != 0) {
                        // if username is available
                        $('#username').removeClass('is-invalid')
                        $('#username').addClass('is-valid')
                        $('.username-feedback').hide();
                    } else {
                        $('#username').removeClass('is-valid')
                        $('#username').removeClass('is-invalid')
                        $('.username-feedback').hide();
                    }
                }
            })
        })

        // password
        $('#password').change(function(e) {
            const password = e.target.value;
            // if password confirmation length is 0, remove all class and its feedback
            if (password.length === 0) {
                $(this).removeClass('is-valid')
                $(this).removeClass('is-invalid')
                $('.password-feedback').hide();
                return
            }
            // check if password is greater than 7
            if (password.length <= 7) {
                $(this).removeClass('is-valid')
                $(this).addClass('is-invalid')
                $('.password-feedback').show();
            } else {
                $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
                $('.password-feedback').hide();
            }
        })

        // password confirmation
        $('#password_confirmation').change(function(e) {
            const password_confirmation = e.target.value;
            // if password confirmation length is 0, remove all class and its feedback
            if (password_confirmation.length === 0) {
                $('#password').removeClass('is-valid')
                $('#password').removeClass('is-invalid')
                $('.password-confirmation-feedback').hide();
                return
            }
            // if password and its confirmation password do not match
            if (password_confirmation !== $('#password').val()) {
                $('#password').removeClass('is-valid')
                $('#password').addClass('is-invalid')
                $('.password-confirmation-feedback').show();
            } else {
                $('#password').removeClass('is-invalid')
                $('#password').addClass('is-valid')
                $('.password-confirmation-feedback').hide();
            }
        })

        // reset data
        function resetData(backbutton = false, addform = true, adduserbtn = false) {
            // hide add-user form
            if (addform) {
                $('#add-user-form').show();
            } else {
                $('#add-user-form').hide();
            }

            // hide / show back button
            if (backbutton) {
                $('#back-button').show();
            } else {
                $('#back-button').hide();
            }

            // show / hide add user button
            if (adduserbtn) {
                $('#new-user-button').show();
            } else {
                $('#new-user-button').hide();
            }
            $('#username').removeClass('is-valid')
            $('#username').removeClass('is-invalid')
            $('.username-feedback').hide();
            $('#password').removeClass('is-valid')
            $('#password').removeClass('is-invalid')
            $('.password-feedback').hide();
            $('.password-confirmation-feedback').hide();

            // reset all data
            $('#name').val('');
            $('#username').val('');
            $('#email').val('');
            $('#password').val('');
            $('#password_confirmation').val('');
            $('#kode_company').val('').trigger('change');
            $('#kode_lokasi').val('').trigger('change');
        }

        // getting users from server
        $(function() {
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('settings.index') }}/getusers',
                columns: [{
                        data: 'id',
                        visible: false,
                    },
                    {
                        data: 'username',
                        "fnCreatedCell": function(nTd, sData, oData, iRow, iCol) {
                            $(nTd).html("<a href='{{ route('settings.index') }}/" + oData
                                .id + "'>" + oData
                                .username + "</a>");
                        }
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'email',
                    },
                    {
                        data: 'kode_company',
                    },
                    {
                        data: 'kode_lokasi',
                    },
                ]
            })
        })
    </script>
@endpush
