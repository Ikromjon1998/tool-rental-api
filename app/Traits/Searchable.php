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
     * @param  Builder<Model>  $query
     * @return Builder<Model>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term = trim($term))) {
            return $query;
        }

        // Get searchable fields from model instance
        $model = $query->getModel();
        $fields = property_exists($model, 'searchable') ? $model->searchable : [];

        return $query->where(function ($q) use ($term, $fields) {
            foreach ($this->normalizeSearchTerms($term) as $word) {
                $q->where(function ($q) use ($word, $fields) {
                    foreach ($fields as $field) {
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
            fn ($word) => ! empty($word)
        );
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
