<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RfqCollection;
use App\Http\Resources\RfqResource;
use App\Rfq;
use App\RfqLead;
use Illuminate\Http\Request;

class RfqApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rfqs = Rfq::has('user')->with([
            'tbl_category:procat_id,category,slug',
            'tbl_sub_category:prosubcat_id,procat_id,category,slug',
            'unit:unit_id,name,sortname',
            'images',
            'user:id,name,email'
        ])->isActive()->latest()->get();

        return new RfqCollection($rfqs);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rfq  $rfq
     * @return \Illuminate\Http\Response
     */
    public function show(Rfq $rfq)
    {
        $rfqDetails = $rfq->has('user')->with([
            'tbl_category:procat_id,category,slug',
            'tbl_sub_category:prosubcat_id,procat_id,category,slug',
            'unit:unit_id,name,sortname',
            'images',
            'user:id,name,email,cr_id',
            'user.currency'
        ])->findOrFail($rfq->id);

        return new RfqResource($rfqDetails);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rfq  $rfq
     * @return \Illuminate\Http\Response
     */
    public function edit(Rfq $rfq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rfq  $rfq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rfq $rfq)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rfq  $rfq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rfq $rfq)
    {
        //
    }
}
