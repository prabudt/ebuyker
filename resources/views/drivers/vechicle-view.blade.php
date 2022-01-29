@extends('layouts.app')

@section('content')

<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ __('Drivers/Owners Vehicle Data') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">{{ __('Dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{url('/drivers-owners')}}">{{ __('Drivers/Owners') }}</a></li>
              <li class="breadcrumb-item active">{{ __('Drivers/Owners Vehicle Data') }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

 <!-- Main content -->
 <div class="content">
      <div class="container-fluid">
      <div class="card">
              <div class="card-header">
                <h4>{{ __('Drivers/Owners Vehicle Data') }}</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                    <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                   
                    <div class="row">
                        <div class="col-12">
                            <div class="post">
                                <div class="user-block">
                                    @if(!empty(@$data->user->profile_picture))
                                        <img src="{{@$data->user->profile_picture}}" class="img-circle img-bordered-sm" alt="User Image">
                                    @else
                                        <img src="{{asset('img/user.jpg')}}" class="img-circle img-bordered-sm" alt="User Image">
                                    @endif
                                    <span class="username">
                                    <a href="#">{{@$data->user->name}}.</a>
                                    </span>
                                    <span class="description">Created Date - {{date('F jS Y', strtotime(@$data->user->created_at))}}</span>
                                </div>
                                   
                                <div class="col-12">
                                <p class="lead">Vehicle Information</p>
                                    <div class="table-responsive">
                                        <table class="table">
                                        <tbody><tr>
                                            <th style="width:50%">Truck Name:</th>
                                            <td>{{@$data->truck_name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Truck Number</th>
                                            <td>{{@$data->truck_number}}</td>
                                        </tr>
                                        <tr>
                                            <th>Location:</th>
                                            <td>{{@$data->location}}</td>
                                        </tr>
                                        <tr>
                                            <th>vehicle Type:</th>
                                            <td>{{@$data->vehicle_type->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>vehicle:</th>
                                            <td>{{@$data->vehicles->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Truck Image:</th>
                                            <td>
                                            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                                              <div class="image">
                                                <img src="{{@$truckFileData['truck_image']}}" alt="user-avatar" class="img-circle img-fluid">
                                              </div>
                                            </div>
                                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>License No:</th>
                                            <td>{{@$data->user->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <th>License Front:</th>
                                            <td>{{@$truckFileData['truck_image']}}</td>
                                        </tr>
                                        <tr>
                                            <th>License Back:</th>
                                            <td>{{@$data->user->created_at}}</td>
                                        </tr>
                                        <tr>
                                            <th>RCBook Number:</th>
                                            <td>{{@$data->user->created_at}}</td>
                                        </tr>

                                        <tr>
                                            <th>RCBook Image:</th>
                                            <td>{{@$data->user->created_at}}</td>
                                        </tr>

                                      
                                        </tbody></table>
                                    </div>
                                </div>
                            <!-- /.user-block -->
                            </div>

                        </div>
                    </div>
                    </div>
              
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
       </div>
</div>

<script>

    <!-- /.content -->
@endsection
