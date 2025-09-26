<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;

class SetContentCompletion extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'content_id' => 'required|integer|exists:contents,id',
            'completed' => 'boolean'
        ]);

        Content::where('id', $data['content_id'])->update([
            'completed' => $data['completed']
        ]);
    }
}