@extends('layouts.app')

@section('content')
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-3 text-center">
            <a href="{{ url('users') }}">ユーザ一覧 <i class="fas fa-users" class="fa-fw"></i> </a>
        </div>
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
                                            <a href="{{ url('users/' .$user->id) }}" class="text-secondary">プロフィール</a>
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
                                        <p class="mb-4 text-danger">140文字以内</p>
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
        @if (isset($timelines))
            @foreach ($timelines as $timeline)
                <div class="col-md-8 mb-3">
                    <div class="card">
                        <div class="card-haeder p-3 w-100 d-flex">
                            <div class="ml-2 d-flex flex-column">
                                <a href="{{ url('users/' .$timeline->user->id) }}" class="text-secondary">{{ $timeline->user->name }}</a>
                                <p class="mb-0 text-secondary">{{ $timeline->created_at->format('Y年m月d日 H:i') }}</p>
                                <div class="card-body">
                                    {!! nl2br(e($timeline->text)) !!}
                                </div>
                            </div>
                        </div>
                            @if ($timeline->user->id === Auth::user()->id)
                                <div class="mr-3 d-flex align-items-center">
                                    <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-fw"></i>
                                    </a>
                                        <form method="POST" action="{{ url('tweets/' .$timeline->id) }}" class="mb-0">
                                            @csrf
                                            @method('DELETE')
                                            
                                            <a href="{{ url('tweets/' .$timeline->id .'/edit') }}" class="btn btn-outline-success">編集</a>
                                            <button type="submit" class="btn btn-outline-danger">削除</button>
                                        </form>
                                </div>
                            @endif
                            <div class="mr-3 d-flex align-items-center">
                                <a href="{{ url('tweets/' .$timeline->id) }}"><i class="far fa-comment fa-fw"></i></a>
                                <p class="mb-0 text-secondary">{{ count($timeline->comments) }}</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <button type="" class="btn p-0 border-0 text-primary"><i class="far fa-heart fa-fw"></i></button>
                                <p class="mb-0 text-secondary">{{ count($timeline->favorites) }}</p>
                            </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="my-4 d-flex justify-content-center">
        {{ $timelines->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection