@extends('layouts.layout')

@section('content')
<div class="container mt-5">
    <h1 class="text-center">{{ trans('expertise.title') }}</h1>
    <div class="row">
        @foreach($expertises as $expertise)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $expertise->name }}</h5>
                        <p class="card-text">{{ $expertise->description }}</p>
                        <a href="{{ route('expertise.detail', $expertise->id) }}" class="btn btn-primary">{{ trans('expertise.learn_more') }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection