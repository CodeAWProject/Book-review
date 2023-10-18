<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Book extends Model
{
    use HasFactory;

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $title):Builder {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    } 

    public function scopePopular(Builder $query, $from = null, $to = null):Builder|QueryBuilder {

        return $query->withCount([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ])
            ->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null):Builder|QueryBuilder {
        return $query->withAvg([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], 'rating')->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder|QueryBuilder {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    private function dateRangeFilter(Builder $query, $from = null, $to = null ) {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    // We get  all the books that are most reviews from the last month until now. also it will return the average ratings and only the books that got at least two reviews
    public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }

    // We get  all the books that are most reviews from the 6 last months until now. also it will return the average ratings and only the books that got at least five reviews
    public function scopePopularLast6Month(Builder $query): Builder|QueryBuilder {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(5);
    }

    //The average rating and the amount of ratings from last month
    public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())     
            ->minReviews(2);
    }

    //from last 6 months
    public function scopeHighestRatedLast6Month(Builder $query): Builder|QueryBuilder {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(), now())     
            ->minReviews(5);
    }
    
}
