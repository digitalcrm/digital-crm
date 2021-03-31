<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
//------------------Models------------------------
use App\Tbl_deals;
use App\Tbl_forecast;
use App\User;
use App\currency;

class ForecastController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('test:forecast-list', ['only' => ['index', 'show', 'getUserForecast']]);
        $this->middleware('test:forecast-create', ['only' => ['create', 'store']]);
        $this->middleware('test:forecast-edit', ['only' => ['edit', 'update', 'forecastEdit', 'forecastUpdate']]);
        $this->middleware('test:forecast-delete', ['only' => ['destroy']]);
        $this->middleware('test:forecast-import', ['only' => ['import', 'importData']]);
        $this->middleware('test:forecast-export', ['only' => ['export', 'exportData']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>"; // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        $now = date('Y-m');
        $forecast = $this->getForecastlist($uid, $now);
        $data['userTable'] = $forecast;

        return view('admin.forecast.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        $useroptions = "<option value=''>Select Users</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>"; // " . $selected . "
        }
        $data['useroptions'] = $useroptions;
        return view('admin.forecast.create')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //        echo json_encode($request->input());


        $uid = $request->input('users');
        $year = $request->input('year');

        $this->validate($request, [
            'users' => 'numeric|required',
            'year' => 'numeric|required',
            'january' => 'numeric|required',
            'february' => 'numeric|required',
            'march' => 'numeric|required',
            'april' => 'numeric|required',
            'may' => 'numeric|required',
            'june' => 'numeric|required',
            'july' => 'numeric|required',
            'august' => 'numeric|required',
            'september' => 'numeric|required',
            'october' => 'numeric|required',
            'november' => 'numeric|required',
            'december' => 'numeric|required',
        ], ['numeric' => 'please enter number only']);

        if ($request->input('year')) {

            $year = $request->input('year');

            $yearExist = Tbl_forecast::where('uid', $uid)->where('year', $year)->get();

            if (count($yearExist) > 0) {
                return redirect('admin/forecast/create')->with('error', 'For year forecast is already assigned.');
            }
        }

        $monthdata = array(
            '01' => $request->input('january'),
            '02' => $request->input('february'),
            '03' => $request->input('march'),
            '04' => $request->input('april'),
            '05' => $request->input('may'),
            '06' => $request->input('june'),
            '07' => $request->input('july'),
            '08' => $request->input('august'),
            '09' => $request->input('september'),
            '10' => $request->input('october'),
            '11' => $request->input('november'),
            '12' => $request->input('december'),
        );

        foreach ($monthdata as $key => $value) {
            //            echo "key : " . $key . ' value : ' . $value . '<br>';
            $formdata[] = array(
                'uid' => $uid,
                'year' => $year,
                'month' => $key,
                'forecast' => $value,
            );
        }

        $forecast = Tbl_forecast::insert($formdata);
        //        echo json_encode($forecast);
        if ($forecast) {
            return redirect('admin/forecast')->with('success', 'Forecast Created Successfully');
        } else {
            return redirect('admin/forecast')->with('error', 'Faild. Try again later');
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

    public function getForecastlist($uid, $date)
    {

        // $year = date("Y", strtotime($date));
        // $month = date("m", strtotime($date));
        // $monthyear = date("Y-m", strtotime($date));


        $today = date('Y-m', strtotime($date));
        $year = date('Y', strtotime($date));

        if ($uid == 'All') {
            $users = User::all();
            $userTable = '<table id="example1" class="table">';
            $userTable .= '<thead>';
            $userTable .= '<tr>';
            $userTable .= '<th>Name</th>';
            $userTable .= '<th>Forecast</th>';
            $userTable .= '<th>Achieved</th>';
            $userTable .= '<th>Deals</th>';
            $userTable .= '<th>Action</th>';
            $userTable .= '</tr>';
            $userTable .= '</thead>';
            $userTable .= '<tbody>';
            foreach ($users as $user) {
                $currency = currency::find($user->cr_id);
                $forecast = $this->getUserForecast($user->id, $today);  //date
                $userTable .= '<tr>';
                $userTable .= '<td>' . $user->name . '</td>';
                $userTable .= '<td><span>' . $currency->html_code . '</span>&nbsp;' . $forecast['forecast'] . '</td>';
                $userTable .= '<td><span>' . $currency->html_code . '</span>&nbsp;' . $forecast['achieved'] . '</td>';
                $userTable .= '<td><span>' . $currency->html_code . '</span>&nbsp;' . $forecast['deals'] . '</td>';
                $userTable .= '<td><a href="' . url('admin/forecastEdit/' . $user->id . '/' . $year) . '" class="btn btn-sm badge badge-success py-1">Edit</a></td>';
                $userTable .= '</tr>';
            }
        } else {
            $user = User::find($uid);
            $currency = currency::find($user->cr_id);
            $forecast = $this->getUserForecast($user->id, $today);  //date
            $userTable = '<table id="example1" class="table">';
            $userTable .= '<thead>';
            $userTable .= '<tr>';
            $userTable .= '<th>Forecast</th>';
            $userTable .= '<th>Achieved</th>';
            $userTable .= '<th>Deals</th>';
            $userTable .= '<th>Action</th>';
            $userTable .= '</tr>';
            $userTable .= '</thead>';
            $userTable .= '<tbody>';
            $userTable .= '<tr>';
            $userTable .= '<td><span>' . $currency->html_code . '</span>&nbsp;' . $forecast['forecast'] . '</td>';
            $userTable .= '<td><span>' . $currency->html_code . '</span>&nbsp;' . $forecast['achieved'] . '</td>';
            $userTable .= '<td><span>' . $currency->html_code . '</span>&nbsp;' . $forecast['deals'] . '</td>';
            $userTable .= '<td><a href="' . url('admin/forecastEdit/' . $uid . '/' . $year) . '" class="btn btn-danger">Edit</a></td>';
            $userTable .= '</tr>';
        }

        $userTable .= '</tbody>';
        $userTable .= '</table>';

        return $userTable;
    }

    public function getUserForecast($uid, $date)
    {
        //        $uid = Auth::user()->id;

        $getYear = date("Y", strtotime($date));
        $getMonth = date("m", strtotime($date));
        $MonthYear = date("Y-m", strtotime($date));

        $forecast = Tbl_forecast::where('uid', $uid)->where('year', $getYear)->where('month', $getMonth)->first();

        $achievedDeals = Tbl_deals::where('uid', $uid)->where('deal_status', 1)->where(DB::raw("DATE_FORMAT(closing_date,'%Y-%m')"), $MonthYear)->sum('value'); //

        $deals = Tbl_deals::where('uid', $uid)->where('deal_status', 0)->where(DB::raw("DATE_FORMAT(closing_date,'%Y-%m')"), $MonthYear)->sum('value'); //

        $data['forecast'] = ($forecast != '') ? $forecast->forecast : 0;
        $data['achieved'] = $achievedDeals;
        $data['deals'] = $deals;
        return $data;
    }

    public function forecastEdit($id, $year)
    {
        $forecast = Tbl_forecast::where('uid', $id)->where('year', $year)->get();

        //        echo json_encode($forecast);
        //        exit(0);

        //        $data['forecast'] = $forecast;
        //        $data['year'] = $year;
        //
        //        $user = User::find($id);
        //        $data['user'] = $user;
        //
        //        $currecy = currency::find($user->id);
        //        $data['currency'] = $currecy;


        if ($forecast->count() > 0) {
            $data['forecast'] = $forecast;
            $data['year'] = $year;

            $user = User::find($id);
            $data['user'] = $user;

            $currecy = currency::find($user->id);
            $data['currency'] = $currecy;

            return view('admin.forecast.edit')->with('data', $data);
        } else {
            return redirect('admin/forecast')->with('error', 'Please add forecast...');
        }
    }

    public function forecastUpdate(Request $request, $id)
    {
        //        echo json_encode($request->input());
        //        exit(0);
        $this->validate($request, [
            //            'users' => 'numeric|required',
            //            'year' => 'numeric|required',
            'january' => 'numeric|required',
            'february' => 'numeric|required',
            'march' => 'numeric|required',
            'april' => 'numeric|required',
            'may' => 'numeric|required',
            'june' => 'numeric|required',
            'july' => 'numeric|required',
            'august' => 'numeric|required',
            'september' => 'numeric|required',
            'october' => 'numeric|required',
            'november' => 'numeric|required',
            'december' => 'numeric|required',
        ], ['numeric' => 'please enter number only']);


        $user = $request->input('user');
        $year = $request->input('year');

        $january = $request->input('january');
        $february = $request->input('february');
        $march = $request->input('march');
        $april = $request->input('april');
        $may = $request->input('may');
        $june = $request->input('june');
        $july = $request->input('july');
        $august = $request->input('august');
        $september = $request->input('september');
        $october = $request->input('october');
        $november = $request->input('november');
        $december = $request->input('december');

        $janfcid = $request->input('janfcid');
        $febfcid = $request->input('febfcid');
        $marfcid = $request->input('marfcid');
        $aprfcid = $request->input('aprfcid');
        $mayfcid = $request->input('mayfcid');
        $junfcid = $request->input('junfcid');
        $julfcid = $request->input('julfcid');
        $augfcid = $request->input('augfcid');
        $sepfcid = $request->input('sepfcid');
        $octfcid = $request->input('octfcid');
        $novfcid = $request->input('novfcid');
        $decfcid = $request->input('decfcid');

        $jan = Tbl_forecast::find($janfcid);
        $jan->forecast = $january;
        $jan->save();

        $feb = Tbl_forecast::find($febfcid);
        $feb->forecast = $february;
        $feb->save();

        $mar = Tbl_forecast::find($marfcid);
        $mar->forecast = $march;
        $mar->save();

        $apr = Tbl_forecast::find($aprfcid);
        $apr->forecast = $april;
        $apr->save();

        //        echo $april . ' ' . $aprfcid . '<br>';

        $ma = Tbl_forecast::find($mayfcid);
        $ma->forecast = $may;
        $ma->save();

        $jun = Tbl_forecast::find($junfcid);
        $jun->forecast = $june;
        $jun->save();

        $jul = Tbl_forecast::find($julfcid);
        $jul->forecast = $july;
        $jul->save();

        $aug = Tbl_forecast::find($augfcid);
        $aug->forecast = $august;
        $aug->save();

        $sep = Tbl_forecast::find($sepfcid);
        $sep->forecast = $september;
        $sep->save();

        $oct = Tbl_forecast::find($octfcid);
        $oct->forecast = $october;
        $oct->save();

        //        echo $october . ' ' . $octfcid . '<br>';

        $nov = Tbl_forecast::find($novfcid);
        $nov->forecast = $november;
        $nov->save();

        $dec = Tbl_forecast::find($decfcid);
        $dec->forecast = $december;
        $dec->save();

        return redirect('admin/forecast')->with('success', 'Forecast Updated Successfully');
    }
}
