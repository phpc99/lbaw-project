<div class="navbar">
    <h1>
        <a href="{{ route('products.list') }}">
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
        @if(Auth::check())
            <!-- <a href="{{ route('purchase.list') }}" class="cool-link">Purchase History</a> -->
            <a href="{{ route('wishlist.show') }}" class="cool-link">
                <img src="{{ asset('images/general_icons/wishlist_icon.png') }}" alt="Wishlist" class="wishlist-icon" style="width: 45px; height: 45px"/>
                @if (isset($wishlistCount) && $wishlistCount > 0)
                    <span class="badge">( {{ $wishlistCount }} )</span>
                @endif
            </a> 
            <a href="{{ route('notifications.list') }}" class="cool-link" style="position: relative;">
                <img src="{{ asset('images/general_icons/notifications_icon.png') }}" alt="Notifications" class="nots-icon" style="width: 70px; height: 52px"/>
                @if (isset($notificationCount) && $notificationCount > 0)
                    <span class="badge">( {{ $notificationCount }} )</span>
                @endif
            </a>
            <a href="{{ route('cart.show') }}" class="cool-link">
                <img src="{{ asset('images/general_icons/cart_icon.png') }}" alt="Shopping Cart" class="cart-icon" style="width: 60px; height: 60px"/>
                @if (isset($cartCount) && $cartCount > 0)
                    <span class="badge">( {{ $cartCount }} )</span>
                @endif
            </a>
        @endif 
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
                    <img src="{{ asset(Auth::user()->picture ?? 'images/general_icons/user_icon.png') }}" 
                        alt="{{ Auth::user()->name }}" 
                        class="user-profile-generic" />
                </a>
            </div>
        @endauth

    </div>
</div>
