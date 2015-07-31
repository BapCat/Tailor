<<?= '?php' ?>

class Loops {
<?php for($i = 0; $i < $amount; $i++): ?>
  public function test<?= $i ?>() {
    return 'test<?= $i ?>';
  }
<?php endfor; ?>
}
