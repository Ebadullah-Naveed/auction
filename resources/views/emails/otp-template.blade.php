<x-mail::message>
    <p>Dear Valued Customer,</p>
    <p>OTP has been successfully generated for AG Auction. As a security measure we recommend not sharing your OTP with anyone.</p>
    <table style="text-align: left;margin-bottom: 35px;">
        <tr>
            <th width="120">OTP:</th>
            <td>{{ $otp }}</td>
        </tr>
    </table>
    <p>Thank you for choosing us.</p>
    <strong>{{ env('APP_NAME') }}</strong>
</x-mail::message>
