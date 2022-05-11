@extends('errors::minimal')
@section('title', __('Not Found'))
<main>
    <div style="text-align:center">
        <h1>ページが見つかりません</h1>
        <a href="{{ url('/tweets') }}">トップに戻る</a>
    </div>
</main>