<?php if($sf_request->getCookie('offline') == "enabled"): ?>
  <form method="post" action="<?php echo url_for('@cookie_reset') ?>">
    <input type="hidden" name="key" value="offline" />
    <input type="submit" value="Désactiver le mode hors-ligne" />
  </form>
<?php else: ?>
  <form method="post" action="<?php echo url_for('@cookie_set') ?>">
    <input type="hidden" name="key" value="offline" />
    <input type="submit" value="Activer le mode hors-ligne" />
  </form>
<?php endif ?>