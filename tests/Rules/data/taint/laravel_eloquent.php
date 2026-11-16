<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * @property string $id
 * @property string $author_uuid
 * @property int $views
 * @property ?bool $published
 * @property string $slug
 */
class Article extends Model
{
    public string $label = 'article';
}

function storedValues(Article $article, ?Article $maybe): void
{
    new Response($article->body); // unsafe
    new Response($maybe?->body); // unsafe
    new Response($article->getAttribute('body')); // unsafe
    DB::select('select * from articles order by ' . $article->getOriginal('sort')); // unsafe
    new Response($article->slug); // unsafe: a string attribute
    new Response($article->label); // safe: a declared property, not an attribute
    new Response($article->getTable()); // safe
}

function keysAndTypedAttributes(Article $article): void
{
    DB::select("select * from articles where id = '{$article->id}'"); // safe: primary key
    DB::select("select * from users where id = '{$article->user_id}'"); // safe: foreign key
    DB::select("select * from users where uuid = '{$article->author_uuid}'"); // safe: foreign key
    DB::select("select * from articles limit {$article->views}"); // safe: @property int
    DB::select("select * from articles where published = {$article->published}"); // safe: @property ?bool
    DB::select("select * from articles where body = '{$article->video_ids}'"); // unsafe: doesn't end in _id
}
