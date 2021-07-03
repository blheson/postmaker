<!DOCTYPE html>
<html>

<head>
    <title>Buy cool new product</title>
    <meta name="viewport" content="width = device-width, initial-scale = 1.0">

    <!-- <link rel="stylesheet" href="style.css"> -->
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <!-- <script src="https://js.stripe.com/v3/"></script> -->
</head>

<body>
    <section>
        <div class="form-row">
            <label for="card-element">
                Credit or debit card
            </label>

            <form action="create-che.php" id="payment-form" method="POST">
                <input type="hidden" name="amount" value="1000">
                <input type="hidden" name="currency" value="usd">
                <input type="hidden" name="description" value="Extension subscription">
                <div id="card-element"></div>
                <button type="submit" id="checkout-button">Checkout</button>
            </form>
            <!-- A Stripe Element will be inserted here. -->
            <!-- Used to display Element errors. -->
            <div id="card-errors" role="alert"></div>
        </div>

    </section>
</body>
<script src="https://js.stripe.com/v3/"></script>
<script src="charge.js"></script>
<script type="text/javascript">

</script>

</html>