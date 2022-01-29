@extends('layouts.app')

@section('content')

<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ __('Drivers/Owners Profile Data') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">{{ __('Dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{url('/drivers-owners')}}">{{ __('Drivers/Owners') }}</a></li>
              <li class="breadcrumb-item active">{{ __('Drivers/Owners Profile Data') }}</li>
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
                <h4>{{ __('Drivers/Owners Profile Data') }}</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                    <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                   
                    <div class="row">
                        <div class="col-12">
                            <div class="post">
                                <div class="user-block">
                                    @if(!empty(@$userList->profile_picture))
                                        <img src="{{@$userList->profile_picture}}" class="img-circle img-bordered-sm" alt="User Image">
                                    @else
                                        <img src="{{asset('img/user.jpg')}}" class="img-circle img-bordered-sm" alt="User Image">
                                    @endif
                                    <span class="username">
                                    <a href="#">{{@$userList->name}}.</a>
                                    </span>
                                    <span class="description">Created Date - {{date('F jS Y', strtotime(@$userList->created_at))}}</span>
                                </div>
                                   
                                <div class="col-9">
                                    <p class="lead">Details</p>

                                    <div class="table-responsive">
                                        <table class="table">
                                        <tbody><tr>
                                            <th style="width:50%">Email:</th>
                                            <td>{{@$userList->email}}</td>
                                        </tr>
                                        <tr>
                                            <th>Mobile Number</th>
                                            <td>{{@$userList->mobile_no}}</td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td>{{@$userList->address}}</td>
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
