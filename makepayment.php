<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Make Payment - Indoor Hub</title>
  <link href="css/common.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="fw-bold mb-4 text-center">Make Payment</h4>

          <form>
            <div class="mb-3">
              <label for="bookingId" class="form-label">Booking ID</label>
              <input type="text" class="form-control" id="bookingId" placeholder="Enter your Booking ID">
            </div>

            <div class="mb-3">
              <label for="amount" class="form-label">Amount (Rs.)</label>
              <input type="number" class="form-control" id="amount" placeholder="Enter Amount">
            </div>

            <div class="mb-3">
              <label for="method" class="form-label">Payment Method</label>
              <select class="form-select" id="method">
                <option selected disabled>Select Payment Method</option>
                <option>Credit Card</option>
              </select>
            </div>

            <!-- New Fields Start -->
            <div class="mb-3">
              <label for="cardholder" class="form-label">Cardholder Name</label>
              <input type="text" class="form-control" id="cardholder" placeholder="Enter Cardholder Name">
            </div>

            <div class="mb-3">
              <label for="cardNumber" class="form-label">Card Number</label>
              <input type="text" class="form-control" id="cardNumber" placeholder="XXXX-XXXX-XXXX-XXXX">
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="expiryDate" class="form-label">Expiry Date</label>
                <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY">
              </div>
              <div class="col-md-6 mb-3">
                <label for="cvv" class="form-label">CVV</label>
                <input type="password" class="form-control" id="cvv" placeholder="***">
              </div>
            </div>
            <!-- New Fields End -->

            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-credit-card me-2"></i>Pay Now
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<script src="js/common.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
