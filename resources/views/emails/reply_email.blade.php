@component('mail::message')
# Thank You for Contacting Us!

Hello {{ $name }},

Thank you for reaching out to us. We have received your message and will get back to you as soon as possible.

If you have any further questions or concerns, feel free to reach out.


  
@component('mail::button', ['url' => url('/')])
Visit Our Website
@endcomponent
  
Best regards,

{{ config('app.name') }}
@endcomponent