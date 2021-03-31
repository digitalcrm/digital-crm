<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Tbl_Accounts;
use App\Tbl_leads;
use App\Tbl_countries;
use App\Tbl_states;
use App\Tbl_deals;
use App\Tbl_deal_events;
use App\Tbl_industrytypes;
use App\Tbl_leadsource;
use App\Tbl_leadstatus;
use App\Tbl_salesfunnel;
use App\User;
use App\currency;
use App\Tbl_lossreasons;
use App\Tbl_products;
use App\Tbl_admin_notifications;
use App\Tbl_deal_types;
use App\Tbl_projects;
use App\Company;

// use Excel;
use App\Imports\DealsImport;
use App\Exports\DealsExport;
use Maatwebsite\Excel\Facades\Excel;

//use Controllers
use App\Http\Controllers\ProjectController;


class DealController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:deals', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'deleteDeal', 'tableView', 'kanban', 'import', 'importData', 'export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('deals/kanban/demo');
    }

    public function tableView()
    {

        $uid = Auth::user()->id;

        // $day = date('Y-m-d');
        // $weekDay = date('Y-m-d', strtotime($day . ' - 30 days'));  // 1 week

        $day = '';
        $weekDay = '';  // 1 week
        $type = 'All';

        $close_date_start = "";
        $close_date_end = "";

        // $data = $this->getDeals($uid, $weekDay, $day, $type);

        $data = $this->getDeals($uid, $close_date_start, $close_date_end, $type);

        //  Deal Stage
        $dealstage = Tbl_salesfunnel::all();
        $dealstageoptions = "<option value='All'>All</option>";
        if (count($dealstage) > 0) {
            foreach ($dealstage as $stage) {
                $dealstageoptions .= "<option value='" . $stage->sfun_id . "'>" . $stage->salesfunnel . "</option>";
            }
        }
        $data['dealstageoptions'] = $dealstageoptions;

        $data['closeDate'] = $this->getCloseDates();

        $data['dealstageVal'] = 'All';
        $data['timer'] = 'All';

        return view('auth.deals.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $uid = Auth::user()->id;

        $user = Auth::user();
        if ($user->can('create', Tbl_deals::class)) {

            //  Leads
            $leads = Tbl_leads::where('uid', $uid)->where('active', 1)->get();
            $leadoptions = "<option value=''>Select Lead</option>";
            if (count($leads) > 0) {
                foreach ($leads as $lead) {
                    $leadoptions .= "<option value='" . $lead->ld_id . "'>" . substr($lead->first_name . " " . $lead->last_name, 0, 20) . "</option>";
                }
            }
            $leadoptions .= "<option disabled>---</option>";
            $leadoptions .= "<option value='NewLead'>Add Lead</option>";
            $data['leadoptions'] = $leadoptions;

            //  Lead Source
            $leadsource = Tbl_leadsource::all();
            $leadsourceoptions = "<option value='0'>Select Lead Source</option>";
            if (count($leadsource) > 0) {
                foreach ($leadsource as $source) {
                    $leadsourceoptions .= "<option value='" . $source->ldsrc_id . "'>" . $source->leadsource . "</option>";
                }
            }
            $data['leadsourceoptions'] = $leadsourceoptions;

            //  Deal Stage
            $dealstage = Tbl_salesfunnel::all();
            $dealstageoptions = "<option value=''>Select Deal Stage</option>";
            if (count($dealstage) > 0) {
                foreach ($dealstage as $stage) {
                    $dealstageoptions .= "<option value='" . $stage->sfun_id . "'>" . $stage->salesfunnel . "</option>";
                }
            }
            $data['dealstageoptions'] = $dealstageoptions;

            //  Products
            $products = Tbl_products::where('active', 1)->get();    //where('uid', $uid)->
            $productoptions = '<option value="">Select ...</option>';
            foreach ($products as $product) {
                $productoptions .= '<option value="' . $product->pro_id . '">' . $product->name . '</option>';
            }
            $data['productoptions'] = $productoptions;

            //  Deal Type Options
            $dtypes = Tbl_deal_types::all();    //where('uid', $uid)->
            $dtypeoptions = '<option value="">Select Deal Type...</option>';
            foreach ($dtypes as $dtype) {
                $dtypeoptions .= '<option value="' . $dtype->dl_id . '">' . $dtype->type . '</option>';
            }
            $data['dealtype_options'] = $dtypeoptions;

            //  User
            $user = User::with('currency')->find($uid)->toArray();
            $data['user'] = $user;
            return view('auth.deals.create')->with('data', $data);
        } else {
            return redirect('/deals');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo json_encode($request->input());
        // $viewType = $request->session()->get('viewType');
        // echo $viewType;
        // exit();

        $this->validate($request, [
            'dealname' => 'required|max:255',
            'amount' => 'required|numeric',
            'closingdate' => 'required|date_format:d-m-Y',
        ]);

        $ld_id = 0;
        if ($request->input('lead') == 'NewLead') {
            $arr_account = array(
                'uid' => Auth::user()->id,
                'first_name' => $request->input('addLead'),
            );
            $leadss = Tbl_leads::create($arr_account);
            $ld_id = $leadss->ld_id;
        } else {
            $ld_id = $request->input('lead');
        }


        $sfun_id = $request->input('dealstage');

        $deal_status = 0;
        if ($sfun_id == 5) {
            $deal_status = 1;
        }
        if ($sfun_id == 6) {
            $deal_status = 2;
        }
        //        $deals->deal_status = $deal_status;

        $probability = ($request->input('probability') != '') ? $request->input('probability') : 0;
        $dealType = ($request->input('dl_id') != '') ? $request->input('dl_id') : 0;
        $formdata = array(
            'uid' => Auth::user()->id,
            'ld_id' => $ld_id,
            'sfun_id' => $request->input('dealstage'),
            'ldsrc_id' => $request->input('leadsource'),
            'name' => $request->input('dealname'),
            'value' => $request->input('amount'),
            'closing_date' => date('Y-m-d', strtotime(str_replace('/', '-', $request->input('closingdate')))),
            'notes' => $request->input('notes'),
            'deal_status' => $deal_status,
            'probability' => $probability,
            'pro_id' => $request->input('pro_id'),    //product
            'dl_id' => $dealType,
        );

        //        $deals = Tbl_deals::create($formdata);
        $deals = $this->addDeal($formdata);
        $deal_id = $deals->deal_id;

        if ($deal_id > 0) {

            if ($deal_status == 1) {
                $projectObj = new ProjectController();
                $project = $projectObj->createProject($deal_id);
            }

            $viewType = $request->session()->get('viewType');
            $page = 'deals';
            if ($viewType == 'kanban') {
                $page = 'deals/kanban/demo';
            }

            return redirect($page)->with('success', 'Deal Created Successfully...!');
        } else {
            return redirect('/deals/create')->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function addDeal($formdata)
    {
        return Tbl_deals::create($formdata);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deals = $this->getDealdetails($id);
        // echo json_encode($deals);
        // exit();
        $user = Auth::user();
        if ($user->can('view', $deals)) {

            $data['deals'] = $deals->toArray();
            $data['editlink'] = url('deals/' . $id . '/edit');

            $acc_id = $deals['tbl_leads']['acc_id'];
            $account = '';
            $company = '';
            if ($acc_id > 0) {
                $accountDetails = Tbl_Accounts::find($acc_id);
                $account = $accountDetails->name;
                $company = $accountDetails->company;
            }
            $data['account'] = $account;
            $data['company'] = $company;

            $events = $deals->Tbl_deal_events;



            $eventsUl = 'Log is not available...';
            if (count($events) > 0) {
                $eventsUl = '<ul class="timeline timeline-inverse">';

                foreach ($events as $event) {
                    $from = Tbl_salesfunnel::find($event->sfun_from);
                    $to = Tbl_salesfunnel::find($event->sfun_to);

                    if (($from == '') && ($to != '')) {

                        $eventsUl .= '<li class="time-label"><span class="bg-grey">' . date('d M Y h:i a', strtotime($event->event_time)) . '</span></li>';
                        $eventsUl .= '<li>
                            <i class="fa fa-suitcase bg-default"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header no-border">Deal status changed to ' . $to->salesfunnel . ' </h3>
                            </div>
                        </li>';
                    }

                    if (($from != '') && ($to != '')) {

                        $eventsUl .= '<li class="time-label"><span class="bg-grey">' . date('d M Y h:i a', strtotime($event->event_time)) . '</span></li>';
                        $eventsUl .= '<li>
                            <i class="fa fa-suitcase bg-default"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header no-border">Deal status changed from ' . $from->salesfunnel . ' to ' . $to->salesfunnel . ' </h3>
                            </div>
                        </li>';
                    }
                }
                $eventsUl .= '<li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>';
                $eventsUl .= '</ul>';
            }
            $data['eventsUl'] = $eventsUl;

            return view('auth.deals.show')->with("data", $data);
        } else {
            return redirect('/deals');
        }
    }

    public function getDealdetails($id)
    {
        return Tbl_deals::with('Tbl_leads')
            ->with('Tbl_leadsource')
            ->with('Tbl_salesfunnel')
            ->with('Tbl_lossreasons')
            ->with('Tbl_products')
            ->with('Tbl_deal_events')
            ->with('Tbl_deal_types')
            ->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $uid = Auth::user()->id;

        $deals = Tbl_deals::with('Tbl_leads')
            ->with('Tbl_leadsource')
            ->with('Tbl_salesfunnel')
            ->with('Tbl_products')
            ->find($id);

        $user = Auth::user();

        if ($user->can('view', $deals)) {

            $data['deal'] = $deals;

            $leads = Tbl_leads::where('active', 1)->where('uid', $uid)->get();
            $leadoptions = "<option value=''>Select Lead</option>";
            if (count($leads) > 0) {
                foreach ($leads as $lead) {
                    $leadselected = (($deals->tbl_leads != '') && ($deals->tbl_leads->ld_id == $lead->ld_id)) ? 'selected' : '';
                    $leadoptions .= "<option value='" . $lead->ld_id . "' " . $leadselected . ">" . substr($lead->first_name . " " . $lead->last_name, 0, 20) . "</option>";
                }
            }
            $leadoptions .= "<option disabled>---</option>";
            $leadoptions .= "<option value='NewLead'>Add Lead</option>";
            $data['leadoptions'] = $leadoptions;

            $leadsource = Tbl_leadsource::all();
            $leadsourceoptions = "<option value='0'>Select Lead Source</option>";
            if (count($leadsource) > 0) {
                foreach ($leadsource as $source) {
                    $leadsourceselected = (($deals->tbl_leadsource != '') && ($deals->tbl_leadsource->ldsrc_id == $source->ldsrc_id)) ? 'selected' : '';
                    $leadsourceoptions .= "<option value='" . $source->ldsrc_id . "' " . $leadsourceselected . ">" . $source->leadsource . "</option>";
                }
            }
            $data['leadsourceoptions'] = $leadsourceoptions;

            $dealstage = Tbl_salesfunnel::all();
            $dealstageoptions = "<option value=''>Select Deal Stage</option>";
            if (count($dealstage) > 0) {
                foreach ($dealstage as $stage) {
                    $dealstageselected = (($deals->tbl_salesfunnel != '') && ($deals->tbl_salesfunnel->sfun_id == $stage->sfun_id)) ? 'selected' : '';
                    $dealstageoptions .= "<option value='" . $stage->sfun_id . "' " . $dealstageselected . ">" . $stage->salesfunnel . "</option>";
                }
            }
            $data['dealstageoptions'] = $dealstageoptions;

            $lossreasons = Tbl_lossreasons::all();
            $lossreasonoptions = "<option value=''>Select Loss Reason</option>";
            if (count($lossreasons) > 0) {
                foreach ($lossreasons as $lossreason) {
                    $lossreasonselected = (($deals->tbl_lossreasons != '') && ($deals->tbl_lossreasons->lr_id == $lossreason->lr_id)) ? 'selected' : '';
                    $lossreasonoptions .= "<option value='" . $lossreason->lr_id . "' " . $lossreasonselected . ">" . $lossreason->reason . "</option>";
                }
            }
            $data['lossreasonoptions'] = $lossreasonoptions;

            //        $uid = Auth::user()->id;
            $user = User::with('currency')->find($uid)->toArray();
            $data['user'] = $user;

            //  Products
            $products = Tbl_products::where('active', 1)->get();    //where('uid', $uid)->
            $productoptions = '<option value="">Select ...</option>';
            foreach ($products as $product) {
                $productselected = (($deals->tbl_products != '') && ($deals->tbl_products->pro_id == $product->pro_id)) ? 'selected' : '';
                $productoptions .= '<option value="' . $product->pro_id . '" ' . $productselected . '>' . $product->name . '</option>';
            }
            $data['productoptions'] = $productoptions;

            //  Deal Type Options
            $dtypes = Tbl_deal_types::all();    //where('uid', $uid)->
            $dtypeoptions = '<option value="">Select Deal Type...</option>';
            foreach ($dtypes as $dtype) {
                $dtypeselected = (($deals->tbl_deal_types != '') && ($deals->tbl_deal_types->dl_id == $dtype->dl_id)) ? 'selected' : '';
                $dtypeoptions .= '<option value="' . $dtype->dl_id . '" ' . $dtypeselected . '>' . $dtype->type . '</option>';
            }
            $data['dealtype_options'] = $dtypeoptions;

            return view('auth.deals.edit')->with('data', $data);
        } else {
            return redirect('/deals');
        }
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
        //        echo json_encode($request->input());
        $deals = Tbl_deals::find($id);

        $user = Auth::user();

        if ($user->can('update', $deals)) {

            $this->validate($request, [
                'dealname' => 'required|max:255',
                'amount' => 'required|numeric',
                'closingdate' => 'required|date_format:d-m-Y',
            ]);

            $formdata = $request->input();

            $ld_id = 0;
            if ($request->input('lead') == 'NewLead') {
                $arr_account = array(
                    'uid' => Auth::user()->id,
                    'first_name' => $request->input('addLead'),
                );
                $leadss = Tbl_leads::create($arr_account);
                $ld_id = $leadss->ld_id;
            } else {
                $ld_id = $request->input('lead');
            }

            $formdata['lead'] = $ld_id;

            $sfun_id = $request->input('dealstage');


            $deal_status = 0;
            if ($sfun_id == 5) {
                $deal_status = 1;
            }
            if ($sfun_id == 6) {
                $deal_status = 2;
            }

            $formdata['deal_status'] = $deal_status;

            $res = $this->updateDeal($formdata, $id);
            $proexist = Tbl_projects::where('deal_id', $id)->count();
            if ($res) {
                if ($deal_status == 1) {
                    if ($proexist > 0) {
                        Tbl_projects::where('deal_id', $id)->update(array('active' => 1));
                    } else {
                        $projectObj = new ProjectController();
                        $project = $projectObj->createProject($id);
                    }
                } else {
                    if ($proexist > 0) {
                        Tbl_projects::where('deal_id', $id)->update(array('active' => 0));
                    }
                }

                $viewType = $request->session()->get('viewType');
                $page = 'deals';
                if ($viewType == 'kanban') {
                    $page = 'deals/kanban/demo';
                }

                return redirect($page)->with('success', 'Deal Updated Successfully...!');
            } else {
                return redirect('/deals/edit/' . $id)->with('error', 'Failed. Please try again...');
            }
        } else {
            return redirect('/deals');
        }
    }

    public function updateDeal($formdata, $id)
    {
        $deals = Tbl_deals::find($id);

        //  Tracking Deal Stages

        $from = $deals->sfun_id;
        $to = (isset($formdata['dealstage'])) ? $formdata['dealstage'] : $deals->sfun_id;
        $deal_status = (isset($formdata['deal_status'])) ? $formdata['deal_status'] : $deals->deal_status;

        if ($from != $to) {
            $this->dealStageEvents($id, $from, $to, $deal_status);
        }

        $deals->ld_id = (isset($formdata['lead'])) ? $formdata['lead'] : $deals->ld_id;
        $deals->sfun_id = (isset($formdata['dealstage'])) ? $formdata['dealstage'] : $deals->sfun_id;
        $deals->ldsrc_id = (isset($formdata['leadsource'])) ? $formdata['leadsource'] : $deals->ldsrc_id;
        $deals->name = (isset($formdata['dealname'])) ? $formdata['dealname'] : $deals->name;
        $deals->value = (isset($formdata['amount'])) ? $formdata['amount'] : $deals->value;
        $deals->closing_date = (isset($formdata['closingdate'])) ? date('Y-m-d', strtotime(str_replace('/', '-', $formdata['closingdate']))) : $deals->closing_date;
        $deals->notes = (isset($formdata['notes'])) ? $formdata['notes'] : $deals->notes;
        $deals->deal_status = (isset($formdata['deal_status'])) ? $formdata['deal_status'] : $deals->deal_status;
        $deals->probability = ($formdata['probability'] > 0) ? $formdata['probability'] : $deals->probability;
        $deals->lr_id = (isset($formdata['lossreason'])) ? $formdata['lossreason'] : $deals->lr_id;
        $deals->pro_id = ($formdata['pro_id'] > 0) ? $formdata['pro_id'] : $deals->pro_id;
        $deals->dl_id = (isset($formdata['dl_id'])) ? $formdata['dl_id'] : $deals->dl_id;
        return $deals->save();
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

    public function changeDealStageDragnDrop(Request $request)
    {
        // echo json_encode($request->input());
        // exit();

        $dealIds = $request->input('deal_id');
        $dealIds = explode("_", $dealIds);
        $d = count($dealIds) - 1;
        $deal_id = $dealIds[$d];

        $stageIds = $request->input('sfun_id');
        $stageIds = explode("_", $stageIds);
        $s = count($stageIds) - 1;
        $sfun_id = $stageIds[$s];

        $fromIds = $request->input('from_id');
        $fromIds = explode("_", $fromIds);
        $f = count($fromIds) - 1;
        $from_id = $fromIds[$f];

        $fromStage = Tbl_salesfunnel::find($from_id);
        $fromStageId = str_replace(" ", "_", $fromStage->salesfunnel) . '_' . $from_id . '_Amount';

        $toStage = Tbl_salesfunnel::find($sfun_id);
        $toStageId = str_replace(" ", "_", $toStage->salesfunnel) . '_' . $sfun_id . '_Amount';

        $deals = Tbl_deals::find($deal_id);

        //  Tracking Deal Stages

        $from = $deals->sfun_id;
        $to = $sfun_id;

        $deals->sfun_id = $sfun_id;
        $deal_status = 0;
        if ($sfun_id == 5) {
            $deal_status = 1;
        }
        if ($sfun_id == 6) {
            $deal_status = 2;
        }
        $deals->deal_status = $deal_status;
        $res = $deals->save();
        if ($res) {
            $proexist = Tbl_projects::where('deal_id', $deal_id)->count();
            if ($deal_status == 1) {

                if ($proexist > 0) {
                    Tbl_projects::where('deal_id', $deal_id)->update(array('active' => 1));
                } else {
                    $projectObj = new ProjectController();
                    $project = $projectObj->createProject($deal_id);
                }
            } else {
                if ($proexist > 0) {
                    Tbl_projects::where('deal_id', $deal_id)->update(array('active' => 0));
                }
            }
            $this->dealStageEvents($deal_id, $from, $to, $deal_status);
            // return 'success';


            $toAmount = $this->getStageAmount($sfun_id);
            $fromAmount = $this->getStageAmount($from);

            $data['fromAmount'] =  $fromAmount;
            $data['toAmount'] =  $toAmount;

            $data['fromStageId'] =  $fromStageId;
            $data['toStageId'] =  $toStageId;

            return json_encode($data);
        } else {
            return 'error';
        }
    }

    public function changestage($deal_id, $sfun_id)
    {
        $deals = Tbl_deals::find($deal_id);

        //  Tracking Deal Stages

        $from = $deals->sfun_id;
        $to = $sfun_id;

        $deals->sfun_id = $sfun_id;
        $deal_status = 0;
        if ($sfun_id == 5) {
            $deal_status = 1;
        }
        if ($sfun_id == 6) {
            $deal_status = 2;
        }
        $deals->deal_status = $deal_status;
        $deals->save();

        $proexist = Tbl_projects::where('deal_id', $deal_id)->count();
        if ($deal_status == 1) {

            if ($proexist > 0) {
                Tbl_projects::where('deal_id', $deal_id)->update(array('active' => 1));
            } else {
                $projectObj = new ProjectController();
                $project = $projectObj->createProject($deal_id);
            }
        } else {
            if ($proexist > 0) {
                Tbl_projects::where('deal_id', $deal_id)->update(array('active' => 0));
            }
        }
        $this->dealStageEvents($deal_id, $from, $to, $deal_status);

        return redirect('/deals')->with('success', 'Deal Updated Successfully...!');
    }

    public function dealStageEvents($id, $from, $to, $status)
    {
        $uid = Auth::user()->id;

        $formdata = array(
            'deal_status' => $status,
            'deal_id' => $id,
            'sfun_from' => $from,
            'sfun_to' => $to,
            'event_time' => date('Y-m-d h:i:s')
        );

        //     Creating admin notifiation   Tbl_admin_notifications
        $deal = Tbl_deals::find($id);
        $from_status = Tbl_salesfunnel::find($from);
        $to_status = Tbl_salesfunnel::find($to);

        if ($from_status != '') {
            $message = $deal->name . " status is changed from " . $from_status->salesfunnel . " to " . $to_status->salesfunnel;
        } else {
            $message = $deal->name . " status is changed to " . $to_status->salesfunnel;
        }

        $adnot = array(
            'aid' => 1,
            'uid' => $uid,
            'status' => 0,
            'type' => 4,
            'id' => $id,
            'message' => $message
        );

        Tbl_admin_notifications::create($adnot);

        return Tbl_deal_events::create($formdata);
    }

    public function import($type)
    {
        return view('auth.deals.import');
    }

    public function importData(Request $request)
    {
        $uid = Auth::user()->id;
        //        echo json_encode($request->input());


        $filename = '';
        if ($request->hasfile('importFile')) {

            //-------------Image Validation----------------------------------
            $file = $request->file('importFile');
            // Build the input for validation
            $fileArray = array('importFile' => $file, 'extension' => strtolower($file->getClientOriginalExtension()));

            //            echo $file->getClientOriginalExtension();
            //            exit(0);
            // Tell the validator that this file should be an image
            $rules = array(
                'importFile' => 'required', // max 10000kb
                'extension' => 'required|in:csv'
            );

            // Now pass the input and rules into the validator
            $validator = Validator::make($fileArray, $rules);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                return redirect('/deals/import/csv')->with('error', 'Please upload .csv only.');
            }
            //-------------Image Validation----------------------------------



            $name = time() . '.' . $file->getClientOriginalExtension();   //getClientOriginalName()
            $path = $request->file('importFile')->getRealPath();
            // $data = Excel::load($path)->get();
            // $exist_rec = '';

            $res = Excel::import(new DealsImport($uid), request()->file('importFile'));
            if ($res) {
                // echo 'Success';
                return redirect('/deals')->with('success', 'Uploaded successfully');
            } else {
                // echo 'Failed';
                return redirect('/deals/import/csv')->with('error', "Error ocurred. Try again later..!");
            }
        } else {
            return redirect('/deals/import/csv')->with('error', 'Please upload file.');
        }
    }

    public function exportData($type)
    {
        $uid = Auth::user()->id;
        return Excel::download(new DealsExport($uid), 'deals.xlsx');
    }

    public function won()
    {

        $uid = Auth::user()->id;
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);

        $deals = Tbl_deals::where('uid', $uid)->where('deal_status', 1)
            ->with('Tbl_leads')
            ->with('Tbl_leadsource')
            ->with('Tbl_salesfunnel')
            ->get()->toArray();

        $total = count($deals);
        if ($total > 0) {
            $formstable = '<div class="table-responsive"><table id="dealsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Deal Stage</th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Amount<span>&nbsp;(' . $currency->html_code . ')</span></th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {
                $formstable .= '<tr>';

                $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';

                $formstable .= '<td class="table-title"><a href="' . url('deals/' . $formdetails['deal_id']) . '">' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $formdetails['tbl_salesfunnel']['salesfunnel'] . '</td>';
                $formstable .= '<td><img src="' . url($leadimage) . '" width="30" height="24">&nbsp;' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</td>';
                $formstable .= '<td>' . $formdetails['value'] . '</td>';
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails['closing_date'])) . '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table></div>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.deals.won')->with("data", $data);
    }

    public function lost()
    {

        $uid = Auth::user()->id;
        $cr_id = Auth::user()->cr_id;
        $currency = currency::find($cr_id);

        $deals = Tbl_deals::where('uid', $uid)->where('deal_status', 2)
            ->with('Tbl_leads')
            ->with('Tbl_leadsource')
            ->with('Tbl_salesfunnel')
            ->get()->toArray();

        $dealstage = Tbl_salesfunnel::all();

        $total = count($deals);
        if ($total > 0) {
            $formstable = '<table id="dealsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Deal Name</th>';
            $formstable .= '<th>Deal Stage</th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Amount<span>&nbsp;(' . $currency->html_code . ')</span></th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '<th>Change Deal Stage</th>';
            //            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {
                $formstable .= '<tr>';

                $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';

                $formstable .= '<td class="table-title"><a href="' . url('deals/' . $formdetails['deal_id']) . '">' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td>' . $formdetails['tbl_salesfunnel']['salesfunnel'] . '</td>';
                $formstable .= '<td><img src="' . url($leadimage) . '" width="30" height="24">&nbsp;' . $formdetails['tbl_leads']['first_name'] . ' ' . $formdetails['tbl_leads']['last_name'] . '</td>';
                $formstable .= '<td>' . $formdetails['value'] . '</td>';
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails['closing_date'])) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                      </button>
                      <div class="dropdown-menu" role="menu">';
                foreach ($dealstage as $stage) {
                    if ($formdetails['tbl_salesfunnel']['sfun_id'] != $stage->sfun_id) {
                        $formstable .= "<a class='dropdown-item' href=" . url('deals/changestage/' . $formdetails['deal_id'] . '/' . $stage->sfun_id) . ">" . $stage->salesfunnel . "</a>";
                    }
                }
                $formstable .= '</div></div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('auth.deals.lost')->with("data", $data);
    }

    public function deleteDeal($id)
    {
        $deals = Tbl_deals::find($id);

        $user = Auth::user();

        if ($user->can('delete', $deals)) {
            $deal = $this->delete($id);
            if ($deal) {
                return redirect('/deals')->with('success', 'Deleted Successfully...');
            } else {
                return redirect('/deals')->with('error', 'Failed. Try again later');
            }
        } else {
            return redirect('/deals');
        }
    }

    public function delete($id)
    {
        $deal = Tbl_deals::find($id);
        $deal->active = 0;
        return $deal->save();
    }

    public function getDeals($uid, $close_date_start, $close_date_end, $type)
    {

        // echo $uid . " " . $close_date_start . " " . $close_date_end . " " . $type;
        // exit();

        $dealstage = Tbl_salesfunnel::all();

        $uid = Auth::user()->id;
        $user = User::find($uid);
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);

        $deals = '';

        //-----------------------------------------
        $query = DB::table('tbl_deals')->where('tbl_deals.active', 1);
        $query->where('tbl_deals.uid', $uid);
        if (($type > 0) && ($type != 'All')) {
            $query->where('tbl_deals.sfun_id', $type);
        }

        if (($close_date_start != '') && ($close_date_end != '')) {
            $query->whereBetween(DB::raw('DATE(tbl_deals.closing_date)'), [$close_date_start, $close_date_end]);
        }
        $query->leftJoin('tbl_leads', 'tbl_leads.ld_id', '=', 'tbl_deals.ld_id');
        $query->leftJoin('tbl_leadsource', 'tbl_leadsource.ldsrc_id', '=', 'tbl_deals.ldsrc_id');
        $query->leftJoin('tbl_salesfunnel', 'tbl_salesfunnel.sfun_id', '=', 'tbl_deals.sfun_id');
        $query->leftJoin('tbl_products', 'tbl_products.pro_id', '=', 'tbl_deals.pro_id');
        $query->leftJoin('tbl_deal_types', 'tbl_deal_types.dl_id', '=', 'tbl_deals.dl_id');
        $query->orderBy('tbl_deals.deal_id', 'desc');
        $query->select(
            'tbl_deals.*',
            'tbl_leads.first_name as first_name',
            'tbl_leads.last_name as last_name',
            'tbl_leads.acc_id as acc_id',
            'tbl_leads.picture as picture',
            'tbl_leadsource.leadsource as leadsource',
            'tbl_salesfunnel.salesfunnel as salesfunnel',
            'tbl_salesfunnel.color as color',
            'tbl_products.name as product',
            'tbl_products.company as c_id',
            'tbl_deal_types.type as deal_type'
        );
        $deals = $query->get();
        //-----------------------------------------

        $total = count($deals);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th colspan="2">Deal Name</th>';
            $formstable .= '<th>Lead</th>';
            $formstable .= '<th>Account</th>';
            $formstable .= '<th>Company</th>';
            $formstable .= '<th>Product</th>';
            $formstable .= '<th>Amount</th>';
            $formstable .= '<th>Lead Source</span></th>';
            $formstable .= '<th>Closing Date</th>';
            $formstable .= '<th>Deal Type</th>';
            $formstable .= '<th>Change Deal Stage</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($deals as $formdetails) {
                $formstable .= '<tr>';

                // $leadimage = ($formdetails['tbl_leads']['picture'] != '') ? $formdetails['tbl_leads']['picture'] : '/uploads/default/leads.png';
                $leadimage = ($formdetails->picture != '') ? $formdetails->picture : '/uploads/default/leads.png';


                $leadsource = ($formdetails->leadsource != '') ? $formdetails->leadsource : '';


                $product = ($formdetails->product != '') ? $formdetails->product : '';

                $acc_id = $formdetails->acc_id;

                $company = '';
                if ($formdetails->c_id > 0) {
                    $comp = Company::find($formdetails->c_id);
                    $company = ($comp != '') ? $comp->c_name : '';
                }


                $account = '';

                if ($acc_id > 0) {
                    $accountDetails = Tbl_Accounts::find($acc_id);
                    $account = $accountDetails->name;
                    // $company = $accountDetails->company;
                }

                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->deal_id . '"><label class="custom-control-label" for="' . $formdetails->deal_id . '"></label></div></td>';
                $formstable .= '<td class="table-title"><h6><a href="' . url('deals/' . $formdetails->deal_id) . '">' . $formdetails->name . '</a></h6><label style="border: 0;border-radius: 2px;font-size: 11px;font-weight: 400;padding: 1px 5px;width:80px;text-align: center;color: #fff;background-color:#' . $formdetails->color . '">' . $formdetails->salesfunnel . '</label></td>';
                $formstable .= '<td><img src="' . url($leadimage) . '" class="avatar"><a href="' . url('leads/' . $formdetails->ld_id) . '" target="_blank">&nbsp;' . $formdetails->first_name . ' ' . $formdetails->last_name . '</a></td>';
                $formstable .= '<td>' . $account . '</td>';
                $formstable .= '<td>' . $company . '</td>';
                $formstable .= '<td>' . $product . '</td>';
                $formstable .= '<td>' . $currency->html_code . ' ' . $formdetails->value . '</td>';
                $formstable .= '<td>' . $leadsource . '</td>';
                $formstable .= '<td>' . date('m-d-Y', strtotime($formdetails->closing_date)) . '</td>';
                $formstable .= '<td>' . $formdetails->deal_type . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                      </button>
                      <div class="dropdown-menu">';
                foreach ($dealstage as $stage) {
                    if ($formdetails->sfun_id != $stage->sfun_id) {
                        $formstable .= "<a class='dropdown-item text-default text-btn-space' href=" . url('deals/changestage/' . $formdetails->deal_id . '/' . $stage->sfun_id) . ">" . $stage->salesfunnel . "</a>";
                    }
                }
                $formstable .= '</div></div>';

                $formstable .= '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                      </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item text-default text-btn-space" href="' . url('deals/' . $formdetails->deal_id . '/edit') . '">Edit</a>
                          <a class="dropdown-item text-default text-btn-space" href="' . url('deals/delete/' . $formdetails->deal_id) . '">Delete</a>
                        </div>
                    </div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return $data;
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_deals::whereIn('deal_id', $ids)->update(array('active' => 0));
    }

    public function restoreAll(Request $request)
    {
        $ids = $request->input('id');
        return Tbl_deals::whereIn('deal_id', $ids)->update(array('active' => 1));
    }

    public function createDeal($id)
    {
        $uid = Auth::user()->id;

        $leadDetails = Tbl_leads::find($id);
        $data['leaddetails'] = $leadDetails;

        $leadsource = Tbl_leadsource::all();
        $leadsourceoptions = "<option value='0'>Select Lead Source</option>";
        if (count($leadsource) > 0) {
            foreach ($leadsource as $source) {
                $leadsourceoptions .= "<option value='" . $source->ldsrc_id . "'>" . $source->leadsource . "</option>";
            }
        }
        $data['leadsourceoptions'] = $leadsourceoptions;

        $dealstage = Tbl_salesfunnel::all();
        $dealstageoptions = "<option value=''>Select Deal Stage</option>";
        if (count($dealstage) > 0) {
            foreach ($dealstage as $stage) {
                $dealstageoptions .= "<option value='" . $stage->sfun_id . "'>" . $stage->salesfunnel . "</option>";
            }
        }
        $data['dealstageoptions'] = $dealstageoptions;

        $user = User::with('currency')->find($uid)->toArray();
        $data['user'] = $user;


        //  Products
        $products = Tbl_products::where('active', 1)->get();    //where('uid', $uid)->
        $productoptions = '<option value="0">Select ...</option>';
        foreach ($products as $product) {
            //            $productselected = (($deals->tbl_products != '') && ($deals->tbl_products->pro_id == $product->pro_id)) ? 'selected' : '';
            $productoptions .= '<option value="' . $product->pro_id . '" >' . $product->name . '</option>';
        }
        $data['productoptions'] = $productoptions;

        //  Deal Type Options
        $dtypes = Tbl_deal_types::all();    //where('uid', $uid)->
        $dtypeoptions = '<option value="">Select Deal Type...</option>';
        foreach ($dtypes as $dtype) {
            $dtypeoptions .= '<option value="' . $dtype->dl_id . '">' . $dtype->type . '</option>';
        }
        $data['dealtype_options'] = $dtypeoptions;

        return view('auth.deals.createdeal')->with('data', $data);
    }

    public function createCustomer($id)
    {
        $uid = Auth::user()->id;

        $leadDetails = Tbl_leads::find($id);
        $data['leaddetails'] = $leadDetails;

        $leadsource = Tbl_leadsource::all();
        $leadsourceoptions = "<option value='0'>Select Lead Source</option>";
        if (count($leadsource) > 0) {
            foreach ($leadsource as $source) {
                $leadsourceoptions .= "<option value='" . $source->ldsrc_id . "'>" . $source->leadsource . "</option>";
            }
        }
        $data['leadsourceoptions'] = $leadsourceoptions;

        $dealstage = Tbl_salesfunnel::all();
        $salesstage = 1;

        if (count($dealstage) > 0) {
            foreach ($dealstage as $stage) {
                if (strtolower($stage->salesfunnel) == strtolower('won')) {
                    $salesstage = $stage->sfun_id;
                }
            }
        }
        $data['dealstage'] = $salesstage;

        $user = User::with('currency')->find($uid)->toArray();
        $data['user'] = $user;

        //  Products
        $products = Tbl_products::where('active', 1)->get();    //where('uid', $uid)->
        $productoptions = '<option value="0">Select ...</option>';
        foreach ($products as $product) {
            //            $productselected = (($deals->tbl_products != '') && ($deals->tbl_products->pro_id == $product->pro_id)) ? 'selected' : '';
            $productoptions .= '<option value="' . $product->pro_id . '" >' . $product->name . '</option>';
        }
        $data['productoptions'] = $productoptions;

        //  Deal Type Options
        $dtypes = Tbl_deal_types::all();    //where('uid', $uid)->
        $dtypeoptions = '<option value="">Select Deal Type...</option>';
        foreach ($dtypes as $dtype) {
            $dtypeoptions .= '<option value="' . $dtype->dl_id . '">' . $dtype->type . '</option>';
        }
        $data['dealtype_options'] = $dtypeoptions;

        return view('auth.deals.createcustomer')->with('data', $data);
    }

    public function dealsFilter(Request $request)
    {
        // return json_encode($request->input());
        // exit();

        $type = $request->input('type');
        $start = ($request->input('start') != '') ? date('Y-m-d', strtotime($request->input('start'))) : '';
        $end = ($request->input('end') != '') ? date('Y-m-d', strtotime($request->input('end'))) : '';

        $uid = Auth::user()->id;

        $deals = $this->getDeals($uid, $start, $end, $type);

        $timer = $this->getFilterTime($start, $end);
        $deals['timer'] = $timer;

        $dealstageVal = 'All';
        if ($type > 0) {
            $dstages = Tbl_salesfunnel::find($type);
            $dealstageVal = $dstages->salesfunnel;
        }
        $deals['dealstageVal'] = $dealstageVal;

        return json_encode($deals);
    }

    public function kanban()
    {
        //->with('data', $data)
        $type = 0;
        $uid = Auth::user()->id;
        $start = '';
        $end = '';

        $data = $this->getDealsKanban($uid, $start, $end, $type);
        // echo json_encode($data);
        return view('auth.deals.demo')->with('data', $data);
    }

    public function getDealsKanban($uid, $start, $end, $type)
    {
        $dstages = Tbl_salesfunnel::all();

        $uid = Auth::user()->id;
        $user = User::find($uid);
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);

        $stageKanban = '';
        // $stageIds = array();
        foreach ($dstages as $dstage) {

            $stageName = $dstage->salesfunnel;
            $sfunId = $dstage->sfun_id;
            $stageNameId = str_replace(" ", "_", $stageName) . '_' . $sfunId;
            $stageNameIdAmount = str_replace(" ", "_", $stageName) . '_' . $sfunId . '_Amount';

            // $stageIds[] = "#" . $stageNameId;

            $stageAmount = Tbl_deals::where('uid', $uid)->where('active', 1)->where('sfun_id', $sfunId)->sum('value');
            // echo 


            //-----------------------------------------
            $query = DB::table('tbl_deals')->where('tbl_deals.active', 1);
            $query->where('tbl_deals.uid', $uid);
            $query->where('tbl_deals.sfun_id', $sfunId);
            if (($start != '') && ($end != '')) {
                $query->whereBetween(DB::raw('DATE(tbl_deals.closing_date)'), [$start, $end]);
            }
            $query->leftJoin('tbl_leads', 'tbl_leads.ld_id', '=', 'tbl_deals.ld_id');
            $query->select(
                'tbl_deals.*',
                'tbl_leads.first_name as first_name',
                'tbl_leads.last_name as last_name',
                'tbl_leads.picture'
            );
            $deals = $query->get();

            $stageKanban .= '<div class="col-md-3">
            <div class="card card-default">
                <div class="card-header kanban-card">
                    <h3 class="card-title">' . $stageName . '</h3>
                    <span id="' . $stageNameIdAmount . '"> ' . $currency->html_code . ' ' . $stageAmount . '</span>
                </div>
                <div class="card-body">
                    <div id="' . $stageNameId . '" class="dlstage">&nbsp;';

            if (count($deals) > 0) {

                foreach ($deals as $deal) {

                    $pro_id = $deal->pro_id;
                    $pro_name = '';
                    $c_name = '';
                    if ($pro_id > 0) {
                        $products = Tbl_products::find($pro_id);
                        $pro_name = $products->name;
                        if ($products->company > 0) {
                            $company = Company::find($products->company);
                            $c_name = ($company != '') ? $company->c_name : '';
                        }
                    }


                    $dealId = "'" . 'card_' . $deal->deal_id . "'";

                    $leadimage = ($deal->picture != '') ? $deal->picture : '/uploads/default/leads.png';

                    $stageKanban .= '<div id=' . $dealId . ' class="kanban-card well" role="alert">
                    <div class="callout callout-success">
      
                    <div class="info-box-content">
                      <span class="info-box-text"><b><img src="' . url($leadimage) . '" class="avatar"><a href=' . url('deals/' . $deal->deal_id) . '>' . $deal->name . '</a></b></span>
                      <span class="info-box-text">' . $deal->first_name . ' ' . $deal->last_name . '</span>
                      <span class="info-box-number">
                      <span>' . $currency->html_code . '</span><span>' . $deal->value . '</span><br>
                      <span>' . $pro_name . '</span><br><span>' . $c_name . '</span>
                      <div class="info-box-text"><a style="text-decoration:none;color:#000;" href=' . url('deals/' . $deal->deal_id . '/edit') . '><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp;<a href=' . url('deals/delete/' . $deal->deal_id) . '><i class="fas fa-trash" aria-hidden="true"></i></a></div>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
      
                    </div>';
                }
            }

            $stageKanban .= '</div>
                </div>
            </div>
        </div>';
        }

        $data['stageKanban'] = $stageKanban;
        return $data;
    }

    public function getStageAmount($sfun_id)
    {
        $uid = Auth::user()->id;
        $user = User::find($uid);
        $cr_id = $user->cr_id;
        $currency = currency::find($cr_id);

        $stageAmount = Tbl_deals::where('uid', $uid)->where('sfun_id', $sfun_id)->where('active', 1)->sum('value');
        return '<span>' . $currency->html_code . '</span> ' . $stageAmount;
    }


    public function getCloseDates()
    {
        $uid = Auth::user()->id;

        $oldDate = Tbl_deals::where('uid', $uid)->where('active', 1)->where(DB::raw('DATE(closing_date)'), '!=', '')->orderBy('closing_date', 'asc')->first();
        // echo json_encode($oldDate);

        $cDate = ($oldDate != '') ? date('Y-m-d', strtotime($oldDate->closing_date)) : date('Y-m-d');
        // if ($oldDate != '') {
        //     $cDate = date('Y-m-d', strtotime($oldDate->closing_date));
        // }
        $data['oDateClose'] = $cDate;

        $latDate = Tbl_deals::where('active', 1)->where(DB::raw('DATE(closing_date)'), '!=', '')->orderBy('closing_date', 'desc')->first();
        // echo json_encode($oldDate);

        $lDate = ($oldDate != '') ? date('Y-m-d', strtotime($latDate->closing_date)) : date('Y-m-d');
        // if ($latDate != '') {
        //     $lDate = date('Y-m-d', strtotime($latDate->closing_date));
        // }
        $data['lDateClose'] = $lDate;

        return $data;
    }

    public function getFilterTime($start, $end)
    {
        $getCloseDates = $this->getCloseDates();

        $oldDate = $getCloseDates['oDateClose'];
        $latestDate = $getCloseDates['lDateClose'];

        $today = date('Y-m-d');

        $yesterday = date('Y-m-d', strtotime("-1 days"));

        $sevendays = date('Y-m-d', strtotime("-6 days"));

        $thirtydays = date('Y-m-d', strtotime("-29 days"));

        $current_month_form = date('Y-m-01', strtotime(date('Y-m-d')));

        $current_month_to =  date('Y-m-t', strtotime(date('Y-m-d')));

        $last_month_form = date('Y-m-01', strtotime('-1 MONTH'));

        $last_month_to =  date('Y-m-t', strtotime(date('Y-m-01') . ' -1 MONTH'));

        $timer = "";

        if (($start ==  $oldDate) && ($end == $latestDate)) {
            $timer = "All";
        } else if (($start ==  $yesterday) && ($end == $yesterday)) {
            $timer = "Yesterday";
        } else if ($start ==  $end) {
            $timer = "Today";
        } else if (($start ==  $sevendays) && ($end == $today)) {
            $timer = "Last 7 Days";
        } else if (($start ==  $thirtydays) && ($end == $today)) {
            $timer = "Last 30 Days";
        } else if (($start ==  $current_month_form) && ($end == $current_month_to)) {
            $timer = "This Month";
        } else if (($start ==  $last_month_form) && ($end == $last_month_to)) {
            $timer = "Last Month";
        } else {
            $start = date('m-d-Y', strtotime($start));
            $end = date('m-d-Y', strtotime($end));

            $timer = "From:" . $start . ' To:' . $end;
        }

        return $timer;
    }

    // public function viewDealsTypeSession(Request $request, $id)
    // {
    //     $req = $request->input('type');
    //     // echo json_encode($req);
    //     $request->session()->forget('viewType');
    //     $request->session()->put('viewType', $req);
    //     $viewType = $request->session()->get('viewType');

    //     return $viewType;
    // }
}
