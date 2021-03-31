<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Tbl_forecast;
use App\Tbl_deals;
use App\currency;

class ForecastController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('test:forecasts', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete', 'deleteAll', 'forecastEdit', 'forecastUpdate', 'getUserForecast', 'getForecastlist']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $today = date('Y-m');

        $data = $this->getForecastlist($uid, $today);

        return view('auth.forecast.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $uid = Auth::user()->id;
        $user = User::find($uid);
        $data['user'] = $user;

        $subusers = User::where('user', $uid)->get();
        $selectUsers = '<option value="' . $uid . '" selected data-subtext="main">' . Auth::user()->name . '</option>';
        foreach ($subusers as $subuser) {
            $selectUsers .= '<option value="' . $subuser->id . '">' . $subuser->name . '</option>';
        }
        $data['selectUsers'] = $selectUsers;
        return view('auth.forecast.create')->with('data', $data);
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
        //        exit(0);

        $uid = Auth::user()->id;

        $user = $request->input('users');
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
            $user = $request->input('users');

            $yearExist = Tbl_forecast::where('uid', $user)->where('year', $year)->get();

            if (count($yearExist) > 0) {
                return redirect('/forecast/create')->with('error', 'For year forecast is already assigned.');
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
                'uid' => $user,
                'year' => $year,
                'month' => $key,
                'forecast' => $value,
            );
        }

        $forecast = Tbl_forecast::insert($formdata);
        //        echo json_encode($forecast);
        if ($forecast) {
            return redirect('/forecast')->with('success', 'Forecast Created Successfully');
        } else {
            return redirect('/forecast')->with('error', 'Faild. Try again later');
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
        $user = User::find($uid);
        $currency = currency::find($user->cr_id);

        //        $today = date('Y-m');
        //        $currentYear = date('Y');

        $today = date('Y-m', strtotime($date));
        $year = date('Y', strtotime($date));

        $forecast = $this->getUserForecast($uid, $today);

        $userTable = '<div class="table-responsive"><table id="example" class="table dataTable no-footer">';
        $userTable .= '<thead>';
        $userTable .= '<tr>';
        $userTable .= '<th>Date</th>';
        $userTable .= '<th>User</th>';
        $userTable .= '<th>Forecast</th>';
        $userTable .= '<th>Achieved</th>';
        $userTable .= '<th>Deals</th>';
        if (Gate::allows('isUser')) {
            $userTable .= '<th>Action</th>';
        }
        $userTable .= '</tr>';
        $userTable .= '</thead>';
        $userTable .= '<tbody>';
        $userTable .= '<tr>';
        $userTable .= '<td>' . date('M-Y', strtotime($today)) . '</td>';
        $userTable .= '<td>' . Auth::user()->name . '</td>';
        $userTable .= '<td>' . $currency->html_code . ' ' . $forecast['forecast'] . '</td>';
        $userTable .= '<td>' . $currency->html_code . ' ' . $forecast['achieved'] . '</td>';
        $userTable .= '<td>' . $currency->html_code . ' ' . $forecast['deals'] . '</td>';
        if (Gate::allows('isUser')) {
            $userTable .= '<td><a href="' . url('forecastEdit/' . $uid . '/' . $year) . '" class="badge badge-success p-2">Edit</a></td>';
        }
        $userTable .= '</tr>';
        $userTable .= '</tbody>';
        $userTable .= '</table></div>';

        $data['userTable'] = $userTable;
        $data['currency_html'] = $currency->html_code;

        if (Gate::allows('isUser')) {
            $subusers = User::where('user', $uid)->get();

            if (count($subusers) > 0) {
                $subuserTable = '<div class="table-responsive"><table id="example1" class="table dataTable no-footer">';
                $subuserTable .= '<thead>';
                $subuserTable .= '<tr>';
                $subuserTable .= '<th>Date</th>';
                $subuserTable .= '<th>User</th>';
                $subuserTable .= '<th>Forecast</th>';
                $subuserTable .= '<th>Achieved</th>';
                $subuserTable .= '<th>Deals</th>';
                $subuserTable .= '<th>Action</th>';
                $subuserTable .= '</tr>';
                $subuserTable .= '</thead>';
                $subuserTable .= '<tbody>';
                foreach ($subusers as $subuser) {

                    $subforecast = $this->getUserForecast($subuser->id, $today);

                    $subcurrency = currency::find($subuser->cr_id);

                    $subuserTable .= '<tr>';
                    $subuserTable .= '<td>' . date('M-Y', strtotime($today)) . '</td>';
                    $subuserTable .= '<td>' . $subuser->name . '</td>';
                    $subuserTable .= '<td>' . $subcurrency->html_code . ' ' . $subforecast['forecast'] . '</td>';
                    $subuserTable .= '<td>' . $subcurrency->html_code . ' ' . $subforecast['achieved'] . '</td>';
                    $subuserTable .= '<td>' . $subcurrency->html_code . ' ' . $subforecast['deals'] . '</td>';
                    $subuserTable .= '<td><a href="' . url('forecastEdit/' . $subuser->id . '/' . $year) . '" class="badge badge-success p-2">Edit</a></td>';
                    $subuserTable .= '</tr>';
                }
                $subuserTable .= '</tbody>';
                $subuserTable .= '</table></div>';
            } else {
                $subuserTable = 'No records available';
            }
            $data['subuserTable'] = $subuserTable;
        }
        return $data;
    }

    public function getUserForecast($uid, $date)
    {
        //        $uid = Auth::user()->id;

        $getYear = date("Y", strtotime($date));
        $getMonth = date("m", strtotime($date));
        $MonthYear = date("Y-m", strtotime($date));

        $forecast = Tbl_forecast::where('uid', $uid)->where('year', $getYear)->where('month', $getMonth)->first();

        $achievedDeals = Tbl_deals::where('uid', $uid)->where('deal_status', 1)->where(DB::raw("DATE_FORMAT(closing_date,'%Y-%m')"), $MonthYear)->sum('value');  //

        $deals = Tbl_deals::where('uid', $uid)->where('deal_status', 0)->where(DB::raw("DATE_FORMAT(closing_date,'%Y-%m')"), $MonthYear)->sum('value');  //

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
        //        echo $forecast->count();

        if ($forecast->count() > 0) {
            $data['forecast'] = $forecast;
            $data['year'] = $year;

            $user = User::find($id);
            $data['user'] = $user;

            $uid = Auth::user()->id;
            $subusers = User::where('user', $uid)->get();
            $selected = ($id == $user) ? 'selected' : '';
            $selectUsers = '<option value="' . Auth::user()->id . '" ' . $selected . ' data-subtext="main">' . Auth::user()->name . '</option>';
            foreach ($subusers as $subuser) {
                $selected = ($id == $subuser->id) ? 'selected' : '';
                $selectUsers .= '<option value="' . $subuser->id . '" ' . $selected . '>' . $subuser->name . '</option>';
            }
            $data['selectUsers'] = $selectUsers;

            return view('auth.forecast.edit')->with('data', $data);
        } else {
            return redirect('forecast/create')->with('info', 'please add forecast..!');
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

        return redirect('/forecast')->with('success', 'Forecast Updated Successfully');
    }
}
