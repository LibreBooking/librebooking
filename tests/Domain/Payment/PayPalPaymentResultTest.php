<?php

require_once(ROOT_DIR . 'Domain/Values/PayPalPaymentResult.php');

class PayPalPaymentResultTest extends TestBase
{
    public function testParsesJsonString()
    {
        $json = '{"id":"PAY-1WW82131WF352133WLH4QOUQ","intent":"sale","state":"approved","cart":"5FD72329MG139642M","create_time":"2017-10-31T23:29:38Z","payer":{"payment_method":"paypal","status":"VERIFIED","payer_info":{"email":"lqqkout13-buyer@aol.com","first_name":"test","middle_name":"test","last_name":"buyer","payer_id":"3GTAL5H4LTHV6","country_code":"US","shipping_address":{"recipient_name":"test buyer","line1":"1 Main St","city":"San Jose","state":"CA","postal_code":"95131","country_code":"US"}}},"transactions":[{"amount":{"total":"1.00","currency":"USD","details":{}},"item_list":{},"related_resources":[{"sale":{"id":"57031264LU207192J","state":"completed","payment_mode":"INSTANT_TRANSFER","protection_eligibility":"ELIGIBLE","parent_payment":"PAY-1WW82131WF352133WLH4QOUQ","create_time":"2017-10-31T23:29:38Z","update_time":"2017-10-31T23:29:38Z","amount":{"total":"1.00","currency":"USD","details":{"subtotal":"1.00"}}}}]}]}';

        $payment = PayPalPaymentResult::FromJsonString($json);

        $timestamp = new Date('2017-10-31 23:29:38', 'UTC');
        $this->assertEquals($json, $payment->Raw);
        $this->assertEquals(1.00, $payment->Amount);
        $this->assertEquals('USD', $payment->Currency);
        $this->assertEquals('57031264LU207192J', $payment->Id);
        $this->assertEquals($timestamp, $payment->Timestamp);
    }
}
