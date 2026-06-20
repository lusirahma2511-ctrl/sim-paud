<nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
    
    <!-- Left navbar -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">

            <!-- FIX DI SINI -->
            <a class="nav-link dropdown-toggle d-flex align-items-center"
   href="#"
   data-toggle="dropdown" 
   aria-expanded="false">


                @if(Auth::user()->foto)
                    <img 
                        src="{{ asset('storage/'.Auth::user()->foto) }}"
                        class="img-circle elevation-2"
                        style="width:32px; height:32px; object-fit:cover;"
                    >
                @else
                    <img 
                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 24 24'%3E%3Ccircle cx='12' cy='7' r='4' fill='%23ccc'/%3E%3Cpath d='M12 14c-5 0-8 2-8 5v1h16v-1c0-3-3-5-8-5z' fill='%23ccc'/%3E%3C/svg%3E"
                        class="img-circle elevation-2"
                        style="width:32px; height:32px;"
                        alt="Default Profile"
                    >
                @endif

                <span class="ml-2">
                    {{ Auth::user()->name }}
                </span>
            </a>

            <!-- DROPDOWN -->
            <div class="dropdown-menu dropdown-menu-right shadow">

                <a class="dropdown-item"
                   href="{{ route('profile.edit') }}">
                    <i class="fas fa-user mr-2"></i>
                    Profile
                </a>

                <div class="dropdown-divider"></div>

                <form method="POST"
                      action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>
                </form>

            </div>

        </li>
    </ul>

</nav>