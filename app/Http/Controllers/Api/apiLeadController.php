<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Lead as LeadResource;
use App\Tbl_leads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                    'tbl_states'
                )
                ->latest()
                ->paginate(15)
        );
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
            'tbl_states'
        )
            ->where('uid', '=', auth()->user()->id)
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

        return response()->json(['status' => 'Lead successfully deleted'], 200);
    }

    public function getProductLeads()
    {
        $uid = auth()->user()->id;

        //-----------------------------------------
        $query = DB::table('tbl_leads')->where('tbl_leads.uid', $uid)->where('tbl_leads.active', 1)->where('tbl_leads.leadtype', 2);
        $query->leftJoin('tbl_leadstatus', 'tbl_leads.ldstatus_id', '=', 'tbl_leadstatus.ldstatus_id');
        $query->leftJoin('tbl_leadsource', 'tbl_leads.ldsrc_id', '=', 'tbl_leadsource.ldsrc_id');
        $query->leftJoin('tbl_products', 'tbl_leads.pro_id', '=', 'tbl_products.pro_id');
        $query->orderBy('tbl_leads.ld_id', 'desc');
        $query->select(
            'tbl_leads.*',
            'tbl_leadstatus.status as leadstatus',
            'tbl_leadsource.leadsource as leadsource',
            'tbl_products.name as product'
        );

        // 'tbl_leadstatus.status as leadstatus',
        // 'tbl_leadsource.leadsource as leadsource',
        // 'tbl_accounts.name as account',

        $leads = $query->get();
        $total = count($leads);
        if ($total > 0) {
            return $query->paginate(50);
        } else {
            return [];
        }
    }

    public function getProductLeadDetails($id)
    {
        $uid = auth()->user()->id;

        //-----------------------------------------
        $leads = Tbl_leads::with('Tbl_leadsource')
            ->with('Tbl_industrytypes')
            ->with('Tbl_leadstatus')
            ->with('Tbl_Accounts')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->with('Tbl_salutations')
            ->with('Tbl_products')
            ->with('users')
            ->find($id);

        return $leads;
    }
}
