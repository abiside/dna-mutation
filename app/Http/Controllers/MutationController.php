<?php

namespace App\Http\Controllers;

use App\Models\Dna;
use App\Http\Requests\DnaRequest;
use Illuminate\Http\Response;

class MutationController extends Controller
{
    /**
     * Save the DNA and check if the chain has a mutation
     *
     * @param  \App\Http\Requests\DnaRequest
     * @return \Illuminate\Http\Response
     */
    public function store(DnaRequest $request): Response
    {
        $dna = Dna::saveByCode($request->dna);

        dd($dna->hasMutations);
    }
}
