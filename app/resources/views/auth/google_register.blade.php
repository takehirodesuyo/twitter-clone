@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="mx-auto col col-12 col-sm-11 col-md-9 col-lg-7 col-xl-6">
            <div class="card mt-3">
                <div class="card-body text-center">
                    <h2 class="h3 card-title text-center mt-2">Google</h2>

                    <div class="card-text">
                        <form method="POST" action="{{ route('register.{provider}', ['provider' => $provider]) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class=" row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">名前</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                                </div>
                            </div>
                            <div class="text-danger">{{ $errors->first('name') }}</div>
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">メールアドレス</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">プロフィール画像</label>

                                <div class="col-md-6">
                                    <input type="file" name="profile_image" class="@error('profile_image') is-invalid @enderror" autocomplete="profile_image">
                                </div>
                            </div>
                            <div class="text-danger">{{ $errors->first('profile_image') }}</div>
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        新規登録
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection