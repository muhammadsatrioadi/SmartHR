<!-- resources/views/home.blade.php -->
@extends('layouts.main')

@section('title', 'Home')
@section('navDashboard')

@endsection
@section('content')
                    
                    <div class="container-fluid ">
                        <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                            <div class="col-30 col-sm-16 col-md-10 col-lg-15 col-xl-14">
                                <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                       <!--  <a href="index.html" class="">-->
                                            <h3 class="text-primary"><i class="fa fa-user-edit me-1 "></i>PROFILE</h3>
                                        <!-- </a> -->
                                    </div>
            
                                    <div class="col-sm-12 col-xl-12">
                                        <div class="bg-secondary rounded h-100 p-4">
                                            <div class="">
                                                <div class="testimonial-item text-center">
                                                    <img class="img-fluid rounded-circle mx-auto mb-4 w-25" src="img/{{ $data->imgProfile }}" >
                                                    <h5 class="mb-1">Admin Name</h5>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                    <form action="{{ route('actionAdminUpdate') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" name="name" value="{{ $data->name }}" placeholder="name@example.com">
                                            <label for="floatingInput">NAMA ANDA</label>
                                        </div>
                                        <div class="form-floating mb-4">
                                            <input type="email" class="form-control" id="floatingPassword" name="email" value="{{ $data->email }}" placeholder="Password">
                                            <label for="floatingPassword">Email</label>
                                        </div>
                                        <div class="form-floating mb-4">
                                            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="oldPassword" value="">
                                            <label for="floatingPassword">Old Password</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" id="floatingInput" placeholder="name@example.com" name="password">
                                            <label for="floatingInput">New Password</label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="imgProfile" class="form-label">Profile Image</label>
                                            <input class="form-control" type="file" id="imgProfile" name="imgProfile">
                                        </div>
                                        <button type="submit" class="btn btn-primary py-3 w-100 mb-4">SIMPAN</button>
                                        <p class="text-center mb-0">batalkan ? <a href="">BACK</a></p>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                
@endsection

