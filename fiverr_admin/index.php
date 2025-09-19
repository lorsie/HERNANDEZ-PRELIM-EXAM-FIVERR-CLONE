<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit;
}

if ($userObj->isClient()) {
  header("Location: ../client/index.php");
} 

if ($userObj->isFreelancer()) {
  header("Location: ../freelancer/index.php");
} 

$allCategories = $categoryObj->getCategories();
$selectedCategoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$subcategories = $selectedCategoryId ? $subcategoryObj->getSubcategoriesByCategory($selectedCategoryId) : [];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body { 
      font-family: "Arial";
      background: #F6F1E9;
     }
    
    .category-card { cursor: pointer; }

    .card-header {
      background-color: #965f37;
    }
  </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container-fluid mt-4">
  <div class="display-4 mt-4 text-center">Welcome, <span class="text-success"><?php echo $_SESSION['username']; ?></span>!</div>
  <h4 class="text-center">Manage categories and subcategories here.</h4>

  <div class="text-center">
    <?php if (isset($_SESSION['message']) && isset($_SESSION['status'])): ?>
      <div id="popupMessage" class="d-flex justify-content-center align-items-center"
           style="position: fixed; top:0; left:0; width:100%; height:100%; 
                  background: rgba(0,0,0,0.5); z-index:1050;"
           onclick="this.remove()">
        <div class="p-4 bg-white rounded shadow text-center" onclick="event.stopPropagation()">
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

  <div class="row justify-content-center mt-4">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header text-white">
          <h4>Add New Category</h4>
        </div>
        <div class="card-body">
          <form action="core/handleForms.php" method="POST">
            <div class="form-group">
              <label>Category Name</label>
              <input type="text" name="category_name" class="form-control" required>
            </div>
            <button type="submit" name="insertCategoryBtn" class="btn btn-success">Create Category</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row justify-content-center mt-4">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header text-white">
          <h4>Add New Subcategory</h4>
        </div>
        <div class="card-body">
          <form action="core/handleForms.php" method="POST">
            <div class="form-group">
              <label>Select Category</label>
              <select name="category_id" class="form-control" required>
                <option value="">Select</option>
                <?php foreach ($allCategories as $cat): ?>
                  <option value="<?= $cat['category_id']; ?>"><?= $cat['category_name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Subcategory Name</label>
              <input type="text" name="subcategory_name" class="form-control" required>
            </div>
            <button type="submit" name="insertSubcategoryBtn" class="btn btn-success">Create Subcategory</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row justify-content-center mt-5">
    <div class="col-md-8">
      <form method="GET" action="index.php" class="form-inline justify-content-center">
        <label class="mr-2">Filter by Category</label>
        <select name="category_id" class="form-control mr-2">
          <option value="0">Show All</option>
          <?php foreach ($allCategories as $cat): ?>
            <option value="<?= $cat['category_id']; ?>" <?= $selectedCategoryId == $cat['category_id'] ? 'selected' : '' ?>>
              <?= $cat['category_name']; ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
      </form>
    </div>
  </div>

  <div class="row justify-content-center mt-4">
    <div class="col-md-8">
      <?php if ($selectedCategoryId > 0): 
          $category = $categoryObj->getCategoryById($selectedCategoryId);
          $subcategories = $subcategoryObj->getSubcategoriesByCategory($selectedCategoryId);  ?>
          <?php if (!empty($subcategories)): ?>
              <div class="card shadow mb-4">
                  <div class="card-header text-white">
                      <h4>Subcategories under <?= $category['category_name']; ?></h4>
                  </div>
                  <div class="card-body">
                      <ul class="list-group">
                          <?php foreach ($subcategories as $sub): ?>
                              <li class="list-group-item"><?= $sub['subcategory_name']; ?></li>
                          <?php endforeach; ?>
                      </ul>
                  </div>
              </div>
          <?php else: ?>
              <div class="alert alert-warning text-center">No subcategories found for this category.</div>
          <?php endif; ?>
      <?php else: ?>
          <div class="card shadow mb-4">
              <div class="card-header text-white"><h4>All Categories</h4></div>
              <div class="card-body">
                  <ul class="list-group">
                      <?php foreach ($allCategories as $cat): ?>
                          <li class="list-group-item"><?= $cat['category_name']; ?></li>
                      <?php endforeach; ?>
                  </ul>
              </div>
          </div>
      <?php endif; ?>
    </div>
  </div>

</div>
</body>
</html>