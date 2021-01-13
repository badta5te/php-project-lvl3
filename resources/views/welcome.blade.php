@extends('layouts.app')

@section('content')
<div class="flex-center position-ref full-height">
    <div class="container-lg">
        <div class="row">
            <div class="col-12 col-md-10 col-lg-8 mx-auto">
                <h1 class="display-3">Page Analyzer</h1>
                <p class="lead">Check web pages for free</p>
                <form action="{{ route('domains.store') }}" method="post"
                    class="d-flex justify-content-center">
                    @csrf
                    <input type="text" name="domain[name]" value="" class="form-control form-control-lg"
                        placeholder="https://www.example.com" required>
                    <button type="submit" class="btn btn-lg btn-primary ml-1 px-5 text-uppercase">Check</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
