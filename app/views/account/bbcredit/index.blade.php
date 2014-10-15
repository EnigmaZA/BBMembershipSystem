@extends('layouts.main')

@section('title')
Build Brighton Credit {{ $user->name }}
@stop

@section('content')

<div class="row page-header">
    <div class="col-xs-12 col-sm-6 col-md-10">
        <h1>Build Brighton Credit</h1>
        <p>
            There are a number services at Build Brighton that require payments, such as the laser fee,
            tuck shop and component store. You can maintain a balance here to pay for these services quickly and easily.
        </p>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-2">
        <div class="panel panel-default text-center">
            <div class="panel-heading">
                <h3 class="panel-title">Balance</h3>
            </div>
            <div class="panel-body">
                <span class="credit-figure">{{ $user->present()->cashBalance }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6 ">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Add Credit</h3>
            </div>
            <div class="panel-body">
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.create', $user->id], 'class'=>'navbar-form')) }}
                {{ Form::hidden('reason', 'balance') }}
                {{ Form::hidden('source', 'gocardless') }}
                {{ Form::text('amount', '5.00', ['class'=>'form-control']) }}
                {{ Form::submit('Top Up Now (Direct Debit)', array('class'=>'btn btn-primary')) }}
                {{ Form::close() }}
                @if (Auth::user()->isAdmin())
                <div class="well">
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.store', $user->id], 'class'=>'navbar-form')) }}
                {{ Form::hidden('reason', 'balance') }}
                {{ Form::text('amount', '5.00', ['class'=>'form-control']) }}
                {{ Form::select('source', ['cash'=>'Cash'], null, ['class'=>'form-control']) }}
                {{ Form::submit('Record Top up', array('class'=>'btn btn-default')) }}
                {{ Form::close() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <?php $payments = $user->payments()->paginate(10); ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Payments</h3>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Reason</th>
                    <th>Method</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->present()->reason }}</td>
                    <td>{{ $payment->present()->method }}</td>
                    <td>{{ $payment->present()->date }}</td>
                    <td>{{ $payment->present()->amount }}</td>
                    <td>{{ $payment->present()->status }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="panel-footer">
            <?php echo $payments->links(); ?>
            </div>
        </div>
    </div>
</div>

@stop