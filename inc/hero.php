<section class="hero hero__seasonal text-center">
    <div class="container text-white">
        <div class="align-content-center flex-column font-bold hero-content justify-content-center row">
            <div class="col-md-5">
                <h1>Seasonal & Special <br>
                Small Group Tours
                </h1>
                <p>Curated experiences. Limited dates.</p>
                <p>
                    Join our small-group tours to discover the best of Kansai countryside culture and seasonal beauty.
                </p>
            </div>
        </div>
    </div>
</section>
<?php if(isset($lang['hero_features'])): ?>
    <section class="hero-features bg-light pt-5 pb-5">
        <div class="container">
            <div class="row">
                <?php foreach($lang['hero_features'] as $feature){ ?>
                    <div class="col-12 col-md-2 flex-fill text-center font-m">
                        <i class="<?php echo $feature['icon']; ?> icon-primary font-lg mb-3 d-block"></i>
                        <strong><?php echo $feature['title']; ?></strong>
                        <p><?php echo $feature['text']; ?></p>
                    </div>
                <?php }; ?>
            </div>
        </div>
    </section>
<?php endif; ?>