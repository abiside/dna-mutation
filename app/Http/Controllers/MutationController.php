<?php

namespace App\Http\Controllers;

use App\Models\Dna;
use App\Http\Requests\DnaRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class MutationController extends Controller
{
    /**
     * Save the DNA and check if the chain has a mutation
     *
     * @param  \App\Http\Requests\DnaRequest
     * @return \Illuminate\Http\Response
     */
    public function store(DnaRequest $request)
    {
        try {
            $dna = Dna::saveByCode($request->dna);

            if (! $dna->hasMutations) {
                throw new AccessDeniedHttpException('The given DNA code doesn\'t have a mutation sequence');
            }

            return response()->make();
        } catch (Throwable $e) {
            throw new UnprocessableEntityHttpException('There was a problem trying to process the DNA code');
        }
    }
}
