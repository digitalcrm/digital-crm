<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

//---------Models---------------
// use App\Role;
// use App\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
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
        $roles = Role::all();
        $total = count($roles);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Role</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($roles as $role) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $role->name . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/roles/' . $role->id . '/edit') . '">Edit</a>';
                // $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2"  href="' . url('admin/roles/delete/' . $role->id) . '">Delete</a>';
                $formstable .= '</td>';
                $formstable .= '</tr>';
            }
            $formstable .= '</tbody>';
            $formstable .= '</table>';
        } else {
            $formstable = 'No records available';
        }

        //        echo $formstable;

        $data['total'] = $total;
        $data['table'] = $formstable;

        return view('admin.roles.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        // echo json_encode($permissions);
        // exit();
        $table = "<table>";
        $k = 1;
        foreach ($permissions as $permission) {

            if ($k == 1) {
                $table .= "<tr>";
            }
            $table .= "<td><input type='checkbox' value='" . $permission->id . "' name='permissions[]' id='" . $permission->id . "'>&nbsp;" . $permission->name . "</td>";
            if ($k == 4) {
                $table .= "</tr>";
            }
            $k++;
        }
        $table .= "</table>";

        // echo $table;
        // exit();
        $data['table'] = $table;
        return view('admin.roles.create')->with('data', $data);
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
        // exit();

        $this->validate($request, [
            'name' => 'required|max:255|unique:roles',
        ]);

        $permissions = $request->input('permissions');

        $formdata = array(
            'name' => $request->input('name'),
            'guard_name' => 'web'
        );

        $types = Role::create($formdata);
        // $role_permit = array();
        $role_id = $types->id;

        if ($role_id > 0) {

            $role = Role::find($role_id);

            if (($permissions != '') && (count($permissions) > 0)) {
                $role->givePermissionTo($permissions);
            }

            return redirect('admin/roles')->with('success', 'Role Created Successfully...!');
        } else {
            return redirect('admin/roles')->with('error', 'Error occurred. Please try again...!');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $role = Role::find($id);
        $permissions = Permission::all();
        // echo json_encode($permissions);
        // exit();

        // $rolePermissions = $role->permissions;
        // echo json_encode($rolePermissions);
        // exit();


        $table = "<table>";
        $k = 1;
        foreach ($permissions as $permission) {

            $rolepermit = $role->hasPermissionTo($permission);
            $permit = ($rolepermit == TRUE) ? 'checked' : "";

            // echo $permission . ' ' . $permit . '<br>';

            if ($k == 1) {
                $table .= "<tr>";
            }
            $table .= "<td><input type='checkbox' value='" . $permission->id . "' name='permissions[]' id='" . $permission->id . "' " . $permit . ">&nbsp;" . $permission->name . "</td>";
            if ($k == 4) {
                $table .= "</tr>";
            }
            $k++;
        }

        $table .= "</table>";
        $role['table'] = $table;

        return view('admin.roles.edit')->with('data', $role);
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
        // echo json_encode($request->input());
        // exit();

        $permissions = $request->input('permissions');

        $this->validate($request, [
            'name' => 'required|max:255|unique:roles,name,' . $id . ',id',
        ]);

        $formdata = array(
            'name' => $request->input('name'),
        );

        $types = Role::where('id', $id)->update($formdata);

        if ($types > 0) {

            $role = Role::find($id);

            if (count($permissions) > 0) {
                $role->syncPermissions($permissions);
            }

            return redirect('admin/roles')->with('success', 'Role Updated Successfully...!');
        } else {
            return redirect('admin/roles')->with('error', 'Error occurred. Please try again...!');
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

    // public function delete($id)
    // {
        
    // }

    
}
