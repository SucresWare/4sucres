@extends('layouts.app')

@section('title')
    {{ $discussion->title }}
@endsection

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h1>{{ $discussion->title }}</h1>
        </div>
        <div class="col-auto">
            @if (!$discussion->private)
                @if (auth()->check() && $discussion->subscribed()->wherePivot('user_id', user()->id)->count())
                    <a href="{{ route('discussions.unsubscribe', [$discussion->id, $discussion->slug]) }}" class="btn btn-outline-primary">Se désabonner</a>
                @else
                    <a href="{{ route('discussions.subscribe', [$discussion->id, $discussion->slug]) }}" class="btn btn-outline-primary">S'abonner</a>
                @endif
            @endif
        </div>
    </div>

    <div class="pb-0">
        {{ $posts->links() }}
    </div>

    <discussion :discussion-id="{{ $discussion->id }}" :initial-paginator="{{ $posts->toJson() }}"></discussion>

    <div class="pb-0">
        {{ $posts->links() }}
    </div>

    @if ($discussion->locked)
        <div class="alert alert-secondary text-center">
            <i class="fas fa-lock"></i> Cette discussion est désormais verrouillée.
        </div>
    @endif
    @if (!$discussion->locked || (auth()->check() && $discussion->locked && user()->can('bypass discussions guard')))
        <div class="card" id="reply">
            <div class="card-body bg-light" >
                <h2 class="h6">Répondre</h2>
                @include('discussion._reply')
            </div>
        </div>
    @endif
</div>

@endsection
