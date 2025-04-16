<aside class="top-0 left-0 w-64 min-h-screen bg-gradient-to-b from-blue-50 via-white to-blue-100 p-6 shadow-2xl rounded-r-3xl z-40">
    <!-- Brand -->
    <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center text-xl font-bold shadow-md">
            C
        </div>
        <h1 class="text-2xl font-extrabold text-blue-700 tracking-wide">CerMarts!</h1>
    </div>

    <!-- Navigation -->
    <nav>
        <ul class="space-y-2 text-sm font-medium">
            <!-- Dashboard -->
            <li>
                <a href="{{ Auth::user()->role === 'admin' ? route('dashboard.admin') : route('dashboard.petugas') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition 
                    {{ request()->routeIs('dashboard.admin') || request()->routeIs('dashboard.petugas') 
                        ? 'bg-blue-600 text-white shadow' 
                        : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700' }}">
                    <i class="fi fi-rr-home text-lg"></i>
                    Dashboard
                </a>
            </li>

            <!-- Produk -->
            <li>
                <a href="{{ route('produk.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition 
                    {{ request()->routeIs('produk.index') 
                        ? 'bg-blue-600 text-white shadow' 
                        : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700' }}">
                    <i class="fi fi-rr-box text-lg"></i>
                    Produk
                </a>
            </li>

            <!-- Penjualan -->
            <li>
                <a href="{{ route('penjualan.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition 
                    {{ request()->routeIs('penjualan.index') 
                        ? 'bg-blue-600 text-white shadow' 
                        : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700' }}">
                    <i class="fi fi-rr-shopping-cart text-lg"></i>
                    Pembelian
                </a>
            </li>

            <!-- User (Admin Only) -->
            @auth
            @if (Auth::user()->role === 'admin')
                <li>
                    <a href="{{ route('user.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition 
                        {{ request()->routeIs('user.index') 
                            ? 'bg-blue-600 text-white shadow' 
                            : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700' }}">
                        <i class="fi fi-rr-users-alt text-lg"></i>
                        User
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('members.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition 
                    {{ request()->routeIs('members.index') 
                        ? 'bg-blue-600 text-white shadow' 
                        : 'text-gray-700 hover:bg-blue-100 hover:text-blue-700' }}">
                    <i class="fi fi-rr-id-badge text-lg"></i>
                    Member
                </a>
            </li>
        @endauth
        </ul>
    </nav>
</aside>
