@extends('layouts.app')

@section('content')

<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ __('Transporter Vehicle List') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">{{ __('Dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{url('/transporter')}}">{{ __('Transporter') }}</a></li>
              <li class="breadcrumb-item active">{{ __('Transporter Vehicle List') }}</li>
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
                <h4>{{ __('Transporter Vehicle List') }}</h4>
                <form action="{{url('transporter/vechile/'.$params['id'])}}" method="get">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Date</label>
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
                    <th>{{ __('Truck Name') }}</th>
                    <th>{{ __('Truck Number') }}</th>
                    <th>{{ __('Location') }}</th>
                    <th>{{ __('Vehicle Type') }}</th>
                    <th>{{ __('Vehicle') }}</th>
                    <th>{{ __('License Number') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Created Date') }}</th>
                    <th>{{ __('Action') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                        @if($result->count() > 0)
                            @foreach($result as $key => $data)
                            <tr>
                                <td>{{@$data->truck_name}}</td>
                                <td>{{@$data->truck_number ? $data->truck_number : '-' }}</td>
                                <td>{{@$data->location}}</td>
                                <td>{{@$data->vehicleType->name}}</td>
                                <td>{{@$data->vehicles->name}}</td>
                                <td>{{@$data->licene_no ? $data->licene_no : '-' }}</td>
                                <td>
                                    @if(@$data->approval_flag == 0)
                                        {{ __('Inactive') }}
                                    @elseif(@$data->approval_flag == 2)
                                     {{ __('Rejected') }}
                                    @else
                                    {{ __('Active') }}
                                    @endif
                                </td>
                                <td>{{date('d-m-Y H:m:s', strtotime(@$data->created_at))}}</td>
                                <td>
                                <a class="btn btn-block btn-outline-primary btn-xs" href="{{url('transporter/vechile-view/'.@$data->id)}}">View</a>   
                                </td>
                               
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td></td>
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
