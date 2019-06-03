@extends('layouts.app')

@section('title')
    Paramètres
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-3 col-xl-2 mb-3">
            @include('user.settings._navigation')
        </div>
        <div class="col-lg-7 col-xl-8">
            <form method="POST" action="{{ route('user.settings.layout', []) }}" enctype='multipart/form-data' id="settings">
                @method('put')
                @csrf

                <div class="card mb-3">
                    <div class="card-header">
                        Paramètres d'affichage
                    </div>
                    <div class="card-body dimmed-border-bottom">
                        <div class="form-group px-4">
                            <div class="row justify-content-center mb-2">
                                <label for="sidebar" class="form-label font-weight-bold text-uppercase">Position de la barre latérale</label>
                            </div>
                            <div class="row">
                                <div class="custom-control custom-radio flex-center col mb-3 mb-md-0">
                                    <input name="sidebar" id="sidebar_left" type="radio" value="left" {{ old('sidebar', $user->getSetting('layout.sidebar', 'left')) == "left" ? 'checked' : '' }} class="custom-control-input">
                                    <label for="sidebar_left" class="custom-control-label"><img src="{{ url('/img/settings/layout.sidebar.left.png') }}" class="img-fluid rounded" style="width: 300px"></label>
                                </div>
                                <div class="custom-control custom-radio flex-center col pr-0">
                                    <input name="sidebar" id="sidebar_right" type="radio" value="right" {{ old('sidebar', $user->getSetting('layout.sidebar', 'left')) == "right" ? 'checked' : '' }} class="custom-control-input">
                                    <label for="sidebar_right" class="custom-control-label"><img src="{{ url('/img/settings/layout.sidebar.right.png') }}" class="img-fluid rounded" style="width: 300px"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group px-4">
                            <div class="row justify-content-center mb-2">
                                <label for="stickers" class="form-label font-weight-bold text-uppercase">Adaptation de la taille des stickers Risidex</label>
                            </div>
                            <div class="row">
                                <div class="custom-control custom-radio flex-center col mb-3 mb-md-0">
                                    <input name="stickers" id="default" type="radio" value="default" {{ old('stickers', $user->getSetting('layout.stickers', 'default')) == "default" ? 'checked' : '' }} class="custom-control-input">
                                    <label for="default" class="custom-control-label"><img src="{{ url('/img/settings/layout.stickers.default.png') }}" class="img-fluid rounded" style="width: 300px"></label>
                                    <small id="stickers_help" class="form-text text-muted mt-2">
                                        Cette disposition permet d'afficher les stickers agrandis par défaut.
                                    </small>
                                </div>
                                <div class="custom-control custom-radio flex-center col pr-0">
                                    <input name="stickers" id="stickers_inline" type="radio" value="inline" {{ old('stickers', $user->getSetting('layout.stickers', 'default')) == "inline" ? 'checked' : '' }} class="custom-control-input">
                                    <label for="stickers_inline" class="custom-control-label"><img src="{{ url('/img/settings/layout.stickers.inline.png') }}" class="img-fluid rounded" style="width: 300px"></label>
                                    <small id="stickers_inline_help" class="form-text text-muted mt-2">
                                        Cette disposition permet d'agrandir la taille des stickers au survol de la souris.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
