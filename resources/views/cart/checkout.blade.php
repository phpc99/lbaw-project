@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Checkout</h1>

    <!-- Cart Summary Section -->
    <div class="col-md-8">
        <h2>Order Summary</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->product->price, 2) }}</td>
                    <td>${{ number_format($item->total * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Total: ${{ number_format($total * $item->quantity, 2) }}</h4>
    </div>

    <!-- Billing & Shipping Form -->
    <div class="col-md-4">
        <h2>Billing Details</h2>
        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <!-- Personal Information -->
            <div class="form-group">
                <p><strong>Name</strong></p>
                <p>{{ Auth::user()->name }}</p>
            </div>

            <div class="form-group">
                <p><strong>Email</strong></p>
                <p>{{ Auth::user()->email }}</p>
            </div>

            <!-- Address Selection -->
            <div class="form-group">
                <label for="address_method"><strong>Address</strong></label>
                <select name="address_method" id="address_method" class="form-control" required>
                    @foreach($addresses as $address)
                        <option value="{{ $address->address_id }}">{{ $address->street }}, {{ $address->city }}, {{ $address->country }}, {{ $address->postal_code }}</option>
                    @endforeach
                    <option value="0">New Address</option>
                </select>
            </div>

            <!-- Address -->
            <div id="address-form">
                <div class="form-group">
                    <label for="street"><strong>Street*</strong></label>
                    <input type="text" name="street" id="street" class="form-control" placeholder="Street">
                </div>

                <div class="form-group">
                    <label for="city"><strong>City*</strong></label>
                    <input type="text" name="city" id="city" class="form-control" placeholder="City">
                </div>

                <div class="form-group">
                    <label for="country"><strong>Country*</strong></label>
                    <input type="text" name="country" id="country" class="form-control" placeholder="Country">
                </div>

                <div class="form-group">
                    <label for="postal_code"><strong>Zip Code*</strong></label>
                    <input type="text" name="postal_code" id="postal_code" class="form-control" placeholder="Zip Code">
                </div>
            </div>

            <h2>Payment Details</h2>

            <!-- Payment Method Selection -->
            <div class="form-group">
                <label for="payment_method"><strong>Payment Method</strong></label>
                <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="Card">Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="MBWay">MBWay</option>
                </select>
            </div>

            <!-- Card Payment Fields -->
            <div id="card-details" class="payment-details" style="display: none;">
                <div class="form-group">
                    <label for="name"><strong>Card Holder Name*</strong></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Card Holder Name">
                </div>

                <div class="form-group">
                    <label for="card_number"><strong>Card Number*</strong></label>
                    <input type="text" name="card_number" id="card_number" class="form-control" placeholder="Card Number">
                </div>

                <div class="form-group">
                    <label for="expiry_date"><strong>Expiry Date*</strong></label>
                    <input type="date" name="expiry_date" id="expiry_date" class="form-control">
                </div>
            </div>

            <!-- PayPal Placeholder -->
            <div id="paypal-details" class="payment-details" style="display: none;">
                <div class="form-group">
                    <label for="email"><strong>PayPal Email*</strong></label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="Your PayPal account email">
                </div>
            </div>

            <!-- MBWay Payment Fields -->
            <div id="mbway-details" class="payment-details" style="display: none;">
                <div class="form-group">
                    <label for="mbway_phone"><strong>Phone Number*</strong></strong></label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <!-- Símbolo + fora do campo -->
                        <span style="font-size: 18px; font-weight: bold; margin-top: 5px;">+</span>
                        <input 
                            type="number" 
                            name="mbway_prefix" 
                            id="mbway_prefix" 
                            class="form-control" 
                            placeholder="351" 
                            style="width: 80px; text-align: center; appearance: textfield;" 
                            maxlength="3" 
                            required
                            oninput="validatePrefix()"
                        >
                        <input 
                            type="number" 
                            name="mbway_phone" 
                            id="mbway_phone" 
                            class="form-control" 
                            placeholder="Your MBWay phone number" 
                            style="flex-grow: 1; appearance: textfield;" 
                            required
                            oninput="validatePhone()"
                        >
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Complete Purchase</button>
            
            <!-- Hidden Total Field -->
            <input type="hidden" name="total" value="{{ $total }}">
            <input type="hidden" name="cartItems" value="{{ json_encode($cartItems) }}">
        </form>
    </div>
</div>

<script>
    function validatePrefix() {
        const prefixInput = document.getElementById('mbway_prefix');
        if (prefixInput.value.length > 3) {
            prefixInput.value = prefixInput.value.slice(0, 3);  
        }
    }

    function validatePhone() {
        const phoneInput = document.getElementById('mbway_phone');
        if (phoneInput.value.length > 9) {
            phoneInput.value = phoneInput.value.slice(0, 9);  
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const paymentMethodSelect = document.getElementById('payment_method');
        const cardDetails = document.getElementById('card-details');
        const paypalDetails = document.getElementById('paypal-details');
        const mbwayDetails = document.getElementById('mbway-details');

        function updatePaymentDetails() {
            const selectedMethod = paymentMethodSelect.value;
            cardDetails.style.display = selectedMethod === 'Card' ? 'block' : 'none';
            paypalDetails.style.display = selectedMethod === 'PayPal' ? 'block' : 'none';
            mbwayDetails.style.display = selectedMethod === 'MBWay' ? 'block' : 'none';
        }

        paymentMethodSelect.addEventListener('change', updatePaymentDetails);

        updatePaymentDetails();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const addressMethodSelect = document.getElementById('address_method');
        const addressDetails = document.getElementById('address-form');

        function updateAddressFields() {
            const selectedAddress = addressMethodSelect.value;
            addressDetails.style.display = selectedAddress === '0' ? 'block' : 'none';
        }

        addressMethodSelect.addEventListener('change', updateAddressFields);

        updateAddressFields();
    });
</script>
@endsection
