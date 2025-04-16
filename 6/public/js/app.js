$(document).ready(function() {
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();

        var data = {
            amount: $('.amount').val(),
        };

        $('.loading').show();

        // wait 1 seconds
        setTimeout(function() {
            $.post('/api/criar-invoice', {
                data: JSON.stringify(data)
            }).done(function(response) {
                const responseData = response;
                console.log('Invoice created successfully:', responseData);
                $('.payment').show();
                $('.qrcode').html("<img src='https://api.qrserver.com/v1/create-qr-code/?data="+responseData.invoice.text+"&size=512x512%27;'>");
                $('.lninvoice').text(responseData.invoice.text);
                $('#paymentForm').hide();
                $('.loading').hide();

                waitTopaymentConfirmation(responseData.invoice.text);
            }).fail(function(error) {
                console.error('Error creating invoice:', error);
                $('.loading').hide();
            });
        }, 1000);

      
    });

    function waitTopaymentConfirmation(invoice) {
        function checkPayment() {
            $.get('api/checar/' + invoice)
            .done(function(response) {
                const responseData = response;
                if (responseData.received > 0) {
                    $('.payment').hide();
                    $('.success').show();
                    $('.piada').html(responseData.piada);
                    $('.piada').show();
                } else {
                    console.log('Invoice not yet received, checking again...');
                    setTimeout(checkPayment, 5000);
                }
            }).fail(function(error) {
                console.error('Error checking invoice status:', error);
            });
        }

        checkPayment();
    }

});
