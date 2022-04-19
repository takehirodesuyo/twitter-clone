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
    <div class="row justify-content-center mb-2">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-haeder p-3 w-100 d-flex">
                    <div class="ml-2 d-flex flex-column">
                        <img src="{{asset('storage/images/' .$tweet->user->profile_image)}}" class="d-block rounded-circle mb-3" width="90" height="90">
                        <p class="mb-0">{{ $tweet->user->name }}</p>
                    </div>
                    <div class="d-flex justify-content-end flex-grow-1">
                        <p class="mb-0 text-secondary">{{ $tweet->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="card-body">
                    {!! nl2br(e($tweet->text)) !!}
                </div>

                @if ($tweet['image'])
                <img src="{{ '/storage/' . $tweet['image'] }}" height="50%" width="100%">
                @endif
                @if ($tweet->user->id === Auth::user()->id)
                <div class="mr-3 d-flex align-items-center btn">
                    <form method="POST" action="{{ url('tweets/' .$tweet->id) }}" class="mb-0" onSubmit="return ThroughDblClick();">
                        @csrf
                        @method('DELETE')

                        <a href="{{ url('tweets/' .$tweet->id .'/edit') }}" class="btn btn-outline-success">編集</a>
                        <button type="submit" class="btn btn-outline-danger">削除</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 mb-3">
            <ul class="list-group">
                @forelse ($comments as $comment)
                <li class="list-group-item">
                    <div class="py-3 w-100 d-flex">
                        <div class="ml-2 d-flex flex-column">
                            <img src="{{asset('storage/images/' .$comment->user->profile_image)}}" class="d-block rounded-circle mb-3" width="90" height="90">
                            <a href="{{ route('users.show' ,$comment->user->id) }}" class="text-secondary text-decoration-none">{{ $comment->user->name }}</a>
                        </div>
                        <div class="d-flex justify-content-end flex-grow-1">
                            <p class="mb-0 text-secondary">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="py-3">
                        {!! nl2br(e($comment->text)) !!}
                    </div>
                </li>
                @empty
                @endforelse
                <li class="list-group-item">
                    <div class="py-3">
                        <form method="POST" action="{{ route('comments.store') }} " onSubmit="return ThroughDblClick();">
                            @csrf

                            <div class="form-group row mb-0">
                                <div class="col-md-12 p-3 w-100 d-flex">
                                    <div class="ml-2 d-flex flex-column">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="tweet_id" value="{{ $tweet->id }}">
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
                                    <button type="submit" class="btn btn-secondary">
                                        コメントする
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection