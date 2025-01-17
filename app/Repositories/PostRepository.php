<?php declare(strict_types=1);

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Contracts\Database\Query\Builder;

final class PostRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Post();
    }

    public function getAll(?string $keyword = null)
    {
        return $this->model->query()
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
            ->orderBy('published_at', 'desc');
    }
}
