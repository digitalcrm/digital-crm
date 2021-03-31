<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Account extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        // return [
        //     'acc_id' => $this->acc_id,
        //     'uid' => $this->uid,            
        //     'name'=>$this->name,
        //     'email' => $this->email,
        //     'picture' => $this->picture,
        //     'mobile' => $this->mobile,
        //     'phone' => $this->phone,
        //     'company' => $this->company,
        //     'employees' => $this->employees,
        //     'website' => $this->website,
        //     'description' => $this->description,
        //     'actype_id' => $this->actype_id,
        //     'intype_id' => $this->intype_id,
        //     'street' => $this->street,
        //     'city' => $this->city,
        //     'state' => $this->state,
        //     'country' => $this->country,
        //     'zip' => $this->zip,
        //     'user'=> $this->users->name,
        //     'account_types' => optional($this->tbl_accounttypes)->account_type,
        //     'industry_types' => optional($this->tbl_industrytypes)->type,
        //     // 'account_types' => $this->tbl_accounttypes,//This will return all data in array
        //     // 'account_types' => $this->tbl_accounttypes->account_type,//This will only return name field //if you don't put optional then it will thrown an error if their is not data
        // ];
    }
}
