<?php

namespace App\Http\Controllers\Api\V1;

use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceFormRequest;
use App\Http\Resources\ServiceResource;
use App\Tbl_leads;
use Illuminate\Database\Eloquent\Builder;

class ServiceV1Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show', 'leadStore']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|numeric',
        ]);

        $queries = Service::query()
            ->whereHas('company', function (Builder $query) {
                $query->isActive();
            })->whereHas('user', function ($query) {
                $query->isActive();
            })->with(
                [
                    'user:id,name,email,active,cr_id',
                    'user.currency:cr_id,name,code,html_code',
                    'serviceCategory:id,name,slug',
                    'company:id,c_name,slug,c_mobileNum,c_whatsappNum,c_email,country_id,address,city,state_id',
                    'company.tbl_countries', 'company.tbl_states'
                ]
            )->isFeatured(request('featured'))->isActive()->latest();

        if (request('limit')) {
            $services = $queries->paginate(request('limit'))->WithQueryString();
        } else {
            $services = $queries->get();
        }
        return ServiceResource::collection($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceFormRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $validatedData['image'] = request('image')->storePublicly('serviceLogo', 'public');
        }

        if (empty($request->price)) {
            $validatedData['price'] = 0;
        }

        $createdData = auth()->user()->services()->create($validatedData);

        if ($request->serv_subcategory_id) {
            $createdData->serviceSubcategories()->attach($request->serv_subcategory_id);
        }

        return response()->json(['status' => 200, 'id' => $createdData->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  string  $api_services
     * @return \Illuminate\Http\Response
     */
    public function show(Service $api_services)
    {
        $api_services->increment('views');

        $data = $api_services->whereHas('company', function (Builder $query) {
            $query->isActive();
        })->whereHas('user', function ($query) {
            $query->isActive();
        })->with(
            [
                'user:id,name,email,active,cr_id',
                'user.currency:cr_id,name,code,html_code',
                'serviceCategory:id,name,slug',
                'company:id,c_name,slug,c_mobileNum,c_whatsappNum,c_email,country_id,address,city,state_id',
                'company.tbl_countries', 'company.tbl_states'
            ]
        )->isActive()->findOrFail($api_services->id);

        return new ServiceResource($data);
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
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }

    public function leadStore(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            // 'email' => 'email|required|max:255',
            'mobile' => 'required|digits_between:10,15',
            // 'quantity' => 'required|numeric|gt:0',
            'service_id' => 'required|numeric|gt:0|exists:services,id',
            'notes' => 'nullable|string|max:300',
        ]);

        if (request('service_id')) {
            $data = Service::findOrFail(request('service_id'));
            Tbl_leads::create([
                'first_name' => $validatedData['name'],
                'mobile' => $validatedData['mobile'],
                'service_id' => $validatedData['service_id'],
                'company' => $data['company_id'],
                'uid' => $data['user_id'],
                'notes' => $validatedData['notes'],
                'leadtype' => 3,
            ]);
        }

        return response()->json(['staus' => 200, 'message' => 'successfully stored']);
    }
}
