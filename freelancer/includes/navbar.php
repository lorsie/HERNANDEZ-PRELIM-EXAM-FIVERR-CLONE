<?php
require_once __DIR__ . '/../classloader.php';

$categories = $categoryObj->getCategories();
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -1px;
}
</style>

<script>
$(document).ready(function(){
  $('.dropdown-submenu a.dropdown-toggle').on("click", function(e){
    e.preventDefault();
    e.stopPropagation(); 

    $(this).closest('.dropdown-menu').find('.dropdown-menu').removeClass('show');
    
    $(this).next('.dropdown-menu').toggleClass('show');
  });

  $('.dropdown').on("hidden.bs.dropdown", function(){
    $(this).find('.dropdown-menu.show').removeClass('show');
  });
});
</script>

<nav class="navbar navbar-expand-lg navbar-dark p-4" 
     style="background: linear-gradient(90deg, #74512D, #B87C4C);">
  <a class="navbar-brand" href="index.php">Freelancer Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">

      <li class="nav-item">
        <a class="nav-link" href="your_proposals.php">Your Proposals</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="offers_from_clients.php">Offers From Clients</a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="categoriesDropdown">
          <?php foreach ($categories as $category): ?>
            <?php $subcategories = $subcategoryObj->getSubcategoriesByCategory($category['category_id']); ?>
            
            <?php if (!empty($subcategories)): ?>
              <div class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="#">
                  <?php echo htmlspecialchars($category['category_name']); ?>
                </a>
                <div class="dropdown-menu">
                  <?php foreach ($subcategories as $sub): ?>
                    <a class="dropdown-item" href="index.php?subcategory=<?php echo $sub['subcategory_id']; ?>">
                      <?php echo htmlspecialchars($sub['subcategory_name']); ?>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php else: ?>
              <a class="dropdown-item" href="#">
                <?php echo htmlspecialchars($category['category_name']); ?>
              </a>
            <?php endif; ?>

          <?php endforeach; ?>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a>
      </li>
    </ul>
  </div>
</nav>
