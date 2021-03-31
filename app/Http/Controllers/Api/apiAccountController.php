<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Account as AccountResource;
use App\Http\Resources\AccountCollection;
use App\Http\Resources\AccountResourceCollection;
use App\Tbl_Accounts;
use App\Tbl_accounttypes;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class apiAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        // return AccountResource::collection(auth()->user()->tbl_accounts()->with('users','tbl_accounttypes')->latest()->paginate(5));

        return new AccountResourceCollection(auth()->user()->tbl_accounts()->with('users','tbl_accounttypes','tbl_industrytypes')->latest()->paginate(5));
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
            'name' => 'required|max:255',
        ]);

        $account_fields = $request->all();

        $account_fields['uid'] = Auth::user()->id;
        $account_fields['employees'] = ($request->employees > 0) ? $request->employees : 1;

        if ($request->hasfile('picture')) {
            $file = $request->file('picture');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/profile/', $name);
            $filename = '/uploads/profile/' . $name;
            $account_fields['picture'] = $filename;
        }
      
        $accounts = Tbl_Accounts::create($account_fields);
        return new AccountResource($accounts);



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $account_show = Tbl_Accounts::where('uid','=',auth()->user()->id)->findOrFail($id);
        
        return new AccountResource($account_show);


        // return response()->json([$account_show], 200);
        

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Tbl_Accounts $account)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $account_fields = $request->all();

        if ($request->hasfile('picture')) {
            $file = $request->file('picture');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/profile/', $name);
            $filename = '/uploads/profile/' . $name;
            $account_fields['picture'] = $filename;
        }

        $account->update($account_fields);

        return new AccountResource($account);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tbl_Accounts $account)
    {
        $account->delete();

        return response()->json(['status'=>'succesfully deleted']);        
    }
}
