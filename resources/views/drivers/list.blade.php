@extends('layouts.app')

@section('content')

<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ __('Drivers/Owners') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">{{ __('Dashboard') }}</a></li>
              <li class="breadcrumb-item active">{{ __('Drivers/Owners') }}</li>
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
                <h4>{{ __('Drivers/Owners Filters') }}</h4>
                <form action="{{url('drivers-owners')}}" method="get">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Mobile Number</label>
                                    <input type="text" class="form-control" placeholder="Mobile Number" name="mobile_no" value="{{(isset($params['mobile_no'])) ? $params['mobile_no'] : '' }}" autocomplete="off">
                                </div>
                            </div>
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
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Mobile No') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('User Type') }}</th>
                    <th>{{ __('Address') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Created Date') }}</th>
                    <th>{{ __('Action') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                        @if($result->count() > 0)
                            @foreach($result as $key => $data)
                            <tr>
                                <td>{{@$data->name}}</td>
                                <td>{{@$data->mobile_no}}</td>
                                <td>{{@$data->email}}</td>
                                <td>{{@$data->userType->name}}</td>
                                <td>{{@$data->address ? $data->address : '-' }}</td>
                                <td>
                                    @if($data->approval_flag == 0)
                                        {{ __('Unapproved') }}
                                    @elseif($data->approval_flag == 2)
                                     {{ __('Off-boarded') }}
                                    @else
                                    {{ __('Approved') }}
                                    @endif
                                </td>
                                <td>{{date('d-m-Y H:m:s', strtotime(@$data->created_at))}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info">Action</button>
                                        <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" href="#">Profile Data</a>
                                        <a class="dropdown-item" href="#">Vechilce Data</a>
                                        <a class="dropdown-item" href="#">Personal Load History</a>                                  
                                    </div>
                                </td>
                               
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
