<!DOCTYPE html>
<html lang="en">

<head>
    @include('partial.customer_head')
</head>

<body class="bg-gray-50">

    @php
        $app_name = app_config('AppName');
        $image = app_config('AppLogo');
    @endphp


    @include('partial.customer_navbar')

    @yield('customer')

    <a href="{{app_config('telegram_id')}}" target="_blank" class="telegram-chat">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240">
    <path fill="white" d="M120 0C53.7 0 0 53.7 0 120s53.7 120 120 120 120-53.7 120-120S186.3 0 120 0zm54.3 80.6l-22.4 104.4c-1.7 7.8-6.1 9.8-12.4 6.1l-34.3-25.3-16.6 15.9c-1.8 1.8-3.3 3.3-6.7 3.3l2.4-34.6 62.9-56.9c2.7-2.4-.6-3.8-4.2-1.4L84.8 125.5 54.9 111.9c-7.5-2.9-7.6-7.5 1.6-11.1l103.9-40.0c4.8-1.9 9.0 1.1 8.0 19.8z"/>
  </svg>
</a>

<style>
.telegram-chat {
  position: fixed;
  right: 20px;
  bottom: 20px;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: #0088cc;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  z-index: 9999;
  transition: transform 0.2s;
}
.telegram-chat:hover {
  transform: scale(1.1);
  background: #0077b3;
}
.telegram-chat svg {
  width: 32px;
  height: 32px;
}
</style>


    @include('partial.customer_footer')

</body>



</html>
