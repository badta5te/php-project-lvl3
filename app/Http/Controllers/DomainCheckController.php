<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DiDom\Document;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class DomainCheckController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $domainId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(int $domainId)
    {

        $domain = DB::table('domains')
            ->find($domainId)
            ->name;

        abort_unless($domain, 404);

        try {
            $response = Http::get($domain);
        } catch (ConnectionException | RequestException $exception) {
            flash($exception->getMessage())->error();
            /** @phpstan-ignore-next-line */
            return redirect()->route('domains.show', $domainId);
        }

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

        /** @phpstan-ignore-next-line */
        return redirect()
            ->route('domains.show', $domainId);
    }
}
