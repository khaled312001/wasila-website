<?php

namespace App\Http\Controllers;

use App\Models\OrderDocumentation;

class DocumentationShareController extends Controller
{
    public function show(OrderDocumentation $documentation)
    {
        $documentation->load('order');

        if (!$documentation->viewed_at) {
            $documentation->forceFill(['viewed_at' => now()])->save();
        }

        return view('documentation.share', [
            'documentation' => $documentation,
            'order' => $documentation->order,
        ]);
    }
}
