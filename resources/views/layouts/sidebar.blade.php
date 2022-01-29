<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('/')}}" class="brand-link">
      <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
      <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          @if(!empty(@Auth::user()->profile_picture))
          <img src="{{@Auth::user()->profile_picture}}" class="img-circle elevation-2" alt="User Image">
          @else
          <img src="{{asset('img/user.jpg')}}" class="img-circle elevation-2" alt="User Image">
          @endif
        </div>
        <div class="info">
          <a href="{{url('/')}}" class="d-block">{{@\Auth::user()->name}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->
    

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{url('/')}}" class="nav-link {{ request()->routeIs('home') ? 'active' : ''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
              {{ __('Dashboard') }}
                <!-- <i class="right fas fa-angle-left"></i> -->
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('users')}}" class="nav-link {{ request()->is('users*') ? 'active' : ''}}">
              <!-- <i class="nav-icon fas fa-th"></i> -->
              <i class="nav-icon fa fa-users"></i>
              <p>
              {{ __('Users') }}
                <!-- <span class="right badge badge-danger">New</span> -->
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('drivers-owners')}}" class="nav-link {{ request()->is('drivers-owners*') ? 'active' : ''}}">
              <!-- <i class="nav-icon fas fa-th"></i> -->
              <i class="nav-icon fa fa-truck"></i>
              <p>
              {{ __('Drivers/Owners') }}
                <!-- <span class="right badge badge-danger">New</span> -->
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('transporter')}}" class="nav-link {{ request()->is('transporter*') ? 'active' : ''}}">
              <!-- <i class="nav-icon fas fa-th"></i> -->
              <i class="nav-icon fa fa-truck-moving"></i>
              <p>
              {{ __('Transporter') }}
                <!-- <span class="right badge badge-danger">New</span> -->
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('customer')}}" class="nav-link {{ request()->is('customer*') ? 'active' : ''}}">
              <!-- <i class="nav-icon fas fa-th"></i> -->
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
              {{ __('Customer') }}
                <!-- <span class="right badge badge-danger">New</span> -->
              </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>