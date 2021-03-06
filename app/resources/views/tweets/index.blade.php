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
<div class="container" style="background-color:#EDF7FF;">
    <div class="row justify-content-center">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('users.show', $user->id) }}"><img src="{{asset('storage/images/' .$user->profile_image)}}" class="d-block rounded-circle mb-3" width="170" height="150"></a>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('users.show', $user->id) }}" class="text-dark text-decoration-none h4">{{ $user->name }}</a>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="p-2 d-flex flex-column align-items-center">
                                    <p class="font-weight-bold">ツイート</p>
                                    <span class="text-primary font-weight-bold h3">{{ $tweetCount }}</span>
                                </div>
                                <div class="p-2 d-flex flex-column align-items-center">
                                    <p class="font-weight-bold">フォロー</p>
                                    <span class="text-primary font-weight-bold h3">{{ $followCount }}</span>
                                </div>
                                <div class="p-2 d-flex flex-column align-items-center">
                                    <p class="font-weight-bold">フォロワー</p>
                                    <span class="text-primary font-weight-bold h3">{{ $followerCount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-body">
                            <h2 class="card-title">フォロー中</h2>
                            @foreach ($followNames as $followName)
                            <div class="p-2 d-flex flex-column align-items-center">
                                <a href="{{ route('users.show', $followName->id) }}"><img src="{{asset('storage/images/'.$followName->profile_image)}}" class="d-block rounded-circle mb-3" width="160" height="170"></a>
                                <a href="{{ route('users.show', $followName->id) }}" class="text-dark text-decoration-none">{{ $followName->name }}</a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-5 mb-1">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('tweets.store') }}" enctype="multipart/form-data" onSubmit="return ThroughDblClick();">
                                @csrf
                                <div class="d-flex flex-row bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <a href="{{ route('users.show', $user->id) }}"><img src="{{asset('storage/images/' .$user->profile_image)}}" class="d-block rounded-circle mb-3" width="70" height="70"></a>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="text" autocomplete="text" rows="2" placeholder="いまどうしてる"></textarea>

                                        <div class="text-danger">{{ $errors->first('text') }}</div>
                                    </div>
                                </div>
                                <input type="file" name="img">
                                <div class="form-group row mb-0">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary">
                                            ツイートする
                                        </button>
                                    </div>
                                    <div class="text-danger">{{ $errors->first('img') }}</div>
                                </div>
                            </form>
                        </div>
                        @if (isset($timeLines))
                        @foreach ($timeLines as $timeLine)
                        <div class="container">
                            <div class="row">
                                <div class="card mb-3">
                                    <div>
                                        <a href="{{ route('users.show', $timeLine->user_id) }}"><img src="{{asset('storage/images/' .$timeLine->user->profile_image)}}" class="d-block rounded-circle mb-3" width="90" height="90"></a>
                                        <a href="{{ route('users.show', $timeLine->user_id) }}" class="text-dark text-decoration-none h5">{{ $timeLine->user->name }}</a>
                                        <a class="mb-0 text-secondary text-decoration-none text-right">{{ $timeLine->created_at->diffForHumans() }}</a>
                                    </div>
                                    {!! nl2br(e($timeLine->text)) !!}
                                    <!-- 写真 -->
                                    @if ($timeLine['image'])
                                    <img src="{{ '/storage/' . $timeLine['image'] }}" height="50%" width="100%">
                                    @endif
                                    <div class="mr-3 d-flex align-items-center btn">
                                        <!-- コメント -->
                                        <a href="{{ route('tweets.show', $timeLine->id  ) }}"><img src="/images/comment.jpg" width="30" height="30"></a>
                                        <p class="mb-0 text-secondary">{{ count($timeLine->comments) }}</p>
                                        <!-- いいね関連 -->
                                        <favorite :is-liked='@json($timeLine->isLiked($user))' :count-likes='@json($timeLine->count_likes)' :authorized='@json(Auth::check())' endpoint="{{ route('tweets.like', $timeLine->id) }}">
                                        </favorite>
                                    </div>
                                    @if ($timeLine->getTweetByUserIdAndAuthId())
                                    <div class="d-flex align-items-center btn">
                                        <form method="POST" action="{{ route('tweets.destroy', $timeLine->id) }}" class="mb-0" onSubmit="return ThroughDblClick();">
                                            @csrf
                                            @method('DELETE')

                                            <a href="{{ route('tweets.edit', $timeLine->id, 'edit') }}" class="btn btn-outline-success">編集</a>
                                            <button type="submit" class="btn btn-outline-danger">削除</button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">フォロワー</h2>
                            @foreach ($followerNames as $followerName)
                            <div class="p-2 d-flex flex-column align-items-center">
                                <a href="{{ route('users.show', $followerName->id) }}"><img src="{{asset('storage/images/'.$followerName->profile_image)}}" class="d-block rounded-circle mb-3" width="160" height="170"></a>
                                <a href="{{ route('users.show', $followerName->id) }}" class="text-dark text-decoration-none">{{ $followerName->name }}</a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="my-4 d-flex justify-content-center">
        {{ $timeLines->links('pagination::bootstrap-4') }}
    </div>

</div>

@endsection