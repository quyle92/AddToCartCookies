<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Post as PostResource;
use Illuminate\Pagination\Paginator;
use DB;
use Illuminate\Database\Eloquent\Builder;
use App\Services\PaginationHelper;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::PostsWithMostComments();
        $showPerPage = 2;
        return response()->json([
          'data' => PaginationHelper::paginate($posts, $showPerPage) //(2)
        ]);
        return PostResource::collection(Post::paginate(10,['*'], 'page', 2));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    /**
     * get posts with comments_count and comments_counts_from_members_with_age_from_20_to_30
     * and total comments from them are > 30
     * @return [type] [description]
     */
    public function PostsWhoesMembersCommentFrom20To30()
    {
        $from = now()->subYears(30)->format('Y-m-d');
        $to = now()->subYears(20)->format('Y-m-d');
        // return $sub =  Post::withCount(['comments',
        //     'comments as comments_from_members_20_30' => function(Builder $query)
        //         use($from, $to ) {
        //         $query->join('members', 'comments.member_id', '=', 'members.id')
        //             ->whereBetween('dob', [
        //                 $from,
        //                 $to
        //             ]);
        //     }])->havingRaw('comments_from_members_20_30 > 30')
        //     ->get();

            //(1)
       // return
       //      DB::table( DB::raw("({$sub->toSql()}) as sub") )
       //      ->mergeBindings($sub->getQuery())
       //     ->where('comments_from_members_20_30', '>', 30)->get();

        return DB::table('posts')
                  ->join('comments', function ($join) {
                      $join->on('posts.id', '=', 'comments.commentable_id')
                            ->where('comments.commentable_type', '=', 'App\Post');
                       })
                  ->join('members', function ($join) use( $from, $to ) {
                      $join->on('comments.member_id', '=', 'members.id');
                  })
                  ->selectRaw("posts.id, COUNT(*) as comments_count, SUM(CASE WHEN dob between CAST(? AS DATE) and CAST(? AS DATE) THEN 1 ELSE 0 END) AS comments_from_members_20_30", [$from, $to])
                  ->groupBy('posts.id', 'posts.title' )
                  ->havingRaw('comments_from_members_20_30 > 30')
                  ->orderBy('posts.id')
                  ->get();







    }
}

/**
 * Note
 */
//(1)this is for subquery. Ref: https://stackoverflow.com/questions/24823915/how-to-select-from-subquery-using-laravel-query-builder
//(2)paginate() is \Builder method so it is for eloquent models and DB queries use only, and not collections ( which means Illuminate\Support\Collection or  Illuminate\Database\Eloquent\Collection) so we need to manually build custom paginator for it.
// Differcen b/t Eloquent Models and Eloquent Collection
// -Eloquent Models: User::where('votes', '>', 100) =>dùng dc paginate()
// -Eloquent Collection: User::where('votes', '>', 100)->get(); User::all() =>ko dùng dc paginate()
// (look at pagination and Eloquent ORM section of Laravel doc for details)
// Ref: https://stackoverflow.com/questions/30420505/how-can-i-paginate-a-merged-collection-in-laravel-5
