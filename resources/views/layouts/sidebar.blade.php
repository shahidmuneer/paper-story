<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="/dashboard">
                    <i class="nav-icon icon-speedometer"></i> Dashboard
                </a>
            </li>
            @can('read-users','read-roles')
            <li class="nav-title">Settings</li>
            @endcan
            @can('read-users')
            <li class="nav-item">
                <a class="nav-link" href="/users">
                    <i class="nav-icon icon-people"></i> Users
                </a>
            </li>
            @endcan
            @can('read-roles')
            <li class="nav-item">
                <a class="nav-link" href="/roles">
                    <i class="nav-icon icon-key"></i> Roles
                </a>
            </li>
            @endcan

            @can('orders-list')
            <li class="nav-item">
                <a class="nav-link" href="/users/customer-orders">
                    <i class="fa fa-book"></i> Customer Orders
                </a>
            </li>
            @endcan

            <!-- @can('read-topup')
            <li class="nav-item">
                <a class="nav-link" href="/agents/topup">
                    <i class="nav-icon icon-card"></i> Send Money 
                </a>
            </li>
            @endcan
            @can('read-transactions')
            <li class="nav-item">
                <a class="nav-link" href="/users/transactions">
                    <i class="nav-icon icon-card"></i> View Transactions
                </a>
            </li>
            @endcan -->

        </ul>
    </nav>
    <sidebar></sidebar>
</div>
