<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

class DomainCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store($domainId)
    {
        $domain = DB::table('domains')
            ->find($domainId)
            ->name;

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
