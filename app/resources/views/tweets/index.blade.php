@extends('layouts.app')

<script>
    let dblClickFlag = null;

    function ThroughDblClick() {
        // ダブルクリック防止
        if (dblClickFlag == null) {
            dblClickFlag = 1;
            return true;
        } else {
            return false;
        }
    }
</script>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('tweets.store') }}" onSubmit="return ThroughDblClick();">
                                @csrf
                                <div class="form-group row mb-0">
                                    <div class="col-md-12 p-3 w-100 d-flex">
                                        <div class="ml-2 d-flex flex-column">
                                            <p class="mb-0">{{ $user->name }}</p>
                                            <a href="{{ route('users.show', $user->id) }}" class="text-secondary">プロフィール</a>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <textarea class="form-control @error('text') is-invalid @enderror" name="text" required autocomplete="text" rows="4">{{ old('text') }}</textarea>

                                        @error('text')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary">
                                            ツイートする
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($TimeLines))
        @foreach ($TimeLines as $TimeLine)
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-haeder p-3 w-100 d-flex">
                    <div class="ml-2 d-flex flex-column">
                        <a href="{{ route('users.show', $TimeLine->user_id) }}" class="text-secondary">{{ $TimeLine->user->name }}</a>
                        <p class="mb-0 text-secondary">{{ $TimeLine->created_at->format('Y年m月d日 H:i') }}</p>
                        <div class="card-body">
                            {!! nl2br(e($TimeLine->text)) !!}
                        </div>
                    </div>
                </div>
                <div class="mr-3 d-flex align-items-center btn">
                    <a href="{{ route('tweets.show', $TimeLine->id  ) }}" class="btn btn-secondary">コメントする</a>
                </div>
                @if ($TimeLine->user->id === Auth::user()->id)
                <div class="mr-3 d-flex align-items-center btn">
                    <form method="POST" action="{{ route('tweets.destroy', $TimeLine->id) }}" class="mb-0" onSubmit="return ThroughDblClick();">
                        @csrf
                        @method('DELETE')

                        <a href="{{ route('tweets.edit', $TimeLine->id, 'edit') }}" class="btn btn-outline-success">編集</a>
                        <button type="submit" class="btn btn-outline-danger">削除</button>
                    </form>
                </div>
                @endif
                <div class="mr-3 d-flex align-items-center">
                    <a href="{{ url('tweets/' .$TimeLine->id) }}"><i class="far fa-comment fa-fw"></i></a>
                    <p class="mb-0 text-secondary">コメント数：{{ count($TimeLine->comments) }}</p>
                </div>
                <div class="d-flex align-items-center">
                    <button type="" class="btn p-0 border-0 text-primary"><i class="far fa-heart fa-fw"></i></button>
                    <p class="mb-0 text-secondary">いいね数：{{ count($TimeLine->favorites) }}</p>
                </div>
                <!-- いいね関連 -->
                <div class="d-flex align-items-center">
                    @if (!in_array($user->id, array_column($TimeLine->favorites->toArray(), 'user_id'), TRUE))
                    <form method="POST" action="{{ url('favorites/') }}" class="mb-0">
                        @csrf

                        <input type="hidden" name="tweet_id" value="{{ $TimeLine->id }}">
                        <button type="submit" class="btn p-0 border-0 text-primary">いいね<i class="far fa-heart fa-fw"></i></button>
                    </form>
                    @else
                    <form method="POST" action="{{ url('favorites/' .array_column($TimeLine->favorites->toArray(), 'id', 'user_id')[$user->id]) }}" class="mb-0">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn p-0 border-0 text-danger">いいね解除</i></button>
                    </form>
                    @endif
                    <p class="mb-0 text-secondary">{{ count($TimeLine->favorites) }}</p>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    <div class="my-4 d-flex justify-content-center">
        {{ $TimeLines->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection