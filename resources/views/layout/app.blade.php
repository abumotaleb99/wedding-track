<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WeddingTracker</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600;700;800&family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700&display=swap"
      rel="stylesheet"
    />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tailwind Config -->
    <script>
      tailwind.config = { 
        theme: {
          extend: {
            colors: {},
            fontFamily: {
              montserrat: ["Montserrat", "sans-serif"],
              nunito: ["Nunito Sans", "sans-serif"],
            },
          },
        },
      };
    </script>
    <link href="{{asset('assets/css/progress.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/toastify.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/jquery.dataTables.min.css')}}" rel="stylesheet" />
    <style>
    .d-none{
      display: none;
    }
    </style>
  </head>
  <body>
    <div id="loader" class="LoadingOverlay">
        <div class="Line-Progress">
            <div class="indeterminate"></div>
        </div>
    </div>
    <div class="bg-[#F6F7F9]">
      <div class="flex items-center justify-center py-2">
        <ul class="items-center bg-white p-[6px] rounded flex">
          <li class="px-5 py-2">
            <a
              href="{{ url('/') }}"
              class="hover:text-[#E72424] font-nunito font-bold {{ Request::is('/') ? 'text-[#E72424]' : 'text-[#090B10]' }}"
              >Check-In List
            </a>
          </li>
          <li class="px-5 py-2">
            <a
              href="{{ url('/guest-invitations') }}"
              class="hover:text-[#E72424] font-nunito font-bold {{ Request::is('guest-invitations') ? 'text-[#E72424]' : 'text-[#090B10]' }}"
              >Guest Invitations
            </a>
          </li>
        </ul>
      </div>
    </div>

    @yield('content')

    
    <script src="{{asset('assets/js/jquery-3.7.0.min.js')}}"></script>
    <script src="{{asset('assets/js/toastify-js.js')}}"></script>
    <script src="{{asset('assets/js/axios.min.js')}}"></script>
    <script src="{{asset('assets/js/config.js')}}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script>
      // Show the loader when the page starts loading
      window.addEventListener('load', function() {
        document.getElementById('loader').classList.add('d-none');
      });

    </script>
  </body>
</html>
