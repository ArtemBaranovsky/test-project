<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentServiceInterface;
use App\Exceptions\PaymentException;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function __construct(protected PaymentServiceInterface $paymentService)
    {
    }

    public function initiatePayment(PaymentRequest $request): \Illuminate\Http\RedirectResponse
    {
        // TODO: add validation to PaymentRequest
        $subscriptionId = $request->input('subscription_id');

        $paymentUrl = $this->paymentService->initiatePayment($subscriptionId);

        return redirect()->to($paymentUrl);
    }

    public function handlePaymentCallback(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // TODO: add custom validation to Request
            $transactionId  = $request->input('transaction_id');
            $subscriptionId = $request->input('subscription_id');

            $paymentStatus = $this->paymentService->checkPaymentStatus($transactionId);

            $this->paymentService->processPaymentStatus($paymentStatus, $subscriptionId);

            return response()->json(['status' => 'success']);
        } catch (PaymentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
