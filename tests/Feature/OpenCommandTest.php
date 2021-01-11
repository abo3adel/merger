<?php

it('will return error if file not exists', function () {
    $this->artisan('open lara/com.php')->expectsOutput('file com not found');
});
