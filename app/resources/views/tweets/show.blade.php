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
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-haeder p-3 w-100 d-flex">
                    <div class="ml-2 d-flex flex-column">
                        <p class="mb-0">{{ $Tweet->user->name }}</p>
                    </div>
                    <div class="d-flex justify-content-end flex-grow-1">
                        <p class="mb-0 text-secondary">{{ $Tweet->created_at->format('Y年m月d日 H:i') }}</p>
                    </div>
                </div>
                <div class="card-body">
                    {!! nl2br(e($Tweet->text)) !!}
                </div>
                @if ($Tweet->user->id === Auth::user()->id)
                <div class="mr-3 d-flex align-items-center btn">
                    <form method="POST" action="{{ url('tweets/' .$Tweet->id) }}" class="mb-0" onSubmit="return ThroughDblClick();">
                        @csrf
                        @method('DELETE')

                        <a href="{{ url('tweets/' .$Tweet->id .'/edit') }}" class="btn btn-outline-success">編集</a>
                        <button type="submit" class="btn btn-outline-danger">削除</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 mb-3">
            <ul class="list-group">
                @forelse ($Comments as $Comment)
                <li class="list-group-item">
                    <div class="py-3 w-100 d-flex">
                        <div class="ml-2 d-flex flex-column">
                            <p class="mb-0">{{ $Comment->user->name }}</p>
                            <a href="{{ route('users.show' ,$Comment->user->id) }}" class="text-secondary">{{ $Comment->user->name }}</a>
                        </div>
                        <div class="d-flex justify-content-end flex-grow-1">
                            <p class="mb-0 text-secondary">{{ $Comment->created_at->format('Y年m月d日 H:i') }}</p>
                        </div>
                    </div>
                    <div class="py-3">
                        {!! nl2br(e($Comment->text)) !!}
                    </div>
                </li>
                @empty
                <li class="list-group-item">
                    <p class="mb-0 text-secondary">コメントはまだありません。</p>
                </li>
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
                                    <input type="hidden" name="tweet_id" value="{{ $Tweet->id }}">
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
                                    <p class="mb-4 text-danger">140文字以内</p>
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