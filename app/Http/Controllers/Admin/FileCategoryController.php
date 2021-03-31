<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
//---------Models---------------
use App\Tbl_file_category;

class FileCategoryController extends Controller {

    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categoryfiles = Tbl_file_category::all();
        $total = count($categoryfiles);
        if ($total > 0) {
            $formstable = '<table id="example1" class="table">';
            $formstable .= '<thead>';
            $formstable .= '<tr>';
            $formstable .= '<th>Category</th>';
            $formstable .= '<th>Action</th>';
            $formstable .= '</tr>';
            $formstable .= '</thead>';
            $formstable .= '<tbody>';
            foreach ($categoryfiles as $types) {
                $formstable .= '<tr>';
                $formstable .= '<td>' . $types->category . '</td>';
                $formstable .= '<td>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/filecategory/' . $types->fc_id . '/edit') . '">Edit</a>';
                $formstable .= '<a class="btn badge badge-secondary py-1 px-2 mr-2" href="' . url('admin/filecategory/delete/' . $types->fc_id) . '">Delete</a>';
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

        return view('admin.filecategory.index')->with("data", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.filecategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
//        echo json_encode($request->input());
//        exit();


        $this->validate($request, [
            'category' => 'required|max:255|unique:tbl_file_category'
        ]);


        $formdata = array(
            'category' => $request->input('category'),
        );

        $types = Tbl_file_category::create($formdata);
        if ($types->fc_id > 0) {
            return redirect('admin/filecategory')->with('success', 'Created Successfully...!');
        } else {
            return redirect('admin/filecategory')->with('error', 'Error occurred. Please try again...!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $category = Tbl_file_category::find($id);
//        echo json_encode($category);
        return view('admin.filecategory.edit')->with('data', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
//        echo json_encode($request->input());
//        exit();


        $this->validate($request, [
            'category' => 'required|max:255|unique:tbl_file_category,category,' . $id . ',fc_id',
                ], [
            'category.unique' => 'Given category already exists !',
        ]);

        $units = Tbl_file_category::find($id);

        $units->category = $request->input('category');
        $units->save();

        return redirect('admin/filecategory')->with('success', 'Updated Successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function delete($id) {
        $units = Tbl_file_category::find($id);
        $units->delete();
        return redirect('admin/filecategory')->with('success', 'Deleted Successfully...!');
    }

}
