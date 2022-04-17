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
        <div class="col-md-8">
            @foreach ($all_users as $user)
            <div class="card">
                <div class="card-haeder p-3 w-100 d-flex">
                    <div class="ml-2 d-flex flex-column">
                        <a href="{{ route('users.show', $user->id) }}" class="text-secondary text-dark text-decoration-none">{{ $user->name }}</a>
                        <img src="{{asset('storage/images/'.$user->profile_image)}}" class="d-block rounded-circle mb-3" width="160" height="170">
                    </div>
                    @if (auth()->user()->isFollowed($user->id))
                    <div class="px-2">
                        <span class="px-1 bg-secondary text-light">フォローされています</span>
                    </div>
                    @endif
                    <div class="d-flex flex-grow-1">
                        @if (auth()->user()->isFollowing($user->id))
                        <form action="{{ route('unfollow', $user->id ) }}" onSubmit="return ThroughDblClick();" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" class="btn btn-danger">フォロー解除</button>
                        </form>
                        @else
                        <form action="{{ route('follow', $user->id ) }}" onSubmit="return ThroughDblClick();" method="POST">
                            {{ csrf_field() }}

                            <button type="submit" class="btn btn-primary">フォローする</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="my-4 d-flex justify-content-center">
        {{ $all_users->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection