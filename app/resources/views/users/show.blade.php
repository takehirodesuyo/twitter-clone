@extends('layouts.app')

<script>
    var dblClickFlag = null;

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
        <div class="col-md-7 mb-3">
            <div class="card">
                <div class="d-inline-flex">
                    <div class="p-3 d-flex flex-column">
                        <div class="mt-3 d-flex flex-column">
                            <h4 class="mb-0 font-weight-bold">{{ $user->name }}</h4>
                            <img src="{{asset('storage/images/' .$user->profile_image)}}" class="d-block rounded-circle mb-3" width="160" height="170">
                        </div>
                    </div>
                    <div class="p-3 d-flex flex-column justify-content-between">
                        <div class="d-flex">
                            <div>
                                @if ($user->id === Auth::user()->id)
                                <a href="{{ route('users.edit', $user->id ) }}" class="btn btn-primary">プロフィールを編集する</a>
                                @else
                                @if ($isFollowing)
                                <form action="{{ route('unfollow', $user->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}

                                    <button type="submit" class="btn btn-danger">フォロー解除</button>
                                </form>
                                @else
                                <form action="{{ route('follow', $user->id) }}" method="POST">
                                    {{ csrf_field() }}

                                    <button type="submit" class="btn btn-primary">フォローする</button>
                                </form>
                                @endif

                                @if ($isFollowed)
                                <span class="mt-2 px-1 bg-secondary text-light">フォローされています</span>
                                @endif
                                @endif
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="p-2 d-flex flex-column align-items-center">
                                <p class="font-weight-bold">ツイート数</p>
                                <span class="text-primary font-weight-bold h3">{{ $tweetCount }}</span>
                            </div>
                            <div class="p-2 d-flex flex-column align-items-center">
                                <p class="font-weight-bold">フォロー数</p>
                                <span class="text-primary font-weight-bold h3">{{ $followCount }}</span>
                            </div>
                            <div class="p-2 d-flex flex-column align-items-center">
                                <p class="font-weight-bold">フォロワー数</p>
                                <span class="text-primary font-weight-bold h3">{{ $followerCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (isset($timeLines))
            @foreach ($timeLines as $timeLine)
            <div class="card mt-2">
                <div>
                    <img src="{{asset('storage/images/' .$timeLine->user->profile_image)}}" class="d-block rounded-circle mb-3" width="90" height="90">
                    <a href="{{ route('users.show', $timeLine->user_id) }}" class="text-dark text-decoration-none h5">{{ $timeLine->user->name }}</a>
                    <a class="mb-0 text-secondary text-decoration-none text-right">{{ $timeLine->created_at->diffForHumans() }}</a>
                </div>
                {!! nl2br(e($timeLine->text)) !!}
                <!-- 写真 -->
                @if ($timeLine['image'])
                <img src="{{ '/storage/' . $timeLine['image'] }}" height="40%" width="100%">
                @endif
                <div class="mr-3 d-flex align-items-center btn">
                    <!-- コメント -->
                    <a href="{{ route('tweets.show', $timeLine->id  ) }}"><img src="/images/comment.jpg" width="30" height="30"></a>
                    <p class="mb-0 text-secondary">{{ count($timeLine->comments) }}</p>
                    <!-- いいね関連 -->
                    <div class="d-flex align-items-center">
                        @if (!in_array($user->id, array_column($timeLine->favorites->toArray(), 'user_id'), TRUE))
                        <form method="POST" action="{{ url('favorites/') }}" class="mb-0">
                            @csrf

                            <input type="hidden" name="tweet_id" value="{{ $timeLine->id }}">
                            <button type="submit" class="btn p-0 border-0 text-primary"><img src="/images/heart_1.png" width="30" height="30"><i class="far fa-heart fa-fw"></i></button>
                        </form>
                        @else
                        <form method="POST" action="{{ url('favorites/' .array_column($timeLine->favorites->toArray(), 'id', 'user_id')[$user->id]) }}" class="mb-0">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn p-0 border-0 text-danger"><img src="/images/heart_2.jpeg" width="30" height="30"></i></button>
                        </form>
                        @endif
                        <p class="mb-0 text-secondary">{{ count($timeLine->favorites) }}</p>
                    </div>
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
            @endforeach
            @endif
        </div>

    </div>
    <div class="my-4 d-flex justify-content-center">
        {{ $timeLines->links() }}
    </div>
</div>
@endsection