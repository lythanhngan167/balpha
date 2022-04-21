<?php
$curDate = '';

?>
<?php foreach ($rows as $item):
    $createdDate = EshopHelper::renderDate($item['created_date']);
    if ($createdDate != $curDate):
        $curDate = $createdDate;
        ?>
        <!-- Separator -->
        <div class="separator text-muted">
            <time><?= $createdDate ?></time>
        </div>

    <?php endif; ?>
    <!-- /Separator -->
    <!-- Panel -->
    <article class="panel panel-success">

        <!-- Icon -->
        <?php if ($item['created_by']): ?>
            <div class="user_icon">
                <svg class="svg-next-icon svg-next-icon-size-40" width="16" height="16">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                        <g id="Layer_2" data-name="Layer 2">
                            <g id="Layer_1-2" data-name="Layer 1">
                                <path xmlns="http://www.w3.org/2000/svg" fill="#3c94d1"
                                      d="M32,63.5A31.5,31.5,0,1,1,63.5,32,31.54,31.54,0,0,1,32,63.5Z"></path>
                                <path xmlns="http://www.w3.org/2000/svg" fill="#fff"
                                      d="M32,1A31,31,0,1,1,1,32,31,31,0,0,1,32,1m0-1A32,32,0,1,0,64,32,32,32,0,0,0,32,0Z"></path>
                                <path xmlns="http://www.w3.org/2000/svg" fill="#fff"
                                      d="M32,7.8A11.94,11.94,0,1,1,20.06,19.75,11.93,11.93,0,0,1,32,7.8Z"></path>
                                <path xmlns="http://www.w3.org/2000/svg" fill="#fff"
                                      d="M32,59.34A28.67,28.67,0,0,1,8.11,46.52C8.23,38.59,24,34.25,32,34.25s23.77,4.34,23.89,12.26A28.67,28.67,0,0,1,32,59.34Z"></path>
                            </g>
                        </g>
                    </svg>
                </svg>
            </div>
        <?php else: ?>
            <div class="icon">
            </div>
        <?php endif; ?>
        <!-- /Icon -->

        <!-- Heading -->
        <div class="panel-heading">
            <?php if ($item['created_by']): ?>
                <b><?= $item['user_name'] ?></b>:
            <?php endif; ?>
            <?= $item['message'] ?>

            <span><time><?= EshopHelper::renderDate($item['created_date'], 'H:i') ?></time></span>
        </div>
        <!-- /Heading -->

        <!-- Body -->

        <div class="panel-body <?= $item['content'] ? 'have_background' : '' ?>">
            <?= $item['content'] ?>
        </div>

        <!-- /Body -->


    </article>
    <!-- /Panel -->

<?php endforeach; ?>
