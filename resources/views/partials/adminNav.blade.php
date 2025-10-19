<div class="navbar">
    <h1>
        <a href="{{route('admin.dashboard')}}">
            EcoNest
        </a>
    </h1> 

    <!-- Search Bar -->
    <div class="search-bar-container">
        <form action="{{ route('global.search') }}" method="GET">
            @if(!Auth::check())
                <select class="hidden" name="type" class="form-select">
                    <option value="products" {{ request('type') === 'products' ? 'selected' : '' }}>Products</option>
                </select>
            @else
                <select name="type" class="form-select">
                    <option value="products" {{ request('type') === 'products' ? 'selected' : '' }}>Products</option>
                    <option value="users" {{ request('type') === 'users' ? 'selected' : '' }}>Users</option>
                </select>
            @endif
            <div class="search-bar-flex">
                <input type="text" name="query" placeholder="Search" required />
                <button type="submit" class="shop-button">Search</button>
            </div>
        </form>
    </div>

    <!-- Links -->
    <div class="navbar-links">
        <!-- <a href="{{ route('purchase.list') }}" class="cool-link">Purchase History</a> -->
        <a href="{{ route('admin.products') }}" class="cool-link">
            <!--<img src="{{ asset('images/general_icons/wishlist_icon.png') }}" alt="Wishlist" class="wishlist-icon" style="width: 50px; height: 50px"/>-->
            Products
        </a> 
        <a href="{{ route('admin.users') }}" class="cool-link">
            <!--<img src="{{ asset('images/general_icons/notifications_icon.png') }}" alt="Notifications" class="nots-icon" style="width: 75px; height: 55px"/>-->
            Users
        </a>
        <a href="{{ route('admin.orders') }}" class="cool-link">
            <!--<img src="{{ asset('images/general_icons/cart_icon.png') }}" alt="Shopping Cart" class="cart-icon" style="width: 60px; height: 60px"/>-->
            Orders
        </a> 
    </div>

    <!-- Login/Register Buttons -->
    <div class="auth-section">
        @guest
        <div class="auth-buttons">
            <a href="{{ route('login') }}" class="button">Login</a>
        </div>
        @endguest
        @auth
            <div class="user-profile-element">
                <a href="{{ route('user.profile', Auth::id()) }}" class="cool-link">
                    <img src="{{ asset(Auth::user()->picture) }}" 
                        alt="{{ Auth::user()->name }}" 
                        class="user-profile-pic" />
                </a>
            </div>
        @endauth
    </div>
</div>
