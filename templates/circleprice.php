<?php
$dir = '../';
include $dir . "system/initiate.php";

use Controller\Template\PriceTag as Price;
$squareImage = new Price();
$newImagePath = "assets/images/render/";
$defaultImage = "assets/images/blank_image.png";
$designTemplate = "assets/images/templates/circleprice/circle-price-tag.png";

if (isset($_POST['contact'])) {
    $post = $_POST;

    $post['logo_details'] = $_FILES['logo'];
    $post['product_details'] = $_FILES['product'];
    $imageLink = $squareImage->priceTag($post, 30);
}

include $dir . "includes/header.php";
?>

<main class="container" style="padding: 2rem;box-shadow: 0 0 10px 1px #ececec;">
    <section class="header">
        <h1 class="text-center">Price Tag Template</h1>
    </section>
    <center>
        <div class="breadcrumb">
            <a href="<?= $dir ?>">home</a>
        </div>
    </center>
    <!-- show template type -->
    <section class="template">
        <div class="row">
            <div class="col-md-4">
                <h3 class="title template">Template <i class="fa fa-caret-down pull-right"></i></h3>
                <div class="default_template" style="display: none;">
                    <img src="<?= $dir . $designTemplate; ?>" alt="<?= basename($designTemplate); ?>" loading="lazy">
                </div>
            </div>
            <div class="col-md-4">
                <h3 class="title">Fill form to edit design</h3>
                <div class="card">

                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="">Add logo</label>

                            <input type="file" name="logo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Add Product</label>

                            <input type="file" name="product" class="form-control" required>
                            <small>Image should be a square</small>
                        </div>
                        <div class="form-group">
                            <label for="">Choose Font</label>
                            <select name="font" class="form-control">
                                <?php
                                $fonts = $squareImage->font->get_font();

                                foreach ($fonts as $key => $font) :

                                ?>
                                    <option value="<?= $font ?>"><?= $key ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Price</label>
                            <input type="text" name="price" class="form-control" value="N5,000" required>

                        </div>
                        <div class="form-group">
                            <label for="">Contact</label>
                            <input type="text" name="contact" class="form-control" value="Company Contact | 0802 000 0000" required>

                        </div>

                        <div class="form-group hidden-settings background_colour" style="display: none;">
                            <label for="">Change font colour</label>
                            <input type="color" name="color" class="form-control" value="#ffffff" required>

                        </div>

                        <div class="row">
                            <div class="col-md-2 col-sm-2 col-xs-1 pull-left">
                                <input type="checkbox" name="check">
                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-11">
                                <label for="">Check to see advance option</label>
                            </div>
                        </div>
                        <input type="hidden" name="defaultImage" value="<?= $dir . $defaultImage ?>">
                        <input type="hidden" name="newImagePath" value="<?= $dir . $newImagePath; ?>">
                        <input type="hidden" name="designTemplate" value="<?= $dir . $designTemplate; ?>">
                        <input type="submit" class="btn btn-submit">
                    </form>
                </div>
            </div>

            <div id="render" class="col-md-4">

                <h3 class="title">Final Render</h3>
                <!-- render finished image -->
                <?php
                if (isset($_POST['contact'])) :
                ?>
                    <div>
                        <div class="render">
                            <img src="<?= $imageLink; ?>" alt="rendered image" value="<?= $_POST['price'] ?>" width="100%" loading="lazy">
                        </div>

                        <div class="form-group">
                            <div>
                                <a href="<?= $imageLink; ?>" download><button class="btn btn-submit">
                                        Download Image
                                    </button></a>

                            </div>

                        </div>
                    </div>
                <?php
                endif;
                ?>


            </div>
        </div>
    </section>
</main>

<?php
include $dir . "includes/footer.php";
?>
<script>
    // let text = document.querySelector("input[name=text]");
    // text.addEventListener("input", () => {
    //     let submit = document.querySelector("input[type=submit]");
    //     if (text.value.length > 60) {
    //         let status = document.querySelector(".status_input");
    //         status.innerText = 'Character should not be more than 60';
    //         submit.disabled = true;
    //     } else {
    //         submit.disabled = false;
    //         status.innerText = '';
    //     }
    // })

    // let check = document.querySelector("input[name=check]");
    // let hidden_settings = document.querySelector(".hidden-settings");
    // check.addEventListener("click", () => {

    //     if (hidden_settings.classList.contains("d-none")) {
    //         // hidden_settings.style.display = "block";
    //         hidden_settings.classList.remove("d-none");
    //         // console.log(hidden_settings.style.display)

    //     } else {
    //         hidden_settings.classList.add("d-none");
    //         // console.log(hidden_settings.style.display)
    //     }

    // })
</script>