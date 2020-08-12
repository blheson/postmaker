<?php
    include "system/initiate.php";
    $design_template="assets/images/card-greeting.png";
    include "includes/header.php";
?>

<main class="container" style="padding:2rem">
    
    <!-- show template type -->
    <section class="template">
        <div class="row"> 
            <h3>Templates</h3>
            <div class="col-md-4 card">
           
                <div class="default_template">
                    <a href="templates/plain.php">
                        <img src="<?=$design_template;?>" alt="<?=basename($design_template);?>" width="300">
                    </a>
                </div>
            </div>
            <div class="col-md-4 card">
           
           <div class="default_template">
               
               <a href="templates/plain.php">
                   <img src="<?=$design_template;?>" alt="<?=basename($design_template);?>" width="300">
               </a>
           </div>
       </div>
        </div>
    </section>
</main>
<?php
    include "includes/footer.php";
?>
