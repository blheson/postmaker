<div id="render" class="col-md-6">
    <!-- render finished image -->
    <div class="title_box">
        <h3 class="title">Final Render</h3>
    </div>
    <div>
        <div class="render">
            <img src="<?= $dir . $designTemplate; ?>" alt="<?= basename($designTemplate); ?>" loading="lazy">
        </div>

        <div class="form-group">
            <div class="downloadBox">
                <a href="<?= $dir . $designTemplate; ?>" download><button class="btn btn-submit">
                        Download Image
                    </button>
                </a>
            </div>

        </div>
    </div>

</div>