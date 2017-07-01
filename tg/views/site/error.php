<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$css = <<<'CSS'
.nb-error {
  margin: 0 auto;
  text-align: center;
  max-width: 480px;
  padding: 60px 30px;
}

.nb-error .error-code {
  color: #2d353c;
  font-size: 96px;
  line-height: 100px;
}

.nb-error .error-desc {
  font-size: 12px;
  color: #647788;
}
CSS;

$this->registerCss($css);

$this->title = $name;
?>
<div class="nb-error">
    <div class="error-code">404</div>
    <h3 class="font-bold"><?= Html::encode($this->title) ?></h3>

    <div class="error-desc">
        <?= nl2br(Html::encode($message)) ?>
        <ul class="list-inline text-center text-sm">
            <li class="list-inline-item"><a href="#" class="text-muted">Go to App</a>
            </li>
        </ul>
    </div>
</div>