<nav
    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <a href="{{ route(explode('.', Route::currentRouteName())[0] . '.index') }}"
           class="text-secondary fw-bold opacity-50">
            {{ ucfirst(explode('.', Route::currentRouteName())[0]) }}
        </a>
        &nbsp;
        @if (!in_array(explode('.', Route::currentRouteName())[1], ['index']))
            <span class="fw-bold navbar-brand mb-0 h1 px-1 text-secondary lh-1"> > {{ ucfirst(explode('.', Route::currentRouteName())[1]) }}
       </span>
        @endif
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Place this tag where you want the button to render. -->
            <li class="nav-item lh-1 me-3 fw-semibold" style="color: var(--bs-heading-color)">
                Admin
            </li>

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar">
                        <img src="/assets/img/avatars/profile.png" alt class="w-px-40 h-auto rounded-circle"/>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar">
                                        <img src="../assets/img/avatars/profile.png" alt
                                             class="w-px-40 h-auto rounded-circle"/>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-medium d-block" style="color: var(--bs-heading-color)">Admin</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.index') }}">

                            <span class="align-middle"> <i class="bx bx-user me-2 "></i>Change Password</span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item" href="#"
                               onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <span class="align-middle"> <i class='bx bx-log-out'></i> Logout</span>
                            </a>
                        </form>

                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
