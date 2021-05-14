<?php

namespace App\Http\Controllers\Api;


use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Queries\TopicQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TopicRequest;
use App\Http\Resources\TopicResource;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;


class TopicsController extends Controller
{
    public function index(Request $request, Topic $topic, TopicQuery $query){
//        $query = $topic->query();
//        if($categoryId = $request->category_id){
//            $query->where('category_id', $categoryId);
//        }
//        $topics = $query
//            ->with('user', 'category')
//            ->withOrder($request->order)
//            ->paginate();
//        $topics = QueryBuilder::for(Topic::class)
//            ->allowedIncludes('user', 'category')
//            ->allowedFilters([
//                'title',
//                AllowedFilter::exact('category_id'),
//                AllowedFilter::scope('withOrder')->default('recentReplied'),
//            ])
//            ->paginate();
        $topics = $query->paginate();

        return TopicResource::collection($topics);
    }

    public function userIndex(Request $request, User $user, TopicQuery $query){
//        $query = $user->topics()->getQuery();
//
//        $topics = QueryBuilder::for($query)
//            ->allowedIncludes('user', 'category')
//            ->allowedFilters([
//                'title',
//                AllowedFilter::exact('category_id'),
//                AllowedFilter::scope('withOrder')->default('recentReplied'),
//            ])
//            ->paginate();
        $topics = $query->where('user_id', $user->id)->paginate();

        return TopicResource::collection($topics);
    }

    public function store(TopicRequest $request, Topic $topic){
//        return $this->errorResponse(403, '您还没用通过认证', 1003);
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }

    public function update(TopicRequest $request, Topic $topic){
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return new TopicResource($topic);
    }

    public function show($topicId, TopicQuery $query){
        $topic = $query->findOrFail($topicId);

        return new TopicResource($topic);
    }
    public function destroy(Topic $topic){
        $this->authorize('destroy', $topic);
        $topic->delete();

        return response(null, 204);
    }
}
