<div class="listing-item" data-business="<?= $business_item_data; ?>">
  <a target="_blank" class="top" href="<?= $business_item_url; ?>">
    <img src="<?= $business_item_img; ?>" alt="<?= $business_item_name; ?>">
  </a>

  <div class="middle">
    <h3 style="text-transform: capitalize;">
      <a target="_blank" href="<?= $business_item_url; ?>" style="color: #000;">
        <?= $business_item_name; ?>
      </a>
    </h3>

    <p><?= htmlspecialchars(limitStr($business_item_address)); ?></p>

    <div class="stats">
      <p>
        <ion-icon name="wallet-outline"></ion-icon>
        Desde $<?= number_format($business_item_price, 2); ?>
      </p>

      <p>
        <?php if ($business_item_capacity > 0) : ?>
          <ion-icon name="person-outline"></ion-icon>
          <?= $business_item_capacity; ?> hasta <?= $business_item_max_capacity; ?>
        <?php endif; ?>
      </p>
    </div>

    <!-- <p class="primary">
      Consigue descuento del 10% con el código FGSS84
    </p> -->
  </div>

  <div class="bottom">
    <a target="_blank" href="http://www.facebook.com/sharer/sharer.php?p[url]=<?= $business_item_url ?>&p[title]=<?= $business_item_name ?>">
      <ion-icon name="arrow-redo"></ion-icon>
      Compartir
    </a>

    <a target="_blank" href="<?= $business_item_url; ?>">
      <ion-icon name="information-circle"></ion-icon>
      Información
    </a>
  </div>
</div>