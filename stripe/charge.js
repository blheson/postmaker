 // Create an instance of the Stripe object with your publishable API key
 var stripe = Stripe("pk_test_51IxHxkG8oNMh0OfzqCM0Jtn5TINX9rkJJ6pYYF1Ohz81LNf4lxhFH1FGuFuSwstvpDqBKv29E0HSvJNnS7ICnwQE003TyDXG72");
 var elements = stripe.elements();
 var checkoutButton = document.getElementById("checkout-button");
 // Custom styling can be passed to options when creating an Element.
 var style = {
     base: {
         // Add your base input styles here. For example:
         fontSize: '16px',
         color: '#32325d',
     },
 };
 // Create an instance of the card Element.
 var card = elements.create('card', {
     style: style
 });
 // Add an instance of the card Element into the `card-element` <div>.
 card.mount('#card-element');
 // Create a token or display an error when the form is submitted.
 var form = document.getElementById('payment-form');
 form.addEventListener('submit', function(event) {
     event.preventDefault();
     stripe.createToken(card).then(function(result) {
        console.log(result)
         if (result.error) {
             // Inform the customer that there was an error.
             var errorElement = document.getElementById('card-errors');
             errorElement.textContent = result.error.message;
         } else {
             // Send the token to your server.
             stripeTokenHandler(result.token);
         }
     });
 });
 function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    // var hiddenInput2 = document.createElement('input');
    // hiddenInput2.setAttribute('type', 'hidden');
    // hiddenInput2.setAttribute('name', 'email');
    // hiddenInput2.setAttribute('value', 'test@gmail.com');
    form.appendChild(hiddenInput);
    // form.appendChild(hiddenInput2);
    // Submit the form
    form.submit();
  }