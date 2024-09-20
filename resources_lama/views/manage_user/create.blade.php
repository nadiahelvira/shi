@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Tambah User Baru</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{url('user/manage')}}">Kelola User</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('user/add') }}">
                        @csrf

                        <!-- Username -->
                        <div class="form-group">
                            <x-label for="username" :value="__('Username')" />

                            <x-input id="username" type="text" name="username" :value="old('username')" required autofocus />
                        </div>

                        <!-- Name -->
                        <div class="form-group">
                            <x-label for="name" :value="__('Name')" />

                            <x-input id="name" type="text" name="name" :value="old('name')" required />
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <x-label for="email" :value="__('Email')" />

                            <x-input id="email" type="email" name="email" :value="old('email')" required />
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <x-label for="password" :value="__('Password')" />

                            <x-input id="password" type="password"
                                            name="password"
                                            required autocomplete="new-password" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <x-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-input id="password_confirmation" type="password"
                                            name="password_confirmation" required />
                        </div>

                        <!-- Divisi -->
                        <div class="form-group">
                            <x-label for="divisi" :value="__('Divisi')" />
                            <select name="divisi" id="divisi" class="btn-group btn-block" style="padding: 10px">
                                <option value="accounting" selected>Accounting</option>
                                <option value="assistant">Assistant</option>
                                <option value="penjualan">Penjualan</option>
                                <option value="pembelian">Pembelian</option>
                                <option value="owner">Owner</option>
                                <option value="programmer">Programmer</option>
                            </select>
                        </div>

                        <!-- Privilege -->
                        <div class="form-group">
                            <x-label for="privilege" :value="__('Privilege')" />
                            <select name="privilege" id="privilege" class="btn-group btn-block" style="padding: 10px">
                                <option value="superadmin" selected>Superadmin</option>
                                <option value="user">User</option>
                                <option value="view">View</option>
                            </select>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex justify-content-end align-items-baseline">
                                {{-- <a class="text-muted mr-3 text-decoration-none" href="{{ route('login') }}">
                                    {{ __('Already registered?') }}
                                </a> --}}

                                <x-button>
                                    {{ __('Tambah User') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('footer-scripts')
<script>   
    $(document).ready(function(){
        $('.date').datepicker({  
            dateFormat: 'dd-mm-yy'
        }); 

        $('#d_ti').keyup(function(){
            var res = $('#d_pj').val() * $('#d_lb').val() * $('#d_ti').val();
            if (res == Number.POSITIVE_INFINITY || res == Number.NEGATIVE_INFINITY || isNaN(res))
                res = "N/A"; // OR 0
            $('#kub').val(res);
        });
    });
</script>
@endsection
