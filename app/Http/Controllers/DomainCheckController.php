<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

class DomainCheckController extends Controller
{
    public function store($domainId)
    {
        $domain = DB::table('domains')
            ->find($domainId)
            ->name;

        if (!$domain) {
            return abort(404);
        }

        $response = Http::get($domain);

        $body = $response->body();
        $document = new Document($body);
        $h1Element = $document->first('h1');
        $h1 = optional($h1Element)->text();
        $keywordsElement = $document->first('meta[name="keywords"]');
        $keywords = optional($keywordsElement)->getAttribute('content');
        $descriptionElement = $document->first('meta[name="description"]');
        $description = optional($descriptionElement)->getAttribute('content');

        DB::table('domain_checks')->insert([
            'domain_id' => $domainId,
            'status_code' => $response->status(),
            'h1' => $h1,
            'keywords' => $keywords,
            'description' => $description,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        flash('Url checked')->info();

        return redirect()
            ->route('domains.show', $domainId);
    }
}
