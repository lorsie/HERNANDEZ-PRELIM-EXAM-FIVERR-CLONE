<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if ($userObj->isAdmin()) {
  header("Location: ../fiverr_admin/index.php");
} 

if ($userObj->isClient()) {
  header("Location: ../client/index.php");
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: "Arial";
      background: #F6F1E9;
    }
    #proposalForm {
      display: none;
    }

    .proposals-list {
      max-width: 900px; 
      margin: 30px auto;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container-fluid">
    <div class="display-4 mt-4 text-center">Welcome, <span class="text-success"><?php echo $_SESSION['username']; ?></span>!</div>
    <h4 class="text-center">Submit new proposals and view existing proposals below.</h4>

    <div class="text-center mt-4 mb-4">
      <button id="toggleFormBtn" class="btn btn-success btn-lg">
        Add Proposal
      </button>
    </div>

    <div class="container">
      <div id="proposalForm" style="display: none; width: 100%;">
        <div class="card mt-4 mb-4" style="max-width: 600px; margin: 0 auto;">
          <div class="card-body">
            <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                <?php  
                if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                  if ($_SESSION['status'] == "200") {
                    echo "<h5 class='text-success'>{$_SESSION['message']}</h5>";
                  } else {
                    echo "<h5 class='text-danger'>{$_SESSION['message']}</h5>"; 
                  }
                  unset($_SESSION['message']);
                  unset($_SESSION['status']);
                }
                ?>
                <h3 class="mb-4">Add Proposal</h3>
                <div class="form-group">
                  <label>Description</label>
                  <input type="text" class="form-control" name="description" required>
                </div>
                <div class="form-group">
                  <label>Minimum Price</label>
                  <input type="number" class="form-control" name="min_price" required>
                </div>
                <div class="form-group">
                  <label>Maximum Price</label>
                  <input type="number" class="form-control" name="max_price" required>
                </div>
                <div class="form-group">
                  <label>Image</label>
                  <input type="file" class="form-control" name="image" required>
                </div>
                <div class="form-group">
                  <label>Category</label>
                  <select class="form-control" name="category_id" id="category" required>
                    <option value="">Select Category</option>
                    <?php 
                      $categories = $categoryObj->getCategories(); 
                      foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id']; ?>"><?= $cat['category_name']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Subcategory</label>
                  <select class="form-control" name="subcategory_id" id="subcategory" required>
                    <option value="">Select Subcategory</option>
                  </select>
                </div>
                <div class="form-group text-right">
                  <input type="submit" class="btn btn-primary" name="insertNewProposalBtn" value="Submit">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

      <div class="proposals-list">
          <div class="col-md-12">
            <?php foreach ($proposals as $proposal) { ?>
            <div class="card shadow mt-4 mb-4">
              <div class="card-body">
                <h2>
                  <a href="other_profile_view.php?user_id=<?php echo $proposal['user_id']; ?>">
                    <?php echo $proposal['username']; ?>
                  </a>
                </h2>
                <img src="<?php echo '../images/' . $proposal['image']; ?>" class="img-fluid" alt="">
                <p class="mt-4"><i><?php echo $proposal['proposals_date_added']; ?></i></p>
                <p class="mt-2"><?php echo $proposal['description']; ?></p>
                <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i></h4>
                <div class="float-right">
                  <a href="#">Check out services</a>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
    </div>

  <script>
  $(document).ready(function(){
    $("#toggleFormBtn").click(function(){
      $("#proposalForm").slideToggle();
    });

    $("#category").change(function(){
      var category_id = $(this).val();
      if(category_id != ""){
        $.ajax({
          url: "core/handleForms.php",
          method: "POST",
          data: { fetchSubcategories: 1, category_id: category_id },
          success: function(data){
            $("#subcategory").html(data);
          }
        });
      } else {
        $("#subcategory").html('<option value="">Select Subcategory</option>');
      }
    });
  });
  </script>

</body>
</html>