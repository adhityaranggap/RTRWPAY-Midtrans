<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RTRW Pay</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <style>
        body {
            min-height: 75rem;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">RTRW Pay</a>
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
            <h1 class="display-4">RT RW Pay</h1>
            <p class="lead">Platform pembayaran iuran Cluster.</p>
        </div>
    </div>

    <div class="container">
        <form action="#" id="transaction_form">
            <legend>Transaction</legend>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" name="transaction_name" class="form-control" id="transaction_name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">E-Mail</label>
                        <input type="email" name="transaction_email" class="form-control" id="transaction_email">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Jenis Iuran</label>
                        <select name="transaction_type" class="form-control" id="transaction_type">
                          <option value="IPL">IPL</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Blok</label>
                        <select name="block_home" class="form-control" id="block_home">
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="E{{ $i }}">E{{ $i }}</option>
                        @endfor 
                    
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Nomor</label>
                        <select name="home_number" class="form-control" id="home_number">

                        @for ($i = 1; $i <= 20; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor                    
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Jumlah</label>
                        <input type="number" name="amount" class="form-control" id="amount" value=100000>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Catatan (Opsional)</label>
                        <textarea name="note" cols="30" rows="3" class="form-control" id="note"></textarea>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Kirim</button>
        </form>
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
    <script>
        $("#transaction_form").submit(function(event) {
            event.preventDefault();

            $.post("/api/transaction", {
                _method: 'POST',
                _token: '{{ csrf_token() }}',
                transaction_name: $('input#transaction_name').val(),
                transaction_email: $('input#transaction_email').val(),
                package_name: $('select#package_name').val(),
                block_home: $('select#block_home').val(),
                home_number: $('select#home_number').val(),
                transaction_type: $('select#transaction_type').val(),
                amount: $('input#amount').val(),
                note: $('textarea#note').val(),
            },
            function (data, status) {
                console.log(data);
                snap.pay(data.snap_token, {
                    // Optional
                    onSuccess: function (result) {
                        location.reload();
                    },
                    // Optional
                    onPending: function (result) {
                        location.reload();
                    },
                    // Optional
                    onError: function (result) {
                        location.reload();
                    }
                });
                return false;
            });
        })
    </script>
</body>

</html>
