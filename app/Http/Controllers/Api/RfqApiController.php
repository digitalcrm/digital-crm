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
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store']);
    }
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required',
            'product_category_id' => 'required|not_in:0',
            'sub_category_id' => 'required|not_in:0',
            'company_id' => 'required|not_in:0',
            'product_quantity' => 'nullable|numeric',
            'unit_id' => 'nullable|numeric|not_in:0',
            'purchase_price' => 'nullable',
            'city' => 'nullable|string|max:255',
            // 'isChecked' => 'required|accepted',
            'details' => 'required|string|min:5',
            'images' => 'nullable',
            'images.*' => 'mimes:jpg,jpeg,png,bmp,gif,svg,webp,pdf,docx|max:1024',
        ]);

        $storedData = auth()->user()->rfqs()->create($validatedData);

        if ($request->hasFile('images')) {
            // foreach ($request->images as $photo) {
                $path_url = request('images')->storePublicly('rfqs', 'public');
                $storedData->images()->create([
                    'file_name' => request('images')->getClientOriginalName(),
                    'mime_type' => request('images')->getMimeType(),
                    'file_path' => $path_url,
                ]);
            // }

        }

        $response =  new RfqResource($storedData);

        return response()->json(['status' => 'success', 'data' => $response]);

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
