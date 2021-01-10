@extends('layouts.app')

@section('content')
<div class="container-lg">
    {{-- {{$latestCheck}} --}}
    <table class="table table-bordered table-hover text-nowrap">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Latest Check</th>
            <th>Status Code</th>
        </tr>
        </thead>
        <tbody>
        @foreach($domains as $domain)
            <tr>
                <td>{{ $domain->id }}</td>
                <td><a href="{{ route('domains.show', $domain->id) }}">{{ $domain->name }}</a></td>
                <td>{{ $latestCheck[$domain->id]->created_at ?? '' }}</td>
                <td>{{ $latestCheck[$domain->id]->status_code ?? '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
