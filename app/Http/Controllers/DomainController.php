<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $domains = DB::table('domains')
            ->orderBy('id')
            ->get();

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain.name' => 'required|url'
        ]);

        if ($validator->fails()) {
            /** @phpstan-ignore-next-line */
            return redirect()
                        ->route('homepage')
                        ->withErrors($validator)
                        ->withInput();
        }

        $url = $request->input('domain.name');
        $urlParts = parse_url(strtolower($url));
        ['scheme' => $scheme, 'host' => $host] = $urlParts;
        $domain = "{$scheme}://{$host}";

        $domainInDb = DB::table('domains')->where('name', $domain)->first();

        /** @var $domainInDb object */
        if ($domainInDb) {
            flash('Url exists');

            /** @phpstan-ignore-next-line */
            return redirect()
                ->route('domains.show', $domainInDb->id);
        }

        $id = DB::table('domains')->insertGetId([
            'name' => $domain,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        flash('Url added')->success();

        /** @phpstan-ignore-next-line */
        return redirect()
            ->route('domains.show', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $domain = DB::table('domains')
            ->find($id);

        abort_unless($domain, 404);

        $domainChecks = DB::table('domain_checks')
            ->where('domain_id', $id)
            ->orderByDesc('id')
            ->get();

        return view('domain.show', compact('domain', 'domainChecks'));
    }
}
