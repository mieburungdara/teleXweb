<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Top Up TeleX Credits</h4>
                </div>
                <div class="card-body">
                    <p class="lead">Purchase Credits to buy folders and other exciting content!</p>
                    <hr>

                    <h5 class="mb-3">Available Credit Packages:</h5>
                    <div class="list-group mb-4">
                        <?php 
                        $packages = [
                            ['usd_amount' => 5, 'credits' => 500, 'bonus_percentage' => 0],
                            ['usd_amount' => 10, 'credits' => 1100, 'bonus_percentage' => 10],
                            ['usd_amount' => 20, 'credits' => 2300, 'bonus_percentage' => 15],
                            ['usd_amount' => 50, 'credits' => 6000, 'bonus_percentage' => 20]
                        ];
                        foreach ($packages as $package): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo $package['credits']; ?> Credits</strong> for $<?php echo $package['usd_amount']; ?> USD
                                    <?php if ($package['bonus_percentage'] > 0): ?>
                                        <span class="badge bg-success ms-2">+<?php echo $package['bonus_percentage']; ?>% Bonus!</span>
                                    <?php endif; ?>
                                </div>
                                <a href="#manualPaymentInstructions" class="btn btn-sm btn-outline-primary">Choose</a>
                            </li>
                        <?php endforeach; ?>
                    </div>

                    <h5 class="mb-3" id="manualPaymentInstructions">How to Top Up Manually:</h5>
                    <div class="alert alert-info" role="alert">
                        <p>To top up your TeleX Credits, please follow these steps:</p>
                        <ol>
                            <li>Select your desired package from the list above.</li>
                            <li>Make a payment for the exact USD amount to our bank account or via other agreed-upon manual methods.</li>
                            <li><strong>Important:</strong> Include your User ID (<code><?php echo $user_id; ?></code>) in the payment reference/description.</li>
                            <li>Send a confirmation of your payment (e.g., screenshot of transfer) along with your User ID to our admin: <strong>@teleXweb_admin</strong> (Telegram username).</li>
                            <li>Once your payment is verified, the Credits will be manually added to your account. This may take up to 24 hours.</li>
                        </ol>
                        <p>Thank you for your patience!</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
