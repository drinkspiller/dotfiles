<?php 
if (isset($filePresent)): 
         uses('model' . DS . 'connection_manager'); 
        $db = ConnectionManager::getInstance(); 
         $connected = $db->getDataSource('default'); 
?> 
<p> 
        <?php 
                if ($connected->isConnected()): 
                        echo '<span class="notice success">'; 
                                 __('Cake is able to connect to the database.'); 
                        echo '</span>'; 
                else: 
                        echo '<span class="notice">'; 
                                __('Cake is NOT able to connect to the database.'); 
                        echo '</span>'; 
                endif; 
        ?> 
</p> 
<?php endif;?> 