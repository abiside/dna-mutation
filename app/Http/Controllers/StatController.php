<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class StatController extends Controller
{
    /**
     * Return the mutation summary report
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $result = DB::table('dna_codes')
            ->select(DB::raw(
                'SUM(IF(mutations, 1, 0)) as count_mutations,
                SUM(IF(mutations, 0, 1)) as count_no_mutations,
                (SUM(IF(mutations, 1, 0)) / COUNT(id)) as ratio'
            ))
            ->first();

        return response()->json([
            'count_mutations' => $result->count_mutations ?? 0,
            'count_no_mutations' => $result->count_no_mutations ?? 0,
            'ratio' => number_format($result->ratio, 2),
        ]);
    }
}
