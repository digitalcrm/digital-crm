<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//  Imported
use Illuminate\Support\Facades\DB;
use Auth;
// Models
use App\Tbl_groups;
use App\User;
use App\Tbl_group_users;
use App\Tbl_products;
use App\Tbl_group_products;
use App\Tbl_product_forms;
use App\Tbl_fb_leads;
use App\Tbl_leads;
use App\currency;

class GroupController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Tbl_groups::where('active', 1)->with('tbl_group_users')->with('tbl_group_products')->orderBy('gid', 'desc')->get();
        //        echo json_encode($groups);
        //        exit();
        $total = count($groups);

        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Description</th>';
            $formstable .= '<th>Users</th>';
            $formstable .= '<th>Products</th>';
            $formstable .= '<th>Leads</th>';
            $formstable .= '<th>Date</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($groups as $formdetails) {

                $gusers = ($formdetails['tbl_group_users'] != '') ? count($formdetails['tbl_group_users']) : 0;
                $gproducts = ($formdetails['tbl_group_products'] != '') ? count($formdetails['tbl_group_products']) : 0;

                $leads = 0;
                if ($gusers > 0) {
                    $leads = Tbl_leads::whereIn('uid', explode(",", $formdetails->users))->count();
                }

                $formstable .= '<tr>';
                $formstable .= '<td class="table-title"><a href="' . url('admin/groups/' . $formdetails['gid']) . '">' . $formdetails['name'] . '</a></td>';
                $formstable .= '<td >' . substr($formdetails['description'], 0, 25) . '</td>';
                $formstable .= '<td><a href="' . (($gusers > 0) ? url('admin/groups/users/' . $formdetails['gid']) : '#') . '">' . $gusers . '</a></td>';
                $formstable .= '<td>' . $gproducts . '</td>';
                $formstable .= '<td><a href="' . (($gusers > 0) ? url('admin/groups/userleads/' . $formdetails['gid'] . '/All') : '#') . '">' . $leads . '</a></td>';
                $formstable .= '<td>' . date('d-m-Y', strtotime($formdetails['created_at'])) . '</td>';
                $formstable .= '<td><div class="btn-group">
                  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/groups/' . $formdetails['gid'] . '/edit') . '">Edit</a>
                    <a class="dropdown-item text-default text-btn-space" href="' . url('admin/groups/delete/' . $formdetails['gid']) . '">Delete</a>
                  </div>
                </div></td>';
                $formstable .= '</tr>';
                // <a class="btn btn-danger" href="' . url('admin/groups/allocate/' . $formdetails['gid']) . '">Allocate</a>
                // <a class="btn btn-danger" href="' . url('admin/groups/assign/' . $formdetails['gid']) . '">Assign</a>

            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('admin.groups.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $users = User::where('active', 1)->with('tbl_group_users')->get(['id', 'name']);
        //        echo json_encode($users);
        //        exit();
        //        $useroptions = "<option value='0'>Select User...</option>";
        $useroptions = "";
        if (count($users) > 0) {
            foreach ($users as $user) {
                //                echo 'id : ' . $user->id . ' name : ' . $user->name . ' tbl_group_users : ' . count($user->tbl_group_users);
                //                echo '<br>';
                $disabled = "";
                $assigned = "";
                if (count($user->tbl_group_users) > 0) {
                    $disabled = "disabled";
                    $assigned = "(Assigned)";
                }
                $useroptions .= "<option value='" . $user->id . "' " . $disabled . ">" . $user->name . " " . $assigned . "</option>";
            }
        }
        $useroptions .= "<option value='none'>None</option>";
        //        exit();

        $data['useroptions'] = $useroptions;


        //  Products

        $products = Tbl_products::where('active', 1)->with('tbl_group_products')->get(['pro_id', 'name']);
        //        echo json_encode($products);
        //        exit();

        $productoptions = "<option value='0'>Select Product...</option>";
        if (count($products) > 0) {
            foreach ($products as $product) {
                $disabled = "";
                $assigned = "";
                if (count($product->tbl_group_products) > 0) {
                    $disabled = "disabled";
                    $assigned = "(Assigned)";
                }
                $productoptions .= "<option value='" . $product->pro_id . "' " . $disabled . ">" . $product->name . " " . $assigned . "</option>";
            }
            //            $productoptions .= "<option value='none'>None</option>";
        }

        $data['productoptions'] = $productoptions;


        return view('admin.groups.create')->with('data', $data);
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
            'name' => 'required|max:255|unique:tbl_groups',
        ]);



        $usersArr = ($request->input('selectUsers') != '') ? $request->input('selectUsers') : array();
        $users = (count($usersArr) > 0) ? implode(",", $usersArr) : '';

        $productsArr = ($request->input('selectProducts') != '') ? $request->input('selectProducts') : array();
        $products = (count($productsArr) > 0) ? implode(",", $productsArr) : '';

        //        echo json_encode($request->input());
        //        exit();

        $formdata = array(
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'users' => $users,
            'products' => $products,
        );

        $group = Tbl_groups::create($formdata);
        $gid = $group->gid;
        if ($gid > 0) {

            //Group Users
            $groupArr = array();
            $gpArr = array();

            if (count($usersArr) > 0) {

                foreach ($usersArr as $guser) {
                    if ((int) $guser > 0) {
                        $groupArr[] = array(
                            'uid' => (int) $guser,
                            'gid' => $gid,
                        );
                    }
                }
                if (count($groupArr) > 0) {
                    Tbl_group_users::insert($groupArr);
                }
            }

            //Group Products
            if (count($productsArr) > 0) {

                foreach ($productsArr as $gproduct) {

                    if ((int) $gproduct > 0) {
                        $gpArr[] = array(
                            'pro_id' => (int) $gproduct,
                            'gid' => $gid,
                        );
                    }
                }
                if (count($gpArr) > 0) {
                    Tbl_group_products::insert($gpArr);
                }
            }


            if ((count($groupArr) > 0) && (count($gpArr) > 0)) {
                return redirect('admin/groups/allocate/' . $gid)->with('success', 'Group Updated Successfully...!');
            } else {
                return redirect('admin/groups')->with('success', 'Group Updated Successfully...!');
            }

            //            return redirect('admin/groups/' . $gid)->with('success', 'Group Created Successfully...!');
        } else {
            return redirect('admin/groups')->with('error', 'Error. Please try again later...!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  Getting Group Details
        $group = Tbl_groups::with('tbl_group_users')->with('tbl_group_products')->find($id);
        $data['group'] = $group;


        $tbl_group_products = $group->tbl_group_products;

        //-------------------------------------------------------------
        $tbl_group_users = $group->tbl_group_users;
        //        $total = count($tbl_group_users);
        //        $data['total'] = $total;
        if (count($tbl_group_users) > 0) {
            $formstable = '<table id="" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            // $formstable .= '<th>Quota</th>';
            $formstable .= '<th>Leads</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($tbl_group_users as $formdetails) {
                $userDetails = User::with('tbl_leads')->find($formdetails->uid);

                $leads = Tbl_leads::where('uid', $formdetails->uid)->count();

                $formstable .= '<tr>';
                $formstable .= '<td><label>' . $userDetails->name . '</label></td>';
                // $formstable .= '<td><label>' . $formdetails->quota . ' %</label></td>';
                $formstable .= '<td><a href="' . url('admin/groups/userleads/' . $formdetails['gid'] . '/' . $formdetails->uid) . '">' . $leads . '</a></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable .= 'No Records Available';
        }

        $data['users_table'] = $formstable;

        // Products Table
        $formtable = 'No Records Available';
        $currency = currency::where('status', 1)->first();
        if (count($tbl_group_products) > 0) {
            $formtable = '<table id="" class="table">';
            $formtable .= '<thead>';
            $formtable .= '<tr>';
            $formtable .= '<th>Product</th>';
            $formtable .= '<th>Price</th>';
            $formtable .= '</tr>';
            $formtable .= '</thead>';
            $formtable .= '<tbody>';
            foreach ($tbl_group_products as $formdetails) {
                $userDetails = Tbl_products::find($formdetails->pro_id);
                $formtable .= '<tr>';
                $formtable .= '<td><label>' . $userDetails->name . '</label></td>';
                $formtable .= '<td><label>' . $currency->html_code . ' ' . $userDetails->price . '</label></td>';
                $formtable .= '</tr>';
            }
            $formtable .= '</tbody>';
            $formtable .= '</table>';
        }
        $data['products_table'] = $formtable;

        return view('admin.groups.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Tbl_groups::find($id);
        $data['group'] = $group;

        //        $tbl_group_users = ($group->users != '') ? explode(",", $group->users) : array();
        //        $tbl_group_products = ($group->products != '') ? explode(",", $group->products) : array();

        $users = User::where('active', 1)->with('tbl_group_users')->get(['id', 'name']);

        $useroptions = "";
        if (count($users) > 0) {
            foreach ($users as $user) {
                $selected = '';
                $assigned = '';
                $disabled = '';

                $tbl_group_users = $user->tbl_group_users;

                if (count($tbl_group_users) > 0) {
                    foreach ($tbl_group_users as $tgu) {
                        if ($tgu->gid == $id) {
                            $selected = 'selected';
                        } else {
                            $disabled = "disabled";
                            $assigned = "(Assigned)";
                        }
                    }
                }

                $useroptions .= "<option value='" . $user->id . "' " . $selected . " " . $disabled . ">" . $user->name . " " . $assigned . "</option>";
            }
        }
        $useroptions .= "<option value='none'>None</option>";

        $data['useroptions'] = $useroptions;


        //  Products

        $products = Tbl_products::where('active', 1)->with('tbl_group_products')->get(['pro_id', 'name']);
        $productoptions = "<option value='0'>Select Product...</option>";
        if (count($products) > 0) {
            foreach ($products as $product) {
                $selected = '';
                $assigned = '';
                $disabled = '';

                $tbl_group_products = $product->tbl_group_products;

                if (count($tbl_group_products) > 0) {
                    foreach ($tbl_group_products as $tpu) {
                        if ($tpu->gid == $id) {
                            $selected = 'selected';
                        } else {
                            $disabled = "disabled";
                            $assigned = "(Assigned)";
                        }
                    }
                }

                $productoptions .= "<option value='" . $product->pro_id . "' " . $selected . " " . $disabled . ">" . $product->name . " " . $assigned . "</option>";
            }
            $productoptions .= "<option value='none'>None</option>";
        }


        $data['productoptions'] = $productoptions;

        return view('admin.groups.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $gid)
    {
        //        echo json_encode($request->input());
        //        exit();


        $this->validate($request, [
            'name' => 'required|max:255|unique:tbl_groups,name,' . $gid . ',gid',
        ]);


        $usersArr = ($request->input('selectUsers') != '') ? $request->input('selectUsers') : array();
        $users = (count($usersArr) > 0) ? implode(",", $usersArr) : '';

        $productsArr = ($request->input('selectProducts') != '') ? $request->input('selectProducts') : array();
        $products = (count($productsArr) > 0) ? implode(",", $productsArr) : '';

        $group = Tbl_groups::find($gid);

        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->users = $users;
        $group->products = $products;
        $res = $group->save();

        if ($res) {

            $groupArr = array();
            $gpArr = array();

            //Group Users
            if (count($usersArr) > 0) {

                foreach ($usersArr as $guser) {

                    if ((int) $guser > 0) {
                        $groupArr[] = array(
                            'uid' => (int) $guser,
                            'gid' => $gid,
                        );
                    }
                }

                Tbl_group_users::where('gid', $gid)->delete();

                if (count($groupArr) > 0) {
                    Tbl_group_users::insert($groupArr);
                }

                //                Tbl_group_users::insert($groupArr);
            } else {
                Tbl_group_users::where('gid', $gid)->delete();
            }

            //Group Products
            if (count($productsArr) > 0) {

                foreach ($productsArr as $gproduct) {

                    if ((int) $gproduct > 0) {
                        $gpArr[] = array(
                            'pro_id' => (int) $gproduct,
                            'gid' => $gid,
                        );
                    }
                }

                Tbl_group_products::where('gid', $gid)->delete();
                if (count($gpArr) > 0) {
                    Tbl_group_products::insert($gpArr);
                }
            } else {
                Tbl_group_products::where('gid', $gid)->delete();
            }

            if ((count($groupArr) > 0) && (count($gpArr) > 0)) {
                return redirect('admin/groups/allocate/' . $gid)->with('success', 'Group Updated Successfully...!');
            } else {
                return redirect('admin/groups')->with('success', 'Group Updated Successfully...!');
            }
        } else {
            return redirect('admin/groups')->with('error', 'Error occurred. Please try again later...!');
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
        $group = Tbl_groups::find($id);
        $group->active = 0;
        $group->save();
        return redirect('admin/groups')->with('success', 'Group Deleted Successfully...!');
    }

    public function delete($id)
    {
        $group = Tbl_groups::find($id);
        $group->active = 0;
        $group->save();

        //  Deleting group products
        Tbl_group_products::where('gid', $id)->delete();

        //  Deleting group users
        Tbl_group_users::where('gid', $id)->delete();

        return redirect('admin/groups')->with('success', 'Group Deleted Successfully...!');
    }

    public function assignUsers(Request $request)
    {
        //        echo json_encode($request->input());

        $users = $request->input('users');
        $gid = $request->input('gid');

        $group = Tbl_groups::find($gid);

        $group->users = implode(',', $users);
        $group->save();

        //  Assign Users to group

        $deleted = Tbl_group_users::where('gid', $gid)->delete();

        //        if ($deleted) {


        foreach ($users as $user) {
            $tuformdata[] = array(
                'uid' => $user,
                'gid' => $gid,
            );
        }

        $assiged = Tbl_group_users::insert($tuformdata);
        if ($assiged) {
            return 'success';
        } else {
            return 'error';
        }

        //        return redirect('admin/groups/' . $gid)->with('success', 'Users assigned successfully...!');
        //        } else {
        //            return redirect('admin/groups/' . $gid)->with('error', 'Error occurred. Please try again later...!');
        //        }
    }

    public function allocate($id)
    {
        //        $users = Tbl_group_users::where('gid', $id)->get();
        //        echo json_encode($users);
        //        exit();

        $users = Tbl_group_users::join('users', 'users.id', '=', 'tbl_group_users.uid')
            ->where('tbl_group_users.gid', $id)
            ->select('tbl_group_users.*', 'users.name')
            ->get();
        //        echo json_encode($users);
        //        exit();
        $total = count($users);
        $total_quota = 0;
        if ($total > 0) {
            $formstable = '<table id="" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Percent %</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($users as $formdetails) {
                $formstable .= '<tr>';
                $formstable .= '<td>';
                $formstable .= '<input type="hidden" value="' . $formdetails->gu_id . '" name="groupusers[]"/>';
                $formstable .= '<input type="hidden" value="' . $formdetails->uid . '" name="users[]"/>';
                $formstable .= '<label>' . $formdetails->name . '</label>';
                $formstable .= '</td>';
                $formstable .= '<td>';
                $formstable .= '<div class="input-group">';
                $formstable .= '<input type="number" value="' . $formdetails->quota . '" name="quota[]" id="quota" class="form-control leadQuota" min="0" onKeyup="return calculateTotal();"/>';
                $formstable .= '<span class="input-group-addon">%</span>';
                $formstable .= '</div>';
                $formstable .= '</td>';
                $formstable .= '</tr>';

                $total_quota += $formdetails->quota;
            }
            $formstable .= '<tr>';
            $formstable .= '<td>';
            $formstable .= '<label>Total</label>';
            $formstable .= '</td>';
            $formstable .= '<td>';
            $formstable .= '<div class="input-group">';
            $formstable .= '<input type="number" name="total_quota" id="total_quota" class="form-control" value="' . $total_quota . '"/>';
            $formstable .= '<span class="input-group-addon">%</span>';
            $formstable .= '</div>';
            $formstable .= '</td>';
            $formstable .= '</tr>';
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        $data['gid'] = $id;
        $data['total'] = $total;
        $data['table'] = $formstable;
        return view('admin.groups.allocate')->with('data', $data);
    }

    public function allocateLeadsQuotatoGroupUsers(Request $request, $id)
    {
        //        echo json_encode($request->input());
        //        exit();
        $users = $request->input('users');
        $quotas = $request->input('quota');
        $groupusers = $request->input('groupusers');

        if (array_sum($quotas) == 100) {

            foreach ($users as $key => $user) {

                $guser = Tbl_group_users::find($groupusers[$key]);
                $guser->quota = $quotas[$key];
                $guser->save();
                //                echo 'guid : ' . $groupusers[$key] . ' uid : ' . $user . ' quota : ' . $quotas[$key] . '<br>';
            }
            return redirect('admin/groups')->with('success', 'Allocated Successfully');
        } else {
            return redirect('admin/groups/allocate/' . $id)->with('error', 'Total should be 100%');
        }
    }

    public function assign($id)
    {
        $data = array();

        $groups = Tbl_groups::with('tbl_group_users')->with('tbl_group_products')->find($id);
        $data['groups'] = $groups;

        $tbl_group_users = $groups->tbl_group_users;
        $tbl_group_products = $groups->tbl_group_products;

        //        echo json_encode($groups);
        //        exit();

        if (count($tbl_group_users) == 0) {
            return redirect('admin/groups')->with('error', 'Please assign users to group...!');
        } else if (count($tbl_group_products) == 0) {
            return redirect('admin/groups')->with('error', 'Please assign products to group...!');
        } else {
            //            echo "Page under development...!";

            $tbl_product_forms = Tbl_product_forms::where('pro_id', $groups->products)->first();

            if ($tbl_product_forms != '') {

                //                echo json_encode($tbl_product_forms);
                //            exit();

                $form_id = $tbl_product_forms->form_id;
                $data['form_id'] = $form_id;

                $leads = Tbl_fb_leads::where('assigned', 0)->where('form_id', $form_id)->get();

                //                echo json_encode($leads);
                //                exit();


                if (count($leads) > 0) {
                    $total_users = count($tbl_group_users);
                    $total_leads = count($leads);
                    $data['total_users'] = $total_users;
                    $data['total_leads'] = $total_leads;
                    $random_users = array();

                    $formstable = '<table id="leadsTable" class="table">';
                    $formstable .= '<thead>';
                    $formstable .= '<tr>';
                    $formstable .= '<th>Name</th>';
                    $formstable .= '<th>Quota</th>';
                    $formstable .= '<th>Assigned Leads</th>';
                    $formstable .= '</tr>';
                    $formstable .= '</thead>';
                    $formstable .= '<tbody>';


                    $remaining_leads = $total_leads;
                    foreach ($tbl_group_users as $formdetails) {

                        $userDetails = User::find($formdetails->uid);

                        $formstable .= '<tr>';
                        $formstable .= '<td><label>' . $userDetails->name . '</label><input type="hidden" value="' . $formdetails->uid . '" name="users[]"></td>';
                        $formstable .= '<td><label>' . $formdetails->quota . ' %</label><input type="hidden" value="' . $formdetails->quota . '" name="quotas[]"></td>';
                        $assigned = 0;
                        if ((int) $formdetails->quota > 0) {
                            $assigned = round(($formdetails->quota / 100) * $total_leads);
                            $remaining_leads = (int) $remaining_leads - (int) $assigned;
                        }
                        $formstable .= '<td><label>' . $assigned . '</label><input type="hidden" value="' . $assigned . '" name="assignedQuotas[]"></td>';
                        $formstable .= '</tr>';
                    }
                    $formstable .= '</tbody>';
                    $formstable .= '</table>';
                    $data['table'] = $formstable;

                    return view('admin.groups.assign')->with('data', $data);
                } else {
                    return redirect('admin/groups')->with('error', 'You have no leads available...!');
                }
            } else {
                return redirect('admin/groups')->with('error', 'Please assign form to product...!');
            }
        }
    }

    public function assignLeadstoGroupUsersbyQuota(Request $request)
    {
        //        echo json_encode($request->input());
        //        exit();

        $gid = $request->input('gid');
        $form_id = $request->input('form_id');
        $users = $request->input('users');
        $quotas = $request->input('quotas');
        $assignedQuotas = $request->input('assignedQuotas');
        $total_leads = $request->input('total_leads');

        $total_quota = array_sum($quotas);
        if ($total_quota == 100) {

            if ($total_leads > 0) {

                $k = 0;
                for ($i = 0; $i < count($users); $i++) {

                    $data = array();

                    $user = $users[$i];
                    $quota = $assignedQuotas[$i];
                    //                    $userdata = User::find($user);

                    $qleads = Tbl_fb_leads::where('assigned', 0)
                        ->where('form_id', $form_id)
                        ->orderBy('fblead_id', 'desc')
                        ->limit($quota)
                        ->get(['fblead_id', 'full_name', 'city', 'phone_number'])
                        ->map(function ($article) {
                            $article->phone_number = str_replace('p:+', '', $article->phone_number);
                            return $article;
                        });

                    //                $data[]['user'] = $user;
                    //                $data[]['quota'] = $quota;
                    //                $data[]['qleads'] = $qleads;
                    $leadId = array();
                    foreach ($qleads as $qlead) {
                        $leadId[] = $qlead->fblead_id;
                        $formdata = array(
                            'uid' => $user,
                            'first_name' => $qlead->full_name,
                            'city' => $qlead->city,
                            'mobile' => $qlead->phone_number,
                            'fblead_id' => $qlead->fblead_id,
                        );
                        $data[] = $formdata;
                    }
                    //                $data[] = $leadId;
                    //                break;

                    $qleadRes = Tbl_leads::insert($data);
                    if ($qleadRes) {
                        Tbl_fb_leads::whereIn('fblead_id', $leadId)->update(array('assigned' => 1));
                    }
                    //                ----------------------------------------------------------
                    //                
                    //                for ($j = 0; $j < $quota; $j++) {
                    //
                    //                    if (isset($leads[$k])) {
                    //                        echo $k . ' ' . $user . ' ' . $userdata->name . ' ' . $leads[$k]->fblead_id . ' ' . $leads[$k]->full_name . '<br>';
                    //                        // . ' ' . $leads[$i]->city . ' ' . $leads[$i]->mobile 
                    //                        $k++;
                    //                    }
                    //                }
                    ////                echo $k. '<br>';
                    //                echo "-----------------------------------------------------------------------<br>";
                }

                //            echo json_encode($data);
                return redirect('admin/groups')->with('success', 'Leads Assigned Successfully');
            } else {
                return redirect()->back()->with('error', "You don't have leads...");
            }
        } else {
            return redirect()->back()->with('error', 'Please assign quota');
        }
    }

    public function groupUsers($id)
    {
        $groups = Tbl_groups::with('tbl_group_users')->with('tbl_group_products')->find($id);
        //        $data['groups'] = $groups;
        //        echo json_encode($data);

        $tbl_group_users = $groups->tbl_group_users;
        $total = count($tbl_group_users);
        $data['total'] = $total;
        if ($total > 0) {
            $formstable = '<table id="leadsTable" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Quota</th>';
            $formstable .= '<th>Assigned Leads</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($tbl_group_users as $formdetails) {
                $userDetails = User::with('tbl_leads')->find($formdetails->uid);

                $assigned = ($userDetails->tbl_leads != '') ? count($userDetails->tbl_leads) : 0;

                $formstable .= '<tr>';
                $formstable .= '<td><label>' . $userDetails->name . '</label></td>';
                $formstable .= '<td><label>' . $formdetails->quota . ' %</label></td>';
                $formstable .= '<td><label><a href="' . (($formdetails->quota > 0) ? url('admin/groups/userleads/' . $id . '/' . $formdetails->uid) : '#') . '">' . $assigned . '</a></label></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
            $data['table'] = $formstable;
            return view('admin.groups.users')->with('data', $data);
        }
    }

    public function getUserLeads(Request $request)
    {

        //        return json_encode($request->input());

        $gid = $request->input('gid');
        $id = $request->input('id');
        //        $data = $this->getGroupUserLeads($gid, $id);
        $groups = Tbl_groups::with('tbl_group_users')->find($gid);
        //        $data['group'] = $groups;

        $data = $this->getLeads($groups, $id);

        return json_encode($data);
    }

    public function getGroupUserLeads($gid, $id)
    {

        $groups = Tbl_groups::with('tbl_group_users')->find($gid);


        $data = $this->getLeads($groups, $id);

        $data['group'] = $groups;

        $user_options = "<option value='All'>All</value>";
        if ($groups->users != '') {
            $users = User::whereIn('id', explode(",", $groups->users))->get();
            $user_options = "<option value='All'>All</value>";
            foreach ($users as $user) {
                $user_options .= "<option value='" . $user->id . "'>" . substr($user->name, 0, 20) . "</value>";
            }
        }
        $data['user_options'] = $user_options;
        $data['userId'] = $id;

        return view('admin.groups.leads')->with('data', $data);
    }

    public function getLeads($groups, $id)
    {

        $leads = array();
        if ($id == "All") {
            if ($groups->users != '') {
                $leads = Tbl_leads::whereIn('uid', explode(',', $groups->users))->with('users')->with('tbl_leadstatus')->get();
            }
        } else {
            $leads = Tbl_leads::where('uid', $id)->with('users')->with('tbl_leadstatus')->get();
        }

        $total = count($leads);
        $data['total'] = $total;
        if ($total > 0) {
            $formstable = '<table id="leadsTable" class="table display">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Name</th>';
            $formstable .= '<th>Mobile</th>';
            $formstable .= '<th>City</th>';
            $formstable .= '<th>Lead Status</th>';
            $formstable .= '<th>User</th>';
            $formstable .= '<th class="none">Notes</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($leads as $lead) {

                $userdetails = $lead->users;

                $formstable .= '<tr>';
                $formstable .= '<td><label><a href="' . url("admin/groups/lead/" . $lead->ld_id) . '">' . substr($lead->first_name . ' ' . $lead->last_name, 0, 20) . '</a></label></td>';
                $formstable .= '<td><label>' . $lead->mobile . '</label></td>';
                $formstable .= '<td><label>' . $lead->city . '</label></td>';
                $formstable .= '<td><label>' . (($lead->tbl_leadstatus != '') ? $lead->tbl_leadstatus->status : '') . '</label></td>';
                $formstable .= '<td><label>' . $userdetails->name . '</label></td>';
                $formstable .= '<td><label>' . $lead->notes . '</label></td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No Records Available';
        }
        $data['table'] = $formstable;

        return $data;
    }

    public function getLeadProfile($id)
    {
        $leads = Tbl_leads::with('Tbl_leadsource')
            ->with('Tbl_industrytypes')
            ->with('Tbl_leadstatus')
            ->with('Tbl_Accounts')
            ->with('Tbl_countries')
            ->with('Tbl_states')
            ->with('Tbl_salutations')
            ->with('Tbl_deals')
            ->with('users')
            ->find($id);
        $leadarr = $leads->toArray();
        // echo json_encode($leadarr);
        $data['leadarr'] = $leadarr;

        //         $data['editLink'] = url('leads').'/'.$id.'/edit';
        $data['deleteLink'] = url('admin/leads/delete') . '/' . $id;

        return view('admin.groups.lead')->with("data", $data);
    }
}
