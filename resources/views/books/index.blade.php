@extends('layouts.app')

@section('content')
    <h1 class="mb-10 text-2xl">Books</h1>

    <form method="GET" action="{{ route('books.index') }}" class="mb-4 flex items-center space-x-2">
        {{-- request('title') means that field will be filled with previous value --}}
        <input type="text" name="title" placeholder="Search by title" value="{{ request('title') }}" class="input h-10">
        <input type="hidden" name="filter" value="{{ request('filter') }}">
        <button type="submit" class="btn h-10">Search</button>
        <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>
    </form>

    <div class="filter-container mb04 flex">
        @php
            $filters = [
                '' => 'Latest',
                'popular_last_month' => 'Popular Last Mounth',
                'popular_last_6month' => 'Popular Last 6 Mounth',
                'highest_rated_last_month' => 'Highest Rated Last Mounth',
                'highest_rated_last_6month' => 'Highest Rated Last 6 Mounth'

            ];
        @endphp

        @foreach ($filters as $key => $label )
        {{-- if filter is selected style of it will be changed else then won't be --}}
            <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}" class="{{ request ('filter') === $key || request('filter') === null && $key === '' ? 'filter-item-active' : 'filter-item'}}">
                {{ $label }}
            </a>
        @endforeach

    </div>

    <ul>
        @forelse ($books as $book)
        <li class="mb-4">
            <div class="book-item">
              <div
                class="flex flex-wrap items-center justify-between">
                <div class="w-full flex-grow sm:w-auto">
                  <a href="{{route('books.show', $book)}}" class="book-title">{{$book->title}}</a>
                  <span class="book-author">by {{$book->author}}</span>
                </div>
                <div>
                  <div class="book-rating">
                    {{ number_format($book->reviews_avg_rating, 1) }}
                  </div>
                  <div class="book-review-count">
                    {{-- Plural function to display review or reviews based of the amount --}}
                    out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                  </div>
                </div>
              </div>
            </div>
          </li>
        @empty
        <li class="mb-4">
            <div class="empty-book-item">
              <p class="empty-text">No books found</p>

              {{-- Link without any parameters takes to empty page --}}
              <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
            </div>
          </li>
        @endforelse
    </ul>
@endsection