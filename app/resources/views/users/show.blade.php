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
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="d-inline-flex">
                    <div class="p-3 d-flex flex-column">
                        <div class="mt-3 d-flex flex-column">
                            <h4 class="mb-0 font-weight-bold">{{ $user->name }}</h4>
                        </div>
                    </div>
                    <div class="p-3 d-flex flex-column justify-content-between">
                        <div class="d-flex">
                            <div>
                                @if ($user->id === Auth::user()->id)
                                <a href="{{ route('users.edit', $user->id ) }}" class="btn btn-primary">プロフィールを編集する</a>
                                @else
                                @if ($IsFollowing)
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

                                @if ($IsFollowed)
                                <span class="mt-2 px-1 bg-secondary text-light">フォローされています</span>
                                @endif
                                @endif
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="p-2 d-flex flex-column align-items-center">
                                <p class="font-weight-bold">ツイート数</p>
                                <span>{{ $TweetCount }}</span>
                            </div>
                            <div class="p-2 d-flex flex-column align-items-center">
                                <p class="font-weight-bold">フォロー数</p>
                                <span>{{ $FollowCount }}</span>
                            </div>
                            <div class="p-2 d-flex flex-column align-items-center">
                                <p class="font-weight-bold">フォロワー数</p>
                                <span>{{ $FollowerCount }}</span>
                            </div>
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
                    <div class="ml-2 d-flex flex-column flex-grow-1">
                        <p class="mb-0">{{ $TimeLine->user->name }}</p>
                        <p class="mb-0 text-secondary">{{ $TimeLine->created_at->format('Y年m月d日 H:i') }}</p>
                    </div>
                </div>
                <div class="card-body">
                    {{ $TimeLine->text }}
                </div>
                @if ($TimeLine->user->id === Auth::user()->id)
                <div class="mr-3 d-flex align-items-center">
                    <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-fw"></i>
                    </a>
                    <form method="POST" action="{{ url('tweets/' .$TimeLine->id) }}" class="mb-0" onSubmit="return ThroughDblClick();">
                        @csrf
                        @method('DELETE')
                        <a href="{{ url('tweets/' .$TimeLine->id .'/edit') }}" class="btn btn-outline-success">編集</a>
                        <button type="submit" class="btn btn-outline-danger">削除</button>
                    </form>
                </div>
                @endif
                <div class="mr-3 d-flex align-items-center">
                    <a href="{{ url('tweets/' .$TimeLine->id) }}"><i class="far fa-comment fa-fw"></i></a>
                    <p class="mb-0 text-secondary">コメント数：{{ count($TimeLine->comments) }}</p>
                </div>
                <div class="d-flex align-items-center">
                    @if (!in_array(Auth::user()->id, array_column($TimeLine->favorites->toArray(), 'user_id'), TRUE))
                    <form method="POST" action="{{ url('favorites/') }}" class="mb-0">
                        @csrf

                        <input type="hidden" name="tweet_id" value="{{ $TimeLine->id }}">
                        <button type="submit" class="btn p-0 border-0 text-primary">いいね</button>
                    </form>
                    @else
                    <form method="POST" action="{{ url('favorites/' .array_column($TimeLine->favorites->toArray(), 'id', 'user_id')[Auth::user()->id]) }}" class="mb-0">
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
        {{ $TimeLines->links() }}
    </div>
</div>
@endsection