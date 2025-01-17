<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;

final class BlogService
{
    public function getAllPostsWithCommentCount(?string $keyword = null, int $offset = 0, int $limit = 10): Collection
    {
        return Post::query()
            ->with(['user'])
            ->whereHas('comments', function (Builder $query) {
                $query->where('is_approved', true);
            })
            ->when($keyword, function (Builder $query) use ($keyword) {
                return $query->keyword($keyword);
            })
            ->withCount(['comments' => function (Builder $query) {
                $query->where('is_approved', true);
            }])
            ->orderBy('published_at', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();
    }
}
