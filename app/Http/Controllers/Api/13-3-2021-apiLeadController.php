<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Lead as LeadResource;
use App\Tbl_leads;
use Illuminate\Http\Request;

class apiLeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         return LeadResource::collection(
                    auth()->user()
                    ->tbl_leads()
                    ->with(
                    'users',
                    'tbl_accounts',
                    'tbl_leadsource',
                    'tbl_leadstatus',
                    'tbl_industrytypes',
                    'tbl_leadsource',
                    'tbl_leadstatus',
                    'tbl_industrytypes',
                    'tbl_countries',
                    'tbl_states')
                    ->latest()
                    ->paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255:tbl_leads',
        ]);

        $leads_data = $request->all();

        $leads_data['uid'] = auth()->user()->id;

        if ($request->hasfile('picture')) {
            $file = $request->file('picture');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/profile/', $name);
            $filename = '/uploads/profile/' . $name;
            $leads_data['picture'] = $filename;
        }
        // dd($leads_data);
        $leads = Tbl_leads::create($leads_data);

        return new LeadResource($leads);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leads_show = Tbl_leads::with(
                                'users',
                                'tbl_accounts',
                                'tbl_leadsource',
                                'tbl_leadstatus',
                                'tbl_industrytypes',
                                'tbl_leadsource',
                                'tbl_leadstatus',
                                'tbl_industrytypes',
                                'tbl_countries',
                                'tbl_states')
                                ->where('uid','=',auth()->user()->id)
                                ->findOrFail($id);

        return new LeadResource($leads_show);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tbl_leads $lead)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255:tbl_leads',
        ]);

        $lead_update = $request->all();

        $lead_update['uid'] = auth()->user()->id;

        if ($request->hasfile('picture')) {
            $file = $request->file('picture');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/profile/', $name);
            $filename = '/uploads/profile/' . $name;
            $lead_update['picture'] = $filename;
        }

        $lead->update($lead_update);

        return new LeadResource($lead);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tbl_leads $lead)
    {
        $lead->delete();

        return response()->json(['status'=>'Lead successfully deleted'], 200);
    }
}
