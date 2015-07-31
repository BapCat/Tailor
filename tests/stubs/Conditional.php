<<?= '?php' ?>

class Conditional {
<?php if($do_it): ?>
  public function test() {
    return 'test';
  }
<?php endif; ?>
}
