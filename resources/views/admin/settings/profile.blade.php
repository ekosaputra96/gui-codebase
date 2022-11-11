@extends('adminlte::page')

@section('title', 'Profile | Settings')

@section('content_header')
    <h1 class="m-0 text-dark">Profile : {{ auth()->user()->username }}</h1>
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
                            {{-- fullname --}}
                            <div class="col-md-6">
                                <x-adminlte-input name="name_edit" type="text" label="Fullname :"
                                    placeholder="Enter Fullname..." readonly />
                            </div>

                            {{-- username --}}
                            <div class="col-md-6">
                                <x-adminlte-input name="username_edit" type="text" label="Username :"
                                    placeholder="Enter Username..." readonly />
                            </div>

                            {{-- email --}}
                            <div class="col-md-12">
                                <x-adminlte-input name="email_edit" type="email" label="Email :"
                                    placeholder="Enter Email..." readonly />
                            </div>

                            {{-- Company --}}
                            <div class="col-md-6">
                                <x-adminlte-select2 name="kode_company_edit" data-placeholder="Select a company..."
                                    label="Company :" readonly>
                                    <x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']" placeholder="" />
                                </x-adminlte-select2>
                            </div>

                            {{-- Lokasi --}}
                            <div class="col-md-6">
                                <x-adminlte-select2 name="kode_lokasi_edit" data-placeholder="Select a location..."
                                    label="Lokasi :" readonly>
                                    <x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']" placeholder="" />
                                </x-adminlte-select2>
                            </div>

                            {{-- submit button --}}
                            <div class="col-md-12">
                                <x-adminlte-button class="btn-flat float-right" label="Edit" theme="success"
                                    icon="fas fa-user-edit" id="edit-user-button" />
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
            $('#edit-user-button').click(function(e) {
                e.preventDefault();
                const data = $('#edit-user').serialize();
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
                    $('#name_edit').val(data.name);
                    $('#username_edit').val(data.username);
                    $('#email_edit').val(data.email);
                    $('#kode_company_edit').val(data.kode_company);
                    $('#kode_lokasi_edit').val(data.kode_lokasi);
                }
            })
        }
    </script>
@endpush
