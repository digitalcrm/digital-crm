<?php

namespace App\Http\Controllers\RFQ\user;

use App\Rfq;
use App\Tbl_countries;
use App\Tbl_productcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RfqController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('lists','details');
        $this->middleware('verified')->except('lists','details');
        $this->middleware('isActive');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rfqs = auth()->user()->rfqs()->latest()->get();

        return view('rfq.user.index',compact('rfqs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('rfq.rfq-form');
        return view('rfq.user.create');
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
            'product_quantity' => 'required',
            'unit_id' => 'required|not_in:0',
            'purchase_price' => 'nullable',
            'city' => 'required|',
            'isChecked' => 'required|accepted',
            'details' => 'required|string|min:1|max:500',
        ])+[
            // 'details' => $request->details,
            'user_id' => auth()->user()->id,
        ];

        if (request('isChecked') === 'on') {
            $validatedData['isChecked'] = true;
        }

        // auth()->user()->rfqs()->create($validatedData);
        Rfq::create($validatedData);

        return redirect()->back()->withMessage('RFQ submitted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rfq  $rfq
     * @return \Illuminate\Http\Response
     */
    public function show(Rfq $rfq_form)
    {
        // return view('rfq.user.rfq-leads', compact('rfq_form'));
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
    public function destroy(Rfq $rfq_form)
    {
        $rfq_form->delete();

        return redirect()->route('rfq-forms.index')->withMessage('rfq deleted successfully');
    }

    public function lists()
    {
        $rfqs = Rfq::with(['tbl_category','tbl_sub_category'])
                ->categoryFilter(request('category'))
                ->subcategoryFilter(request('subcategory'))
                ->isActive()
                ->latest()
                ->paginate(30)->withQueryString();

        $productCategory = Tbl_productcategory::has('rfqs')->get();

        return view('rfq.lists-rfq', compact('rfqs','productCategory'));
    }

    public function details(Rfq $details)
    {
        $countries = Tbl_countries::get(['id','name']);
        return view('rfq.details-rfq',compact('details','countries'));
    }
}
