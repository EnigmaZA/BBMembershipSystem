<?php

class CashPaymentController extends \BaseController {


    /**
     * @var \BB\Repo\PaymentRepository
     */
    private $paymentRepository;

    function __construct(\BB\Repo\PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;

        $this->beforeFilter('role:admin', array('only' => ['store']));
    }


    /**
     * Start the creation of a new gocardless payment
     *   Details get posted into this method and the redirected to gocardless
     * @param $userId
     * @throws \BB\Exceptions\AuthenticationException
     * @throws \BB\Exceptions\FormValidationException
     * @throws \BB\Exceptions\NotImplementedException
     */
	public function store($userId)
    {
        $user = User::findWithPermission($userId);

        $amount      = Request::get('amount');
        $reason      = Request::get('reason');
        $returnPath  = Request::get('return_path');

        $this->paymentRepository->recordPayment($reason, $userId, 'cash', '', $amount);

        Notification::success("Payment recorded");
        return Redirect::to($returnPath);
    }

}