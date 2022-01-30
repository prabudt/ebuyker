@extends('layouts.app')

@section('content')

<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ __('Transporter Load History') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">{{ __('Dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{url('/transporter')}}">{{ __('Transporter') }}</a></li>
              <li class="breadcrumb-item active">{{ __('Transporter Load History') }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    @if(session()->has('message-success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i>{{ session()->get('message-success') }}</h5>
    </div>
    @endif

    @if(session()->has('message-error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> {{ session()->get('message-error') }}</h5>
        
    </div>
    @endif
    
 <!-- Main content -->
 <div class="content">
      <div class="container-fluid">
      <div class="card">
              <div class="card-header">
                <h4>{{ __('Transporter Load History') }}</h4>
                <form action="{{url('transporter/load-history/'.$params['id'])}}" method="get">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Booking Date</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-calendar-alt"></i>
                                        </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="date_range" placeholder="Select Data Range" name="date_range" value="{{ (isset($params['date_range'])) ? $params['date_range'] : '' }}" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="form-group">
                                    <label> </label>
                                    <button type="submit" class="btn btn-block btn-outline-primary" style="margin-top: 8px;">Submit</button>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </form>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>{{ __('Vehicle Type') }}</th>
                    <th>{{ __('Vehicle') }}</th>
                    <th>{{ __('Location(From/To)') }}</th>
                    <th>{{ __('Pickup Date') }}</th>
                    <th>{{ __('Material Type') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Booking Date') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                        @if($result->count() > 0)
                            @foreach($result as $key => $data)
                            <tr>
                                <td>{{@$data->loads->vehicles->name}}</td>
                                <td>{{@$data->loads->vehicleType->name}}</td>
                                <td>{{@$data->loads->load_location}} / {{@$data->loads->unload_location}}</td>
                                <td>{{date('d-m-Y H:m:s', strtotime(@$data->loads->pickup_date))}}</td>
                                <td>{{@$data->loads->material_type ? $data->loads->material_type : '-' }}</td>
                                <td>
                                    @if(@$data->loads->approval_flag == 0)
                                        {{ __('Unapproved') }}
                                    @elseif(@$data->loads->approval_flag == 2)
                                     {{ __('Off-boarded') }}
                                    @else
                                    {{ __('Approved') }}
                                    @endif
                                </td>
                                <td>{{@$data->loads->amount}}</td>
                                <td>{{date('d-m-Y H:m:s', strtotime(@$data->created_at))}}</td>
                               
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ __('No Records Found') }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endif
                  </tbody>
                 
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
       </div>
</div>

<script>

    <!-- /.content -->
@endsection
