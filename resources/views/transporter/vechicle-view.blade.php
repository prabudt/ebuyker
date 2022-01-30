@extends('layouts.app')

@section('content')

<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ __('Transporter Vehicle Data') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">{{ __('Dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{url('/transporter')}}">{{ __('Transporter') }}</a></li>
              <li class="breadcrumb-item"><a href="{{url('/transporter/vechile/'.$data->user_id)}}">{{ __('Transporter Vehicle List') }}</a></li>
              <li class="breadcrumb-item active">{{ __('Transporter Vehicle Data') }}</li>
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
                <h4>{{ __('Transporter Vehicle Data') }}</h4>
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
                                            <th>Vehicle Type:</th>
                                            <td>{{@$data->vehicleType->name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Vehicle:</th>
                                            <td>{{@$data->vehicles->name}}</td>
                                        </tr>
                                        <tr>
                                            <th>Truck Image:</th>
                                            <td>
                                              @if(isset($truckFileData['truck_image']))
                                              <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                                                <div class="image">
                                                  <img src="{{@$truckFileData['truck_image']}}" style="width:7.1rem" alt="user-avatar" class="img-circle img-fluid">
                                                </div>
                                              </div>
                                              @else
                                              -
                                              @endif
                                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>License No:</th>
                                            <td>{{@$data->licene_no}}</td>
                                        </tr>
                                        <tr>
                                            <th>License Front:</th>
                                            <td>  @if(isset($truckFileData['licene_front']))
                                              <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                                                <div class="image">
                                                  <img src="{{@$truckFileData['licene_front']}}" style="width:7.1rem" alt="user-avatar" class="img-circle img-fluid">
                                                </div>
                                              </div>
                                              @else
                                              -
                                              @endif</td>
                                        </tr>
                                        <tr>
                                            <th>License Back:</th>
                                            <td>  @if(isset($truckFileData['licene_back']))
                                              <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                                                <div class="image">
                                                  <img src="{{@$truckFileData['licene_back']}}" style="width:7.1rem" alt="user-avatar" class="img-circle img-fluid">
                                                </div>
                                              </div>
                                              @else
                                              -
                                              @endif</td>
                                        </tr>
                                        <tr>
                                            <th>RCBook Number:</th>
                                            <td>{{@$data->rc_book_number}}</td>
                                        </tr>

                                        <tr>
                                            <th>RCBook Image:</th>
                                            <td> @if(isset($truckFileData['rc_image']))
                                              <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                                                <div class="image">
                                                  <a href="{{@$truckFileData['rc_image']}}" data-toggle="lightbox" data-title="sample 12 - black" data-gallery="gallery">
                                                   <img src="{{@$truckFileData['rc_image']}}" style="width:7.1rem" alt="user-avatar" class="img-circle img-fluid">
                                                  </a>
                                                </div>
                                              </div>
                                              @else
                                              -
                                              @endif</td>
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
