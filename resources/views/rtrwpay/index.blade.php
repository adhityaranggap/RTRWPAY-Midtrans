<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RT RW PAY</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <style>
        body {
            min-height: 75rem;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">RT RW PAY</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('transaction.create')}}">Transaction <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="jumbotron">
        <div class="container">
            <h1 class="display-4">RT RW PAY</h1>
            <p class="lead">Platform pembayaran iuran RT RW .</p>
        </div>
    </div>

    <div class="container">
      <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama KK</th>
                <th>Jumlah</th>
                <th>Transaction Type</th>
                <th>Blok Rumah</th>
                <th>Nomor Rumah</th>
                <th>Status</th>
                <th style="text-align: center;"></th>
            </tr>
        </thead>
        <tbody>
          @foreach($transactions as $transaction)
          <tr>
                <th><code>{{ $transaction->id }}</code></th>
                <th>{{ $transaction->transaction_name}}</th>
                <th>Rp. {{ number_format ($transaction->amount) }}</th>
                <th>{{ ucwords(str_replace('_',' ', $transaction->package_name)) }}</th>
                <th>{{ $transaction->block_home}}</th>
                <th>{{ $transaction->home_number}}</th>
                <th>{{ ucfirst($transaction->status) }}</th>
                <th style="text-align: center;">
                    @if ($transaction->status == 'pending')
                    <button class="btn btn-success btn-sm" onclick="snap.pay('{{ $transaction->snap_token }}')"> Complate Payment</button>
                    @endif
                </th>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
            <td colspan="6">{{ $transactions->links() }}</td>
            
        </tfoot>
      </table>
    </div>



    <script src="https://code.jquery.com/jquery-3.4.1.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js">
    </script>
   <!-- Production or Sandbox -->
    <!-- <script src="{{
        !config('services.midtrans.isProduction') ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.clientKey')
    }}"></script> -->
    <!-- Sandbox -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('service.midtrans.clientkey') }}"></script>
 
</body>

</html>
