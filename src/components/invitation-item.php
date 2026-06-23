<div class="invitation-item" data-invitation=<?= $invitation_item_data; ?> data-link="<?= $invitation_item_url; ?>" data-image="<?= $invitation_item_img; ?>" data-edit="<?= BASE_URL . '/editar-invitacion/' . $invitation_item_slug; ?>">
  <div class="top">
    <div style="flex: 1;">
      <img src="<?= $invitation_item_img; ?>" alt="<?= $invitation_item_title; ?>">
    </div>

    <div class="actions mobile">
      <button class="btn btn-danger btn-delete-invitation" data-invitation=<?= $invitation_item_data; ?>>
        <ion-icon name="trash"></ion-icon>
      </button>

      <a class="btn btn-primary" data-invitation=<?= $invitation_item_data; ?> href="<?= BASE_URL . '/editar-invitacion/' . $invitation_item_slug; ?>">
        <ion-icon name="pencil"></ion-icon>
      </a>

      <a title="Visualizar invitación" target="_blank" class="btn btn-default" href="<?= $invitation_item_url; ?>">
        <ion-icon title="Visualizar invitación" name="eye"></ion-icon>
      </a>

      <a class="btn btn-secondary btn-shared-invitation" data-link="<?= $invitation_item_url; ?>" href="javascript:void(0)">
        <ion-icon title="Copiar link" name="arrow-redo"></ion-icon>
      </a>
    </div>
  </div>

  <div class="bottom">
    <h4><?= $invitation_item_title; ?></h4>
    <h5><?= $invitation_item_subtitle; ?></h5>
  </div>
</div>