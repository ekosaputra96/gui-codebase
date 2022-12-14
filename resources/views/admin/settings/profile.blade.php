@extends('adminlte::page')

@section('title', 'Profile | Settings')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <h5 class="m-0 text-dark">Profile : <span id="username"></span></h5>
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

                                    <x-adminlte-button type="submit" class="btn-flat edit-mode-button" label="Update" theme="success"
                                        icon="fas fa-recycle" id="submit-user-button" />

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
                $(this).hide();
                $('.edit-mode-button').show();
                $('.edit-mode').removeAttr('readonly');
            })

            // cancel user button
            $('#cancel-user-button').click(function() {
                refreshData();
            })

            // submit user button
            $('#edit-user').on('submit', function(e) {
                e.preventDefault();
                const id = '{{ auth()->user()->id }}';
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
            const id = '{{ auth()->user()->id }}'

            // request user information
            $.ajax({
                url: '{{ route('settings.index') }}/' + id + '/edit',
                type: 'GET',
                success: function(data) {
                    // set roles for the current user
                    let roles = '';
                    if(data.roles.length === 0){
                        roles = '-'
                    }else{
                        $.each(data.roles, function(index, value) {
                            if(index == 0){
                                roles += value;
                            }else{
                                roles += ', ' + value;
                            }
                        })
                    }
                    $('#roles').html('<span>'+ roles +'</span>')
                    $('#name_edit').val(data.name);
                    $('#username').html(data.username);
                    $('#username_edit').val(data.username);
                    $('#email_edit').val(data.email);
                    $('#kode_company_edit').val(data.kode_company).trigger('change');
                    $('#kode_lokasi_edit').val(data.kode_lokasi).trigger('change');
                    $('.edit-mode').attr('readonly', true);
                    $('.edit-mode-button').hide();
                    $('.read-mode-button').show();
                }
            })
        }
    </script>
@endpush
