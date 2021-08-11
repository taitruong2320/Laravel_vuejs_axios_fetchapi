<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Http\Resources\CustomerResource;
use File;
use Storage;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  CustomerResource::collection(Customer::paginate(10));
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
    public function store( Request $request)
    {
        $customer = new Customer;
        $customer->name_customer = $request->name_customer;
        $customer->phone_customer = $request->phone_customer;
        $customer->address_customer = $request->address_customer;
        $customer->email_customer = $request->email_customer;
        $customer->city_customer = $request->city_customer;
        if($request->image_customer){
            $image = $request->image_customer;
            $extension = $image->getClientOriginalExtension();
            $name = time().'_'.$image->getClientOriginalName();
            Storage::disk('public')->put($name, File::get($image));
            $customer->image_customer = $name;
        }else{
            $customer->image_customer = 'default.jpg';
        }
        $customer->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        // $customer = Customer::all();
        return new CustomerResource($customer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Customer $customer)
    {
        $customer->name_customer = $request->name_customer;
        $customer->phone_customer = $request->phone_customer;
        $customer->address_customer = $request->address_customer;
        $customer->email_customer = $request->email_customer;
        $customer->city_customer = $request->city_customer;
        Storage::disk('public')->delete($customer->image_customer);
        if($request->image_customer){
            // unlink("public/images/".$customer->image_customer);
            $image = $request->image_customer;
            $extension = $image->getClientOriginalExtension();
            $name = time().'_'.$image->getClientOriginalName();
            Storage::disk('public')->put($name, File::get($image));
            $customer->image_customer = $name;
        }
        $customer->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
       
        $customer->delete();
    }
}
