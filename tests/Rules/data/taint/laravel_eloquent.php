<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
    new Response($article->label); // safe: a declared property, not an attribute
    new Response($article->getTable()); // safe
}
