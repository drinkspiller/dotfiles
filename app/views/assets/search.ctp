<?
    App::Import('Vendor', 'utils');
    echo $javascript->	codeBlock   (	"var asset_folder = '" . ASSET_UPLOAD_PATH . "';",
                                        array('inline'=>false)
                                    );
    echo $javascript->	link(array  (	'jquery-1.4.2.min.js',
                                        'swfobject.js',
                                        'jquery.uploadify.v2.1.4.js',
                                        'jquery.cookie.js',
                                        'jquery.color.js',
                                        'thickbox.js',
					'jquery.colorize-1.4.0.js',
                                        'upload.js',
					'main.js'
                                    ),
                                    false
                            );
?>
<div class="search form">
    <?php echo $form->create(false, array('url' => '/assets/search'));?>
        <fieldset>
            <legend><?php __('Search');?></legend>
        <?php
            echo $form->input('keywords', array('label'=> 'Keywords'));
        ?>
        </fieldset>
    <?php echo $form->end('Submit');?>
    
    <? if($data): ?>
    <?
    ///////////////////////
    // ASSETS
    ///////////////////////
    ?>
    <h2>Asset Names Containing '<?= $search_term ?>'</h2>
    <table cellpadding="0" cellspacing="0" id="assets_tbl" class="alt_color_table">
    <tr>
        <th>Asset Name</th>
        <th>Collection</th>
        <th>Created</th>
        <th>Uploaded By</th>
    </tr>
    <?php
    $i = 0;
    foreach ($asset_results as $asset):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
    ?>
        <tr<?php echo $class;?>>
            <td class="filename">
                <?
                    $pathInfo = pathinfo($asset['Asset']['name']);
                    $basename = $pathInfo['basename'];
                ?>
                <?php echo $html->image('asset_icons/32/' . getFileExtension($asset['Asset']['name']) . '.png', array('class'=>'document_icon')) .
                                                                        $html->link     (__($basename, true), 	array   ('controller'=> 'assets',
                                                                                                                                    'action'=>'view',
                                                                                                                                    $asset['Asset']['id'],
                                                                                                                                    $asset['Collection']['access_code']
                                                                                                                    ), 
                                                                                            array   (   'class'=>'filename_list',
                                                                                                        'title'=> $asset['Collection']['name'] . ": " . $basename
                                                                                                    )
                                                                                        );
                ?>
            </td>
            <td>
                <?php echo $html->link  (__( $asset['Collection']['name'], true), 	array   (   'controller'=> 'collections',
                                                                            'action'=>'view',
                                                                            $asset['Collection']['id'],
                                                                            $asset['Collection']['access_code']
                                                                        )
                                        );
                ?>
            </td>
            <td>
                <?php echo date("n/j/Y  g:ia" , strtotime($asset['Asset']['created'])); ?>
            </td>
            <td>
                 <?php if($asset['Admin']['id']):?>
                    <?php echo $html->link($asset['Admin']['fname'] . " " . $asset['Admin']['lname'], 'mailto:' . $asset['Admin']['email'] . "?subject=Collection: " . $asset['Collection']['name']); ?>
                <?php else: ?>
                    Guest
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
    
    <?
    ///////////////////////
    // COLLECTIONS
    ///////////////////////
    ?>
    <h2>Collection Names Containing '<?= $search_term ?>'</h2>
    <table cellpadding="0" cellspacing="0" id="assets_tbl" class="alt_color_table">
    <tr>
        <th>Collection</th>
        <th>Created</th>
    </tr>
    <?php
    $i = 0;
    foreach ($collection_results as $collection):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
    ?>
        <tr<?php echo $class;?>>
            <td class="filename">
                <?php echo $html->link  (__( $collection['Collection']['name'], true), 	array   (   'controller'=> 'collections',
                                                                            'action'=>'view',
                                                                            $collection['Collection']['id'],
                                                                            $collection['Collection']['access_code']
                                                                        )
                                        );
                ?>
            </td>
            <td>
                <?php echo date("n/j/Y  g:ia" , strtotime($collection['Collection']['created'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
    
    <?
    ///////////////////////
    // TAGS
    ///////////////////////
    ?>
    <h2>Collections Tagged '<?= $search_term ?>'</h2>
    <table cellpadding="0" cellspacing="0" id="assets_tbl" class="alt_color_table">
    <tr>
        <th>Collection</th>
        <th>Created</th>
    </tr>
    <?php
    $i = 0;
    foreach ($tag_results as $collection):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
    ?>
        <tr<?php echo $class;?>>
            <td class="filename">
                <?php echo $html->link  (__( $collection['collections']['name'], true), 	array   (   'controller'=> 'collections',
                                                                            'action'=>'view',
                                                                            $collection['collections']['id'],
                                                                            $collection['collections']['access_code']
                                                                        )
                                        );
                ?>
            </td>
            <td>
                <?php echo date("n/j/Y  g:ia" , strtotime($collection['collections']['created'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
    <?
    //debug($tag_results);
    //$collection_results
    //$asset_results
    //$tag_results
    
    ?>
    <? endif; ?>
</div>