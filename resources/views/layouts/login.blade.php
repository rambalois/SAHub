@extends('layouts.app')

@section('title', 'Login')

@section('content')
        <section class="position-relative py-4 py-xl-5 mt-5">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6 col-xl-4">
                        <div class="card mb-5">
                            <div class="card-body d-flex flex-column align-items-center">
                                <h2 class="mb-5">{{ __('Login') }}</h2>

                                <form class="text-center" method="POST" action="{{ route('authenticate') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <input class="form-control" type="email" name="email" placeholder="{{ __('Email') }}">
                                        <span class="text-danger"> @error('id_number') {{$message}} @enderror</span>
                                    </div>

                                    <div class="mb-3">
                                        <input class="form-control" type="password" name="password" placeholder="{{ __('Password') }}">
                                        <span class="text-danger"> @error('password') {{$message}} @enderror</span>
                                    </div>

                                    <div class="mb-3">
                                        <button class="btn btn-primary d-block w-100" type="submit">{{ __('Login') }}</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection