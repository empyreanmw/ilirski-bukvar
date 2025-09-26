<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;

class SetLastWatchedController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'content_id' => 'required|integer|exists:contents,id',
            'last_watched' => 'boolean'
        ]);

        $content = Content::where('id', $data['content_id'])->first();

        Content::where('parent_id', $content->parent_id)
            ->where('parent_type', $content->parent_type)
            ->update([
                'last_watched' => false
        ]);

        Content::where('id', $data['content_id'])->update([
            'last_watched' => $data['last_watched'],
            'completed' => true
        ]);
    }
}