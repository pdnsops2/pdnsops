<?php
/**
 * User: Pascal Brewing
 * Date: 15.09.13
 * Time: 18:36
 * @package ${DIR}.${NAME}
 * Class ${NAME}
 */
?>
<?php if(!empty($items)) : ?>
    <ul class="<?php echo $type ?>">
    <?php foreach($items as $item) : //CVarDumper::dump($item,10,true);?>
        <li>
            <?php echo BSHtml::link($item[0],$item[1],$item[2]) ?>
            <?php if(isset($item[2]['badge'])) : ?>
                <strong><?php echo $item[2]['badge'] ?></strong>
            <?php endif; ?>
        </li>
    <?php endforeach;?>
    </ul>
    <div class="divider"><span></span></div>
<?php endif; ?>