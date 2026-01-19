<?php

namespace DarksLight2\AiRequestsMonitoring\Controllers;

use DarksLight2\AiRequestsMonitoring\Models\AiRequestMonitoring;

class ShowController
{
    public function __invoke(string $id)
    {
        $req = AiRequestMonitoring::query()->find($id);

        if($req->operation === 'embedding') {
            return view('ai-monitor::show-embedding', [
                'request' => $req,
            ]);
        } else {
            return view('ai-monitor::show-completions', [
                'request' => $req,
                'messages' => $req->messages,
            ]);
        }
    }
}
