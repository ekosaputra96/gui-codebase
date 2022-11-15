@extends('adminlte::page')

@section('title', 'Profile | Settings')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <h5 class="m-0 text-dark">User Detail : <span id="username"></span></h5>
        </div>
        <div class="col-md-6">
            <h5 class="m-0 text-dark">Role : <span id="roles"></span></h5>
        </div>
    </div>
@stop

{{-- enabling select2 --}}
@section('plugins.Select2', true)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- header content --}}
                    <div class="header-content">
                        {{-- back button --}}
                        <a href="{{ route('settings.index') }}/manageusers">
                            <x-adminlte-button label="Back" theme="primary" icon="fas fa-arrow-left" class="btn-xs"
                                id="back-button" />
                        </a>

                        {{-- managing users/laratrust --}}
                        <a href="{{ url('/laratrust') }}" target="_blank">
                            <x-adminlte-button label="Manage Users" theme="success" icon="fas fa-users-cog"
                                class="btn-xs float-right" />
                        </a>
                    </div>
                    <hr>

                    {{-- form for editing logged in user --}}
                    <form action="#" id="edit-user">
                        @csrf
                        <div class="row">
                            {{-- username --}}
                            <div class="col-md-6">
                                <x-adminlte-input name="username_edit" type="text" label="Username :"
                                    placeholder="Enter Username..." readonly required />
                            </div>

                            {{-- fullname --}}
                            <div class="col-md-6">
                                <x-adminlte-input name="name_edit" type="text" label="Fullname :" class="edit-mode"
                                    placeholder="Enter Fullname..." readonly required />
                            </div>

                            {{-- email --}}
                            <div class="col-md-12">
                                <x-adminlte-input name="email_edit" type="email" label="Email :" class="edit-mode"
                                    placeholder="Enter Email..." readonly required />
                            </div>

                            {{-- Company --}}
                            <div class="col-md-6">
                                <x-adminlte-select2 name="kode_company_edit" data-placeholder="Select a company..."
                                    class="edit-mode" label="Company :" readonly required>
                                    <x-adminlte-options :options="$companies" placeholder="" />
                                </x-adminlte-select2>
                            </div>

                            {{-- Lokasi --}}
                            <div class="col-md-6">
                                <x-adminlte-select2 name="kode_lokasi_edit" data-placeholder="Select a location..."
                                    class="edit-mode" label="Lokasi :" readonly required>
                                    <x-adminlte-options :options="$locations" placeholder="" />
                                </x-adminlte-select2>
                            </div>

                            <div class="col-md-12">
                                <div class="float-right">
                                    {{-- edit button --}}
                                    <x-adminlte-button class="btn-flat read-mode-button" label="Edit" theme="info"
                                        icon="fas fa-user-edit" id="edit-user-button" />

                                    {{-- delete button --}}
                                    <x-adminlte-button class="btn-flat read-mode-button" label="Delete" theme="danger"
                                        icon="fas fa-trash-alt" id="delete-user-button" />

                                    {{-- submit button --}}
                                    <x-adminlte-button type="submit" class="btn-flat edit-mode-button" label="Update"
                                        theme="success" icon="fas fa-recycle" id="submit-user-button" />

                                    {{-- cancel button --}}
                                    <x-adminlte-button class="btn-flat edit-mode-button" label="Cancel" theme="danger"
                                        icon="fas fa-undo-alt" id="cancel-user-button" />
                                </div>
                            </div>
                        </div>
                    </form>
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
            // refresh data
            refreshData();

            // edit-user-button
            $('#edit-user-button').click(function() {
                $('.read-mode-button').hide();
                $('.edit-mode-button').show();
                $('.edit-mode').removeAttr('readonly');
            })

            // cancel user button
            $('#cancel-user-button').click(function() {
                refreshData();
            })

            // delete user button
            $('#delete-user-button').click(function() {
                const id = '{{ $id }}';
                Swal.fire({
                    icon: 'warning',
                    text: 'Are you sure to delete this user ?',
                    showCancelButton: true,
                    reverseButtons: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then(function(e) {
                    if (e.value === true) {
                        $.ajax({
                            url: '{{ route('settings.index') }}/' + id,
                            type: 'DELETE',
                            success: function(data) {
                                if (data.success) {
                                    return Swal.fire({
                                        icon: 'success',
                                        title: data.title,
                                        text: data.message
                                    }).then(function() {
                                        $(location).attr('href',
                                            '{{ route('settings.index') }}/manageusers'
                                        )
                                    })
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: typeof data.title === 'undefined' ? 'Error' : data.title,
                                        text: typeof data.title === 'undefined' ? 'Something Wrong !' : data.message
                                    })
                                }
                            },
                        })
                    }
                })
            })

            // submit user button
            $('#edit-user').on('submit', function(e) {
                e.preventDefault()
                const id = '{{ $id }}';
                const data = $(this).serialize();
                Swal.fire({
                    icon: 'warning',
                    title: 'Loading..',
                    text: "Please wait for a moment !",
                    showConfirmButton: false,
                });
                $.ajax({
                    url: '{{ route('settings.index') }}/' + id,
                    type: 'PUT',
                    data: data,
                    success: function(data) {
                        // notif
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
                        refreshData();
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something Wrong !'
                        })
                    }
                })
            })
        })

        // refresh data
        function refreshData() {
            const id = '{{ $id }}'

            // request user information
            $.ajax({
                url: '{{ route('settings.index') }}/' + id + '/edit',
                type: 'GET',
                success: function(data) {
                    // set roles for the current user
                    let roles = '';
                    if (data.roles.length === 0) {
                        roles = '-'
                    } else {
                        $.each(data.roles, function(index, value) {
                            if (index == 0) {
                                roles += value;
                            } else {
                                roles += ', ' + value;
                            }
                        })
                    }
                    $('#roles').html('<span>' + roles + '</span>')
                    $('#name_edit').val(data.name);
                    $('#username').html(data.username);
                    $('#username_edit').val(data.username);
                    $('#email_edit').val(data.email);
                    $('#kode_company_edit').val(data.kode_company).trigger('change');
                    $('#kode_lokasi_edit').val(data.kode_lokasi).trigger('change');
                    $('.edit-mode').attr('readonly', true);
                    $('.edit-mode-button').hide();
                    $('.read-mode-button').show();
                },
                error: function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Not Found',
                        text: 'The user is not found !'
                    }).then(function() {
                        $(location).attr('href', '{{route('settings.index')}}/manageusers');
                    })
                }
            })
        }
    </script>
@endpush
