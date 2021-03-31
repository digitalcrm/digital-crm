<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//  Models
use App\Tbl_projects;
use App\Tbl_project_status;
use App\User;
use App\Tbl_project_members;
use App\Tbl_deals;
use Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('test:project-list', ['only' => ['index', 'show']]);
        $this->middleware('test:project-create', ['only' => ['create', 'store']]);
        $this->middleware('test:project-edit', ['only' => ['edit', 'update']]);
        $this->middleware('test:project-delete', ['only' => ['destroy', 'delete', 'deleteAllProjects']]);
        // $this->middleware('test:project-import', ['only' => ['import', 'importData']]);
        // $this->middleware('test:project-export', ['only' => ['export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $uid = Auth::user()->id;

        $manager = 'All';
        $type = 'All';
        $auser = 'All';
        $uid = 'All';
        $data = $this->getProjectList($uid, $manager, $type, $auser);

        // User Options
        $users = User::where('user_type', 1)->orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        $data['useroptions'] = $useroptions;

        // Manager Options
        $musers = User::where('user_type', 2)->get();

        $museroptions = "<option value='All'>All</option>";
        foreach ($musers as $muser) {
            $museroptions .= "<option value='" . $muser->id . "'>" . $muser->name . "</option>";
        }

        $data['museroptions'] = $museroptions;

        $ausers = User::whereIn('user_type', [3, 4])->get();
        $auseroptions = "<option value='All'>All</option>";
        foreach ($ausers as $auser) {
            if ($auser->user_type == 3) {
                $desg = 'Analyst';
            }
            if ($auser->user_type == 4) {
                $desg = 'Sub Analyst';
            }

            $auseroptions .= "<option value='" . $auser->id . "'>" . $auser->name . " (" . $desg . ")</option>";
        }
        $data['auseroptions'] = $auseroptions;

        $data['managerName'] = $manager;
        $data['auserName'] = 'All';
        $data['userVal'] = 'All';

        return view('admin.projects.index')->with("data", $data);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Tbl_projects::with('tbl_project_status')->with('tbl_project_members')->find($id);
        // echo json_encode($project);
        // exit();

        $promembers = $project->tbl_project_members;
        $members = ($promembers != '') ? count($promembers) : 0;
        $project['total_members'] = $members;
        // $manager = User::find($project->manager);
        // $project['project_manager'] = $manager->name;

        $project_manager = '';
        if ($project->manager > 0) {
            $manager = User::find($project->manager);
            $project_manager = $manager->name;
        }
        $project['project_manager'] = $project_manager;



        $table = "<table class='table'>";
        $table .= "<tr>";
        $table .= "<td>Member</td><td>Weight</td>";
        $table .= "</tr>";
        if ($members > 0) {
            foreach ($promembers as $promember) {
                $user = User::with('Tbl_user_types')->find($promember->uid);
                // echo json_encode($user);
                // exit();

                if ($user != '') {



                    $table .= "<tr>";
                    $table .= "<td>" . $user->name . "</td><td>" . (($user->tbl_user_types != '') ? $user->tbl_user_types->weight : 0) . "</td>";
                    $table .= "</tr>";
                }
            }
            $table .= "</table>";
        } else {
            $table = "No records available";
        }
        $project['table'] = $table;

        return view('admin.projects.show')->with("data", $project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Tbl_projects::with('tbl_project_members')->with('tbl_project_status')->find($id);
        $promembers = $project->tbl_project_members;
        // echo json_encode($project);

        $prostatuses = Tbl_project_status::all();
        $prostatusoptions = "<option value='0'>Select Project Status</option>";
        foreach ($prostatuses as $prostatus) {
            $prostatusSelect = (($project->tbl_project_status != '') && ($project->tbl_project_status->ps_id == $prostatus->ps_id)) ? 'selected' : '';
            $prostatusoptions .= "<option value='" . $prostatus->ps_id . "' " . $prostatusSelect . ">" . $prostatus->status . "</option>";
        }
        $project['prostatusoptions'] = $prostatusoptions;

        $musers = User::where('user_type', 2)->get();
        $museroptions = "<option value=''>Select Manager</option>";
        foreach ($musers as $muser) {
            $musereslected = ($muser->id == $project->manager) ? 'selected' : '';
            $museroptions .= "<option value='" . $muser->id . "' " . $musereslected . ">" . $muser->name . "</option>";
        }
        $project['museroptions'] = $museroptions;

        // echo $museroptions;
        // exit();

        $ausers = User::whereIn('user_type', [3, 4])->with('Tbl_user_types')->get();
        $auseroptions = "<option value=''>Select Analysts</option>";
        foreach ($ausers as $auser) {
            $weight = ($auser->tbl_user_types != '') ? $auser->tbl_user_types->weight : 0;
            $auseroptions .= "<option value='" . $auser->id . '|' . $weight . "'>" . $auser->name . "</option>";
        }

        $project['auseroptions'] = $auseroptions;
        $memberHtml = '';
        $k = 1;


        if (count($promembers) > 0) {


            foreach ($promembers as $promember) {

                $userWeight = User::with('Tbl_user_types')->find($promember->uid);
                if ($userWeight != '') {

                    $anuseroptions = "<option value=''>Select Analysts</option>";
                    foreach ($ausers as $auser) {
                        $anusereslected = ($auser->id == $promember->uid) ? 'selected' : '';
                        $anuseroptions .= "<option value='" . $auser->id . '|' . $auser->weight . "' " . $anusereslected . ">" . $auser->name . "</option>";
                    }



                    $calRowid = "calRow" . $k;
                    $removeId = "remove" . $k;
                    $auserId = "auser" . $k;
                    $userId = "user" . $k;
                    $pro_codeId = "weight" . $k;

                    $pro_codeIdArg = '"' . $pro_codeId . '"';
                    $userIdArg = '"' . $userId . '"';
                    $removeIdArg = "'" . $calRowid . "'";
                    $auserIdArg = '"' . $auserId . '"';

                    $memberHtml .= '<div class="row calRows col-12" id="' . $calRowid . '">';
                    $memberHtml .= '<div class="col-4">';
                    $memberHtml .= "<select class='form-control ausers' id='" . $auserId . "' name='ausers[]' onchange='return analystUser(" . $auserIdArg . "," . $pro_codeIdArg . "," . $userIdArg . ");'>";
                    $memberHtml .= $anuseroptions;
                    $memberHtml .= '</select>';
                    $memberHtml .= '<input type="hidden" value="' . $promember->uid . '" class="userIds" name="userIds[]"  id="' . $userId . '"/>';
                    $memberHtml .= '</div>';


                    if ($k == 1) {
                        // $memberHtml .= '<span class="input-group-text" id="basic-addon2">  <small><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add Member</small></span>';

                        $memberHtml .= '<div class="col-2">';
                        $memberHtml .= '<div class="form-group">';
                        $memberHtml .= '<input type="text" class="form-control weights" name="weight[]" id="' . $pro_codeId . '" placeholder="" value="' . (($userWeight->tbl_user_types != '') ? $userWeight->tbl_user_types->weight : 0) . '" required>';
                        $memberHtml .= '</div>';
                        $memberHtml .= '</div>';
                        $memberHtml .= '<div class="col-2">';
                        $memberHtml .= '<div class="form-group">';
                        $memberHtml .= '<input type="button" value="Add Member" class="btn btn-default" id="basic-addon2"/>';
                        $memberHtml .= '</div>';
                        $memberHtml .= '</div>';
                    } else {
                        $memberHtml .= '<div class="col-2">';
                        $memberHtml .= '<div class="form-group">';
                        $memberHtml .= '<div class="input-group mb-3">';
                        $memberHtml .= '<input type="text" class="form-control weights" name="weight[]" id="' . $pro_codeId . '" placeholder="" value="' . (($userWeight->tbl_user_types != '') ? $userWeight->tbl_user_types->weight : 0) . '" required>';
                        $memberHtml .= '<div class="input-group-append">';
                        $memberHtml .= '<span class="input-group-text" id="' . $removeId . '" onclick="return removeRow(' . $removeIdArg . ')"><i class="fa fa-window-close" aria-hidden="true"></i></span>';
                        $memberHtml .= '</div>';
                        $memberHtml .= '</div>';
                        $memberHtml .= '</div>';
                        $memberHtml .= '</div>';
                    }


                    $memberHtml .= '</div>';
                    // $memberHtml .= '</div>';

                    $k++;
                } else {

                    $ausers = User::whereIn('user_type', [3, 4])->with('Tbl_user_types')->get();
                    $auseroptions = "<option value=''>Select Analysts</option>";
                    foreach ($ausers as $auser) {
                        $weight = ($auser->tbl_user_types != '') ? $auser->tbl_user_types->weight : 0;
                        $auseroptions .= "<option value='" . $auser->id . '|' . $weight . "'>" . $auser->name . "</option>";
                    }
                    $data['auseroptions'] = $auseroptions;

                    $memberHtml .= '<div class="row calRows col-12" id="calRow">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pro_code">Analysts</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control ausers" id="auser" name="ausers[]" onchange=\'return analystUser("auser","weight","userId");\'>
                                        ' . $auseroptions . '
                                        </select>
                                        <input type="hidden" value="" id="userId" class="userIds" name="userIds[]" />
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="pro_code">Weight</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control weights" name="weight[]" id="weight" placeholder="" value="" required>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <input type="button" value="Add Member" class="btn btn-default" id="basic-addon2" />
                                    </div>
                                </div>
                            </div>';
                }
            }
        } else {
            $ausers = User::whereIn('user_type', [3, 4])->with('Tbl_user_types')->get();
            $auseroptions = "<option value=''>Select Analysts</option>";
            foreach ($ausers as $auser) {
                $weight = ($auser->tbl_user_types != '') ? $auser->tbl_user_types->weight : 0;
                $auseroptions .= "<option value='" . $auser->id . '|' . $weight . "'>" . $auser->name . "</option>";
            }
            $data['auseroptions'] = $auseroptions;

            $memberHtml .= '<div class="row calRows col-12" id="calRow">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pro_code">Analysts</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <select class="form-control ausers" id="auser" name="ausers[]" onchange=\'return analystUser("auser","weight","userId");\'>
                                        ' . $auseroptions . '
                                        </select>
                                        <input type="hidden" value="" id="userId" class="userIds" name="userIds[]" />
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="pro_code">Weight</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="text" class="form-control weights" name="weight[]" id="weight" placeholder="" value="" required>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <input type="button" value="Add Member" class="btn btn-default" id="basic-addon2" />
                                    </div>
                                </div>
                            </div>';
        }



        $project['memberHtml'] = $memberHtml;

        return view('admin.projects.edit')->with("data", $project);
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
        //
        $uid = Auth::user()->id;

        $weights = $request->input('weight');
        $userIds  = $request->input('userIds');

        $this->validate($request, [
            'name' => 'required|unique:tbl_projects,name,' . $id . ',project_id',
            'code' => 'required|unique:tbl_projects,code,' . $id . ',project_id',
            'type' => 'required',
            'forecast' => 'required',
            'manager' => 'required',
            'total_forecast' => 'required',
        ]);

        $startDate = ($request->input('creation_date') != '') ? date('Y-m-d', strtotime($request->input('creation_date'))) : NULL;
        $endDate = ($request->input('submission_date') != '') ? date('Y-m-d', strtotime($request->input('submission_date'))) : NULL;
        $client_submit = ($request->input('client_submit') != '') ? date('Y-m-d', strtotime($request->input('client_submit'))) : NULL;

        $formdata = array(
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'type' => $request->input('type'),
            'forecast' => $request->input('forecast'),
            'manager' => $request->input('manager'),
            'total_forecast' => $request->input('total_forecast'),
            'deal_id' => 0,
            'description' => $request->input('description'),
            'members' => '',
            'weights' => '',
            'ps_id' => $request->input('ps_id'),
            'creation_date' => $startDate,
            'submission_date' => $endDate,
            'actual_days' => $request->input('actual_days'),

            'leaves' => $request->input('leaves'),
            'different_project' => $request->input('different_project'),
            'company_activity' => $request->input('company_activity'),
            'working' => $request->input('working'),
            'other' => $request->input('other'),
            'total_days' => $request->input('total_days'),
            'marketing_collateral' => $request->input('marketing_collateral'),
            'sample_pages' => $request->input('sample_pages'),
            'client_submit' => $client_submit,
            'feedback' => $request->input('feedback'),
        );

        $project = Tbl_projects::where('project_id', $id)->update($formdata);

        if ($project) {
            Tbl_project_members::where('project_id', $id)->delete();

            $project_id = $id;
            $pmArr = array();
            if (count($userIds) > 0) {

                foreach ($userIds as $key => $userid) {
                    $pmArr[] = array('project_id' => $project_id, 'uid' => $userid);
                }

                Tbl_project_members::insert($pmArr);
            }
            return redirect('admin/projects')->with('success', 'Project Updated Successfully...!');
        } else {
            return redirect('admin/projects')->with('error', 'Error occurred. Please try again...!');
        }
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


    //  Project Status Options

    public function getProjectStatusOptions($id)
    {
        $option = '';

        if ($id == 0) {
            // echo 'Zero : ' . $id;

            $option = "<option value=''>Select Project Status...</option>";
        }

        if ($id === 'All') {
            // echo 'All : ' . $id;
            $option = "<option value='All'>All</option>";
        }
        // exit();

        $statues = Tbl_project_status::all();
        foreach ($statues as $status) {
            $selected = ($id == $status->ps_id) ? "selected" : '';
            $option .= "<option value='" . $status->ps_id . "' " . $selected . ">" . $status->status . "</option>";
        }
        return $option;
    }

    public function getProjectList($uid, $manager, $type, $auser)
    {

        // $projects = Tbl_projects::where('uid', $uid)->where('active', 1)->with('tbl_project_members')->get();
        // echo json_encode($projects);
        // exit();
        $projecIds = array();
        if ($auser > 0) {
            $projectMembers = Tbl_project_members::where('uid', $auser)->get(['project_id']);
            // return $projectMembers;

            foreach ($projectMembers as $members) {
                $projecIds[] = $members->project_id;
            }
        }

        $projects = '';

        //-----------------------------------------
        $query = DB::table('tbl_projects')->where('tbl_projects.active', 1);
        if ($uid > 0) {
            $query->where('tbl_projects.uid', $uid);
        }
        if ($manager > 0) {
            $query->where('tbl_projects.manager', $manager);
        }
        if (($auser > 0) && (count($projecIds) > 0)) {
            $query->whereIn('tbl_projects.project_id', $projecIds);
            // $query->where('tbl_projects.manager', $manager);
        }

        $query->leftJoin('tbl_project_status', 'tbl_projects.ps_id', '=', 'tbl_project_status.ps_id');
        $query->leftJoin('users', 'tbl_projects.manager', '=', 'users.id');
        $query->orderBy('tbl_projects.project_id', 'desc');
        $query->select(
            'tbl_projects.*',
            'users.name as manager_name',
            'tbl_project_status.status'
        );
        $projects = $query->get();
        // echo json_encode($projects);
        // exit();

        $total = $projects->count();


        if ($total > 0) {
            $formstable = '<table id="dealsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th width="2"></th>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Code</th>';
            $formstable .= '<th>Type</th>';
            $formstable .= '<th>Status</th>';
            $formstable .= '<th>Forecast</th>';
            $formstable .= '<th>Manager</th>';
            $formstable .= '<th>Members</th>';
            $formstable .= '<th>Total Forecast</th>';
            $formstable .= '<th>Creation Date</th>';
            $formstable .= '<th>Submission Date</th>';
            $formstable .= '<th>Created at</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '<th class="none">Notes</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($projects as $formdetails) {

                $members = Tbl_project_members::where('project_id', $formdetails->project_id)->count();

                // $manager = User::find($formdetails->manager);
                $type = "";

                if ($formdetails->type == 1) {
                    $type = "Syndicate";
                }

                if ($formdetails->type == 2) {
                    $type = "Custom";
                }

                $creationDate = (($formdetails->creation_date != NULL)) ? date('d-m-Y', strtotime($formdetails->creation_date)) : '';
                $submissionDate = (($formdetails->submission_date != NULL)) ? date('d-m-Y', strtotime($formdetails->submission_date)) : '';

                $formstable .= '<tr>';
                $formstable .= '<td><div class="custom-control custom-checkbox"><input type="checkbox" class="checkAll custom-control-input" id="' . $formdetails->project_id . '" value="' . $formdetails->project_id . '"><label class="custom-control-label" for="' . $formdetails->project_id . '"></label></div></td>';
                $formstable .= '<td class="table-title"><label class=""><a href="' . url('admin/projects/' . $formdetails->project_id) . '">' . $formdetails->name . '</a></label></td>';
                $formstable .= '<td>' . $formdetails->code . '</td>';
                $formstable .= '<td>' . $type . '</td>';
                $formstable .= '<td>' . $formdetails->status . '</td>';
                $formstable .= '<td>' . $formdetails->forecast . '</td>';
                $formstable .= '<td>' . $formdetails->manager_name . '</td>';
                $formstable .= '<td>' . $members . '</td>';
                $formstable .= '<td>' . $formdetails->total_forecast . '</td>';
                $formstable .= '<td>' . $creationDate . '</td>';
                $formstable .= '<td>' . $submissionDate . '</td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails->created_at)) . '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                      </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item text-default text-btn-space" href="' . url('admin/projects/' . $formdetails->project_id . '/edit') . '">Edit</a>
                          <a class="dropdown-item text-default text-btn-space" href="' . url('admin/projects/delete/' . $formdetails->project_id) . '">Delete</a>
                        </div>
                    </div>';
                $formstable .= '</td>';
                $formstable .= '<td>' . $formdetails->description . '</td>';
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

    public function ajaxGetProjectsList(Request $request)
    {
        // $uid = Auth::user()->id;
        $user = $request->input('user');
        $manager = $request->input('manager');
        $auser = $request->input('auser');
        $type = 'All';
        $data = $this->getProjectList($user, $manager, $type, $auser);

        // $managerName = 'All';
        // $auserName = 'All';

        // if ($manager > 0) {
        //     $Manuser = User::find($manager);
        //     $managerName = $Manuser->name;
        // }

        // if ($auser > 0) {
        //     $Auserval = User::find($auser);
        //     $auserName = $Auserval->name;
        // }

        // if ($user > 0) {
        //     $usersval = User::find($user);
        //     $userval = $usersval->name;
        // }

        // $data['managerName'] = $managerName;
        // $data['auserName'] = $auserName;
        // $data['userval'] = $userval;

        echo json_encode($data);
    }

    public function getSubmissionDate(Request $request)
    {
        //  $startDate, $days
        $startDate = $request->input('startDate');
        $days = round($request->input('days'));
        // return $days;

        $days -= 1;

        if ($days > 1) {

            $startDate =  date('Y-m-d', strtotime($startDate));
            $endDate = $this->addDaystoDate($startDate, $days);
            // return date('d-m-Y', strtotime($endDate));

            $hdays = $this->CalculateSaturdaySundayBetweenDates($startDate, $endDate);

            // return date('d-m-Y', strtotime($endDate)) . ' ' . $hdays;
            if ($hdays > 0) {
                $endDate = $this->addDaystoDate($endDate, $hdays);
            }

            return date('d-m-Y', strtotime($endDate));
        } else {
            return $startDate;
        }
    }

    public function addDaystoDate($date, $days)
    {
        // $add = '+ 1 day';
        // if ($days > 1) {
        $add = '+ ' . $days . ' days';
        // }

        return date('Y-m-d', strtotime($date . $add));
    }

    public function CalculateSaturdaySundayBetweenDates($startDate, $endDate)
    {

        // $dt1 = "2020-01-26";
        // $dt2 = "2020-02-26";
        $dt = array();
        $tm1 = strtotime($startDate);
        $tm2 = strtotime($endDate);

        for ($i = $tm1; $i <= $tm2; $i = $i + 86400) {
            // Saturday
            if (date("w", $i) == 6) {
                $dt[] = date("l Y-m-d ", $i);
            }
            // Sunday
            if (date("w", $i) == 0) {
                $dt[] = date("l Y-m-d ", $i);
            }
        }

        return count($dt);
    }

    public function deleteAllProjects(Request $request)
    {
        // echo json_encode($request->input());

        $ids = $request->input('id');
        return Tbl_projects::whereIn('project_id', $ids)->update(array('active' => 0));
    }

    public function delete($id)
    {
        $account = Tbl_projects::where('project_id', $id)->update(array('active' => 0));
        if ($account) {
            return redirect('admin/projects')->with('success', 'Project Deleted Successfully...!');
        } else {
            return redirect('admin/projects')->with('error', 'Error occurred. Please try again...!');
        }
    }

    public function createProject($id)
    {
        $deal = Tbl_deals::find($id);

        $formdata = array(
            'uid' => $deal->uid,
            'name' => $deal->name,
            'code' => NULL,
            'type' => 1,
            'forecast' => 0,
            'manager' => 0,
            'total_forecast' => 0,
            'deal_id' => $deal->deal_id,
            'description' => NULL,
            'members' => '',
            'weights' => '',
            'ps_id' => 5,
            'creation_date' => NULL,
            'submission_date' => NULL,
            'actual_days' => 0,
        );

        $project = Tbl_projects::create($formdata);

        if ($project->project_id > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
