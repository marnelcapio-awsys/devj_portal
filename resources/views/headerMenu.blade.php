@php
    $userInfo = Auth::user();
@endphp

<nav class="navbar navbar-expand-md p-2 pe-2 ps-2" style="background-color:#1746A2;padding: 0!important;">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" id="home-link" href="{{ route('home') }}">DEV J PORTAL </br><p style="font-size: 10px;margin: 0px">v1.1.0</p></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#headerMenu" aria-controls="headerMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="headerMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link text-white"  role="button" style="pointer-events: none;">Employees</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('employees') }}" class="dropdown-item small text-white">View List</a></li>            
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link text-white"  role="button" style="pointer-events: none;">Laptops</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('laptops.index') }}" class="dropdown-item small text-white">View List</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a href="{{ route('laptops.create') }}" class="dropdown-item small text-white">Create Laptop</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link text-white"  role="button" style="pointer-events: none;">Softwares</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('softwares') }}" class="dropdown-item small text-white">View List</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a href="{{ route('softwares.create') }}" class="dropdown-item small text-white">Create Software</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link text-white"  role="button" style="pointer-events: none;">Projects</a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('projects') }}" class="dropdown-item small text-white">View List</a></li>
                        @if ($userInfo->roles == config('constants.MANAGER_ROLE_VALUE'))
                            <li><hr class="dropdown-divider"></li>
                            <li><a href="{{ route('projects.create') }}" class="dropdown-item small text-white">Create Project</a></li>
                        @endif
                    </ul>
                </li>
                @if ($userInfo->roles === config('constants.MANAGER_ROLE_VALUE') ||  $userInfo->server_manage_flag)
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link text-white"  role="button" style="pointer-events: none;">Servers</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('servers.index') }}" class="dropdown-item small text-white">View List</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a href="{{ route('servers.create') }}" class="dropdown-item small text-white">Create Server</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link text-white text-nowrap"  role="button" style="pointer-events: none;"><i class="bi bi-person-fill"> </i>{{ $userInfo->last_name .', ' .$userInfo->first_name }}</a>
                    <ul class="dropdown-menu">
                        @if ($userInfo->email != 'devj-portal@awsys-i.com')
                        <li><a href="{{ route('employees.details', ['id' => $userInfo->id]) }}" class="dropdown-item small text-white">My Details</a></li>
                        <li><hr class="dropdown-divider"></li>
                        @endif
                        <li><a href="{{ route('logout') }}" class="dropdown-item small text-white">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
