@extends('dashboards.users.layouts.user-dash-layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>{{ trans('dashboard.Whoops!') }}</strong> {{ trans('dashboard.There were some problems with your input.') }}<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card" style="padding: 16px;">
            <div class="card-body">
                <h4 class="card-title">{{ trans('dashboard.Edit Book Detail') }}</h4>
                <p class="card-description">{{ trans('dashboard.Enter Book Information') }}</p>
                <form class="forms-sample" action="{{ route('books.update', $book->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group row">
                        <label for="exampleInputac_name" class="col-sm-3 col-form-label">{{ trans('dashboard.Book Name') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="ac_name" value="{{ $book->ac_name }}" class="form-control" placeholder="{{ trans('dashboard.Book Name') }}">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="exampleInputac_sourcetitle" class="col-sm-3 col-form-label">{{ trans('dashboard.Place of Publication') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="ac_sourcetitle" value="{{ $book->ac_sourcetitle }}" class="form-control" placeholder="{{ trans('dashboard.Place of Publication') }}">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="exampleInputac_year" class="col-sm-3 col-form-label">{{ trans('dashboard.Publication Year (B.E.)') }}</label>
                        <div class="col-sm-9">
                            <input type="date" name="ac_year" value="{{ $book->ac_year }}" class="form-control" placeholder="{{ trans('dashboard.Publication Year (B.E.)') }}">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="exampleInputac_page" class="col-sm-3 col-form-label">{{ trans('dashboard.Page (Count)') }}</label>
                        <div class="col-sm-9">
                            <input type="text" name="ac_page" value="{{ $book->ac_page }}" class="form-control" placeholder="{{ trans('dashboard.Page (Count)') }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">{{ trans('dashboard.Submit') }}</button>
                    <a class="btn btn-light" href="{{ route('books.index') }}">{{ trans('dashboard.Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
