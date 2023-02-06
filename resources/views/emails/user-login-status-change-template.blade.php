<x-mail::message>
    <p>Dear {{$name}},</p>
    <p>Your account status has been changed to <strong>{{strtoupper($status==1?'Active':'InActive')}}</strong> associated with Phone# {{$phone_number}}.</p>
    <p>Thank you.</p>
    <strong>{{ env('APP_NAME') }}</strong>
</x-mail::message>
