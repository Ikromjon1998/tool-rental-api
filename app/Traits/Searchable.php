<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Searchable
{
    /**
     * Apply search scope to the query.
     *
     * @param Builder<Model> $query
     * @param string|null $term
     * @return Builder<Model>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        // Return immediately if no search term
        if (empty($term = trim($term))) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            // Process each word in the search term
            foreach ($this->normalizeSearchTerms($term) as $word) {
                $q->where(function ($q) use ($word) {
                    // Search in all searchable fields
                    foreach ($this->getSearchableFields() as $field) {
                        $this->applySearchCondition($q, $field, $word);
                    }
                });
            }
        });
    }

    /**
     * Normalize search terms by splitting and cleaning.
     */
    protected function normalizeSearchTerms(string $term): array
    {
        return array_filter(
            array_map('trim', preg_split('/\s+/', $term)),
            fn($word) => !empty($word)
        );
    }

    /**
     * Get searchable fields with fallback to model property.
     */
    protected function getSearchableFields(): array
    {
        return property_exists($this, 'searchable')
            ? $this->searchable
            : [];
    }

    /**
     * Apply search condition for a single field.
     */
    protected function applySearchCondition(Builder $query, string $field, string $word): void
    {
        if (Str::contains($field, '.')) {
            $this->applyRelationSearch($query, $field, $word);
        } else {
            $this->applyDirectSearch($query, $field, $word);
        }
    }

    /**
     * Apply search condition for a relationship field.
     */
    protected function applyRelationSearch(Builder $query, string $field, string $word): void
    {
        $parts = explode('.', $field);
        $column = array_pop($parts);
        $relation = implode('.', $parts);

        $query->orWhereHas($relation, function ($q) use ($column, $word) {
            $this->applyDirectSearch($q, $column, $word);
        });
    }

    /**
     * Apply direct field search condition.
     */
    protected function applyDirectSearch(Builder $query, string $field, string $word): void
    {
        $query->orWhere($field, 'ilike', "%{$word}%");
    }
}
