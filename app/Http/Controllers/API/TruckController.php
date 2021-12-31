<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use JWTAuth;
use App\Models\Truck;
use App\Models\Vehicles;
use App\Models\StoreFileData;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $this->getRequest($request);
        $currentDate = Carbon::now()->format('Y-m-d');

        $data = Truck::with(['vehicleType','vehicles', 'user', 'truckFileFata'])->where('active_flag', '1');        
        
        if(isset($params['show']) && $params['show'] == true ) {
        } else {
            $data->where('user_id','>=', JWTAuth::user()->id);
        }


        $data = $data->get();
        return $this->sendSuccess($data);
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
        
        $params = $this->getRequest($request);
        $validator = Validator::make($params, [
            'truck_name' => 'required',
            'truck_number' =>'required|unique:truck,truck_number,null,null,active_flag,1',
            // 'truck_image' => 'required|mimes:jpg,jpeg,png|max:512',
            'location' => 'required',
            'vehicle_type_id' => 'required',
            'vehicle_name' => 'required',
            'licene_no' => 'required',
            // 'licene_front' => 'required|mimes:jpg,jpeg,png|max:512',
            // 'licene_back' => 'required|mimes:jpg,jpeg,png|max:512',
            'rc_book_number' => 'required',
            // 'rc_image' => 'required|mimes:jpg,jpeg,png|max:512'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }
        $vechileCreate['vehicle_type_id'] = $params['vehicle_type_id'];
        $vechileCreate['name'] = $params['vehicle_name'];

        $vechileResult = Vehicles::where('name', 'like', '%' . $params['vehicle_name'] . '%')->first();
        if(empty($vechileResult)) {
            $vechileResult = Vehicles::create($vechileCreate);
        }
        

        $params['user_id'] = JWTAuth::user()->id;
       /*  $params['truck_image'] = fileUploadStorage($params['truck_image']);
        $params['licene_front'] = fileUploadStorage($params['licene_front']);
        $params['licene_back'] = fileUploadStorage($params['licene_back']);
        $params['rc_image'] = fileUploadStorage($params['rc_image']); */
        $params['vehicle_id'] = $vechileResult->id;
        $response = Truck::create($params);
        $message = 'Truck created successfully.';

        return $this->sendSuccess($response, $message);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Loads::with(['vehicleType','vehicles'])->where('active_flag', '1')->find($id);
        return $this->sendSuccess($data);
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
        $params = $this->getRequest($request);   
        $validator = Validator::make($params, [           
            'amount' => 'required|numeric|between:0,99.99'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }    
        if(isset($params['pickup_date'])) {
            $params['pickup_date'] = date('Y-m-d H:m:s', \strtotime($params['pickup_date']));
        }
        
        $response = Loads::find($id);
        $response->update($params);
        $message = 'Load updated successfully.';

        return $this->sendSuccess($response, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fileStore(Request $request)
    {
        $params = $this->getRequest($request);

        $validator = Validator::make($params, [
            'truck_id' => 'required',
            'truck_type' => 'required|unique:store_file_data,truck_type,null,null,truck_id,'.$params['truck_id'],
            'file' => 'required|mimes:jpg,jpeg,png|max:512'
        ],["truck_type.unique" => "Truck type against truck id already exists"]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }

        $params['file'] = fileUploadStorage($params['file']);
        $response = StoreFileData::create($params);
        $message = 'Data created successfully.';

        return $this->sendSuccess($response, $message);
    }


}
