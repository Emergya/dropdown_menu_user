<div class="user-login">
  <div class="main-wrapper">
    <div class="user-info">
      <?php if (isset($user['image'])): ?>
        <div class="user-image">
          <?php print($user['image']); ?>
        </div>
      <?php endif; ?>
      <?php if (isset($user['name'])): ?>
        <div class="user-name">
          <span><?php print($user['name']); ?></span>
        </div>
      <?php endif; ?>
    </div>

    <div class="user-settings">
      <div><?php print t('Settings'); ?></div>
    </div>
  </div>
</div>