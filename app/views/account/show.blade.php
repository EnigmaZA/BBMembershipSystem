@extends('layouts.main')

@section('meta-title')
{{ $user->name }} - Manage your membership
@stop

@section('page-title')
    {{ $user->name }}<br />
    <small>{{ $user->email }}</small>
@stop

@section('page-key-image')
    {{ HTML::memberPhoto($user->profile, $user->hash, 100, '') }}
@stop

@section('content')

@include('account.partials.member-status-bar')

@include('account.partials.member-admin-action-bar')

@if ($user->trusted && !$user->key_holder)
<div class="row">
    <div class="col-xs-12 col-md-6 ">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Door Key</h3>
            </div>
            <div class="panel-body">
                @if (!$user->key_deposit_payment_id)
                    <p>If you would like a door key you need to pay a £10 deposit, this can be paid now or by cash at the space.</p>

                    @include('partials/payment-form', ['reason'=>'door-key', 'displayReason'=>'Door Key Deposit', 'returnPath'=>route('account.show', [$user->id], false), 'amount'=>10, 'buttonLabel'=>'Pay Now', 'methods'=>['gocardless', 'stripe']])

                @else
                    You have paid the key deposit, please let a trustee know and they will issue you will a key.
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@if ($user->promoteGoCardless())

    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 pull-left">
            @include('account.partials.switch-to-gocardless-panel')
        </div>
    </div>

@endif

<!--
<div class="row">
    <div class="col-xs-12 col-md-6 ">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Build Brighton Credit</h3>
            </div>
            <div class="panel-body">
                <p>
                    There are a number services at Build Brighton that require payments, such as the laser fee,
                    tuck shop and component store. You can maintain a balance here to pay for these services quickly and easily.
                </p>
                <strong>{{ $user->present()->cashBalance }}</strong>
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.create', $user->id])) }}
                {{ Form::hidden('reason', 'balance') }}
                {{ Form::hidden('source', 'gocardless') }}
                {{ Form::text('amount', '5.00') }}
                {{ Form::submit('Top up Now (Direct Debit)', array('class'=>'btn btn-primary btn-xs')) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
-->

@if ($user->status == 'setting-up')

    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 pull-left">
            @include('account.partials.setup-panel')
        </div>
    </div>

@else

    @if ($user->status == 'left')
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 pull-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Member Left</h3>
                </div>
                <div class="panel-body">
                    <p>To rejoin please setup a direct debit for the monthly subscription.</p>
                    @include('account.partials.setup-payment')
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($user->status == 'leaving')
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 pull-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Member Leaving</h3>
                </div>
                <div class="panel-body">
                    <p class="lead">
                        Your currently setup to leave Build Brighton once your subscription payment expires.<br />
                        Once this happens you will no longer have access to the work space, mailing list or any other member areas.
                    </p>
                    <p>
                        If you wish to rejoin please use the payment options below
                    </p>
                    @include('account.partials.setup-payment')

                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($user->status == 'payment-warning')
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2 pull-left">
                @include('account.partials.payment-problem-panel')
            </div>
        </div>
    @endif


    <div class="row">
        <div class="col-xs-12 col-lg-12">
            @include('account.partials.induction-panel')
        </div>
    </div>


    @if ($user->status != 'honorary')
        <div class="row">
            <div class="col-xs-12 col-lg-12 pull-left">
                @include('account.partials.payments-panel')
            </div>
        </div>


        @if (($user->status != 'left') && ($user->status != 'leaving'))
        <div class="row">
            <div class="col-xs-12 col-lg-4">
                @include('account.partials.cancel-panel')
            </div>
        </div>
        @endif
    @endif

@endif


@stop