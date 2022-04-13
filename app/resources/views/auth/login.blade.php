@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 card bg-info text-black">
        <div class="center-block">
            <img src="/images/twitter_1.svg" width="400" height="700" class="d-block mx-auto">
        </div>
    </div>
    <div class="col-md-6">
        <img src="/images/twitter.svg" width="50" height="70">
        <div class="display-1">
            すべての話題が、ここに。
        </div>
        <div class="h1">
            Twitterをはじめよう
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="email" class="col-md-2 col-form-label text-md-end">メールアドレス</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div>
                <label for="password" class="col-md-2 col-form-label text-md-end">パスワード</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            ログイン状態にする
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        ログイン
                    </button>

                    @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        パスワードを忘れた方
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
@endsection