<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if ($userObj->isAdmin()) {
  header("Location: ../fiverr_admin/index.php");
} 

if ($userObj->isFreelancer()) {
  header("Location: ../freelancer/index.php");
} 


if (isset($_GET['subcategory'])) {
    $subId = (int) $_GET['subcategory'];
    $proposals = $proposalObj->getProposalsBySubcategory($subId);
} else {
    $proposals = $proposalObj->getProposals();
}
?>
<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <style>
      body {
        font-family: "Arial";
        background: #F6F1E9;
      }
    </style>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid">
      <div class="display-4 mt-4 text-center">Welcome, <span class="text-success"><?php echo $_SESSION['username']; ?></span>!</div>
      <h4 class="text-center">Double-click to edit your offers and then press enter to save.</h4>
      <div class="text-center">
      <?php if (isset($_SESSION['message']) && isset($_SESSION['status'])): ?>
        <div id="popupMessage" class="d-flex justify-content-center align-items-center"
             style="position: fixed; top:0; left:0; width:100%; height:100%; 
                    background: rgba(0,0,0,0.5); z-index:1050;"
             onclick="this.remove()">
          <div class="p-4 bg-white rounded shadow text-center" 
               onclick="event.stopPropagation()">
            <h4 style="color: <?= $_SESSION['status']=="200" ? "green" : "red"; ?>">
              <?= $_SESSION['message']; ?>
            </h4>
          </div>
        </div>
      <?php 
        unset($_SESSION['message']);
        unset($_SESSION['status']);
      endif; ?>
      </div>
      
      <div class="row justify-content-center">
        <div class="col-md-12">
          <?php foreach ($proposals as $proposal) { ?>
          <div class="card shadow mt-4 mb-4">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h2><a href="other_profile_view.php?user_id=<?php echo $proposal['user_id'] ?>"><?php echo $proposal['username']; ?></a></h2>
                  <img src="<?php echo '../images/'.$proposal['image']; ?>" class="img-fluid" alt="">
                  <p class="mt-4 mb-4"><?php echo $proposal['description']; ?></p>
                  <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']);?> PHP</i></h4>
                </div>
                <div class="col-md-6">
                  <div class="card" style="height: 600px;">
                    <div class="card-header"><h2>All Offers</h2></div>
                    <div class="card-body overflow-auto">

                      <?php $getOffersByProposalID = $offerObj->getOffersByProposalID($proposal['proposal_id']); ?>
                      <?php foreach ($getOffersByProposalID as $offer) { ?>
                      <div class="offer">
                        <h4><?php echo $offer['username']; ?> <span class="text-primary">( <?php echo $offer['contact_number']; ?> )</span></h4>
                        <small><i><?php echo $offer['offer_date_added']; ?></i></small>
                        <p><?php echo $offer['description']; ?></p>

                        <?php if ($offer['user_id'] == $_SESSION['user_id']) { ?>
                          <form action="core/handleForms.php" method="POST">
                            <div class="form-group">
                              <input type="hidden" class="form-control" value="<?php echo $offer['offer_id']; ?>" name="offer_id" >
                              <input type="submit" class="btn btn-danger" value="Delete" name="deleteOfferBtn">
                            </div>
                          </form>

                          <form action="core/handleForms.php" method="POST" class="updateOfferForm d-none">
                            <div class="form-group">
                              <label for="#">Description</label>
                              <input type="text" class="form-control" value="<?php echo $offer['description']; ?>" name="description">
                              <input type="hidden" class="form-control" value="<?php echo $offer['offer_id']; ?>" name="offer_id" >
                              <input type="submit" class="btn btn-primary form-control" name="updateOfferBtn">
                            </div>
                          </form>
                        <?php } ?>
                        <hr>
                      </div>
                      <?php } ?>
                    </div>
                    <div class="card-footer">
                      <form action="core/handleForms.php" method="POST">
                        <div class="form-group">
                          <label for="#">Description</label>
                          <input type="text" class="form-control" name="description">
                          <input type="hidden" class="form-control" name="proposal_id" value="<?php echo $proposal['proposal_id']; ?>">
                          <input type="submit" class="btn btn-primary float-right mt-4" name="insertOfferBtn"> 
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <script>
       $('.offer').on('dblclick', function (event) {
          var updateOfferForm = $(this).find('.updateOfferForm');
          updateOfferForm.toggleClass('d-none');
        });
    </script>
  </body>
</html>