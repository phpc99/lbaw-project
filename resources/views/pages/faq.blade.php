@extends('layouts.app')

@section('content')
<section id="faq" class="container">
    <div class="static-header">
        <h1 class="faq-title">FAQ</h1>
        <p class="faq-description">Find answers to the most common questions below:</p>
    </div>
    <div>
        <div class="card">
            <div class="card-body">
                <h2>1. How do I create an account on the website?</h2>
                <p>
                    To create an account, click on the "Register" button located at the top 
                    right of the homepage. Fill in the required details, including your name, 
                    email address, and password. Once submitted, you’ll receive a confirmation 
                    email. Click the link in the email to verify your account and get started!
                </p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h2>2. What payment methods are accepted?</h2>
                <p>
                    We accept a variety of payment methods, including:
                </p>
                <ul>
                    <li>Credit/Debit Cards</li>
                    <li>PayPal</li>
                    <li>MBWay</li>
                </ul>
                <p>
                    At checkout, you can select your preferred payment method to complete your purchase securely.
                </p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h2>3. Can I update or cancel an order after placing it?</h2>
                <p>
                    Yes, you can update or cancel your order as long as it hasn’t been shipped. To do so, 
                    log in to your account, go to "Order History," select the order you want to modify, and 
                    click "Update" or "Cancel." If the order has already been shipped, you can initiate a 
                    return once you receive it.
                </p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h2>4. How do I add or remove items from my wishlist?</h2>
                <p>
                    To add an item to your wishlist, click the "Add to Wishlist" button on the product page. 
                    To remove an item, navigate to your wishlist, find the product you want to remove, and click 
                    the "Remove" button. Note that you must be logged in to use the wishlist feature.
                </p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h2>5. How do I leave a review for a product I purchased?</h2>
                <p>
                    After purchasing a product, log in to your account and go to "Order History". 
                    Select the product you want to review, click on the "Write a Review" button, and 
                    share your feedback. You can rate the product, add comments, and submit your review 
                    to help other users make informed decisions.
                </p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h2>6. How can I reset my password?</h2>
                <p>
                    To reset your password, click on the "Forgot Password?" link on the login page. Enter your registered email address, and we’ll send you instructions to reset your password. Follow the steps in the email to create a new password.
                </p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h2>7. What is the return policy for products?</h2>
                <p>
                    You can return any product within 30 days of delivery, provided it is in its original condition and packaging. Log in to your account, go to "Order History," select the product you want to return, and follow the return instructions. Some exceptions may apply.
                </p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h2>8. Can I track my order?</h2>
                <p>
                    Yes, you can track your order by logging into your account and navigating to "Order History." Click on the order you want to track to view the tracking details and delivery status.
                </p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h2>9. Is my personal information secure on this website?</h2>
                <p>
                    Yes, we take your privacy seriously. All your data is encrypted and stored securely. We comply with the latest data protection regulations to ensure your personal information remains safe.
                </p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h2>10. How do I contact customer support?</h2>
                <p>
                    If you need assistance, you can contact our support team through the "Contact Us" page. Fill out the form or email us directly at support@example.com. We strive to respond to all inquiries within 24 hours.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
