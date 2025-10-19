@extends('layouts.app')

@section('content')
<section id="about" class="container">
    <div class="static-header">
        <h1 class="about-title">About EcoNest</h1>
        <p class="about-motto">Sustainability at your fingertips</p>
    </div>
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-body">
                    <h2>Our Mission</h2>
                    <p>
                        EcoNest provides a platform where users can browse, search, and purchase sustainable products. 
                        Key features include user authentication, product filtering, and a carbon footprint calculator 
                        for each item. With a secure shopping experience and robust order tracking, EcoNest ensures 
                        performance, security, and scalability. The platform is responsive, accessible across devices, 
                        and compliant with legal and environmental regulations.
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h2>Why EcoNest?</h2>
                    <p>
                        EcoNest addresses the growing demand for sustainable shopping options by providing an online 
                        platform dedicated to eco-friendly products. With increasing awareness of climate change, 
                        consumers actively seek ways to reduce their carbon footprint. EcoNest enables users to discover 
                        and purchase a variety of eco-conscious products while promoting a sustainable lifestyle. The 
                        motivation behind this project is to contribute to a greener future by making environmentally-friendly 
                        shopping accessible and convenient.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
