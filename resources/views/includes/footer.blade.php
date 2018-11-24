<?php
$settings = \App\Helpers\General::get_settings();
?>
<footer>
    <div class="container">
        {{@$settings['copyright_cms']['value']}}
    </div>
</footer>