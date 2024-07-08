<x-template>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-4">
                <ul class="no-list-style">
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="ft-user font-small-3"></i> Full Name:</a></span>
                        <span class="d-block overflow-hidden">OWHONDA IBE PRINCE</span>
                    </li>
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="ft-mail font-small-3"></i> Email:</a></span>
                        <span class="d-block overflow-hidden">briiteheart@gmail.com</span>
                    </li>
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="ft-map-pin font-small-3"></i> State:</a></span>
                        <span class="d-block overflow-hidden">Rivers</span>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <ul class="no-list-style">
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="ft-smartphone font-small-3"></i> Mobile Number:</a></span>
                        <span class="d-block overflow-hidden">2348184702082</span>
                    </li>
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="icon-present font-small-3"></i> Date of Birth:</a></span>
                        <span class="d-block overflow-hidden">24 Jun 2006</span>
                    </li>
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="fa fa-user font-small-3"></i> Gender:</a></span>
                        <span class="d-block overflow-hidden">Male</span>
                    </li>


                </ul>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <ul class="no-list-style">
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="ft-briefcase font-small-3"></i> Occupation:</a></span>
                        <span class="d-block overflow-hidden">WORKER</span>
                    </li>
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="ft-globe font-small-3"></i> Address:</a></span>
                        <a class="d-block overflow-hidden">NO. 32, OHIA STREET, OLD </a>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12 col-md-6 col-lg-4">
                <ul class="no-list-style">
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="ft-sidebar font-small-3"></i> Registration Number:</a></span>
                        <span class="d-block overflow-hidden">AH545KNM</span>
                    </li>
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="ft-server font-small-3"></i> Chassis Number:</a></span>
                        <span class="d-block overflow-hidden">4T1BG22K3XU448715</span>
                    </li>
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="ft-settings font-small-3"></i> Engine Number:</a></span>
                        <span class="d-block overflow-hidden">5S5180054</span>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <ul class="no-list-style">
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="icon-support font-small-3"></i> Vehicle Make:</a></span>
                        <span class="d-block overflow-hidden">TOYOTA</span>
                    </li>
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="icon-badge font-small-3"></i> Vehicle Model:</a></span>
                        <span class="d-block overflow-hidden">CAMRY</span>
                    </li>
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="icon-calendar font-small-3"></i> Year Of Make:</a></span>
                        <span class="d-block overflow-hidden">2009</span>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <ul class="no-list-style">
                    <li class="mb-2">
                        <span class="text-bold-500 primary"><a><i class="ft-droplet font-small-3"></i> Vehicle Color:</a></span>
                        <span class="d-block overflow-hidden">BLACK</span>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6 col-sm-12 text-left">
                <p class="lead">Payment Methods:</p>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <td>Payment Type:</td>
                                    <td class="text-right">ePIN: <strong>6187318606670824</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <p class="lead">Total due</p>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Sub Total</td>
                                <td class="text-right">₦ 15,000.00</td>
                            </tr>
                            <tr>
                                <td>Surcharge</td>
                                <td class="text-right">₦ 0.00</td>
                            </tr>
                            <tr>
                                <td class="text-bold-800">Total</td>
                                <td class="text-bold-800 text-right">₦ 15,000.00</td>
                            </tr>
                            <tr class="bg-grey bg-lighten-4">
                                <td class="text-bold-800">Total Due</td>
                                <td class="text-bold-800 text-right">₦ 15,000.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-template>

{{-- <!DOCTYPE html>
<html>
<head>
    <title>Generate QR Code</title>
</head>
<body>
    <img src="{{ qrCode('https://example.com') }}"/>
    <form action="/tester" method="GET">
        <label for="url">Enter URL:</label>
        <input type="text" id="url" name="url" placeholder="https://example.com">
        <input type="submit" value="Generate QR Code"/>
    </form>

    @if(request()->has('url'))
        <h2>QR Code for {{ request()->input('url') }}</h2>
        <img src="{{ qrCode(request()->input('url')) }}" alt="QR Code">
    @endif
</body>
</html> --}}
