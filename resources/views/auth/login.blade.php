<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-blue-200 via-white to-pink-200 min-h-screen flex items-center justify-center">

  <div class="w-full max-w-md p-8 bg-white rounded-3xl shadow-2xl border border-gray-200 animate-fade-in">
    <h2 class="text-3xl font-bold text-center text-blue-700 mb-8">Welcome Back!</h2>

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="mb-6 relative">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <div class="flex items-center relative">
          <i class="fi fi-rr-envelope absolute left-3 text-gray-400 text-lg"></i>
          <input id="email" type="email" name="email" required autofocus
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm" />
        </div>
      </div>

      <div class="mb-8 relative">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <div class="flex items-center relative">
          <i class="fi fi-rr-lock absolute left-3 text-gray-400 text-lg"></i>
          <input id="password" type="password" name="password" required
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm" />
        </div>
      </div>

      <button type="submit"
        class="w-full py-2 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-800 transition-all duration-200 shadow-md hover:shadow-lg">
        Log In
      </button>

      {{-- <p class="text-center text-sm text-gray-500 mt-6">
        Don't have an account? <a href="#" class="text-blue-600 hover:underline">Sign Up</a>
      </p> --}}
    </form>
  </div>

  <style>
    @keyframes fade-in {
      0% {
        opacity: 0;
        transform: scale(0.95);
      }

      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    .animate-fade-in {
      animation: fade-in 0.5s ease-out;
    }
  </style>
</body>

</html>
