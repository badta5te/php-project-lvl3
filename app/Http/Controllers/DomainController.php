<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = DB::table('domains')->get();

        $latestCheck = DB::table('domain_checks')
            ->distinct('domain_id')
            ->select('domain_id', 'created_at', 'status_code')
            ->orderBy('domain_id')
            ->latest()
            ->get()
            ->keyBy('domain_id');

        return view('domain.index', compact('domains', 'latestCheck'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain.name' => 'required|url'
        ]);

        $url = $request->input('domain.name');
        $domain = strtolower(parse_url($url, PHP_URL_HOST));

        $domainInDb = DB::table('domains')->where('name', $domain)->first();

        if ($domainInDb) {
            flash('Url exists');

            return redirect()
                ->route('domains.show', $domainInDb->id);
        }

        $id = DB::table('domains')->insertGetId([
            'name' => $domain,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        flash('Url added')->success();

        return redirect()
            ->route('domains.show', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $domain = DB::table('domains')
            ->where('id', $id)
            ->first();

        if (!$domain) {
            abort(404);
        }

        $domainChecks = DB::table('domain_checks')
            ->where('domain_id', $id)
            ->orderByDesc('id')
            ->get();

        return view('domain.show', compact('domain', 'domainChecks'));
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
