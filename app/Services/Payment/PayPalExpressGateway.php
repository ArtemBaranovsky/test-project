<?php

namespace App\Services\Payment;

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalExpressGateway extends PaymentGateway
{
    protected ApiContext $apiContext;

    public function __construct()
    {
        $config = config('payment.paypal');

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential($config['client_id'], $config['secret'])
        );
    }

    public function initiatePayment($amount): ?string
    {
        $payment = new Payment();
        $payment->setIntent('sale');
        $payment->setPayer($this->createPayer());
        $payment->setTransactions([$this->createTransaction($amount)]);
        $payment->setRedirectUrls($this->createRedirectUrls());

        $payment->create($this->apiContext);

        $approvalUrl = $payment->getApprovalLink();

        return $approvalUrl;
    }

    public function verifyPayment($paymentId, $payerId): bool
    {
        $payment = Payment::get($paymentId, $this->apiContext);

        $execution = $this->createPaymentExecution($payerId);

        $payment->execute($execution, $this->apiContext);

        if ($payment->getState() === 'approved') {
            return true;
        }

        return false;
    }

    private function createPayer(): Payer
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        return $payer;
    }

    private function createTransaction($amount): Transaction
    {
        $transaction = new Transaction();
        $transaction->setAmount($this->createAmount($amount));
        $transaction->setDescription('Payment description');

        return $transaction;
    }

    private function createAmount($amount): Amount
    {
        $paymentAmount = new Amount();
        $paymentAmount->setCurrency('USD');
        $paymentAmount->setTotal($amount);

        return $paymentAmount;
    }

    private function createRedirectUrls(): RedirectUrls
    {
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl('https://example.com/success');
        $redirectUrls->setCancelUrl('https://example.com/cancel');

        return $redirectUrls;
    }

    private function createPaymentExecution($payerId): PaymentExecution
    {
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        return $execution;
    }

    public function checkPaymentStatus($transactionId): string
    {
        $payment = Payment::get($transactionId, $this->apiContext);

        return $payment->getState();
    }

    public function confirmPayment($transactionId): string
    {
        $payment = Payment::get($transactionId, $this->apiContext);

        $execution = $this->createPaymentExecution($payment->getPayer()->getPayerInfo()->getPayerId());
        $payment->execute($execution, $this->apiContext);

        return $payment->getState();
    }

    public function cancelPayment($transactionId): string
    {
        $payment = Payment::get($transactionId, $this->apiContext);

        $payment->removeTransaction($transactionId);

        return $payment->getState();
    }
}
