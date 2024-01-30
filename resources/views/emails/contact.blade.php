
@component('mail::message')
  # Name: {{ $data['name'] }}
 # Email: {{  $data['email'] }}<br>
 Subject: {{ $data['subject']  }} <br><br>
 Message:<br> {{ $data['message'] }}
  

  
{{-- @component('mail::button', '$url')
Visit Our Website
@endcomponent --}}
  
Thanks,

{{ config('app.name') }}
@endcomponent