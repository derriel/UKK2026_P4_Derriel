@extends('layouts.app')

@section('content')
<h1>Hasil Pencarian</h1>

@forelse($books as $book)
    <div>{{ $book->title }}</div>
@empty
    <p>Tidak ada hasil</p>
@endforelse

@endsection