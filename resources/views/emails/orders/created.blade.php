@component('mail::message')
# Introduction

Hi Quy, 
you have a new order: {{$transaction_id}}

@component('mail::button', ['url' => ''])
Click Here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
