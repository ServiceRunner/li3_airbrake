# li3_airbake

Simple [Airbrake.io](http://airbrake.io) integration for [Lithium](http://lithify.me).

## Installation

Load `li3_airbake` by updating `config/bootstrap/libraries.php`:

```php
<?php

// ... snip ...

// Airbrake.io
Libraries::add('li3_airbake', array('apiKey' => '-> INSERT API KEY <-'));
```